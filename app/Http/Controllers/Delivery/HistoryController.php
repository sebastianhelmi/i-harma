<?php

namespace App\Http\Controllers\Delivery;

use App\Http\Controllers\Controller;
use App\Models\DeliveryNote;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        $deliveryNotes = collect();

        if ($request->has('start_date') && $request->has('end_date')) {
            $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
            ]);

            $startDate = $request->start_date;
            $endDate = $request->end_date;

            $deliveryNotes = DeliveryNote::with(['deliveryPlan.project'])
                ->whereBetween('departure_date', [$startDate, $endDate])
                ->latest('departure_date')
                ->get();
        }

        return view('delivery.history.index', compact('deliveryNotes'));
    }

    public function show()
    {
        // Logic to show shipment details
    }

    public function export()
    {
        // Logic to export history
    }
}
