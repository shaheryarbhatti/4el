<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Models\VendorProfile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Admin dashboard — real stats, chart data, recent orders, top products.
 */
class DashboardController extends Controller
{
    public function index()
    {
        $now = Carbon::now();

        // ── Revenue ──────────────────────────────────────────────────────────
        $revenueTotal   = Order::where('payment_status', 'paid')->sum('grand_total');
        $revenueToday   = Order::where('payment_status', 'paid')
                               ->whereDate('created_at', $now->toDateString())
                               ->sum('grand_total');
        $revenueMonth   = Order::where('payment_status', 'paid')
                               ->whereYear('created_at', $now->year)
                               ->whereMonth('created_at', $now->month)
                               ->sum('grand_total');

        // ── Orders ───────────────────────────────────────────────────────────
        $ordersTotal    = Order::count();
        $ordersPending  = Order::where('status', 'pending')->count();
        $ordersMonth    = Order::whereYear('created_at', $now->year)
                               ->whereMonth('created_at', $now->month)
                               ->count();

        // Orders by status
        $ordersByStatus = Order::select('status', DB::raw('count(*) as total'))
                               ->groupBy('status')
                               ->pluck('total', 'status')
                               ->toArray();
        $statusKeys = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
        foreach ($statusKeys as $s) {
            $ordersByStatus[$s] = $ordersByStatus[$s] ?? 0;
        }

        // ── Products ─────────────────────────────────────────────────────────
        $productsActive = Product::where('is_active', true)
                                 ->where('status', 'approved')
                                 ->count();

        // ── Vendors / Customers ───────────────────────────────────────────────
        $vendorsPending   = VendorProfile::where('status', 'pending')->count();
        $customersTotal   = User::role('customer')->count();

        // ── Recent 6 orders ──────────────────────────────────────────────────
        $recentOrders = Order::with('user')
                             ->orderByDesc('created_at')
                             ->limit(6)
                             ->get();

        // ── Top 5 selling products (by quantity sold) ─────────────────────────
        $topProducts = OrderItem::select(
                            'product_id',
                            'product_name',
                            DB::raw('SUM(quantity) as units_sold'),
                            DB::raw('SUM(line_total) as revenue')
                        )
                        ->groupBy('product_id', 'product_name')
                        ->orderByDesc('units_sold')
                        ->limit(5)
                        ->get();

        // ── Monthly revenue — last 7 months (for SVG bar chart) ───────────────
        $monthlyRevenue = [];
        for ($i = 6; $i >= 0; $i--) {
            $m = $now->copy()->subMonths($i);
            $rev = Order::where('payment_status', 'paid')
                        ->whereYear('created_at', $m->year)
                        ->whereMonth('created_at', $m->month)
                        ->sum('grand_total');
            $monthlyRevenue[] = [
                'label'   => $m->format('M'),
                'revenue' => (float) $rev,
            ];
        }

        return view('admin.dashboard', compact(
            'revenueTotal',
            'revenueToday',
            'revenueMonth',
            'ordersTotal',
            'ordersPending',
            'ordersMonth',
            'ordersByStatus',
            'productsActive',
            'vendorsPending',
            'customersTotal',
            'recentOrders',
            'topProducts',
            'monthlyRevenue',
        ));
    }
}
