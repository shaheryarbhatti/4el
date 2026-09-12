@extends('frontend.layouts.app')

@section('title', 'Cart — ' . config('app.name'))

@push('styles')
<style>
/* ═══════════════════════════════════════════════════
   eBay-style Cart Page — Rich Design
═══════════════════════════════════════════════════ */
.eb-cart-page { padding: 28px 0 72px; min-height: 70vh; background: #f5f6f7; }

/* ── Page heading row ── */
.eb-cart-page__head {
    display: flex; align-items: baseline; gap: 12px;
    margin-bottom: 20px;
}
.eb-cart-page__title { font-size: 26px; font-weight: 700; color: #111; margin: 0; }
.eb-cart-page__count { font-size: 15px; color: #767676; }

/* ── Guest banner ── */
.eb-cart-banner {
    background: linear-gradient(90deg,#e8f0fe 0%,#eef3ff 100%);
    border: 1px solid #c5d5fb;
    border-radius: 10px;
    padding: 14px 20px;
    display: flex; align-items: center; gap: 12px;
    margin-bottom: 22px;
    font-size: 13.5px; color: #1a1a1a;
}
.eb-cart-banner i { color: #3665f3; font-size: 20px; flex-shrink: 0; }
.eb-cart-banner a { color: #3665f3; font-weight: 700; text-decoration: none; }
.eb-cart-banner a:hover { text-decoration: underline; }

/* ── Seller card ── */
.eb-seller-card {
    background: #fff;
    border: 1px solid #e2e2e2;
    border-radius: 12px;
    overflow: hidden;
    margin-bottom: 16px;
    box-shadow: 0 1px 4px rgba(0,0,0,.06);
}
.eb-seller-card__head {
    display: flex; align-items: center; gap: 14px;
    padding: 14px 20px;
    background: #fafafa;
    border-bottom: 1px solid #f0f0f0;
}
.eb-seller-avatar {
    width: 40px; height: 40px; border-radius: 50%;
    background: linear-gradient(135deg,#667eea,#764ba2);
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: 17px; font-weight: 700; flex-shrink: 0;
}
.eb-seller-card__name { font-weight: 700; font-size: 14px; color: #111; text-decoration: none; }
.eb-seller-card__name:hover { color: #3665f3; }
.eb-seller-card__fb { font-size: 12px; color: #767676; margin-top: 1px; }
.eb-seller-card__fb span { color: #007600; font-weight: 600; }

/* ── Line item ── */
.eb-cart-item {
    display: flex; gap: 18px; align-items: flex-start;
    padding: 20px;
    border-bottom: 1px solid #f2f2f2;
    transition: background .15s;
}
.eb-cart-item:last-child { border-bottom: none; }
.eb-cart-item:hover { background: #fefefe; }

.eb-cart-item__img-wrap {
    position: relative; flex-shrink: 0;
    width: 130px; height: 130px;
}
.eb-cart-item__img {
    width: 130px; height: 130px; object-fit: contain;
    border: 1px solid #e8e8e8; border-radius: 8px;
    background: #fafafa; padding: 6px;
    transition: box-shadow .2s;
}
.eb-cart-item:hover .eb-cart-item__img { box-shadow: 0 4px 16px rgba(0,0,0,.12); }

.eb-cart-item__body { flex: 1; min-width: 0; }

.eb-cart-item__title {
    font-size: 15px; font-weight: 600; color: #111; text-decoration: none;
    display: block; margin-bottom: 5px; line-height: 1.4;
}
.eb-cart-item__title:hover { color: #3665f3; }

.eb-cart-item__meta { display: flex; flex-wrap: wrap; gap: 8px 18px; margin-bottom: 10px; }
.eb-cart-item__cond {
    font-size: 12px; color: #555;
    background: #f3f4f6; border-radius: 20px;
    padding: 2px 10px; display: inline-block;
}

.eb-cart-item__price-row { display: flex; align-items: baseline; gap: 10px; margin-bottom: 6px; }
.eb-cart-item__price { font-size: 20px; font-weight: 700; color: #111; }
.eb-cart-item__orig { font-size: 13px; color: #9b9b9b; text-decoration: line-through; }
.eb-cart-item__disc { font-size: 12px; font-weight: 700; color: #e53238; background: #fff0f1; border-radius: 4px; padding: 1px 6px; }

.eb-cart-item__ship {
    font-size: 13px; margin-bottom: 4px;
    display: flex; align-items: center; gap: 6px;
}
.eb-cart-item__ship .free { color: #007600; font-weight: 700; }
.eb-cart-item__ship i { font-size: 12px; color: #888; }
.eb-cart-item__returns {
    font-size: 12px; color: #555; margin-bottom: 14px;
    display: flex; align-items: center; gap: 5px;
}
.eb-cart-item__returns i { color: #3665f3; font-size: 11px; }

/* ── Controls row ── */
.eb-cart-item__controls { display: flex; align-items: center; gap: 14px; flex-wrap: wrap; }

.eb-qty {
    display: inline-flex; align-items: center;
    border: 1.5px solid #d0d0d0; border-radius: 25px;
    overflow: hidden; height: 36px;
    background: #fff;
}
.eb-qty__btn {
    width: 36px; height: 36px;
    background: none; border: none;
    font-size: 18px; color: #444; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    transition: background .15s, color .15s;
}
.eb-qty__btn:hover { background: #f0f0f0; color: #111; }
.eb-qty__btn:first-child { border-right: 1.5px solid #d0d0d0; }
.eb-qty__btn:last-child  { border-left:  1.5px solid #d0d0d0; }
.eb-qty__val {
    width: 40px; text-align: center; font-size: 14px; font-weight: 700;
    border: none; outline: none; background: transparent;
    -moz-appearance: textfield; color: #111;
}
.eb-qty__val::-webkit-outer-spin-button,
.eb-qty__val::-webkit-inner-spin-button { -webkit-appearance: none; }

.eb-cart-item__trash {
    width: 36px; height: 36px; border-radius: 50%;
    border: 1.5px solid #e0e0e0; background: #fff;
    display: inline-flex; align-items: center; justify-content: center;
    cursor: pointer; transition: all .2s;
    color: #888; flex-shrink: 0; vertical-align: middle;
}
.eb-cart-item__trash:hover { border-color: #e53238; background: #fff0f1; color: #e53238; }

.eb-cart-divider { height: 1px; width: 1px; background: #d0d0d0; margin: 0 2px; }

.eb-cart-item__link-btn {
    background: none; border: none; padding: 0;
    font-size: 13px; cursor: pointer; text-decoration: none;
    transition: color .15s;
}
.eb-cart-item__save { color: #3665f3; }
.eb-cart-item__save:hover { color: #1a4fcf; text-decoration: underline; }
.eb-cart-item__remove { color: #767676; }
.eb-cart-item__remove:hover { color: #e53238; text-decoration: underline; }

/* ── Right: Order summary ── */
.eb-summary-card {
    background: #fff;
    border: 1px solid #e2e2e2;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,.07);
    position: sticky; top: 20px;
}
.eb-summary-card__head {
    padding: 18px 22px 0;
    font-size: 20px; font-weight: 700; color: #111;
}
.eb-summary-card__body { padding: 14px 22px 22px; }

.eb-summary-row {
    display: flex; justify-content: space-between; align-items: center;
    font-size: 14px; color: #444; padding: 7px 0;
}
.eb-summary-row .lbl { display: flex; align-items: center; gap: 5px; }
.eb-summary-row .info-ic { color: #aaa; font-size: 11px; cursor: help; }
.eb-summary-divider { border: none; border-top: 1px solid #eee; margin: 10px 0; }
.eb-summary-total {
    display: flex; justify-content: space-between; align-items: center;
    font-size: 17px; font-weight: 800; color: #111; padding: 8px 0 18px;
}

.eb-checkout-btn {
    display: block; width: 100%; padding: 15px 0;
    background: linear-gradient(135deg, #3665f3 0%, #254cc9 100%);
    color: #fff; font-size: 15px; font-weight: 700;
    border: none; border-radius: 50px; cursor: pointer;
    text-align: center; text-decoration: none;
    letter-spacing: .3px;
    box-shadow: 0 4px 14px rgba(54,101,243,.35);
    transition: box-shadow .2s, transform .15s;
    margin-bottom: 16px;
}
.eb-checkout-btn:hover {
    box-shadow: 0 6px 20px rgba(54,101,243,.45);
    transform: translateY(-1px);
    color: #fff; text-decoration: none;
}
.eb-checkout-btn:active { transform: translateY(0); }

.eb-summary-secure {
    display: flex; align-items: center; gap: 8px;
    font-size: 12px; color: #555; margin-bottom: 16px;
}
.eb-summary-secure i { color: #3665f3; font-size: 16px; }

/* Trust badges */
.eb-trust-badges {
    display: flex; gap: 6px; flex-wrap: wrap;
    padding-top: 14px; border-top: 1px solid #f0f0f0;
}
.eb-trust-badge {
    flex: 1; min-width: 60px;
    border: 1px solid #e8e8e8; border-radius: 8px;
    padding: 8px 6px; text-align: center;
    font-size: 10px; color: #555; line-height: 1.4;
}
.eb-trust-badge i { display: block; font-size: 16px; color: #3665f3; margin-bottom: 4px; }

/* Continue shopping link */
.eb-continue-link {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: 13px; color: #3665f3; text-decoration: none;
    margin-top: 14px;
}
.eb-continue-link:hover { text-decoration: underline; color: #1a4fcf; }

/* Clear all button */
.eb-clear-btn {
    background: none; border: none; font-size: 13px; cursor: pointer;
    color: #e53238; text-decoration: none; padding: 0;
    display: inline-flex; align-items: center; gap: 5px;
    transition: color .15s;
}
.eb-clear-btn:hover { color: #b91c1c; text-decoration: underline; }

/* Coupon box */
.eb-coupon-card {
    background: #fff; border: 1px solid #e2e2e2; border-radius: 12px;
    padding: 18px 22px; margin-top: 14px;
    box-shadow: 0 1px 4px rgba(0,0,0,.05);
}
.eb-coupon-title { font-size: 14px; font-weight: 700; color: #111; margin-bottom: 12px; display: flex; align-items: center; gap: 7px; }
.eb-coupon-title i { color: #3665f3; }
.eb-coupon-row { display: flex; gap: 8px; }
.eb-coupon-input {
    flex: 1; border: 1.5px solid #d0d0d0; border-radius: 8px;
    padding: 10px 14px; font-size: 13px; color: #111;
    text-transform: uppercase; outline: none; transition: border-color .15s;
}
.eb-coupon-input:focus { border-color: #3665f3; }
.eb-coupon-btn {
    padding: 10px 20px; background: #3665f3; color: #fff; border: none;
    border-radius: 8px; font-size: 13px; font-weight: 700; cursor: pointer;
    transition: background .15s;
}
.eb-coupon-btn:hover { background: #1e4fc4; }
.eb-coupon-msg { font-size: 12.5px; margin-top: 8px; }
.eb-coupon-msg.success { color: #007600; }
.eb-coupon-msg.error   { color: #e53238; }
.eb-coupon-applied {
    display: flex; align-items: center; justify-content: space-between;
    background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px;
    padding: 10px 14px; font-size: 13px; color: #166534;
}
.eb-coupon-applied strong { font-family: monospace; font-size: 14px; }
.eb-coupon-remove { background: none; border: none; color: #e53238; cursor: pointer; font-size: 18px; line-height: 1; padding: 0; }

/* Discount row in summary */
.eb-summary-discount { color: #007600; font-weight: 700; }

/* ── Empty state ── */
.eb-cart-empty {
    background: #fff; border: 1px solid #e2e2e2;
    border-radius: 12px; text-align: center;
    padding: 70px 30px;
    box-shadow: 0 2px 8px rgba(0,0,0,.06);
}
.eb-cart-empty__icon {
    width: 90px; height: 90px; border-radius: 50%;
    background: linear-gradient(135deg,#e8f0fe,#dbeafe);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 20px; font-size: 36px; color: #3665f3;
}
.eb-cart-empty h3 { font-size: 22px; font-weight: 700; color: #111; margin-bottom: 8px; }
.eb-cart-empty p  { font-size: 14px; color: #767676; margin-bottom: 26px; }
.eb-cart-empty__btn {
    display: inline-flex; align-items: center; gap: 8px;
    background: linear-gradient(135deg,#3665f3,#254cc9);
    color: #fff; font-size: 14px; font-weight: 700;
    padding: 13px 32px; border-radius: 50px; text-decoration: none;
    box-shadow: 0 4px 14px rgba(54,101,243,.35);
    transition: box-shadow .2s, transform .15s;
}
.eb-cart-empty__btn:hover { color:#fff; text-decoration:none; box-shadow:0 6px 20px rgba(54,101,243,.45); transform:translateY(-1px); }

/* Flash alert */
.eb-cart-flash {
    display: flex; align-items: center; gap: 10px;
    background: #f0fdf4; border: 1px solid #bbf7d0;
    border-radius: 10px; padding: 12px 18px;
    font-size: 13.5px; color: #166534; margin-bottom: 20px;
}
.eb-cart-flash i { font-size: 16px; flex-shrink: 0; }
</style>
@endpush

@section('content')
<div class="eb-cart-page">
<div class="container">

    {{-- Page heading --}}
    @php
        $cartItems   = $cart;
        $totalQty    = array_sum(array_column($cartItems, 'quantity'));
        $subtotal    = array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $cartItems));
        $shippingSum = array_sum(array_map(fn($i) => $i['free_shipping'] ? 0 : ($i['shipping_cost'] ?? 0) * $i['quantity'], $cartItems));
        $discount    = $coupon ? min((float)$coupon['discount'], $subtotal) : 0;
        $total       = max(0, $subtotal + $shippingSum - $discount);
    @endphp

    <div class="eb-cart-page__head">
        <h1 class="eb-cart-page__title">Cart</h1>
        @if($totalQty > 0)
            <span class="eb-cart-page__count">{{ $totalQty }} {{ Str::plural('item', $totalQty) }}</span>
            <form method="POST" action="{{ route('cart.clear') }}" style="margin-left:auto;"
                  class="js-confirm"
                  data-confirm-title="Clear your cart?"
                  data-confirm-message="This will remove all items from your cart. This action cannot be undone."
                  data-confirm-text="Yes, clear cart"
                  data-confirm-icon="fa-trash-alt"
                  data-confirm-variant="danger">
                @csrf
                <button type="submit" class="eb-clear-btn">
                    <i class="fas fa-trash-alt" style="font-size:11px;"></i> Clear all
                </button>
            </form>
        @endif
    </div>

    {{-- Guest banner --}}
    @guest
    <div class="eb-cart-banner">
        <i class="fas fa-info-circle"></i>
        <span>You're signed out. To save these items or see your previously saved items,
            <a href="#loginModal" data-toggle="modal">sign in</a>.
        </span>
    </div>
    @endguest

    {{-- Flash success --}}
    @if(session('cart_added'))
    <div class="eb-cart-flash">
        <i class="fas fa-check-circle"></i>
        {{ session('cart_added') }} — <a href="{{ route('cart.index') }}" style="color:#166534;font-weight:700;">View Cart</a>
    </div>
    @endif

    @if(count($cartItems) === 0)

    {{-- ── Empty state ── --}}
    <div class="eb-cart-empty">
        <div class="eb-cart-empty__icon">
            <i class="fas fa-shopping-cart"></i>
        </div>
        <h3>Your cart is empty</h3>
        <p>Looks like you haven't added anything yet.<br>Explore thousands of great deals waiting for you.</p>
        <a href="{{ route('home') }}" class="eb-cart-empty__btn">
            <i class="fas fa-store"></i> Start Shopping
        </a>
    </div>

    @else

    <div class="row" style="align-items:flex-start;">

        {{-- ══ LEFT: Item cards ══ --}}
        <div class="col-lg-8 col-md-7 mb-4">

            @foreach(collect($cartItems)->groupBy('vendor_name') as $vendorName => $items)
            <div class="eb-seller-card">

                {{-- Seller header --}}
                <div class="eb-seller-card__head">
                    <div class="eb-seller-avatar">{{ strtoupper(substr($vendorName,0,1)) }}</div>
                    <div>
                        <a href="#" class="eb-seller-card__name">{{ $vendorName }}</a>
                        <div class="eb-seller-card__fb"><span>99.2%</span> positive feedback</div>
                    </div>
                    <div class="ml-auto" style="font-size:12px;color:#888;">
                        <i class="fas fa-shield-alt" style="color:#3665f3;"></i> Top Rated Seller
                    </div>
                </div>

                {{-- Line items --}}
                @foreach($items as $item)
                @php
                    $hasDisc = $item['is_on_sale'] && $item['original_price'] > $item['price'];
                    $discPct = $hasDisc ? round((1 - $item['price'] / $item['original_price']) * 100) : 0;
                @endphp
                <div class="eb-cart-item" id="cart-item-{{ $item['product_id'] }}">

                    {{-- Image --}}
                    <div class="eb-cart-item__img-wrap">
                        <a href="{{ route('product.show', $item['slug']) }}">
                            <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="eb-cart-item__img">
                        </a>
                    </div>

                    {{-- Body --}}
                    <div class="eb-cart-item__body">

                        <a href="{{ route('product.show', $item['slug']) }}" class="eb-cart-item__title">
                            {{ $item['name'] }}
                        </a>

                        <div class="eb-cart-item__meta">
                            <span class="eb-cart-item__cond">{{ ucfirst($item['condition'] ?? 'new') }} with box</span>
                        </div>

                        <div class="eb-cart-item__price-row">
                            <span class="eb-cart-item__price">${{ number_format($item['price'], 2) }}</span>
                            @if($hasDisc)
                                <span class="eb-cart-item__orig">${{ number_format($item['original_price'], 2) }}</span>
                                <span class="eb-cart-item__disc">-{{ $discPct }}%</span>
                            @endif
                        </div>

                        <div class="eb-cart-item__ship">
                            <i class="fas fa-truck"></i>
                            @if($item['free_shipping'])
                                + <span class="free">Free shipping</span>
                            @elseif(($item['shipping_cost'] ?? 0) == 0)
                                + <span class="free">Free shipping</span>
                            @else
                                + ${{ number_format($item['shipping_cost'] ?? 0, 2) }} shipping
                            @endif
                        </div>

                        <div class="eb-cart-item__returns">
                            <i class="fas fa-redo-alt"></i> Returns accepted
                        </div>

                        {{-- Controls --}}
                        <div style="display:flex;flex-direction:row;align-items:center;gap:10px;flex-wrap:nowrap;">

                            {{-- Trash button --}}
                            <button type="button" class="eb-cart-item__trash" title="Remove item"
                                    data-remove-url="{{ route('cart.remove', $item['product_id']) }}"
                                    onclick="ebRemoveItem(this)">
                                <i class="fas fa-trash-alt" style="font-size:13px;"></i>
                            </button>

                            {{-- Qty stepper --}}
                            <div class="eb-qty">
                                <button type="button" class="eb-qty__btn" onclick="ebQty(this,-1)">&#8722;</button>
                                <input type="number" class="eb-qty__val"
                                       value="{{ $item['quantity'] }}"
                                       min="1" max="{{ $item['stock'] }}"
                                       data-pid="{{ $item['product_id'] }}"
                                       onchange="ebQtyChanged(this)">
                                <button type="button" class="eb-qty__btn" onclick="ebQty(this,1)">&#43;</button>
                            </div>

                        </div>

                    </div>{{-- /body --}}
                </div>{{-- /item --}}
                @endforeach

            </div>{{-- /seller-card --}}
            @endforeach

            {{-- Coupon Code --}}
            <div class="eb-coupon-card">
                <div class="eb-coupon-title"><i class="fas fa-tag"></i> Coupon Code</div>
                @if($coupon)
                    <div class="eb-coupon-applied" id="coupon-applied">
                        <span>Coupon <strong>{{ $coupon['code'] }}</strong> applied — you save ${{ number_format($discount, 2) }}</span>
                        <button class="eb-coupon-remove" onclick="ebRemoveCoupon()" title="Remove coupon">&times;</button>
                    </div>
                @else
                    <div id="coupon-form-wrap">
                        <div class="eb-coupon-row">
                            <input type="text" class="eb-coupon-input" id="coupon-input"
                                   placeholder="Enter coupon code" maxlength="50">
                            <button class="eb-coupon-btn" onclick="ebApplyCoupon()">Apply</button>
                        </div>
                        <div class="eb-coupon-msg" id="coupon-msg"></div>
                    </div>
                @endif
            </div>

            <a href="{{ route('home') }}" class="eb-continue-link">
                <i class="fas fa-arrow-left" style="font-size:11px;"></i> Continue shopping
            </a>

        </div>

        {{-- ══ RIGHT: Order Summary ══ --}}
        <div class="col-lg-4 col-md-5">
            <div class="eb-summary-card">

                <div class="eb-summary-card__head">Order summary</div>

                <div class="eb-summary-card__body">

                    <div class="eb-summary-row">
                        <span class="lbl">
                            {{ Str::ucfirst(Str::plural('Item', $totalQty)) }} ({{ $totalQty }})
                        </span>
                        <span id="summary-items">${{ number_format($subtotal, 2) }}</span>
                    </div>

                    <div class="eb-summary-row">
                        <span class="lbl">
                            Shipping
                            <i class="fas fa-info-circle info-ic" title="Calculated at checkout"></i>
                        </span>
                        <span id="summary-ship">
                            @if($shippingSum == 0)
                                <span style="color:#007600;font-weight:700;">Free</span>
                            @else
                                ${{ number_format($shippingSum, 2) }}
                            @endif
                        </span>
                    </div>

                    <div class="eb-summary-row" id="discount-row" style="{{ $discount > 0 ? '' : 'display:none;' }}">
                        <span class="lbl">
                            <i class="fas fa-tag" style="color:#007600;font-size:11px;"></i>
                            Coupon (<span id="discount-code">{{ $coupon['code'] ?? '' }}</span>)
                        </span>
                        <span id="summary-discount" class="eb-summary-discount">
                            −${{ number_format($discount, 2) }}
                        </span>
                    </div>

                    <hr class="eb-summary-divider">

                    <div class="eb-summary-total">
                        <span>Subtotal</span>
                        <span id="summary-subtotal">${{ number_format($total, 2) }}</span>
                    </div>

                    @guest
                        <a href="#loginModal" data-toggle="modal" class="eb-checkout-btn"
                           title="Sign in to checkout">
                            <i class="fas fa-lock mr-1" style="font-size:13px;"></i> Go to checkout
                        </a>
                    @else
                        <a href="{{ route('checkout.index') }}" class="eb-checkout-btn">
                            <i class="fas fa-lock mr-1" style="font-size:13px;"></i> Go to checkout
                        </a>
                    @endguest

                    <div class="eb-summary-secure">
                        <i class="fas fa-shield-alt"></i>
                        <span>Purchase protected by <a href="#" style="color:#3665f3;font-weight:600;">{{ config('app.name') }} Money Back Guarantee</a></span>
                    </div>

                    <div class="eb-trust-badges">
                        <div class="eb-trust-badge">
                            <i class="fas fa-lock"></i>
                            Secure Payment
                        </div>
                        <div class="eb-trust-badge">
                            <i class="fas fa-undo"></i>
                            Easy Returns
                        </div>
                        <div class="eb-trust-badge">
                            <i class="fas fa-headset"></i>
                            24/7 Support
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>{{-- /row --}}
    @endif

</div>
</div>
@endsection

@push('scripts')
<script>
var EB_CSRF = '{{ csrf_token() }}';

/* ── Qty stepper ── */
function ebQty(btn, delta) {
    var input = btn.parentElement.querySelector('.eb-qty__val');
    var val   = parseInt(input.value) || 1;
    var max   = parseInt(input.max)   || 9999;
    val = Math.max(1, Math.min(max, val + delta));
    input.value = val;
    ebQtyChanged(input);
}

function ebQtyChanged(input) {
    var pid = input.dataset.pid;
    var qty = parseInt(input.value) || 1;
    fetch('{{ route('cart.update') }}', {
        method : 'POST',
        headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN':EB_CSRF, 'Accept':'application/json' },
        body   : JSON.stringify({ product_id: pid, quantity: qty })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            document.querySelectorAll('.eb-cart-badge,.eb-top__badge').forEach(el => el.textContent = data.count);
            location.reload();
        }
    });
}

/* ── Remove single item ── */
function ebRemoveItem(btn) {
    var row = btn.closest('.eb-cart-item');
    if (row) { row.style.opacity = '.4'; row.style.pointerEvents = 'none'; }
    fetch(btn.dataset.removeUrl, {
        method : 'POST',
        headers: { 'X-CSRF-TOKEN': EB_CSRF, 'Accept': 'application/json', 'Content-Type': 'application/x-www-form-urlencoded' },
        body   : '_method=DELETE'
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            document.querySelectorAll('.eb-cart-badge,.eb-top__badge').forEach(el => el.textContent = data.count);
            location.reload();
        } else if (row) {
            row.style.opacity = '1'; row.style.pointerEvents = '';
        }
    })
    .catch(() => { if (row) { row.style.opacity='1'; row.style.pointerEvents=''; } });
}

/* ── Apply coupon ── */
function ebApplyCoupon() {
    var code = (document.getElementById('coupon-input')?.value || '').trim();
    var msgEl = document.getElementById('coupon-msg');
    if (!code) { msgEl.textContent = 'Please enter a coupon code.'; msgEl.className = 'eb-coupon-msg error'; return; }

    fetch('{{ route('cart.coupon.apply') }}', {
        method : 'POST',
        headers: { 'X-CSRF-TOKEN': EB_CSRF, 'Accept': 'application/json', 'Content-Type': 'application/json' },
        body   : JSON.stringify({ coupon_code: code })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            // Update summary
            document.getElementById('discount-row').style.display = '';
            document.getElementById('discount-code').textContent = data.code;
            document.getElementById('summary-discount').textContent = '−$' + data.discount;
            document.getElementById('summary-subtotal').textContent = '$' + data.total;
            // Replace coupon form with applied badge
            document.getElementById('coupon-form-wrap').outerHTML =
                '<div class="eb-coupon-applied" id="coupon-applied">' +
                '<span>Coupon <strong>' + data.code + '</strong> applied — you save $' + data.discount + '</span>' +
                '<button class="eb-coupon-remove" onclick="ebRemoveCoupon()" title="Remove coupon">&times;</button>' +
                '</div>';
        } else {
            msgEl.textContent = data.message;
            msgEl.className = 'eb-coupon-msg error';
        }
    });
}

/* ── Remove coupon ── */
function ebRemoveCoupon() {
    fetch('{{ route('cart.coupon.remove') }}', {
        method : 'POST',
        headers: { 'X-CSRF-TOKEN': EB_CSRF, 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            document.getElementById('discount-row').style.display = 'none';
            document.getElementById('summary-subtotal').textContent = '$' + data.total;
            // Replace applied badge with coupon form
            document.getElementById('coupon-applied').outerHTML =
                '<div id="coupon-form-wrap">' +
                '<div class="eb-coupon-row">' +
                '<input type="text" class="eb-coupon-input" id="coupon-input" placeholder="Enter coupon code" maxlength="50">' +
                '<button class="eb-coupon-btn" onclick="ebApplyCoupon()">Apply</button>' +
                '</div><div class="eb-coupon-msg" id="coupon-msg"></div></div>';
        }
    });
}

/* ── Allow Enter key on coupon input ── */
document.addEventListener('keydown', function(e) {
    if (e.key === 'Enter' && e.target && e.target.id === 'coupon-input') {
        ebApplyCoupon();
    }
});
</script>
@endpush
