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

        $ordersPaid     = Order::where('payment_status', 'paid')->count();
        $ordersUnpaid   = Order::where('payment_status', '!=', 'paid')->count();
        $avgOrderValue  = $ordersPaid > 0 ? $revenueTotal / $ordersPaid : 0;

        // ── Products ─────────────────────────────────────────────────────────
        $productsActive = Product::where('is_active', true)
                                 ->where('status', 'approved')
                                 ->count();
        $productsTotal  = Product::count();
        $lowStock       = Product::where('stock', '>', 0)->where('stock', '<=', 5)->count();
        $auctionsLive   = Product::where('listing_type', 'auction')
                                 ->whereNull('auction_closed_at')
                                 ->where('auction_ends_at', '>', $now)
                                 ->count();

        // ── Vendors / Customers ───────────────────────────────────────────────
        $vendorsPending   = VendorProfile::where('status', 'pending')->count();
        $vendorsApproved  = VendorProfile::where('status', 'approved')->count();
        $customersTotal   = User::role('customer')->count();
        $newCustomers30d  = User::role('customer')
                                ->where('created_at', '>=', $now->copy()->subDays(30))
                                ->count();

        // ── Earnings split (from paid orders' items) ──────────────────────────
        $earnings = OrderItem::join('orders', 'orders.id', '=', 'order_items.order_id')
                        ->where('orders.payment_status', 'paid')
                        ->selectRaw('COALESCE(SUM(order_items.vendor_amount),0) as vendor_earnings')
                        ->selectRaw('COALESCE(SUM(order_items.commission_amount),0) as platform_earnings')
                        ->first();
        $vendorEarnings   = (float) ($earnings->vendor_earnings ?? 0);
        $platformEarnings = (float) ($earnings->platform_earnings ?? 0);

        // ── Top 5 vendors by earnings ─────────────────────────────────────────
        $topVendors = OrderItem::join('orders', 'orders.id', '=', 'order_items.order_id')
                        ->join('users', 'users.id', '=', 'order_items.vendor_id')
                        ->leftJoin('vendor_profiles', 'vendor_profiles.user_id', '=', 'order_items.vendor_id')
                        ->where('orders.payment_status', 'paid')
                        ->groupBy('order_items.vendor_id', 'users.name', 'vendor_profiles.store_name')
                        ->selectRaw('order_items.vendor_id,
                                     COALESCE(vendor_profiles.store_name, users.name) as store_name,
                                     COALESCE(SUM(order_items.vendor_amount),0) as earnings,
                                     COUNT(DISTINCT order_items.order_id) as orders_count')
                        ->orderByDesc('earnings')
                        ->limit(5)
                        ->get();

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
            'ordersPaid',
            'ordersUnpaid',
            'avgOrderValue',
            'ordersByStatus',
            'productsActive',
            'productsTotal',
            'lowStock',
            'auctionsLive',
            'vendorsPending',
            'vendorsApproved',
            'customersTotal',
            'newCustomers30d',
            'vendorEarnings',
            'platformEarnings',
            'topVendors',
            'recentOrders',
            'topProducts',
            'monthlyRevenue',
        ));
    }
}
