@extends('admin.layouts.app')
@section('title', 'Promo Banners')
@section('page_title', '')
@section('breadcrumb', '')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
<style>.page-header-breadcrumb { display:none !important; }</style>
<style>
.stat-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:22px; margin-bottom:28px; }
.stat-card { border-radius:20px; padding:0; background:#fff; box-shadow:0 4px 24px rgba(0,0,0,.08); overflow:hidden; position:relative; transition:transform .2s,box-shadow .2s; }
.stat-card:hover { transform:translateY(-4px); box-shadow:0 12px 36px rgba(0,0,0,.14); }
.stat-card__top { padding:22px 22px 14px; display:flex; align-items:flex-start; justify-content:space-between; gap:12px; }
.stat-card__icon { width:56px; height:56px; border-radius:16px; display:flex; align-items:center; justify-content:center; font-size:24px; flex-shrink:0; }
.stat-card__value { font-size:38px; font-weight:900; line-height:1; color:#1e293b; }
.stat-card__label { font-size:13px; font-weight:600; color:#64748b; margin-top:5px; }
.stat-card__bar { height:6px; }
.stat-card__footer { padding:10px 22px 14px; display:flex; align-items:center; gap:6px; font-size:12px; color:#94a3b8; border-top:1px solid #f1f5f9; }
.stat-card--total  .stat-card__icon { background:linear-gradient(135deg,#6c63ff,#a78bfa); color:#fff; }
.stat-card--total  .stat-card__bar  { background:linear-gradient(90deg,#6c63ff,#a78bfa); }
.stat-card--active .stat-card__icon { background:linear-gradient(135deg,#10b981,#34d399); color:#fff; }
.stat-card--active .stat-card__bar  { background:linear-gradient(90deg,#10b981,#34d399); }
.stat-card--hidden .stat-card__icon { background:linear-gradient(135deg,#64748b,#94a3b8); color:#fff; }
.stat-card--hidden .stat-card__bar  { background:linear-gradient(90deg,#64748b,#94a3b8); }

.filter-card { background:#fff; border-radius:18px; padding:22px 24px; margin-bottom:24px; box-shadow:0 4px 24px rgba(0,0,0,.07); }
.filter-title { font-size:12px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:.07em; margin-bottom:16px; display:flex; align-items:center; gap:8px; }
.filter-row { display:grid; grid-template-columns:1fr 1fr auto; gap:14px; align-items:end; }
.filter-group label { display:block; font-size:12px; font-weight:600; color:#475569; margin-bottom:6px; }
.filter-select { width:100%; padding:10px 32px 10px 12px; border:1.5px solid #e2e8f0; border-radius:10px; font-size:13px; color:#374151; background:#f8fafc; appearance:none; background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%2394a3b8' stroke-width='1.5' fill='none'/%3E%3C/svg%3E"); background-repeat:no-repeat; background-position:right 12px center; }
.filter-btn-wrap { display:flex; gap:8px; }
.btn-apply { padding:11px 22px; border:none; border-radius:12px; font-size:13.5px; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:9px; color:#fff; background:linear-gradient(135deg,#10b981,#059669); box-shadow:0 4px 14px rgba(16,185,129,.35); transition:transform .22s,box-shadow .22s; }
.btn-apply:hover { transform:translateY(-3px); box-shadow:0 8px 24px rgba(16,185,129,.5); }
.btn-reset { padding:11px 18px; border:none; border-radius:12px; font-size:13.5px; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:8px; color:#fff; background:linear-gradient(135deg,#f43f5e,#fb923c); box-shadow:0 4px 14px rgba(244,63,94,.32); transition:transform .22s,box-shadow .22s; }
.btn-reset:hover { transform:translateY(-3px); }

.table-card { background:#fff; border-radius:18px; box-shadow:0 4px 24px rgba(0,0,0,.07); overflow:hidden; }
.table-card-head { display:flex; align-items:center; justify-content:space-between; padding:20px 24px; border-bottom:1px solid #f1f5f9; }
.table-card-title { font-size:16px; font-weight:700; color:#1e293b; display:flex; align-items:center; gap:10px; }
.btn-add { display:inline-flex; align-items:center; gap:10px; padding:12px 22px; background:linear-gradient(135deg,#6c63ff,#5a7cff 45%,#a78bfa); color:#fff; border-radius:14px; font-size:14px; font-weight:700; text-decoration:none; box-shadow:0 4px 16px rgba(108,99,255,.4); transition:transform .25s,box-shadow .25s; }
.btn-add:hover { transform:translateY(-3px) scale(1.03); box-shadow:0 10px 28px rgba(108,99,255,.55); color:#fff; text-decoration:none; }
.btn-add .icon { width:28px; height:28px; background:rgba(255,255,255,.25); border-radius:9px; display:flex; align-items:center; justify-content:center; }
.table-card-body { padding:16px 24px 24px; }

#bannersTable { width:100% !important; border-collapse:separate; border-spacing:0; }
#bannersTable thead th { background:#f8fafc; color:#475569; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.07em; padding:13px 16px; border-bottom:2px solid #e2e8f0; white-space:nowrap; }
#bannersTable tbody td { padding:14px 16px; vertical-align:middle; border-bottom:1px solid #f1f5f9; font-size:13.5px; }
#bannersTable tbody tr:last-child td { border-bottom:none; }
#bannersTable tbody tr:hover td { background:#fafbff; }
.dataTables_wrapper .dataTables_filter input { border:1.5px solid #e2e8f0; border-radius:8px; padding:7px 12px; font-size:13px; margin-left:6px; }
.dataTables_wrapper .dataTables_length select { border:1.5px solid #e2e8f0; border-radius:8px; padding:5px 28px 5px 10px; font-size:13px; }
.dataTables_wrapper .dataTables_paginate .paginate_button { border-radius:8px !important; font-size:13px; padding:5px 12px !important; }
.dataTables_wrapper .dataTables_paginate .paginate_button.current,
.dataTables_wrapper .dataTables_paginate .paginate_button.current:hover { background:linear-gradient(135deg,#6c63ff,#a78bfa) !important; border-color:transparent !important; color:#fff !important; }
.dt-top { display:flex; align-items:center; justify-content:space-between; margin-bottom:14px; flex-wrap:wrap; gap:10px; }

.banner-swatch { width:100px; height:48px; border-radius:10px; display:flex; align-items:center; justify-content:center; overflow:hidden; border:1px solid rgba(0,0,0,.06); }
.banner-swatch span { font-size:8px; font-weight:700; color:rgba(255,255,255,.7); text-align:center; padding:0 4px; line-height:1.3; }
.slide-title-txt { font-weight:700; color:#1e293b; font-size:13.5px; }
.slide-eyebrow { font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:#6c63ff; margin-bottom:2px; }
.slide-sub { font-size:11.5px; color:#64748b; margin-top:2px; }
.slide-btn-preview { font-size:11px; color:#94a3b8; margin-top:3px; }
.placement-badge { display:inline-flex; align-items:center; gap:4px; font-size:10px; font-weight:700; padding:2px 9px; border-radius:20px; margin-bottom:4px; }
.placement-top    { background:#ede9fe; color:#6c63ff; }
.placement-bottom { background:#fef3c7; color:#b45309; }
.order-badge { display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; background:linear-gradient(135deg,#f1f5f9,#e2e8f0); border-radius:8px; font-size:14px; font-weight:800; color:#334155; }
.b-approved { background:#d1fae5; color:#065f46; padding:4px 12px; border-radius:20px; font-size:12px; font-weight:700; display:inline-flex; align-items:center; gap:5px; }
.b-off      { background:#f1f5f9; color:#94a3b8; padding:4px 12px; border-radius:20px; font-size:12px; font-weight:700; display:inline-flex; align-items:center; gap:5px; }
.act-wrap { display:flex; align-items:center; gap:6px; }
.btn-act { width:32px; height:32px; border-radius:8px; display:inline-flex; align-items:center; justify-content:center; font-size:13px; cursor:pointer; border:none; text-decoration:none; transition:transform .12s; }
.btn-act:hover { transform:scale(1.12); }
.btn-edit { background:#dbeafe; color:#1d4ed8; }
.btn-del  { background:#fee2e2; color:#b91c1c; }
.page-heading { display:flex; align-items:center; justify-content:space-between; margin-bottom:28px; }
.page-heading h4 { font-size:22px; font-weight:800; color:#1e293b; margin:0; display:flex; align-items:center; gap:10px; }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-4">

    <div class="page-heading">
        <div>
            <h4><i class="bi bi-megaphone-fill" style="color:#6c63ff;"></i> Promo Banners</h4>
            <nav style="margin-top:4px;">
                <ol class="breadcrumb mb-0" style="font-size:12.5px;">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin</a></li>
                    <li class="breadcrumb-item">Pages</li>
                    <li class="breadcrumb-item active">Promo Banners</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.promo-banners.create') }}" class="btn-add">
            <span class="icon"><i class="bi bi-plus-lg"></i></span>
            Add Banner
        </a>
    </div>

    {{-- Stat cards --}}
    <div class="stat-grid">
        @php
        $cards = [
            ['cls'=>'total',  'icon'=>'bi-megaphone-fill', 'value'=>$stats['total'],  'label'=>'Total Banners',  'foot'=>'All banners including hidden'],
            ['cls'=>'active', 'icon'=>'bi-eye-fill',       'value'=>$stats['active'], 'label'=>'Active',         'foot'=>'Currently showing on storefront'],
            ['cls'=>'hidden', 'icon'=>'bi-eye-slash-fill', 'value'=>$stats['total'] - $stats['active'], 'label'=>'Hidden', 'foot'=>'Not visible to shoppers'],
        ];
        @endphp
        @foreach($cards as $c)
        <div class="stat-card stat-card--{{ $c['cls'] }}">
            <div class="stat-card__bar"></div>
            <div class="stat-card__top">
                <div>
                    <div class="stat-card__value">{{ $c['value'] }}</div>
                    <div class="stat-card__label">{{ $c['label'] }}</div>
                </div>
                <div class="stat-card__icon"><i class="bi {{ $c['icon'] }}"></i></div>
            </div>
            <div class="stat-card__footer"><i class="bi bi-info-circle"></i> {{ $c['foot'] }}</div>
        </div>
        @endforeach
    </div>

    {{-- Filters --}}
    <div class="filter-card">
        <div class="filter-title"><i class="bi bi-funnel-fill" style="color:#6c63ff;"></i> Filter Banners</div>
        <div class="filter-row">
            <div class="filter-group">
                <label>Status</label>
                <select id="f-status" class="filter-select">
                    <option value="">All Statuses</option>
                    <option value="active">Active</option>
                    <option value="hidden">Hidden</option>
                </select>
            </div>
            <div class="filter-group">
                <label>Placement</label>
                <select id="f-placement" class="filter-select">
                    <option value="">All Placements</option>
                    <option value="top">Top — between categories &amp; special offers</option>
                    <option value="bottom">Bottom — after featured products</option>
                </select>
            </div>
            <div class="filter-btn-wrap">
                <button class="btn-apply" id="btn-apply"><i class="bi bi-search"></i> Apply Filters</button>
                <button class="btn-reset" id="btn-reset"><i class="bi bi-arrow-counterclockwise"></i> Reset</button>
            </div>
        </div>
    </div>

    {{-- DataTable --}}
    <div class="table-card">
        <div class="table-card-head">
            <div class="table-card-title"><i class="bi bi-table" style="color:#6c63ff;"></i> Banner List</div>
            <small style="color:#94a3b8;font-size:12px;"><i class="bi bi-lightning-charge-fill" style="color:#f59e0b;"></i> Server-side · Yajra DataTables</small>
        </div>
        <div class="table-card-body">
            <table id="bannersTable" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th style="width:110px;">Preview</th>
                        <th>Content</th>
                        <th style="width:70px;">Image</th>
                        <th style="width:80px;">Order</th>
                        <th style="width:110px;">Status</th>
                        <th style="width:100px;" class="text-center">Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script>
$(function () {
    var table = $('#bannersTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route('admin.promo-banners.index') }}',
            type: 'GET',
            data: function (d) { d.status = $('#f-status').val(); d.placement = $('#f-placement').val(); }
        },
        columns: [
            { data: 'preview_html', name: 'bg_color_start', orderable: false, searchable: false },
            { data: 'content_html', name: 'title',          orderable: false },
            { data: 'image_html',   name: 'image_path',     orderable: false, searchable: false },
            { data: 'order_html',   name: 'sort_order',     className: 'text-center' },
            { data: 'status_html',  name: 'is_active',      orderable: false, searchable: false },
            { data: 'actions',      name: 'actions',        orderable: false, searchable: false, className: 'text-center' },
        ],
        order: [[3, 'asc']],
        pageLength: 15,
        language: {
            search: '', searchPlaceholder: 'Search banners…',
            processing: '<span style="color:#6c63ff;">Loading…</span>',
            emptyTable: '<div style="padding:40px 0;text-align:center;color:#94a3b8;"><i class="bi bi-megaphone" style="font-size:32px;display:block;margin-bottom:10px;"></i>No banners found</div>',
            lengthMenu: 'Show _MENU_ entries',
            info: 'Showing _START_–_END_ of _TOTAL_ banners',
            paginate: { previous: '‹ Prev', next: 'Next ›' }
        },
        dom: '<"dt-top"lf>rt<"row mt-3"<"col-sm-6"i><"col-sm-6 d-flex justify-content-end"p>>',
    });
    $('#btn-apply').on('click', function () { table.ajax.reload(); });
    $('#btn-reset').on('click', function () { $('#f-status').val(''); $('#f-placement').val(''); table.ajax.reload(); });
});
</script>
@endpush
