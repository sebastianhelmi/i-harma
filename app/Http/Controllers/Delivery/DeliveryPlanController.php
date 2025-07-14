<?php

namespace App\Http\Controllers\Delivery;

use App\Http\Controllers\Controller;
use App\Models\DeliveryPlan;
use App\Models\Inventory;
use App\Models\InventoryTransaction;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DeliveryPlanController extends Controller
{
    public function index(Request $request)
    {
        $query = DeliveryPlan::with(['creator', 'updater'])
            ->latest();

        // Filter by date
        if ($request->has('date')) {
            $query->whereDate('planned_date', $request->date);
        }

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Search by plan number or destination
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('plan_number', 'like', "%{$search}%")
                    ->orWhere('destination', 'like', "%{$search}%");
            });
        }

        $plans = $query->paginate(10);
        $statuses = [
            'draft' => 'Draft',
            'packing' => 'Packing',
            'ready' => 'Siap Kirim',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan'
        ];

        return view('delivery.plans.index', compact('plans', 'statuses'));
    }

    public function create()
    {
        $vehicleTypes = [
            'truck' => 'Truk',
            'pickup' => 'Pickup',
            'box' => 'Box',
            'container' => 'Container'
        ];
        $projects = Project::orderBy('name')->get();

        return view('delivery.plans.create', compact('vehicleTypes', 'projects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'destination' => 'required|string|max:255',
            'planned_date' => 'required|date|after_or_equal:today',
            'vehicle_count' => 'required|integer|min:1',
            'vehicle_type' => 'required|in:truck,pickup,box,container',
            'delivery_notes' => 'nullable|string'
        ]);

        $plan = DeliveryPlan::create([
            'project_id' => $validated['project_id'],
            'plan_number' => $this->generatePlanNumber(),
            'destination' => strtoupper($validated['destination']),
            'planned_date' => $validated['planned_date'],
            'vehicle_count' => $validated['vehicle_count'],
            'vehicle_type' => $validated['vehicle_type'],
            'delivery_notes' => $validated['delivery_notes'],
            'status' => 'draft',
            'created_by' => Auth::id()
        ]);

        return redirect()
            ->route('delivery.plans.show', $plan)
            ->with('success', 'Rencana pengiriman berhasil dibuat');
    }

    public function show(DeliveryPlan $plan)
    {
        $plan->load([
            'packings',
            'draftItems',
            'deliveryNotes',
            'creator',
            'updater'
        ]);

        return view('delivery.plans.show', compact('plan'));
    }

    public function edit(DeliveryPlan $plan)
    {
        if (!$plan->canBeUpdated()) {
            return back()->with('error', 'Rencana pengiriman tidak dapat diubah');
        }

        $vehicleTypes = [
            'truck' => 'Truk',
            'pickup' => 'Pickup',
            'box' => 'Box',
            'container' => 'Container'
        ];
        $projects = Project::orderBy('name')->get(); // Tambahkan baris ini

        return view('delivery.plans.edit', compact('plan', 'vehicleTypes', 'projects')); // Tambahkan $projects
    }

    public function update(Request $request, DeliveryPlan $plan)
    {
        if (!$plan->canBeUpdated()) {
            return back()->with('error', 'Rencana pengiriman tidak dapat diubah');
        }

        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'destination' => 'required|string|max:255',
            'planned_date' => 'required|date|after_or_equal:today',
            'vehicle_count' => 'required|integer|min:1',
            'vehicle_type' => 'required|in:truck,pickup,box,container',
            'delivery_notes' => 'nullable|string'
        ]);

        $plan->update([
            'destination' => strtoupper($validated['destination']),
            'planned_date' => $validated['planned_date'],
            'vehicle_count' => $validated['vehicle_count'],
            'vehicle_type' => $validated['vehicle_type'],
            'delivery_notes' => $validated['delivery_notes'],
            'updated_by' => Auth::id()
        ]);

        return redirect()
            ->route('delivery.plans.show', $plan)
            ->with('success', 'Rencana pengiriman berhasil diubah');
    }

    public function destroy(DeliveryPlan $plan)
    {
        if (!$plan->canBeCancelled()) {
            return back()->with('error', 'Rencana pengiriman tidak dapat dibatalkan');
        }

        $plan->update([
            'status' => 'cancelled',
            'updated_by' => Auth::id()
        ]);

        return redirect()
            ->route('delivery.plans.index')
            ->with('success', 'Rencana pengiriman berhasil dibatalkan');
    }

    public function updateStatus(Request $request, DeliveryPlan $plan)
    {
        $validated = $request->validate([
            'status' => 'required|in:packing,ready,delivering,completed'
        ]);

        if (!$this->isValidStatusTransition($plan->status, $validated['status'])) {
            return back()->with('error', 'Status tidak dapat diubah');
        }

        try {
            DB::beginTransaction();

            $plan->update([
                'status' => $validated['status'],
                'updated_by' => Auth::id()
            ]);

            DB::commit();

            $message = match ($validated['status']) {
                'packing' => 'Packing dimulai',
                'ready' => 'Pengiriman siap dilakukan',
                'delivering' => 'Pengiriman dalam perjalanan',
                'completed' => 'Pengiriman selesai',
                default => 'Status berhasil diubah'
            };

            return back()->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    private function generatePlanNumber(): string
    {
        $prefix = 'DEL';
        $date = now()->format('Ymd');
        $lastPlan = DeliveryPlan::where('plan_number', 'like', "{$prefix}{$date}%")
            ->orderBy('plan_number', 'desc')
            ->first();

        if (!$lastPlan) {
            $number = '001';
        } else {
            $lastNumber = substr($lastPlan->plan_number, -3);
            $number = str_pad((int)$lastNumber + 1, 3, '0', STR_PAD_LEFT);
        }

        return "{$prefix}{$date}{$number}";
    }

    private function isValidStatusTransition(string $currentStatus, string $newStatus): bool
    {
        $allowedTransitions = [
            'draft' => ['packing'],
            'packing' => ['ready'],
            'ready' => ['delivering'],
            'delivering' => ['completed']
        ];

        return isset($allowedTransitions[$currentStatus]) &&
            in_array($newStatus, $allowedTransitions[$currentStatus]);
    }
}
