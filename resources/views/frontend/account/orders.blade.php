{{-- Customer order history --}}
@extends('frontend.account.layout')
@section('title', 'My Orders')
@push('styles')<link rel="stylesheet" href="{{ asset('frontend-assets/css/checkout.css') }}">@endpush

@section('account_content')
@php $cur = setting('currency_symbol','$'); @endphp
<div>
    <div class="co-head">
        <h1 style="font-size:22px">My Orders</h1>
        <p>Track and review everything you've purchased.</p>
    </div>

    <div class="co-card">
        <div class="co-card__body">
            @if ($orders->count())
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead><tr><th>Order #</th><th>Date</th><th>Items</th><th>Total</th><th>Payment</th><th>Status</th><th></th></tr></thead>
                        <tbody>
                            @foreach ($orders as $order)
                                <tr>
                                    <td class="fw-bold">{{ $order->order_number }}</td>
                                    <td>{{ $order->created_at->format('d M Y') }}</td>
                                    <td>{{ $order->items_count }}</td>
                                    <td class="fw-bold">{{ $cur }}{{ number_format($order->grand_total, 2) }}</td>
                                    <td>
                                        @php $pc = ['unpaid'=>'warning','paid'=>'success','failed'=>'danger','refunded'=>'secondary'][$order->payment_status] ?? 'light'; @endphp
                                        <span class="badge bg-{{ $pc }}">{{ ucfirst($order->payment_status) }}</span>
                                    </td>
                                    <td>
                                        @php $sc = ['pending'=>'warning','processing'=>'info','completed'=>'success','cancelled'=>'danger'][$order->status] ?? 'secondary'; @endphp
                                        <span class="badge bg-{{ $sc }}">{{ ucfirst($order->status) }}</span>
                                    </td>
                                    <td class="text-end"><a href="{{ route('account.orders.show', $order) }}" class="btn btn-sm btn-dark">View</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">{{ $orders->links() }}</div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                    <h4>No orders yet</h4>
                    <p class="text-muted">When you place an order it will appear here.</p>
                    <a href="{{ route('home') }}" class="btn btn-dark">Start Shopping</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
