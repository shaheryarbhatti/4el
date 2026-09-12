@extends('frontend.layouts.app')
@section('title', 'Checkout — ' . config('app.name'))

@push('styles')
<link rel="stylesheet" href="{{ asset('frontend-assets/css/checkout-autocomplete.css') }}">
<style>
/* ═══════════════════════════════════════════════
   eBay-style Checkout Page
═══════════════════════════════════════════════ */
.co-page { padding: 28px 0 72px; min-height: 70vh; background: #f5f6f7; }

/* ── breadcrumb steps ── */
.co-steps {
    display: flex; align-items: center; gap: 0;
    margin-bottom: 24px; font-size: 13px;
}
.co-step { display: flex; align-items: center; gap: 6px; color: #aaa; }
.co-step.active { color: #3665f3; font-weight: 700; }
.co-step.done   { color: #2e7d32; }
.co-step__num {
    width: 22px; height: 22px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 11px; font-weight: 700; background: #e0e0e0; color: #999;
}
.co-step.active .co-step__num { background: #3665f3; color: #fff; }
.co-step.done   .co-step__num { background: #2e7d32; color: #fff; }
.co-step__sep { margin: 0 10px; color: #ddd; font-size: 16px; }

/* ── layout ── */
.co-layout { display: flex; gap: 24px; align-items: flex-start; }
.co-main   { flex: 1; min-width: 0; }
.co-aside  { flex: 0 0 340px; position: sticky; top: 80px; }
@media (max-width: 900px) { .co-layout { flex-direction: column; } .co-aside { flex: none; width: 100%; position: static; } }

/* ── cards ── */
.co-card {
    background: #fff; border: 1px solid #e2e2e2; border-radius: 12px;
    overflow: hidden; margin-bottom: 18px; box-shadow: 0 1px 4px rgba(0,0,0,.05);
}
.co-card__head {
    display: flex; align-items: center; gap: 12px;
    padding: 16px 20px; border-bottom: 1px solid #f0f0f0; background: #fafafa;
}
.co-card__head-icon {
    width: 36px; height: 36px; border-radius: 9px;
    background: linear-gradient(135deg,#3665f3,#6fa3ff);
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: 15px; flex-shrink: 0;
}
.co-card__head-title { font-size: 15px; font-weight: 700; color: #111; margin: 0; }
.co-card__head-sub   { font-size: 12px; color: #767676; margin: 0; }
.co-card__body { padding: 20px; }

/* ── form fields ── */
.co-label {
    font-size: 12px; font-weight: 700; color: #555;
    text-transform: uppercase; letter-spacing: .05em;
    margin-bottom: 5px; display: block;
}
.co-label .req { color: #e53238; }
.co-input, .co-select, .co-textarea {
    width: 100%; border: 1.5px solid #dde3ec; border-radius: 8px;
    padding: 10px 13px; font-size: 14px; color: #222;
    background: #fff; outline: none; transition: border-color .15s, box-shadow .15s;
}
.co-input:focus, .co-select:focus, .co-textarea:focus {
    border-color: #3665f3; box-shadow: 0 0 0 3px rgba(54,101,243,.12);
}
.co-input.is-invalid { border-color: #e53238; }
.co-row { display: flex; gap: 14px; flex-wrap: wrap; }
.co-col { flex: 1; min-width: 130px; }
.co-fgroup { margin-bottom: 16px; }
.co-ferror { font-size: 12px; color: #e53238; margin-top: 4px; }
.co-select {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8'%3E%3Cpath fill='%23555' d='M6 8L0 0h12z'/%3E%3C/svg%3E");
    background-repeat: no-repeat; background-position: right 12px center; padding-right: 32px; cursor: pointer;
}
.co-textarea { resize: vertical; min-height: 72px; line-height: 1.6; }

/* ── payment method cards ── */
.co-pay-opts { display: flex; flex-direction: column; gap: 10px; }
.co-pay-opt  { display: block; cursor: pointer; }
.co-pay-opt input[type="radio"] { display: none; }
.co-pay-opt__inner {
    display: flex; align-items: center; gap: 14px;
    border: 2px solid #e2e2e2; border-radius: 10px; padding: 14px 18px;
    transition: border-color .15s, background .15s;
}
.co-pay-opt input:checked + .co-pay-opt__inner {
    border-color: #3665f3; background: #f0f4ff;
}
.co-pay-opt__inner:hover { border-color: #adc5fb; }
.co-pay-opt__radio {
    width: 18px; height: 18px; border-radius: 50%; border: 2px solid #ccc;
    flex-shrink: 0; display: flex; align-items: center; justify-content: center;
    background: #fff; transition: border-color .15s;
}
.co-pay-opt input:checked + .co-pay-opt__inner .co-pay-opt__radio {
    border-color: #3665f3; background: #3665f3;
}
.co-pay-opt input:checked + .co-pay-opt__inner .co-pay-opt__radio::after {
    content: ''; width: 6px; height: 6px; border-radius: 50%; background: #fff;
}
.co-pay-opt__logo {
    width: 48px; height: 28px; object-fit: contain; flex-shrink: 0;
}
.co-pay-opt__icon {
    width: 48px; height: 28px; display: flex; align-items: center; justify-content: center;
    font-size: 22px; flex-shrink: 0;
}
.co-pay-opt__info { flex: 1; }
.co-pay-opt__name { font-size: 14px; font-weight: 700; color: #111; }
.co-pay-opt__desc { font-size: 12px; color: #767676; margin-top: 2px; }

/* ── order summary sidebar ── */
.co-sum-card {
    background: #fff; border: 1px solid #e2e2e2; border-radius: 12px; overflow: hidden;
    box-shadow: 0 1px 4px rgba(0,0,0,.05);
}
.co-sum-head {
    padding: 16px 20px; border-bottom: 1px solid #f0f0f0; background: #fafafa;
    font-size: 15px; font-weight: 700; color: #111;
    display: flex; align-items: center; gap: 8px;
}
.co-sum-head i { color: #3665f3; }
.co-sum-items { padding: 14px 20px; border-bottom: 1px solid #f0f0f0; max-height: 280px; overflow-y: auto; }
.co-sum-item {
    display: flex; gap: 12px; align-items: flex-start; padding: 8px 0;
    border-bottom: 1px solid #f7f7f7;
}
.co-sum-item:last-child { border-bottom: none; }
.co-sum-item__img {
    width: 52px; height: 52px; border-radius: 8px; object-fit: cover;
    border: 1px solid #e5e5e5; flex-shrink: 0; background: #f7f7f7;
}
.co-sum-item__img-ph {
    width: 52px; height: 52px; border-radius: 8px; flex-shrink: 0;
    background: #f0f0f0; display: flex; align-items: center; justify-content: center;
    color: #ddd; font-size: 20px;
}
.co-sum-item__name { font-size: 13px; font-weight: 600; color: #111; line-height: 1.35; flex: 1; }
.co-sum-item__qty  { font-size: 12px; color: #767676; margin-top: 2px; }
.co-sum-item__price{ font-size: 13px; font-weight: 700; color: #3665f3; white-space: nowrap; }
.co-sum-totals { padding: 14px 20px; }
.co-sum-row {
    display: flex; align-items: center; justify-content: space-between;
    font-size: 13.5px; padding: 5px 0; color: #444;
}
.co-sum-row.discount { color: #2e7d32; font-weight: 600; }
.co-sum-row.grand {
    font-size: 17px; font-weight: 800; color: #111;
    border-top: 2px solid #e5e5e5; margin-top: 8px; padding-top: 12px;
}
.co-sum-row.grand span:last-child { color: #3665f3; }

/* ── place order button ── */
.co-place-btn {
    width: 100%; background: linear-gradient(135deg, #3665f3, #1e4fc4);
    color: #fff; border: none; border-radius: 10px; padding: 16px;
    font-size: 16px; font-weight: 800; cursor: pointer; letter-spacing: .3px;
    transition: all .2s; box-shadow: 0 4px 16px rgba(54,101,243,.35);
    margin-top: 14px; display: flex; align-items: center; justify-content: center; gap: 10px;
}
.co-place-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(54,101,243,.45); }
.co-place-btn:disabled { opacity: .7; cursor: not-allowed; transform: none; }
.co-secure-note {
    text-align: center; font-size: 12px; color: #999; margin-top: 10px;
    display: flex; align-items: center; justify-content: center; gap: 5px;
}
.co-secure-note i { color: #2e7d32; }
</style>
@endpush

@section('content')
<div class="co-page">
<div class="container">

    {{-- Breadcrumb steps --}}
    <div class="co-steps">
        <div class="co-step done">
            <div class="co-step__num"><i class="fas fa-check" style="font-size:9px"></i></div>
            <span>Cart</span>
        </div>
        <span class="co-step__sep">›</span>
        <div class="co-step active">
            <div class="co-step__num">2</div>
            <span>Checkout</span>
        </div>
        <span class="co-step__sep">›</span>
        <div class="co-step">
            <div class="co-step__num">3</div>
            <span>Confirmation</span>
        </div>
    </div>

    @if (session('error'))
        <div style="background:#fff0f0;border:1px solid #fca5a5;border-radius:10px;padding:14px 18px;margin-bottom:18px;display:flex;align-items:center;gap:10px;font-size:13.5px;color:#b91c1c;">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif

    <form id="co-form" action="{{ route('checkout.store') }}" method="POST">
    @csrf

    <div class="co-layout">

        {{-- ═══ LEFT — Forms ═══ --}}
        <div class="co-main">

            {{-- ── Shipping Address ── --}}
            <div class="co-card">
                <div class="co-card__head">
                    <div class="co-card__head-icon"><i class="fas fa-map-marker-alt"></i></div>
                    <div>
                        <p class="co-card__head-title">Shipping Address</p>
                        <p class="co-card__head-sub">Where should we deliver your order?</p>
                    </div>
                </div>
                <div class="co-card__body">

                    <div class="co-row">
                        <div class="co-col co-fgroup">
                            <label class="co-label">Full Name <span class="req">*</span></label>
                            <input type="text" name="customer_name" class="co-input @error('customer_name') is-invalid @enderror"
                                   value="{{ old('customer_name', auth()->user()->name ?? '') }}"
                                   placeholder="e.g. Ahmed Ali" required>
                            @error('customer_name')<div class="co-ferror">{{ $message }}</div>@enderror
                        </div>
                        <div class="co-col co-fgroup">
                            <label class="co-label">Email <span class="req">*</span></label>
                            <input type="email" name="customer_email" class="co-input @error('customer_email') is-invalid @enderror"
                                   value="{{ old('customer_email', auth()->user()->email ?? '') }}"
                                   placeholder="you@example.com" required>
                            @error('customer_email')<div class="co-ferror">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="co-row">
                        <div class="co-col co-fgroup">
                            <label class="co-label">Phone</label>
                            <input type="text" name="customer_phone" class="co-input"
                                   value="{{ old('customer_phone') }}" placeholder="+92 300 1234567">
                        </div>
                    </div>

                    <div class="co-fgroup">
                        <label class="co-label">Street Address <span class="req">*</span></label>
                        <input type="text" name="address_line" class="co-input @error('address_line') is-invalid @enderror"
                               value="{{ old('address_line', auth()->user()->address_line) }}"
                               placeholder="House No., Street, Area" required>
                        @error('address_line')<div class="co-ferror">{{ $message }}</div>@enderror
                    </div>

                    <div class="co-row">
                        <div class="co-col co-fgroup">
                            <label class="co-label">City</label>
                            <input type="text" name="city" class="co-input" value="{{ old('city', auth()->user()->city) }}" placeholder="Lahore">
                        </div>
                        <div class="co-col co-fgroup">
                            <label class="co-label">State / Province</label>
                            <input type="text" name="state" class="co-input" value="{{ old('state', auth()->user()->state) }}" placeholder="Punjab">
                        </div>
                        <div class="co-col co-fgroup">
                            <label class="co-label">Postal Code</label>
                            <input type="text" name="postal_code" class="co-input" value="{{ old('postal_code', auth()->user()->postal_code) }}" placeholder="54000">
                        </div>
                    </div>

                    <div class="co-fgroup">
                        <label class="co-label">Country</label>
                        <select name="country" class="co-select">
                            @php $countries = ['Pakistan','United States','United Kingdom','United Arab Emirates','Canada','Australia','India','Saudi Arabia','Germany','France','Other']; @endphp
                            @foreach ($countries as $c)
                                <option value="{{ $c }}" {{ old('country', auth()->user()->country ?? 'Pakistan') === $c ? 'selected':'' }}>{{ $c }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="co-fgroup" style="margin-bottom:0">
                        <label class="co-label">Order Notes <span style="font-weight:400;text-transform:none;letter-spacing:0;color:#aaa">(optional)</span></label>
                        <textarea name="notes" class="co-textarea" placeholder="Any special instructions for delivery…">{{ old('notes') }}</textarea>
                    </div>

                </div>
            </div>

            {{-- ── Payment Method ── --}}
            <div class="co-card">
                <div class="co-card__head">
                    <div class="co-card__head-icon" style="background:linear-gradient(135deg,#2e7d32,#66bb6a)">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <div>
                        <p class="co-card__head-title">Payment Method</p>
                        <p class="co-card__head-sub">Choose how you'd like to pay</p>
                    </div>
                </div>
                <div class="co-card__body">

                    <div class="co-pay-opts">

                        {{-- Cash on Delivery (only if enabled in admin settings) --}}
                        @if ($methods['cod'])
                        <label class="co-pay-opt">
                            <input type="radio" name="payment_method" value="cod"
                                   {{ old('payment_method','cod') === 'cod' ? 'checked' : '' }} required>
                            <div class="co-pay-opt__inner">
                                <div class="co-pay-opt__radio"></div>
                                <div class="co-pay-opt__icon" style="color:#f59e0b;">
                                    <i class="fas fa-money-bill-wave"></i>
                                </div>
                                <div class="co-pay-opt__info">
                                    <div class="co-pay-opt__name">Cash on Delivery</div>
                                    <div class="co-pay-opt__desc">Pay in cash when your order arrives</div>
                                </div>
                            </div>
                        </label>
                        @endif

                        {{-- Stripe --}}
                        @if ($methods['stripe'])
                        <label class="co-pay-opt">
                            <input type="radio" name="payment_method" value="stripe"
                                   {{ old('payment_method') === 'stripe' ? 'checked' : '' }}>
                            <div class="co-pay-opt__inner">
                                <div class="co-pay-opt__radio"></div>
                                <div class="co-pay-opt__icon" style="color:#635bff;">
                                    <i class="fab fa-stripe" style="font-size:28px"></i>
                                </div>
                                <div class="co-pay-opt__info">
                                    <div class="co-pay-opt__name">Stripe — Credit / Debit Card</div>
                                    <div class="co-pay-opt__desc">Visa, Mastercard, Amex — secure hosted checkout</div>
                                </div>
                            </div>
                        </label>
                        @endif

                        {{-- PayPal --}}
                        @if ($methods['paypal'])
                        <label class="co-pay-opt">
                            <input type="radio" name="payment_method" value="paypal"
                                   {{ old('payment_method') === 'paypal' ? 'checked' : '' }}>
                            <div class="co-pay-opt__inner">
                                <div class="co-pay-opt__radio"></div>
                                <div class="co-pay-opt__icon" style="color:#003087;">
                                    <i class="fab fa-paypal" style="font-size:26px"></i>
                                </div>
                                <div class="co-pay-opt__info">
                                    <div class="co-pay-opt__name">PayPal</div>
                                    <div class="co-pay-opt__desc">Pay via your PayPal account or PayPal-supported cards</div>
                                </div>
                            </div>
                        </label>
                        @endif

                    </div>

                    @error('payment_method')
                        <div class="co-ferror" style="margin-top:10px">{{ $message }}</div>
                    @enderror

                </div>
            </div>

        </div>

        {{-- ═══ RIGHT — Order Summary ═══ --}}
        <div class="co-aside">
            <div class="co-sum-card">
                <div class="co-sum-head">
                    <i class="fas fa-shopping-bag"></i>
                    Order Summary
                    <span style="font-size:13px;font-weight:500;color:#767676;margin-left:4px;">({{ count($summary['items']) }} item{{ count($summary['items'])!==1?'s':'' }})</span>
                </div>

                {{-- Items --}}
                <div class="co-sum-items">
                    @foreach ($summary['items'] as $item)
                    <div class="co-sum-item">
                        @if ($item['image'])
                            <img src="{{ $item['image'] }}" alt="" class="co-sum-item__img">
                        @else
                            <div class="co-sum-item__img-ph"><i class="fas fa-image"></i></div>
                        @endif
                        <div style="flex:1">
                            <div class="co-sum-item__name">{{ $item['name'] }}</div>
                            <div class="co-sum-item__qty">Qty: {{ $item['quantity'] }}</div>
                        </div>
                        <div class="co-sum-item__price">${{ number_format($item['line_total'], 2) }}</div>
                    </div>
                    @endforeach
                </div>

                {{-- Totals --}}
                <div class="co-sum-totals">
                    <div class="co-sum-row">
                        <span>Subtotal</span>
                        <span>${{ number_format($summary['subtotal'], 2) }}</span>
                    </div>
                    @if ($summary['shipping_total'] > 0)
                    <div class="co-sum-row">
                        <span>Shipping</span>
                        <span>${{ number_format($summary['shipping_total'], 2) }}</span>
                    </div>
                    @else
                    <div class="co-sum-row" style="color:#2e7d32">
                        <span>Shipping</span>
                        <span><i class="fas fa-check-circle"></i> Free</span>
                    </div>
                    @endif
                    @if ($summary['tax_total'] > 0)
                    <div class="co-sum-row">
                        <span>Tax</span>
                        <span>${{ number_format($summary['tax_total'], 2) }}</span>
                    </div>
                    @endif
                    @if ($summary['discount'] > 0)
                    <div class="co-sum-row discount">
                        <span><i class="fas fa-tag"></i> Coupon ({{ $summary['coupon']['code'] ?? '' }})</span>
                        <span>−${{ number_format($summary['discount'], 2) }}</span>
                    </div>
                    @endif
                    <div class="co-sum-row grand">
                        <span>Total</span>
                        <span>${{ number_format($summary['grand_total'], 2) }}</span>
                    </div>
                </div>

                {{-- Place Order --}}
                <div style="padding: 0 20px 20px;">
                    <button type="submit" class="co-place-btn" id="co-place-btn">
                        <i class="fas fa-lock"></i> Place Order
                    </button>
                    <p class="co-secure-note">
                        <i class="fas fa-shield-alt"></i>
                        Secure &amp; encrypted checkout
                    </p>
                    <div style="text-align:center;margin-top:12px;">
                        <a href="{{ route('cart.index') }}"
                           style="font-size:12.5px;color:#767676;text-decoration:none;">
                            <i class="fas fa-arrow-left" style="margin-right:4px"></i> Return to Cart
                        </a>
                    </div>
                </div>

            </div>
        </div>

    </div>
    </form>

</div>
</div>
@endsection

@push('scripts')
{{-- Google Places address autocomplete (only when a Maps API key is configured) --}}
@if (setting('google_maps_api_key'))
    <script src="{{ asset('frontend-assets/js/checkout-autocomplete.js') }}"></script>
    <script async
            src="https://maps.googleapis.com/maps/api/js?key={{ setting('google_maps_api_key') }}&libraries=places&callback=initCheckoutAutocomplete&loading=async"></script>
@endif
<script>
document.getElementById('co-form').addEventListener('submit', function() {
    var btn = document.getElementById('co-place-btn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing…';
});
</script>
@endpush
