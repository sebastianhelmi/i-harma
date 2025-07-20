<?php

namespace App\Http\Controllers\Purchasing;

use App\Http\Controllers\Controller;
use App\Models\Po;
use App\Models\Spb;
use App\Models\Supplier;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $now = now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();

        // Summary
        $totalSpb = Spb::where('status', 'approved')->count();
        $pendingPo = Po::where('status', 'pending')->count();
        $completedPo = Po::where('status', 'completed')->count();
        $suppliers = Supplier::count();

        // PO Progress
        $poProgress = Po::with(['spb.project'])
            ->orderBy('order_date', 'desc')
            ->take(5)
            ->get();

        // SPB waiting for PO
        $spbWaitingPo = Spb::where('status', 'approved')
            ->where('status_po', 'pending')
            ->with(['project', 'requester'])
            ->orderBy('spb_date', 'desc')
            ->take(5)
            ->get();

        // Recent notifications (last 5 unread)
        $notifications = $user->unreadNotifications->sortByDesc('created_at')->take(5);

        return view('purchasing.dashboard', compact(
            'totalSpb',
            'pendingPo',
            'completedPo',
            'suppliers',
            'poProgress',
            'spbWaitingPo',
            'notifications'
        ));
    }
}
