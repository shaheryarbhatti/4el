@extends('frontend.layouts.app')
@section('title', $q ? '"'.$q.'" — Search Results' : 'Search Results')

@push('styles')
<style>
.sr-wrap { display: flex; gap: 28px; align-items: flex-start; }
.sr-sidebar {
    flex: 0 0 220px; min-width: 0;
    background: #fff; border: 1px solid #e5e5e5; border-radius: 10px; padding: 0;
    overflow: hidden; position: sticky; top: 80px;
}
.sr-sidebar-title {
    font-size: 13px; font-weight: 700; color: #191919;
    padding: 14px 16px; border-bottom: 1px solid #e5e5e5; background: #f7f7f7;
    display: flex; align-items: center; gap: 8px;
}
.sr-sidebar-section { padding: 14px 16px; border-bottom: 1px solid #f0f0f0; }
.sr-sidebar-section-title { font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; color: #767676; margin-bottom: 10px; }
.sr-cat-item {
    display: flex; align-items: center; justify-content: space-between;
    padding: 5px 0; font-size: 13px; color: #333; text-decoration: none; transition: color .1s;
}
.sr-cat-item:hover { color: #3665f3; text-decoration: none; }
.sr-cat-item.active { color: #3665f3; font-weight: 700; }
.sr-cat-count { font-size: 11px; color: #999; background: #f0f0f0; padding: 1px 6px; border-radius: 10px; }
.sr-price-row { display: flex; gap: 8px; align-items: center; margin-bottom: 12px; }
.sr-price-input { flex: 1; border: 1px solid #e5e5e5; border-radius: 6px; padding: 6px 10px; font-size: 13px; outline: none; width: 0; }
.sr-price-input:focus { border-color: #3665f3; }
.sr-filter-btn { width: 100%; padding: 8px; border: 1px solid #3665f3; border-radius: 6px; background: #fff; color: #3665f3; font-size: 13px; font-weight: 600; cursor: pointer; transition: all .15s; }
.sr-filter-btn:hover { background: #3665f3; color: #fff; }
.sr-cond-pill { display: inline-flex; align-items: center; gap: 5px; padding: 5px 12px; border: 1px solid #e5e5e5; border-radius: 20px; font-size: 12px; font-weight: 600; color: #555; text-decoration: none; margin: 3px 3px 3px 0; transition: all .15s; }
.sr-cond-pill:hover { border-color: #3665f3; color: #3665f3; text-decoration: none; }
.sr-cond-pill.active { background: #3665f3; border-color: #3665f3; color: #fff; }

.sr-main { flex: 1; min-width: 0; }
.sr-header { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; margin-bottom: 18px; padding-bottom: 14px; border-bottom: 1px solid #e5e5e5; }
.sr-count { font-size: 13px; color: #767676; }
.sr-count strong { color: #191919; }
.sr-controls { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
.sr-select { border: 1px solid #e5e5e5; border-radius: 6px; padding: 6px 28px 6px 10px; font-size: 13px; color: #333; outline: none; background: #fff; cursor: pointer; }
.sr-select:focus { border-color: #3665f3; }

/* Product grid — reuse Porto-compatible cards */
.sr-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 18px; }
@media (max-width: 900px) { .sr-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 600px) { .sr-grid { grid-template-columns: 1fr; } }
.sr-card { background: #fff; border: 1px solid #e5e5e5; border-radius: 10px; overflow: hidden; text-decoration: none; color: inherit; display: flex; flex-direction: column; transition: box-shadow .2s, transform .2s; }
.sr-card:hover { box-shadow: 0 4px 20px rgba(0,0,0,.12); transform: translateY(-2px); text-decoration: none; color: inherit; }
.sr-card__img { position: relative; aspect-ratio: 1; overflow: hidden; background: #f7f7f7; }
.sr-card__img img { width: 100%; height: 100%; object-fit: cover; display: block; transition: transform .3s; }
.sr-card:hover .sr-card__img img { transform: scale(1.04); }
.sr-card__img-ph { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: #ddd; font-size: 48px; }
.sr-card__badge { position: absolute; top: 8px; left: 8px; background: #e53238; color: #fff; font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 4px; }
.sr-card__body { padding: 12px 14px 14px; display: flex; flex-direction: column; gap: 6px; flex: 1; }
.sr-card__name { font-size: 13.5px; font-weight: 600; color: #191919; line-height: 1.35; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
.sr-card__meta { font-size: 11.5px; color: #999; }
.sr-card__price { margin-top: auto; }
.sr-card__price-sale { font-size: 17px; font-weight: 800; color: #e53238; }
.sr-card__price-orig { font-size: 12px; color: #999; text-decoration: line-through; margin-left: 4px; }
.sr-card__price-reg { font-size: 17px; font-weight: 800; color: #191919; }

/* Empty state */
.sr-empty { text-align: center; padding: 60px 20px; color: #999; }
.sr-empty i { font-size: 52px; display: block; margin-bottom: 16px; color: #ddd; }
.sr-empty h3 { font-size: 18px; font-weight: 700; color: #333; margin-bottom: 8px; }
.sr-empty p { font-size: 14px; line-height: 1.6; }

/* Active filters */
.sr-active-filter { display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px; background: #e8f0fe; border-radius: 20px; font-size: 12px; font-weight: 600; color: #3665f3; margin: 0 4px 4px 0; text-decoration: none; }
.sr-active-filter i { font-size: 10px; }
.sr-active-filter:hover { background: #d0e2fc; text-decoration: none; color: #3665f3; }

@media (max-width: 768px) {
    .sr-wrap { flex-direction: column; }
    .sr-sidebar { flex: none; width: 100%; position: static; }
}
</style>
@endpush

@section('content')
<div class="container" style="padding-top: 28px; padding-bottom: 48px;">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" style="margin-bottom: 16px;">
        <ol class="breadcrumb" style="font-size:13px;padding:0;background:none;margin:0;">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active">
                {{ $q ? 'Results for "'.$q.'"' : 'All Products' }}
            </li>
        </ol>
    </nav>

    {{-- Page heading --}}
    <div style="margin-bottom: 20px;">
        <h1 style="font-size: 22px; font-weight: 800; color: #191919; margin: 0 0 4px;">
            @if ($q)
                Results for <span style="color:#3665f3;">"{{ $q }}"</span>
            @else
                All Products
            @endif
        </h1>
        <p style="font-size:13px;color:#767676;margin:0;">
            {{ number_format($products->total()) }} result{{ $products->total() !== 1 ? 's' : '' }}
            @if ($selectedCategory) in <strong>{{ $selectedCategory->name }}</strong>@endif
        </p>
    </div>

    <div class="sr-wrap">

        {{-- ====== SIDEBAR ====== --}}
        <aside class="sr-sidebar">
            <div class="sr-sidebar-title">
                <i class="fas fa-filter" style="color:#3665f3"></i> Refine Results
            </div>

            {{-- Category filter --}}
            <div class="sr-sidebar-section">
                <div class="sr-sidebar-section-title">Category</div>
                <a href="{{ route('search', array_merge(request()->except(['cat','page']), ['q'=>$q])) }}"
                   class="sr-cat-item {{ !$catId ? 'active' : '' }}">
                    All Categories
                </a>
                @foreach ($categories as $cat)
                    @if ($cat->product_count > 0 || $catId == $cat->id)
                    <a href="{{ route('search', array_merge(request()->except(['cat','page']), ['q'=>$q,'cat'=>$cat->id])) }}"
                       class="sr-cat-item {{ $catId == $cat->id ? 'active' : '' }}">
                        {{ $cat->name }}
                        <span class="sr-cat-count">{{ $cat->product_count }}</span>
                    </a>
                    @endif
                @endforeach
            </div>

            {{-- Price filter --}}
            <div class="sr-sidebar-section">
                <div class="sr-sidebar-section-title">Price</div>
                <form action="{{ route('search') }}" method="GET">
                    <input type="hidden" name="q"    value="{{ $q }}">
                    <input type="hidden" name="cat"  value="{{ $catId }}">
                    <input type="hidden" name="sort" value="{{ $sort }}">
                    <input type="hidden" name="condition" value="{{ $condition }}">
                    <div class="sr-price-row">
                        <input type="number" name="min_price" class="sr-price-input"
                               placeholder="Min $" value="{{ $minPrice }}" min="0" step="0.01">
                        <span style="color:#999;font-size:12px;">to</span>
                        <input type="number" name="max_price" class="sr-price-input"
                               placeholder="Max $" value="{{ $maxPrice }}" min="0" step="0.01">
                    </div>
                    <button type="submit" class="sr-filter-btn">Apply</button>
                </form>
            </div>

            {{-- Condition filter --}}
            <div class="sr-sidebar-section">
                <div class="sr-sidebar-section-title">Condition</div>
                @foreach (['new'=>'New','used'=>'Used','refurbished'=>'Refurbished'] as $val => $lbl)
                    <a href="{{ route('search', array_merge(request()->except(['condition','page']), ['q'=>$q,'condition'=>$condition===$val?'':$val])) }}"
                       class="sr-cond-pill {{ $condition === $val ? 'active' : '' }}">
                        @if ($condition === $val)<i class="fas fa-check"></i>@endif
                        {{ $lbl }}
                    </a>
                @endforeach
            </div>

            {{-- Clear filters --}}
            @if ($catId || $minPrice || $maxPrice || $condition)
            <div class="sr-sidebar-section">
                <a href="{{ route('search', ['q' => $q]) }}"
                   style="font-size:13px;color:#e53238;font-weight:600;text-decoration:none;display:flex;align-items:center;gap:5px;">
                    <i class="fas fa-times-circle"></i> Clear all filters
                </a>
            </div>
            @endif
        </aside>

        {{-- ====== MAIN RESULTS ====== --}}
        <div class="sr-main">

            {{-- Toolbar --}}
            <div class="sr-header">
                <div class="sr-count">
                    Showing <strong>{{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }}</strong>
                    of <strong>{{ number_format($products->total()) }}</strong> results
                </div>
                <div class="sr-controls">
                    <select class="sr-select" onchange="applySort(this.value)">
                        <option value="default"    {{ $sort==='default'    ? 'selected':'' }}>Best Match</option>
                        <option value="newest"     {{ $sort==='newest'     ? 'selected':'' }}>Newly Listed</option>
                        <option value="price_asc"  {{ $sort==='price_asc'  ? 'selected':'' }}>Price: Low to High</option>
                        <option value="price_desc" {{ $sort==='price_desc' ? 'selected':'' }}>Price: High to Low</option>
                    </select>
                    <select class="sr-select" onchange="applyPerPage(this.value)">
                        <option value="12"  {{ $perPage==12  ? 'selected':'' }}>12 / page</option>
                        <option value="24"  {{ $perPage==24  ? 'selected':'' }}>24 / page</option>
                        <option value="36"  {{ $perPage==36  ? 'selected':'' }}>36 / page</option>
                        <option value="48"  {{ $perPage==48  ? 'selected':'' }}>48 / page</option>
                    </select>
                </div>
            </div>

            @if ($products->count())
                <div class="sr-grid">
                    @foreach ($products as $product)
                    @php
                        $img = $product->primaryImage;
                        $imgUrl = $img
                            ? (str_starts_with($img->path, 'frontend-assets/')
                                ? asset($img->path)
                                : asset('storage/'.$img->path))
                            : null;
                        $hasDiscount = $product->sale_price && $product->sale_price < $product->price;
                        $pct = $hasDiscount ? round((1 - $product->sale_price/$product->price)*100) : 0;
                    @endphp
                    @php
                        $srAuct      = $product->is_auction && setting('auction_badge_on_cards','1') === '1';
                        $srAuctTimer = $srAuct && $product->auction_active && setting('auction_countdown_on_cards','1') === '1';
                    @endphp
                    <a href="{{ route('product.show', $product->slug) }}" class="sr-card">
                        <div class="sr-card__img">
                            @if ($imgUrl)
                                <img src="{{ $imgUrl }}" alt="{{ $product->name }}" loading="lazy">
                            @else
                                <div class="sr-card__img-ph"><i class="fas fa-image"></i></div>
                            @endif
                            @if ($srAuct)
                                <span class="sr-card__badge" style="background:#e53238;">
                                    <i class="fas fa-gavel" style="font-size:9px"></i> Auction
                                </span>
                            @elseif ($hasDiscount)
                                <span class="sr-card__badge">-{{ $pct }}%</span>
                            @endif
                        </div>
                        <div class="sr-card__body">
                            <div class="sr-card__name">{{ $product->name }}</div>
                            <div class="sr-card__meta">
                                {{ $product->category?->name }}
                                @if ($product->condition)
                                    · <span style="text-transform:capitalize">{{ $product->condition }}</span>
                                @endif
                            </div>
                            <div class="sr-card__price">
                                @if ($product->is_auction)
                                    <span class="sr-card__price-sale" style="color:#e53238">
                                        ${{ number_format($product->current_bid ?? $product->starting_bid ?? 0, 2) }}
                                    </span>
                                @elseif ($hasDiscount)
                                    <span class="sr-card__price-sale">${{ number_format($product->sale_price, 2) }}</span>
                                    <span class="sr-card__price-orig">${{ number_format($product->price, 2) }}</span>
                                @else
                                    <span class="sr-card__price-reg">${{ number_format($product->price, 2) }}</span>
                                @endif
                            </div>
                            @if ($srAuct)
                            <div style="font-size:11px;color:#767676;margin-top:4px;display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
                                <span><i class="fas fa-gavel" style="color:#e53238;font-size:10px"></i>
                                    {{ $product->bid_count > 0 ? $product->bid_count.' bid'.($product->bid_count!==1?'s':'') : 'No bids' }}
                                </span>
                                @if ($srAuctTimer)
                                <span class="pc-auc-cd" data-ends="{{ $product->auction_ends_at->toIso8601String() }}"
                                      style="color:#e53238;font-weight:600;font-size:11px;">
                                    <i class="fas fa-clock"></i> <span class="pc-auc-cd__txt">...</span>
                                </span>
                                @elseif (!$product->auction_active)
                                <span style="color:#aaa"><i class="fas fa-ban"></i> Ended</span>
                                @endif
                            </div>
                            @endif
                        </div>
                    </a>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div style="margin-top: 32px; display: flex; justify-content: center;">
                    {{ $products->links() }}
                </div>
            @else
                <div class="sr-empty">
                    <i class="fas fa-search"></i>
                    <h3>No results found</h3>
                    <p>
                        @if ($q)
                            We couldn't find anything matching <strong>"{{ $q }}"</strong>.<br>
                            Try different keywords, or browse by category.
                        @else
                            No products are available at the moment.
                        @endif
                    </p>
                    <a href="{{ route('home') }}" style="display:inline-flex;align-items:center;gap:8px;margin-top:16px;padding:10px 24px;background:#3665f3;color:#fff;border-radius:20px;font-size:14px;font-weight:700;text-decoration:none;">
                        <i class="fas fa-home"></i> Go to Homepage
                    </a>
                </div>
            @endif

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function applySort(val) {
    var url = new URL(window.location.href);
    url.searchParams.set('sort', val);
    url.searchParams.delete('page');
    window.location.href = url.toString();
}
function applyPerPage(val) {
    var url = new URL(window.location.href);
    url.searchParams.set('per_page', val);
    url.searchParams.delete('page');
    window.location.href = url.toString();
}

// Auction card countdowns
(function () {
    function fmtRemaining(endsAt) {
        var diff = endsAt - Date.now();
        if (diff <= 0) return 'Ended';
        var h = Math.floor(diff / 3600000);
        var m = Math.floor((diff % 3600000) / 60000);
        var s = Math.floor((diff % 60000) / 1000);
        if (h > 24) return Math.floor(h/24) + 'd ' + (h%24) + 'h';
        if (h > 0)  return h + 'h ' + m + 'm';
        return m + 'm ' + s + 's';
    }
    var els = document.querySelectorAll('.pc-auc-cd');
    if (!els.length) return;
    var ends = Array.from(els).map(function(el) {
        return { el: el, txt: el.querySelector('.pc-auc-cd__txt'), endsAt: new Date(el.dataset.ends).getTime() };
    });
    function tick() {
        ends.forEach(function(o) {
            var str = fmtRemaining(o.endsAt);
            o.txt.textContent = str;
            if (str === 'Ended') o.el.style.color = '#999';
        });
    }
    tick();
    setInterval(tick, 1000);
})();
</script>
@endpush
