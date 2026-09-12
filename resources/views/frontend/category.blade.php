@extends('frontend.layouts.app')
@section('title', $category->name . ' — ' . config('app.name'))

@section('content')

{{-- ── Breadcrumb ── --}}
<div class="container">
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}"><i class="icon-home"></i></a>
            </li>
            @if($category->parent)
                <li class="breadcrumb-item">
                    <a href="{{ route('category.show', $category->parent->slug) }}">
                        {{ $category->parent->name }}
                    </a>
                </li>
            @endif
            <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
        </ol>
    </nav>

    <div class="row">

        {{-- ── MAIN CONTENT col-lg-9 ── --}}
        <div class="col-lg-9 main-content">

            {{-- ── Toolbox ── --}}
            <nav class="toolbox sticky-header" data-sticky-options="{'mobile': true}">
                <div class="toolbox-left">
                    <a href="#" class="sidebar-toggle">
                        <svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px">
                            <line x1="15" x2="26" y1="9"  y2="9"  class="cls-1"/>
                            <line x1="6"  x2="9"  y1="9"  y2="9"  class="cls-1"/>
                            <line x1="23" x2="26" y1="16" y2="16" class="cls-1"/>
                            <line x1="6"  x2="17" y1="16" y2="16" class="cls-1"/>
                            <line x1="17" x2="26" y1="23" y2="23" class="cls-1"/>
                            <line x1="6"  x2="11" y1="23" y2="23" class="cls-1"/>
                            <path d="M14.5,8.92A2.6,2.6,0,0,1,12,11.5,2.6,2.6,0,0,1,9.5,8.92a2.5,2.5,0,0,1,5,0Z" class="cls-2"/>
                            <path d="M22.5,15.92a2.5,2.5,0,1,1-5,0,2.5,2.5,0,0,1,5,0Z" class="cls-2"/>
                            <path d="M16.5,22.92A2.6,2.6,0,0,1,14,25.5a2.6,2.6,0,0,1-2.5-2.58,2.5,2.5,0,0,1,5,0Z" class="cls-2"/>
                        </svg>
                        <span>Filter</span>
                    </a>

                    <div class="toolbox-item toolbox-sort">
                        <label>Sort By:</label>
                        <div class="select-custom">
                            <select class="form-control" id="select-sort" onchange="goWith({sort:this.value,page:1})">
                                <option value="default"    {{ $sort==='default'    ? 'selected':'' }}>Default sorting</option>
                                <option value="newest"     {{ $sort==='newest'     ? 'selected':'' }}>Sort by newness</option>
                                <option value="price_asc"  {{ $sort==='price_asc'  ? 'selected':'' }}>Sort by price: low to high</option>
                                <option value="price_desc" {{ $sort==='price_desc' ? 'selected':'' }}>Sort by price: high to low</option>
                            </select>
                        </div>
                    </div>
                </div>
                {{-- End .toolbox-left --}}

                <div class="toolbox-right">
                    <div class="toolbox-item toolbox-show">
                        <label>Show:</label>
                        <div class="select-custom">
                            <select class="form-control" id="select-perpage" onchange="goWith({per_page:this.value,page:1})">
                                <option value="12" {{ $perPage==12 ? 'selected':'' }}>12</option>
                                <option value="24" {{ $perPage==24 ? 'selected':'' }}>24</option>
                                <option value="30" {{ $perPage==30 ? 'selected':'' }}>30</option>
                                <option value="36" {{ $perPage==36 ? 'selected':'' }}>36</option>
                                <option value="48" {{ $perPage==48 ? 'selected':'' }}>48</option>
                            </select>
                        </div>
                    </div>

                    <div class="toolbox-item layout-modes">
                        <a href="#" class="layout-btn btn-grid active" id="btn-grid" title="Grid">
                            <i class="icon-mode-grid"></i>
                        </a>
                        <a href="#" class="layout-btn btn-list" id="btn-list" title="List">
                            <i class="icon-mode-list"></i>
                        </a>
                    </div>
                </div>
                {{-- End .toolbox-right --}}
            </nav>

            {{-- ── Products ── --}}
            @if($products->isNotEmpty())
            <div class="row pb-4" id="products-grid">
                @foreach($products as $product)
                @php
                    $pPath = $product->primaryImage->path ?? null;
                    $pImg  = $pPath
                        ? (str_starts_with($pPath,'frontend-assets/') ? asset($pPath) : asset('storage/'.$pPath))
                        : asset('frontend-assets/images/demoes/demo36/products/product-1.jpg');
                    $pSale = $product->effective_price;
                    $pOrig = $product->price;
                    $pDisc = ($pOrig > 0 && $pSale < $pOrig) ? round((($pOrig-$pSale)/$pOrig)*100) : 0;
                    $catUrl = $product->category ? route('category.show', $product->category->slug) : '#';
                    $catName = $product->category->name ?? '';
                    $desc = strip_tags($product->short_description ?? $product->description ?? '');
                    $desc = Str::limit($desc, 140);
                @endphp

                {{-- ── GRID card ── --}}
                @php
                    $showAuctBadge = $product->is_auction && setting('auction_badge_on_cards','1') === '1';
                    $showAuctTimer = $showAuctBadge && $product->auction_active && setting('auction_countdown_on_cards','1') === '1';
                @endphp
                <div class="col-6 col-sm-4 product-col product-col--grid">
                    <div class="product-default inner-quickview inner-icon">
                        <figure>
                            <a href="{{ route('product.show', $product->slug) }}">
                                <img src="{{ $pImg }}" width="280" height="280" alt="{{ $product->name }}">
                            </a>
                            <div class="label-group">
                                @if($product->is_featured)<div class="product-label label-hot">HOT</div>@endif
                                @if($showAuctBadge)
                                    <div class="product-label" style="background:#e53238;color:#fff;display:flex;align-items:center;gap:3px;">
                                        <i class="fas fa-gavel" style="font-size:10px"></i> Auction
                                    </div>
                                @elseif($pDisc > 0)
                                    <div class="product-label label-sale">-{{ $pDisc }}%</div>
                                @endif
                            </div>
                            <div class="btn-icon-group">
                                <a href="#" class="btn-icon btn-add-cart product-type-simple" title="Add To Cart"
                                   data-product-id="{{ $product->id }}">
                                    <i class="icon-shopping-cart"></i>
                                </a>
                            </div>
                            <a href="{{ route('product.quick-view', $product) }}" class="btn-quickview" title="Quick View">Quick View</a>
                        </figure>
                        <div class="product-details">
                            <div class="category-wrap">
                                <div class="category-list">
                                    <a href="{{ $catUrl }}" class="product-category">{{ $catName }}</a>
                                </div>
                                <a href="#" class="btn-icon-wish js-wish-toggle {{ wishlist_has($product->id) ? 'is-active' : '' }}"
                                   title="Add to Wishlist" data-url="{{ route('wishlist.toggle', $product->id) }}"><i class="icon-heart"></i></a>
                            </div>
                            <h3 class="product-title"><a href="{{ route('product.show', $product->slug) }}">{{ $product->name }}</a></h3>
                            <div class="ratings-container">
                                <div class="product-ratings">
                                    <span class="ratings" style="width:80%"></span>
                                    <span class="tooltiptext tooltip-top"></span>
                                </div>
                            </div>
                            @if($product->is_auction)
                            <div class="price-box">
                                <span class="product-price" style="color:#e53238">
                                    ${{ number_format($product->current_bid ?? $product->starting_bid ?? 0, 2) }}
                                </span>
                            </div>
                            @if($showAuctBadge)
                            <div style="font-size:11px;color:#767676;margin-top:2px;display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
                                <span><i class="fas fa-gavel" style="color:#e53238"></i>
                                    {{ $product->bid_count > 0 ? $product->bid_count.' bid'.($product->bid_count!==1?'s':'') : 'No bids' }}
                                </span>
                                @if($showAuctTimer)
                                <span class="pc-auc-cd" data-ends="{{ $product->auction_ends_at->toIso8601String() }}"
                                      style="color:#e53238;font-weight:600;">
                                    <i class="fas fa-clock"></i> <span class="pc-auc-cd__txt">...</span>
                                </span>
                                @elseif(!$product->auction_active)
                                <span style="color:#999"><i class="fas fa-ban"></i> Ended</span>
                                @endif
                            </div>
                            @endif
                            @else
                            <div class="price-box">
                                @if($pDisc > 0)
                                    <span class="old-price">${{ number_format($pOrig,2) }}</span>
                                    <span class="product-price">${{ number_format($pSale,2) }}</span>
                                @else
                                    <span class="product-price">${{ number_format($pSale,2) }}</span>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- ── LIST card ── --}}
                <div class="col-sm-12 col-6 product-col product-col--list" style="display:none">
                    <div class="product-default left-details product-list mb-2">
                        <figure>
                            <a href="{{ route('product.show', $product->slug) }}">
                                <img src="{{ $pImg }}" width="250" height="250" alt="{{ $product->name }}">
                            </a>
                            <div class="label-group">
                                @if($product->is_featured)<div class="product-label label-hot">HOT</div>@endif
                                @if($showAuctBadge)
                                    <div class="product-label" style="background:#e53238;color:#fff;display:flex;align-items:center;gap:3px;">
                                        <i class="fas fa-gavel" style="font-size:10px"></i> Auction
                                    </div>
                                @elseif($pDisc > 0)
                                    <div class="product-label label-sale">-{{ $pDisc }}%</div>
                                @endif
                            </div>
                        </figure>
                        <div class="product-details">
                            <div class="category-list">
                                <a href="{{ $catUrl }}" class="product-category">{{ $catName }}</a>
                            </div>
                            <h3 class="product-title"><a href="{{ route('product.show', $product->slug) }}">{{ $product->name }}</a></h3>
                            <div class="ratings-container">
                                <div class="product-ratings">
                                    <span class="ratings" style="width:80%"></span>
                                    <span class="tooltiptext tooltip-top"></span>
                                </div>
                            </div>
                            @if($desc)
                            <p class="product-description">{{ $desc }}</p>
                            @endif
                            @if($product->is_auction)
                            <div class="price-box">
                                <span class="product-price" style="color:#e53238">
                                    ${{ number_format($product->current_bid ?? $product->starting_bid ?? 0, 2) }}
                                </span>
                            </div>
                            @if($showAuctBadge)
                            <div style="font-size:12px;color:#767676;margin-top:4px;display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                                <span><i class="fas fa-gavel" style="color:#e53238"></i>
                                    {{ $product->bid_count > 0 ? $product->bid_count.' bid'.($product->bid_count!==1?'s':'') : 'No bids yet' }}
                                </span>
                                @if($showAuctTimer)
                                <span class="pc-auc-cd" data-ends="{{ $product->auction_ends_at->toIso8601String() }}"
                                      style="color:#e53238;font-weight:600;">
                                    <i class="fas fa-clock"></i> <span class="pc-auc-cd__txt">...</span>
                                </span>
                                @elseif(!$product->auction_active)
                                <span style="color:#999"><i class="fas fa-ban"></i> Auction Ended</span>
                                @endif
                            </div>
                            @endif
                            @else
                            <div class="price-box">
                                @if($pDisc > 0)
                                    <span class="old-price">${{ number_format($pOrig,2) }}</span>
                                    <span class="product-price">${{ number_format($pSale,2) }}</span>
                                @else
                                    <span class="product-price">${{ number_format($pSale,2) }}</span>
                                @endif
                            </div>
                            @endif
                            <div class="product-action">
                                <a href="#" class="btn-icon btn-add-cart product-type-simple"
                                   data-product-id="{{ $product->id }}">
                                    <i class="icon-shopping-cart"></i>
                                    <span>ADD TO CART</span>
                                </a>
                                <a href="#" class="btn-icon-wish js-wish-toggle {{ wishlist_has($product->id) ? 'is-active' : '' }}"
                                   title="Add to Wishlist" data-url="{{ route('wishlist.toggle', $product->id) }}">
                                    <i class="icon-heart"></i>
                                </a>
                                <a href="{{ route('product.quick-view', $product) }}" class="btn-quickview" title="Quick View">
                                    <i class="fas fa-external-link-alt"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                @endforeach
            </div>

            {{-- ── Pagination ── --}}
            @if($products->hasPages())
            <nav class="toolbox toolbox-pagination">
                <div class="toolbox-item toolbox-show">
                    <label>Show:</label>
                    <div class="select-custom">
                        <select class="form-control" onchange="goWith({per_page:this.value,page:1})">
                            <option value="12" {{ $perPage==12?'selected':'' }}>12</option>
                            <option value="24" {{ $perPage==24?'selected':'' }}>24</option>
                            <option value="30" {{ $perPage==30?'selected':'' }}>30</option>
                            <option value="36" {{ $perPage==36?'selected':'' }}>36</option>
                            <option value="48" {{ $perPage==48?'selected':'' }}>48</option>
                        </select>
                    </div>
                </div>
                <ul class="pagination toolbox-item">
                    <li class="page-item {{ $products->onFirstPage()?'disabled':'' }}">
                        <a class="page-link page-link-btn" href="{{ $products->previousPageUrl() ?? '#' }}"><i class="icon-angle-left"></i></a>
                    </li>
                    @foreach($products->getUrlRange(1,$products->lastPage()) as $page => $url)
                    <li class="page-item {{ $page==$products->currentPage()?'active':'' }}">
                        <a class="page-link" href="{{ $url }}">{{ $page }}@if($page==$products->currentPage())<span class="sr-only">(current)</span>@endif</a>
                    </li>
                    @endforeach
                    <li class="page-item {{ !$products->hasMorePages()?'disabled':'' }}">
                        <a class="page-link page-link-btn" href="{{ $products->nextPageUrl() ?? '#' }}"><i class="icon-angle-right"></i></a>
                    </li>
                </ul>
            </nav>
            @endif

            @else
            <div class="text-center py-5">
                <i class="icon-shopping-cart" style="font-size:60px;color:#e0e0e0;display:block;margin-bottom:16px;"></i>
                <h4 style="color:#777;font-weight:600;">No products found</h4>
                <p class="text-muted">Try a different category or adjust your filters.</p>
                <a href="{{ route('home') }}" class="btn btn-primary mt-2">Back to Home</a>
            </div>
            @endif

        </div>
        {{-- End col-lg-9 --}}

        {{-- ── SIDEBAR col-lg-3 (left) ── --}}
        <div class="sidebar-overlay"></div>
        <aside class="sidebar-shop col-lg-3 order-lg-first mobile-sidebar">
            <div class="sidebar-wrapper">

                {{-- Categories widget --}}
                <div class="widget">
                    <h3 class="widget-title">
                        <a data-toggle="collapse" href="#widget-cats" role="button"
                           aria-expanded="true" aria-controls="widget-cats">Categories</a>
                    </h3>
                    <div class="collapse show" id="widget-cats">
                        <div class="widget-body">
                            <ul class="cat-list">
                                @foreach($allCategories as $parentCat)
                                @php
                                    $isCurrentParent = $category->id === $parentCat->id;
                                    $isChildActive   = $category->parent_id === $parentCat->id;
                                    $isExpanded      = $isCurrentParent || $isChildActive;
                                    $colId           = 'cat-'.$parentCat->id;
                                    $totalCount      = $parentCat->product_count ?? 0;
                                @endphp
                                <li class="{{ $isCurrentParent ? 'active' : '' }}">
                                    @if($parentCat->children->isNotEmpty())
                                        <a href="#{{ $colId }}" data-toggle="collapse" role="button"
                                           aria-expanded="{{ $isExpanded ? 'true' : 'false' }}"
                                           aria-controls="{{ $colId }}"
                                           class="{{ $isExpanded ? '' : 'collapsed' }}">
                                            {{ $parentCat->name }}
                                            <span class="products-count">({{ $totalCount }})</span>
                                            <span class="toggle"></span>
                                        </a>
                                        <div class="collapse {{ $isExpanded ? 'show' : '' }}" id="{{ $colId }}">
                                            <ul class="cat-sublist">
                                                <li class="{{ $isCurrentParent ? 'active' : '' }}">
                                                    <a href="{{ route('category.show', $parentCat->slug) }}">
                                                        All {{ $parentCat->name }}
                                                        <span class="products-count">({{ $totalCount }})</span>
                                                    </a>
                                                </li>
                                                @foreach($parentCat->children as $childCat)
                                                <li class="{{ $category->id === $childCat->id ? 'active' : '' }}">
                                                    <a href="{{ route('category.show', $childCat->slug) }}">
                                                        {{ $childCat->name }}
                                                        <span class="products-count">({{ $childCat->product_count ?? 0 }})</span>
                                                    </a>
                                                </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @else
                                        <a href="{{ route('category.show', $parentCat->slug) }}"
                                           style="{{ $isCurrentParent ? 'color:#336699;font-weight:600;' : '' }}">
                                            {{ $parentCat->name }}
                                            <span class="products-count">({{ $totalCount }})</span>
                                        </a>
                                    @endif
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>


            </div>
        </aside>
        {{-- End sidebar --}}

    </div>
    {{-- End .row --}}
</div>
{{-- End .container --}}

@endsection

@push('scripts')
<script>
// Navigate keeping all existing query params + overrides
function goWith(overrides) {
    var url = new URL(window.location.href);
    for (var k in overrides) url.searchParams.set(k, overrides[k]);
    window.location = url.toString();
}

(function () {
    // ── Grid / List toggle ────────────────────────────────────
    var btnGrid  = document.getElementById('btn-grid');
    var btnList  = document.getElementById('btn-list');
    var gridCols = document.querySelectorAll('.product-col--grid');
    var listCols = document.querySelectorAll('.product-col--list');

    function setGrid() {
        btnGrid.classList.add('active');
        btnList.classList.remove('active');
        gridCols.forEach(function(el){ el.style.display = ''; });
        listCols.forEach(function(el){ el.style.display = 'none'; });
    }
    function setList() {
        btnList.classList.add('active');
        btnGrid.classList.remove('active');
        gridCols.forEach(function(el){ el.style.display = 'none'; });
        listCols.forEach(function(el){ el.style.display = ''; });
    }

    if (btnGrid) btnGrid.addEventListener('click', function(e){ e.preventDefault(); setGrid(); });
    if (btnList) btnList.addEventListener('click', function(e){ e.preventDefault(); setList(); });
})();

// ── Auction card countdowns ───────────────────────────────
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
