@php
    $_pPath     = $product->primaryImage->path ?? null;
    $primaryImg = $_pPath
        ? (str_starts_with($_pPath, 'frontend-assets/') ? asset($_pPath) : asset('storage/'.$_pPath))
        : null;
    $allImgs    = $product->images->count() ? $product->images : collect();
    $sale       = $product->effective_price;
    $orig       = $product->price;
    $isOnSale   = $product->is_on_sale;
    $disc       = $isOnSale ? round((($orig - $sale) / $orig) * 100) : 0;
    $stars      = rand(60, 100);
@endphp

<div class="product-single-container product-single-default product-quick-view mb-0 custom-scrollbar">
    <div class="row">

        {{-- LEFT: Image gallery --}}
        <div class="col-md-6 product-single-gallery mb-md-0">
            <div class="product-slider-container">
                <div class="label-group">
                    @if ($disc > 0)
                        <div class="product-label label-sale">-{{ $disc }}%</div>
                    @endif
                    @if ($product->is_featured)
                        <div class="product-label label-hot">HOT</div>
                    @endif
                </div>

                <div class="product-single-carousel owl-carousel owl-theme show-nav-hover">
                    @if ($allImgs->count())
                        @foreach ($allImgs as $img)
                            <div class="product-item">
                                <img class="product-single-image"
                                     src="{{ str_starts_with($img->path, 'frontend-assets/') ? asset($img->path) : asset('storage/'.$img->path) }}"
                                     alt="{{ $product->name }}">
                            </div>
                        @endforeach
                    @elseif ($primaryImg)
                        <div class="product-item">
                            <img class="product-single-image"
                                 src="{{ $primaryImg }}"
                                 alt="{{ $product->name }}">
                        </div>
                    @else
                        <div class="product-item d-flex align-items-center justify-content-center bg-light" style="height:300px;">
                            <i class="fas fa-image fa-3x text-muted"></i>
                        </div>
                    @endif
                </div><!-- End .product-single-carousel -->

                @if ($allImgs->count() > 1)
                <div class="prod-thumbnail owl-dots">
                    @foreach ($allImgs as $img)
                        <div class="owl-dot">
                            <img src="{{ str_starts_with($img->path, 'frontend-assets/') ? asset($img->path) : asset('storage/'.$img->path) }}" alt="">
                        </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div><!-- End .product-single-gallery -->

        {{-- RIGHT: Product details --}}
        <div class="col-md-6">
            <div class="product-single-details mb-0 ml-md-4">

                <h1 class="product-title">{{ $product->name }}</h1>

                <div class="ratings-container">
                    <div class="product-ratings">
                        <span class="ratings" style="width:{{ $stars }}%"></span>
                    </div>
                    <a href="#" class="rating-link">( {{ rand(1,50) }} Reviews )</a>
                </div>

                <hr class="short-divider">

                <div class="price-box">
                    @if ($isOnSale)
                        <del class="old-price">${{ number_format($orig, 2) }}</del>
                        <span class="product-price">${{ number_format($sale, 2) }}</span>
                    @else
                        <span class="product-price">${{ number_format($sale, 2) }}</span>
                    @endif
                </div>

                @if ($product->short_description)
                <div class="product-desc">
                    <p>{{ $product->short_description }}</p>
                </div>
                @endif

                <ul class="single-info-list">
                    @if ($product->sku)
                    <li>SKU: <strong>{{ $product->sku }}</strong></li>
                    @endif
                    @if ($product->category)
                    <li>CATEGORY:
                        <strong><a href="#" class="product-category">{{ $product->category->name }}</a></strong>
                    </li>
                    @endif
                    <li>CONDITION: <strong>{{ ucfirst($product->condition ?? 'New') }}</strong></li>
                    <li>AVAILABILITY:
                        <strong class="{{ $product->stock > 0 ? 'text-success' : 'text-danger' }}">
                            {{ $product->stock > 0 ? 'In Stock (' . $product->stock . ')' : 'Out of Stock' }}
                        </strong>
                    </li>
                    @if ($product->free_shipping)
                    <li>SHIPPING: <strong class="text-primary">Free Shipping</strong></li>
                    @endif
                </ul>

                <form method="POST" action="{{ route('cart.add', $product) }}"
                      id="qv-atc-form-{{ $product->id }}" class="qv-atc-form">
                    @csrf
                    <div class="product-action">
                        <div class="product-single-qty">
                            <input name="quantity" class="horizontal-quantity form-control" type="text" value="1" min="1" max="{{ $product->stock }}">
                        </div>

                        <button type="submit" class="btn btn-dark mr-2 qv-atc-btn" title="Add to Cart">
                            <i class="fas fa-shopping-cart mr-1"></i> ADD TO CART
                        </button>
                    </div>
                </form>

<script>
(function () {
    var form = document.getElementById('qv-atc-form-{{ $product->id }}');
    if (!form) return;

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        var btn = form.querySelector('.qv-atc-btn');
        var qty = form.querySelector('[name="quantity"]').value || 1;

        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Adding…';

        fetch(form.action, {
            method : 'POST',
            headers: {
                'Accept'      : 'application/json',
                'X-CSRF-TOKEN': form.querySelector('[name="_token"]').value,
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: '_token=' + encodeURIComponent(form.querySelector('[name="_token"]').value)
                + '&quantity=' + encodeURIComponent(qty)
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (data.success || data.count !== undefined) {
                // Update all cart badges in the header
                document.querySelectorAll('.eb-cart-badge, .eb-top__badge').forEach(function (el) {
                    el.textContent = data.count;
                });

                // Show brief success state on button
                btn.innerHTML = '<i class="fas fa-check mr-1"></i> Added!';
                btn.style.background = '#16a34a';
                btn.style.borderColor = '#16a34a';

                // Show Porto's native miniPopup
                var pImg = document.querySelector('.product-single-carousel .product-single-image, .product-item img');
                if (typeof window.ebShowMiniPopup === 'function') {
                    ebShowMiniPopup(
                        '{{ addslashes($product->name) }}',
                        '{{ route('product.show', $product->slug) }}',
                        pImg ? pImg.src : '{{ $primaryImg ?? '' }}',
                        '{{ route('product.show', $product->slug) }}'
                    );
                }

                // Close popup after short delay
                setTimeout(function () {
                    if (typeof $.magnificPopup !== 'undefined') {
                        $.magnificPopup.close();
                    }
                }, 700);
            }
        })
        .catch(function () {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-shopping-cart mr-1"></i> ADD TO CART';
        });
    });
})();
</script>

                <hr class="divider mb-0 mt-0">

                <div class="product-single-share mb-0">
                    <label class="sr-only">Share:</label>
                    <div class="social-icons mr-2">
                        <a href="#" class="social-icon social-facebook icon-facebook" target="_blank" title="Facebook"></a>
                        <a href="#" class="social-icon social-twitter icon-twitter" target="_blank" title="Twitter"></a>
                        <a href="#" class="social-icon social-linkedin fab fa-linkedin-in" target="_blank" title="LinkedIn"></a>
                        <a href="#" class="social-icon social-mail icon-mail-alt" target="_blank" title="Mail"></a>
                    </div>
                    <a href="#" class="btn-icon-wish add-wishlist" title="Add to Wishlist">
                        <i class="icon-wishlist-2"></i><span>Add to Wishlist</span>
                    </a>
                </div>

            </div>
        </div><!-- End .product-single-details -->

        <button title="Close (Esc)" type="button" class="mfp-close">×</button>

    </div><!-- End .row -->
</div><!-- End .product-single-container -->
