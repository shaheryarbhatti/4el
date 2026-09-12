{{-- Admin > Catalog > Auctions — rich dashboard + Yajra DataTables + live countdowns --}}
@extends('admin.layouts.app')
@section('title', 'Auctions')
@section('breadcrumb')
    <li class="breadcrumb-item">Catalog</li>
    <li class="breadcrumb-item active">Auctions</li>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('admin-assets/css/admin-auctions.css') }}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
@endpush

@section('content')
@php $cur = setting('currency_symbol', '$'); @endphp

{{-- ===== Dashboard summary ===== --}}
<div class="au-stats">
    @php
        $cards = [
            ['total',   'Total Auctions', $stats['total'],                          'bx bx-time-five'],
            ['live',    'Live Now',        $stats['live'],                          'bx bx-broadcast'],
            ['ended',   'Ended',           $stats['ended'],                         'bx bx-flag'],
            ['sold',    'Sold',            $stats['sold'],                          'bx bx-check-circle'],
            ['bids',    'Total Bids',      $stats['bids'],                          'bx bx-purchase-tag'],
            ['highest', 'Highest Bid',     $cur.number_format($stats['highest'], 0), 'bx bx-trophy'],
        ];
    @endphp
    @foreach ($cards as [$key, $label, $value, $icon])
        <div class="au-stat au-stat--{{ $key }}">
            <div class="au-stat__icon"><i class="{{ $icon }}"></i></div>
            <div><div class="au-stat__num">{{ $value }}</div><div class="au-stat__label">{{ $label }}</div></div>
        </div>
    @endforeach
</div>

{{-- ===== Tabs + search ===== --}}
<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
    <div class="au-tabs" id="au-tabs">
        <button type="button" class="au-tab is-active" data-status="">All</button>
        <button type="button" class="au-tab" data-status="live">Live</button>
        <button type="button" class="au-tab" data-status="ended">Ended</button>
        <button type="button" class="au-tab" data-status="sold">Sold</button>
        <button type="button" class="au-tab" data-status="unsold">Unsold</button>
    </div>
    <input type="text" id="au-q" class="form-control" style="max-width:280px" placeholder="Search auction…">
</div>

{{-- ===== Auctions table ===== --}}
<div class="au-table-card">
    <table id="auctionsTable" class="display" style="width:100%">
        <thead>
            <tr>
                <th>Auction</th>
                <th>Vendor</th>
                <th>Current / Start</th>
                <th>Reserve</th>
                <th>Bids</th>
                <th>Time Left</th>
                <th>Status</th>
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
    var currentStatus = '';
    var table = $('#auctionsTable').DataTable({
        processing : true,
        serverSide : true,
        searching  : false,
        ordering   : false,
        ajax: {
            url : '{{ route('admin.auctions.index') }}',
            type: 'GET',
            data: function (d) { d.status = currentStatus; d.q = $('#au-q').val(); },
            error: function (xhr) { console.error('DataTables error:', xhr.status, xhr.responseText); }
        },
        columns: [
            { data: 'product_html', name: 'name',    orderable: false },
            { data: 'vendor_html',  name: 'vendor',  orderable: false, searchable: false },
            { data: 'bid_html',     name: 'bid',     orderable: false, searchable: false },
            { data: 'reserve_html', name: 'reserve', orderable: false, searchable: false },
            { data: 'bids_html',    name: 'bids',    orderable: false, searchable: false, className: 'text-center' },
            { data: 'time_html',    name: 'time',    orderable: false, searchable: false },
            { data: 'status_html',  name: 'status',  orderable: false, searchable: false },
            { data: 'actions',      name: 'actions', orderable: false, searchable: false, className: 'text-center' },
        ],
        pageLength : 10,
        lengthMenu : [[10, 15, 25, 50], [10, 15, 25, 50]],
        language: {
            processing  : '<span style="color:#7c73ff;">Loading…</span>',
            emptyTable  : '<div style="padding:50px 0;text-align:center;color:#94a3b8;"><i class="bx bx-time-five" style="font-size:40px;display:block;margin-bottom:10px;"></i>No auctions yet<div style="font-size:12px;margin-top:6px;">Auctions appear here when vendors list products as “Auction”.</div></div>',
            zeroRecords : '<div style="padding:40px 0;text-align:center;color:#94a3b8;">No matching auctions</div>',
            info        : 'Showing _START_–_END_ of _TOTAL_ auctions',
            infoEmpty   : 'No auctions',
            lengthMenu  : 'Show _MENU_',
            paginate    : { previous: '‹ Prev', next: 'Next ›' }
        },
        dom: '<"dt-top"l>rt<"row mt-3"<"col-sm-6"i><"col-sm-6 d-flex justify-content-end"p>>',
        drawCallback: function () { updateCountdowns(); }
    });

    $('#au-tabs .au-tab').on('click', function () {
        $('#au-tabs .au-tab').removeClass('is-active');
        $(this).addClass('is-active');
        currentStatus = $(this).data('status');
        table.ajax.reload();
    });
    $('#au-q').on('keyup', function (e) { if (e.key === 'Enter') table.ajax.reload(); });

    // ---- Live countdown timers ----
    function fmt(ms) {
        if (ms <= 0) return 'Ended';
        var s = Math.floor(ms / 1000);
        var d = Math.floor(s / 86400); s %= 86400;
        var h = Math.floor(s / 3600);  s %= 3600;
        var m = Math.floor(s / 60);    s %= 60;
        var p = function (n) { return (n < 10 ? '0' : '') + n; };
        if (d > 0) return d + 'd ' + p(h) + 'h ' + p(m) + 'm';
        return p(h) + ':' + p(m) + ':' + p(s);
    }
    function updateCountdowns() {
        $('.au-countdown').each(function () {
            var ends = new Date($(this).data('ends')).getTime();
            var diff = ends - Date.now();
            $(this).html('<i class="bx bx-time"></i> ' + fmt(diff));
            $(this).toggleClass('is-soon', diff > 0 && diff < 3600000);
        });
    }
    setInterval(updateCountdowns, 1000);
});
</script>
@endpush
