<?php

namespace App\Http\Controllers\Delivery;

use App\Http\Controllers\Controller;
use App\Models\DeliveryPlan;
use App\Models\DeliveryNote;
use App\Models\Packing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $now = now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();

        // Summary Cards
        $pendingPlans = DeliveryPlan::where('status', 'ready')->count();
        $packingToday = Packing::whereDate('created_at', $now->today())->count();
        $deliveringToday = DeliveryNote::whereHas('deliveryPlan', function ($query) {
            $query->where('status', 'delivering');
        })->whereDate('departure_date', $now->today())->count();
        $completedThisMonth = DeliveryPlan::where('status', 'completed')
            ->whereBetween('updated_at', [$startOfMonth, $endOfMonth])
            ->count();

        // Recent Activities (last 5)
        $recentActivities = DeliveryPlan::orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Delivery on Progress
        $onProgress = DeliveryNote::whereHas('deliveryPlan', function ($query) {
            $query->whereIn('status', ['packing', 'delivering']);
        })->with('deliveryPlan.project')
            ->orderBy('departure_date', 'desc')
            ->get();

        // dd($onProgress);


        return view('delivery.dashboard', compact(
            'pendingPlans',
            'packingToday',
            'deliveringToday',
            'completedThisMonth',
            'recentActivities',
            'onProgress'
        ));
    }
}
