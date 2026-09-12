{{-- Wishlist / Watchlist page (guests + logged-in users) --}}
@extends('frontend.layouts.app')
@section('title', 'My Wishlist')

@section('content')
<div class="container wl-wrap">

    @include('vendor.partials.alerts')

    <div class="wl-head">
        <div>
            <h1><i class="fas fa-heart" style="color:#e53935"></i> My Wishlist</h1>
            <p>{{ $products->count() }} item{{ $products->count() === 1 ? '' : 's' }} saved</p>
        </div>
        <a href="{{ route('home') }}" class="wl-card__cart" style="width:auto;padding:10px 20px;">Continue Shopping</a>
    </div>

    @if ($products->isEmpty())
        <div class="wl-empty">
            <i class="far fa-heart"></i>
            <h3>Your wishlist is empty</h3>
            <p>Save items you love by tapping the heart — they'll show up here.</p>
            <a href="{{ route('home') }}">Browse Products</a>
        </div>
    @else
        <div class="wl-grid">
            @foreach ($products as $product)
                @php
                    $img = $product->primaryImage ?? $product->images->first();
                    $src = $img
                        ? (str_starts_with($img->path, 'frontend-assets/') ? asset($img->path) : asset('storage/'.$img->path))
                        : asset('frontend-assets/images/demoes/demo36/products/product-1.jpg');
                @endphp
                <div class="wl-card" id="wl-card-{{ $product->id }}">
                    <div class="wl-card__imgwrap">
                        <a href="{{ route('product.show', $product->slug) }}"><img src="{{ $src }}" alt="{{ $product->name }}"></a>
                        {{-- Remove from wishlist --}}
                        <form action="{{ route('wishlist.remove', $product->id) }}" method="POST"
                              class="js-wish-remove" data-id="{{ $product->id }}">
                            @csrf @method('DELETE')
                            <button type="submit" class="wl-card__remove" title="Remove"><i class="fas fa-times"></i></button>
                        </form>
                    </div>
                    <div class="wl-card__body">
                        <a href="{{ route('product.show', $product->slug) }}" class="wl-card__name">{{ \Illuminate\Support\Str::limit($product->name, 55) }}</a>
                        <div class="wl-card__meta">{{ $product->vendor?->name ?? 'Market Seller' }}</div>
                        <div class="wl-card__price">
                            {{ setting('currency_symbol','$') }}{{ number_format($product->effective_price, 2) }}
                            @if ($product->is_on_sale)
                                <span class="wl-card__old">{{ setting('currency_symbol','$') }}{{ number_format($product->price, 2) }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="wl-card__actions">
                        <form action="{{ route('cart.add', $product->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="wl-card__cart"><i class="fas fa-cart-plus"></i> Add to Cart</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
