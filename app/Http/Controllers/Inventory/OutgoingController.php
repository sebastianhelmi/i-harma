<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\InventoryTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class OutgoingController extends Controller
{
    public function index(Request $request)
    {
        // Get all inventory items for the filter dropdown
        $inventoryItems = Inventory::select('id', 'item_name as name')
            ->orderBy('item_name')
            ->get();

        $query = InventoryTransaction::with(['inventory', 'handler'])
            ->where('transaction_type', 'out');

        // Filter by date range
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('transaction_date', [
                $request->start_date,
                $request->end_date
            ]);
        }

        // Filter by inventory item
        if ($request->filled('inventory_id')) {
            $query->where('inventory_id', $request->inventory_id);
        }

        // Filter by PO number
        if ($request->filled('po_number')) {
            $query->whereHas('po', function ($q) use ($request) {
                $q->where('po_number', 'like', "%{$request->po_number}%");
            });
        }

        // Get total outgoing quantity
        $totalOutgoing = $query->sum('quantity') * -1;

        // Get paginated results
        $transactions = $query->latest()->paginate(10);

        return view('inventory.outgoing.index', compact(
            'transactions',
            'inventoryItems',
            'totalOutgoing'
        ));
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

            $currentStock = $inventory->quantity;
            $newStock = $currentStock - $validated['quantity'];

            // Create outgoing transaction
            InventoryTransaction::create([
                'inventory_id' => $validated['inventory_id'],
                'quantity' => -abs($validated['quantity']), // Ensure negative for outgoing
                'transaction_type' => 'out',
                'transaction_date' => $validated['transaction_date'],
                'handled_by' => Auth::id(),
                'remarks' => $validated['remarks'],
                'stock_after_transaction' => $newStock
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

    public function export(Request $request)
    {
        $query = InventoryTransaction::with(['inventory', 'handler'])
            ->where('transaction_type', 'out');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('transaction_date', [
                $request->start_date,
                $request->end_date
            ]);
        }

        if ($request->filled('inventory_id')) {
            $query->where('inventory_id', $request->inventory_id);
        }

        if ($request->filled('po_number')) {
            $query->whereHas('po', function ($q) use ($request) {
                $q->where('po_number', 'like', "%{$request->po_number}%");
            });
        }

        $transactions = $query->latest()->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Tanggal');
        $sheet->setCellValue('B1', 'Nama Barang');
        $sheet->setCellValue('C1', 'Jumlah Keluar');
        $sheet->setCellValue('D1', 'Satuan');
        $sheet->setCellValue('E1', 'Ditangani Oleh');
        $sheet->setCellValue('F1', 'Catatan');

        $row = 2;
        foreach ($transactions as $transaction) {
            $sheet->setCellValue('A' . $row, $transaction->transaction_date->format('d-m-Y'));
            $sheet->setCellValue('B' . $row, $transaction->inventory->item_name);
            $sheet->setCellValue('C' . $row, abs($transaction->quantity));
            $sheet->setCellValue('D' . $row, $transaction->inventory->unit);
            $sheet->setCellValue('E' . $row, $transaction->handler->name);
            $sheet->setCellValue('F' . $row, $transaction->remarks);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'outgoing-transactions-' . now()->format('Y-m-d_H-i-s') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        $writer->save('php://output');
    }
}
