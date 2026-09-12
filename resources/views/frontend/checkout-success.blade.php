@extends('frontend.layouts.app')
@section('title', 'Order Confirmed — ' . config('app.name'))

@push('styles')
<style>
.cs-page { padding: 52px 0 80px; min-height: 70vh; background: #f5f6f7; }

.cs-card {
    max-width: 720px; margin: 0 auto;
    background: #fff; border: 1px solid #e2e2e2; border-radius: 16px;
    overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,.07);
}

/* ── hero banner ── */
.cs-hero {
    background: linear-gradient(135deg, #1565c0, #3665f3, #6fa3ff);
    padding: 40px 32px; text-align: center; position: relative; overflow: hidden;
}
.cs-hero::before {
    content: ''; position: absolute; inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}
.cs-check-circle {
    width: 80px; height: 80px; border-radius: 50%; background: rgba(255,255,255,.2);
    border: 3px solid rgba(255,255,255,.6);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 18px; font-size: 34px; color: #fff; position: relative; z-index: 1;
}
.cs-hero h2 { color: #fff; font-size: 24px; font-weight: 800; margin: 0 0 8px; position: relative; z-index: 1; }
.cs-hero p  { color: rgba(255,255,255,.85); font-size: 14px; margin: 0; position: relative; z-index: 1; }

/* ── order number bar ── */
.cs-order-bar {
    background: #f0f4ff; border-bottom: 1px solid #dde6ff;
    padding: 14px 24px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;
}
.cs-order-bar__label { font-size: 12px; color: #767676; text-transform: uppercase; letter-spacing: .06em; font-weight: 700; }
.cs-order-bar__num   { font-size: 18px; font-weight: 800; color: #1565c0; letter-spacing: .04em; }
.cs-order-bar__status {
    display: inline-flex; align-items: center; gap: 6px;
    background: #e8f5e9; color: #2e7d32; border-radius: 20px; padding: 4px 12px; font-size: 12px; font-weight: 700;
}

/* ── body ── */
.cs-body { padding: 28px 28px; }

/* ── section headers ── */
.cs-sec-head {
    font-size: 12px; font-weight: 800; color: #555; text-transform: uppercase;
    letter-spacing: .07em; margin: 0 0 12px; display: flex; align-items: center; gap: 8px;
}
.cs-sec-head i { color: #3665f3; font-size: 13px; }

/* ── items list ── */
.cs-items { border: 1px solid #e9ecef; border-radius: 10px; overflow: hidden; margin-bottom: 24px; }
.cs-item {
    display: flex; align-items: flex-start; gap: 14px; padding: 14px 16px;
    border-bottom: 1px solid #f0f0f0;
}
.cs-item:last-child { border-bottom: none; }
.cs-item__img {
    width: 56px; height: 56px; border-radius: 8px; object-fit: cover;
    border: 1px solid #e5e5e5; flex-shrink: 0; background: #f7f7f7;
}
.cs-item__img-ph {
    width: 56px; height: 56px; border-radius: 8px; flex-shrink: 0;
    background: #f0f0f0; display: flex; align-items: center; justify-content: center; color: #ddd; font-size: 22px;
}
.cs-item__name  { font-size: 14px; font-weight: 700; color: #111; flex: 1; line-height: 1.35; }
.cs-item__meta  { font-size: 12px; color: #767676; margin-top: 3px; }
.cs-item__price { font-size: 14px; font-weight: 700; color: #3665f3; white-space: nowrap; }

/* ── totals grid ── */
.cs-totals { border: 1px solid #e9ecef; border-radius: 10px; overflow: hidden; margin-bottom: 24px; }
.cs-tot-row {
    display: flex; justify-content: space-between; align-items: center;
    padding: 10px 16px; font-size: 14px; border-bottom: 1px solid #f5f5f5; color: #444;
}
.cs-tot-row:last-child { border-bottom: none; }
.cs-tot-row.grand { font-size: 17px; font-weight: 800; color: #111; background: #f9fbff; }
.cs-tot-row.grand span:last-child { color: #3665f3; }
.cs-tot-row.discount { color: #2e7d32; font-weight: 600; }

/* ── info grid ── */
.cs-info-grid { display: flex; gap: 16px; flex-wrap: wrap; margin-bottom: 28px; }
.cs-info-box {
    flex: 1; min-width: 140px; background: #f9fbff; border: 1px solid #dde6ff;
    border-radius: 10px; padding: 14px 16px;
}
.cs-info-box__label { font-size: 11px; font-weight: 700; color: #767676; text-transform: uppercase; letter-spacing: .06em; margin-bottom: 5px; }
.cs-info-box__val   { font-size: 13.5px; font-weight: 600; color: #222; line-height: 1.5; }

/* ── actions ── */
.cs-actions { display: flex; gap: 12px; flex-wrap: wrap; }
.cs-btn-primary {
    flex: 1; min-width: 140px; background: linear-gradient(135deg,#3665f3,#1e4fc4);
    color: #fff; border: none; border-radius: 10px; padding: 14px;
    font-size: 14px; font-weight: 700; cursor: pointer; text-align: center;
    text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 8px;
    box-shadow: 0 4px 14px rgba(54,101,243,.3); transition: all .2s;
}
.cs-btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(54,101,243,.4); color: #fff; }
.cs-btn-outline {
    flex: 1; min-width: 140px; background: #fff; color: #3665f3;
    border: 2px solid #3665f3; border-radius: 10px; padding: 13px;
    font-size: 14px; font-weight: 700; cursor: pointer; text-align: center;
    text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 8px;
    transition: all .2s;
}
.cs-btn-outline:hover { background: #f0f4ff; color: #3665f3; }
</style>
@endpush

@section('content')
<div class="cs-page">
<div class="container">
<div class="cs-card">

    {{-- Hero --}}
    <div class="cs-hero">
        <div class="cs-check-circle"><i class="fas fa-check"></i></div>
        <h2>Order Placed Successfully!</h2>
        <p>Thank you for your purchase. We've received your order and will process it shortly.</p>
    </div>

    {{-- Order number bar --}}
    <div class="cs-order-bar">
        <div>
            <div class="cs-order-bar__label">Order Number</div>
            <div class="cs-order-bar__num">#{{ $order->order_number }}</div>
        </div>
        <div>
            <div class="cs-order-bar__label">Payment</div>
            <div style="margin-top:4px">
                @if ($order->isPaid())
                    <span class="cs-order-bar__status">
                        <i class="fas fa-check-circle"></i> Paid
                    </span>
                @else
                    <span style="background:#fff8e1;color:#f59e0b;border-radius:20px;padding:4px 12px;font-size:12px;font-weight:700;display:inline-flex;align-items:center;gap:6px;">
                        <i class="fas fa-clock"></i> Pending
                    </span>
                @endif
            </div>
        </div>
        <div style="text-align:right">
            <div class="cs-order-bar__label">Placed on</div>
            <div style="font-size:14px;font-weight:600;color:#333;margin-top:4px;">
                {{ $order->created_at->format('M d, Y · h:i A') }}
            </div>
        </div>
    </div>

    <div class="cs-body">

        {{-- Items --}}
        <div class="cs-sec-head"><i class="fas fa-box"></i> Items Ordered</div>
        <div class="cs-items">
            @foreach ($order->items as $item)
            <div class="cs-item">
                @php $img = optional($item->product?->primaryImage)->path; @endphp
                @if ($img)
                    @php $imgUrl = str_starts_with($img,'frontend-assets/') ? asset($img) : asset('storage/'.$img); @endphp
                    <img src="{{ $imgUrl }}" alt="" class="cs-item__img">
                @else
                    <div class="cs-item__img-ph"><i class="fas fa-image"></i></div>
                @endif
                <div style="flex:1">
                    <div class="cs-item__name">{{ $item->product_name }}</div>
                    <div class="cs-item__meta">Qty: {{ $item->quantity }}</div>
                </div>
                <div class="cs-item__price">${{ number_format($item->line_total, 2) }}</div>
            </div>
            @endforeach
        </div>

        {{-- Totals --}}
        <div class="cs-sec-head"><i class="fas fa-receipt"></i> Order Total</div>
        <div class="cs-totals">
            <div class="cs-tot-row">
                <span>Subtotal</span>
                <span>${{ number_format($order->subtotal, 2) }}</span>
            </div>
            @if ($order->shipping_total > 0)
            <div class="cs-tot-row">
                <span>Shipping</span>
                <span>${{ number_format($order->shipping_total, 2) }}</span>
            </div>
            @else
            <div class="cs-tot-row" style="color:#2e7d32">
                <span>Shipping</span>
                <span><i class="fas fa-check-circle"></i> Free</span>
            </div>
            @endif
            @if ($order->tax_total > 0)
            <div class="cs-tot-row">
                <span>Tax</span>
                <span>${{ number_format($order->tax_total, 2) }}</span>
            </div>
            @endif
            @if ($order->discount_total > 0)
            <div class="cs-tot-row discount">
                <span><i class="fas fa-tag"></i> Discount
                    @if($order->coupon_code) ({{ $order->coupon_code }}) @endif
                </span>
                <span>−${{ number_format($order->discount_total, 2) }}</span>
            </div>
            @endif
            <div class="cs-tot-row grand">
                <span>Grand Total</span>
                <span>${{ number_format($order->grand_total, 2) }}</span>
            </div>
        </div>

        {{-- Info: shipping + payment ── --}}
        <div class="cs-sec-head"><i class="fas fa-info-circle"></i> Delivery Details</div>
        <div class="cs-info-grid">
            <div class="cs-info-box">
                <div class="cs-info-box__label"><i class="fas fa-map-marker-alt"></i> Ship To</div>
                <div class="cs-info-box__val">
                    {{ $order->customer_name }}<br>
                    {{ $order->address_line }}<br>
                    @if($order->city){{ $order->city }}{{ $order->state ? ', '.$order->state : '' }}{{ $order->postal_code ? ' '.$order->postal_code : '' }}<br>@endif
                    {{ $order->country }}
                </div>
            </div>
            <div class="cs-info-box">
                <div class="cs-info-box__label"><i class="fas fa-envelope"></i> Contact</div>
                <div class="cs-info-box__val">
                    {{ $order->customer_email }}
                    @if($order->customer_phone)<br>{{ $order->customer_phone }}@endif
                </div>
            </div>
            <div class="cs-info-box">
                <div class="cs-info-box__label"><i class="fas fa-credit-card"></i> Payment</div>
                <div class="cs-info-box__val">
                    @php $pm = ['cod'=>'Cash on Delivery','stripe'=>'Stripe Card','paypal'=>'PayPal']; @endphp
                    {{ $pm[$order->payment_method] ?? ucfirst($order->payment_method) }}
                    <br>
                    @if ($order->isPaid())
                        <span style="color:#2e7d32;font-size:12px;"><i class="fas fa-check-circle"></i> Paid</span>
                    @else
                        <span style="color:#f59e0b;font-size:12px;"><i class="fas fa-clock"></i> Pending</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Actions ── --}}
        <div class="cs-actions">
            <a href="{{ route('home') }}" class="cs-btn-primary">
                <i class="fas fa-store"></i> Continue Shopping
            </a>
            <a href="{{ route('home') }}" class="cs-btn-outline">
                <i class="fas fa-list"></i> My Orders
            </a>
        </div>

    </div>
</div>
</div>
</div>
@endsection
