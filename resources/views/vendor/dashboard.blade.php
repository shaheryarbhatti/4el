{{-- Vendor dashboard home (rich stat cards + recent products) --}}
@extends('vendor.layouts.app')
@section('title', 'Vendor Dashboard')

@section('vendor_content')

    {{-- Stat cards --}}
    <div class="vstats">
        @php
            $cards = [
                ['Total Products', $stats['total'],    'primary', 'fas fa-box'],
                ['Approved',       $stats['approved'], 'success', 'fas fa-check-circle'],
                ['Pending',        $stats['pending'],  'warning', 'fas fa-hourglass-half'],
                ['Out of Stock',   $stats['out'],      'danger',  'fas fa-triangle-exclamation'],
            ];
        @endphp
        @foreach ($cards as [$label, $value, $color, $icon])
            <div class="vstat vstat--{{ $color }}">
                <div class="vstat__icon"><i class="{{ $icon }}"></i></div>
                <div>
                    <div class="vstat__num">{{ $value }}</div>
                    <div class="vstat__label">{{ $label }}</div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Recent products --}}
    <div class="v-card">
        <div class="v-card__head">
            <h2 class="v-card__title">Recent Products</h2>
            <a href="{{ route('vendor.products.create') }}" class="v-btn v-btn--primary v-btn--sm"><i class="fas fa-plus"></i> Add Product</a>
        </div>
        <div class="v-card__body">
            @if ($recent->count())
                <div class="table-responsive">
                    <table class="v-table">
                        <thead><tr><th>Product</th><th>Price</th><th>Stock</th><th>Status</th></tr></thead>
                        <tbody>
                            @foreach ($recent as $p)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            @php $img = $p->primaryImage ?? $p->images->first(); @endphp
                                            @if ($img)<img src="{{ asset('storage/'.$img->path) }}" class="v-thumb v-thumb--40" alt="">@endif
                                            <span>{{ \Illuminate\Support\Str::limit($p->name, 40) }}</span>
                                        </div>
                                    </td>
                                    <td>{{ setting('currency_symbol','$') }}{{ number_format($p->effective_price, 2) }}</td>
                                    <td>{{ $p->stock }}</td>
                                    <td><span class="v-pill v-pill--{{ $p->status }}">{{ ucfirst($p->status) }}</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="v-empty">
                    <i class="fas fa-box-open fa-2x mb-2"></i>
                    <p>No products yet. <a href="{{ route('vendor.products.create') }}">Add your first product</a>.</p>
                </div>
            @endif
        </div>
    </div>

@endsection
