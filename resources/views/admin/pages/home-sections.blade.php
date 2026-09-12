{{-- Admin > Pages > Home Sections --}}
@extends('admin.layouts.app')
@section('title', 'Home Page Sections')
@section('page_title', '')
@section('breadcrumb', '')

@push('styles')
<style>
.page-header-breadcrumb { display:none !important; }

/* ── Page heading ──────────────────────────────────── */
.hs-page-head { display:flex; align-items:center; justify-content:space-between; margin-bottom:28px; margin-top:24px; }
.hs-page-head h4 { font-size:22px; font-weight:800; color:#1e293b; margin:0; display:flex; align-items:center; gap:10px; }
.hs-page-head h4 .head-icon { width:42px; height:42px; border-radius:12px; background:linear-gradient(135deg,#6c63ff,#a78bfa); display:flex; align-items:center; justify-content:center; color:#fff; font-size:20px; }

/* ── Stat cards ────────────────────────────────────── */
.hs-stats { display:grid; grid-template-columns:repeat(4,1fr); gap:20px; margin-bottom:26px; }
.hs-stat { border-radius:20px; background:#fff; box-shadow:0 4px 24px rgba(0,0,0,.07); overflow:hidden; position:relative; transition:transform .2s,box-shadow .2s; }
.hs-stat:hover { transform:translateY(-4px); box-shadow:0 12px 36px rgba(0,0,0,.12); }
.hs-stat__top { padding:20px 20px 12px; display:flex; align-items:flex-start; justify-content:space-between; gap:12px; }
.hs-stat__icon { width:52px; height:52px; border-radius:14px; display:flex; align-items:center; justify-content:center; font-size:22px; color:#fff; flex-shrink:0; }
.hs-stat__val  { font-size:36px; font-weight:900; line-height:1; color:#1e293b; }
.hs-stat__lbl  { font-size:12px; font-weight:600; color:#64748b; margin-top:4px; }
.hs-stat__bar  { height:5px; }
.hs-stat__foot { padding:9px 20px 12px; font-size:11.5px; color:#94a3b8; border-top:1px solid #f1f5f9; display:flex; align-items:center; gap:5px; }
.hs-stat--all  .hs-stat__icon { background:linear-gradient(135deg,#6c63ff,#a78bfa); }
.hs-stat--all  .hs-stat__bar  { background:linear-gradient(90deg,#6c63ff,#a78bfa); }
.hs-stat--on   .hs-stat__icon { background:linear-gradient(135deg,#10b981,#34d399); }
.hs-stat--on   .hs-stat__bar  { background:linear-gradient(90deg,#10b981,#34d399); }
.hs-stat--off  .hs-stat__icon { background:linear-gradient(135deg,#64748b,#94a3b8); }
.hs-stat--off  .hs-stat__bar  { background:linear-gradient(90deg,#64748b,#94a3b8); }
.hs-stat--promo .hs-stat__icon { background:linear-gradient(135deg,#f59e0b,#fbbf24); }
.hs-stat--promo .hs-stat__bar  { background:linear-gradient(90deg,#f59e0b,#fbbf24); }

/* ── Main card ─────────────────────────────────────── */
.hs-card { background:#fff; border-radius:20px; box-shadow:0 4px 24px rgba(0,0,0,.07); overflow:hidden; }
.hs-card__head { display:flex; align-items:center; justify-content:space-between; padding:20px 26px; border-bottom:1px solid #f1f5f9; }
.hs-card__title { font-size:15px; font-weight:700; color:#1e293b; display:flex; align-items:center; gap:9px; }
.hs-card__title i { font-size:18px; color:#6c63ff; }
.btn-preview { display:inline-flex; align-items:center; gap:7px; padding:9px 18px; background:linear-gradient(135deg,#f8faff,#f1f5ff); color:#6c63ff; border:1.5px solid #ddd6fe; border-radius:12px; font-size:13px; font-weight:700; text-decoration:none; transition:all .2s; }
.btn-preview:hover { background:linear-gradient(135deg,#6c63ff,#a78bfa); color:#fff; border-color:transparent; }

.hs-info { display:flex; align-items:center; gap:8px; padding:14px 26px; background:#fafbff; border-bottom:1px solid #f1f5f9; font-size:12.5px; color:#64748b; }
.hs-info i { color:#a78bfa; font-size:15px; }

/* ── Sections table ─────────────────────────────────── */
.sec-table { width:100%; border-collapse:separate; border-spacing:0; }
.sec-table thead th { background:#f8fafc; color:#475569; font-size:10.5px; font-weight:700; text-transform:uppercase; letter-spacing:.08em; padding:12px 16px; border-bottom:2px solid #e2e8f0; white-space:nowrap; }
.sec-table tbody td { padding:0; border-bottom:1px solid #f1f5f9; vertical-align:middle; }
.sec-table tbody tr:last-child td { border-bottom:none; }

/* ── Section row ────────────────────────────────────── */
.sec-row { display:flex; align-items:center; gap:0; transition:background .15s; cursor:default; }
.sec-row:hover { background:#fafbff; }
.sec-row.dragging { opacity:.35; background:#f0f4ff; }
.sec-row.drag-over { box-shadow:inset 0 3px 0 #6c63ff; }

.sec-col-grip  { width:44px; display:flex; align-items:center; justify-content:center; padding:18px 0; }
.sec-col-num   { width:44px; display:flex; align-items:center; justify-content:center; padding:18px 0; }
.sec-col-type  { width:220px; padding:14px 12px; flex-shrink:0; }
.sec-col-config{ flex:1; padding:14px 16px; min-width:0; }
.sec-col-vis   { width:78px; display:flex; align-items:center; justify-content:center; padding:14px 0; }
.sec-col-act   { width:56px; display:flex; align-items:center; justify-content:center; padding:14px 0; }

.drag-grip { color:#d1d5db; font-size:20px; cursor:grab; line-height:1; transition:color .15s; }
.drag-grip:hover { color:#6c63ff; }
.drag-grip:active { cursor:grabbing; }

.order-num { width:28px; height:28px; border-radius:50%; background:linear-gradient(135deg,#f1f5f9,#e2e8f0); display:flex; align-items:center; justify-content:center; font-size:11.5px; font-weight:800; color:#334155; }

/* ── Section type chip ──────────────────────────────── */
.sec-type { display:flex; align-items:center; gap:9px; }
.sec-type__icon { width:36px; height:36px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:16px; color:#fff; flex-shrink:0; box-shadow:0 4px 12px rgba(0,0,0,.15); }
.sec-type__name { font-size:13px; font-weight:700; color:#1e293b; line-height:1.3; }
.sec-type__badges { display:flex; gap:4px; margin-top:4px; flex-wrap:wrap; }
.badge-key  { font-size:9.5px; font-weight:600; padding:2px 6px; border-radius:4px; background:#f1f5f9; color:#475569; font-family:monospace; letter-spacing:.02em; }
.badge-core { font-size:9.5px; font-weight:700; padding:2px 7px; border-radius:10px; background:#ede9fe; color:#6c63ff; }

/* ── Inline config form ─────────────────────────────── */
.cfg-form  { display:flex; flex-wrap:wrap; align-items:center; gap:8px; }
.cfg-input { height:34px; padding:0 12px; border:1.5px solid #e2e8f0; border-radius:9px; font-size:12.5px; color:#374151; background:#f8fafc; transition:border-color .15s; min-width:140px; max-width:200px; }
.cfg-input:focus { outline:none; border-color:#a78bfa; background:#fff; }
.cfg-select { height:34px; padding:0 28px 0 10px; border:1.5px solid #e2e8f0; border-radius:9px; font-size:12px; color:#374151; background:#f8fafc url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='7'%3E%3Cpath d='M1 1l4 4 4-4' stroke='%2394a3b8' stroke-width='1.5' fill='none'/%3E%3C/svg%3E") no-repeat right 9px center; appearance:none; transition:border-color .15s; min-width:170px; max-width:210px; }
.cfg-select:focus { outline:none; border-color:#a78bfa; background-color:#fff; }
.cfg-limit-wrap { display:flex; align-items:center; gap:5px; }
.cfg-limit-lbl { font-size:11px; color:#94a3b8; font-weight:600; white-space:nowrap; }
.cfg-limit-input { width:48px; height:34px; text-align:center; padding:0 6px; border:1.5px solid #e2e8f0; border-radius:9px; font-size:13px; font-weight:700; color:#1e293b; background:#f8fafc; }
.cfg-limit-input:focus { outline:none; border-color:#a78bfa; background:#fff; }
.btn-save { height:34px; padding:0 16px; background:linear-gradient(135deg,#6c63ff,#a78bfa); color:#fff; border:none; border-radius:9px; font-size:12.5px; font-weight:700; cursor:pointer; white-space:nowrap; transition:transform .15s,box-shadow .15s; box-shadow:0 3px 10px rgba(108,99,255,.3); }
.btn-save:hover { transform:translateY(-1px); box-shadow:0 5px 16px rgba(108,99,255,.45); }

/* ── Banner status pills ────────────────────────────── */
.cfg-status { display:flex; align-items:center; gap:7px; margin-top:7px; flex-wrap:wrap; }
.pill-random   { display:inline-flex; align-items:center; gap:5px; font-size:10.5px; font-weight:700; padding:3px 10px; border-radius:20px; background:#f1f5f9; color:#64748b; }
.pill-specific { display:inline-flex; align-items:center; gap:5px; font-size:10.5px; font-weight:700; padding:3px 10px; border-radius:20px; background:#ede9fe; color:#6c63ff; }
.pill-limit    { display:inline-flex; align-items:center; gap:5px; font-size:10.5px; font-weight:700; padding:3px 10px; border-radius:20px; background:#dbeafe; color:#1d4ed8; }

/* ── Banner swatch (for pinned banner) ──────────────── */
.banner-swatch-mini { width:32px; height:22px; border-radius:5px; display:inline-block; flex-shrink:0; border:1px solid rgba(0,0,0,.08); }

/* ── Visible toggle ─────────────────────────────────── */
.vis-form .form-check-input { width:40px; height:21px; cursor:pointer; }

/* ── Delete btn ─────────────────────────────────────── */
.btn-del-row { width:32px; height:32px; border-radius:8px; background:#fff0f0; color:#dc2626; border:none; display:flex; align-items:center; justify-content:center; cursor:pointer; font-size:14px; transition:background .15s,transform .12s; }
.btn-del-row:hover { background:#fee2e2; transform:scale(1.1); }

/* ── Right panel ─────────────────────────────────────── */
.rp-card { background:#fff; border-radius:20px; box-shadow:0 4px 24px rgba(0,0,0,.07); overflow:hidden; margin-bottom:18px; }
.rp-card__head { padding:18px 22px 14px; display:flex; align-items:center; gap:10px; border-bottom:1px solid #f1f5f9; }
.rp-card__head-icon { width:36px; height:36px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:17px; color:#fff; flex-shrink:0; }
.rp-card__head-title { font-size:14px; font-weight:700; color:#1e293b; }
.rp-card__head-sub { font-size:11.5px; color:#94a3b8; margin-top:1px; }
.rp-card__body { padding:18px 22px 22px; }

.rp-label { font-size:11.5px; font-weight:700; color:#374151; margin-bottom:6px; display:block; }
.rp-input { width:100%; height:38px; padding:0 13px; border:1.5px solid #e2e8f0; border-radius:10px; font-size:13px; color:#374151; background:#f8fafc; transition:border-color .15s; }
.rp-input:focus { outline:none; border-color:#a78bfa; background:#fff; }
.rp-select { width:100%; height:38px; padding:0 30px 0 12px; border:1.5px solid #e2e8f0; border-radius:10px; font-size:13px; color:#374151; background:#f8fafc url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='7'%3E%3Cpath d='M1 1l4 4 4-4' stroke='%2394a3b8' stroke-width='1.5' fill='none'/%3E%3C/svg%3E") no-repeat right 11px center; appearance:none; transition:border-color .15s; }
.rp-select:focus { outline:none; border-color:#a78bfa; background-color:#fff; }
.rp-hint { font-size:11px; color:#94a3b8; margin-top:5px; }
.btn-add-sec { width:100%; padding:13px; background:linear-gradient(135deg,#6c63ff,#5a7cff 50%,#a78bfa); color:#fff; border:none; border-radius:13px; font-size:14px; font-weight:700; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:9px; box-shadow:0 4px 16px rgba(108,99,255,.4); transition:transform .2s,box-shadow .2s; }
.btn-add-sec:hover { transform:translateY(-2px); box-shadow:0 8px 24px rgba(108,99,255,.55); }
.btn-add-sec .btn-icon { width:24px; height:24px; background:rgba(255,255,255,.25); border-radius:7px; display:flex; align-items:center; justify-content:center; }

/* ── How it works ────────────────────────────────────── */
.how-step { display:flex; gap:13px; padding:10px 0; border-bottom:1px solid #f8fafc; }
.how-step:last-child { border-bottom:none; }
.how-num { width:22px; height:22px; border-radius:50%; background:linear-gradient(135deg,#6c63ff,#a78bfa); color:#fff; font-size:10.5px; font-weight:700; display:flex; align-items:center; justify-content:center; flex-shrink:0; margin-top:1px; }
.how-text strong { font-size:12.5px; color:#1e293b; font-weight:700; display:block; }
.how-text span { font-size:11.5px; color:#64748b; }

/* ── No banners notice ──────────────────────────────── */
.no-banners-notice { border-radius:12px; padding:14px 16px; background:linear-gradient(135deg,#fffbeb,#fef3c7); border:1px solid #fde68a; display:flex; align-items:flex-start; gap:10px; margin-top:16px; }
.no-banners-notice i { color:#f59e0b; font-size:16px; flex-shrink:0; margin-top:1px; }
.no-banners-notice span { font-size:12px; color:#78350f; }

/* ── Drag save toast indicator ──────────────────────── */
.drag-saving { position:fixed; bottom:20px; left:50%; transform:translateX(-50%); background:#1e293b; color:#fff; padding:10px 20px; border-radius:12px; font-size:13px; font-weight:600; display:flex; align-items:center; gap:8px; z-index:9999; opacity:0; transition:opacity .2s; pointer-events:none; box-shadow:0 8px 30px rgba(0,0,0,.2); }
.drag-saving.visible { opacity:1; }
</style>
@endpush

@section('content')

@php
$totalSec  = $sections->count();
$activeSec = $sections->where('is_active', true)->count();
$hiddenSec = $sections->where('is_active', false)->count();
$promoSlot = $sections->where('key', 'promo_banner')->count();

$secMeta = [
    'hero_slider'       => ['gradient'=>'linear-gradient(135deg,#6c63ff,#a78bfa)', 'icon'=>'bx bx-slideshow',        'label'=>'Hero Slider'],
    'categories'        => ['gradient'=>'linear-gradient(135deg,#0ea5e9,#38bdf8)', 'icon'=>'bx bx-grid-alt',         'label'=>'Shop by Category'],
    'special_offers'    => ['gradient'=>'linear-gradient(135deg,#f43f5e,#fb923c)', 'icon'=>'bx bx-purchase-tag-alt', 'label'=>'Special Offers'],
    'featured_products' => ['gradient'=>'linear-gradient(135deg,#f59e0b,#fbbf24)', 'icon'=>'bx bx-star',             'label'=>'Featured Products'],
    'promo_banner'      => ['gradient'=>'linear-gradient(135deg,#10b981,#34d399)', 'icon'=>'bx bx-megaphone',        'label'=>'Promo Banner'],
];
@endphp

{{-- ── Page heading ── --}}
<div class="hs-page-head">
    <h4>
        <span class="head-icon"><i class="bx bx-layout"></i></span>
        Home Page Sections
    </h4>
    <a href="{{ route('home') }}" target="_blank" class="btn-preview">
        <i class="bx bx-link-external"></i> Preview Storefront
    </a>
</div>

{{-- ── Stats row ── --}}
<div class="hs-stats">
    <div class="hs-stat hs-stat--all">
        <div class="hs-stat__top">
            <div>
                <div class="hs-stat__val">{{ $totalSec }}</div>
                <div class="hs-stat__lbl">Total Sections</div>
            </div>
            <div class="hs-stat__icon"><i class="bx bx-layer"></i></div>
        </div>
        <div class="hs-stat__bar"></div>
        <div class="hs-stat__foot"><i class="bx bx-info-circle"></i> All home page blocks</div>
    </div>
    <div class="hs-stat hs-stat--on">
        <div class="hs-stat__top">
            <div>
                <div class="hs-stat__val">{{ $activeSec }}</div>
                <div class="hs-stat__lbl">Visible Sections</div>
            </div>
            <div class="hs-stat__icon"><i class="bx bx-show"></i></div>
        </div>
        <div class="hs-stat__bar"></div>
        <div class="hs-stat__foot"><i class="bx bx-check-circle"></i> Shown to visitors</div>
    </div>
    <div class="hs-stat hs-stat--off">
        <div class="hs-stat__top">
            <div>
                <div class="hs-stat__val">{{ $hiddenSec }}</div>
                <div class="hs-stat__lbl">Hidden Sections</div>
            </div>
            <div class="hs-stat__icon"><i class="bx bx-hide"></i></div>
        </div>
        <div class="hs-stat__bar"></div>
        <div class="hs-stat__foot"><i class="bx bx-minus-circle"></i> Not shown to visitors</div>
    </div>
    <div class="hs-stat hs-stat--promo">
        <div class="hs-stat__top">
            <div>
                <div class="hs-stat__val">{{ $promoSlot }}</div>
                <div class="hs-stat__lbl">Promo Banner Slots</div>
            </div>
            <div class="hs-stat__icon"><i class="bx bx-megaphone"></i></div>
        </div>
        <div class="hs-stat__bar"></div>
        <div class="hs-stat__foot"><i class="bx bx-image-alt"></i> Dynamic banner positions</div>
    </div>
</div>

{{-- ── Main grid ── --}}
<div class="row g-4">

    {{-- LEFT: Sortable sections ── --}}
    <div class="col-xl-8">
        <div class="hs-card">
            <div class="hs-card__head">
                <div class="hs-card__title">
                    <i class="bx bx-list-ul"></i>
                    Home Page Layout
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="text-muted" style="font-size:12px; display:flex; align-items:center; gap:4px;">
                        <i class="bx bx-mouse-alt"></i> Drag to reorder
                    </span>
                </div>
            </div>
            <div class="hs-info">
                <i class="bx bx-info-circle"></i>
                Drag rows to reorder sections on the storefront. Toggle the switch to show or hide. <strong style="color:#6c63ff;">Core sections</strong> (marked <span class="badge-core">core</span>) cannot be deleted.
            </div>

            <div style="overflow-x:auto;">
                <table class="sec-table">
                    <thead>
                        <tr>
                            <th style="width:44px"></th>
                            <th style="width:44px">#</th>
                            <th style="width:220px">Section</th>
                            <th>Label / Configuration</th>
                            <th style="width:78px; text-align:center">Visible</th>
                            <th style="width:56px"></th>
                        </tr>
                    </thead>
                    <tbody id="sortable-body">
                        @foreach ($sections as $section)
                        @php
                            $sm = $secMeta[$section->key] ?? ['gradient'=>'linear-gradient(135deg,#64748b,#94a3b8)','icon'=>'bx bx-layout','label'=>ucfirst(str_replace('_',' ',$section->key))];
                        @endphp
                        <tr data-id="{{ $section->id }}" class="section-row">
                            <td>
                                <div class="sec-row" style="pointer-events:none; height:100%;">
                                    <div class="sec-col-grip">
                                        <i class="bx bx-grid-vertical drag-grip" style="pointer-events:all;"></i>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="sec-col-num">
                                    <span class="order-num sort-index">{{ $loop->iteration }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="sec-col-type">
                                    <div class="sec-type">
                                        <div class="sec-type__icon" style="background:{{ $sm['gradient'] }};">
                                            <i class="{{ $sm['icon'] }}"></i>
                                        </div>
                                        <div>
                                            <div class="sec-type__name">{{ $sm['label'] }}</div>
                                            <div class="sec-type__badges">
                                                <span class="badge-key">{{ $section->key }}</span>
                                                @if($section->isFixed())
                                                    <span class="badge-core">core</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="sec-col-config">
                                    <form action="{{ route('admin.home-sections.update', $section) }}" method="POST">
                                        @csrf @method('PUT')
                                        <div class="cfg-form">
                                            <input type="text" name="title" class="cfg-input"
                                                   value="{{ $section->title }}" placeholder="Section label">

                                            @if($section->key === 'promo_banner')
                                            <select name="promo_banner_id" class="cfg-select">
                                                <option value="">— Random active banner —</option>
                                                @foreach($banners as $b)
                                                <option value="{{ $b->id }}" {{ $section->promo_banner_id == $b->id ? 'selected' : '' }}>
                                                    {{ $b->title }}
                                                </option>
                                                @endforeach
                                            </select>
                                            <div class="cfg-limit-wrap">
                                                <span class="cfg-limit-lbl">Show</span>
                                                <input type="number" name="banner_limit" class="cfg-limit-input"
                                                       min="1" max="10" value="{{ $section->getBannerLimit() }}">
                                                <span class="cfg-limit-lbl">banner(s)</span>
                                            </div>
                                            @endif

                                            <button type="submit" class="btn-save">Save</button>
                                        </div>

                                        {{-- Banner status line for promo sections --}}
                                        @if($section->key === 'promo_banner')
                                        <div class="cfg-status">
                                            @if($section->promoBanner)
                                                <span class="banner-swatch-mini"
                                                      style="background:{{ $section->promoBanner->bgGradient() }};"></span>
                                                <span class="pill-specific">
                                                    <i class="bx bx-image-alt"></i>
                                                    {{ Str::limit($section->promoBanner->title, 28) }}
                                                </span>
                                            @else
                                                <span class="pill-random">
                                                    <i class="bx bx-shuffle"></i> Random active banner
                                                </span>
                                            @endif
                                            @if($section->getBannerLimit() > 1)
                                                <span class="pill-limit">
                                                    <i class="bx bx-copy-alt"></i>
                                                    {{ $section->getBannerLimit() }}× banners
                                                </span>
                                            @endif
                                        </div>
                                        @endif
                                    </form>
                                </div>
                            </td>
                            <td>
                                <div class="sec-col-vis">
                                    <form action="{{ route('admin.home-sections.toggle', $section) }}" method="POST" class="vis-form">
                                        @csrf @method('PUT')
                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input" type="checkbox"
                                                   onchange="this.form.submit()"
                                                   {{ $section->is_active ? 'checked' : '' }}>
                                        </div>
                                    </form>
                                </div>
                            </td>
                            <td>
                                <div class="sec-col-act">
                                    @if(!$section->isFixed())
                                    <form action="{{ route('admin.home-sections.destroy', $section) }}"
                                          method="POST"
                                          onsubmit="return confirm('Remove this banner section?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-del-row" title="Remove section">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </form>
                                    @else
                                    <span style="width:32px;height:32px;display:flex;align-items:center;justify-content:center;color:#e2e8f0;">
                                        <i class="bx bx-lock-alt" title="Core section"></i>
                                    </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- RIGHT: Add section + How it works ── --}}
    <div class="col-xl-4">

        {{-- Add Promo Banner --}}
        <div class="rp-card">
            <div class="rp-card__head">
                <div class="rp-card__head-icon" style="background:linear-gradient(135deg,#10b981,#34d399);">
                    <i class="bx bx-megaphone"></i>
                </div>
                <div>
                    <div class="rp-card__head-title">Add Promo Banner Section</div>
                    <div class="rp-card__head-sub">Insert a banner slot at the bottom of the page</div>
                </div>
            </div>
            <div class="rp-card__body">
                <form action="{{ route('admin.home-sections.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="rp-label">Section Label <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="rp-input @error('title') is-invalid @enderror"
                               placeholder="e.g. Mid-Page Banner" value="{{ old('title') }}" required>
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="rp-label">Banner to Show</label>
                        <select name="promo_banner_id" class="rp-select">
                            <option value="">— Random active banner —</option>
                            @foreach($banners as $b)
                            <option value="{{ $b->id }}" {{ old('promo_banner_id') == $b->id ? 'selected' : '' }}>
                                {{ $b->title }}
                            </option>
                            @endforeach
                        </select>
                        <div class="rp-hint">Leave blank to rotate randomly on each visit.</div>
                    </div>

                    <button type="submit" class="btn-add-sec">
                        <span class="btn-icon"><i class="bx bx-plus"></i></span>
                        Add Section to Homepage
                    </button>
                </form>

                @if($banners->isEmpty())
                <div class="no-banners-notice">
                    <i class="bx bx-info-circle"></i>
                    <span>No active banners yet. <a href="{{ route('admin.promo-banners.create') }}" class="fw-bold text-warning">Create a promo banner</a> first, then add it here.</span>
                </div>
                @endif
            </div>
        </div>

        {{-- How it works --}}
        <div class="rp-card">
            <div class="rp-card__head">
                <div class="rp-card__head-icon" style="background:linear-gradient(135deg,#6c63ff,#a78bfa);">
                    <i class="bx bx-bulb"></i>
                </div>
                <div>
                    <div class="rp-card__head-title">How it works</div>
                    <div class="rp-card__head-sub">Managing your homepage layout</div>
                </div>
            </div>
            <div class="rp-card__body" style="padding-top:10px;">
                <div class="how-step">
                    <div class="how-num">1</div>
                    <div class="how-text">
                        <strong>Drag to reorder</strong>
                        <span>Grab the ⠿ handle on any row and drag it to the desired position. Order saves automatically.</span>
                    </div>
                </div>
                <div class="how-step">
                    <div class="how-num">2</div>
                    <div class="how-text">
                        <strong>Toggle visibility</strong>
                        <span>Use the switch to instantly show or hide any section without deleting it.</span>
                    </div>
                </div>
                <div class="how-step">
                    <div class="how-num">3</div>
                    <div class="how-text">
                        <strong>Set banner limit</strong>
                        <span>For promo slots, set how many banners to stack. Use "Show 2 banners" to display 2 full-width banners in that slot.</span>
                    </div>
                </div>
                <div class="how-step">
                    <div class="how-num">4</div>
                    <div class="how-text">
                        <strong>Core sections</strong>
                        <span>Hero, Categories, Deals & Featured are core — always available but cannot be deleted. Only promo banner slots can be removed.</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section type legend --}}
        <div class="rp-card">
            <div class="rp-card__head">
                <div class="rp-card__head-icon" style="background:linear-gradient(135deg,#f43f5e,#fb923c);">
                    <i class="bx bx-palette"></i>
                </div>
                <div>
                    <div class="rp-card__head-title">Section Types</div>
                    <div class="rp-card__head-sub">Color reference guide</div>
                </div>
            </div>
            <div class="rp-card__body" style="padding-top:12px;">
                @foreach($secMeta as $key => $meta)
                <div class="d-flex align-items-center gap-3 mb-3 {{ $loop->last ? 'mb-0' : '' }}">
                    <div style="width:32px;height:32px;border-radius:9px;background:{{ $meta['gradient'] }};display:flex;align-items:center;justify-content:center;color:#fff;font-size:14px;flex-shrink:0;box-shadow:0 3px 10px rgba(0,0,0,.12);">
                        <i class="{{ $meta['icon'] }}"></i>
                    </div>
                    <div>
                        <div style="font-size:12.5px;font-weight:700;color:#1e293b;">{{ $meta['label'] }}</div>
                        <code style="font-size:10px;color:#94a3b8;">{{ $key }}</code>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

    </div>
</div>

{{-- Drag save indicator --}}
<div class="drag-saving" id="dragSavingToast">
    <i class="bx bx-check-circle" style="color:#34d399;font-size:16px;"></i>
    Section order saved
</div>

{{-- Reorder success toast (Bootstrap) --}}
<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index:9999">
    <div id="reorderToast" class="toast align-items-center border-0 text-white bg-success" role="alert" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body fw-semibold">
                <i class="bx bx-check-circle me-1 fs-16"></i> Section order saved.
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
(function () {
    const body    = document.getElementById('sortable-body');
    let dragged   = null;
    let dragOver  = null;

    // Make all rows draggable
    body.querySelectorAll('tr').forEach(tr => tr.setAttribute('draggable', 'true'));

    body.addEventListener('dragstart', e => {
        dragged = e.target.closest('tr');
        if (!dragged) return;
        dragged.classList.add('dragging');
        e.dataTransfer.effectAllowed = 'move';
        e.dataTransfer.setDragImage(dragged, 0, 0);
    });

    body.addEventListener('dragend', () => {
        if (dragged) dragged.classList.remove('dragging');
        if (dragOver) dragOver.classList.remove('drag-over');
        dragged = dragOver = null;
        updateIndexes();
    });

    body.addEventListener('dragover', e => {
        e.preventDefault();
        const target = e.target.closest('tr');
        if (!target || target === dragged) return;
        if (dragOver && dragOver !== target) dragOver.classList.remove('drag-over');
        dragOver = target;
        dragOver.classList.add('drag-over');
        const rect  = target.getBoundingClientRect();
        const after = (e.clientY - rect.top) / rect.height > 0.5;
        body.insertBefore(dragged, after ? target.nextSibling : target);
    });

    body.addEventListener('drop', e => {
        e.preventDefault();
        if (dragOver) dragOver.classList.remove('drag-over');
        saveOrder();
    });

    function updateIndexes() {
        body.querySelectorAll('tr .sort-index').forEach((el, i) => el.textContent = i + 1);
    }

    function saveOrder() {
        const ids  = [...body.querySelectorAll('tr')].map(tr => tr.dataset.id);
        const toast = document.getElementById('dragSavingToast');

        toast.classList.add('visible');

        fetch('{{ route('admin.home-sections.reorder') }}', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify({ order: ids }),
        }).then(r => {
            setTimeout(() => toast.classList.remove('visible'), 1800);
            if (r.ok) {
                const bsToast = document.getElementById('reorderToast');
                if (bsToast) bootstrap.Toast.getOrCreateInstance(bsToast, { delay: 2500 }).show();
            }
        }).catch(() => setTimeout(() => toast.classList.remove('visible'), 1800));
    }
})();
</script>
@endpush
