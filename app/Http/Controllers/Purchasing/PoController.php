<?php

namespace App\Http\Controllers\Purchasing;

use App\Http\Controllers\Controller;
use App\Models\Po;
use App\Models\SiteSpb;
use App\Models\Spb;
use App\Models\Supplier;
use App\Models\WorkshopSpb;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PoController extends Controller
{
    public function index(Request $request)
    {
        $query = Po::with(['spb', 'supplier', 'creator'])
            ->when($request->search, function($q) use ($request) {
                return $q->where(function($query) use ($request) {
                    $query->where('po_number', 'like', "%{$request->search}%")
                        ->orWhere('company_name', 'like', "%{$request->search}%")
                        ->orWhereHas('supplier', function($q) use ($request) {
                            $q->where('name', 'like', "%{$request->search}%");
                        });
                });
            })
            ->when($request->status, function($q) use ($request) {
                return $q->where('status', $request->status);
            })
            ->latest('order_date');

        $pos = $query->paginate(10);
        $statuses = ['pending' => 'Pending', 'completed' => 'Selesai', 'cancelled' => 'Dibatalkan'];

        return view('purchasing.pos.index', compact('pos', 'statuses'));
    }

    public function create($spb_id)
    {
        $spb = Spb::with(['project', 'task', 'siteItems', 'workshopItems'])
            ->where('status', 'approved')
            ->where('status_po', 'pending')
            ->findOrFail($spb_id);

        $suppliers = Supplier::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('purchasing.pos.create', compact('spb', 'suppliers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'spb_id' => 'required|exists:spbs,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'company_name' => 'required|string|max:255',
            'order_date' => 'required|date',
            'estimated_usage_date' => 'required|date|after:order_date',
            'remarks' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|integer',
            'items.*.type' => 'required|in:site,workshop',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Create PO
            $po = Po::create([
                'po_number' => Po::generatePoNumber(),
                'spb_id' => $validated['spb_id'],
                'created_by' => Auth::id(),
                'supplier_id' => $validated['supplier_id'],
                'company_name' => $validated['company_name'],
                'order_date' => $validated['order_date'],
                'estimated_usage_date' => $validated['estimated_usage_date'],
                'status' => 'pending',
                'remarks' => $validated['remarks'],
                'total_amount' => 0
            ]);

            $totalAmount = 0;

            // Create PO Items
            foreach ($validated['items'] as $item) {
                if ($item['type'] === 'site') {
                    $spbItem = SiteSpb::findOrFail($item['id']);
                } else {
                    $spbItem = WorkshopSpb::findOrFail($item['id']);
                }

                $totalPrice = $spbItem->quantity * $item['unit_price'];
                $totalAmount += $totalPrice;

                $po->items()->create([
                    'spb_id' => $validated['spb_id'],
                    $item['type'] . '_spb_id' => $spbItem->id,
                    'item_name' => $item['type'] === 'site' ?
                        $spbItem->item_name :
                        $spbItem->explanation_items,
                    'unit' => $spbItem->unit,
                    'quantity' => $spbItem->quantity,
                    'unit_price' => $item['unit_price'],
                    'total_price' => $totalPrice
                ]);
            }

            // Update PO total
            $po->update(['total_amount' => $totalAmount]);

            // Update SPB status
            Spb::findOrFail($validated['spb_id'])
                ->update(['status_po' => 'ordered']);

            DB::commit();

            return redirect()
                ->route('purchasing.pos.index')
                ->with('success', "PO {$po->po_number} berhasil dibuat.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->with('error', 'Terjadi kesalahan saat membuat PO.')
                ->withInput();
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
}
