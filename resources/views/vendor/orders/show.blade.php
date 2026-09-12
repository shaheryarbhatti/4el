{{-- Vendor order detail — only this vendor's items + shipping address --}}
@extends('vendor.layouts.app')
@section('title', 'Order '.$order->order_number)

@section('vendor_content')
@php $cur = setting('currency_symbol','$'); @endphp

<div class="v-card">
    <div class="v-card__head">
        <h2 class="v-card__title">Order {{ $order->order_number }}</h2>
        <a href="{{ route('vendor.orders.index') }}" class="v-btn v-btn--light v-btn--sm">← Back</a>
    </div>
    <div class="v-card__body">
        <div class="table-responsive">
            <table class="v-table">
                <thead><tr><th>Product</th><th>Price</th><th>Qty</th><th>My Earnings</th><th>Fulfilment</th></tr></thead>
                <tbody>
                    @foreach ($items as $it)
                        <tr>
                            <td class="fw-semibold">{{ $it->product_name }}</td>
                            <td>{{ $cur }}{{ number_format($it->price, 2) }}</td>
                            <td>{{ $it->quantity }}</td>
                            <td class="fw-semibold">{{ $cur }}{{ number_format($it->vendor_amount, 2) }}</td>
                            <td>
                                {{-- Update fulfilment status for this line --}}
                                <form action="{{ route('vendor.orders.item-status', $it) }}" method="POST" class="d-flex gap-2 align-items-center">
                                    @csrf @method('PUT')
                                    <select name="vendor_status" class="form-control form-control-sm v-select-sm" onchange="this.form.submit()">
                                        @foreach (['pending','shipped','completed'] as $s)
                                            <option value="{{ $s }}" @selected($it->vendor_status === $s)>{{ ucfirst($s) }}</option>
                                        @endforeach
                                    </select>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="v-note">
            Your total earnings from this order: <b>{{ $cur }}{{ number_format($items->sum('vendor_amount'), 2) }}</b>
            (platform commission {{ $cur }}{{ number_format($items->sum('commission_amount'), 2) }})
        </div>
    </div>
</div>

<div class="v-card">
    <div class="v-card__head"><h2 class="v-card__title">Ship To</h2></div>
    <div class="v-card__body v-addr">
        <p class="mb-1"><b>{{ $order->customer_name }}</b> · {{ $order->customer_phone }}</p>
        <p class="mb-0">{{ $order->address_line }}, {{ $order->city }} {{ $order->state }} {{ $order->postal_code }}, {{ $order->country }}</p>
    </div>
</div>
@endsection
