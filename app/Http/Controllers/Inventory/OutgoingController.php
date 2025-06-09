<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\InventoryTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OutgoingController extends Controller
{
    public function index(Request $request)
    {
        $query = InventoryTransaction::with(['inventory', 'handler'])
            ->where('transaction_type', 'out');

        // Filter by date
        if ($request->has('date')) {
            $query->whereDate('transaction_date', $request->date);
        }

        // Search by item name
        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('inventory', function ($q) use ($search) {
                $q->where('item_name', 'like', "%{$search}%");
            });
        }

        $transactions = $query->latest()->paginate(10);

        return view('inventory.outgoing.index', compact('transactions'));
    }

    public function create()
    {
        $inventories = Inventory::where('quantity', '>', 0)->get();
        return view('inventory.outgoing.create', compact('inventories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'inventory_id' => 'required|exists:inventories,id',
            'quantity' => 'required|integer|min:1',
            'transaction_date' => 'required|date|before_or_equal:today',
            'remarks' => 'required|string|max:255'
        ]);

        try {
            DB::beginTransaction();

            $inventory = Inventory::findOrFail($validated['inventory_id']);

            if ($inventory->quantity < $validated['quantity']) {
                throw new \Exception('Stok tidak mencukupi');
            }

            // Create outgoing transaction
            InventoryTransaction::create([
                'inventory_id' => $validated['inventory_id'],
                'quantity' => -abs($validated['quantity']), // Ensure negative for outgoing
                'transaction_type' => 'out',
                'transaction_date' => $validated['transaction_date'],
                'handled_by' => Auth::id(),
                'remarks' => $validated['remarks']
            ]);

            // Update inventory quantity
            $inventory->decrement('quantity', $validated['quantity']);

            DB::commit();

            return redirect()
                ->route('inventory.outgoing.index')
                ->with('success', 'Transaksi keluar berhasil dicatat');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(InventoryTransaction $transaction)
    {
        if ($transaction->transaction_type !== 'out') {
            abort(404);
        }

        return view('inventory.outgoing.show', compact('transaction'));
    }
}
