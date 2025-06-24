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
        ]);

        $validated['quantity'] = $validated['initial_stock'];
        $validated['added_by'] = Auth::id();

        Inventory::create($validated);

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
}
