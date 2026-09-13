{{-- =====================================================================
     STOREFRONT HEADER — eBay-style layout
     Three rows:
       1. Top info bar  (sign-in links, deals, help | sell, account, cart)
       2. Middle bar    (logo, shop-by-category, search, account icon, cart)
       3. Category tabs (top-level categories with megamenu dropdowns)
     ===================================================================== --}}
@php
    use App\Models\Category;
    $navCats = Category::active()->parents()->orderBy('sort_order')->with([
        'children' => fn($q) => $q->active()->orderBy('sort_order'),
    ])->get();
@endphp

<style>
/* ============================================================
   HEADER — eBay style
   ============================================================ */
.eb-header { background: #fff; border-bottom: 1px solid #e5e5e5; font-family: 'Open Sans', sans-serif; }

/* ---- TOP INFO BAR ---- */
.eb-top { background: #fff; border-bottom: 1px solid #e5e5e5; font-size: 12px; }
.eb-top__inner {
    display: flex; align-items: center; justify-content: space-between;
    padding: 11px 0 5px; gap: 16px; flex-wrap: wrap;
}
.eb-top__left, .eb-top__right { display: flex; align-items: center; gap: 18px; }
.eb-top__left a, .eb-top__right a { color: #333; text-decoration: none; font-size: 12px; }
.eb-top__left a:hover, .eb-top__right a:hover { text-decoration: underline; }
.eb-top__hi { color: #555; }
.eb-top__sep { color: #ccc; }
.eb-top__dropdown { position: relative; display: inline-block; }
.eb-top__dropdown-btn {
    background: none; border: none; cursor: pointer; font-size: 12px;
    color: #333; display: flex; align-items: center; gap: 3px; padding: 0;
}
.eb-top__dropdown-btn:hover { text-decoration: underline; }
.eb-top__dropdown-menu {
    display: none; position: absolute; top: calc(100% + 6px); right: 0;
    background: #fff; border: 1px solid #e5e5e5; border-radius: 6px;
    min-width: 160px; box-shadow: 0 4px 16px rgba(0,0,0,.12); z-index: 999;
    padding: 6px 0;
}
.eb-top__dropdown-menu a {
    display: block; padding: 8px 16px; font-size: 13px; color: #333; text-decoration: none;
}
.eb-top__dropdown-menu a:hover { background: #f5f5f5; }
.eb-top__dropdown:hover .eb-top__dropdown-menu { display: block; }
.eb-top__icon-btn {
    background: none; border: none; cursor: pointer; font-size: 18px; color: #333;
    padding: 2px; position: relative;
}
.eb-top__icon-btn:hover { color: #3665f3; }
.eb-top__badge {
    position: absolute; top: -6px; right: -8px;
    background: #e53238; color: #fff; font-size: 10px; font-weight: 700;
    border-radius: 50%; width: 16px; height: 16px;
    display: flex; align-items: center; justify-content: center;
}

/* ---- MIDDLE BAR ---- */
.eb-mid { padding: 0; position: relative; }
.eb-midbar {
    display: table !important;
    width: 100% !important;
    height: 68px !important;
    border-collapse: separate !important;
    border-spacing: 0 !important;
    table-layout: auto !important;
}
.eb-midbar__cell {
    display: table-cell !important;
    vertical-align: middle !important;
    white-space: nowrap;
    padding: 0 7px;
}
.eb-midbar__cell--stretch {
    display: table-cell !important;
    vertical-align: middle !important;
    width: 100% !important;
    padding: 12px 7px;
}
.eb-midbar__cell:first-child { padding-left: 0; }
.eb-midbar__cell:last-child  { padding-right: 0; }
.eb-midbar__cell--stretch:first-child { padding-left: 0; }
.eb-midbar__cell--stretch:last-child  { padding-right: 0; }

/* Logo */
.eb-logo { display: inline-flex; align-items: center; line-height: 1; text-decoration: none; }
.eb-logo img { height: 40px; width: auto; max-width: none; flex-shrink: 0; display: block; }
.eb-logo-text {
    font-size: 30px; font-weight: 900; text-decoration: none;
    color: #e53238; letter-spacing: -1px; line-height: 1;
}
.eb-logo-text span:nth-child(1) { color: #e53238; }
.eb-logo-text span:nth-child(2) { color: #0064d2; }
.eb-logo-text span:nth-child(3) { color: #f5af02; }
.eb-logo-text span:nth-child(4) { color: #86b817; }

/* Shop by category */
.eb-shopby { flex: 0 0 auto; position: static; }
.eb-shopby__btn {
    display: flex !important; align-items: center; gap: 6px; background: none;
    border: 1px solid #e5e5e5; border-radius: 20px; padding: 8px 14px;
    font-size: 13px; font-weight: 600; color: #333; cursor: pointer;
    white-space: nowrap; transition: background .15s; line-height: 1;
}
.eb-shopby__btn:hover { background: #f5f5f5; }
.eb-shopby__mega {
    display: none; position: absolute; top: 100%; left: 0; right: 0;
    background: #fff; border: 1px solid #e5e5e5; border-top: none;
    box-shadow: 0 8px 28px rgba(0,0,0,.14); z-index: 9999;
    padding: 28px 32px 16px;
}
/* mega open state toggled by JS */
.eb-shopby__mega.is-open { display: block; }
.eb-shopby__grid {
    display: grid; grid-template-columns: repeat(3, 1fr); gap: 0 32px;
    max-height: 520px; overflow-y: auto;
}
.eb-shopby__group { margin-bottom: 22px; break-inside: avoid; }
.eb-shopby__group-title {
    font-size: 13.5px; font-weight: 700; color: #191919;
    margin-bottom: 7px; text-decoration: none; display: flex; align-items: center; gap: 7px;
}
.eb-shopby__group-title:hover { color: #3665f3; }
.eb-shopby__group-title i { font-size: 13px; color: #3665f3; width: 16px; text-align: center; }
.eb-shopby__sub { list-style: none; margin: 0; padding: 0; }
.eb-shopby__sub li { line-height: 1; }
.eb-shopby__sub a {
    display: block; font-size: 13px; color: #555; text-decoration: none;
    padding: 4px 0 4px 23px; transition: color .1s;
}
.eb-shopby__sub a:hover { color: #3665f3; }
.eb-shopby__footer {
    display: flex; gap: 32px; border-top: 1px solid #e5e5e5;
    margin-top: 4px; padding-top: 14px;
}
.eb-shopby__footer a {
    font-size: 13px; font-weight: 600; color: #3665f3; text-decoration: none;
}
.eb-shopby__footer a:hover { text-decoration: underline; }

/* Search */
.eb-search { display: block; position: relative; }
.eb-search__form {
    display: flex; border: 2px solid #3665f3; border-radius: 26px;
    overflow: hidden; background: #fff; width: 100%; height: 44px;
    transition: border-color .15s, box-shadow .15s;
}
.eb-search__form:focus-within {
    border-color: #1e4fc4; box-shadow: 0 0 0 3px rgba(54,101,243,.15);
    border-radius: 26px 26px 0 0;
}
.eb-search__form:focus-within:not(.has-suggestions) { border-radius: 26px; }
.eb-search__input {
    flex: 1; border: none; outline: none; padding: 0 16px;
    font-size: 14px; color: #333; background: transparent; height: 100%;
}
.eb-search__select {
    border: none; border-left: 1px solid #e5e5e5; outline: none;
    padding: 0 12px; font-size: 13px; color: #555; background: #f7f7f7;
    cursor: pointer; min-width: 140px; height: 100%;
}
.eb-search__btn {
    background: #3665f3; border: none; color: #fff; padding: 0 22px;
    font-size: 15px; font-weight: 700; cursor: pointer; transition: background .15s;
    white-space: nowrap; height: 100%;
}
.eb-search__btn:hover { background: #1e4fc4; }

/* Autocomplete dropdown */
.eb-search__suggest {
    display: none; position: absolute; top: 100%; left: 0; right: 0; z-index: 9999;
    background: #fff; border: 2px solid #1e4fc4; border-top: none;
    border-radius: 0 0 14px 14px;
    box-shadow: 0 8px 28px rgba(0,0,0,.13);
    max-height: 420px; overflow-y: auto;
}
.eb-search__suggest.show { display: block; }
.eb-suggest-section { padding: 6px 0 0; }
.eb-suggest-label {
    font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .07em;
    color: #999; padding: 8px 16px 4px;
}
.eb-suggest-product {
    display: flex; align-items: center; gap: 12px;
    padding: 9px 16px; cursor: pointer; text-decoration: none; color: #333;
    transition: background .12s;
}
.eb-suggest-product:hover, .eb-suggest-product.focused { background: #f0f4ff; color: #333; text-decoration: none; }
.eb-suggest-product__img {
    width: 40px; height: 40px; border-radius: 6px; object-fit: cover;
    border: 1px solid #e5e5e5; flex-shrink: 0; background: #f7f7f7;
}
.eb-suggest-product__img-ph {
    width: 40px; height: 40px; border-radius: 6px; flex-shrink: 0;
    background: #f0f0f0; display: flex; align-items: center; justify-content: center;
    color: #ccc; font-size: 18px;
}
.eb-suggest-product__name { font-size: 13.5px; font-weight: 600; flex: 1; line-height: 1.3; }
.eb-suggest-product__price { font-size: 13px; font-weight: 700; color: #3665f3; white-space: nowrap; }
.eb-suggest-cat {
    display: flex; align-items: center; gap: 8px;
    padding: 8px 16px; cursor: pointer; text-decoration: none; color: #555;
    font-size: 13px; transition: background .12s;
}
.eb-suggest-cat:hover, .eb-suggest-cat.focused { background: #f0f4ff; color: #3665f3; text-decoration: none; }
.eb-suggest-cat i { color: #3665f3; font-size: 12px; width: 14px; text-align: center; }
.eb-suggest-all {
    display: block; padding: 10px 16px; font-size: 13px; font-weight: 600;
    color: #3665f3; border-top: 1px solid #e5e5e5; text-decoration: none;
    transition: background .12s; text-align: center;
}
.eb-suggest-all:hover { background: #f0f4ff; color: #3665f3; text-decoration: none; }

/* Right icons */
.eb-mid__icons { display: flex !important; align-items: center; gap: 16px; flex: 0 0 auto; }
.eb-icon-group {
    display: flex; flex-direction: column; align-items: center; gap: 1px;
    text-decoration: none; color: #333; line-height: 1.2;
}
.eb-icon-group:hover { color: #3665f3; }
.eb-icon-group__top { font-size: 11px; color: #767676; white-space: nowrap; }
.eb-icon-group__main { font-size: 13px; font-weight: 600; white-space: nowrap; }
.eb-cart-btn {
    position: relative; background: none; border: none; cursor: pointer;
    color: #333; font-size: 22px; padding: 0; display: flex; align-items: center;
}
.eb-cart-btn:hover { color: #3665f3; }
.eb-cart-badge {
    position: absolute; top: -6px; right: -10px;
    background: #e53238; color: #fff; font-size: 10px; font-weight: 700;
    border-radius: 50%; width: 18px; height: 18px;
    display: flex; align-items: center; justify-content: center;
}

/* ---- CATEGORY TABS BAR ---- */
.eb-cats { border-top: 1px solid #e5e5e5; background: #fff; }
.eb-cats__inner { display: flex; align-items: center; overflow-x: auto; scrollbar-width: none; }
.eb-cats__inner::-webkit-scrollbar { display: none; }
.eb-cats__tab {
    flex: 0 0 auto; position: relative; white-space: nowrap;
    padding: 12px 14px; font-size: 13px; color: #191919;
    text-decoration: none; border-bottom: 3px solid transparent;
    transition: color .15s, border-color .15s; cursor: pointer;
    background: none; border-left: none; border-right: none; border-top: none;
    display: block;
}
.eb-cats__tab:hover, .eb-cats__tab.active { color: #3665f3; border-bottom-color: #3665f3; }

/* Megamenu */
.eb-mega {
    display: none; position: absolute; top: 100%; left: 0; z-index: 998;
    background: #fff; border: 1px solid #e5e5e5; border-top: none;
    box-shadow: 0 8px 28px rgba(0,0,0,.14);
    min-width: 700px; min-height: 280px;
    border-radius: 0 0 10px 10px; padding: 24px 28px; gap: 24px;
    display: none;
}
.eb-cats__tab-wrap { position: static; }
.eb-cats__tab-wrap:hover .eb-mega { display: flex; }
.eb-mega__col { flex: 0 0 auto; min-width: 180px; }
.eb-mega__col-title {
    font-size: 12px; font-weight: 700; text-transform: uppercase;
    letter-spacing: .06em; color: #767676; margin-bottom: 12px;
    padding-bottom: 8px; border-bottom: 1px solid #e5e5e5;
}
.eb-mega__col a {
    display: block; font-size: 13.5px; color: #333; text-decoration: none;
    padding: 5px 0; transition: color .1s;
}
.eb-mega__col a:hover { color: #3665f3; }
.eb-mega__banner {
    flex: 1; border-radius: 10px; overflow: hidden; position: relative;
    background: linear-gradient(135deg, #e8f0fe, #dbeafe);
    min-height: 200px; display: flex; align-items: flex-end; justify-content: center;
}
.eb-mega__banner-img {
    position: absolute; inset: 0; width: 100%; height: 100%;
    object-fit: cover; object-position: center; display: block;
}
.eb-mega__banner-overlay {
    position: absolute; inset: 0;
    background: linear-gradient(to top, rgba(0,0,0,.68) 0%, rgba(0,0,0,.18) 55%, transparent 100%);
}
.eb-mega__banner-inner {
    position: relative; z-index: 1; text-align: center; padding: 22px 20px; width: 100%;
}
.eb-mega__banner-cat {
    font-size: 20px; font-weight: 800; color: #1e40af; margin-bottom: 12px; line-height: 1.25;
}
.eb-mega__banner.has-image .eb-mega__banner-cat {
    color: #fff; text-shadow: 0 1px 6px rgba(0,0,0,.55);
}
.eb-mega__banner-btn {
    display: inline-flex; align-items: center; gap: 6px;
    background: #3665f3; color: #fff; font-size: 13px; font-weight: 700;
    padding: 9px 20px; border-radius: 20px; text-decoration: none;
    transition: background .15s, transform .15s;
}
.eb-mega__banner-btn:hover { background: #1e4fc4; color: #fff; transform: translateY(-1px); }
.eb-mega__banner.has-image .eb-mega__banner-btn {
    background: rgba(255,255,255,.92); color: #1e40af;
}
.eb-mega__banner.has-image .eb-mega__banner-btn:hover {
    background: #fff; color: #1e40af;
}

/* ---- The wrapper needs relative so megamenu positions correctly ---- */
.eb-cats { position: relative; }
.eb-cats__inner { position: static; }

/* ---- MOBILE HEADER ---- */
@media (max-width: 991px) {
    .eb-mid__icons .eb-icon-group { display: none; }
    .eb-shopby { display: none; }
    .eb-search__select { display: none; }
    .eb-top__left .eb-top__sep ~ * { display: none; }
}

@media (max-width: 767px) {
    /* Hide entire top info bar on mobile */
    .eb-top { display: none; }

    /* Middle bar: switch from table to flex, two rows */
    .eb-mid { padding: 8px 0 0; }
    .eb-mid > .container { padding: 0 12px; }

    table.eb-midbar-table,
    .eb-mid .container > table {
        display: block !important;
        width: 100% !important;
        height: auto !important;
    }
    .eb-mid .container > table tr {
        display: flex !important;
        flex-wrap: wrap !important;
        align-items: center !important;
        gap: 0 !important;
    }
    /* Logo cell */
    .eb-mid .container > table tr td:nth-child(1) {
        display: flex !important;
        align-items: center;
        flex: 0 0 auto;
        padding: 0 !important;
    }
    /* Shop-by cell — hide on mobile */
    .eb-mid .container > table tr td:nth-child(2) {
        display: none !important;
    }
    /* Search cell — full width, second row */
    .eb-mid .container > table tr td:nth-child(3) {
        display: flex !important;
        flex: 0 0 100% !important;
        width: 100% !important;
        order: 10;
        padding: 8px 0 8px !important;
    }
    /* Icons cell (cart) — push to right of logo */
    .eb-mid .container > table tr td:nth-child(4) {
        display: flex !important;
        flex: 1 !important;
        justify-content: flex-end;
        padding: 0 !important;
    }

    /* Shrink logo text on mobile */
    .eb-logo-text { font-size: 22px; }
    .eb-logo img  { height: 28px; }

    /* Shrink search bar */
    .eb-search__form { height: 38px; border-width: 1.5px; }
    .eb-search__btn  { padding: 0 14px; font-size: 13px; }
    .eb-search__input { font-size: 13px; padding: 0 12px; }

    /* Cart icon size */
    .eb-cart-btn { font-size: 18px; }

    /* Category tabs */
    .eb-cats__tab { padding: 9px 10px; font-size: 12px; }
}
</style>

<div class="eb-header">

    {{-- ========== TOP INFO BAR ========== --}}
    <div class="eb-top">
        <div class="container">
            <div class="eb-top__inner">
                <div class="eb-top__left">
                    @auth
                        <span class="eb-top__hi">Hi, {{ auth()->user()->name }}!</span>
                    @else
                        <span class="eb-top__hi">Hi!</span>
                        <a href="#loginModal" data-toggle="modal">Sign in</a>
                        <span class="eb-top__sep">or</span>
                        <a href="#loginModal" data-toggle="modal" data-tab="register">register</a>
                    @endauth
                    <span class="eb-top__sep">|</span>
                    <a href="#">Deals</a>
                    <a href="#">Brand Outlet</a>
                    <a href="#">Help &amp; Contact</a>
                </div>
                <div class="eb-top__right">
                    @auth
                        @if (auth()->user()->hasRole('vendor'))
                            <a href="{{ route('vendor.dashboard') }}">My Store</a>
                        @else
                            <a href="{{ route('vendor.apply') }}">Sell</a>
                        @endif
                        <div class="eb-top__dropdown">
                            <button class="eb-top__dropdown-btn">
                                My Account <i class="fas fa-chevron-down" style="font-size:10px"></i>
                            </button>
                            <div class="eb-top__dropdown-menu">
                                <a href="{{ route('account.orders') }}">My Purchases</a>
                                <a href="{{ route('wishlist.index') }}">Watchlist</a>
                                <a href="#">Saved Searches</a>
                                <a href="#" onclick="event.preventDefault();document.getElementById('top-logout').submit();">
                                    Sign Out
                                </a>
                            </div>
                        </div>
                        <form id="top-logout" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                    @else
                        <a href="{{ route('vendor.apply') }}">Sell</a>
                        <a href="{{ route('wishlist.index') }}" class="eb-top__dropdown-btn">Watchlist</a>
                        <div class="eb-top__dropdown">
                            <button class="eb-top__dropdown-btn">
                                My Account <i class="fas fa-chevron-down" style="font-size:10px"></i>
                            </button>
                            <div class="eb-top__dropdown-menu">
                                <a href="#loginModal" data-toggle="modal">Sign in</a>
                                <a href="#loginModal" data-toggle="modal" data-tab="register">Register</a>
                            </div>
                        </div>
                    @endauth
                    <a href="#" title="Notifications"><i class="fas fa-bell" style="font-size:15px;color:#555"></i></a>
                    {{-- Wishlist / Watchlist (heart) with live count --}}
                    <a href="{{ route('wishlist.index') }}" title="Wishlist" class="eb-wish" id="eb-wish-link">
                        <i class="fas fa-heart"></i>
                        @php $wishCount = wishlist_count(); @endphp
                        <span class="eb-wish__badge {{ $wishCount ? '' : 'is-zero' }}" id="eb-wish-count">{{ $wishCount }}</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- ========== MIDDLE BAR ========== --}}
    <div class="eb-mid">
        <div class="container" style="position:relative;">
            <table style="width:100%;height:68px;border-collapse:collapse;table-layout:auto;border:0;padding:0;margin:0;">
              <tr>

                {{-- Logo --}}
                <td style="vertical-align:middle;white-space:nowrap;padding:0 12px 0 0;border:0;">
                    <a href="{{ route('home') }}" class="eb-logo">
                        @if (setting('logo'))
                            <img src="{{ asset('storage/'.setting('logo')) }}" alt="{{ setting('site_name', 'Marketplace') }}">
                        @else
                            <span class="eb-logo-text"><span>M</span><span>a</span><span>r</span><span>k</span>et</span>
                        @endif
                    </a>
                </td>

                {{-- Shop by category --}}
                <td style="vertical-align:middle;white-space:nowrap;padding:0 12px 0 0;border:0;">
                    <div class="eb-shopby">
                        <button class="eb-shopby__btn">
                            <i class="fas fa-th-large" style="font-size:13px"></i>
                            Shop by category
                            <i class="fas fa-chevron-down" style="font-size:10px"></i>
                        </button>
                        <div class="eb-shopby__mega">
                            <div class="eb-shopby__grid">
                                @foreach ($navCats as $cat)
                                    <div class="eb-shopby__group">
                                        <a href="{{ route('category.show', $cat->slug) }}" class="eb-shopby__group-title">
                                            <i class="fas {{ $cat->icon ?? 'fa-tag' }}"></i>
                                            {{ $cat->name }}
                                        </a>
                                        @if ($cat->children->count())
                                            <ul class="eb-shopby__sub">
                                                @foreach ($cat->children->take(5) as $child)
                                                    <li><a href="{{ route('category.show', $child->slug) }}">{{ $child->name }}</a></li>
                                                @endforeach
                                                @if ($cat->children->count() > 5)
                                                    <li><a href="{{ route('category.show', $cat->slug) }}" style="color:#3665f3;font-weight:600;">See all in {{ $cat->name }} ›</a></li>
                                                @endif
                                            </ul>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            {{-- <div class="eb-shopby__footer">
                                <a href="#">All Brands ›</a>
                                <a href="#">All Categories ›</a>
                                <a href="#">Seasonal Sales &amp; Events ›</a>
                            </div> --}}
                        </div>
                    </div>
                </td>

                {{-- Search (takes all remaining width) --}}
                <td style="vertical-align:middle;width:100%;padding:0 12px 0 0;border:0;">
                    <div class="eb-search">
                        <form class="eb-search__form" id="eb-search-form"
                              action="{{ route('search') }}" method="GET" style="margin:0;"
                              autocomplete="off">
                            <input class="eb-search__input" type="search" name="q" id="eb-search-input"
                                   value="{{ request('q') }}"
                                   placeholder="Search for anything" autocomplete="off">
                            <select class="eb-search__select" name="cat" id="eb-search-cat">
                                <option value="">All Categories</option>
                                @foreach ($navCats as $cat)
                                    <option value="{{ $cat->id }}" {{ request('cat') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                            <button class="eb-search__btn" type="submit">Search</button>
                        </form>
                        <div class="eb-search__suggest" id="eb-suggest"></div>
                    </div>
                </td>

                {{-- Right icons --}}
                <td style="vertical-align:middle;white-space:nowrap;padding:0;border:0;">
                    <div class="eb-mid__icons">
                        @auth
                            <a href="{{ auth()->user()->hasRole('vendor') ? route('vendor.dashboard') : route('account.dashboard') }}" class="eb-icon-group">
                                <span class="eb-icon-group__top">Welcome,</span>
                                <span class="eb-icon-group__main">{{ Str::words(auth()->user()->name, 1, '') }}</span>
                            </a>
                        @else
                            <a href="#loginModal" data-toggle="modal" class="eb-icon-group">
                                <span class="eb-icon-group__top">My</span>
                                <span class="eb-icon-group__main">Account</span>
                            </a>
                        @endauth
                        <a href="{{ route('cart.index') }}" class="eb-cart-btn" title="Cart" style="text-decoration:none;">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="eb-cart-badge">{{ $cartCount ?? array_sum(array_column(session('cart',[]),'quantity')) }}</span>
                        </a>
                    </div>
                </td>

              </tr>
            </table>
        </div>
    </div>

    {{-- ========== CATEGORY TABS BAR ========== --}}
    <div class="eb-cats">
        <div class="container" style="position:relative;">
            <div class="eb-cats__inner">
                @foreach ($navCats as $cat)
                    <div class="eb-cats__tab-wrap" style="position:static;">
                        <a href="{{ route('category.show', $cat->slug) }}"
                           class="eb-cats__tab {{ request()->routeIs('category.show') && request()->route('category')?->id == $cat->id ? 'active' : '' }}">
                            {{ $cat->name }}
                        </a>
                        @if ($cat->children->count())
                            <div class="eb-mega">
                                {{-- Top categories column --}}
                                <div class="eb-mega__col">
                                    <div class="eb-mega__col-title">Top categories</div>
                                    @foreach ($cat->children->where('show_on_home', true) as $child)
                                        <a href="{{ route('category.show', $child->slug) }}">{{ $child->name }}</a>
                                    @endforeach
                                </div>

                                {{-- Additional categories column --}}
                                @if ($cat->children->where('show_on_home', false)->count())
                                    <div class="eb-mega__col">
                                        <div class="eb-mega__col-title">More categories</div>
                                        @foreach ($cat->children->where('show_on_home', false) as $child)
                                            <a href="{{ route('category.show', $child->slug) }}">{{ $child->name }}</a>
                                        @endforeach
                                    </div>
                                @endif

                                {{-- Banner panel --}}
                                <div class="eb-mega__banner {{ $cat->image ? 'has-image' : '' }}">
                                    @if($cat->image)
                                        <img src="{{ asset($cat->image) }}" alt="{{ $cat->name }}" class="eb-mega__banner-img">
                                        <div class="eb-mega__banner-overlay"></div>
                                    @endif
                                    <div class="eb-mega__banner-inner">
                                        <div class="eb-mega__banner-cat">{{ $cat->name }}</div>
                                        <a href="{{ route('category.show', $cat->slug) }}" class="eb-mega__banner-btn">
                                            Shop now <i class="fas fa-arrow-right" style="font-size:11px"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>

</div>{{-- /eb-header --}}

<script>
(function () {
    var input   = document.getElementById('eb-search-input');
    var suggest = document.getElementById('eb-suggest');
    var form    = document.getElementById('eb-search-form');
    if (!input || !suggest) return;

    var timer = null;
    var AUTOCOMPLETE_URL = '{{ route('search.autocomplete') }}';

    function escHtml(s) {
        return String(s || '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    function showSuggest(data) {
        var html = '';

        if (data.products && data.products.length) {
            html += '<div class="eb-suggest-section"><div class="eb-suggest-label">Products</div>';
            data.products.forEach(function(p) {
                var img = p.image
                    ? '<img src="' + escHtml(p.image) + '" alt="" class="eb-suggest-product__img">'
                    : '<div class="eb-suggest-product__img-ph"><i class="fas fa-image"></i></div>';
                html += '<a href="' + escHtml(p.url) + '" class="eb-suggest-product">'
                      + img
                      + '<span class="eb-suggest-product__name">' + escHtml(p.name) + '</span>'
                      + '<span class="eb-suggest-product__price">' + escHtml(p.price) + '</span>'
                      + '</a>';
            });
            html += '</div>';
        }

        if (data.categories && data.categories.length) {
            html += '<div class="eb-suggest-section"><div class="eb-suggest-label">Categories</div>';
            data.categories.forEach(function(c) {
                html += '<a href="' + escHtml(c.url) + '" class="eb-suggest-cat">'
                      + '<i class="fas fa-th-large"></i>' + escHtml(c.name)
                      + '</a>';
            });
            html += '</div>';
        }

        if (html) {
            var q = input.value.trim();
            html += '<a href="{{ route('search') }}?q=' + encodeURIComponent(q) + '" class="eb-suggest-all">'
                  + '<i class="fas fa-search" style="margin-right:6px"></i>See all results for "<strong>' + escHtml(q) + '</strong>"'
                  + '</a>';
            suggest.innerHTML = html;
            suggest.classList.add('show');
            form.classList.add('has-suggestions');
        } else {
            closeSuggest();
        }
    }

    function closeSuggest() {
        suggest.classList.remove('show');
        form.classList.remove('has-suggestions');
    }

    input.addEventListener('input', function() {
        clearTimeout(timer);
        var q = this.value.trim();
        if (q.length < 2) { closeSuggest(); return; }
        timer = setTimeout(function() {
            fetch(AUTOCOMPLETE_URL + '?q=' + encodeURIComponent(q))
                .then(function(r) { return r.json(); })
                .then(showSuggest)
                .catch(function() { closeSuggest(); });
        }, 220);
    });

    input.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeSuggest();
    });

    document.addEventListener('click', function(e) {
        if (!e.target.closest('.eb-search')) closeSuggest();
    });

    input.addEventListener('focus', function() {
        if (this.value.trim().length >= 2 && suggest.innerHTML) {
            suggest.classList.add('show');
        }
    });
})();
</script>
