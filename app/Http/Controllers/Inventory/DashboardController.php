<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\InventoryTransaction;
use App\Models\Po;
use App\Models\ItemCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $now = now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();

        // Summary
        $totalItems = Inventory::count();
        $totalStock = Inventory::sum('quantity');
        $pendingPo = Po::where('status', 'pending')->count();
        $completedPo = Po::where('status', 'completed')->count();

        // Grafik: Stok per Kategori
        $stockByCategory = ItemCategory::with(['inventories' => function($q) {
            $q->select('id', 'item_category_id', 'quantity');
        }])->get()->map(function($cat) {
            return [
                'category' => $cat->name,
                'stock' => $cat->inventories->sum('quantity'),
            ];
        });

        // Grafik: Transaksi per Bulan (12 bulan terakhir)
        $transactionsByMonth = collect(range(0, 11))->map(function($i) {
            $month = now()->subMonths($i)->format('Y-m');
            $in = InventoryTransaction::where('transaction_type', 'IN')
                ->whereBetween('transaction_date', [
                    now()->subMonths($i)->startOfMonth(),
                    now()->subMonths($i)->endOfMonth()
                ])->sum('quantity');
            $out = InventoryTransaction::where('transaction_type', 'OUT')
                ->whereBetween('transaction_date', [
                    now()->subMonths($i)->startOfMonth(),
                    now()->subMonths($i)->endOfMonth()
                ])->sum('quantity');
            return [
                'month' => $month,
                'in' => $in,
                'out' => $out,
            ];
        })->reverse()->values();

        // Recent notifications (last 5 unread)
        $notifications = $user->unreadNotifications->sortByDesc('created_at')->take(5);

        return view('inventory.dashboard', compact(
            'totalItems',
            'totalStock',
            'pendingPo',
            'completedPo',
            'stockByCategory',
            'transactionsByMonth',
            'notifications'
        ));
    }
}
