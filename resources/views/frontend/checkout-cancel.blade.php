@extends('frontend.layouts.app')
@section('title', 'Payment Cancelled — ' . config('app.name'))

@push('styles')
<style>
.cc-page { padding: 52px 0 80px; min-height: 70vh; background: #f5f6f7; }
.cc-card {
    max-width: 560px; margin: 0 auto;
    background: #fff; border: 1px solid #e2e2e2; border-radius: 16px;
    overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,.07);
    text-align: center;
}
.cc-hero {
    background: linear-gradient(135deg, #b71c1c, #e53238, #ff6b6b);
    padding: 40px 32px 32px; position: relative; overflow: hidden;
}
.cc-hero::before {
    content: ''; position: absolute; inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.06'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}
.cc-icon-circle {
    width: 80px; height: 80px; border-radius: 50%;
    background: rgba(255,255,255,.2); border: 3px solid rgba(255,255,255,.6);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 18px; font-size: 34px; color: #fff; position: relative; z-index: 1;
}
.cc-hero h2 { color: #fff; font-size: 22px; font-weight: 800; margin: 0 0 8px; position: relative; z-index: 1; }
.cc-hero p  { color: rgba(255,255,255,.85); font-size: 14px; margin: 0; position: relative; z-index: 1; }

.cc-body { padding: 30px 32px 32px; }

.cc-msg {
    background: #fff8f8; border: 1px solid #fca5a5; border-radius: 10px;
    padding: 16px 20px; margin-bottom: 24px; font-size: 14px; color: #7f1d1d; line-height: 1.6;
}
.cc-msg i { margin-right: 6px; }

@if (isset($order))
.cc-order-chip {
    display: inline-flex; align-items: center; gap: 8px;
    background: #f9fbff; border: 1px solid #dde6ff; border-radius: 8px;
    padding: 10px 18px; margin-bottom: 24px; font-size: 14px; font-weight: 700; color: #1565c0;
}
@endif

.cc-actions { display: flex; flex-direction: column; gap: 10px; }
.cc-btn-primary {
    background: linear-gradient(135deg,#3665f3,#1e4fc4); color: #fff;
    border: none; border-radius: 10px; padding: 15px;
    font-size: 15px; font-weight: 700; cursor: pointer; text-align: center;
    text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 8px;
    box-shadow: 0 4px 14px rgba(54,101,243,.3); transition: all .2s;
}
.cc-btn-primary:hover { transform: translateY(-2px); color: #fff; box-shadow: 0 8px 20px rgba(54,101,243,.4); }
.cc-btn-ghost {
    background: transparent; color: #767676;
    border: 1.5px solid #ddd; border-radius: 10px; padding: 12px;
    font-size: 14px; font-weight: 600; cursor: pointer; text-align: center;
    text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 8px;
    transition: all .2s;
}
.cc-btn-ghost:hover { border-color: #bbb; color: #444; background: #f9f9f9; }
.cc-btn-cart {
    background: #fff; color: #3665f3;
    border: 2px solid #3665f3; border-radius: 10px; padding: 13px;
    font-size: 14px; font-weight: 700; cursor: pointer; text-align: center;
    text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 8px;
    transition: all .2s;
}
.cc-btn-cart:hover { background: #f0f4ff; color: #3665f3; }

.cc-help { margin-top: 20px; font-size: 12.5px; color: #aaa; }
</style>
@endpush

@section('content')
<div class="cc-page">
<div class="container">
<div class="cc-card">

    {{-- Hero --}}
    <div class="cc-hero">
        <div class="cc-icon-circle"><i class="fas fa-times"></i></div>
        <h2>Payment Cancelled</h2>
        <p>Your payment was not completed. No charges have been made.</p>
    </div>

    <div class="cc-body">

        @if (isset($order))
        <div class="cc-order-chip">
            <i class="fas fa-receipt"></i> Order #{{ $order->order_number }}
        </div>
        @endif

        <div class="cc-msg">
            <i class="fas fa-info-circle"></i>
            Your order was <strong>not placed</strong>. You cancelled the payment or something went wrong during the payment process.
            Your cart items are still available — you can try again or choose a different payment method.
        </div>

        <div class="cc-actions">
            <a href="{{ route('checkout.index') }}" class="cc-btn-primary">
                <i class="fas fa-redo"></i> Try Again
            </a>
            <a href="{{ route('cart.index') }}" class="cc-btn-cart">
                <i class="fas fa-shopping-cart"></i> Back to Cart
            </a>
            <a href="{{ route('home') }}" class="cc-btn-ghost">
                <i class="fas fa-home"></i> Return to Homepage
            </a>
        </div>

        <p class="cc-help">
            Having trouble? Contact our support team for help with your order.
        </p>

    </div>
</div>
</div>
</div>
@endsection
