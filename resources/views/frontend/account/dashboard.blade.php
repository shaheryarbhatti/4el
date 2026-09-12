{{-- Customer account dashboard --}}
@extends('frontend.account.layout')
@section('title', 'My Account')

@section('account_content')
@php $cur = setting('currency_symbol','$'); @endphp

{{-- Stat cards --}}
<div class="ac-stats">
    @php
        $cards = [
            ['Total Orders', $stats['orders'],    'primary', 'fas fa-box',            route('account.orders')],
            ['Pending',      $stats['pending'],    'warning', 'fas fa-clock',          route('account.orders')],
            ['Completed',    $stats['completed'],  'success', 'fas fa-check-circle',   route('account.orders')],
            ['Wishlist',     $stats['wishlist'],   'pink',    'fas fa-heart',          route('wishlist.index')],
        ];
    @endphp
    @foreach ($cards as [$label, $value, $color, $icon, $link])
        <a href="{{ $link }}" class="ac-stat ac-stat--{{ $color }}" style="text-decoration:none">
            <div class="ac-stat__icon"><i class="{{ $icon }}"></i></div>
            <div>
                <div class="ac-stat__num">{{ $value }}</div>
                <div class="ac-stat__label">{{ $label }}</div>
            </div>
        </a>
    @endforeach
</div>

{{-- Recent orders --}}
<div class="ac-card">
    <div class="ac-card__head">
        <h2 class="ac-card__title">Recent Orders</h2>
        <a href="{{ route('account.orders') }}" class="ac-btn ac-btn--sm ac-btn--light">View all</a>
    </div>
    <div class="ac-card__body">
        @if ($recentOrders->count())
            <div class="table-responsive">
                <table class="ac-table">
                    <thead><tr><th>Order #</th><th>Date</th><th>Items</th><th>Total</th><th>Status</th><th></th></tr></thead>
                    <tbody>
                        @foreach ($recentOrders as $order)
                            <tr>
                                <td class="fw-bold">{{ $order->order_number }}</td>
                                <td>{{ $order->created_at->format('d M Y') }}</td>
                                <td>{{ $order->items_count }}</td>
                                <td class="fw-bold">{{ $cur }}{{ number_format($order->grand_total, 2) }}</td>
                                <td><span class="ac-pill ac-pill--{{ $order->status }}">{{ ucfirst($order->status) }}</span></td>
                                <td class="text-end"><a href="{{ route('account.orders.show', $order) }}" class="ac-btn ac-btn--sm">View</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="ac-empty">
                <i class="fas fa-box-open fa-2x mb-2"></i>
                <p>You haven't placed any orders yet.</p>
                <a href="{{ route('home') }}" class="ac-btn">Start Shopping</a>
            </div>
        @endif
    </div>
</div>
@endsection
