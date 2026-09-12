{{-- Admin vendor detail — rich, professional --}}
@extends('admin.layouts.app')
@section('title', 'Vendor: '.$vendor->store_name)
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.vendors.index') }}">Vendors</a></li>
    <li class="breadcrumb-item active">{{ $vendor->store_name }}</li>
@endsection

@push('styles')<link rel="stylesheet" href="{{ asset('admin-assets/css/admin-vendors.css') }}">@endpush

@section('content')
@php
    $cur = setting('currency_symbol', '$');
    $initial = mb_strtoupper(mb_substr($vendor->store_name ?? 'S', 0, 1));
@endphp

{{-- Hero --}}
<div class="avd-hero">
    <div class="avd-hero__logo">
        @if ($vendor->logo) <img src="{{ asset('storage/'.$vendor->logo) }}" alt=""> @else {{ $initial }} @endif
    </div>
    <div class="avd-hero__info">
        <h1 class="avd-hero__name">{{ $vendor->store_name }}</h1>
        <div class="avd-hero__owner"><i class="bx bx-user"></i> {{ $vendor->user?->name }} · <i class="bx bx-envelope"></i> {{ $vendor->user?->email }}</div>
    </div>
    <div class="avd-hero__status">
        <span class="av-badge av-badge--{{ $vendor->status }}">{{ ucfirst($vendor->status) }}</span>
    </div>
</div>

<div class="avd-grid">
    {{-- LEFT --}}
    <div>
        {{-- Vendor stat cards --}}
        <div class="avd-stats">
            <div class="avd-stat avd-stat--prod">
                <div class="avd-stat__num">{{ $productCount }}</div>
                <div class="avd-stat__label"><i class="bx bx-package"></i> Products</div>
            </div>
            <div class="avd-stat avd-stat--ord">
                <div class="avd-stat__num">{{ $ordersCount }}</div>
                <div class="avd-stat__label"><i class="bx bx-cart"></i> Total Orders</div>
            </div>
            <div class="avd-stat avd-stat--earn">
                <div class="avd-stat__num">{{ $cur }}{{ number_format($earnings, 2) }}</div>
                <div class="avd-stat__label"><i class="bx bx-dollar-circle"></i> Earnings (paid)</div>
            </div>
        </div>

        {{-- Store details --}}
        <div class="avd-card">
            <div class="avd-card__head"><i class="bx bx-store-alt"></i> Store Details</div>
            <div class="avd-card__body">
                <div class="avd-row"><div class="avd-row__k">Owner</div><div class="avd-row__v">{{ $vendor->user?->name }} <span class="text-muted">({{ $vendor->user?->email }})</span></div></div>
                <div class="avd-row"><div class="avd-row__k">Phone</div><div class="avd-row__v">{{ $vendor->phone ?: '—' }}</div></div>
                <div class="avd-row"><div class="avd-row__k">Address</div><div class="avd-row__v">{{ $vendor->address ?: '—' }}</div></div>
                <div class="avd-row"><div class="avd-row__k">About</div><div class="avd-row__v">{{ strip_tags($vendor->description) ?: '—' }}</div></div>
                <div class="avd-row"><div class="avd-row__k">Store URL</div><div class="avd-row__v"><code>/{{ $vendor->slug }}</code></div></div>
                <div class="avd-row"><div class="avd-row__k">Applied</div><div class="avd-row__v">{{ $vendor->created_at->format('d M Y, g:i A') }}</div></div>
            </div>
        </div>

        {{-- Recent products --}}
        <div class="avd-card">
            <div class="avd-card__head"><i class="bx bx-box"></i> Recent Products</div>
            <div class="avd-card__body">
                @forelse ($recentProducts as $p)
                    @php
                        $img = $p->primaryImage;
                        $src = $img ? (str_starts_with($img->path, 'frontend-assets/') ? asset($img->path) : asset('storage/'.$img->path)) : null;
                    @endphp
                    <div class="avd-prod">
                        @if ($src)<img src="{{ $src }}" class="avd-prod__img" alt="">@else<div class="avd-prod__img-ph"><i class="bx bx-image"></i></div>@endif
                        <div>
                            <div class="avd-prod__name">{{ \Illuminate\Support\Str::limit($p->name, 46) }}</div>
                            <span class="av-badge av-badge--{{ $p->status === 'approved' ? 'approved' : ($p->status === 'rejected' ? 'rejected' : 'pending') }}">{{ ucfirst($p->status) }}</span>
                        </div>
                        <div class="avd-prod__price">{{ $cur }}{{ number_format($p->effective_price, 2) }}</div>
                    </div>
                @empty
                    <div class="text-center text-muted py-3">This store has no products yet.</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- RIGHT: Actions --}}
    <div>
        <div class="avd-card">
            <div class="avd-card__head"><i class="bx bx-cog"></i> Actions</div>
            <div class="avd-card__body">
                @if ($vendor->status !== 'approved')
                    <form action="{{ route('admin.vendors.approve', $vendor) }}" method="POST">
                        @csrf @method('PUT')
                        <button class="avd-btn avd-btn--approve"><i class="bx bx-check"></i> Approve Store</button>
                    </form>
                @endif
                @if ($vendor->status !== 'rejected')
                    <form action="{{ route('admin.vendors.reject', $vendor) }}" method="POST" onsubmit="return confirm('Reject this store?')">
                        @csrf @method('PUT')
                        <button class="avd-btn avd-btn--reject"><i class="bx bx-x"></i> Reject Store</button>
                    </form>
                @endif
                <a href="{{ route('admin.vendors.index') }}" class="avd-btn avd-btn--light">Back to list</a>
            </div>
        </div>

        {{-- Selling defaults --}}
        <div class="avd-card">
            <div class="avd-card__head"><i class="bx bx-purchase-tag"></i> Selling Defaults</div>
            <div class="avd-card__body">
                <div class="avd-row"><div class="avd-row__k">Commission</div><div class="avd-row__v">{{ !is_null($vendor->commission_rate) ? $vendor->commission_rate.'%' : 'Global' }}</div></div>
                <div class="avd-row"><div class="avd-row__k">Def. Shipping</div><div class="avd-row__v">{{ $cur }}{{ number_format($vendor->default_shipping_cost ?? 0, 2) }}{{ $vendor->default_free_shipping ? ' (Free)' : '' }}</div></div>
            </div>
        </div>
    </div>
</div>
@endsection
