<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\InventoryTransaction;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $transactions = collect();

        if ($request->has('start_date') && $request->has('end_date')) {
            $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
            ]);

            $startDate = $request->start_date;
            $endDate = $request->end_date;

            $transactions = InventoryTransaction::with(['inventory', 'handler'])
                ->whereBetween('transaction_date', [$startDate, $endDate])
                ->latest('transaction_date')
                ->get();
        }

        return view('inventory.reports.index', compact('transactions'));
    }

    public function exportPdf(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $transactions = InventoryTransaction::with(['inventory', 'handler'])
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->latest('transaction_date')
            ->get();

        $pdf = Pdf::loadView('inventory.reports.pdf', compact('transactions', 'startDate', 'endDate'));
        return $pdf->download('laporan-inventory-' . $startDate . '-' . $endDate . '.pdf');
    }
}
