{{-- Admin > Sales > Orders — dashboard stats + Yajra DataTables (server-side) --}}
@extends('admin.layouts.app')
@section('title', 'Orders')
@section('breadcrumb')
    <li class="breadcrumb-item">Sales</li>
    <li class="breadcrumb-item active">Orders</li>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('admin-assets/css/admin-orders.css') }}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
@endpush

@section('content')
@php $cur = setting('currency_symbol', '$'); @endphp

{{-- ===== Dashboard summary ===== --}}
<div class="ao-stats">
    @php
        $cards = [
            ['total',      'Total Orders', $stats['total'],                          'bx bx-cart'],
            ['pending',    'Pending',      $stats['pending'],                        'bx bx-time-five'],
            ['processing', 'Processing',   $stats['processing'],                     'bx bx-loader-circle'],
            ['completed',  'Completed',    $stats['completed'],                      'bx bx-check-circle'],
            ['revenue',    'Revenue',      $cur.number_format($stats['revenue'], 0), 'bx bx-dollar-circle'],
            ['unpaid',     'Unpaid',       $stats['unpaid'],                         'bx bx-error-circle'],
            ['vendor',     'Vendor Earnings',   $cur.number_format($stats['vendor_earnings'], 0),   'bx bx-store-alt'],
            ['platform',   'Platform Earnings', $cur.number_format($stats['platform_earnings'], 0), 'bx bx-buildings'],
        ];
    @endphp
    @foreach ($cards as [$key, $label, $value, $icon])
        <div class="ao-stat ao-stat--{{ $key }}">
            <div class="ao-stat__icon"><i class="{{ $icon }}"></i></div>
            <div class="ao-stat__num">{{ $value }}</div>
            <div class="ao-stat__label">{{ $label }}</div>
        </div>
    @endforeach
</div>

{{-- ===== Filters ===== --}}
<div class="card custom-card mb-3">
    <div class="card-body">
        <div class="row gy-2 gx-2">
            <div class="col-md-4">
                <input type="text" id="f-q" class="form-control" placeholder="Search order # / customer…">
            </div>
            <div class="col-md-3">
                <select id="f-status" class="form-control">
                    <option value="">Any status</option>
                    @foreach (['pending','processing','completed','cancelled'] as $s)
                        <option value="{{ $s }}">{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select id="f-payment" class="form-control">
                    <option value="">Any payment</option>
                    @foreach (['unpaid','paid','failed','refunded'] as $s)
                        <option value="{{ $s }}">{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-grid"><button id="btn-apply" class="btn btn-primary"><i class="bx bx-filter-alt me-1"></i>Filter</button></div>
        </div>
    </div>
</div>

{{-- ===== Orders table (server-side) ===== --}}
<div class="ao-table-card">
    <table id="ordersTable" class="display" style="width:100%">
        <thead>
            <tr>
                <th>Order</th>
                <th>Customer</th>
                <th>Products</th>
                <th>Payment</th>
                <th>Status</th>
                <th>Total</th>
                <th class="text-center">Action</th>
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
    var table = $('#ordersTable').DataTable({
        processing : true,
        serverSide : true,
        searching  : false,   // we use our own filter inputs
        ordering   : false,
        ajax: {
            url : '{{ route('admin.orders.index') }}',
            type: 'GET',
            data: function (d) {
                d.q              = $('#f-q').val();
                d.status         = $('#f-status').val();
                d.payment_status = $('#f-payment').val();
            },
            error: function (xhr) { console.error('DataTables error:', xhr.status, xhr.responseText); }
        },
        columns: [
            { data: 'order_html',    name: 'order_number', orderable: false },
            { data: 'customer_html', name: 'customer',     orderable: false },
            { data: 'products_html', name: 'products',     orderable: false, searchable: false },
            { data: 'payment_html',  name: 'payment',      orderable: false, searchable: false },
            { data: 'status_html',   name: 'status',       orderable: false, searchable: false },
            { data: 'total_html',    name: 'total',        orderable: false, searchable: false },
            { data: 'actions',       name: 'actions',      orderable: false, searchable: false, className: 'text-center' },
        ],
        pageLength : 10,
        lengthMenu : [[10, 15, 25, 50], [10, 15, 25, 50]],
        language: {
            processing  : '<span style="color:#7c73ff;">Loading…</span>',
            emptyTable  : '<div style="padding:40px 0;text-align:center;color:#94a3b8;"><i class="bx bx-cart" style="font-size:34px;display:block;margin-bottom:8px;"></i>No orders found</div>',
            zeroRecords : '<div style="padding:40px 0;text-align:center;color:#94a3b8;">No matching orders</div>',
            info        : 'Showing _START_–_END_ of _TOTAL_ orders',
            infoEmpty   : 'No orders',
            lengthMenu  : 'Show _MENU_',
            paginate    : { previous: '‹ Prev', next: 'Next ›' }
        },
        dom: '<"dt-top"l>rt<"row mt-3"<"col-sm-6"i><"col-sm-6 d-flex justify-content-end"p>>',
    });

    // Filter button + Enter key reload the table.
    $('#btn-apply').on('click', function () { table.ajax.reload(); });
    $('#f-q').on('keyup', function (e) { if (e.key === 'Enter') table.ajax.reload(); });
    $('#f-status, #f-payment').on('change', function () { table.ajax.reload(); });
});
</script>
@endpush
