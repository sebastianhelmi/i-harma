<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\ItemCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Inventory::with(['itemCategory', 'addedBy'])
            ->when($request->search, function ($q) use ($request) {
                return $q->where('item_name', 'like', "%{$request->search}%")
                    ->orWhere('category', 'like', "%{$request->search}%");
            })
            ->when($request->category_id, function ($q) use ($request) {
                return $q->where('item_category_id', $request->category_id);
            })
            ->latest();

        $categories = ItemCategory::pluck('name', 'id');
        $items = $query->paginate(10);

        return view('inventory.items.index', compact('items', 'categories'));
    }

    public function create()
    {
        $categories = ItemCategory::pluck('name', 'id');
        return view('inventory.items.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'item_category_id' => 'required|exists:item_categories,id',
            'initial_stock' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
            'unit_price' => 'nullable|numeric|min:0',
            'weight' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $existingInventory = Inventory::where('item_name', $validated['item_name'])
            ->where('item_category_id', $validated['item_category_id'])
            ->first();

        if ($existingInventory) {
            $existingInventory->quantity += $validated['initial_stock'];
            $existingInventory->save();

            $existingInventory->transactions()->create([
                'quantity' => $validated['initial_stock'],
                'transaction_type' => 'IN',
                'transaction_date' => now(),
                'handled_by' => Auth::id(),
                'remarks' => $validated['notes'] ?? 'Stok ditambahkan',
                'stock_after_transaction' => $existingInventory->quantity,
            ]);

            return redirect()
                ->route('inventory.items.index')
                ->with('success', 'Stok barang berhasil ditambahkan dan tercatat dalam transaksi.');
        }

        $validated['quantity'] = $validated['initial_stock'];
        $validated['added_by'] = Auth::id();

        $inventory = Inventory::create($validated);

        $inventory->transactions()->create([
            'quantity' => $validated['initial_stock'],
            'transaction_type' => 'IN',
            'transaction_date' => now(),
            'handled_by' => Auth::id(),
            'remarks' => $validated['notes'] ?? 'Stok awal',
            'stock_after_transaction' => $inventory->quantity,
        ]);

        return redirect()
            ->route('inventory.items.index')
            ->with('success', 'Item berhasil ditambahkan ke inventory');
    }

    public function edit(Inventory $item)
    {
        $categories = ItemCategory::pluck('name', 'id');
        return view('inventory.items.edit', compact('item', 'categories'));
    }

    public function update(Request $request, Inventory $item)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'item_category_id' => 'required|exists:item_categories,id',
            'unit' => 'required|string|max:50',
            'unit_price' => 'nullable|numeric|min:0',
            'weight' => 'nullable|numeric|min:0',
        ]);

        $item->update($validated);

        return redirect()
            ->route('inventory.items.index')
            ->with('success', 'Item berhasil diperbarui');
    }

    public function history(Inventory $item)
    {
        $transactions = $item->transactions()->with('handler')->latest('transaction_date')->paginate(15);

        return view('inventory.items.history', compact('item', 'transactions'));
    }
}
