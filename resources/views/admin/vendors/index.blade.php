{{-- Admin > Users > Vendors — dashboard stats + Yajra DataTables (server-side) --}}
@extends('admin.layouts.app')
@section('title', 'Vendors')
@section('breadcrumb')
    <li class="breadcrumb-item">Users</li>
    <li class="breadcrumb-item active">Vendors</li>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('admin-assets/css/admin-vendors.css') }}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
@endpush

@section('content')
@php $cur = setting('currency_symbol', '$'); @endphp

{{-- ===== Dashboard summary ===== --}}
<div class="av-stats">
    @php
        $cards = [
            ['total',    'Total Vendors', $stats['total'],                          'bx bx-store-alt'],
            ['approved', 'Approved',      $stats['approved'],                       'bx bx-check-circle'],
            ['pending',  'Pending',       $stats['pending'],                        'bx bx-time-five'],
            ['rejected', 'Rejected',      $stats['rejected'],                       'bx bx-x-circle'],
            ['earnings', 'Vendor Earnings', $cur.number_format($stats['earnings'], 0), 'bx bx-dollar-circle'],
        ];
    @endphp
    @foreach ($cards as [$key, $label, $value, $icon])
        <div class="av-stat av-stat--{{ $key }}">
            <div class="av-stat__icon"><i class="{{ $icon }}"></i></div>
            <div class="av-stat__num">{{ $value }}</div>
            <div class="av-stat__label">{{ $label }}</div>
        </div>
    @endforeach
</div>

{{-- ===== Status tabs + search ===== --}}
<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
    <div class="av-tabs" id="av-tabs">
        <button type="button" class="av-tab is-active" data-status="">All</button>
        <button type="button" class="av-tab" data-status="pending">Pending</button>
        <button type="button" class="av-tab" data-status="approved">Approved</button>
        <button type="button" class="av-tab" data-status="rejected">Rejected</button>
    </div>
    <input type="text" id="av-q" class="form-control" style="max-width:280px" placeholder="Search store / owner…">
</div>

{{-- ===== Vendors table (server-side) ===== --}}
<div class="av-table-card">
    <table id="vendorsTable" class="display" style="width:100%">
        <thead>
            <tr>
                <th>Store</th>
                <th>Owner</th>
                <th>Applied</th>
                <th>Products</th>
                <th>Orders</th>
                <th>Earnings</th>
                <th>Status</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
    </table>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script>
$(function () {
    var currentStatus = '';
    var table = $('#vendorsTable').DataTable({
        processing : true,
        serverSide : true,
        searching  : false,
        ordering   : false,
        ajax: {
            url : '{{ route('admin.vendors.index') }}',
            type: 'GET',
            data: function (d) {
                d.status = currentStatus;
                d.q      = $('#av-q').val();
            },
            error: function (xhr) { console.error('DataTables error:', xhr.status, xhr.responseText); }
        },
        columns: [
            { data: 'store_html',    name: 'store_name', orderable: false },
            { data: 'owner_html',    name: 'owner',      orderable: false },
            { data: 'applied_html',  name: 'created_at', orderable: false },
            { data: 'products_html', name: 'products',   orderable: false, searchable: false, className: 'text-center' },
            { data: 'orders_html',   name: 'orders',     orderable: false, searchable: false, className: 'text-center' },
            { data: 'earnings_html', name: 'earnings',   orderable: false, searchable: false },
            { data: 'status_html',   name: 'status',     orderable: false, searchable: false },
            { data: 'actions',       name: 'actions',    orderable: false, searchable: false, className: 'text-center' },
        ],
        pageLength : 10,
        lengthMenu : [[10, 15, 25, 50], [10, 15, 25, 50]],
        language: {
            processing  : '<span style="color:#7c73ff;">Loading…</span>',
            emptyTable  : '<div style="padding:40px 0;text-align:center;color:#94a3b8;"><i class="bx bx-store-alt" style="font-size:34px;display:block;margin-bottom:8px;"></i>No vendors found</div>',
            zeroRecords : '<div style="padding:40px 0;text-align:center;color:#94a3b8;">No matching vendors</div>',
            info        : 'Showing _START_–_END_ of _TOTAL_ vendors',
            infoEmpty   : 'No vendors',
            lengthMenu  : 'Show _MENU_',
            paginate    : { previous: '‹ Prev', next: 'Next ›' }
        },
        dom: '<"dt-top"l>rt<"row mt-3"<"col-sm-6"i><"col-sm-6 d-flex justify-content-end"p>>',
    });

    // Status tabs
    $('#av-tabs .av-tab').on('click', function () {
        $('#av-tabs .av-tab').removeClass('is-active');
        $(this).addClass('is-active');
        currentStatus = $(this).data('status');
        table.ajax.reload();
    });
    // Search
    $('#av-q').on('keyup', function (e) { if (e.key === 'Enter') table.ajax.reload(); });
});
</script>
@endpush
