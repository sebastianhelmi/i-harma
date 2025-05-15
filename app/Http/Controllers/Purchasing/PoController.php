<?php

namespace App\Http\Controllers\Purchasing;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\Po;
use App\Models\SiteSpb;
use App\Models\Spb;
use App\Models\Supplier;
use App\Models\WorkshopSpb;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PoController extends Controller
{
    public function index(Request $request)
    {
        $query = Po::with(['spb', 'supplier', 'creator'])
            ->when($request->search, function ($q) use ($request) {
                return $q->where(function ($query) use ($request) {
                    $query->where('po_number', 'like', "%{$request->search}%")
                        ->orWhere('company_name', 'like', "%{$request->search}%")
                        ->orWhereHas('supplier', function ($q) use ($request) {
                            $q->where('name', 'like', "%{$request->search}%");
                        });
                });
            })
            ->when($request->status, function ($q) use ($request) {
                return $q->where('status', $request->status);
            })
            ->latest('order_date');

        $pos = $query->paginate(10);
        $statuses = ['pending' => 'Pending', 'completed' => 'Selesai', 'cancelled' => 'Dibatalkan'];

        return view('purchasing.pos.index', compact('pos', 'statuses'));
    }
    public function create($spb_id)
    {
        $spb = Spb::with([
            'project',
            'requester',
            'task',
            'siteItems',
            'workshopItems'
        ])
            ->where('status', 'approved')
            ->where('status_po', 'pending')
            ->findOrFail($spb_id);

        $suppliers = Supplier::orderBy('name')->get();
        $hasItemsToOrder = false;

        // Process site items
        if ($spb->category_entry === 'site') {
            foreach ($spb->siteItems as $item) {
                $inventory = Inventory::where('item_name', $item->item_name)
                    ->where('item_category_id', $spb->item_category_id)
                    ->first();

                $item->inventory_qty = $inventory ? $inventory->quantity : 0;
                $item->available = $inventory && $inventory->quantity >= $item->quantity;
                $item->needed_quantity = $item->available ? 0 : $item->quantity - $item->inventory_qty;
                $item->inventory_unit_price = $inventory ? $inventory->unit_price : null;

                if (!$item->available) {
                    $hasItemsToOrder = true;
                }
            }
        } else {
            // Process workshop items
            foreach ($spb->workshopItems as $item) {
                $inventory = Inventory::where('item_name', $item->explanation_items)
                    ->where('item_category_id', $spb->item_category_id)
                    ->first();

                $item->inventory_qty = $inventory ? $inventory->quantity : 0;
                $item->available = $inventory && $inventory->quantity >= $item->quantity;
                $item->needed_quantity = $item->available ? 0 : $item->quantity - $item->inventory_qty;
                $item->inventory_unit_price = $inventory ? $inventory->unit_price : null;

                if (!$item->available) {
                    $hasItemsToOrder = true;
                }
            }
        }

        // Add action button for marking SPB as not required
        return view('purchasing.pos.create', compact('spb', 'suppliers', 'hasItemsToOrder'));
    }

    // Add new method to mark SPB as not required
    public function markAsNotRequired(Spb $spb)
    {
        if ($spb->status !== 'approved' || $spb->status_po !== 'pending') {
            return response()->json([
                'icon' => 'error',
                'title' => 'Gagal',
                'text' => 'SPB tidak dapat diubah statusnya.'
            ], 422);
        }

        try {
            DB::transaction(function () use ($spb) {
                $spb->update(['status_po' => 'not_required']);
            });

            return response()->json([
                'success' => true,
                'icon' => 'success',
                'title' => 'Berhasil',
                'text' => 'SPB telah ditandai tidak memerlukan PO.',
                'redirectTo' => route('purchasing.spbs.index')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'icon' => 'error',
                'title' => 'Gagal',
                'text' => 'Terjadi kesalahan saat mengubah status SPB.'
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'spb_id' => 'required|exists:spbs,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'order_date' => 'required|date',
            'estimated_usage_date' => 'required|date|after:order_date',
            'remarks' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.type' => 'required|in:site,workshop',
        ]);

        try {
            DB::beginTransaction();

            // Get supplier data
            $supplier = Supplier::findOrFail($validated['supplier_id']);

            // Create PO
            $po = Po::create([
                'po_number' => Po::generatePoNumber(),
                'spb_id' => $validated['spb_id'],
                'supplier_id' => $validated['supplier_id'],
                'company_name' => $supplier->name,
                'created_by' => Auth::id(),
                'order_date' => $validated['order_date'],
                'estimated_usage_date' => $validated['estimated_usage_date'],
                'status' => 'pending',
                'remarks' => $validated['remarks'],
                'total_amount' => 0
            ]);

            $totalAmount = 0;

            // Create PO Items
            foreach ($validated['items'] as $id => $item) {
                // Get SPB item based on type
                if ($item['type'] === 'site') {
                    $spbItem = SiteSpb::findOrFail($id);
                    $itemName = $spbItem->item_name;
                    $siteSpbId = $id;
                    $workshopSpbId = null;
                } else {
                    $spbItem = WorkshopSpb::findOrFail($id);
                    $itemName = $spbItem->explanation_items;
                    $siteSpbId = null;
                    $workshopSpbId = $id;
                }

                $totalPrice = $spbItem->quantity * $item['unit_price'];
                $totalAmount += $totalPrice;

                // Create PO Item
                $po->items()->create([
                    'spb_id' => $validated['spb_id'],
                    'site_spb_id' => $siteSpbId,
                    'workshop_spb_id' => $workshopSpbId,
                    'item_name' => $itemName,
                    'unit' => $spbItem->unit,
                    'quantity' => $spbItem->quantity,
                    'unit_price' => $item['unit_price'],
                    'total_price' => $totalPrice
                ]);
            }

            // Update PO total amount
            $po->update(['total_amount' => $totalAmount]);

            // Update SPB status
            Spb::findOrFail($validated['spb_id'])
                ->update(['status_po' => 'ordered']);

            DB::commit();

            return response()->json([
                'success' => true,
                'icon' => 'success',
                'title' => 'Berhasil',
                'text' => "PO {$po->po_number} berhasil dibuat",
                'redirectTo' => route('purchasing.pos.index')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('PO Creation Error: ' . $e->getMessage());
            return response()->json([
                'icon' => 'error',
                'title' => 'Gagal',
                'text' => 'Terjadi kesalahan saat membuat PO: ' . $e->getMessage()
            ], 500);
        }
    }

    public function complete(Po $po)
    {
        if ($po->status !== 'pending') {
            return back()->with('error', 'PO ini tidak dapat diselesaikan.');
        }

        $po->update(['status' => 'completed']);

        return back()->with('success', "PO {$po->po_number} telah diselesaikan.");
    }

    public function cancel(Po $po)
    {
        if ($po->status !== 'pending') {
            return back()->with('error', 'PO ini tidak dapat dibatalkan.');
        }

        $po->update(['status' => 'cancelled']);

        // Reset SPB status
        $po->spb->update(['status_po' => 'pending']);

        return back()->with('success', "PO {$po->po_number} telah dibatalkan.");
    }

    public function show(Po $po)
    {
        $po->load(['spb', 'supplier', 'creator', 'items']);
        return view('purchasing.pos.show', compact('po'));
    }
    public function edit(Po $po)
    {
        if ($po->status !== 'pending') {
            return redirect()
                ->route('purchasing.pos.show', $po)
                ->with('error', 'Hanya PO dengan status pending yang dapat diedit.');
        }

        $po->load(['spb', 'supplier', 'creator', 'items']);
        $suppliers = Supplier::orderBy('name')->get();

        return view('purchasing.pos.edit', compact('po', 'suppliers'));
    }

    public function update(Request $request, Po $po)
    {
        if ($po->status !== 'pending') {
            return response()->json([
                'icon' => 'error',
                'title' => 'Gagal',
                'text' => 'Hanya PO dengan status pending yang dapat diedit.'
            ], 422);
        }

        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'order_date' => 'required|date',
            'estimated_usage_date' => 'required|date|after:order_date',
            'remarks' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Update PO header
            $supplier = Supplier::findOrFail($validated['supplier_id']);
            $po->update([
                'supplier_id' => $validated['supplier_id'],
                'company_name' => $supplier->name,
                'order_date' => $validated['order_date'],
                'estimated_usage_date' => $validated['estimated_usage_date'],
                'remarks' => $validated['remarks']
            ]);

            $totalAmount = 0;

            // Update PO items
            foreach ($validated['items'] as $id => $itemData) {
                $item = $po->items()->findOrFail($id);
                $totalPrice = $item->quantity * $itemData['unit_price'];
                $totalAmount += $totalPrice;

                $item->update([
                    'unit_price' => $itemData['unit_price'],
                    'total_price' => $totalPrice
                ]);
            }

            // Update total amount
            $po->update(['total_amount' => $totalAmount]);

            DB::commit();

            return response()->json([
                'success' => true,
                'icon' => 'success',
                'title' => 'Berhasil',
                'text' => "PO {$po->po_number} berhasil diupdate",
                'redirectTo' => route('purchasing.pos.show', $po)
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('PO Update Error: ' . $e->getMessage());
            return response()->json([
                'icon' => 'error',
                'title' => 'Gagal',
                'text' => 'Terjadi kesalahan saat mengupdate PO.'
            ], 500);
        }
    }

    public function print(Po $po)
    {
        try {
            $po->load(['spb', 'supplier', 'creator', 'items']);

            $pdf = PDF::loadView('purchasing.pos.print', compact('po'));

            // Set paper size to A4 landscape
            $pdf->setPaper('a4', 'landscape');

            // Force download instead of inline display
            return $pdf->download("PO-{$po->po_number}.pdf");
        } catch (\Exception $e) {
            Log::error('PO Print Error:', [
                'po_id' => $po->id,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Terjadi kesalahan saat mencetak PO.');
        }
    }
}
