{{-- Admin order detail — rich, professional --}}
@extends('admin.layouts.app')
@section('title', 'Order '.$order->order_number)
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Orders</a></li>
    <li class="breadcrumb-item active">{{ $order->order_number }}</li>
@endsection

@push('styles')<link rel="stylesheet" href="{{ asset('admin-assets/css/admin-orders.css') }}">@endpush

@section('content')
@php
    $cur = setting('currency_symbol', '$');
    $pc  = ['unpaid'=>'unpaid','paid'=>'paid','failed'=>'failed','refunded'=>'refunded'][$order->payment_status] ?? 'unpaid';
    $sc  = ['pending'=>'pending','processing'=>'processing','completed'=>'completed','cancelled'=>'cancelled'][$order->status] ?? 'pending';
@endphp

{{-- Hero --}}
<div class="aod-hero">
    <div class="aod-hero__icon"><i class="bx bx-receipt"></i></div>
    <div class="aod-hero__info">
        <h1 class="aod-hero__num">{{ $order->order_number }}</h1>
        <div class="aod-hero__date"><i class="bx bx-calendar"></i> {{ $order->created_at->format('d M Y, g:i A') }}
            · <i class="bx bx-credit-card"></i> {{ strtoupper((string) $order->payment_method) }}</div>
    </div>
    <div class="aod-hero__right">
        <div class="aod-hero__total">{{ $cur }}{{ number_format($order->grand_total, 2) }}</div>
        <div class="aod-hero__badges">
            <span class="ao-badge ao-badge--{{ $pc }}">{{ ucfirst($order->payment_status) }}</span>
            <span class="ao-badge ao-badge--{{ $sc }}">{{ ucfirst($order->status) }}</span>
        </div>
    </div>
</div>

<div class="aod-grid">
    {{-- LEFT: items grouped by vendor --}}
    <div>
        @foreach ($byVendor as $vendorId => $items)
            @php $vendorName = optional($items->first()->vendor)->name ?? 'Platform'; @endphp
            <div class="aod-card">
                <div class="aod-card__head">
                    <span class="aod-vendor-ic"><i class="bx bx-store-alt"></i></span> {{ $vendorName }}
                </div>
                <div class="aod-card__body">
                    <table class="aod-otable">
                        <thead><tr><th>Product</th><th>Price</th><th>Qty</th><th>Ship</th><th>Tax</th><th>Line Total</th><th>Fulfilment</th></tr></thead>
                        <tbody>
                            @foreach ($items as $it)
                                @php
                                    $img = $it->product?->primaryImage;
                                    $src = $img ? (str_starts_with($img->path, 'frontend-assets/') ? asset($img->path) : asset('storage/'.$img->path)) : null;
                                @endphp
                                <tr>
                                    <td>
                                        <div class="aod-prodcell">
                                            @if ($src)<img src="{{ $src }}" class="aod-prodcell__img" alt="">@else<div class="aod-prodcell__img-ph"><i class="bx bx-image"></i></div>@endif
                                            <span class="aod-prodcell__name">{{ $it->product_name }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $cur }}{{ number_format($it->price, 2) }}</td>
                                    <td>{{ $it->quantity }}</td>
                                    <td>{{ $cur }}{{ number_format($it->shipping_cost, 2) }}</td>
                                    <td>{{ $cur }}{{ number_format($it->tax_amount, 2) }}</td>
                                    <td class="aod-linetotal">{{ $cur }}{{ number_format($it->line_total, 2) }}</td>
                                    <td><span class="ao-badge ao-badge--{{ $it->vendor_status === 'completed' ? 'completed' : ($it->vendor_status === 'shipped' ? 'processing' : 'pending') }}">{{ ucfirst($it->vendor_status) }}</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="aod-card__foot">
                    <i class="bx bx-wallet"></i> Vendor earns <b>{{ $cur }}{{ number_format($items->sum('vendor_amount'), 2) }}</b>
                    · Platform commission <b>{{ $cur }}{{ number_format($items->sum('commission_amount'), 2) }}</b>
                </div>
            </div>
        @endforeach
    </div>

    {{-- RIGHT --}}
    <div>
        {{-- Summary --}}
        <div class="aod-summary">
            <div class="aod-summary__head"><i class="bx bx-calculator"></i> Order Summary</div>
            <div class="aod-summary__body">
                <div class="aod-srow"><span>Subtotal</span><span>{{ $cur }}{{ number_format($order->subtotal, 2) }}</span></div>
                @if ($order->discount_total > 0)
                    <div class="aod-srow discount"><span>Discount {{ $order->coupon_code ? '('.$order->coupon_code.')' : '' }}</span><span>−{{ $cur }}{{ number_format($order->discount_total, 2) }}</span></div>
                @endif
                <div class="aod-srow"><span>Shipping</span><span>{{ $cur }}{{ number_format($order->shipping_total, 2) }}</span></div>
                <div class="aod-srow"><span>Tax</span><span>{{ $cur }}{{ number_format($order->tax_total, 2) }}</span></div>
                <div class="aod-srow grand"><span>Grand Total</span><span>{{ $cur }}{{ number_format($order->grand_total, 2) }}</span></div>
            </div>
        </div>

        {{-- Customer & shipping --}}
        <div class="aod-info-card">
            <div class="aod-info-card__head"><i class="bx bx-user"></i> Customer &amp; Shipping</div>
            <div class="aod-info-card__body">
                <div class="aod-cust-name">{{ $order->customer_name }}</div>
                <div class="aod-cust-line">{{ $order->customer_email }}</div>
                <div class="aod-cust-line">{{ $order->customer_phone }}</div>
                <div class="aod-addr">
                    {{ $order->address_line }}<br>
                    {{ trim($order->city.' '.$order->state.' '.$order->postal_code) }}<br>
                    {{ $order->country }}
                </div>
                @if ($order->notes)
                    <div class="aod-notes"><i class="bx bx-note"></i> {{ $order->notes }}</div>
                @endif
            </div>
        </div>

        {{-- Update status --}}
        <div class="aod-info-card">
            <div class="aod-info-card__head"><i class="bx bx-edit"></i> Update Status</div>
            <div class="aod-info-card__body">
                <form action="{{ route('admin.orders.update-status', $order) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="aod-field">
                        <label>Order Status</label>
                        <select name="status" class="aod-select">
                            @foreach (['pending','processing','completed','cancelled'] as $s)
                                <option value="{{ $s }}" @selected($order->status === $s)>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="aod-field">
                        <label>Payment Status</label>
                        <select name="payment_status" class="aod-select">
                            @foreach (['unpaid','paid','failed','refunded'] as $s)
                                <option value="{{ $s }}" @selected($order->payment_status === $s)>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button class="aod-save"><i class="bx bx-save"></i> Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
