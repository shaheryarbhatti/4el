<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

/**
 * Vendor order management — a vendor only sees the ORDER LINES that belong to
 * their own store (the per-vendor split), never other vendors' items.
 */
class OrderController extends Controller
{
    private function vendorId(): int
    {
        return auth()->id();
    }

    public function index(Request $request)
    {
        // Orders that contain at least one of this vendor's items.
        $orders = Order::whereHas('items', fn ($q) => $q->where('vendor_id', $this->vendorId()))
            ->with(['items' => fn ($q) => $q->where('vendor_id', $this->vendorId())])
            ->when($request->filled('q'), fn ($q) => $q->where('order_number', 'like', '%'.$request->q.'%'))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('vendor.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        // Only this vendor's items on the order.
        $items = $order->items()->where('vendor_id', $this->vendorId())->with('product')->get();

        // Block access if none of the order belongs to this vendor.
        abort_if($items->isEmpty(), 403);

        return view('vendor.orders.show', compact('order', 'items'));
    }

    /** Update fulfilment status of one of the vendor's own line items. */
    public function updateItemStatus(Request $request, OrderItem $item)
    {
        abort_unless($item->vendor_id === $this->vendorId(), 403);

        $data = $request->validate([
            'vendor_status' => ['required', 'in:pending,shipped,completed'],
        ]);

        $item->update($data);

        return back()->with('success', 'Item status updated.');
    }
}
