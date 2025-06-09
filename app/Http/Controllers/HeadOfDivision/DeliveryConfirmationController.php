<?php

namespace App\Http\Controllers\HeadOfDivision;

use App\Http\Controllers\Controller;
use App\Models\DeliveryPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeliveryConfirmationController extends Controller
{
    public function index()
    {
        $plans = DeliveryPlan::where('status', 'shipping')
            ->with(['creator', 'deliveryNotes'])
            ->latest()
            ->paginate(10);

        return view('head-of-division.delivery-confirmations.index', compact('plans'));
    }

    public function show(DeliveryPlan $plan)
    {
        $plan->load(['draftItems', 'packings', 'deliveryNotes.document']);
        return view('head-of-division.delivery-confirmations.show', compact('plan'));
    }

    public function approve(DeliveryPlan $plan)
    {
        if ($plan->status !== 'shipping') {
            return back()->with('error', 'Status pengiriman tidak valid');
        }

        $plan->update([
            'status' => DeliveryPlan::STATUS_COMPLETED,
            'updated_by' => Auth::id()
        ]);

        return redirect()
            ->route('head-of-division.delivery-confirmations.index')
            ->with('success', 'Pengiriman berhasil dikonfirmasi');
    }

    public function reject(Request $request, DeliveryPlan $plan)
    {
        if ($plan->status !== 'shipping') {
            return back()->with('error', 'Status pengiriman tidak valid');
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:255'
        ]);

        $plan->update([
            'status' => DeliveryPlan::STATUS_CANCELLED,
            'delivery_notes' => $request->rejection_reason,
            'updated_by' => Auth::id()
        ]);

        return redirect()
            ->route('head-of-division.delivery-confirmations.index')
            ->with('success', 'Pengiriman ditolak');
    }
}
