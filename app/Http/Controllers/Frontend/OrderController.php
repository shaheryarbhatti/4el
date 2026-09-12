<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;

/**
 * Customer order history — a logged-in customer sees only their OWN orders.
 */
class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())
            ->withCount('items')
            ->latest()
            ->paginate(10);

        return view('frontend.account.orders', compact('orders'));
    }

    public function show(Order $order)
    {
        // Own orders only.
        abort_unless($order->user_id === auth()->id(), 403);

        $order->load('items.product');

        return view('frontend.account.order-detail', compact('order'));
    }
}
