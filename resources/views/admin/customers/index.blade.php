{{-- Admin > Users > Customers — dashboard stats + Yajra DataTables (server-side) --}}
@extends('admin.layouts.app')
@section('title', 'Customers')
@section('breadcrumb')
    <li class="breadcrumb-item">Users</li>
    <li class="breadcrumb-item active">Customers</li>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('admin-assets/css/admin-customers.css') }}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
@endpush

@section('content')
@php $cur = setting('currency_symbol', '$'); @endphp

{{-- ===== Dashboard summary ===== --}}
<div class="cu-stats">
    @php
        $cards = [
            ['total',   'Total Customers', $stats['total'],                          'bx bx-group'],
            ['active',  'Active',          $stats['active'],                         'bx bx-check-circle'],
            ['blocked', 'Blocked',         $stats['blocked'],                        'bx bx-block'],
            ['revenue', 'Revenue',         $cur.number_format($stats['revenue'], 0), 'bx bx-dollar-circle'],
            ['new',     'New (30d)',       $stats['new'],                            'bx bx-user-plus'],
        ];
    @endphp
    @foreach ($cards as [$key, $label, $value, $icon])
        <div class="cu-stat cu-stat--{{ $key }}">
            <div class="cu-stat__icon"><i class="{{ $icon }}"></i></div>
            <div class="cu-stat__num">{{ $value }}</div>
            <div class="cu-stat__label">{{ $label }}</div>
        </div>
    @endforeach
</div>

{{-- ===== Tabs + search ===== --}}
<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
    <div class="cu-tabs" id="cu-tabs">
        <button type="button" class="cu-tab is-active" data-status="">All</button>
        <button type="button" class="cu-tab" data-status="active">Active</button>
        <button type="button" class="cu-tab" data-status="blocked">Blocked</button>
    </div>
    <input type="text" id="cu-q" class="form-control" style="max-width:280px" placeholder="Search name / email…">
</div>

{{-- ===== Customers table ===== --}}
<div class="cu-table-card">
    <table id="customersTable" class="display" style="width:100%">
        <thead>
            <tr>
                <th>Customer</th>
                <th>Phone</th>
                <th>Joined</th>
                <th>Orders</th>
                <th>Total Spent</th>
                <th>Wishlist</th>
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
    var table = $('#customersTable').DataTable({
        processing : true,
        serverSide : true,
        searching  : false,
        ordering   : false,
        ajax: {
            url : '{{ route('admin.customers.index') }}',
            type: 'GET',
            data: function (d) { d.status = currentStatus; d.q = $('#cu-q').val(); },
            error: function (xhr) { console.error('DataTables error:', xhr.status, xhr.responseText); }
        },
        columns: [
            { data: 'customer_html', name: 'name',     orderable: false },
            { data: 'phone_html',    name: 'phone',    orderable: false, searchable: false },
            { data: 'joined_html',   name: 'created_at', orderable: false },
            { data: 'orders_html',   name: 'orders',   orderable: false, searchable: false, className: 'text-center' },
            { data: 'spent_html',    name: 'spent',    orderable: false, searchable: false },
            { data: 'wishlist_html', name: 'wishlist', orderable: false, searchable: false, className: 'text-center' },
            { data: 'status_html',   name: 'status',   orderable: false, searchable: false },
            { data: 'actions',       name: 'actions',  orderable: false, searchable: false, className: 'text-center' },
        ],
        pageLength : 10,
        lengthMenu : [[10, 15, 25, 50], [10, 15, 25, 50]],
        language: {
            processing  : '<span style="color:#7c73ff;">Loading…</span>',
            emptyTable  : '<div style="padding:40px 0;text-align:center;color:#94a3b8;"><i class="bx bx-group" style="font-size:34px;display:block;margin-bottom:8px;"></i>No customers found</div>',
            zeroRecords : '<div style="padding:40px 0;text-align:center;color:#94a3b8;">No matching customers</div>',
            info        : 'Showing _START_–_END_ of _TOTAL_ customers',
            infoEmpty   : 'No customers',
            lengthMenu  : 'Show _MENU_',
            paginate    : { previous: '‹ Prev', next: 'Next ›' }
        },
        dom: '<"dt-top"l>rt<"row mt-3"<"col-sm-6"i><"col-sm-6 d-flex justify-content-end"p>>',
    });

    $('#cu-tabs .cu-tab').on('click', function () {
        $('#cu-tabs .cu-tab').removeClass('is-active');
        $(this).addClass('is-active');
        currentStatus = $(this).data('status');
        table.ajax.reload();
    });
    $('#cu-q').on('keyup', function (e) { if (e.key === 'Enter') table.ajax.reload(); });
});
</script>
@endpush
