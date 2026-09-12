@extends('admin.layouts.app')
@section('title', 'Products')
@section('page_title', '')
@section('breadcrumb', '')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
<style>
.page-header-breadcrumb { display:none !important; }

/* ── stat cards ── */
.stat-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:22px; margin-bottom:28px; }
.stat-card { border-radius:20px; background:#fff; box-shadow:0 4px 24px rgba(0,0,0,.08); overflow:hidden; transition:transform .2s,box-shadow .2s; cursor:default; }
.stat-card:hover { transform:translateY(-4px); box-shadow:0 12px 36px rgba(0,0,0,.14); }
.stat-card__bar  { height:6px; }
.stat-card__top  { padding:22px 22px 14px; display:flex; align-items:flex-start; justify-content:space-between; gap:12px; }
.stat-card__icon { width:56px; height:56px; border-radius:16px; display:flex; align-items:center; justify-content:center; font-size:24px; flex-shrink:0; }
.stat-card__value { font-size:38px; font-weight:900; line-height:1; color:#1e293b; }
.stat-card__label { font-size:13px; font-weight:600; color:#64748b; margin-top:5px; }
.stat-card__footer { padding:10px 22px 14px; display:flex; align-items:center; gap:6px; font-size:12px; color:#94a3b8; border-top:1px solid #f1f5f9; }

.stat-card--total    .stat-card__icon { background:linear-gradient(135deg,#6c63ff,#a78bfa); color:#fff; }
.stat-card--total    .stat-card__bar  { background:linear-gradient(90deg,#6c63ff,#a78bfa); }
.stat-card--total    .stat-card__footer i { color:#6c63ff; }
.stat-card--approved .stat-card__icon { background:linear-gradient(135deg,#10b981,#34d399); color:#fff; }
.stat-card--approved .stat-card__bar  { background:linear-gradient(90deg,#10b981,#34d399); }
.stat-card--approved .stat-card__footer i { color:#10b981; }
.stat-card--pending  .stat-card__icon { background:linear-gradient(135deg,#f59e0b,#fbbf24); color:#fff; }
.stat-card--pending  .stat-card__bar  { background:linear-gradient(90deg,#f59e0b,#fbbf24); }
.stat-card--pending  .stat-card__footer i { color:#f59e0b; }
.stat-card--featured .stat-card__icon { background:linear-gradient(135deg,#3b82f6,#60a5fa); color:#fff; }
.stat-card--featured .stat-card__bar  { background:linear-gradient(90deg,#3b82f6,#60a5fa); }
.stat-card--featured .stat-card__footer i { color:#3b82f6; }

/* ── filter card ── */
.filter-card { background:#fff; border-radius:18px; padding:22px 24px; margin-bottom:24px; box-shadow:0 4px 24px rgba(0,0,0,.07); }
.filter-title { font-size:12px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:.07em; margin-bottom:16px; display:flex; align-items:center; gap:8px; }
.filter-row { display:grid; grid-template-columns:2fr 1fr 1fr 1fr auto; gap:14px; align-items:end; }
.filter-group label { display:block; font-size:12px; font-weight:600; color:#475569; margin-bottom:6px; }
.filter-input,.filter-select { width:100%; padding:10px 12px; border:1.5px solid #e2e8f0; border-radius:10px; font-size:13px; color:#374151; background:#f8fafc; transition:border-color .15s; outline:none; box-sizing:border-box; }
.filter-select { padding-right:32px; appearance:none; background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%2394a3b8' stroke-width='1.5' fill='none'/%3E%3C/svg%3E"); background-repeat:no-repeat; background-position:right 12px center; cursor:pointer; }
.filter-select:focus,.filter-input:focus { border-color:#6c63ff; background:#fff; }
.filter-btn-wrap { display:flex; gap:8px; }
.btn-apply { padding:11px 22px; border:none; border-radius:12px; font-size:13.5px; font-weight:700; cursor:pointer; white-space:nowrap; display:inline-flex; align-items:center; gap:9px; color:#fff; position:relative; overflow:hidden; background:linear-gradient(135deg,#10b981 0%,#059669 100%); box-shadow:0 4px 14px rgba(16,185,129,.35); transition:transform .22s,box-shadow .22s; }
.btn-apply::after { content:''; position:absolute; inset:0; background:linear-gradient(90deg,transparent,rgba(255,255,255,.18),transparent); transform:translateX(-100%); transition:transform .55s ease; }
.btn-apply:hover::after { transform:translateX(100%); }
.btn-apply:hover { transform:translateY(-3px); box-shadow:0 8px 24px rgba(16,185,129,.5); }
.btn-apply i { position:relative; z-index:1; transition:transform .3s; }
.btn-apply:hover i { transform:scale(1.25); }
.btn-apply span { position:relative; z-index:1; }
.btn-reset { padding:11px 18px; border:none; border-radius:12px; font-size:13.5px; font-weight:700; cursor:pointer; white-space:nowrap; display:inline-flex; align-items:center; gap:8px; color:#fff; background:linear-gradient(135deg,#f43f5e 0%,#fb923c 100%); box-shadow:0 4px 14px rgba(244,63,94,.32); transition:transform .22s,box-shadow .22s; }
.btn-reset:hover { transform:translateY(-3px); box-shadow:0 8px 24px rgba(244,63,94,.5); }
.btn-reset i { transition:transform .45s ease; }
.btn-reset:hover i { transform:rotate(-200deg); }

/* ── table card ── */
.table-card { background:#fff; border-radius:18px; box-shadow:0 4px 24px rgba(0,0,0,.07); overflow:hidden; }
.table-card-head { display:flex; align-items:center; justify-content:space-between; padding:20px 24px; border-bottom:1px solid #f1f5f9; }
.table-card-title { font-size:16px; font-weight:700; color:#1e293b; display:flex; align-items:center; gap:10px; }
.table-card-title i { color:#6c63ff; }
.table-card-body { padding:16px 24px 24px; }
.btn-add { display:inline-flex; align-items:center; gap:10px; padding:12px 22px; background:linear-gradient(135deg,#6c63ff 0%,#5a7cff 45%,#a78bfa 100%); color:#fff; border-radius:14px; font-size:14px; font-weight:700; text-decoration:none; letter-spacing:.02em; position:relative; overflow:hidden; box-shadow:0 4px 16px rgba(108,99,255,.4); transition:transform .25s,box-shadow .25s,color 0s; }
.btn-add::after { content:''; position:absolute; inset:0; background:linear-gradient(90deg,transparent,rgba(255,255,255,.22),transparent); transform:translateX(-100%); transition:transform .55s ease; }
.btn-add:hover::after { transform:translateX(100%); }
.btn-add:hover { transform:translateY(-3px) scale(1.03); box-shadow:0 10px 28px rgba(108,99,255,.55); color:#fff; text-decoration:none; }
.btn-add .btn-add-icon { width:28px; height:28px; background:rgba(255,255,255,.25); border-radius:9px; display:flex; align-items:center; justify-content:center; font-size:15px; flex-shrink:0; transition:transform .35s ease; position:relative; z-index:1; }
.btn-add:hover .btn-add-icon { transform:rotate(90deg); }
.btn-add .btn-add-label { position:relative; z-index:1; }

/* ── DataTable ── */
#prodTable { width:100% !important; border-collapse:separate; border-spacing:0; }
#prodTable thead th { background:#f8fafc; color:#475569; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.07em; padding:13px 16px; border-bottom:2px solid #e2e8f0; white-space:nowrap; }
#prodTable tbody td { padding:12px 16px; vertical-align:middle; border-bottom:1px solid #f1f5f9; font-size:13.5px; color:#374151; }
#prodTable tbody tr:last-child td { border-bottom:none; }
#prodTable tbody tr:hover td { background:#fafbff; }
.dataTables_wrapper .dataTables_filter input { border:1.5px solid #e2e8f0; border-radius:8px; padding:7px 12px; font-size:13px; margin-left:6px; outline:none; }
.dataTables_wrapper .dataTables_filter input:focus { border-color:#6c63ff; }
.dataTables_wrapper .dataTables_length select { border:1.5px solid #e2e8f0; border-radius:8px; padding:5px 28px 5px 10px; font-size:13px; }
.dataTables_wrapper .dataTables_paginate .paginate_button { border-radius:8px !important; font-size:13px; padding:5px 12px !important; }
.dataTables_wrapper .dataTables_paginate .paginate_button.current,
.dataTables_wrapper .dataTables_paginate .paginate_button.current:hover { background:linear-gradient(135deg,#6c63ff,#a78bfa) !important; border-color:transparent !important; color:#fff !important; }
.dataTables_wrapper .dataTables_paginate .paginate_button:hover { background:#f1f5f9 !important; border-color:transparent !important; color:#374151 !important; }
.dataTables_wrapper .dataTables_info { font-size:13px; color:#64748b; }
.dataTables_wrapper .dataTables_processing { background:rgba(255,255,255,.95); box-shadow:0 4px 20px rgba(0,0,0,.1); border-radius:12px; font-size:13px; color:#6c63ff; padding:16px 32px; }
.dt-top { display:flex; align-items:center; justify-content:space-between; margin-bottom:14px; flex-wrap:wrap; gap:10px; }

/* ── cell widgets ── */
.prod-cell { display:flex; align-items:center; gap:12px; }
.prod-thumb { width:48px; height:48px; border-radius:10px; object-fit:cover; border:1.5px solid #e2e8f0; flex-shrink:0; }
.prod-avatar { width:48px; height:48px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:20px; font-weight:800; color:#fff; flex-shrink:0; background:hsl(var(--av-hue,260),60%,58%); }
.prod-info { min-width:0; }
.prod-name { display:block; font-weight:700; color:#1e293b; font-size:13.5px; white-space:normal; line-height:1.3; }
.prod-sku  { display:block; font-size:11px; color:#94a3b8; font-family:monospace; margin-top:2px; }
.cat-pill  { background:#ede9fe; color:#4f46e5; padding:3px 10px; border-radius:20px; font-size:12px; font-weight:600; white-space:nowrap; }
.price-main { font-weight:800; color:#1e293b; font-size:14px; }
.price-old  { display:block; font-size:11px; color:#94a3b8; text-decoration:line-through; }
.price-sale { display:block; font-weight:800; color:#10b981; font-size:14px; }
.stock-ok   { color:#065f46; font-weight:700; display:inline-flex; align-items:center; gap:4px; font-size:13px; }
.stock-low  { color:#92400e; font-weight:700; display:inline-flex; align-items:center; gap:4px; font-size:13px; }
.stock-out  { color:#991b1b; font-weight:700; display:inline-flex; align-items:center; gap:4px; font-size:13px; }
.b-approved { background:#d1fae5; color:#065f46; padding:4px 10px; border-radius:20px; font-size:12px; font-weight:700; display:inline-flex; align-items:center; gap:5px; }
.b-pending  { background:#fef3c7; color:#92400e; padding:4px 10px; border-radius:20px; font-size:12px; font-weight:700; display:inline-flex; align-items:center; gap:5px; }
.b-rejected { background:#fee2e2; color:#991b1b; padding:4px 10px; border-radius:20px; font-size:12px; font-weight:700; display:inline-flex; align-items:center; gap:5px; }
.b-hidden   { background:#f1f5f9; color:#64748b; padding:3px 8px; border-radius:20px; font-size:11px; font-weight:600; display:inline-flex; align-items:center; gap:4px; margin-top:3px; }
.b-feat     { background:#fef3c7; color:#92400e; padding:3px 9px; border-radius:20px; font-size:11.5px; font-weight:600; display:inline-flex; align-items:center; gap:4px; }
.b-deal     { background:#fee2e2; color:#991b1b; padding:3px 9px; border-radius:20px; font-size:11.5px; font-weight:600; display:inline-flex; align-items:center; gap:4px; }
.b-auction  { background:#ede9fe; color:#4f46e5; padding:3px 9px; border-radius:20px; font-size:11.5px; font-weight:600; display:inline-flex; align-items:center; gap:4px; }
.b-fixed    { background:#f0fdf4; color:#166534; padding:3px 9px; border-radius:20px; font-size:11.5px; font-weight:600; display:inline-flex; align-items:center; gap:4px; }
.b-off      { background:#f1f5f9; color:#94a3b8; padding:4px 10px; border-radius:20px; font-size:11.5px; font-weight:600; display:inline-flex; align-items:center; gap:4px; }
.act-wrap   { display:flex; align-items:center; gap:6px; }
.btn-act    { width:32px; height:32px; border-radius:8px; display:inline-flex; align-items:center; justify-content:center; font-size:13px; cursor:pointer; border:none; text-decoration:none; transition:transform .12s,opacity .12s; }
.btn-act:hover { transform:scale(1.12); opacity:.88; }
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
            <h4><i class="bi bi-box-seam-fill" style="color:#6c63ff;"></i> Products</h4>
            <nav style="margin-top:4px;">
                <ol class="breadcrumb mb-0" style="font-size:12.5px;">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin</a></li>
                    <li class="breadcrumb-item">Catalog</li>
                    <li class="breadcrumb-item active">Products</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.products.create') }}" class="btn-add">
            <span class="btn-add-icon"><i class="bi bi-plus-lg"></i></span>
            <span class="btn-add-label">Add Product</span>
        </a>
    </div>

    {{-- Stat cards --}}
    <div class="stat-grid">
        @php
        $cards = [
            ['cls'=>'total',    'icon'=>'bi-box-seam-fill',      'value'=>$stats['total'],    'label'=>'Total Products',  'foot'=>'All products in catalogue'],
            ['cls'=>'approved', 'icon'=>'bi-check-circle-fill',  'value'=>$stats['approved'], 'label'=>'Approved',        'foot'=>'Live and visible to shoppers'],
            ['cls'=>'pending',  'icon'=>'bi-clock-fill',         'value'=>$stats['pending'],  'label'=>'Pending Review',  'foot'=>'Awaiting admin approval'],
            ['cls'=>'featured', 'icon'=>'bi-star-fill',          'value'=>$stats['featured'], 'label'=>'Featured',        'foot'=>'Highlighted in home widget'],
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
        <div class="filter-title"><i class="bi bi-funnel-fill" style="color:#6c63ff;"></i> Filter Products</div>
        <div class="filter-row">
            <div class="filter-group">
                <label>Search</label>
                <input type="text" id="f-q" class="filter-input" placeholder="Name or SKU…">
            </div>
            <div class="filter-group">
                <label>Status</label>
                <select id="f-status" class="filter-select">
                    <option value="">Any Status</option>
                    <option value="approved">Approved</option>
                    <option value="pending">Pending</option>
                    <option value="rejected">Rejected</option>
                </select>
            </div>
            <div class="filter-group">
                <label>Category</label>
                <select id="f-category" class="filter-select">
                    <option value="">All Categories</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->parent_id ? '— ' : '' }}{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <label>Listing Type</label>
                <select id="f-listing" class="filter-select">
                    <option value="">All Types</option>
                    <option value="fixed">Fixed Price</option>
                    <option value="auction">Auction</option>
                </select>
            </div>
            <div class="filter-btn-wrap">
                <button class="btn-apply" id="btn-apply">
                    <i class="bi bi-search"></i><span>Apply</span>
                </button>
                <button class="btn-reset" id="btn-reset">
                    <i class="bi bi-arrow-counterclockwise"></i><span>Reset</span>
                </button>
            </div>
        </div>
    </div>

    {{-- DataTable --}}
    <div class="table-card">
        <div class="table-card-head">
            <div class="table-card-title"><i class="bi bi-table"></i> Product List</div>
            <small style="color:#94a3b8;font-size:12px;"><i class="bi bi-lightning-charge-fill" style="color:#f59e0b;"></i> Server-side · Yajra DataTables</small>
        </div>
        <div class="table-card-body">
            <table id="prodTable" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th style="width:130px;">Category</th>
                        <th style="width:110px;">Price</th>
                        <th style="width:90px;">Stock</th>
                        <th style="width:140px;">Status</th>
                        <th style="width:180px;">Flags</th>
                        <th style="width:100px;">Actions</th>
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
    var table = $('#prodTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url : '{{ route('admin.products.index') }}',
            type: 'GET',
            data: function (d) {
                d.q            = $('#f-q').val();
                d.status       = $('#f-status').val();
                d.category     = $('#f-category').val();
                d.listing_type = $('#f-listing').val();
            },
            error: function (xhr) { console.error('DT error:', xhr.status, xhr.responseText); }
        },
        columns: [
            { data: 'product_html',  name: 'name',         orderable: true  },
            { data: 'category_html', name: 'category_id',  orderable: false, searchable: false },
            { data: 'price_html',    name: 'price',        orderable: true,  searchable: false },
            { data: 'stock_html',    name: 'stock',        orderable: true,  searchable: false },
            { data: 'status_html',   name: 'status',       orderable: false, searchable: false },
            { data: 'flags_html',    name: 'listing_type', orderable: false, searchable: false },
            { data: 'actions',       name: 'actions',      orderable: false, searchable: false, className: 'text-center' },
        ],
        pageLength : 15,
        lengthMenu : [[10, 15, 25, 50, -1], [10, 15, 25, 50, 'All']],
        language: {
            search: '', searchPlaceholder: 'Search table…',
            processing: '<span style="color:#6c63ff;">Loading…</span>',
            emptyTable : '<div style="padding:40px 0;text-align:center;color:#94a3b8;"><i class="bi bi-inbox fs-2" style="display:block;margin-bottom:10px;"></i>No products found</div>',
            zeroRecords: '<div style="padding:40px 0;text-align:center;color:#94a3b8;"><i class="bi bi-search fs-2" style="display:block;margin-bottom:10px;"></i>No matching products</div>',
            lengthMenu: 'Show _MENU_ entries',
            info: 'Showing _START_–_END_ of _TOTAL_ products',
            infoEmpty: 'No products available',
            paginate: { previous: '‹ Prev', next: 'Next ›' }
        },
        dom: '<"dt-top"lf>rt<"row mt-3"<"col-sm-6"i><"col-sm-6 d-flex justify-content-end"p>>',
    });

    $('#btn-apply').on('click', function () { table.ajax.reload(); });
    $('#btn-reset').on('click', function () {
        $('#f-q').val('');
        $('#f-status, #f-category, #f-listing').val('');
        table.ajax.reload();
    });

    // Reload on Enter in search box
    $('#f-q').on('keydown', function (e) { if (e.key === 'Enter') table.ajax.reload(); });
});
</script>
@endpush
