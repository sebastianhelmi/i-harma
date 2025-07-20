<?php

namespace App\Http\Controllers\Purchasing;

use App\Http\Controllers\Controller;
use App\Models\Po;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $pos = collect();

        if ($request->has('start_date') && $request->has('end_date')) {
            $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
            ]);

            $startDate = $request->start_date;
            $endDate = $request->end_date;

            $pos = Po::with(['spb.project', 'supplier'])
                ->whereBetween('order_date', [$startDate, $endDate])
                ->latest('order_date')
                ->get();
        }

        return view('purchasing.reports.index', compact('pos'));
    }

    public function exportPdf(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $pos = Po::with(['spb.project', 'supplier'])
            ->whereBetween('order_date', [$startDate, $endDate])
            ->latest('order_date')
            ->get();

        $pdf = Pdf::loadView('purchasing.reports.pdf', compact('pos', 'startDate', 'endDate'));
        return $pdf->download('laporan-po-' . $startDate . '-' . $endDate . '.pdf');
    }
}
