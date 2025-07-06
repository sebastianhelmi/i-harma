<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Po;
use App\Models\Inventory;
use App\Models\InventoryTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReceivedGoodsController extends Controller
{
    public function index(Request $request)
    {
        $query = Po::with(['supplier', 'spb.project'])
            ->where('status', 'pending')  // Only show pending POs
            ->when($request->search, function ($q) use ($request) {
                return $q->where('po_number', 'like', "%{$request->search}%")
                    ->orWhereHas('supplier', function ($q) use ($request) {
                        $q->where('name', 'like', "%{$request->search}%");
                    })
                    ->orWhereHas('spb.project', function ($q) use ($request) {
                        $q->where('name', 'like', "%{$request->search}%");
                    });
            })
            ->latest();

        $pos = $query->paginate(10);

        return view('inventory.received_goods.index', compact('pos'));
    }

    public function create(Po $po)
    {
        if ($po->status !== 'pending') {
            return redirect()
                ->route('inventory.received-goods.index')
                ->with('error', 'PO ini tidak dapat diproses karena sudah selesai.');
        }

        $po->load(['items', 'supplier', 'spb.project']);

        // Check and prepare inventory items status
        $itemsStatus = [];
        foreach ($po->items as $item) {
            $inventory = Inventory::where('item_name', $item->item_name)
                ->where('unit', $item->unit)
                ->first();

            $itemsStatus[$item->id] = [
                'exists' => !is_null($inventory),
                'inventory' => $inventory,
                'po_item' => $item
            ];
        }

        return view('inventory.received_goods.create', compact('po', 'itemsStatus'));
    }

    public function storeItem(Request $request)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'unit' => 'required|string|max:50',
            'item_category_id' => 'required|exists:item_categories,id',
            'unit_price' => 'required|numeric|min:0',
        ]);

        try {
            $inventory = Inventory::create([
                'item_name' => $validated['item_name'],
                'unit' => $validated['unit'],
                'item_category_id' => $validated['item_category_id'],
                'unit_price' => $validated['unit_price'],
                'quantity' => 0,
                'initial_stock' => 0,
                'added_by' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'inventory' => $inventory,
                'message' => 'Item berhasil ditambahkan'
            ]);

        } catch (\Exception $e) {
            Log::error('Error creating inventory item:', [
                'error' => $e->getMessage(),
                'data' => $validated
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan item'
            ], 500);
        }
    }

             public function store(Request $request, Po $po)
        {
            // Add validation for PO status
            if ($po->status !== 'pending') {
                return back()->with('error', 'PO ini sudah tidak dapat diproses');
            }

            $validated = $request->validate([
                'items' => 'required|array',
                'items.*.quantity_received' => 'required|numeric|min:0',
                'items.*.inventory_id' => 'required|exists:inventories,id',
                'remarks' => 'nullable|string'
            ]);

            try {
                DB::beginTransaction();

                $allReceived = true;
                $po->load('items'); // Eager load items

                foreach ($validated['items'] as $itemId => $item) {
                    $poItem = $po->items->find($itemId);

                    if (!$poItem) {
                        throw new \Exception("Item PO dengan ID {$itemId} tidak ditemukan");
                    }

                    // Validate quantity
                    if ($item['quantity_received'] > $poItem->quantity) {
                        throw new \Exception("Jumlah diterima tidak boleh melebihi jumlah order untuk {$poItem->item_name}");
                    }

                    if ($item['quantity_received'] < $poItem->quantity) {
                        $allReceived = false;
                    }

                    // Update inventory
                    $inventory = Inventory::findOrFail($item['inventory_id']);
                    $currentStock = $inventory->quantity;
                    $newStock = $currentStock + $item['quantity_received'];
                    $inventory->increment('quantity', $item['quantity_received']);

                    // Record transaction
                    InventoryTransaction::create([
                        'inventory_id' => $item['inventory_id'],
                        'po_id' => $po->id,
                        'quantity' => $item['quantity_received'],
                        'transaction_type' => InventoryTransaction::TYPE_IN,
                        'transaction_date' => now(),
                        'handled_by' => Auth::id(),
                        'remarks' => $validated['remarks'] ?? null,
                        'stock_after_transaction' => $newStock
                    ]);
                }

                // Update PO status if needed
                if ($allReceived) {
                    $po->update(['status' => 'completed']);
                }

                DB::commit();

                return redirect()
                    ->route('inventory.received-goods.index')
                    ->with('success', 'Penerimaan barang berhasil dicatat');

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Error recording received goods:', [
                    'po_id' => $po->id,
                    'error' => $e->getMessage(),
                    'data' => $validated['items'] ?? [],
                    'trace' => $e->getTraceAsString()
                ]);

                return back()
                    ->withInput()
                    ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
            }
        }
}
