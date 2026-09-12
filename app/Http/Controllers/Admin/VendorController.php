<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use App\Models\VendorProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

/**
 * Admin management of vendor applications / stores.
 * Approving a vendor also grants the "vendor" role so they can access
 * the frontend /vendor area.
 *
 * The listing is server-side (Yajra DataTables) and also shows each vendor's
 * total orders and total earnings (their share of PAID orders).
 */
class VendorController extends Controller
{
    public function index(Request $request)
    {
        // ---- DataTables AJAX (server-side) ----
        if ($request->has('draw')) {
            $query = VendorProfile::query()
                ->with('user')
                ->select('vendor_profiles.*')
                // Per-vendor metrics via correlated subqueries (vendor = users.id)
                ->selectRaw('(select count(*) from products where products.vendor_id = vendor_profiles.user_id) as products_count')
                ->selectRaw('(select count(distinct order_id) from order_items where order_items.vendor_id = vendor_profiles.user_id) as orders_count')
                ->selectRaw('(select coalesce(sum(oi.vendor_amount),0) from order_items oi
                              join orders o on o.id = oi.order_id
                              where oi.vendor_id = vendor_profiles.user_id and o.payment_status = "paid") as earnings');

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            if ($request->filled('q')) {
                $term = '%'.$request->q.'%';
                $query->where(fn ($w) => $w->where('store_name', 'like', $term)
                    ->orWhereHas('user', fn ($u) => $u->where('name', 'like', $term)->orWhere('email', 'like', $term)));
            }

            $query->latest();

            return DataTables::of($query)
                ->addColumn('store_html', function (VendorProfile $v) {
                    if ($v->logo) {
                        $thumb = '<img src="'.asset('storage/'.$v->logo).'" class="av-logo" alt="">';
                    } else {
                        $initial = mb_strtoupper(mb_substr($v->store_name ?? 'S', 0, 1));
                        $thumb   = '<div class="av-logo av-logo--ph">'.$initial.'</div>';
                    }
                    $sub = $v->phone ? e($v->phone) : e($v->slug);
                    return '<div class="av-store">'.$thumb.'<div><div class="av-store__name">'.e($v->store_name).'</div>'
                        .'<div class="av-store__sub">'.$sub.'</div></div></div>';
                })
                ->addColumn('owner_html', function (VendorProfile $v) {
                    return '<div class="av-owner__name">'.e($v->user?->name).'</div>'
                        .'<div class="av-owner__email">'.e($v->user?->email).'</div>';
                })
                ->addColumn('applied_html', fn (VendorProfile $v) => '<span class="av-date">'.$v->created_at->format('d M Y').'</span>')
                ->addColumn('products_html', fn (VendorProfile $v) => '<span class="av-chip av-chip--prod"><i class="bx bx-package"></i> '.(int) $v->products_count.'</span>')
                ->addColumn('orders_html', fn (VendorProfile $v) => '<span class="av-chip av-chip--order"><i class="bx bx-cart"></i> '.(int) $v->orders_count.'</span>')
                ->addColumn('earnings_html', function (VendorProfile $v) {
                    $cur = setting('currency_symbol', '$');
                    return '<span class="av-earn">'.$cur.number_format((float) $v->earnings, 2).'</span>';
                })
                ->addColumn('status_html', function (VendorProfile $v) {
                    $map = ['approved'=>'approved','pending'=>'pending','rejected'=>'rejected'];
                    $cls = $map[$v->status] ?? 'pending';
                    return '<span class="av-badge av-badge--'.$cls.'">'.ucfirst($v->status).'</span>';
                })
                ->addColumn('actions', function (VendorProfile $v) {
                    $show = '<a href="'.route('admin.vendors.show', $v).'" class="av-act av-act--view" title="View"><i class="bx bx-show"></i></a>';
                    $approve = $v->status !== 'approved'
                        ? '<form action="'.route('admin.vendors.approve', $v).'" method="POST" class="d-inline">'.csrf_field().method_field('PUT')
                          .'<button class="av-act av-act--ok" title="Approve"><i class="bx bx-check"></i></button></form>'
                        : '';
                    $reject = $v->status !== 'rejected'
                        ? '<form action="'.route('admin.vendors.reject', $v).'" method="POST" class="d-inline" onsubmit="return confirm(\'Reject this store?\')">'.csrf_field().method_field('PUT')
                          .'<button class="av-act av-act--no" title="Reject"><i class="bx bx-x"></i></button></form>'
                        : '';
                    return '<div class="av-actions">'.$show.$approve.$reject.'</div>';
                })
                ->rawColumns(['store_html','owner_html','applied_html','products_html','orders_html','earnings_html','status_html','actions'])
                ->make(true);
        }

        // ---- Dashboard summary ----
        $paidEarnings = (float) OrderItem::join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.payment_status', 'paid')
            ->sum('order_items.vendor_amount');

        $stats = [
            'total'    => VendorProfile::count(),
            'approved' => VendorProfile::where('status', 'approved')->count(),
            'pending'  => VendorProfile::where('status', 'pending')->count(),
            'rejected' => VendorProfile::where('status', 'rejected')->count(),
            'earnings' => $paidEarnings,
        ];

        return view('admin.vendors.index', compact('stats'));
    }

    public function show(VendorProfile $vendor)
    {
        $vendor->load('user');
        $vendorId = $vendor->user_id;

        $productCount = $vendor->user->products()->count();
        $ordersCount  = OrderItem::where('vendor_id', $vendorId)->distinct('order_id')->count('order_id');
        $earnings     = (float) OrderItem::where('vendor_id', $vendorId)
            ->whereHas('order', fn ($q) => $q->where('payment_status', 'paid'))
            ->sum('vendor_amount');

        $recentProducts = $vendor->user->products()->with('primaryImage')->latest()->take(5)->get();

        return view('admin.vendors.show', compact('vendor', 'productCount', 'ordersCount', 'earnings', 'recentProducts'));
    }

    /** Approve a store: set status + grant the vendor role. */
    public function approve(VendorProfile $vendor)
    {
        $vendor->update(['status' => 'approved']);
        $vendor->user->assignRole('vendor');

        return back()->with('success', "{$vendor->store_name} approved.");
    }

    /** Reject a store: set status + remove the vendor role. */
    public function reject(VendorProfile $vendor)
    {
        $vendor->update(['status' => 'rejected']);
        $vendor->user->removeRole('vendor');

        return back()->with('success', "{$vendor->store_name} rejected.");
    }
}
