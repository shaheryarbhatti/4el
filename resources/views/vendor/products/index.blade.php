{{-- Vendor's own product list --}}
@extends('vendor.layouts.app')
@section('title', 'My Products')

@section('vendor_content')
<div class="v-card">
    <div class="v-card__head">
        <h2 class="v-card__title">My Products</h2>
        <a href="{{ route('vendor.products.create') }}" class="v-btn v-btn--primary v-btn--sm"><i class="fas fa-plus"></i> Add Product</a>
    </div>
    <div class="v-card__body">
        <form method="GET" class="mb-3">
            <div class="input-group">
                <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Search my products…">
                <div class="input-group-append"><button class="v-btn v-btn--light">Search</button></div>
            </div>
        </form>

        @if ($products->count())
            <div class="table-responsive">
                <table class="v-table">
                    <thead><tr><th>Product</th><th>Price</th><th>Stock</th><th>Status</th><th class="text-right">Actions</th></tr></thead>
                    <tbody>
                        @foreach ($products as $p)
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
                                <td class="text-right">
                                    <a href="{{ route('vendor.products.edit', $p) }}" class="v-btn v-btn--outline-primary v-btn--sm">Edit</a>
                                    <form action="{{ route('vendor.products.destroy', $p) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this product?');">
                                        @csrf @method('DELETE')
                                        <button class="v-btn v-btn--outline-danger v-btn--sm">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $products->links() }}</div>
        @else
            <div class="v-empty"><i class="fas fa-box-open fa-2x mb-2"></i><p>No products yet.</p></div>
        @endif
    </div>
</div>
@endsection
