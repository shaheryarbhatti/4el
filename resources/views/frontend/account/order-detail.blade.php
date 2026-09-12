{{-- Customer order detail --}}
@extends('frontend.account.layout')
@section('title', 'Order '.$order->order_number)
@push('styles')<link rel="stylesheet" href="{{ asset('frontend-assets/css/checkout.css') }}">@endpush

@section('account_content')
@php $cur = setting('currency_symbol','$'); @endphp
<div>
    <div class="co-head d-flex justify-content-between align-items-center">
        <div>
            <h1>Order {{ $order->order_number }}</h1>
            <p>Placed on {{ $order->created_at->format('d M Y, g:i A') }}</p>
        </div>
        <a href="{{ route('account.orders') }}" class="btn btn-outline-dark">← All Orders</a>
    </div>

    <div class="co-grid">
        {{-- Items --}}
        <div class="co-card">
            <div class="co-card__head"><i class="fas fa-box"></i><h2 class="co-card__title">Items</h2></div>
            <div class="co-card__body">
                <table class="table align-middle mb-0">
                    <thead><tr><th>Product</th><th>Price</th><th>Qty</th><th class="text-end">Total</th></tr></thead>
                    <tbody>
                        @foreach ($order->items as $it)
                            <tr>
                                <td>
                                    @if ($it->product)
                                        <a href="{{ route('product.show', $it->product->slug) }}" class="text-dark fw-semibold text-decoration-none">{{ $it->product_name }}</a>
                                    @else
                                        <span class="fw-semibold">{{ $it->product_name }}</span>
                                    @endif
                                </td>
                                <td>{{ $cur }}{{ number_format($it->price, 2) }}</td>
                                <td>{{ $it->quantity }}</td>
                                <td class="text-end fw-semibold">{{ $cur }}{{ number_format($it->line_total, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Summary + address --}}
        <div class="co-summary">
            <div class="co-card">
                <div class="co-card__head"><i class="fas fa-receipt"></i><h2 class="co-card__title">Summary</h2></div>
                <div class="co-card__body">
                    <div class="co-total-row"><span>Subtotal</span><span>{{ $cur }}{{ number_format($order->subtotal, 2) }}</span></div>
                    @if ($order->discount_total > 0)
                        <div class="co-total-row discount"><span>Discount {{ $order->coupon_code ? '('.$order->coupon_code.')' : '' }}</span><span>−{{ $cur }}{{ number_format($order->discount_total, 2) }}</span></div>
                    @endif
                    <div class="co-total-row"><span>Shipping</span><span>{{ $cur }}{{ number_format($order->shipping_total, 2) }}</span></div>
                    <div class="co-total-row"><span>Tax</span><span>{{ $cur }}{{ number_format($order->tax_total, 2) }}</span></div>
                    <div class="co-total-row grand"><span>Total</span><span>{{ $cur }}{{ number_format($order->grand_total, 2) }}</span></div>
                    <div class="co-total-row"><span>Payment</span><span class="text-uppercase">{{ $order->payment_method }} · {{ ucfirst($order->payment_status) }}</span></div>
                </div>
            </div>
            <div class="co-card">
                <div class="co-card__head"><i class="fas fa-truck"></i><h2 class="co-card__title">Delivery</h2></div>
                <div class="co-card__body">
                    <p class="mb-1"><b>{{ $order->customer_name }}</b></p>
                    <p class="mb-1 text-muted">{{ $order->customer_phone }}</p>
                    <p class="mb-0">{{ $order->address_line }}, {{ $order->city }} {{ $order->state }} {{ $order->postal_code }}, {{ $order->country }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
