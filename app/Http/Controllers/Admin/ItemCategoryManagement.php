<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ItemCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class ItemCategoryManagement extends Controller
{
    public function index(Request $request)
    {
        $query = ItemCategory::query()
            ->withCount('inventories');

        // Apply search filter
        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%")
                ->orWhere('description', 'like', "%{$request->search}%");
        }

        // Apply sorting
        $query->when($request->sort, function ($q) use ($request) {
            switch ($request->sort) {
                case 'asc':
                    return $q->orderBy('name', 'asc');
                case 'desc':
                    return $q->orderBy('name', 'desc');
                case 'latest':
                    return $q->latest();
                default:
                    return $q->orderBy('name', 'asc');
            }
        });

        $categories = $query->paginate(10)->withQueryString();

        if ($request->ajax()) {
            return response()->json($categories);
        }

        return view('admin.item-categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'min:3', 'max:255', 'unique:item_categories'],
                'description' => ['nullable', 'string', 'max:1000'],
            ], [
                'name.required' => 'Nama kategori harus diisi',
                'name.min' => 'Nama kategori minimal 3 karakter',
                'name.unique' => 'Nama kategori sudah digunakan',
            ]);

            DB::beginTransaction();

            $category = ItemCategory::create($validated);

            DB::commit();

            return response()->json([
                'message' => 'Kategori berhasil ditambahkan',
                'category' => $category
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating category: ' . $e->getMessage());

            return response()->json([
                'message' => 'Terjadi kesalahan saat menambahkan kategori',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(ItemCategory $category)
    {
        return response()->json($category);
    }

    public function update(Request $request, ItemCategory $category)
    {
        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'min:3', 'max:255', Rule::unique('item_categories')->ignore($category->id)],
                'description' => ['nullable', 'string', 'max:1000'],
            ], [
                'name.required' => 'Nama kategori harus diisi',
                'name.min' => 'Nama kategori minimal 3 karakter',
                'name.unique' => 'Nama kategori sudah digunakan',
            ]);

            DB::beginTransaction();

            $category->update($validated);

            DB::commit();

            return response()->json([
                'message' => 'Kategori berhasil diperbarui',
                'category' => $category->fresh()
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating category: ' . $e->getMessage());

            return response()->json([
                'message' => 'Terjadi kesalahan saat memperbarui kategori',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(ItemCategory $category)
    {
        try {
            // Check if category has related inventories
            if ($category->inventories()->count() > 0) {
                return response()->json([
                    'message' => 'Kategori tidak dapat dihapus karena masih memiliki inventaris terkait'
                ], 422);
            }

            DB::beginTransaction();

            $category->delete();

            DB::commit();

            return response()->json([
                'message' => 'Kategori berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting category: ' . $e->getMessage());

            return response()->json([
                'message' => 'Terjadi kesalahan saat menghapus kategori',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function bulkDestroy(Request $request)
    {
        try {
            $validated = $request->validate([
                'ids' => ['required', 'array'],
                'ids.*' => ['exists:item_categories,id']
            ]);

            DB::beginTransaction();

            // Check if any category has related inventories
            $categoriesWithInventories = ItemCategory::whereIn('id', $validated['ids'])
                ->whereHas('inventories')
                ->pluck('name');

            if ($categoriesWithInventories->isNotEmpty()) {
                return response()->json([
                    'message' => 'Beberapa kategori tidak dapat dihapus karena masih memiliki inventaris terkait: ' . $categoriesWithInventories->implode(', ')
                ], 422);
            }
            ItemCategory::whereIn('id', $validated['ids'])->delete();

            DB::commit();

            return response()->json([
                'message' => count($validated['ids']) . ' kategori berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error bulk deleting categories: ' . $e->getMessage());

            return response()->json([
                'message' => 'Terjadi kesalahan saat menghapus kategori',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
