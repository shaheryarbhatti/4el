<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

/**
 * Admin order management — sees ALL orders across every vendor.
 * The listing uses Yajra DataTables (server-side) so it stays fast even with
 * many thousands of orders (only one page is queried/rendered at a time).
 */
class OrderController extends Controller
{
    public function index(Request $request)
    {
        // ---- DataTables AJAX (server-side) ----
        if ($request->has('draw')) {
            $query = Order::query()
                ->withCount('items')
                ->with(['items.product.primaryImage', 'items.vendor.vendorProfile'])
                ->select('orders.*');

            if ($request->filled('q')) {
                $term = '%'.$request->q.'%';
                $query->where(fn ($w) => $w->where('order_number', 'like', $term)
                    ->orWhere('customer_name', 'like', $term)
                    ->orWhere('customer_email', 'like', $term));
            }
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            if ($request->filled('payment_status')) {
                $query->where('payment_status', $request->payment_status);
            }

            $query->latest();

            return DataTables::of($query)
                ->addColumn('order_html', function (Order $o) {
                    return '<div class="ao-onum">'.e($o->order_number).'</div>'
                        .'<div class="ao-odate"><i class="bx bx-calendar"></i> '.$o->created_at->format('d M Y, g:i A').'</div>';
                })
                ->addColumn('customer_html', function (Order $o) {
                    return '<span class="ao-cust__name">'.e($o->customer_name).'</span>'
                        .'<br><span class="ao-cust__email">'.e($o->customer_email).'</span>';
                })
                ->addColumn('products_html', fn (Order $o) => $this->itemsHtml($o))
                ->addColumn('payment_html', function (Order $o) {
                    $cls = ['unpaid'=>'unpaid','paid'=>'paid','failed'=>'failed','refunded'=>'refunded'][$o->payment_status] ?? 'unpaid';
                    return '<span class="ao-badge ao-badge--'.$cls.'"><i class="bx bx-credit-card"></i> '.ucfirst($o->payment_status).'</span>'
                        .'<div class="ao-badge__method">'.strtoupper((string) $o->payment_method).'</div>';
                })
                ->addColumn('status_html', function (Order $o) {
                    $cls = ['pending'=>'pending','processing'=>'processing','completed'=>'completed','cancelled'=>'cancelled'][$o->status] ?? 'pending';
                    return '<span class="ao-badge ao-badge--'.$cls.'">'.ucfirst($o->status).'</span>';
                })
                ->addColumn('total_html', function (Order $o) {
                    $cur = setting('currency_symbol', '$');
                    return '<div class="ao-total">'.$cur.number_format($o->grand_total, 2)
                        .'<small>'.$o->items_count.' item(s)</small></div>';
                })
                ->addColumn('actions', function (Order $o) {
                    return '<a href="'.route('admin.orders.show', $o).'" class="ao-view" title="View order"><i class="bx bx-show"></i></a>';
                })
                ->rawColumns(['order_html','customer_html','products_html','payment_html','status_html','total_html','actions'])
                ->make(true);
        }

        // ---- Dashboard summary (cheap COUNT/SUM queries) ----
        // Vendor + platform earnings come from PAID orders' line items.
        $vendorEarnings = (float) OrderItem::join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.payment_status', 'paid')->sum('order_items.vendor_amount');
        $platformEarnings = (float) OrderItem::join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.payment_status', 'paid')->sum('order_items.commission_amount');

        $stats = [
            'total'      => Order::count(),
            'pending'    => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'completed'  => Order::where('status', 'completed')->count(),
            'revenue'    => (float) Order::where('payment_status', 'paid')->sum('grand_total'),
            'unpaid'     => Order::where('payment_status', 'unpaid')->count(),
            'vendor_earnings'   => $vendorEarnings,
            'platform_earnings' => $platformEarnings,
        ];

        return view('admin.orders.index', compact('stats'));
    }

    /** Build the stacked "Products" cell HTML for one order (image/name/vendor/stock/prices). */
    private function itemsHtml(Order $o): string
    {
        $cur = setting('currency_symbol', '$');
        $rows = '';

        foreach ($o->items as $item) {
            $product = $item->product;
            $img = $product?->primaryImage;
            if ($img) {
                $src = str_starts_with($img->path, 'frontend-assets/') ? asset($img->path) : asset('storage/'.$img->path);
                $thumb = '<img src="'.$src.'" alt="" class="ao-item__img">';
            } else {
                $thumb = '<div class="ao-item__img-ph"><i class="bx bx-image"></i></div>';
            }

            $vendor = $item->vendor?->vendorProfile?->store_name ?? $item->vendor?->name ?? 'Platform';
            $sold   = (float) $item->price;
            $actual = $product ? (float) $product->price : $sold;
            $stock  = $product?->stock;

            if (is_null($stock)) {
                $stockBadge = '<span class="ao-stock ao-stock--out"><i class="bx bx-x"></i> No product</span>';
            } elseif ($stock <= 0) {
                $stockBadge = '<span class="ao-stock ao-stock--out"><i class="bx bx-error"></i> Out of stock</span>';
            } elseif ($stock <= 5) {
                $stockBadge = '<span class="ao-stock ao-stock--low"><i class="bx bx-error-circle"></i> '.$stock.' left</span>';
            } else {
                $stockBadge = '<span class="ao-stock ao-stock--ok"><i class="bx bx-box"></i> '.$stock.' in stock</span>';
            }

            $prices = '<div class="ao-item__prices"><span class="ao-price-lbl">Sold / Actual</span>'
                .'<span class="ao-price-sold">'.$cur.number_format($sold, 2).'</span>'
                .($actual > $sold ? '<span class="ao-price-actual">'.$cur.number_format($actual, 2).'</span>' : '')
                .'</div>';

            $rows .= '<div class="ao-item">'.$thumb
                .'<div class="ao-item__main"><p class="ao-item__name">'.e(Str::limit($item->product_name, 46)).' <span class="ao-item__qty">× '.$item->quantity.'</span></p>'
                .'<span class="ao-item__vendor"><i class="bx bx-store-alt"></i> '.e($vendor).'</span></div>'
                .$stockBadge.$prices.'</div>';
        }

        return '<div class="ao-items">'.$rows.'</div>';
    }

    public function show(Order $order)
    {
        $order->load(['items.vendor', 'items.product', 'user']);

        // Group items by vendor for the per-vendor breakdown.
        $byVendor = $order->items->groupBy('vendor_id');

        return view('admin.orders.show', compact('order', 'byVendor'));
    }

    /** Update the overall order + payment status. */
    public function updateStatus(Request $request, Order $order)
    {
        $data = $request->validate([
            'status'         => ['required', 'in:pending,processing,completed,cancelled'],
            'payment_status' => ['required', 'in:unpaid,paid,failed,refunded'],
        ]);

        // Stamp paid_at the first time it becomes paid.
        if ($data['payment_status'] === 'paid' && ! $order->paid_at) {
            $order->paid_at = now();
        }

        $order->update($data);

        return back()->with('success', 'Order status updated.');
    }
}
