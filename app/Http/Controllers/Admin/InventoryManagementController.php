<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\ItemCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class InventoryManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = Inventory::with(['itemCategory', 'addedBy'])
            ->select('inventories.*')
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->search;
                $q->where(function ($query) use ($search) {
                    $query->where('item_name', 'like', "%{$search}%")
                        ->orWhere('id', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('category'), function ($q) use ($request) {
                $q->where('item_category_id', $request->category);
            })
            ->when($request->filled('status'), function ($q) use ($request) {
                switch ($request->status) {
                    case 'low':
                        $q->where('quantity', '<', 10);
                        break;
                    case 'out':
                        $q->where('quantity', 0);
                        break;
                    case 'available':
                        $q->where('quantity', '>', 10);
                        break;
                }
            });

        $inventories = $query->latest()->paginate(10)->withQueryString();
        $categories = ItemCategory::pluck('name', 'id');

        // Get statistics
        $stats = [
            'total_items' => Inventory::count(),
            'low_stock' => Inventory::where('quantity', '<', 10)->count(),
            'out_of_stock' => Inventory::where('quantity', 0)->count(),
            'categories_stats' => Inventory::select('item_category_id', DB::raw('count(*) as total'))
                ->groupBy('item_category_id')
                ->get()
                ->map(function ($item) {
                    return [
                        'category' => ItemCategory::find($item->item_category_id)->name,
                        'total' => $item->total
                    ];
                })
        ];

        if ($request->ajax()) {
            return response()->json([
                'inventories' => $inventories,
                'stats' => $stats
            ]);
        }

        return view('admin.inventory.index', compact('inventories', 'categories', 'stats'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'item_name' => ['required', 'string', 'max:255'],
                'item_category_id' => ['required', 'exists:item_categories,id'],
                'quantity' => ['required', 'integer', 'min:0'],
                'initial_stock' => ['required', 'integer', 'min:0'],
                'unit' => ['required', 'string', 'max:50'],
                'unit_price' => ['nullable', 'numeric', 'min:0'],
            ], [
                'item_name.required' => 'Nama item harus diisi',
                'item_category_id.required' => 'Kategori harus dipilih',
                'quantity.required' => 'Jumlah stok harus diisi',
                'unit.required' => 'Satuan harus diisi',
            ]);

            DB::beginTransaction();

            $validated['added_by'] = Auth::id();
            $inventory = Inventory::create($validated);

            DB::commit();

            return response()->json([
                'message' => 'Item berhasil ditambahkan',
                'inventory' => $inventory->load(['itemCategory', 'addedBy'])
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating inventory: ' . $e->getMessage());

            return response()->json([
                'message' => 'Terjadi kesalahan saat menambahkan item',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Inventory $inventory)
    {
        return response()->json($inventory->load(['itemCategory', 'addedBy']));
    }

    public function update(Request $request, Inventory $inventory)
    {
        try {
            $validated = $request->validate([
                'item_name' => ['required', 'string', 'max:255'],
                'item_category_id' => ['required', 'exists:item_categories,id'],
                'quantity' => ['required', 'integer', 'min:0'],
                'unit' => ['required', 'string', 'max:50'],
                'unit_price' => ['nullable', 'numeric', 'min:0'],
            ]);

            DB::beginTransaction();

            $inventory->update($validated);

            DB::commit();

            return response()->json([
                'message' => 'Item berhasil diperbarui',
                'inventory' => $inventory->fresh()->load(['itemCategory', 'addedBy'])
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating inventory: ' . $e->getMessage());

            return response()->json([
                'message' => 'Terjadi kesalahan saat memperbarui item',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Inventory $inventory)
    {
        try {
            DB::beginTransaction();

            $inventory->delete();

            DB::commit();

            return response()->json([
                'message' => 'Item berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting inventory: ' . $e->getMessage());

            return response()->json([
                'message' => 'Terjadi kesalahan saat menghapus item',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateStock(Request $request, Inventory $inventory)
    {
        try {
            $validated = $request->validate([
                'adjustment' => ['required', 'integer'],
                'notes' => ['nullable', 'string', 'max:255']
            ]);

            DB::beginTransaction();

            $newQuantity = $inventory->quantity + $validated['adjustment'];

            if ($newQuantity < 0) {
                return response()->json([
                    'message' => 'Stok tidak mencukupi'
                ], 422);
            }

            $inventory->update([
                'quantity' => $newQuantity
            ]);

            // Here you might want to log the stock adjustment in a separate table

            DB::commit();

            return response()->json([
                'message' => 'Stok berhasil diperbarui',
                'inventory' => $inventory->fresh()
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating stock: ' . $e->getMessage());

            return response()->json([
                'message' => 'Terjadi kesalahan saat memperbarui stok',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
