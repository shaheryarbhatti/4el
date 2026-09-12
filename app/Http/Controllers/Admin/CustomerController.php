<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

/**
 * Admin management of customers (users with the "customer" role).
 * Server-side DataTables listing with per-customer orders / spend / wishlist.
 */
class CustomerController extends Controller
{
    public function index(Request $request)
    {
        // ---- DataTables AJAX (server-side) ----
        if ($request->has('draw')) {
            $query = User::role('customer')
                ->select('users.*')
                ->selectRaw('(select count(*) from orders where orders.user_id = users.id) as orders_count')
                ->selectRaw('(select coalesce(sum(grand_total),0) from orders where orders.user_id = users.id and payment_status = "paid") as total_spent')
                ->selectRaw('(select count(*) from wishlists where wishlists.user_id = users.id) as wishlist_count');

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            if ($request->filled('q')) {
                $term = '%'.$request->q.'%';
                $query->where(fn ($w) => $w->where('name', 'like', $term)->orWhere('email', 'like', $term));
            }

            $query->latest();

            return DataTables::of($query)
                ->addColumn('customer_html', function (User $u) {
                    $avatar = $u->avatar
                        ? '<img src="'.asset('storage/'.$u->avatar).'" class="cu-avatar" alt="">'
                        : '<div class="cu-avatar cu-avatar--ph">'.mb_strtoupper(mb_substr($u->name, 0, 1)).'</div>';
                    return '<div class="cu-cust">'.$avatar.'<div><div class="cu-cust__name">'.e($u->name).'</div>'
                        .'<div class="cu-cust__email">'.e($u->email).'</div></div></div>';
                })
                ->addColumn('phone_html', fn (User $u) => $u->phone ? e($u->phone) : '<span class="text-muted">—</span>')
                ->addColumn('joined_html', fn (User $u) => '<span class="cu-date">'.$u->created_at->format('d M Y').'</span>')
                ->addColumn('orders_html', fn (User $u) => '<span class="cu-chip cu-chip--order"><i class="bx bx-cart"></i> '.(int) $u->orders_count.'</span>')
                ->addColumn('spent_html', function (User $u) {
                    $cur = setting('currency_symbol', '$');
                    return '<span class="cu-spent">'.$cur.number_format((float) $u->total_spent, 2).'</span>';
                })
                ->addColumn('wishlist_html', fn (User $u) => '<span class="cu-chip cu-chip--wish"><i class="bx bx-heart"></i> '.(int) $u->wishlist_count.'</span>')
                ->addColumn('status_html', function (User $u) {
                    return $u->status === 'blocked'
                        ? '<span class="cu-badge cu-badge--blocked"><i class="bx bx-block"></i> Blocked</span>'
                        : '<span class="cu-badge cu-badge--active"><i class="bx bx-check-circle"></i> Active</span>';
                })
                ->addColumn('actions', function (User $u) {
                    $view = '<a href="'.route('admin.customers.show', $u).'" class="cu-act cu-act--view" title="View"><i class="bx bx-show"></i></a>';
                    $toggle = '<form action="'.route('admin.customers.toggle', $u).'" method="POST" class="d-inline" onsubmit="return confirm(\''
                        .($u->status === 'blocked' ? 'Unblock' : 'Block').' this customer?\')">'.csrf_field().method_field('PUT');
                    $toggle .= $u->status === 'blocked'
                        ? '<button class="cu-act cu-act--ok" title="Unblock"><i class="bx bx-check"></i></button>'
                        : '<button class="cu-act cu-act--no" title="Block"><i class="bx bx-block"></i></button>';
                    $toggle .= '</form>';
                    return '<div class="cu-actions">'.$view.$toggle.'</div>';
                })
                ->rawColumns(['customer_html','phone_html','joined_html','orders_html','spent_html','wishlist_html','status_html','actions'])
                ->make(true);
        }

        // ---- Dashboard summary ----
        $customerIds = User::role('customer')->pluck('id');

        $stats = [
            'total'   => $customerIds->count(),
            'active'  => User::role('customer')->where('status', '!=', 'blocked')->count(),
            'blocked' => User::role('customer')->where('status', 'blocked')->count(),
            'revenue' => (float) Order::whereIn('user_id', $customerIds)->where('payment_status', 'paid')->sum('grand_total'),
            'new'     => User::role('customer')->where('created_at', '>=', now()->subDays(30))->count(),
        ];

        return view('admin.customers.index', compact('stats'));
    }

    public function show(User $customer)
    {
        abort_unless($customer->hasRole('customer'), 404);

        $orders = $customer->orders()->withCount('items')->latest()->take(10)->get();

        $metrics = [
            'orders'   => $customer->orders()->count(),
            'spent'    => (float) $customer->orders()->where('payment_status', 'paid')->sum('grand_total'),
            'wishlist' => Wishlist::where('user_id', $customer->id)->count(),
        ];

        return view('admin.customers.show', compact('customer', 'orders', 'metrics'));
    }

    /** Block / unblock a customer. */
    public function toggle(User $customer)
    {
        abort_unless($customer->hasRole('customer'), 404);

        $customer->status = $customer->status === 'blocked' ? 'active' : 'blocked';
        $customer->save();

        return back()->with('success', $customer->name.' is now '.$customer->status.'.');
    }
}
