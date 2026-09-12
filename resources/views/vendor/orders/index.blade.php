{{-- Vendor > Orders (only orders containing this vendor's items) --}}
@extends('vendor.layouts.app')
@section('title', 'My Orders')

@section('vendor_content')
@php $cur = setting('currency_symbol','$'); @endphp
<div class="v-card">
    <div class="v-card__head">
        <h2 class="v-card__title">My Orders</h2>
    </div>
    <div class="v-card__body">
        @if ($orders->count())
            <div class="table-responsive">
                <table class="v-table">
                    <thead><tr><th>Order #</th><th>Date</th><th>My Items</th><th>My Earnings</th><th>Order Status</th><th class="text-right">View</th></tr></thead>
                    <tbody>
                        @foreach ($orders as $order)
                            @php $myItems = $order->items; @endphp
                            <tr>
                                <td class="fw-semibold">{{ $order->order_number }}</td>
                                <td>{{ $order->created_at->format('d M Y') }}</td>
                                <td>{{ $myItems->sum('quantity') }} item(s)</td>
                                <td class="fw-semibold">{{ $cur }}{{ number_format($myItems->sum('vendor_amount'), 2) }}</td>
                                <td><span class="v-pill v-pill--{{ $order->status === 'completed' ? 'approved' : ($order->status === 'cancelled' ? 'rejected' : 'pending') }}">{{ ucfirst($order->status) }}</span></td>
                                <td class="text-right">
                                    <a href="{{ route('vendor.orders.show', $order) }}" class="v-btn v-btn--outline-primary v-btn--sm">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $orders->links() }}</div>
        @else
            <div class="v-empty"><i class="fas fa-receipt fa-2x mb-2"></i><p>No orders yet for your store.</p></div>
        @endif
    </div>
</div>
@endsection
