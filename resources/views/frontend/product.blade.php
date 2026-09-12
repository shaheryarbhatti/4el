@extends('frontend.layouts.app')

@php
    use Illuminate\Support\Str;

    $discountPct = ($product->is_on_sale && $product->price > 0)
        ? round((1 - $product->sale_price / $product->price) * 100)
        : 0;

    // Build ordered image list
    $allImages = $product->images->count()
        ? $product->images
        : collect([$product->primaryImage])->filter();

    $imgSrcs = $allImages->map(function($img) {
        return $img
            ? (str_starts_with($img->path, 'frontend-assets/')
                ? asset($img->path)
                : asset('storage/' . $img->path))
            : asset('frontend-assets/images/demoes/demo36/products/product-1.jpg');
    })->values();

    if ($imgSrcs->isEmpty()) {
        $imgSrcs = collect([asset('frontend-assets/images/demoes/demo36/products/product-1.jpg')]);
    }

    $reviewCount = $reviews->count();
    $ratingWidth = $avgRating > 0 ? round(($avgRating / 5) * 100) . '%' : '0%';
@endphp

@section('title', $product->name . ' — ' . config('app.name'))

@push('styles')
<style>
/* ── Bottom widget mini-cards ── */
.ps-widget-card {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 14px;
    text-decoration: none;
    color: inherit;
}
.ps-widget-card:hover { color: inherit; text-decoration: none; }
.ps-widget-card img {
    width: 72px; height: 72px;
    object-fit: cover;
    border-radius: 6px;
    border: 1px solid #f0f0f0;
    flex-shrink: 0;
}
.ps-widget-card__name {
    font-size: 13px;
    font-weight: 600;
    color: #333;
    line-height: 1.35;
    margin-bottom: 4px;
    transition: color .15s;
}
.ps-widget-card:hover .ps-widget-card__name { color: #336699; }
.ps-widget-card__price {
    font-size: 13px;
    font-weight: 700;
    color: #c00;
}
.ps-widgets-section {
    padding-bottom: 48px;
}
.ps-widget-col-title {
    font-size: 13px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .06em;
    color: #333;
    border-bottom: 2px solid #e8e8e8;
    padding-bottom: 10px;
    margin-bottom: 16px;
}

/* ── Product gallery ── */
.ps-gallery { position: relative; }
.ps-main-img-wrap {
    position: relative;
    border: 1px solid #e9e9e9;
    border-radius: 4px;
    background: #fff;
    overflow: hidden; /* elevateZoom inner renders inside — safe to clip */
}
.ps-main-img-wrap img {
    width: 100%;
    height: 420px;
    object-fit: contain;
    display: block;
    transition: opacity .2s;
}
/* prod-full-screen (+) button — matches Porto style */
.prod-full-screen {
    position: absolute;
    right: 1.4rem;
    bottom: 1.4rem;
    z-index: 10;
    opacity: 0;
    transition: opacity .3s;
    cursor: pointer;
    outline: none;
}
.ps-main-img-wrap:hover .prod-full-screen { opacity: 1; }
.prod-full-screen i {
    color: #000;
    font-size: 1.4rem;
}
.ps-thumbs {
    display: flex;
    gap: 8px;
    margin-top: 12px;
    flex-wrap: wrap;
}
.ps-thumb {
    width: 82px;
    height: 82px;
    border: 2px solid #e9e9e9;
    border-radius: 4px;
    overflow: hidden;
    cursor: pointer;
    transition: border-color .15s;
    flex-shrink: 0;
}
.ps-thumb img { width: 100%; height: 100%; object-fit: cover; }
.ps-thumb.active, .ps-thumb:hover { border-color: #336699; }

/* ── label badges ── */
.ps-labels {
    position: absolute;
    top: 12px; left: 12px;
    z-index: 2;
    display: flex;
    flex-direction: column;
    gap: 4px;
}

/* ── Review stars (Porto re-uses .product-ratings) ── */
.rv-stars-display { color: #fbbf24; font-size: 14px; }
.rv-stars-display .empty { color: #d1d5db; }

/* ── Review avatar ── */
.rv-avatar {
    width: 72px; height: 72px; border-radius: 6px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    font-size: 26px; font-weight: 800; color: #fff;
    background: linear-gradient(135deg,#667eea,#764ba2);
}

/* ── Qty stepper ── */
.ps-qty-wrap {
    display: flex;
    align-items: center;
    border: 1px solid #d1d5db;
    border-radius: 4px;
    overflow: hidden;
    width: 110px;
}
.ps-qty-btn {
    width: 34px; height: 38px;
    background: #f3f4f6; border: none;
    font-size: 18px; font-weight: 700;
    cursor: pointer; color: #374151;
    transition: background .15s;
    line-height: 1;
    display: flex; align-items: center; justify-content: center;
}
.ps-qty-btn:hover { background: #e5e7eb; }
.ps-qty-input {
    width: 42px; text-align: center;
    border: none; border-left: 1px solid #d1d5db; border-right: 1px solid #d1d5db;
    height: 38px; font-size: 14px; font-weight: 600;
    -moz-appearance: textfield;
}
.ps-qty-input::-webkit-outer-spin-button,
.ps-qty-input::-webkit-inner-spin-button { -webkit-appearance: none; }

/* ── Shipping badge ── */
.ps-ship-badge {
    display: inline-flex; align-items: center; gap: 8px;
    background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px;
    padding: 8px 14px; font-size: 13px; color: #166534; margin-top: 6px;
}
.ps-ship-badge--paid {
    background: #f8fafc; border-color: #e2e8f0; color: #475569;
}

/* ── Tab pane spacing ── */
.product-single-tabs .tab-pane { padding: 20px 0; }
</style>
@endpush

@section('content')

<div class="container">

    {{-- ── Breadcrumb ── --}}
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="icon-home"></i></a></li>
            @if($product->category)
                @if($product->category->parent)
                    <li class="breadcrumb-item">
                        <a href="{{ route('category.show', $product->category->parent->slug) }}">{{ $product->category->parent->name }}</a>
                    </li>
                @endif
                <li class="breadcrumb-item">
                    <a href="{{ route('category.show', $product->category->slug) }}">{{ $product->category->name }}</a>
                </li>
            @endif
            <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($product->name, 50) }}</li>
        </ol>
    </nav>

    {{-- ── Flash message ── --}}
    @if(session('review_success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle mr-2"></i>{{ session('review_success') }}
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    @endif

    {{-- ════════════════════════════════ PRODUCT MAIN ════════════════════════════════ --}}
    <div class="product-single-container product-single-default">
        <div class="row">

            {{-- ── Left: Gallery ── --}}
            <div class="col-lg-5 col-md-6 product-single-gallery">
                <div class="ps-gallery">
                    {{-- Badges --}}
                    <div class="ps-labels label-group">
                        @if($product->is_featured)
                            <div class="product-label label-hot">HOT</div>
                        @endif
                        @if($product->is_on_sale)
                            <div class="product-label label-sale">-{{ $discountPct }}%</div>
                        @endif
                    </div>

                    {{-- Main image --}}
                    <div class="ps-main-img-wrap">
                        <img id="ps-active-img"
                             src="{{ $imgSrcs->first() }}"
                             data-zoom-image="{{ $imgSrcs->first() }}"
                             alt="{{ $product->name }}">
                        <span class="prod-full-screen" title="View full size">
                            <i class="icon-plus"></i>
                        </span>
                    </div>

                    {{-- Thumbnails (only if >1 image) --}}
                    @if($imgSrcs->count() > 1)
                    <div class="ps-thumbs">
                        @foreach($imgSrcs as $i => $src)
                        <div class="ps-thumb {{ $i === 0 ? 'active' : '' }}"
                             onclick="psSwitchImg(this, '{{ $src }}', {{ $i }})">
                            <img src="{{ $src }}" alt="thumb {{ $i+1 }}">
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
            {{-- End gallery --}}

            {{-- ── Right: Details ── --}}
            <div class="col-lg-7 col-md-6 product-single-details">
                <h1 class="product-title">{{ $product->name }}</h1>

                {{-- Star rating --}}
                <div class="ratings-container">
                    <div class="product-ratings">
                        <span class="ratings" style="width:{{ $ratingWidth }}"></span>
                        <span class="tooltiptext tooltip-top">{{ number_format($avgRating,1) }}</span>
                    </div>
                    <a href="#product-reviews-content" class="rating-link">
                        ({{ $reviewCount }} {{ Str::plural('Review', $reviewCount) }})
                    </a>
                </div>

                <hr class="short-divider">

                @if($product->is_auction)
                {{-- ══════════ AUCTION PRICE & BID UI ══════════ --}}

                {{-- Flash messages --}}
                @if(session('bid_success'))
                <div style="background:#d1fae5;border:1px solid #a7f3d0;border-radius:10px;padding:12px 16px;margin-bottom:14px;display:flex;align-items:center;gap:8px;font-size:13.5px;font-weight:600;color:#065f46;">
                    <i class="fas fa-check-circle"></i> {{ session('bid_success') }}
                </div>
                @endif
                @if(session('bid_error'))
                <div style="background:#fee2e2;border:1px solid #fecaca;border-radius:10px;padding:12px 16px;margin-bottom:14px;display:flex;align-items:center;gap:8px;font-size:13.5px;font-weight:600;color:#991b1b;">
                    <i class="fas fa-exclamation-circle"></i> {{ session('bid_error') }}
                </div>
                @endif

                <div style="background:linear-gradient(135deg,#1a1a2e 0%,#0f3460 100%);border-radius:14px;padding:20px 22px;margin-bottom:16px;">
                    <div style="display:flex;align-items:center;justify-content:space-between;gap:10px;flex-wrap:wrap;">
                        <div>
                            <div style="font-size:11px;color:rgba(255,255,255,.65);text-transform:uppercase;letter-spacing:.06em;margin-bottom:4px;">
                                {{ $product->bid_count > 0 ? 'Current Bid' : 'Starting Bid' }}
                            </div>
                            <div style="font-size:32px;font-weight:900;color:#fff;line-height:1;">
                                ${{ number_format($product->current_bid ?? $product->starting_bid, 2) }}
                            </div>
                            @if($product->bid_count > 0)
                            <div style="font-size:12px;color:rgba(255,255,255,.6);margin-top:4px;">
                                {{ $product->bid_count }} bid{{ $product->bid_count !== 1 ? 's' : '' }} placed
                            </div>
                            @endif
                        </div>
                        @if($product->auction_active)
                        <div style="text-align:center;">
                            <div style="font-size:11px;color:rgba(255,255,255,.6);text-transform:uppercase;letter-spacing:.06em;margin-bottom:6px;">Time Remaining</div>
                            <div id="auction-countdown" data-ends="{{ $product->auction_ends_at->toIso8601String() }}"
                                 style="display:flex;gap:8px;align-items:flex-end;">
                                @foreach(['days'=>'Days','hours'=>'Hrs','minutes'=>'Min','seconds'=>'Sec'] as $unit => $label)
                                <div style="background:rgba(255,255,255,.12);border-radius:8px;padding:8px 10px;min-width:48px;text-align:center;">
                                    <div id="cd-{{ $unit }}" style="font-size:20px;font-weight:800;color:#fff;line-height:1;">00</div>
                                    <div style="font-size:9px;color:rgba(255,255,255,.55);margin-top:2px;">{{ $label }}</div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @else
                        <div style="background:#ef4444;color:#fff;border-radius:8px;padding:10px 18px;font-size:13px;font-weight:700;">
                            <i class="fas fa-gavel"></i> Auction Ended
                        </div>
                        @endif
                    </div>
                </div>

                @if($product->auction_active)
                    @auth
                    @php $minBid = \App\Http\Controllers\Frontend\BidController::minimumBid($product); @endphp
                    <form method="POST" action="{{ route('product.bid', $product) }}" style="margin-bottom:14px;">
                        @csrf
                        <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                            <div style="position:relative;flex:1;min-width:180px;">
                                <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#888;font-weight:700;">$</span>
                                <input type="number" name="amount" step="0.01"
                                       min="{{ $minBid }}"
                                       placeholder="{{ number_format($minBid, 2) }}"
                                       style="width:100%;padding:11px 14px 11px 26px;border:2px solid #e0e0e0;border-radius:10px;font-size:15px;font-weight:600;outline:none;"
                                       onfocus="this.style.borderColor='#1a1a2e';"
                                       onblur="this.style.borderColor='#e0e0e0';">
                            </div>
                            <button type="submit"
                                    style="background:linear-gradient(135deg,#1a1a2e,#0f3460);color:#fff;border:none;border-radius:10px;padding:12px 26px;font-size:14px;font-weight:700;cursor:pointer;white-space:nowrap;box-shadow:0 4px 14px rgba(15,52,96,.4);transition:all .2s;"
                                    onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 24px rgba(15,52,96,.5)';"
                                    onmouseout="this.style.transform='';this.style.boxShadow='0 4px 14px rgba(15,52,96,.4)';">
                                <i class="fas fa-gavel"></i> Place Bid
                            </button>
                        </div>
                        <div style="font-size:12px;color:#888;margin-top:6px;">
                            <i class="fas fa-info-circle"></i> Minimum bid: <strong>${{ number_format($minBid, 2) }}</strong>
                        </div>
                    </form>
                    @else
                    <a href="{{ route('login') }}" class="btn btn-dark" style="width:100%;text-align:center;padding:13px;font-weight:700;border-radius:10px;">
                        <i class="fas fa-sign-in-alt mr-1"></i> Login to Bid
                    </a>
                    @endauth

                    {{-- Recent bids --}}
                    @php $recentBids = $product->bids()->with('user')->latest()->take(5)->get(); @endphp
                    @if($recentBids->count())
                    <div style="margin-top:14px;border:1px solid #f0f0f0;border-radius:10px;overflow:hidden;">
                        <div style="background:#f8f8f8;padding:8px 14px;font-size:11.5px;font-weight:700;color:#555;text-transform:uppercase;letter-spacing:.06em;">
                            <i class="fas fa-history" style="color:#1a1a2e;"></i> Bid History
                        </div>
                        @foreach($recentBids as $bid)
                        <div style="display:flex;align-items:center;justify-content:space-between;padding:9px 14px;border-top:1px solid #f0f0f0;font-size:13px;">
                            <span style="color:#555;">
                                {{ Str::mask($bid->user->name ?? 'Bidder', '*', 2) }}
                            </span>
                            <div style="display:flex;align-items:center;gap:10px;">
                                <strong style="color:#1a1a2e;">${{ number_format($bid->amount, 2) }}</strong>
                                @if($bid->is_winning)
                                    <span style="background:#d1fae5;color:#065f46;padding:2px 8px;border-radius:10px;font-size:10px;font-weight:700;">Winning</span>
                                @endif
                                <span style="color:#bbb;font-size:11px;">{{ $bid->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                @else
                <div style="background:#fef3c7;border:1px solid #fde68a;border-radius:10px;padding:14px 16px;font-size:13.5px;color:#92400e;font-weight:600;">
                    <i class="fas fa-gavel"></i> This auction has ended.
                    @if($product->winningBid)
                        The winning bid was <strong>${{ number_format($product->winningBid->amount, 2) }}</strong>.
                    @else
                        No bids were placed.
                    @endif
                </div>
                @endif

                @else
                {{-- ══════════ FIXED PRICE UI ══════════ --}}

                {{-- Price --}}
                <div class="price-box">
                    @if($product->is_on_sale)
                        <span class="old-price">${{ number_format($product->price, 2) }}</span>
                        <span class="new-price">${{ number_format($product->sale_price, 2) }}</span>
                    @else
                        <span class="new-price">${{ number_format($product->price, 2) }}</span>
                    @endif
                </div>

                {{-- Add to Cart --}}
                @if($product->stock > 0)
                <form method="POST" action="{{ route('cart.add', $product) }}" id="ps-atc-form"
                      style="display:flex; align-items:center; gap:12px; flex-wrap:wrap; margin-top:16px;">
                    @csrf
                    <div class="ps-qty-wrap">
                        <button type="button" class="ps-qty-btn" onclick="psQty(-1)">−</button>
                        <input id="ps-qty" name="quantity" class="ps-qty-input" type="number" value="1" min="1" max="{{ $product->stock }}">
                        <button type="button" class="ps-qty-btn" onclick="psQty(1)">+</button>
                    </div>
                    <button type="submit" class="btn btn-dark" style="padding:9px 28px; font-weight:700;">
                        <i class="fas fa-shopping-cart mr-1"></i> ADD TO CART
                    </button>
                    <a href="#" class="btn-icon-wish add-wishlist js-wish-toggle {{ wishlist_has($product->id) ? 'is-active' : '' }}"
                       title="Add to Wishlist" data-url="{{ route('wishlist.toggle', $product->id) }}">
                        <i class="icon-wishlist-2"></i>
                    </a>
                </form>
                @else
                <div style="margin-top:16px;">
                    <button class="btn btn-secondary" disabled>Out of Stock</button>
                </div>
                @endif

                @endif {{-- end auction/fixed --}}

                {{-- Short description (shared) --}}
                @if($product->short_description)
                <div class="product-desc" style="margin-top:14px;">
                    <p>{{ $product->short_description }}</p>
                </div>
                @endif

                {{-- Meta --}}
                <ul class="single-info-list">
                    @if($product->sku)
                    <li>SKU: <strong>{{ $product->sku }}</strong></li>
                    @endif
                    @if($product->category)
                    <li>CATEGORY:
                        <strong>
                            <a href="{{ route('category.show', $product->category->slug) }}" class="product-category">{{ $product->category->name }}</a>
                        </strong>
                    </li>
                    @endif
                    @if($product->brand)
                    <li>BRAND: <strong>{{ $product->brand->name }}</strong></li>
                    @endif
                    <li>CONDITION: <strong>{{ ucfirst($product->condition) }}</strong></li>
                    @if(!$product->is_auction)
                    <li>AVAILABILITY:
                        @if($product->stock > 0)
                            <strong style="color:#22c55e;">In Stock ({{ $product->stock }} units)</strong>
                        @else
                            <strong style="color:#ef4444;">Out of Stock</strong>
                        @endif
                    </li>
                    @else
                    <li>LISTING TYPE: <strong><i class="fas fa-gavel" style="color:#0f3460;"></i> Auction</strong></li>
                    @endif
                </ul>

                {{-- Shipping --}}
                @if($product->free_shipping)
                <div class="ps-ship-badge mt-3">
                    <i class="fas fa-truck"></i> <strong>Free Shipping</strong> on this item
                </div>
                @elseif($product->shipping_cost > 0)
                <div class="ps-ship-badge ps-ship-badge--paid mt-3">
                    <i class="fas fa-truck"></i> Shipping: <strong>${{ number_format($product->shipping_cost,2) }}</strong>
                </div>
                @endif

                {{-- ── Seller Info ── --}}
                @if($product->vendor)
                @php
                    $sv        = $product->vendor->vendorProfile;
                    $shopName  = $sv?->store_name ?? $product->vendor->name;
                    $isApproved = $sv?->status === 'approved';
                    $memberSince = ($sv?->created_at ?? $product->vendor->created_at)?->format('M Y') ?? '—';
                    $shopCount = $product->vendor->products()->where('status','approved')->count();
                @endphp
                <div style="margin-top:16px;border:1px solid #e8eef3;border-radius:10px;padding:14px 16px;background:#f8fafc;display:flex;align-items:center;gap:16px;flex-wrap:wrap;">
                    <div style="flex-shrink:0;">
                        @if($sv?->logo)
                            <img src="{{ asset('storage/'.$sv->logo) }}" alt="{{ $shopName }}"
                                 style="width:46px;height:46px;border-radius:50%;object-fit:cover;border:2px solid #dde3ea;">
                        @else
                            <div style="width:46px;height:46px;border-radius:50%;background:linear-gradient(135deg,#336699,#5b9bd5);display:flex;align-items:center;justify-content:center;font-size:20px;font-weight:800;color:#fff;">
                                {{ strtoupper(substr($shopName,0,1)) }}
                            </div>
                        @endif
                    </div>
                    <div style="flex:1;min-width:0;">
                        <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                            <span style="font-size:14px;font-weight:700;color:#1a1a2e;">{{ $shopName }}</span>
                            @if($isApproved)
                            <span style="background:#dcfce7;color:#16a34a;font-size:10px;font-weight:700;padding:2px 8px;border-radius:12px;display:inline-flex;align-items:center;gap:3px;">
                                <i class="fas fa-shield-alt"></i> Verified Seller
                            </span>
                            @endif
                        </div>
                        <div style="font-size:12px;color:#888;margin-top:3px;">
                            Member since {{ $memberSince }}
                            <span style="margin:0 6px;color:#ddd;">|</span>
                            {{ number_format($shopCount) }} {{ Str::plural('product', $shopCount) }} listed
                        </div>
                    </div>
                    @if($sv?->slug ?? null)
                    <a href="{{ route('home') }}?seller={{ $sv->slug }}" title="Visit {{ $shopName }}'s Shop"
                       style="flex-shrink:0;padding:7px 16px;border:1.5px solid #336699;border-radius:8px;font-size:12px;font-weight:700;color:#336699;text-decoration:none;white-space:nowrap;transition:all .2s;"
                       onmouseover="this.style.background='#336699';this.style.color='#fff';"
                       onmouseout="this.style.background='';this.style.color='#336699';">
                        <i class="fas fa-store" style="margin-right:4px;"></i> Visit Shop
                    </a>
                    @endif
                </div>
                @endif

                <hr class="divider mb-0 mt-3">

                {{-- Share --}}
                <div class="product-single-share mb-3">
                    <label class="sr-only">Share:</label>
                    <div class="social-icons mr-2">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}"
                           class="social-icon social-facebook icon-facebook" target="_blank" title="Facebook"></a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($product->name) }}"
                           class="social-icon social-twitter icon-twitter" target="_blank" title="Twitter"></a>
                    </div>
                </div>
            </div>
            {{-- End details --}}

        </div>
    </div>
    {{-- End product-single-container --}}

    {{-- ════════════════════════════════ TABS ════════════════════════════════ --}}
    <div class="product-single-tabs">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#ps-tab-desc" role="tab">Description</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#ps-tab-info" role="tab">Additional Information</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#product-reviews-content" role="tab">
                    Reviews ({{ $reviewCount }})
                </a>
            </li>
        </ul>

        <div class="tab-content">

            {{-- Description --}}
            <div class="tab-pane fade show active" id="ps-tab-desc" role="tabpanel">
                <div class="product-desc-content">
                    @if($product->description)
                        {!! nl2br(e($product->description)) !!}
                    @else
                        <p class="text-muted">No description available for this product.</p>
                    @endif
                </div>
            </div>

            {{-- Additional Info --}}
            <div class="tab-pane fade" id="ps-tab-info" role="tabpanel">
                <table class="table table-striped mt-2" style="max-width:600px;">
                    <tbody>
                        @if($product->sku)
                        <tr><th style="width:160px">SKU</th><td>{{ $product->sku }}</td></tr>
                        @endif
                        <tr><th>Condition</th><td>{{ ucfirst($product->condition) }}</td></tr>
                        @if($product->brand)
                        <tr><th>Brand</th><td>{{ $product->brand->name }}</td></tr>
                        @endif
                        @if($product->category)
                        <tr><th>Category</th><td>{{ $product->category->name }}</td></tr>
                        @endif
                        <tr><th>Listing Type</th><td>{{ ucfirst($product->listing_type) }}</td></tr>
                        <tr><th>Shipping</th>
                            <td>{{ $product->free_shipping ? 'Free Shipping' : '$'.number_format($product->shipping_cost,2) }}</td>
                        </tr>
                        @if(!$product->is_auction)
                        <tr><th>Stock</th><td>{{ $product->stock }} units</td></tr>
                        @endif
                        <tr><th>Views</th><td>{{ number_format($product->views) }}</td></tr>
                        {{-- Item Specifics --}}
                        @foreach($product->specValues as $sv)
                        @if($sv->specification && $sv->value)
                        <tr>
                            <th>{{ $sv->specification->display_label }}</th>
                            <td>{{ $sv->display_value }}{{ $sv->specification->unit ? ' ' . $sv->specification->unit : '' }}</td>
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Reviews --}}
            <div class="tab-pane fade" id="product-reviews-content" role="tabpanel">
                <div class="product-reviews-content">

                    <h3 class="reviews-title">
                        {{ $reviewCount }} {{ Str::plural('review', $reviewCount) }} for <em>{{ $product->name }}</em>
                    </h3>

                    @if($reviewCount > 0)
                    <div class="comment-list">
                        @foreach($reviews as $review)
                        <div class="comments" style="display:flex; gap:16px; margin-bottom:24px;">
                            <div class="rv-avatar" style="flex-shrink:0;">
                                {{ strtoupper(substr($review->reviewer_name, 0, 1)) }}
                            </div>
                            <div class="comment-block" style="flex:1;">
                                <div class="comment-header">
                                    <div class="comment-arrow"></div>
                                    <div class="ratings-container float-sm-right">
                                        <div class="product-ratings">
                                            <span class="ratings" style="width:{{ ($review->rating/5)*100 }}%"></span>
                                        </div>
                                    </div>
                                    <span class="comment-by">
                                        <strong>{{ $review->reviewer_name }}</strong>
                                        – {{ $review->created_at->format('F j, Y') }}
                                    </span>
                                </div>
                                <div class="comment-content">
                                    <p>{{ $review->review }}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="divider"></div>
                    @endif

                    {{-- Write review form --}}
                    <div class="add-product-review">
                        <h3 class="review-title">Add a review</h3>
                        <form action="{{ route('product.review.store', $product->slug) }}" method="POST" class="comment-form m-0">
                            @csrf
                            <div class="rating-form">
                                <label>Your rating <span class="required">*</span></label>
                                <span class="rating-stars">
                                    <a class="star-1" href="#">1</a>
                                    <a class="star-2" href="#">2</a>
                                    <a class="star-3" href="#">3</a>
                                    <a class="star-4" href="#">4</a>
                                    <a class="star-5" href="#">5</a>
                                </span>
                                <select name="rating" id="rating" required style="display:none;">
                                    <option value="">Rate…</option>
                                    <option value="5">Perfect</option>
                                    <option value="4">Good</option>
                                    <option value="3">Average</option>
                                    <option value="2">Not that bad</option>
                                    <option value="1">Very poor</option>
                                </select>
                                @error('rating')<span class="text-danger small d-block mt-1">{{ $message }}</span>@enderror
                            </div>

                            <div class="form-group mt-3">
                                <label>Your review <span class="required">*</span></label>
                                <textarea name="review" rows="6"
                                    class="form-control form-control-sm @error('review') is-invalid @enderror">{{ old('review') }}</textarea>
                                @error('review')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Name <span class="required">*</span></label>
                                        <input type="text" name="reviewer_name"
                                               value="{{ old('reviewer_name', auth()->user()?->name) }}"
                                               class="form-control form-control-sm @error('reviewer_name') is-invalid @enderror" required>
                                        @error('reviewer_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Email <span class="required">*</span></label>
                                        <input type="email" name="reviewer_email"
                                               value="{{ old('reviewer_email', auth()->user()?->email) }}"
                                               class="form-control form-control-sm @error('reviewer_email') is-invalid @enderror" required>
                                        @error('reviewer_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>

                            <input type="submit" class="btn btn-primary" value="Submit">
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
    {{-- End tabs --}}

    {{-- ════════════════════════════════ RELATED PRODUCTS ════════════════════════════════ --}}
    @if($related->count() > 0)
    <div class="products-section pt-0">
        <h2 class="section-title">Related Products</h2>
        <div class="products-slider owl-carousel owl-theme dots-top dots-small">
            @foreach($related as $rp)
            @php
                $rpImages   = $rp->images;
                $rpImg1     = $rpImages->first();
                $rpImg2     = $rpImages->skip(1)->first();
                $fallback   = asset('frontend-assets/images/demoes/demo36/products/product-1.jpg');
                $rpSrc1 = $rpImg1
                    ? (str_starts_with($rpImg1->path,'frontend-assets/') ? asset($rpImg1->path) : asset('storage/'.$rpImg1->path))
                    : $fallback;
                $rpSrc2 = $rpImg2
                    ? (str_starts_with($rpImg2->path,'frontend-assets/') ? asset($rpImg2->path) : asset('storage/'.$rpImg2->path))
                    : $rpSrc1;
                $rpDisc = ($rp->is_on_sale && $rp->price > 0)
                    ? round((1 - $rp->sale_price / $rp->price) * 100) : 0;
            @endphp
            <div class="product-default">
                <figure>
                    <a href="{{ route('product.show', $rp->slug) }}">
                        <img src="{{ $rpSrc1 }}" width="280" height="280" alt="{{ $rp->name }}">
                        <img src="{{ $rpSrc2 }}" width="280" height="280" alt="{{ $rp->name }}">
                    </a>
                    @if($rpDisc > 0 || $rp->is_featured)
                    <div class="label-group">
                        @if($rp->is_featured)<div class="product-label label-hot">HOT</div>@endif
                        @if($rpDisc > 0)<div class="product-label label-sale">-{{ $rpDisc }}%</div>@endif
                    </div>
                    @endif
                </figure>
                <div class="product-details">
                    @if($rp->category)
                    <div class="category-list">
                        <a href="{{ route('category.show', $rp->category->slug) }}" class="product-category">{{ $rp->category->name }}</a>
                    </div>
                    @endif
                    <h3 class="product-title">
                        <a href="{{ route('product.show', $rp->slug) }}">{{ $rp->name }}</a>
                    </h3>
                    <div class="ratings-container">
                        <div class="product-ratings">
                            <span class="ratings" style="width:0%"></span>
                            <span class="tooltiptext tooltip-top"></span>
                        </div>
                    </div>
                    <div class="price-box">
                        @if($rp->is_on_sale)
                            <del class="old-price">${{ number_format($rp->price,2) }}</del>
                            <span class="product-price">${{ number_format($rp->sale_price,2) }}</span>
                        @else
                            <span class="product-price">${{ number_format($rp->price,2) }}</span>
                        @endif
                    </div>
                    <div class="product-action">
                        <a href="#" title="Wishlist" class="btn-icon-wish"><i class="icon-heart"></i></a>
                        <a href="{{ route('product.show', $rp->slug) }}" class="btn-icon btn-add-cart">
                            <i class="fa fa-arrow-right"></i><span>SELECT OPTIONS</span>
                        </a>
                        <a href="{{ route('product.show', $rp->slug) }}" class="btn-quickview" title="Quick View">
                            <i class="fas fa-external-link-alt"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <hr class="mt-0 m-b-5">

    {{-- ════════════════════════════════ BOTTOM WIDGETS ════════════════════════════════ --}}
    @if($featuredProducts->count() > 0 || $latestProducts->count() > 0)
    <div class="row ps-widgets-section">

        @if($featuredProducts->count() > 0)
        <div class="col-lg-3 col-sm-6">
            <div class="ps-widget-col-title">Featured Products</div>
            @foreach($featuredProducts as $fp)
            @php
                $fpImg = $fp->primaryImage;
                $fpSrc = $fpImg
                    ? (str_starts_with($fpImg->path,'frontend-assets/') ? asset($fpImg->path) : asset('storage/'.$fpImg->path))
                    : asset('frontend-assets/images/demoes/demo36/products/product-1.jpg');
            @endphp
            <a href="{{ route('product.show', $fp->slug) }}" class="ps-widget-card">
                <img src="{{ $fpSrc }}" alt="{{ $fp->name }}">
                <div>
                    <div class="ps-widget-card__name">{{ Str::limit($fp->name, 38) }}</div>
                    <div class="ps-widget-card__price">${{ number_format($fp->effective_price, 2) }}</div>
                </div>
            </a>
            @endforeach
        </div>
        @endif

        @if($latestProducts->count() > 0)
        <div class="col-lg-3 col-sm-6">
            <div class="ps-widget-col-title">Latest Products</div>
            @foreach($latestProducts as $lp)
            @php
                $lpImg = $lp->primaryImage;
                $lpSrc = $lpImg
                    ? (str_starts_with($lpImg->path,'frontend-assets/') ? asset($lpImg->path) : asset('storage/'.$lpImg->path))
                    : asset('frontend-assets/images/demoes/demo36/products/product-1.jpg');
            @endphp
            <a href="{{ route('product.show', $lp->slug) }}" class="ps-widget-card">
                <img src="{{ $lpSrc }}" alt="{{ $lp->name }}">
                <div>
                    <div class="ps-widget-card__name">{{ Str::limit($lp->name, 38) }}</div>
                    <div class="ps-widget-card__price">${{ number_format($lp->effective_price, 2) }}</div>
                </div>
            </a>
            @endforeach
        </div>
        @endif

    </div>
    @endif

</div>{{-- /container --}}

@push('scripts')
<script>
// All product image URLs (passed from Blade)
var psAllImages = @json($imgSrcs->values());
var psCurrentIdx = 0;

function psInitZoom() {
    var $img = $('#ps-active-img');
    var ez = $img.data('elevateZoom');
    if (ez) { try { ez.kill(); } catch(e){} }
    $('.zoomContainer').remove();
    $img.removeData('elevateZoom');

    if ($.fn.elevateZoom) {
        $img.elevateZoom({
            responsive       : true,
            zoomWindowFadeIn : 350,
            zoomWindowFadeOut: 200,
            borderSize       : 0,
            zoomContainer    : $img.parent(),
            zoomType         : 'inner',
            cursor           : 'grab'
        });
    }
}

function psSwitchImg(thumb, src, idx) {
    psCurrentIdx = idx || 0;
    var $img = $('#ps-active-img');

    // Use elevateZoom's built-in swap method — designed exactly for thumbnail switching
    var ez = $img.data('elevateZoom');
    if (ez && typeof ez.swaptheimage === 'function') {
        ez.swaptheimage(src, src);
        $img.attr('src', src).attr('data-zoom-image', src);
    } else {
        // Fallback: full reinit
        $img.attr('src', src).attr('data-zoom-image', src);
        psInitZoom();
    }

    document.querySelectorAll('.ps-thumb').forEach(function(t) { t.classList.remove('active'); });
    thumb.classList.add('active');
}

function psQty(delta) {
    var inp = document.getElementById('ps-qty');
    var val = parseInt(inp.value) || 1;
    var max = parseInt(inp.max) || 9999;
    val = Math.max(1, Math.min(max, val + delta));
    inp.value = val;
}

$(function () {
    // Init zoom on page load
    psInitZoom();

    // prod-full-screen (+) button → Magnific Popup lightbox
    $(document).on('click', '.prod-full-screen', function (e) {
        e.stopPropagation();
        if (!$.fn.magnificPopup) return;
        var items = psAllImages.map(function (src) { return { src: src }; });
        $.magnificPopup.open({
            items           : items,
            type            : 'image',
            navigateByImgClick: true,
            index           : psCurrentIdx,
            gallery         : { enabled: true, navigateByImgClick: true, preload: [0, 1] },
            image           : { titleSrc: function () { return ''; } },
            removalDelay    : 300,
            mainClass       : 'mfp-fade'
        });
    });

    // Scroll to reviews tab when clicking rating link
    $(document).on('click', 'a[href="#product-reviews-content"]', function (e) {
        e.preventDefault();
        $('a[data-toggle="tab"][href="#product-reviews-content"]').tab('show');
        $('html, body').animate({ scrollTop: $('.product-single-tabs').offset().top - 80 }, 400);
    });
});

/* ── Auction countdown timer ── */
(function () {
    var el = document.getElementById('auction-countdown');
    if (!el) return;
    var endsAt = new Date(el.dataset.ends).getTime();

    function pad(n) { return String(n).padStart(2, '0'); }

    function tick() {
        var now  = Date.now();
        var diff = endsAt - now;
        if (diff <= 0) {
            el.innerHTML = '<span style="color:#ef4444;font-weight:700;font-size:14px;">Auction Ended</span>';
            return;
        }
        var days    = Math.floor(diff / 86400000);
        var hours   = Math.floor((diff % 86400000) / 3600000);
        var minutes = Math.floor((diff % 3600000) / 60000);
        var seconds = Math.floor((diff % 60000) / 1000);

        document.getElementById('cd-days').textContent    = pad(days);
        document.getElementById('cd-hours').textContent   = pad(hours);
        document.getElementById('cd-minutes').textContent = pad(minutes);
        document.getElementById('cd-seconds').textContent = pad(seconds);
    }
    tick();
    setInterval(tick, 1000);
})();
</script>
@endpush

@endsection
