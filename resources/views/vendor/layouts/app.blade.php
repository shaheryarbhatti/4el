{{--
    VENDOR DASHBOARD LAYOUT (frontend / Porto).
    Wraps the storefront header+footer and adds a rich hero + account sidebar.
    All styling is in the external file: public/frontend-assets/css/vendor.css
    (loaded via @push('styles') — no inline styles).

    Vendor pages use:
        @extends('vendor.layouts.app')
        @section('vendor_content') ... @endsection
--}}
@extends('frontend.layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/vendor.css') }}">
@endpush

@section('content')
@php
    $profile = auth()->user()->vendorProfile;
    $logo    = $profile?->logo ? asset('storage/'.$profile->logo) : null;
    $initial = strtoupper(substr($profile->store_name ?? 'S', 0, 1));

    // Quick store stats for the hero
    $totalProducts    = auth()->user()->products()->count();
    $approvedProducts = auth()->user()->products()->where('status', 'approved')->count();
    $pendingProducts  = auth()->user()->products()->where('status', 'pending')->count();
@endphp

{{-- ===== HERO BANNER ===== --}}
<div class="vh-wrap">

    {{-- Background layers --}}
    <div class="vh-bg">
        <div class="vh-bg__dots"></div>
        <div class="vh-bg__orb vh-bg__orb--1"></div>
        <div class="vh-bg__orb vh-bg__orb--2"></div>
        <div class="vh-bg__orb vh-bg__orb--3"></div>
        <div class="vh-bg__line vh-bg__line--1"></div>
        <div class="vh-bg__line vh-bg__line--2"></div>
    </div>

    <div class="container">
        <div class="vh-inner">

            {{-- Left: Avatar + Store identity --}}
            <div class="vh-identity">
                {{-- Avatar with glowing ring --}}
                <div class="vh-avatar-ring">
                    <div class="vh-avatar-ring__glow"></div>
                    <div class="vh-avatar">
                        @if ($logo)
                            <img src="{{ $logo }}" alt="{{ $profile->store_name }}">
                        @else
                            {{ $initial }}
                        @endif
                    </div>
                    <span class="vh-avatar__verified" title="Approved Vendor">
                        <i class="fas fa-check"></i>
                    </span>
                </div>

                {{-- Store name + sub --}}
                <div class="vh-store">
                    <div class="vh-store__label">
                        <span class="vh-tag vh-tag--vendor"><i class="fas fa-store"></i> Seller</span>
                        <span class="vh-tag vh-tag--approved"><i class="fas fa-shield-alt"></i> Verified</span>
                    </div>
                    <h1 class="vh-store__name">{{ $profile->store_name ?? 'My Store' }}</h1>
                    <p class="vh-store__sub">
                        <i class="fas fa-user-circle"></i>
                        {{ auth()->user()->name }}
                        <span class="vh-divider">|</span>
                        <i class="fas fa-map-marker-alt"></i>
                        {{ $profile->address ?? 'Location not set' }}
                    </p>
                </div>
            </div>

            {{-- Center: Stats bar --}}
            <div class="vh-stats">
                <div class="vh-stat">
                    <div class="vh-stat__icon vh-stat__icon--total">
                        <i class="fas fa-boxes"></i>
                    </div>
                    <div class="vh-stat__body">
                        <span class="vh-stat__num">{{ $totalProducts }}</span>
                        <span class="vh-stat__lbl">Total Products</span>
                    </div>
                </div>
                <div class="vh-stat-sep"></div>
                <div class="vh-stat">
                    <div class="vh-stat__icon vh-stat__icon--approved">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="vh-stat__body">
                        <span class="vh-stat__num">{{ $approvedProducts }}</span>
                        <span class="vh-stat__lbl">Live / Approved</span>
                    </div>
                </div>
                <div class="vh-stat-sep"></div>
                <div class="vh-stat">
                    <div class="vh-stat__icon vh-stat__icon--pending">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="vh-stat__body">
                        <span class="vh-stat__num">{{ $pendingProducts }}</span>
                        <span class="vh-stat__lbl">Pending Review</span>
                    </div>
                </div>
            </div>

            {{-- Right: CTAs --}}
            <div class="vh-actions">
                <a href="{{ route('vendor.products.create') }}" class="vh-btn vh-btn--primary">
                    <i class="fas fa-plus-circle"></i>
                    <span>Add Product</span>
                </a>
                <a href="{{ route('vendor.shop.edit') }}" class="vh-btn vh-btn--ghost">
                    <i class="fas fa-cog"></i>
                    <span>Settings</span>
                </a>
            </div>

        </div>
    </div>
</div>

{{-- ===== MAIN CONTENT GRID ===== --}}
<div class="container py-4">
    <div class="v-grid">
        <aside class="v-side">
            <div class="v-side__store">
                <div class="v-side__avatar">
                    @if ($logo) <img src="{{ $logo }}" alt="store logo"> @else {{ $initial }} @endif
                </div>
                <div class="v-side__name">{{ $profile->store_name ?? 'My Store' }}</div>
                <div class="v-side__role">Vendor Account</div>
            </div>

            @php
                $nav = [
                    ['vendor.dashboard',      'Dashboard',     'fas fa-chart-line'],
                    ['vendor.products.index', 'My Products',   'fas fa-box'],
                    ['vendor.products.create','Add Product',   'fas fa-plus-circle'],
                    ['vendor.orders.index',   'Orders',        'fas fa-receipt'],
                    ['vendor.earnings.index', 'Earnings',      'fas fa-dollar-sign'],
                    ['vendor.shop.edit',      'Shop Settings', 'fas fa-store'],
                ];
            @endphp
            <ul class="v-nav">
                @foreach ($nav as [$route, $label, $icon])
                    <li>
                        <a href="{{ route($route) }}" class="{{ request()->routeIs($route) ? 'is-active' : '' }}">
                            <i class="{{ $icon }}"></i> {{ $label }}
                        </a>
                    </li>
                @endforeach
                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="v-nav__logout">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>
        </aside>

        <div class="v-content">
            @include('vendor.partials.alerts')
            @yield('vendor_content')
        </div>
    </div>
</div>

@endsection
