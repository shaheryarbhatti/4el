{{--
    CUSTOMER ACCOUNT LAYOUT (frontend / Porto).
    Account pages use:
        @extends('frontend.account.layout')
        @section('account_content') ... @endsection
    Styles: external public/frontend-assets/css/account.css
--}}
@extends('frontend.layouts.app')

@push('styles')<link rel="stylesheet" href="{{ asset('frontend-assets/css/account.css') }}">@endpush

@section('content')
@php
    $u = auth()->user();
    $avatar  = $u->avatar ? asset('storage/'.$u->avatar) : null;
    $initial = strtoupper(substr($u->name ?? 'U', 0, 1));
@endphp

<div class="container ac-wrap">

    {{-- Hero --}}
    <div class="ac-hero">
        <div class="ac-hero__avatar">
            @if ($avatar) <img src="{{ $avatar }}" alt="avatar"> @else {{ $initial }} @endif
        </div>
        <div class="ac-hero__info">
            <h1 class="ac-hero__name">{{ $u->name }}</h1>
            <div class="ac-hero__meta">
                <span><i class="fas fa-envelope"></i> {{ $u->email }}</span>
                <span><i class="fas fa-calendar-alt"></i> Member since {{ $u->created_at->format('M Y') }}</span>
            </div>
        </div>
        <div class="ac-hero__cta">
            <a href="{{ route('vendor.apply') }}" class="ac-hero__btn"><i class="fas fa-store"></i> Start Selling</a>
        </div>
    </div>

    <div class="ac-grid">
        {{-- Sidebar --}}
        <aside class="ac-side">
            @php
                $nav = [
                    ['account.dashboard', 'Dashboard',     'fas fa-gauge-high'],
                    ['account.orders',    'My Orders',     'fas fa-box'],
                    ['wishlist.index',    'My Wishlist',   'fas fa-heart'],
                    ['account.profile',   'Profile',       'fas fa-user-cog'],
                ];
            @endphp
            <ul class="ac-nav">
                @foreach ($nav as [$route, $label, $icon])
                    <li>
                        <a href="{{ route($route) }}" class="{{ request()->routeIs($route) || request()->routeIs($route.'.*') ? 'is-active' : '' }}">
                            <i class="{{ $icon }}"></i> {{ $label }}
                        </a>
                    </li>
                @endforeach
                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="ac-logout"><i class="fas fa-sign-out-alt"></i> Logout</button>
                    </form>
                </li>
            </ul>
        </aside>

        {{-- Content --}}
        <div class="ac-content">
            @include('vendor.partials.alerts')
            @yield('account_content')
        </div>
    </div>
</div>
@endsection
