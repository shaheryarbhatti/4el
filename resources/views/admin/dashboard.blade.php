{{-- Admin dashboard — professional, rich, detailed. All styles external (admin-dashboard.css). --}}
@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('admin-assets/css/admin-dashboard.css') }}">
@endpush

@section('content')
@php
    $cur  = setting('currency_symbol', '$');
    $fmtK = fn ($v) => $v >= 1000 ? number_format($v / 1000, $v >= 10000 ? 0 : 1) . 'k' : number_format($v);
@endphp

<div class="dbx-wrap">

    {{-- ═══════════════ HERO ═══════════════ --}}
    <div class="dbx-hero">
        <div class="dbx-hero__icon"><i class="bx bx-bar-chart-alt-2"></i></div>
        <div class="dbx-hero__text">
            <h2 class="dbx-hero__title">Admin Dashboard</h2>
            <p class="dbx-hero__sub">Marketplace overview — revenue, orders, vendors &amp; activity at a glance</p>
        </div>
        <div class="dbx-hero__pills">
            <span class="dbx-pill"><i class="bx bx-calendar"></i> {{ now()->format('M j, Y') }}</span>
            <span class="dbx-pill"><i class="bx bx-dollar-circle"></i> <b>{{ $cur }}{{ number_format($revenueTotal, 0) }}</b> revenue</span>
            <span class="dbx-pill dbx-pill--live"><span class="dbx-dot"></span> Live</span>
        </div>
    </div>

    {{-- ═══════════════ QUICK ACTIONS ═══════════════ --}}
    <div class="dbx-card">
        <div class="dbx-card__head">
            <h6 class="dbx-card__title"><i class="bx bx-grid-alt dbx-i-indigo"></i> Quick Actions</h6>
        </div>
        <div class="dbx-card__body">
            <div class="dbx-actions">
                <a href="{{ route('admin.orders.index') }}" class="dbx-action"><i class="bx bx-cart-alt dbx-i-blue"></i> Manage Orders</a>
                <a href="{{ route('admin.products.index') }}" class="dbx-action"><i class="bx bx-package dbx-i-green"></i> Products</a>
                <a href="{{ route('admin.vendors.index') }}" class="dbx-action"><i class="bx bx-store dbx-i-violet"></i> Vendors</a>
                <a href="{{ route('admin.home-sections.index') }}" class="dbx-action"><i class="bx bx-layout dbx-i-indigo"></i> Home Sections</a>
                <a href="{{ route('admin.settings.index', 'payment') }}" class="dbx-action"><i class="bx bx-credit-card dbx-i-amber"></i> Payment Gateways</a>
                <a href="{{ route('home') }}" target="_blank" class="dbx-action"><i class="bx bx-link-external dbx-i-red"></i> View Storefront</a>
            </div>
        </div>
    </div>

    {{-- ═══════════════ PRIMARY KPI CARDS ═══════════════ --}}
    <div class="row g-3">
        <div class="col-xl-3 col-md-6">
            <div class="dbx-kpi dbx-accent-indigo">
                <div class="dbx-kpi__top">
                    <span class="dbx-kpi__label">Total Revenue</span>
                    <span class="dbx-kpi__icon"><i class="bx bx-dollar-circle"></i></span>
                </div>
                <div class="dbx-kpi__value">{{ $cur }}{{ number_format($revenueTotal, 0) }}</div>
                <div class="dbx-kpi__foot">
                    <span class="dbx-up"><i class="bx bx-trending-up"></i> {{ $cur }}{{ number_format($revenueMonth, 0) }}</span> this month
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="dbx-kpi dbx-accent-blue">
                <div class="dbx-kpi__top">
                    <span class="dbx-kpi__label">Total Orders</span>
                    <span class="dbx-kpi__icon"><i class="bx bx-cart-alt"></i></span>
                </div>
                <div class="dbx-kpi__value">{{ number_format($ordersTotal) }}</div>
                <div class="dbx-kpi__foot">
                    <i class="bx bx-calendar"></i> {{ number_format($ordersMonth) }} this month · {{ number_format($ordersPaid) }} paid
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="dbx-kpi dbx-accent-green">
                <div class="dbx-kpi__top">
                    <span class="dbx-kpi__label">Active Products</span>
                    <span class="dbx-kpi__icon"><i class="bx bx-shopping-bag"></i></span>
                </div>
                <div class="dbx-kpi__value">{{ number_format($productsActive) }}</div>
                <div class="dbx-kpi__foot">
                    <i class="bx bx-box"></i> of {{ number_format($productsTotal) }} total listings
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="dbx-kpi dbx-accent-pink">
                <div class="dbx-kpi__top">
                    <span class="dbx-kpi__label">Customers</span>
                    <span class="dbx-kpi__icon"><i class="bx bx-group"></i></span>
                </div>
                <div class="dbx-kpi__value">{{ number_format($customersTotal) }}</div>
                <div class="dbx-kpi__foot">
                    <span class="dbx-up"><i class="bx bx-user-plus"></i> {{ number_format($newCustomers30d) }}</span> new in 30 days
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════ MINI STAT STRIP ═══════════════ --}}
    <div class="row g-3">
        <div class="col-xl-2 col-md-4 col-6">
            <div class="dbx-mini dbx-accent-amber">
                <div class="dbx-mini__icon"><i class="bx bx-sun"></i></div>
                <div class="dbx-mini__body">
                    <div class="dbx-mini__value">{{ $cur }}{{ number_format($revenueToday, 0) }}</div>
                    <div class="dbx-mini__label">Today's Revenue</div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6">
            <div class="dbx-mini dbx-accent-red">
                <div class="dbx-mini__icon"><i class="bx bx-time-five"></i></div>
                <div class="dbx-mini__body">
                    <div class="dbx-mini__value">{{ number_format($ordersPending) }}</div>
                    <div class="dbx-mini__label">Pending Orders</div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6">
            <div class="dbx-mini dbx-accent-indigo">
                <div class="dbx-mini__icon"><i class="bx bx-receipt"></i></div>
                <div class="dbx-mini__body">
                    <div class="dbx-mini__value">{{ $cur }}{{ number_format($avgOrderValue, 0) }}</div>
                    <div class="dbx-mini__label">Avg Order Value</div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6">
            <div class="dbx-mini dbx-accent-violet">
                <div class="dbx-mini__icon"><i class="bx bx-user-check"></i></div>
                <div class="dbx-mini__body">
                    <div class="dbx-mini__value">{{ number_format($vendorsPending) }}</div>
                    <div class="dbx-mini__label">Pending Vendors</div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6">
            <div class="dbx-mini dbx-accent-amber">
                <div class="dbx-mini__icon"><i class="bx bx-error-circle"></i></div>
                <div class="dbx-mini__body">
                    <div class="dbx-mini__value">{{ number_format($lowStock) }}</div>
                    <div class="dbx-mini__label">Low Stock (&le;5)</div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6">
            <div class="dbx-mini dbx-accent-cyan">
                <div class="dbx-mini__icon"><i class="bx bx-gavel"></i></div>
                <div class="dbx-mini__body">
                    <div class="dbx-mini__value">{{ number_format($auctionsLive) }}</div>
                    <div class="dbx-mini__label">Live Auctions</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════ CHARTS ROW ═══════════════ --}}
    <div class="row g-3">

        {{-- Monthly revenue — SVG bar chart --}}
        <div class="col-xl-8">
            <div class="dbx-card">
                <div class="dbx-card__head">
                    <h6 class="dbx-card__title"><i class="bx bx-line-chart dbx-i-indigo"></i> Monthly Revenue</h6>
                    <span class="dbx-card__tag">Last 7 months</span>
                </div>
                <div class="dbx-card__body">
                    @php
                        $rev    = collect($monthlyRevenue);
                        $maxRev = (float) ($rev->max('revenue') ?: 0);
                        $sumRev = (float) $rev->sum('revenue');
                        $avgRev = (float) $rev->avg('revenue');
                        $n      = max(1, count($monthlyRevenue));

                        $W = 700; $H = 250; $pl = 46; $pr = 14; $pt = 22; $pb = 30;
                        $plotW = $W - $pl - $pr; $plotH = $H - $pt - $pb;
                        $slot  = $plotW / $n; $barW = min(48, $slot * 0.5);
                        $baseY = $pt + $plotH;
                    @endphp
                    <svg class="dbx-chart-svg" viewBox="0 0 {{ $W }} {{ $H }}" preserveAspectRatio="xMidYMid meet" role="img" aria-label="Monthly revenue bar chart">
                        <defs>
                            <linearGradient id="dbxBarGrad" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0%" stop-color="#a855f7"/>
                                <stop offset="55%" stop-color="#7c3aed"/>
                                <stop offset="100%" stop-color="#4f46e5"/>
                            </linearGradient>
                        </defs>

                        {{-- gridlines + y labels --}}
                        @for ($g = 0; $g <= 4; $g++)
                            @php
                                $gy  = $pt + $plotH - ($g / 4) * $plotH;
                                $gv  = $maxRev * $g / 4;
                            @endphp
                            <line x1="{{ $pl }}" y1="{{ $gy }}" x2="{{ $W - $pr }}" y2="{{ $gy }}"
                                  stroke="#eef2f7" stroke-width="1"/>
                            <text x="{{ $pl - 8 }}" y="{{ $gy + 3 }}" text-anchor="end"
                                  font-size="10" fill="#94a3b8">{{ $cur }}{{ $fmtK($gv) }}</text>
                        @endfor

                        {{-- bars --}}
                        @foreach ($monthlyRevenue as $i => $m)
                            @php
                                $pct = $maxRev > 0 ? $m['revenue'] / $maxRev : 0;
                                $bh  = $pct * $plotH;
                                $bx  = $pl + $i * $slot + ($slot - $barW) / 2;
                                $by  = $baseY - $bh;
                            @endphp
                            @if ($bh > 0)
                                <rect x="{{ round($bx, 1) }}" y="{{ round($by, 1) }}"
                                      width="{{ round($barW, 1) }}" height="{{ round($bh, 1) }}"
                                      rx="6" fill="url(#dbxBarGrad)"/>
                                <text x="{{ round($bx + $barW / 2, 1) }}" y="{{ round($by - 7, 1) }}"
                                      text-anchor="middle" font-size="10" font-weight="700" fill="#475569">{{ $cur }}{{ $fmtK($m['revenue']) }}</text>
                            @endif
                            <text x="{{ round($bx + $barW / 2, 1) }}" y="{{ $baseY + 18 }}"
                                  text-anchor="middle" font-size="11" font-weight="600" fill="#94a3b8">{{ $m['label'] }}</text>
                        @endforeach

                        {{-- baseline --}}
                        <line x1="{{ $pl }}" y1="{{ $baseY }}" x2="{{ $W - $pr }}" y2="{{ $baseY }}" stroke="#e5eaf1" stroke-width="1.5"/>

                        @if ($maxRev <= 0)
                            <text x="{{ $W / 2 }}" y="{{ $pt + $plotH / 2 }}" text-anchor="middle"
                                  font-size="13" fill="#cbd5e1" font-weight="600">No paid revenue in this period yet</text>
                        @endif
                    </svg>

                    <div class="dbx-chart-foot">
                        <span class="dbx-chart-foot__item"><b>{{ $cur }}{{ number_format($sumRev, 0) }}</b> total (7 mo)</span>
                        <span class="dbx-chart-foot__item">Avg <b>{{ $cur }}{{ number_format($avgRev, 0) }}</b>/mo</span>
                        <span class="dbx-chart-foot__item">Peak <b>{{ $cur }}{{ number_format($maxRev, 0) }}</b></span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Orders by status — donut --}}
        <div class="col-xl-4">
            <div class="dbx-card">
                <div class="dbx-card__head">
                    <h6 class="dbx-card__title"><i class="bx bx-doughnut-chart dbx-i-amber"></i> Orders by Status</h6>
                    <span class="dbx-card__tag">All time</span>
                </div>
                <div class="dbx-card__body">
                    @php
                        $statusMeta = [
                            'pending'    => ['#f59e0b', 'Pending'],
                            'processing' => ['#3b82f6', 'Processing'],
                            'shipped'    => ['#06b6d4', 'Shipped'],
                            'delivered'  => ['#10b981', 'Delivered'],
                            'cancelled'  => ['#ef4444', 'Cancelled'],
                        ];
                        $statusTotal = array_sum($ordersByStatus) ?: 0;
                        $div = $statusTotal ?: 1;
                        $r = 54; $cx = 64; $cy = 64; $circ = 2 * M_PI * $r; $offset = 0;
                    @endphp
                    <div class="dbx-donut">
                        <svg width="128" height="128" viewBox="0 0 128 128">
                            <circle cx="{{ $cx }}" cy="{{ $cy }}" r="{{ $r }}" fill="none" stroke="#f1f5f9" stroke-width="18"/>
                            @foreach ($statusMeta as $key => [$color, $label])
                                @php
                                    $val  = $ordersByStatus[$key] ?? 0;
                                    $dash = ($val / $div) * $circ;
                                @endphp
                                @if ($val > 0)
                                    <circle cx="{{ $cx }}" cy="{{ $cy }}" r="{{ $r }}" fill="none"
                                            stroke="{{ $color }}" stroke-width="18"
                                            stroke-dasharray="{{ number_format($dash, 2) }} {{ number_format($circ - $dash, 2) }}"
                                            stroke-dashoffset="{{ number_format($circ - $offset, 2) }}"
                                            transform="rotate(-90 {{ $cx }} {{ $cy }})"/>
                                    @php $offset += $dash; @endphp
                                @endif
                            @endforeach
                            <text x="{{ $cx }}" y="{{ $cy - 4 }}" text-anchor="middle" font-size="22" font-weight="800" fill="#0f172a">{{ number_format($statusTotal) }}</text>
                            <text x="{{ $cx }}" y="{{ $cy + 13 }}" text-anchor="middle" font-size="9" font-weight="700" fill="#94a3b8" letter-spacing="1">ORDERS</text>
                        </svg>
                        <div class="dbx-legend">
                            @foreach ($statusMeta as $key => [$color, $label])
                                @php $val = $ordersByStatus[$key] ?? 0; $pctv = $statusTotal ? round($val / $statusTotal * 100) : 0; @endphp
                                <div class="dbx-legend__row">
                                    <span class="dbx-legend__dot dbx-st-{{ $key }}"></span>
                                    {{ $label }}
                                    <span class="dbx-legend__val">{{ number_format($val) }}</span>
                                    <span class="dbx-legend__pct">{{ $pctv }}%</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════ RECENT ORDERS + TOP PRODUCTS ═══════════════ --}}
    <div class="row g-3">

        <div class="col-xl-8">
            <div class="dbx-card">
                <div class="dbx-card__head">
                    <h6 class="dbx-card__title"><i class="bx bx-receipt dbx-i-indigo"></i> Recent Orders</h6>
                    @php $ordRoute = \Illuminate\Support\Facades\Route::has('admin.orders.index') ? route('admin.orders.index') : null; @endphp
                    @if ($ordRoute)<a href="{{ $ordRoute }}" class="dbx-card__link">View all <i class="bx bx-chevron-right"></i></a>@endif
                </div>
                <div class="dbx-tablewrap">
                    <table class="dbx-table">
                        <thead>
                            <tr><th>Order</th><th>Customer</th><th>Amount</th><th>Status</th><th>Payment</th><th>Date</th></tr>
                        </thead>
                        <tbody>
                            @forelse ($recentOrders as $order)
                                @php
                                    $cname = optional($order->user)->name ?? ($order->customer_name ?? 'Guest');
                                    $st = $order->status ?: 'pending';
                                    $ps = $order->payment_status ?: 'unpaid';
                                    $stMap = ['pending','processing','shipped','delivered','completed','cancelled'];
                                    $stCls = in_array($st, $stMap) ? 'dbx-b-'.$st : 'dbx-b-pending';
                                    $psCls = $ps==='paid' ? 'dbx-b-paid' : ($ps==='unpaid' ? 'dbx-b-unpaid' : 'dbx-b-cod');
                                @endphp
                                <tr>
                                    <td><span class="dbx-onum">{{ $order->order_number }}</span></td>
                                    <td>
                                        <span class="dbx-cust">
                                            <span class="dbx-avatar">{{ strtoupper(mb_substr($cname, 0, 1)) }}</span>
                                            <span class="dbx-cust__name">{{ $cname }}</span>
                                        </span>
                                    </td>
                                    <td><span class="dbx-amt">{{ $cur }}{{ number_format($order->grand_total, 2) }}</span></td>
                                    <td><span class="dbx-badge {{ $stCls }}"><span class="dbx-badge__dot"></span>{{ ucfirst($st) }}</span></td>
                                    <td><span class="dbx-badge {{ $psCls }}">{{ strtoupper($ps) }}</span></td>
                                    <td><span class="dbx-date">{{ $order->created_at->format('M j, Y') }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="6"><div class="dbx-empty"><i class="bx bx-package"></i><p>No orders yet</p></div></td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="dbx-card">
                <div class="dbx-card__head">
                    <h6 class="dbx-card__title"><i class="bx bx-trophy dbx-i-amber"></i> Top Selling Products</h6>
                    <span class="dbx-card__tag">by units</span>
                </div>
                <div class="dbx-card__body">
                    <div class="dbx-list">
                        @forelse ($topProducts as $idx => $product)
                            <div class="dbx-prod">
                                <span class="dbx-rank dbx-rank-{{ $idx + 1 }}">{{ $idx + 1 }}</span>
                                <span class="dbx-prod__img dbx-prod__ph"><i class="bx bx-box"></i></span>
                                <div class="dbx-prod__info">
                                    <div class="dbx-prod__name" title="{{ $product->product_name }}">{{ $product->product_name }}</div>
                                    <div class="dbx-prod__sub">{{ number_format($product->units_sold) }} units sold</div>
                                </div>
                                <span class="dbx-prod__rev">{{ $cur }}{{ number_format($product->revenue, 0) }}</span>
                            </div>
                        @empty
                            <div class="dbx-empty"><i class="bx bx-shopping-bag"></i><p>No sales data yet</p></div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════ EARNINGS SPLIT + TOP VENDORS ═══════════════ --}}
    <div class="row g-3">

        <div class="col-xl-6">
            <div class="dbx-card">
                <div class="dbx-card__head">
                    <h6 class="dbx-card__title"><i class="bx bx-wallet dbx-i-green"></i> Earnings Split</h6>
                    <span class="dbx-card__tag">paid orders</span>
                </div>
                <div class="dbx-card__body">
                    @php
                        $earnTotal = $vendorEarnings + $platformEarnings;
                        $vPct = $earnTotal > 0 ? $vendorEarnings / $earnTotal * 100 : 0;
                        $pPct = 100 - $vPct;
                    @endphp
                    <svg class="dbx-earn-svg" viewBox="0 0 100 14" preserveAspectRatio="none" role="img" aria-label="Earnings split bar">
                        <rect x="0" y="0" width="100" height="14" fill="#eef2f7"/>
                        @if ($earnTotal > 0)
                            <rect x="0" y="0" width="{{ round($vPct, 2) }}" height="14" fill="#10b981"/>
                            <rect x="{{ round($vPct, 2) }}" y="0" width="{{ round($pPct, 2) }}" height="14" fill="#4f46e5"/>
                        @endif
                    </svg>
                    <div class="dbx-earn">
                        <div class="dbx-earn__row">
                            <div class="dbx-earn__top">
                                <span class="dbx-earn__label"><span class="dbx-legend__dot dbx-dot-green"></span> Vendor Earnings</span>
                                <span class="dbx-earn__val">{{ $cur }}{{ number_format($vendorEarnings, 2) }}</span>
                            </div>
                        </div>
                        <div class="dbx-earn__row">
                            <div class="dbx-earn__top">
                                <span class="dbx-earn__label"><span class="dbx-legend__dot dbx-dot-indigo"></span> Platform Commission</span>
                                <span class="dbx-earn__val">{{ $cur }}{{ number_format($platformEarnings, 2) }}</span>
                            </div>
                        </div>
                        <div class="dbx-earn__total">
                            <span>Total processed ({{ number_format($ordersPaid) }} paid orders)</span>
                            <b>{{ $cur }}{{ number_format($earnTotal, 2) }}</b>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="dbx-card">
                <div class="dbx-card__head">
                    <h6 class="dbx-card__title"><i class="bx bx-store-alt dbx-i-violet"></i> Top Vendors</h6>
                    @php $venRoute = \Illuminate\Support\Facades\Route::has('admin.vendors.index') ? route('admin.vendors.index') : null; @endphp
                    @if ($venRoute)<a href="{{ $venRoute }}" class="dbx-card__link">View all <i class="bx bx-chevron-right"></i></a>@endif
                </div>
                <div class="dbx-card__body">
                    @forelse ($topVendors as $v)
                        <div class="dbx-vend">
                            <span class="dbx-vend__logo">{{ strtoupper(mb_substr($v->store_name ?? 'V', 0, 1)) }}</span>
                            <div class="dbx-vend__info">
                                <div class="dbx-vend__name">{{ $v->store_name }}</div>
                                <div class="dbx-vend__sub">{{ number_format($v->orders_count) }} paid orders</div>
                            </div>
                            <span class="dbx-vend__earn">{{ $cur }}{{ number_format($v->earnings, 0) }}</span>
                        </div>
                    @empty
                        <div class="dbx-empty"><i class="bx bx-store"></i><p>No vendor sales yet</p></div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
