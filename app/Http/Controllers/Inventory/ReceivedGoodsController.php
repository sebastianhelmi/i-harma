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
        if ($po->status === 'completed') {
            return redirect()
                ->route('inventory.received-goods.index')
                ->with('error', 'PO ini sudah selesai dan tidak dapat diproses lagi.');
        }

        $po->load(['items', 'supplier', 'spb.project']);

        $itemsWithStatus = [];
        foreach ($po->items as $item) {
            $inventory = Inventory::where('item_name', $item->item_name)
                ->where('unit', $item->unit)
                ->first();

            $alreadyReceived = InventoryTransaction::where('po_id', $po->id)
                ->whereHas('inventory', function ($query) use ($item) {
                    $query->where('item_name', $item->item_name);
                })
                ->where('transaction_type', InventoryTransaction::TYPE_IN)
                ->sum('quantity');

            $remainingQuantity = $item->quantity - $alreadyReceived;

            $itemsWithStatus[$item->id] = [
                'exists' => !is_null($inventory),
                'inventory' => $inventory,
                'po_item' => $item,
                'already_received' => $alreadyReceived,
                'remaining_quantity' => $remainingQuantity,
            ];
        }

        return view('inventory.received_goods.create', compact('po', 'itemsWithStatus'));
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

            $po->load('items'); // Eager load items

            foreach ($validated['items'] as $itemId => $item) {
                $poItem = $po->items->find($itemId);

                if (!$poItem) {
                    throw new \Exception("Item PO dengan ID {$itemId} tidak ditemukan");
                }

                // Get total quantity already received for this item
                $alreadyReceived = InventoryTransaction::where('po_id', $po->id)
                    ->whereHas('inventory', function ($query) use ($poItem) {
                        $query->where('item_name', $poItem->item_name);
                    })
                    ->where('transaction_type', InventoryTransaction::TYPE_IN)
                    ->sum('quantity');

                $remainingQuantity = $poItem->quantity - $alreadyReceived;

                // Validate quantity
                if ($item['quantity_received'] > $remainingQuantity) {
                    throw new \Exception("Jumlah diterima ({$item['quantity_received']}) tidak boleh melebihi sisa jumlah order ({$remainingQuantity}) untuk item {$poItem->item_name}");
                }

                // Update inventory
                if ($item['quantity_received'] > 0) {
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
            }

            // Update PO status if all items are fully received
            $totalStillRemaining = 0;
            foreach ($po->items as $poItem) {
                $totalReceivedForItem = InventoryTransaction::where('po_id', $po->id)
                    ->whereHas('inventory', function ($query) use ($poItem) {
                        $query->where('item_name', $poItem->item_name);
                    })
                    ->where('transaction_type', InventoryTransaction::TYPE_IN)
                    ->sum('quantity');
                $totalStillRemaining += $poItem->quantity - $totalReceivedForItem;
            }

            if ($totalStillRemaining <= 0) {
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
