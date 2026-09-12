{{-- Admin customer detail --}}
@extends('admin.layouts.app')
@section('title', 'Customer: '.$customer->name)
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.customers.index') }}">Customers</a></li>
    <li class="breadcrumb-item active">{{ $customer->name }}</li>
@endsection

@push('styles')<link rel="stylesheet" href="{{ asset('admin-assets/css/admin-customers.css') }}">@endpush

@section('content')
@php
    $cur = setting('currency_symbol', '$');
    $initial = mb_strtoupper(mb_substr($customer->name ?? 'U', 0, 1));
    $blocked = $customer->status === 'blocked';
@endphp

{{-- Hero --}}
<div class="cud-hero">
    <div class="cud-hero__avatar">
        @if ($customer->avatar) <img src="{{ asset('storage/'.$customer->avatar) }}" alt=""> @else {{ $initial }} @endif
    </div>
    <div class="cud-hero__info">
        <h1 class="cud-hero__name">{{ $customer->name }}</h1>
        <div class="cud-hero__meta"><i class="bx bx-envelope"></i> {{ $customer->email }}
            @if ($customer->phone) · <i class="bx bx-phone"></i> {{ $customer->phone }} @endif
            · <i class="bx bx-calendar"></i> Joined {{ $customer->created_at->format('M Y') }}</div>
    </div>
    <div class="cud-hero__status">
        <span class="cu-badge cu-badge--{{ $blocked ? 'blocked' : 'active' }}">{{ $blocked ? 'Blocked' : 'Active' }}</span>
    </div>
</div>

<div class="cud-grid">
    {{-- LEFT --}}
    <div>
        {{-- Metrics --}}
        <div class="cud-stats">
            <div class="cud-stat cud-stat--ord"><div class="cud-stat__num">{{ $metrics['orders'] }}</div><div class="cud-stat__label"><i class="bx bx-cart"></i> Total Orders</div></div>
            <div class="cud-stat cud-stat--spent"><div class="cud-stat__num">{{ $cur }}{{ number_format($metrics['spent'], 2) }}</div><div class="cud-stat__label"><i class="bx bx-dollar-circle"></i> Total Spent</div></div>
            <div class="cud-stat cud-stat--wish"><div class="cud-stat__num">{{ $metrics['wishlist'] }}</div><div class="cud-stat__label"><i class="bx bx-heart"></i> Wishlist Items</div></div>
        </div>

        {{-- Saved address / details --}}
        <div class="cud-card">
            <div class="cud-card__head"><i class="bx bx-user"></i> Profile</div>
            <div class="cud-card__body">
                <div class="cud-row"><div class="cud-row__k">Email</div><div>{{ $customer->email }}</div></div>
                <div class="cud-row"><div class="cud-row__k">Phone</div><div>{{ $customer->phone ?: '—' }}</div></div>
                <div class="cud-row"><div class="cud-row__k">Address</div><div>{{ $customer->address_line ? trim($customer->address_line.', '.$customer->city.' '.$customer->state.' '.$customer->postal_code.', '.$customer->country, ', ') : '—' }}</div></div>
                <div class="cud-row"><div class="cud-row__k">Joined</div><div>{{ $customer->created_at->format('d M Y, g:i A') }}</div></div>
            </div>
        </div>

        {{-- Recent orders --}}
        <div class="cud-card">
            <div class="cud-card__head"><i class="bx bx-receipt"></i> Recent Orders</div>
            <div class="cud-card__body">
                @if ($orders->count())
                    <table class="cud-otable">
                        <thead><tr><th>Order #</th><th>Date</th><th>Items</th><th>Total</th><th>Payment</th><th>Status</th><th></th></tr></thead>
                        <tbody>
                            @foreach ($orders as $o)
                                <tr>
                                    <td class="fw-bold">{{ $o->order_number }}</td>
                                    <td>{{ $o->created_at->format('d M Y') }}</td>
                                    <td>{{ $o->items_count }}</td>
                                    <td class="fw-bold">{{ $cur }}{{ number_format($o->grand_total, 2) }}</td>
                                    <td><span class="cu-badge cu-badge--{{ $o->payment_status === 'paid' ? 'active' : 'blocked' }}">{{ ucfirst($o->payment_status) }}</span></td>
                                    <td>{{ ucfirst($o->status) }}</td>
                                    <td><a href="{{ route('admin.orders.show', $o) }}" class="cu-act cu-act--view" title="View"><i class="bx bx-show"></i></a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="text-center text-muted py-3">No orders yet.</div>
                @endif
            </div>
        </div>
    </div>

    {{-- RIGHT --}}
    <div>
        <div class="cud-card">
            <div class="cud-card__head"><i class="bx bx-cog"></i> Actions</div>
            <div class="cud-card__body">
                <form action="{{ route('admin.customers.toggle', $customer) }}" method="POST"
                      onsubmit="return confirm('{{ $blocked ? 'Unblock' : 'Block' }} this customer?')">
                    @csrf @method('PUT')
                    @if ($blocked)
                        <button class="cud-btn cud-btn--ok"><i class="bx bx-check"></i> Unblock Customer</button>
                    @else
                        <button class="cud-btn cud-btn--block"><i class="bx bx-block"></i> Block Customer</button>
                    @endif
                </form>
                <a href="{{ route('admin.customers.index') }}" class="cud-btn cud-btn--light">Back to list</a>
            </div>
        </div>
    </div>
</div>
@endsection
