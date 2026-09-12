@extends('vendor.layouts.app')
@section('title', 'My Earnings')

@push('styles')
<style>
/* ── Earnings stat cards ── */
.earn-cards { display:grid; grid-template-columns:repeat(4,1fr); gap:20px; margin-bottom:28px; }
@media(max-width:991px){ .earn-cards{grid-template-columns:repeat(2,1fr);} }
@media(max-width:575px){ .earn-cards{grid-template-columns:1fr;} }

.earn-card {
    background:#fff;
    border-radius:14px;
    padding:22px 20px;
    box-shadow:0 2px 12px rgba(0,0,0,.06);
    display:flex;
    align-items:flex-start;
    gap:16px;
    border:1px solid #f0f0f0;
    transition:transform .2s,box-shadow .2s;
}
.earn-card:hover { transform:translateY(-3px); box-shadow:0 8px 24px rgba(0,0,0,.1); }
.earn-card__icon {
    width:52px; height:52px;
    border-radius:12px;
    display:flex; align-items:center; justify-content:center;
    font-size:22px; flex-shrink:0;
}
.earn-card__icon--green  { background:#dcfce7; color:#16a34a; }
.earn-card__icon--blue   { background:#dbeafe; color:#2563eb; }
.earn-card__icon--orange { background:#ffedd5; color:#ea580c; }
.earn-card__icon--purple { background:#ede9fe; color:#7c3aed; }
.earn-card__value { font-size:26px; font-weight:800; color:#1a1a2e; line-height:1.1; }
.earn-card__label { font-size:12px; color:#888; font-weight:600; text-transform:uppercase; letter-spacing:.06em; margin-top:4px; }
.earn-card__sub   { font-size:11px; color:#aaa; margin-top:2px; }

/* ── Chart card ── */
.earn-chart-card {
    background:#fff;
    border-radius:14px;
    padding:24px;
    box-shadow:0 2px 12px rgba(0,0,0,.06);
    border:1px solid #f0f0f0;
    margin-bottom:28px;
}
.earn-chart-title { font-size:15px; font-weight:700; color:#1a1a2e; margin-bottom:20px; }

/* ── Simple SVG bar chart wrapper ── */
.bar-chart-wrap { position:relative; }
.bar-chart-svg  { width:100%; overflow:visible; }
.bar-chart-bar  { transition:opacity .2s; cursor:pointer; }
.bar-chart-bar:hover { opacity:.8; }

/* ── Transactions table ── */
.earn-table-card {
    background:#fff;
    border-radius:14px;
    padding:24px;
    box-shadow:0 2px 12px rgba(0,0,0,.06);
    border:1px solid #f0f0f0;
    margin-bottom:28px;
}
.earn-table-head {
    display:flex; align-items:center; justify-content:space-between;
    margin-bottom:18px; gap:12px; flex-wrap:wrap;
}
.earn-table-title { font-size:15px; font-weight:700; color:#1a1a2e; }

.earn-table { width:100%; border-collapse:collapse; font-size:13px; }
.earn-table thead th {
    padding:10px 12px;
    text-align:left;
    font-size:11px;
    font-weight:700;
    text-transform:uppercase;
    letter-spacing:.06em;
    color:#888;
    border-bottom:2px solid #f0f0f0;
    white-space:nowrap;
}
.earn-table tbody tr { border-bottom:1px solid #f8f8f8; transition:background .15s; }
.earn-table tbody tr:hover { background:#fafafa; }
.earn-table tbody td { padding:12px 12px; color:#333; vertical-align:middle; }

/* status pills */
.earn-pill {
    display:inline-block;
    padding:3px 10px;
    border-radius:20px;
    font-size:11px;
    font-weight:700;
    letter-spacing:.03em;
}
.earn-pill--paid       { background:#dcfce7; color:#16a34a; }
.earn-pill--pending    { background:#fef9c3; color:#854d0e; }
.earn-pill--processing { background:#dbeafe; color:#1d4ed8; }
.earn-pill--cancelled  { background:#fee2e2; color:#dc2626; }
.earn-pill--refunded   { background:#f1f5f9; color:#64748b; }
.earn-pill--shipped    { background:#ede9fe; color:#6d28d9; }
.earn-pill--delivered  { background:#dcfce7; color:#15803d; }

/* payout CTA */
.payout-card {
    background:linear-gradient(135deg,#1a1a2e 0%,#0f3460 100%);
    border-radius:14px;
    padding:28px 28px;
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:20px;
    flex-wrap:wrap;
    margin-bottom:28px;
}
.payout-card__left h3 { color:#fff; font-size:18px; font-weight:800; margin:0 0 6px; }
.payout-card__left p  { color:rgba(255,255,255,.65); font-size:13px; margin:0; }
.payout-card__amount  { color:#fbbf24; font-size:32px; font-weight:900; white-space:nowrap; }
.payout-btn {
    background:#fbbf24;
    color:#1a1a2e;
    border:none;
    border-radius:10px;
    padding:12px 26px;
    font-size:14px;
    font-weight:800;
    cursor:pointer;
    transition:all .2s;
    white-space:nowrap;
}
.payout-btn:hover { background:#f59e0b; transform:translateY(-2px); box-shadow:0 8px 20px rgba(245,158,11,.4); }
</style>
@endpush

@section('vendor_content')

@php $currency = setting('currency_symbol','$'); @endphp

{{-- ── Payout CTA ── --}}
<div class="payout-card">
    <div class="payout-card__left">
        <h3>Available Balance</h3>
        <p>Your total earnings from paid orders. Request a payout anytime.</p>
    </div>
    <div class="payout-card__amount">{{ $currency }}{{ number_format($totalEarned, 2) }}</div>
    <button class="payout-btn" onclick="alert('Payout requests will be available soon. We will notify you when this feature goes live!')">
        <i class="fas fa-paper-plane"></i> Request Payout
    </button>
</div>

{{-- ── 4 Stat Cards ── --}}
<div class="earn-cards">
    <div class="earn-card">
        <div class="earn-card__icon earn-card__icon--green"><i class="fas fa-wallet"></i></div>
        <div>
            <div class="earn-card__value">{{ $currency }}{{ number_format($totalEarned, 2) }}</div>
            <div class="earn-card__label">Total Earned</div>
            <div class="earn-card__sub">All time, paid orders</div>
        </div>
    </div>
    <div class="earn-card">
        <div class="earn-card__icon earn-card__icon--blue"><i class="fas fa-calendar-alt"></i></div>
        <div>
            <div class="earn-card__value">{{ $currency }}{{ number_format($thisMonth, 2) }}</div>
            <div class="earn-card__label">This Month</div>
            <div class="earn-card__sub">{{ now()->format('F Y') }}</div>
        </div>
    </div>
    <div class="earn-card">
        <div class="earn-card__icon earn-card__icon--orange"><i class="fas fa-percentage"></i></div>
        <div>
            <div class="earn-card__value">{{ $currency }}{{ number_format($totalCommission, 2) }}</div>
            <div class="earn-card__label">Commission Paid</div>
            <div class="earn-card__sub">Platform fee, all time</div>
        </div>
    </div>
    <div class="earn-card">
        <div class="earn-card__icon earn-card__icon--purple"><i class="fas fa-shopping-bag"></i></div>
        <div>
            <div class="earn-card__value">{{ number_format($ordersCount) }}</div>
            <div class="earn-card__label">Orders Fulfilled</div>
            <div class="earn-card__sub">Paid orders total</div>
        </div>
    </div>
</div>

{{-- ── Bar Chart – last 6 months ── --}}
<div class="earn-chart-card">
    <div class="earn-chart-title"><i class="fas fa-chart-bar" style="color:#336699;margin-right:8px;"></i>Monthly Earnings — Last 6 Months</div>
    <div class="bar-chart-wrap">
        @php
            $maxVal = max(array_column($monthlyEarnings, 'amount') ?: [1]);
            $maxVal = $maxVal ?: 1;
            $chartH = 180;
            $barW   = 48;
            $gap    = 24;
            $totalW = count($monthlyEarnings) * ($barW + $gap) - $gap;
        @endphp
        <svg class="bar-chart-svg" viewBox="0 0 {{ $totalW + 60 }} {{ $chartH + 60 }}" style="max-height:260px;">
            @foreach($monthlyEarnings as $idx => $m)
            @php
                $barH  = $maxVal > 0 ? ($m['amount'] / $maxVal) * $chartH : 4;
                $barH  = max($barH, 4);
                $x     = 30 + $idx * ($barW + $gap);
                $y     = $chartH - $barH + 10;
                $pct   = $maxVal > 0 ? round(($m['amount'] / $maxVal) * 100) : 0;
            @endphp
            <g class="bar-chart-bar">
                {{-- Background track --}}
                <rect x="{{ $x }}" y="10" width="{{ $barW }}" height="{{ $chartH }}"
                      rx="6" fill="#f3f4f6"/>
                {{-- Filled bar --}}
                <rect x="{{ $x }}" y="{{ $y }}" width="{{ $barW }}" height="{{ $barH }}"
                      rx="6" fill="url(#barGrad)"/>
                {{-- Amount label above --}}
                <text x="{{ $x + $barW/2 }}" y="{{ $y - 6 }}"
                      text-anchor="middle" font-size="10" font-weight="700" fill="#336699">
                    {{ $m['amount'] > 0 ? $currency.number_format($m['amount'],0) : '' }}
                </text>
                {{-- Month label below --}}
                <text x="{{ $x + $barW/2 }}" y="{{ $chartH + 30 }}"
                      text-anchor="middle" font-size="11" fill="#888">{{ $m['short'] }}</text>
            </g>
            @endforeach
            <defs>
                <linearGradient id="barGrad" x1="0" y1="0" x2="0" y2="1">
                    <stop offset="0%" stop-color="#336699"/>
                    <stop offset="100%" stop-color="#5b9bd5"/>
                </linearGradient>
            </defs>
        </svg>
    </div>
</div>

{{-- ── Recent Transactions Table ── --}}
<div class="earn-table-card">
    <div class="earn-table-head">
        <div class="earn-table-title"><i class="fas fa-history" style="color:#336699;margin-right:8px;"></i>Recent Transactions</div>
        <a href="{{ route('vendor.orders.index') }}" class="v-btn v-btn--primary v-btn--sm">View All Orders</a>
    </div>

    @if($recent->count())
    <div class="table-responsive">
        <table class="earn-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Order #</th>
                    <th>Product</th>
                    <th>Sale Price</th>
                    <th>Commission</th>
                    <th>You Receive</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recent as $item)
                @php
                    $order      = $item->order;
                    $status     = $order?->status ?? 'pending';
                    $pillClass  = match($status) {
                        'paid','completed','delivered' => 'earn-pill--paid',
                        'pending'                      => 'earn-pill--pending',
                        'processing'                   => 'earn-pill--processing',
                        'shipped'                      => 'earn-pill--shipped',
                        'cancelled'                    => 'earn-pill--cancelled',
                        'refunded'                     => 'earn-pill--refunded',
                        default                        => 'earn-pill--pending',
                    };
                @endphp
                <tr>
                    <td style="white-space:nowrap;color:#666;">{{ $item->created_at->format('d M Y') }}</td>
                    <td>
                        @if($order)
                        <a href="{{ route('vendor.orders.show', $order->id) }}"
                           style="color:#336699;font-weight:700;text-decoration:none;">
                            {{ $order->order_number }}
                        </a>
                        @else —
                        @endif
                    </td>
                    <td style="max-width:200px;">
                        <span title="{{ $item->product_name }}">
                            {{ \Illuminate\Support\Str::limit($item->product_name, 35) }}
                        </span>
                        @if($item->quantity > 1)
                        <span style="color:#888;font-size:11px;"> ×{{ $item->quantity }}</span>
                        @endif
                    </td>
                    <td style="font-weight:600;">{{ $currency }}{{ number_format($item->line_total, 2) }}</td>
                    <td style="color:#ea580c;">
                        {{ number_format($item->commission_rate, 1) }}%
                        <span style="color:#bbb;font-size:11px;">({{ $currency }}{{ number_format($item->commission_amount, 2) }})</span>
                    </td>
                    <td style="font-weight:700;color:#16a34a;">{{ $currency }}{{ number_format($item->vendor_amount, 2) }}</td>
                    <td><span class="earn-pill {{ $pillClass }}">{{ ucfirst($status) }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="v-empty">
        <i class="fas fa-receipt fa-2x mb-2"></i>
        <p>No transactions yet. Once customers place orders your earnings will appear here.</p>
    </div>
    @endif
</div>

@endsection
