<?php

namespace App\Http\Controllers;

use App\Models\ProcurementHistory;
use Illuminate\Http\Request;

class ProcurementHistoryController extends Controller
{
    public function index(Request $request)
    {
        $query = ProcurementHistory::with(['spb.division', 'po', 'actor'])
            ->latest();

        // Filter by document type
        if ($request->has('type')) {
            $query->where('document_type', $request->type);
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->has('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->has('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $histories = $query->paginate(10)
            ->withQueryString();

        return view('pm.riwayat.index', compact('histories'));
    }
}
