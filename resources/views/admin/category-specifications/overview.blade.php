@extends('admin.layouts.app')
@section('title', 'Item Specifications')
@section('page_title', '')
@section('breadcrumb', '')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
<style>
.page-header-breadcrumb { display:none !important; }

/* ── stat cards ── */
.stat-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:22px; margin-bottom:28px; }
.stat-card { border-radius:20px; background:#fff; box-shadow:0 4px 24px rgba(0,0,0,.08); overflow:hidden; transition:transform .2s,box-shadow .2s; }
.stat-card:hover { transform:translateY(-4px); box-shadow:0 12px 36px rgba(0,0,0,.14); }
.stat-card__bar { height:6px; }
.stat-card__top { padding:22px 22px 10px; display:flex; align-items:flex-start; justify-content:space-between; gap:12px; }
.stat-card__icon { width:56px; height:56px; border-radius:16px; display:flex; align-items:center; justify-content:center; font-size:24px; flex-shrink:0; }
.stat-card__value { font-size:38px; font-weight:900; line-height:1; color:#1e293b; }
.stat-card__label { font-size:13px; font-weight:600; color:#64748b; margin-top:5px; }
.stat-card__footer { padding:10px 22px 14px; display:flex; align-items:center; gap:6px; font-size:12px; color:#94a3b8; border-top:1px solid #f1f5f9; }
.stat-card--purple .stat-card__bar { background:linear-gradient(90deg,#6c63ff,#a78bfa); }
.stat-card--purple .stat-card__icon { background:linear-gradient(135deg,#6c63ff,#a78bfa); color:#fff; }
.stat-card--purple .stat-card__footer i { color:#6c63ff; }
.stat-card--green  .stat-card__bar { background:linear-gradient(90deg,#10b981,#34d399); }
.stat-card--green  .stat-card__icon { background:linear-gradient(135deg,#10b981,#34d399); color:#fff; }
.stat-card--green  .stat-card__footer i { color:#10b981; }
.stat-card--amber  .stat-card__bar { background:linear-gradient(90deg,#f59e0b,#fbbf24); }
.stat-card--amber  .stat-card__icon { background:linear-gradient(135deg,#f59e0b,#fbbf24); color:#fff; }
.stat-card--amber  .stat-card__footer i { color:#f59e0b; }
.stat-card--blue   .stat-card__bar { background:linear-gradient(90deg,#3b82f6,#60a5fa); }
.stat-card--blue   .stat-card__icon { background:linear-gradient(135deg,#3b82f6,#60a5fa); color:#fff; }
.stat-card--blue   .stat-card__footer i { color:#3b82f6; }

/* ── table card ── */
.table-card { background:#fff; border-radius:18px; box-shadow:0 4px 24px rgba(0,0,0,.07); overflow:hidden; }
.table-card-head { display:flex; align-items:center; justify-content:space-between; padding:20px 24px; border-bottom:1px solid #f1f5f9; flex-wrap:wrap; gap:12px; }
.table-card-title { font-size:16px; font-weight:700; color:#1e293b; display:flex; align-items:center; gap:10px; }
.table-card-title i { color:#6c63ff; }
.table-card-body { padding:16px 24px 24px; }

/* ── category avatar (dummy image) ── */
.cat-thumb  { width:44px; height:44px; border-radius:12px; object-fit:cover; border:2px solid #e2e8f0; display:block; }
.cat-avatar { width:44px; height:44px; border-radius:12px; display:flex; align-items:center; justify-content:center; color:#fff; font-size:15px; font-weight:800; flex-shrink:0; }

/* ── name ── */
.cat-title      { font-weight:700; color:#1e293b; font-size:14px; }
.cat-sub-count  { font-size:11.5px; color:#94a3b8; }

/* ── spec count badges ── */
.spec-count { display:inline-flex; align-items:center; gap:5px; font-size:12.5px; font-weight:700; padding:4px 11px; border-radius:8px; }
.spec-count.has  { background:#dcfce7; color:#166534; }
.spec-count.none { background:#fef9c3; color:#854d0e; }

/* ── sub-cat pills ── */
.sub-pills { display:flex; flex-wrap:wrap; gap:5px; }
.sub-pill { font-size:11px; font-weight:600; padding:3px 9px; border-radius:6px; text-decoration:none; transition:all .15s; }
.sub-pill.has-specs { background:#e0f2fe; color:#0369a1; }
.sub-pill.has-specs:hover { background:#bae6fd; }
.sub-pill.no-specs { background:#f1f5f9; color:#64748b; }
.sub-pill.no-specs:hover { background:#e2e8f0; color:#374151; }
.pill-badge { background:#0369a1; color:#fff; border-radius:4px; padding:0 4px; font-size:9px; margin-left:2px; }

/* ── action buttons ── */
.btn-specs { display:inline-flex; align-items:center; gap:6px; padding:7px 14px; border-radius:9px; font-size:12.5px; font-weight:700; text-decoration:none; transition:all .18s; white-space:nowrap; }
.btn-specs.edit { background:linear-gradient(135deg,#6c63ff,#a78bfa); color:#fff; box-shadow:0 2px 8px rgba(108,99,255,.3); }
.btn-specs.edit:hover { transform:translateY(-1px); box-shadow:0 4px 14px rgba(108,99,255,.45); color:#fff; }
.btn-specs.add  { background:#f1f5f9; color:#374151; border:1.5px solid #e2e8f0; }
.btn-specs.add:hover { background:#6c63ff; color:#fff; border-color:#6c63ff; transform:translateY(-1px); }

/* ── DataTables overrides ── */
#specTable { width:100% !important; border-collapse:separate; border-spacing:0; }
#specTable thead th { background:#f8fafc; color:#475569; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.07em; padding:13px 16px; border-bottom:2px solid #e2e8f0; white-space:nowrap; }
#specTable tbody td { padding:14px 16px; vertical-align:middle; border-bottom:1px solid #f1f5f9; font-size:13.5px; color:#374151; }
#specTable tbody tr:last-child td { border-bottom:none; }
#specTable tbody tr:hover td { background:#fafbff; }
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

@media(max-width:900px) { .stat-grid { grid-template-columns:1fr 1fr; } }
@media(max-width:576px) { .stat-grid { grid-template-columns:1fr; } }
</style>
@endpush

@section('content')

{{-- Page header --}}
<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:14px;margin-bottom:24px;">
    <div>
        <nav><ol class="breadcrumb mb-1" style="font-size:12.5px;">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin</a></li>
            <li class="breadcrumb-item">Catalog</li>
            <li class="breadcrumb-item active">Item Specifications</li>
        </ol></nav>
        <h4 style="font-weight:800;color:#1e293b;margin:0;font-size:22px;">
            <i class="bi bi-sliders me-2" style="color:#6c63ff;"></i>Item Specifications
        </h4>
    </div>
</div>

{{-- Stat cards --}}
<div class="stat-grid">
    @foreach([
        ['purple','bi-sliders',        $totalSpecs,    'Total Specifications', 'Across all categories'],
        ['green', 'bi-check-circle-fill', $catsWithSpecs,'Categories Configured',($totalCats-$catsWithSpecs).' still need specs'],
        ['amber', 'bi-diagram-2-fill', $subWithSpecs,  'Sub-cats Configured',  'Out of '.$subTotal.' sub-categories'],
        ['blue',  'bi-grid-fill',      $totalCats,     'Parent Categories',    'Top-level categories'],
    ] as [$color,$icon,$val,$lbl,$foot])
    <div class="stat-card stat-card--{{ $color }}">
        <div class="stat-card__bar"></div>
        <div class="stat-card__top">
            <div>
                <div class="stat-card__value">{{ $val }}</div>
                <div class="stat-card__label">{{ $lbl }}</div>
            </div>
            <div class="stat-card__icon"><i class="bi {{ $icon }}"></i></div>
        </div>
        <div class="stat-card__footer"><i class="bi bi-info-circle"></i> {{ $foot }}</div>
    </div>
    @endforeach
</div>

{{-- Table card --}}
<div class="table-card">
    <div class="table-card-head">
        <div class="table-card-title">
            <i class="bi bi-table"></i> Category Specifications List
            <small style="font-size:12px;font-weight:500;color:#94a3b8;">· Server-side &nbsp;·&nbsp; Yajra DataTables</small>
        </div>
    </div>
    <div class="table-card-body">
        <table id="specTable" class="display">
            <thead>
                <tr>
                    <th style="width:56px;"></th>
                    <th>Category</th>
                    <th>Specs</th>
                    <th>Sub-categories</th>
                    <th style="text-align:right;">Action</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script>
$(function () {
    $('#specTable').DataTable({
        processing : true,
        serverSide : true,
        ajax: {
            url  : '{{ route('admin.specifications.overview') }}',
            type : 'GET',
            error: function (xhr) {
                console.error('DataTables AJAX error:', xhr.status, xhr.responseText);
            }
        },
        columns: [
            { data: 'image_html', name: 'image',   orderable: false, searchable: false },
            { data: 'name_html',  name: 'name',    orderable: false },
            { data: 'specs_html', name: 'specs',   orderable: false, searchable: false },
            { data: 'sub_html',   name: 'sub',     orderable: false, searchable: false },
            { data: 'actions',    name: 'actions', orderable: false, searchable: false, className: 'text-end' },
        ],
        ordering   : false,
        pageLength : 15,
        lengthMenu : [[10, 15, 25, 50], [10, 15, 25, 50]],
        language: {
            search            : '',
            searchPlaceholder : 'Search categories…',
            processing        : '<span style="color:#6c63ff;">Loading…</span>',
            emptyTable        : '<div style="padding:40px 0;text-align:center;color:#94a3b8;">No categories found</div>',
            zeroRecords       : '<div style="padding:40px 0;text-align:center;color:#94a3b8;">No matching records</div>',
            lengthMenu        : 'Show _MENU_ entries',
            info              : 'Showing _START_–_END_ of _TOTAL_ categories',
            paginate          : { previous: '‹ Prev', next: 'Next ›' },
        },
        dom: '<"dt-top"lf>rt<"row mt-3"<"col-sm-6"i><"col-sm-6 d-flex justify-content-end"p>>',
    });
});
</script>
@endpush
