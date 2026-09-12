<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;

class EarningsController extends Controller
{
    public function index()
    {
        $vendor   = auth()->user();
        $vendorId = $vendor->id;

        // Base paid-orders scope
        $paid = fn ($q) => $q->where('payment_status', 'paid');

        // ── Stat totals ──────────────────────────────────────────────────────────
        $totalEarned     = OrderItem::where('vendor_id', $vendorId)->whereHas('order', $paid)->sum('vendor_amount');
        $totalCommission = OrderItem::where('vendor_id', $vendorId)->whereHas('order', $paid)->sum('commission_amount');
        $ordersCount     = OrderItem::where('vendor_id', $vendorId)->whereHas('order', $paid)->distinct('order_id')->count('order_id');

        $thisMonth = OrderItem::where('vendor_id', $vendorId)
            ->whereHas('order', $paid)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('vendor_amount');

        // ── Recent 10 order items (all statuses, so vendor sees pipeline) ───────
        $recent = OrderItem::where('vendor_id', $vendorId)
            ->with('order')
            ->latest()
            ->take(10)
            ->get();

        // ── Monthly earnings – last 6 months ─────────────────────────────────────
        $monthlyEarnings = [];
        for ($i = 5; $i >= 0; $i--) {
            $m      = now()->subMonths($i);
            $amount = OrderItem::where('vendor_id', $vendorId)
                ->whereHas('order', $paid)
                ->whereMonth('created_at', $m->month)
                ->whereYear('created_at', $m->year)
                ->sum('vendor_amount');

            $monthlyEarnings[] = [
                'label'  => $m->format('M Y'),
                'short'  => $m->format('M'),
                'amount' => (float) $amount,
            ];
        }

        return view('vendor.earnings.index', compact(
            'totalEarned', 'thisMonth', 'totalCommission',
            'ordersCount', 'recent', 'monthlyEarnings'
        ));
    }
}
