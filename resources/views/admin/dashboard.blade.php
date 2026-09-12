{{-- Admin dashboard — premium redesign --}}
@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
@endsection

@push('styles')
<style>
/* ═══════════════════════════════════════════════════════════════
   Dashboard — page-specific styles
═══════════════════════════════════════════════════════════════ */

/* ── Header banner ── */
.db-header {
    background: linear-gradient(135deg, #1e1b4b 0%, #312e81 45%, #4c1d95 100%);
    border-radius: 20px;
    padding: 32px 36px;
    margin-bottom: 28px;
    position: relative;
    overflow: hidden;
    display: flex;
    align-items: center;
    gap: 20px;
}
.db-header::before {
    content: '';
    position: absolute;
    top: -60px; right: -60px;
    width: 240px; height: 240px;
    border-radius: 50%;
    background: rgba(255,255,255,.04);
}
.db-header::after {
    content: '';
    position: absolute;
    bottom: -80px; right: 120px;
    width: 180px; height: 180px;
    border-radius: 50%;
    background: rgba(255,255,255,.03);
}
.db-header-icon {
    width: 64px; height: 64px;
    border-radius: 18px;
    background: rgba(255,255,255,.15);
    display: flex; align-items: center; justify-content: center;
    font-size: 28px; color: #fff;
    flex-shrink: 0; position: relative; z-index: 1;
    backdrop-filter: blur(8px);
    box-shadow: 0 4px 20px rgba(0,0,0,.2);
}
.db-header-text { position: relative; z-index: 1; }
.db-header-text h2 {
    color: #fff; font-size: 24px; font-weight: 800; margin: 0 0 5px;
    letter-spacing: -.3px;
}
.db-header-text p { color: rgba(255,255,255,.65); font-size: 14px; margin: 0; }
.db-header-pills { margin-left: auto; position: relative; z-index: 1; display: flex; gap: 8px; flex-wrap: wrap; }
.db-pill {
    background: rgba(255,255,255,.12);
    border: 1px solid rgba(255,255,255,.18);
    color: rgba(255,255,255,.85);
    border-radius: 20px; padding: 6px 14px;
    font-size: 12px; font-weight: 600;
    display: flex; align-items: center; gap: 5px;
    backdrop-filter: blur(4px);
}
.db-pill i { font-size: 13px; }

/* ── Stat cards ── */
.db-stat-card {
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 18px;
    padding: 22px 24px;
    position: relative;
    overflow: hidden;
    transition: transform .2s, box-shadow .2s;
    box-shadow: 0 2px 12px rgba(0,0,0,.05);
    height: 100%;
}
.db-stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 28px rgba(0,0,0,.1);
}
.db-stat-card::after {
    content: '';
    position: absolute;
    bottom: -24px; right: -24px;
    width: 100px; height: 100px;
    border-radius: 50%;
    opacity: .06;
}
.db-stat-label {
    font-size: 11.5px; font-weight: 700;
    text-transform: uppercase; letter-spacing: .07em;
    color: #9ca3af; margin-bottom: 10px;
    display: flex; align-items: center; gap: 6px;
}
.db-stat-value {
    font-size: 30px; font-weight: 800;
    color: #111827; letter-spacing: -.5px;
    line-height: 1;
    margin-bottom: 10px;
}
.db-stat-sub {
    font-size: 12px; color: #6b7280;
    display: flex; align-items: center; gap: 5px;
}
.db-stat-sub .badge-up   { color: #10b981; font-weight: 700; }
.db-stat-sub .badge-down { color: #ef4444; font-weight: 700; }
.db-stat-icon {
    position: absolute; top: 18px; right: 20px;
    width: 48px; height: 48px; border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; color: #fff;
    box-shadow: 0 4px 14px rgba(0,0,0,.18);
}

/* ── Section headers ── */
.db-section-title {
    font-size: 15px; font-weight: 700; color: #111827;
    display: flex; align-items: center; gap: 8px; margin-bottom: 16px;
}
.db-section-title i { font-size: 17px; }

/* ── Chart cards ── */
.db-chart-card {
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 18px;
    padding: 24px;
    box-shadow: 0 2px 12px rgba(0,0,0,.05);
    height: 100%;
}
.db-chart-card .card-head {
    display: flex; align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
}
.db-chart-card .card-head h6 {
    font-size: 14px; font-weight: 700; color: #111827; margin: 0;
}
.db-chart-card .card-head span {
    font-size: 11px; color: #9ca3af; font-weight: 600;
    text-transform: uppercase; letter-spacing: .06em;
}

/* ── Bar chart bars ── */
.db-bar-chart {
    display: flex;
    align-items: flex-end;
    gap: 8px;
    height: 160px;
    padding: 0 4px;
}
.db-bar-wrap {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 6px;
    height: 100%;
    justify-content: flex-end;
}
.db-bar {
    width: 100%;
    border-radius: 8px 8px 0 0;
    min-height: 4px;
    transition: opacity .2s;
    cursor: default;
    position: relative;
}
.db-bar:hover { opacity: .8; }
.db-bar-label {
    font-size: 10px; font-weight: 600; color: #9ca3af;
    white-space: nowrap;
}
.db-bar-val {
    font-size: 10px; font-weight: 700; color: #6b7280;
    position: absolute; top: -20px; left: 50%;
    transform: translateX(-50%);
    white-space: nowrap;
    opacity: 0;
    transition: opacity .2s;
    background: #111827;
    color: #fff;
    border-radius: 4px;
    padding: 2px 5px;
    pointer-events: none;
}
.db-bar:hover .db-bar-val { opacity: 1; }
.db-bar-axis {
    border-top: 1px solid #f3f4f6;
    margin-top: 4px;
}

/* ── Donut chart ── */
.db-donut-wrap {
    display: flex;
    align-items: center;
    gap: 24px;
    justify-content: center;
}
.db-donut-svg { flex-shrink: 0; }
.db-donut-legend { flex: 1; display: flex; flex-direction: column; gap: 8px; }
.db-legend-item {
    display: flex; align-items: center; gap: 8px;
    font-size: 12.5px; color: #374151;
}
.db-legend-dot {
    width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0;
}
.db-legend-val { margin-left: auto; font-weight: 700; color: #111827; font-size: 12px; }

/* ── Table card ── */
.db-table-card {
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 2px 12px rgba(0,0,0,.05);
}
.db-table-card .db-table-head {
    padding: 18px 22px;
    border-bottom: 1px solid #f0f0f0;
    display: flex; align-items: center; justify-content: space-between;
}
.db-table-card .db-table-head h6 {
    font-size: 14px; font-weight: 700; color: #111827; margin: 0;
    display: flex; align-items: center; gap: 8px;
}
.db-table-card .db-table-head a {
    font-size: 12px; font-weight: 600; color: #6366f1; text-decoration: none;
}
.db-table-card .db-table-head a:hover { text-decoration: underline; }
.db-table-card table { width: 100%; border-collapse: collapse; font-size: 13px; }
.db-table-card table th {
    padding: 10px 16px; background: #f9fafb;
    font-size: 10.5px; font-weight: 800; text-transform: uppercase;
    letter-spacing: .07em; color: #6b7280; border-bottom: 1px solid #f0f0f0;
    white-space: nowrap;
}
.db-table-card table td {
    padding: 13px 16px; border-bottom: 1px solid #f9fafb;
    color: #374151; vertical-align: middle;
}
.db-table-card table tr:last-child td { border-bottom: none; }
.db-table-card table tr:hover td { background: #fafbff; }
.db-order-num { font-weight: 700; color: #4f46e5; font-size: 12.5px; }
.db-customer-name { font-weight: 600; color: #111827; }
.db-amount { font-weight: 700; color: #111827; }

/* ── Status badges ── */
.db-badge {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 3px 9px; border-radius: 20px;
    font-size: 11px; font-weight: 700; white-space: nowrap;
}
.db-badge-pending    { background: #fef9c3; color: #854d0e; }
.db-badge-processing { background: #dbeafe; color: #1d4ed8; }
.db-badge-shipped    { background: #cffafe; color: #0e7490; }
.db-badge-delivered  { background: #dcfce7; color: #166534; }
.db-badge-cancelled  { background: #fee2e2; color: #991b1b; }
.db-badge-paid       { background: #dcfce7; color: #166534; }
.db-badge-unpaid     { background: #fef3c7; color: #92400e; }
.db-badge-cod        { background: #f3f4f6; color: #374151; }
.db-badge-dot {
    width: 6px; height: 6px; border-radius: 50%;
    background: currentColor; display: inline-block;
}

/* ── Top products list ── */
.db-top-product {
    display: flex; align-items: center; gap: 14px;
    padding: 12px 0; border-bottom: 1px solid #f3f4f6;
}
.db-top-product:last-child { border-bottom: none; padding-bottom: 0; }
.db-rank {
    width: 26px; height: 26px; border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    font-size: 11px; font-weight: 800; flex-shrink: 0;
}
.db-rank-1 { background: linear-gradient(135deg,#f59e0b,#d97706); color:#fff; }
.db-rank-2 { background: linear-gradient(135deg,#94a3b8,#64748b); color:#fff; }
.db-rank-3 { background: linear-gradient(135deg,#cd7c4e,#b45309); color:#fff; }
.db-rank-4, .db-rank-5 { background: #f3f4f6; color: #6b7280; }
.db-product-img {
    width: 40px; height: 40px; border-radius: 10px;
    background: linear-gradient(135deg, #f0f4ff, #e0e7ff);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; font-size: 18px; color: #a5b4fc;
    border: 1px solid #e0e7ff;
}
.db-product-info { flex: 1; min-width: 0; }
.db-product-name {
    font-size: 13px; font-weight: 600; color: #111827;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    margin-bottom: 2px;
}
.db-product-units { font-size: 11.5px; color: #9ca3af; }
.db-product-rev { font-size: 13px; font-weight: 700; color: #4f46e5; white-space: nowrap; }

/* ── Quick actions ── */
.db-action-btn {
    display: flex; align-items: center; gap: 10px;
    padding: 12px 16px; border-radius: 12px; border: 1.5px solid #e9ecef;
    background: #fff; text-decoration: none; color: #374151;
    font-size: 13px; font-weight: 600; transition: all .15s;
    box-shadow: 0 1px 4px rgba(0,0,0,.04);
}
.db-action-btn:hover {
    border-color: #c7d2fe; background: #f5f3ff;
    color: #4f46e5; text-decoration: none;
    transform: translateY(-1px); box-shadow: 0 4px 12px rgba(99,102,241,.15);
}
.db-action-btn i { font-size: 18px; }
</style>
@endpush

@section('content')
@php
    $cur = setting('currency_symbol', '$');
@endphp

{{-- ══════════════════════════════════════════════════════════════
     PAGE HEADER BANNER
════════════════════════════════════════════════════════════════ --}}
<div class="db-header">
    <div class="db-header-icon">
        <i class="bx bx-bar-chart-alt-2"></i>
    </div>
    <div class="db-header-text">
        <h2>Admin Dashboard</h2>
        <p>Overview of your marketplace — revenue, orders, and activity</p>
    </div>
    <div class="db-header-pills">
        <div class="db-pill">
            <i class="bx bx-calendar"></i>
            {{ now()->format('M j, Y') }}
        </div>
        <div class="db-pill">
            <i class="bx bx-store"></i>
            Live Marketplace
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════
     ROW 1 — 6 STAT CARDS
════════════════════════════════════════════════════════════════ --}}
<div class="row g-3 mb-4">

    {{-- Today's Revenue --}}
    <div class="col-xxl-2 col-xl-4 col-lg-4 col-md-6 col-sm-6">
        <div class="db-stat-card" style="border-top: 3px solid #f59e0b;">
            <div class="db-stat-icon" style="background: linear-gradient(135deg,#f59e0b,#d97706);">
                <i class="bx bx-sun"></i>
            </div>
            <div class="db-stat-label"><i class="bx bx-trending-up"></i> Today's Revenue</div>
            <div class="db-stat-value">{{ $cur }}{{ number_format($revenueToday, 0) }}</div>
            <div class="db-stat-sub">
                <i class="bx bx-calendar-check" style="color:#f59e0b;"></i>
                Paid orders today
            </div>
        </div>
    </div>

    {{-- Total Revenue --}}
    <div class="col-xxl-2 col-xl-4 col-lg-4 col-md-6 col-sm-6">
        <div class="db-stat-card" style="border-top: 3px solid #6366f1;">
            <div class="db-stat-icon" style="background: linear-gradient(135deg,#6366f1,#4f46e5);">
                <i class="bx bx-dollar-circle"></i>
            </div>
            <div class="db-stat-label"><i class="bx bx-coin-stack"></i> Total Revenue</div>
            <div class="db-stat-value">{{ $cur }}{{ number_format($revenueTotal, 0) }}</div>
            <div class="db-stat-sub">
                <i class="bx bx-trending-up" style="color:#10b981;"></i>
                <span class="badge-up">{{ $cur }}{{ number_format($revenueMonth, 0) }}</span>
                &nbsp;this month
            </div>
        </div>
    </div>

    {{-- Total Orders --}}
    <div class="col-xxl-2 col-xl-4 col-lg-4 col-md-6 col-sm-6">
        <div class="db-stat-card" style="border-top: 3px solid #3b82f6;">
            <div class="db-stat-icon" style="background: linear-gradient(135deg,#3b82f6,#2563eb);">
                <i class="bx bx-package"></i>
            </div>
            <div class="db-stat-label"><i class="bx bx-list-ul"></i> Total Orders</div>
            <div class="db-stat-value">{{ number_format($ordersTotal) }}</div>
            <div class="db-stat-sub">
                <i class="bx bx-calendar" style="color:#3b82f6;"></i>
                {{ number_format($ordersMonth) }} this month
            </div>
        </div>
    </div>

    {{-- Pending Orders --}}
    <div class="col-xxl-2 col-xl-4 col-lg-4 col-md-6 col-sm-6">
        <div class="db-stat-card" style="border-top: 3px solid #ef4444;">
            <div class="db-stat-icon" style="background: linear-gradient(135deg,#ef4444,#dc2626);">
                <i class="bx bx-time-five"></i>
            </div>
            <div class="db-stat-label"><i class="bx bx-error-circle"></i> Pending Orders</div>
            <div class="db-stat-value">{{ number_format($ordersPending) }}</div>
            <div class="db-stat-sub">
                @if($ordersPending > 0)
                    <span class="badge-down"><i class="bx bx-bell"></i> Need attention</span>
                @else
                    <span class="badge-up"><i class="bx bx-check"></i> All clear</span>
                @endif
            </div>
        </div>
    </div>

    {{-- Active Products --}}
    <div class="col-xxl-2 col-xl-4 col-lg-4 col-md-6 col-sm-6">
        <div class="db-stat-card" style="border-top: 3px solid #10b981;">
            <div class="db-stat-icon" style="background: linear-gradient(135deg,#10b981,#059669);">
                <i class="bx bx-shopping-bag"></i>
            </div>
            <div class="db-stat-label"><i class="bx bx-store"></i> Active Products</div>
            <div class="db-stat-value">{{ number_format($productsActive) }}</div>
            <div class="db-stat-sub">
                <i class="bx bx-check-circle" style="color:#10b981;"></i>
                Approved &amp; live
            </div>
        </div>
    </div>

    {{-- Pending Vendors --}}
    <div class="col-xxl-2 col-xl-4 col-lg-4 col-md-6 col-sm-6">
        <div class="db-stat-card" style="border-top: 3px solid #8b5cf6;">
            <div class="db-stat-icon" style="background: linear-gradient(135deg,#8b5cf6,#7c3aed);">
                <i class="bx bx-user-check"></i>
            </div>
            <div class="db-stat-label"><i class="bx bx-store-alt"></i> Pending Vendors</div>
            <div class="db-stat-value">{{ number_format($vendorsPending) }}</div>
            <div class="db-stat-sub">
                <i class="bx bx-user-circle" style="color:#8b5cf6;"></i>
                {{ number_format($customersTotal) }} customers total
            </div>
        </div>
    </div>

</div>

{{-- ══════════════════════════════════════════════════════════════
     ROW 2 — CHARTS
════════════════════════════════════════════════════════════════ --}}
<div class="row g-3 mb-4">

    {{-- Revenue Bar Chart (last 7 months) --}}
    <div class="col-xl-8 col-lg-7">
        <div class="db-chart-card">
            <div class="card-head">
                <h6><i class="bx bx-bar-chart-alt-2 me-1" style="color:#6366f1;"></i> Monthly Revenue</h6>
                <span>Last 7 months</span>
            </div>

            @php
                $maxRev = collect($monthlyRevenue)->max('revenue') ?: 1;
                $barColors = [
                    'linear-gradient(180deg,#6366f1,#4f46e5)',
                    'linear-gradient(180deg,#8b5cf6,#7c3aed)',
                    'linear-gradient(180deg,#3b82f6,#2563eb)',
                    'linear-gradient(180deg,#06b6d4,#0891b2)',
                    'linear-gradient(180deg,#10b981,#059669)',
                    'linear-gradient(180deg,#f59e0b,#d97706)',
                    'linear-gradient(180deg,#ef4444,#dc2626)',
                ];
            @endphp

            <div class="db-bar-chart" style="padding-bottom: 0;">
                @foreach ($monthlyRevenue as $idx => $m)
                    @php
                        $pct = $maxRev > 0 ? ($m['revenue'] / $maxRev) * 100 : 0;
                        $barH = max(4, round($pct * 1.6)); {{-- scale to 160px max --}}
                    @endphp
                    <div class="db-bar-wrap">
                        <div class="db-bar"
                             style="height: {{ $barH }}px; background: {{ $barColors[$idx % count($barColors)] }}; position: relative;">
                            <span class="db-bar-val">{{ $cur }}{{ number_format($m['revenue'], 0) }}</span>
                        </div>
                        <div class="db-bar-label">{{ $m['label'] }}</div>
                    </div>
                @endforeach
            </div>
            <div class="db-bar-axis"></div>

            {{-- X-axis totals summary --}}
            <div class="d-flex align-items-center gap-3 mt-3" style="flex-wrap:wrap;">
                <div style="font-size:12px;color:#6b7280;">
                    <span style="font-weight:700;color:#111827;">{{ $cur }}{{ number_format(collect($monthlyRevenue)->sum('revenue'), 0) }}</span>
                    &nbsp;total over 7 months
                </div>
                <div style="font-size:12px;color:#6b7280;">
                    Avg. <span style="font-weight:700;color:#111827;">
                        {{ $cur }}{{ number_format(collect($monthlyRevenue)->avg('revenue'), 0) }}
                    </span>/month
                </div>
            </div>
        </div>
    </div>

    {{-- Orders by Status Donut --}}
    <div class="col-xl-4 col-lg-5">
        <div class="db-chart-card">
            <div class="card-head">
                <h6><i class="bx bx-doughnut-chart me-1" style="color:#f59e0b;"></i> Orders by Status</h6>
                <span>All time</span>
            </div>

            @php
                $statusMeta = [
                    'pending'    => ['color'=>'#f59e0b','label'=>'Pending'],
                    'processing' => ['color'=>'#3b82f6','label'=>'Processing'],
                    'shipped'    => ['color'=>'#06b6d4','label'=>'Shipped'],
                    'delivered'  => ['color'=>'#10b981','label'=>'Delivered'],
                    'cancelled'  => ['color'=>'#ef4444','label'=>'Cancelled'],
                ];
                $statusTotal = array_sum($ordersByStatus) ?: 1;

                // Build SVG donut
                $r = 54; $cx = 64; $cy = 64;
                $circumference = 2 * M_PI * $r;
                $offset = 0;
                $segments = [];
                foreach ($statusMeta as $key => $meta) {
                    $val = $ordersByStatus[$key] ?? 0;
                    $pct = $val / $statusTotal;
                    $dash = $pct * $circumference;
                    $segments[] = [
                        'dash'   => $dash,
                        'gap'    => $circumference - $dash,
                        'offset' => $circumference - $offset,
                        'color'  => $meta['color'],
                        'label'  => $meta['label'],
                        'val'    => $val,
                    ];
                    $offset += $dash;
                }
            @endphp

            <div class="db-donut-wrap">
                <svg class="db-donut-svg" width="128" height="128" viewBox="0 0 128 128">
                    <circle cx="{{ $cx }}" cy="{{ $cy }}" r="{{ $r }}"
                            fill="none" stroke="#f3f4f6" stroke-width="18"/>
                    @foreach ($segments as $seg)
                        @if ($seg['val'] > 0)
                        <circle cx="{{ $cx }}" cy="{{ $cy }}" r="{{ $r }}"
                                fill="none"
                                stroke="{{ $seg['color'] }}"
                                stroke-width="18"
                                stroke-dasharray="{{ number_format($seg['dash'], 2) }} {{ number_format($seg['gap'], 2) }}"
                                stroke-dashoffset="{{ number_format($seg['offset'], 2) }}"
                                transform="rotate(-90 {{ $cx }} {{ $cy }})"
                                stroke-linecap="butt"/>
                        @endif
                    @endforeach
                    {{-- center text --}}
                    <text x="{{ $cx }}" y="{{ $cy - 6 }}" text-anchor="middle"
                          font-size="20" font-weight="800" fill="#111827">
                        {{ number_format($statusTotal) }}
                    </text>
                    <text x="{{ $cx }}" y="{{ $cy + 12 }}" text-anchor="middle"
                          font-size="9" fill="#9ca3af" font-weight="600" letter-spacing="0.5">
                        ORDERS
                    </text>
                </svg>

                <div class="db-donut-legend">
                    @foreach ($statusMeta as $key => $meta)
                        <div class="db-legend-item">
                            <span class="db-legend-dot" style="background: {{ $meta['color'] }};"></span>
                            {{ $meta['label'] }}
                            <span class="db-legend-val">{{ number_format($ordersByStatus[$key] ?? 0) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════
     ROW 3 — RECENT ORDERS + TOP PRODUCTS
════════════════════════════════════════════════════════════════ --}}
<div class="row g-3 mb-4">

    {{-- Recent Orders Table --}}
    <div class="col-xl-8 col-lg-7">
        <div class="db-table-card">
            <div class="db-table-head">
                <h6><i class="bx bx-receipt" style="color:#6366f1;"></i>&nbsp; Recent Orders</h6>
                {{-- Link to orders index if route exists --}}
                @php $ordRoute = null;
                try { $ordRoute = route('admin.orders.index'); } catch (\Exception $e) {} @endphp
                @if ($ordRoute)
                    <a href="{{ $ordRoute }}">View all <i class="bx bx-chevron-right"></i></a>
                @endif
            </div>
            <div style="overflow-x:auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Payment</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentOrders as $order)
                            <tr>
                                <td><span class="db-order-num">{{ $order->order_number }}</span></td>
                                <td>
                                    <span class="db-customer-name">
                                        {{ optional($order->user)->name ?? $order->customer_name ?? '—' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="db-amount">{{ $cur }}{{ number_format($order->grand_total, 2) }}</span>
                                </td>
                                <td>
                                    @php
                                        $st = $order->status;
                                        $stClass = match($st) {
                                            'pending'    => 'db-badge-pending',
                                            'processing' => 'db-badge-processing',
                                            'shipped'    => 'db-badge-shipped',
                                            'delivered'  => 'db-badge-delivered',
                                            'cancelled'  => 'db-badge-cancelled',
                                            default      => 'db-badge-pending',
                                        };
                                    @endphp
                                    <span class="db-badge {{ $stClass }}">
                                        <span class="db-badge-dot"></span>
                                        {{ ucfirst($st) }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $ps = $order->payment_status;
                                        $psClass = match($ps) {
                                            'paid'   => 'db-badge-paid',
                                            'unpaid' => 'db-badge-unpaid',
                                            'cod'    => 'db-badge-cod',
                                            default  => 'db-badge-cod',
                                        };
                                    @endphp
                                    <span class="db-badge {{ $psClass }}">
                                        {{ strtoupper($ps) }}
                                    </span>
                                </td>
                                <td style="color:#9ca3af;font-size:12px;white-space:nowrap;">
                                    {{ $order->created_at->format('M j, Y') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align:center;padding:32px;color:#9ca3af;">
                                    <i class="bx bx-package" style="font-size:28px;display:block;margin-bottom:8px;"></i>
                                    No orders yet
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Top Products --}}
    <div class="col-xl-4 col-lg-5">
        <div class="db-chart-card" style="padding: 0; overflow:hidden;">
            <div class="db-table-head" style="padding:18px 22px;border-bottom:1px solid #f0f0f0;display:flex;align-items:center;justify-content:space-between;">
                <h6 style="font-size:14px;font-weight:700;color:#111827;margin:0;display:flex;align-items:center;gap:8px;">
                    <i class="bx bx-trophy" style="color:#f59e0b;font-size:17px;"></i>
                    Top Selling Products
                </h6>
                <span style="font-size:11px;color:#9ca3af;font-weight:600;text-transform:uppercase;letter-spacing:.06em;">by units</span>
            </div>
            <div style="padding: 16px 22px;">
                @forelse ($topProducts as $idx => $product)
                    <div class="db-top-product">
                        <div class="db-rank db-rank-{{ $idx + 1 }}">{{ $idx + 1 }}</div>
                        <div class="db-product-img">
                            <i class="bx bx-box"></i>
                        </div>
                        <div class="db-product-info">
                            <div class="db-product-name" title="{{ $product->product_name }}">
                                {{ $product->product_name }}
                            </div>
                            <div class="db-product-units">
                                {{ number_format($product->units_sold) }} units sold
                            </div>
                        </div>
                        <div class="db-product-rev">
                            {{ $cur }}{{ number_format($product->revenue, 0) }}
                        </div>
                    </div>
                @empty
                    <div style="text-align:center;padding:32px 0;color:#9ca3af;">
                        <i class="bx bx-shopping-bag" style="font-size:28px;display:block;margin-bottom:8px;"></i>
                        No sales data yet
                    </div>
                @endforelse
            </div>
        </div>
    </div>

</div>

{{-- ══════════════════════════════════════════════════════════════
     ROW 4 — QUICK ACTIONS
════════════════════════════════════════════════════════════════ --}}
<div class="row g-3">
    <div class="col-12">
        <div class="db-chart-card">
            <div class="db-section-title" style="margin-bottom:16px;">
                <i class="bx bx-grid-alt" style="color:#6366f1;"></i> Quick Actions
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('admin.home-sections.index') }}" class="db-action-btn">
                    <i class="bx bx-layout" style="color:#6366f1;"></i> Home Sections
                </a>
                <a href="{{ route('admin.settings.index', 'general') }}" class="db-action-btn">
                    <i class="bx bx-cog" style="color:#10b981;"></i> General Settings
                </a>
                <a href="{{ route('admin.settings.index', 'payment') }}" class="db-action-btn">
                    <i class="bx bx-credit-card" style="color:#3b82f6;"></i> Payment Gateways
                </a>
                <a href="{{ route('admin.settings.index', 'smtp') }}" class="db-action-btn">
                    <i class="bx bx-envelope" style="color:#8b5cf6;"></i> Email / SMTP
                </a>
                <a href="{{ route('admin.settings.index', 'map') }}" class="db-action-btn">
                    <i class="bx bx-map" style="color:#ef4444;"></i> Google Maps
                </a>
                <a href="{{ route('home') }}" target="_blank" class="db-action-btn">
                    <i class="bx bx-store" style="color:#f59e0b;"></i> View Storefront
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
