<?php

namespace App\Http\Controllers\Delivery;

use App\Http\Controllers\Controller;
use App\Models\DeliveryPlan;
use App\Models\Packing;
use Illuminate\Http\Request;

class PackingController extends Controller
{
    public function create(DeliveryPlan $plan)
    {
        if ($plan->status !== 'packing') {
            return back()->with('error', 'Rencana pengiriman tidak dalam status packing');
        }

        $types = [
            'box' => 'Box',
            'bundle' => 'Bundle',
            'loose' => 'Loose'
        ];

        $categories = [
            'normal' => 'Normal',
            'fragile' => 'Mudah Pecah',
            'dangerous' => 'Berbahaya',
            'liquid' => 'Cairan',
            'heavy' => 'Berat'
        ];

        return view('delivery.packings.create', [
            'plan' => $plan,
            'types' => $types,
            'categories' => $categories
        ]);
    }

    public function store(Request $request, DeliveryPlan $plan)
    {
        if ($plan->status !== 'packing') {
            return back()->with('error', 'Rencana pengiriman tidak dalam status packing');
        }

        $validated = $request->validate([
            'packing_type' => 'required|string',
            'packing_category' => 'required|string',
            'packing_dimensions' => 'required|string|max:100',
        ]);

        $packing = $plan->packings()->create([
            'packing_number' => Packing::generatePackingNumber(),
            'packing_type' => $validated['packing_type'],
            'packing_category' => $validated['packing_category'],
            'packing_dimensions' => $validated['packing_dimensions'],
        ]);

        return redirect()
            ->route('delivery.plans.show', $plan)
            ->with('success', 'Data packing berhasil ditambahkan');
    }

    public function destroy(DeliveryPlan $plan, Packing $packing)
    {
        if ($plan->status !== 'packing') {
            return back()->with('error', 'Rencana pengiriman tidak dalam status packing');
        }

        $packing->delete();

        return back()->with('success', 'Data packing berhasil dihapus');
    }
}
