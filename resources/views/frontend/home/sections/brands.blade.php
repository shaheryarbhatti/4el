{{-- Home section: brands (from Porto demo36). Managed in Admin > Pages > Home Sections. --}}
<div class="category-filter-section bg-gray pt-0"><div class="container">
                    <div class="brands-section mt-2 mb-3 appear-animate" data-animation-delay="200" data-animation-name="fadeIn" data-animation-duration="1000">
                        <div class="headding">
                            <h4 class="section-title text-transform-none">Featured Brands</h4>
                        </div>
                        <div class="brands-slider owl-carousel bg-white owl-theme nav-circle images-center" data-owl-options="{
                                'margin': 1,
                                'navText': [ '<i class=icon-left-open-big>', '<i class=icon-right-open-big>' ],
                                'nav': true
                            }">
                            <figure><img src="/frontend-assets/images/brands/small/brand1.png" width="140" height="60" alt="brand">
                            </figure>
                            <figure><img src="/frontend-assets/images/brands/small/brand2.png" width="140" height="60" alt="brand">
                            </figure>
                            <figure><img src="/frontend-assets/images/brands/small/brand3.png" width="140" height="60" alt="brand">
                            </figure>
                            <figure><img src="/frontend-assets/images/brands/small/brand4.png" width="140" height="60" alt="brand">
                            </figure>
                            <figure><img src="/frontend-assets/images/brands/small/brand5.png" width="140" height="60" alt="brand">
                            </figure>
                            <figure><img src="/frontend-assets/images/brands/small/brand6.png" width="140" height="60" alt="brand">
                            </figure>
                            <figure><img src="/frontend-assets/images/brands/small/brand4.png" width="140" height="60" alt="brand">
                            </figure>
                        </div>
                        <!-- End .brands-slider -->
                    </div>

                    <div class="product-slider-tab selected-products-section appear-animate bg-white" data-animation-name="fadeIn" data-animation-delay="100">
                        <div class="heading shop-list d-flex flex-lg-row flex-column align-items-lg-center bg-gray mb-0 pl-0 pr-0 pt-2">
                            <h4 class="section-title text-transform-none mb-0 ml-0">Selected Products</h4>
                            <ul class="nav justify-content-lg-center mb-0" id="myTab-two" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="seller-two-tab" data-toggle="tab" href="#seller-two" role="tab" aria-controls="seller-two" aria-selected="true">Best Sellers</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="new-two-tab" data-toggle="tab" href="#new-two" role="tab" aria-controls="new-two" aria-selected="false">New
                                        Arrivals</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="best-two-tab" data-toggle="tab" href="#best-two" role="tab" aria-controls="best-two" aria-selected="false">Best
                                        Ratings</a>
                                </li>
                            </ul>
                            <a class="view-all ml-auto" href="demo36-shop.html">View
                                All<i class="fas fa-long-arrow-alt-right"></i></a>
                        </div>

                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="seller-two" role="tabpanel" aria-labelledby="seller-two-tab">
                                <div class="products-slider owl-carousel nav-circle carousel-with-bg owl-theme pb-0" data-owl-options="{
                                'margin': 1,
                                'dots': false,
                                'nav': true,
                                'navText': [ '<i class=icon-left-open-big>', '<i class=icon-right-open-big>' ],
                                'loop': false,
                                'responsive': {
                                    '0': {
                                        'items': 2
                                    },
                                    '576': {
                                        'items': 3
                                    },
                                    '768': {
                                        'items': 4
                                    },
                                    '992': {
                                        'items': 5
                                    },
                                    '1200': {
                                        'items': 6
                                    }
                                }
                            }">
                                    <div class="product-default inner-quickview inner-icon">
                                        <figure>
                                            <a href="demo36-product.html">
                                                <img src="/frontend-assets/images/demoes/demo36/products/product-16.jpg" width="239" height="239" alt="product">
                                            </a>
                                            <div class="btn-icon-group">
                                                <a href="#" class="btn-icon btn-add-cart product-type-simple"><i
                                                        class="icon-shopping-cart"></i></a>
                                            </div>
                                            <a href="/ajax/product-quick-view.html" class="btn-quickview" title="Quick View">Quick
                                                View</a>
                                        </figure>
                                        <div class="product-details">
                                            <div class="category-wrap">
                                                <div class="category-list">
                                                    <a href="demo36-shop.html" class="product-category">category</a>
                                                </div>
                                                <a href="{{ route('wishlist.index') }}" class="btn-icon-wish"><i
                                                        class="icon-heart"></i></a>
                                            </div>
                                            <h3 class="product-title">
                                                <a href="demo36-product.html">White Brooch</a>
                                            </h3>
                                            <div class="ratings-container">
                                                <div class="product-ratings">
                                                    <span class="ratings" style="width:100%"></span>
                                                    <!-- End .ratings -->
                                                    <span class="tooltiptext tooltip-top"></span>
                                                </div>
                                                <!-- End .product-ratings -->
                                            </div>
                                            <!-- End .product-container -->
                                            <div class="price-box">
                                                <span class="old-price">$29.00</span>
                                                <span class="product-price">$19.00</span>
                                            </div>
                                            <!-- End .price-box -->
                                        </div>
                                        <!-- End .product-details -->
                                    </div>

                                    <div class="product-default inner-quickview inner-icon">
                                        <figure>
                                            <a href="demo36-product.html">
                                                <img src="/frontend-assets/images/demoes/demo36/products/product-15.jpg" width="239" height="239" alt="product">
                                            </a>
                                            <div class="btn-icon-group">
                                                <a href="#" class="btn-icon btn-add-cart product-type-simple"><i
                                                        class="icon-shopping-cart"></i></a>
                                            </div>
                                            <a href="/ajax/product-quick-view.html" class="btn-quickview" title="Quick View">Quick
                                                View</a>
                                        </figure>
                                        <div class="product-details">
                                            <div class="category-wrap">
                                                <div class="category-list">
                                                    <a href="demo36-shop.html" class="product-category">category</a>
                                                </div>
                                                <a href="{{ route('wishlist.index') }}" class="btn-icon-wish"><i
                                                        class="icon-heart"></i></a>
                                            </div>
                                            <h3 class="product-title">
                                                <a href="demo36-product.html">Tea bowl</a>
                                            </h3>
                                            <div class="ratings-container">
                                                <div class="product-ratings">
                                                    <span class="ratings" style="width:100%"></span>
                                                    <!-- End .ratings -->
                                                    <span class="tooltiptext tooltip-top"></span>
                                                </div>
                                                <!-- End .product-ratings -->
                                            </div>
                                            <!-- End .product-container -->
                                            <div class="price-box">
                                                <span class="old-price">$29.00</span>
                                                <span class="product-price">$19.00</span>
                                            </div>
                                            <!-- End .price-box -->
                                        </div>
                                        <!-- End .product-details -->
                                    </div>

                                    <div class="product-default inner-quickview inner-icon">
                                        <figure>
                                            <a href="demo36-product.html">
                                                <img src="/frontend-assets/images/demoes/demo36/products/product-17.jpg" width="239" height="239" alt="product">
                                            </a>
                                            <div class="btn-icon-group">
                                                <a href="#" class="btn-icon btn-add-cart product-type-simple"><i
                                                        class="icon-shopping-cart"></i></a>
                                            </div>
                                            <a href="/ajax/product-quick-view.html" class="btn-quickview" title="Quick View">Quick
                                                View</a>
                                        </figure>
                                        <div class="product-details">
                                            <div class="category-wrap">
                                                <div class="category-list">
                                                    <a href="demo36-shop.html" class="product-category">category</a>
                                                </div>
                                                <a href="{{ route('wishlist.index') }}" class="btn-icon-wish"><i
                                                        class="icon-heart"></i></a>
                                            </div>
                                            <h3 class="product-title">
                                                <a href="demo36-product.html">White ring</a>
                                            </h3>
                                            <div class="ratings-container">
                                                <div class="product-ratings">
                                                    <span class="ratings" style="width:100%"></span>
                                                    <!-- End .ratings -->
                                                    <span class="tooltiptext tooltip-top"></span>
                                                </div>
                                                <!-- End .product-ratings -->
                                            </div>
                                            <!-- End .product-container -->
                                            <div class="price-box">
                                                <span class="old-price">$29.00</span>
                                                <span class="product-price">$19.00</span>
                                            </div>
                                            <!-- End .price-box -->
                                        </div>
                                        <!-- End .product-details -->
                                    </div>

                                    <div class="product-default inner-quickview inner-icon">
                                        <figure>
                                            <a href="demo36-product.html">
                                                <img src="/frontend-assets/images/demoes/demo36/products/product-14.jpg" width="239" height="239" alt="product">
                                            </a>
                                            <div class="btn-icon-group">
                                                <a href="#" class="btn-icon btn-add-cart product-type-simple"><i
                                                        class="icon-shopping-cart"></i></a>
                                            </div>
                                            <a href="/ajax/product-quick-view.html" class="btn-quickview" title="Quick View">Quick
                                                View</a>
                                        </figure>
                                        <div class="product-details">
                                            <div class="category-wrap">
                                                <div class="category-list">
                                                    <a href="demo36-shop.html" class="product-category">category</a>
                                                </div>
                                                <a href="{{ route('wishlist.index') }}" class="btn-icon-wish"><i
                                                        class="icon-heart"></i></a>
                                            </div>
                                            <h3 class="product-title">
                                                <a href="demo36-product.html">PT Cup</a>
                                            </h3>
                                            <div class="ratings-container">
                                                <div class="product-ratings">
                                                    <span class="ratings" style="width:100%"></span>
                                                    <!-- End .ratings -->
                                                    <span class="tooltiptext tooltip-top"></span>
                                                </div>
                                                <!-- End .product-ratings -->
                                            </div>
                                            <!-- End .product-container -->
                                            <div class="price-box">
                                                <span class="old-price">$29.00</span>
                                                <span class="product-price">$19.00</span>
                                            </div>
                                            <!-- End .price-box -->
                                        </div>
                                        <!-- End .product-details -->
                                    </div>

                                    <div class="product-default inner-quickview inner-icon">
                                        <figure>
                                            <a href="demo36-product.html">
                                                <img src="/frontend-assets/images/demoes/demo36/products/product-13.jpg" width="239" height="239" alt="product">
                                            </a>
                                            <div class="btn-icon-group">
                                                <a href="#" class="btn-icon btn-add-cart product-type-simple"><i
                                                        class="icon-shopping-cart"></i></a>
                                            </div>
                                            <a href="/ajax/product-quick-view.html" class="btn-quickview" title="Quick View">Quick
                                                View</a>
                                        </figure>
                                        <div class="product-details">
                                            <div class="category-wrap">
                                                <div class="category-list">
                                                    <a href="demo36-shop.html" class="product-category">category</a>
                                                </div>
                                                <a href="{{ route('wishlist.index') }}" class="btn-icon-wish"><i
                                                        class="icon-heart"></i></a>
                                            </div>
                                            <h3 class="product-title">
                                                <a href="demo36-product.html">Belt accessories</a>
                                            </h3>
                                            <div class="ratings-container">
                                                <div class="product-ratings">
                                                    <span class="ratings" style="width:100%"></span>
                                                    <!-- End .ratings -->
                                                    <span class="tooltiptext tooltip-top"></span>
                                                </div>
                                                <!-- End .product-ratings -->
                                            </div>
                                            <!-- End .product-container -->
                                            <div class="price-box">
                                                <span class="old-price">$29.00</span>
                                                <span class="product-price">$19.00</span>
                                            </div>
                                            <!-- End .price-box -->
                                        </div>
                                        <!-- End .product-details -->
                                    </div>

                                    <div class="product-default inner-quickview inner-icon">
                                        <figure>
                                            <a href="demo36-product.html">
                                                <img src="/frontend-assets/images/demoes/demo36/products/product-2.jpg" width="239" height="239" alt="product">
                                            </a>
                                            <div class="btn-icon-group">
                                                <a href="#" class="btn-icon btn-add-cart product-type-simple"><i
                                                        class="icon-shopping-cart"></i></a>
                                            </div>
                                            <a href="/ajax/product-quick-view.html" class="btn-quickview" title="Quick View">Quick
                                                View</a>
                                        </figure>
                                        <div class="product-details">
                                            <div class="category-wrap">
                                                <div class="category-list">
                                                    <a href="demo36-shop.html" class="product-category">category</a>
                                                </div>
                                                <a href="{{ route('wishlist.index') }}" class="btn-icon-wish"><i
                                                        class="icon-heart"></i></a>
                                            </div>
                                            <h3 class="product-title">
                                                <a href="demo36-product.html">PT Speaker</a>
                                            </h3>
                                            <div class="ratings-container">
                                                <div class="product-ratings">
                                                    <span class="ratings" style="width:100%"></span>
                                                    <!-- End .ratings -->
                                                    <span class="tooltiptext tooltip-top"></span>
                                                </div>
                                                <!-- End .product-ratings -->
                                            </div>
                                            <!-- End .product-container -->
                                            <div class="price-box">
                                                <span class="old-price">$29.00</span>
                                                <span class="product-price">$19.00</span>
                                            </div>
                                            <!-- End .price-box -->
                                        </div>
                                        <!-- End .product-details -->
                                    </div>

                                    <div class="product-default inner-quickview inner-icon">
                                        <figure>
                                            <a href="demo36-product.html">
                                                <img src="/frontend-assets/images/demoes/demo36/products/product-21.jpg" width="239" height="239" alt="product">
                                            </a>
                                            <div class="btn-icon-group">
                                                <a href="#" class="btn-icon btn-add-cart product-type-simple"><i
                                                        class="icon-shopping-cart"></i></a>
                                            </div>
                                            <a href="/ajax/product-quick-view.html" class="btn-quickview" title="Quick View">Quick
                                                View</a>
                                        </figure>
                                        <div class="product-details">
                                            <div class="category-wrap">
                                                <div class="category-list">
                                                    <a href="demo36-shop.html" class="product-category">category</a>
                                                </div>
                                                <a href="{{ route('wishlist.index') }}" class="btn-icon-wish"><i
                                                        class="icon-heart"></i></a>
                                            </div>
                                            <h3 class="product-title">
                                                <a href="demo36-product.html">Wooden Chair</a>
                                            </h3>
                                            <div class="ratings-container">
                                                <div class="product-ratings">
                                                    <span class="ratings" style="width:100%"></span>
                                                    <!-- End .ratings -->
                                                    <span class="tooltiptext tooltip-top"></span>
                                                </div>
                                                <!-- End .product-ratings -->
                                            </div>
                                            <!-- End .product-container -->
                                            <div class="price-box">
                                                <span class="old-price">$29.00</span>
                                                <span class="product-price">$19.00</span>
                                            </div>
                                            <!-- End .price-box -->
                                        </div>
                                        <!-- End .product-details -->
                                    </div>
                                </div>
                                <!-- End .products-slider -->
                            </div>
                            <div class="tab-pane fade" id="new-two" role="tabpanel" aria-labelledby="new-two-tab">
                                <div class="products-slider owl-carousel nav-circle carousel-with-bg owl-theme pb-0" data-owl-options="{
                                    'margin': 1,
                                    'navText': [ '<i class=icon-left-open-big>', '<i class=icon-right-open-big>' ],
                                    'dots': false,
                                    'nav': true,
                                    'loop': false,
                                    'responsive': {
                                        '0': {
                                            'items': 2
                                        },
                                        '576': {
                                            'items': 3
                                        },
                                        '768': {
                                            'items': 4
                                        },
                                        '992': {
                                            'items': 5
                                        },
                                        '1200': {
                                            'items': 6
                                        }
                                    }
                                }">

                                    <div class="product-default inner-quickview inner-icon">
                                        <figure>
                                            <a href="demo36-product.html">
                                                <img src="/frontend-assets/images/demoes/demo36/products/product-14.jpg" width="239" height="239" alt="product">
                                            </a>
                                            <div class="btn-icon-group">
                                                <a href="#" class="btn-icon btn-add-cart product-type-simple"><i
                                                        class="icon-shopping-cart"></i></a>
                                            </div>
                                            <a href="/ajax/product-quick-view.html" class="btn-quickview" title="Quick View">Quick
                                                View</a>
                                        </figure>
                                        <div class="product-details">
                                            <div class="category-wrap">
                                                <div class="category-list">
                                                    <a href="demo36-shop.html" class="product-category">category</a>
                                                </div>
                                                <a href="{{ route('wishlist.index') }}" class="btn-icon-wish"><i
                                                        class="icon-heart"></i></a>
                                            </div>
                                            <h3 class="product-title">
                                                <a href="demo36-product.html">PT Cup</a>
                                            </h3>
                                            <div class="ratings-container">
                                                <div class="product-ratings">
                                                    <span class="ratings" style="width:100%"></span>
                                                    <!-- End .ratings -->
                                                    <span class="tooltiptext tooltip-top"></span>
                                                </div>
                                                <!-- End .product-ratings -->
                                            </div>
                                            <!-- End .product-container -->
                                            <div class="price-box">
                                                <span class="old-price">$29.00</span>
                                                <span class="product-price">$19.00</span>
                                            </div>
                                            <!-- End .price-box -->
                                        </div>
                                        <!-- End .product-details -->
                                    </div>

                                    <div class="product-default inner-quickview inner-icon">
                                        <figure>
                                            <a href="demo36-product.html">
                                                <img src="/frontend-assets/images/demoes/demo36/products/product-13.jpg" width="239" height="239" alt="product">
                                            </a>
                                            <div class="btn-icon-group">
                                                <a href="#" class="btn-icon btn-add-cart product-type-simple"><i
                                                        class="icon-shopping-cart"></i></a>
                                            </div>
                                            <a href="/ajax/product-quick-view.html" class="btn-quickview" title="Quick View">Quick
                                                View</a>
                                        </figure>
                                        <div class="product-details">
                                            <div class="category-wrap">
                                                <div class="category-list">
                                                    <a href="demo36-shop.html" class="product-category">category</a>
                                                </div>
                                                <a href="{{ route('wishlist.index') }}" class="btn-icon-wish"><i
                                                        class="icon-heart"></i></a>
                                            </div>
                                            <h3 class="product-title">
                                                <a href="demo36-product.html">Belt accessories</a>
                                            </h3>
                                            <div class="ratings-container">
                                                <div class="product-ratings">
                                                    <span class="ratings" style="width:100%"></span>
                                                    <!-- End .ratings -->
                                                    <span class="tooltiptext tooltip-top"></span>
                                                </div>
                                                <!-- End .product-ratings -->
                                            </div>
                                            <!-- End .product-container -->
                                            <div class="price-box">
                                                <span class="old-price">$29.00</span>
                                                <span class="product-price">$19.00</span>
                                            </div>
                                            <!-- End .price-box -->
                                        </div>
                                        <!-- End .product-details -->
                                    </div>

                                    <div class="product-default inner-quickview inner-icon">
                                        <figure>
                                            <a href="demo36-product.html">
                                                <img src="/frontend-assets/images/demoes/demo36/products/product-2.jpg" width="239" height="239" alt="product">
                                            </a>
                                            <div class="btn-icon-group">
                                                <a href="#" class="btn-icon btn-add-cart product-type-simple"><i
                                                        class="icon-shopping-cart"></i></a>
                                            </div>
                                            <a href="/ajax/product-quick-view.html" class="btn-quickview" title="Quick View">Quick
                                                View</a>
                                        </figure>
                                        <div class="product-details">
                                            <div class="category-wrap">
                                                <div class="category-list">
                                                    <a href="demo36-shop.html" class="product-category">category</a>
                                                </div>
                                                <a href="{{ route('wishlist.index') }}" class="btn-icon-wish"><i
                                                        class="icon-heart"></i></a>
                                            </div>
                                            <h3 class="product-title">
                                                <a href="demo36-product.html">PT Speaker</a>
                                            </h3>
                                            <div class="ratings-container">
                                                <div class="product-ratings">
                                                    <span class="ratings" style="width:100%"></span>
                                                    <!-- End .ratings -->
                                                    <span class="tooltiptext tooltip-top"></span>
                                                </div>
                                                <!-- End .product-ratings -->
                                            </div>
                                            <!-- End .product-container -->
                                            <div class="price-box">
                                                <span class="old-price">$29.00</span>
                                                <span class="product-price">$19.00</span>
                                            </div>
                                            <!-- End .price-box -->
                                        </div>
                                        <!-- End .product-details -->
                                    </div>

                                    <div class="product-default inner-quickview inner-icon">
                                        <figure>
                                            <a href="demo36-product.html">
                                                <img src="/frontend-assets/images/demoes/demo36/products/product-21.jpg" width="239" height="239" alt="product">
                                            </a>
                                            <div class="btn-icon-group">
                                                <a href="#" class="btn-icon btn-add-cart product-type-simple"><i
                                                        class="icon-shopping-cart"></i></a>
                                            </div>
                                            <a href="/ajax/product-quick-view.html" class="btn-quickview" title="Quick View">Quick
                                                View</a>
                                        </figure>
                                        <div class="product-details">
                                            <div class="category-wrap">
                                                <div class="category-list">
                                                    <a href="demo36-shop.html" class="product-category">category</a>
                                                </div>
                                                <a href="{{ route('wishlist.index') }}" class="btn-icon-wish"><i
                                                        class="icon-heart"></i></a>
                                            </div>
                                            <h3 class="product-title">
                                                <a href="demo36-product.html">Wooden Chair</a>
                                            </h3>
                                            <div class="ratings-container">
                                                <div class="product-ratings">
                                                    <span class="ratings" style="width:100%"></span>
                                                    <!-- End .ratings -->
                                                    <span class="tooltiptext tooltip-top"></span>
                                                </div>
                                                <!-- End .product-ratings -->
                                            </div>
                                            <!-- End .product-container -->
                                            <div class="price-box">
                                                <span class="old-price">$29.00</span>
                                                <span class="product-price">$19.00</span>
                                            </div>
                                            <!-- End .price-box -->
                                        </div>
                                        <!-- End .product-details -->
                                    </div>
                                </div>
                                <!-- End .products-slider -->
                            </div>
                            <div class="tab-pane fade" id="best-two" role="tabpanel" aria-labelledby="best-tab">
                                <div class="products-slider owl-carousel nav-circle carousel-with-bg owl-theme" data-owl-options="{
                                    'margin': 1,
                                    'dots': false,
                                    'navText': [ '<i class=icon-left-open-big>', '<i class=icon-right-open-big>' ],
                                    'nav': true,
                                    'loop': false,
                                    'responsive': {
                                        '0': {
                                            'items': 2
                                        },
                                        '576': {
                                            'items': 3
                                        },
                                        '768': {
                                            'items': 4
                                        },
                                        '992': {
                                            'items': 5
                                        },
                                        '1200': {
                                            'items': 6
                                        }
                                    }
                                }">
                                    <div class="product-default inner-quickview inner-icon">
                                        <figure>
                                            <a href="demo36-product.html">
                                                <img src="/frontend-assets/images/demoes/demo36/products/product-20.jpg" width="239" height="239" alt="product">
                                            </a>
                                            <div class="btn-icon-group">
                                                <a href="#" class="btn-icon btn-add-cart product-type-simple"><i
                                                        class="icon-shopping-cart"></i></a>
                                            </div>
                                            <a href="/ajax/product-quick-view.html" class="btn-quickview" title="Quick View">Quick
                                                View</a>
                                        </figure>
                                        <div class="product-details">
                                            <div class="category-wrap">
                                                <div class="category-list">
                                                    <a href="demo36-shop.html" class="product-category">category</a>
                                                </div>
                                                <a href="{{ route('wishlist.index') }}" class="btn-icon-wish"><i
                                                        class="icon-heart"></i></a>
                                            </div>
                                            <h3 class="product-title">
                                                <a href="demo36-product.html">White Sofa</a>
                                            </h3>
                                            <div class="ratings-container">
                                                <div class="product-ratings">
                                                    <span class="ratings" style="width:100%"></span>
                                                    <!-- End .ratings -->
                                                    <span class="tooltiptext tooltip-top"></span>
                                                </div>
                                                <!-- End .product-ratings -->
                                            </div>
                                            <!-- End .product-container -->
                                            <div class="price-box">
                                                <span class="old-price">$29.00</span>
                                                <span class="product-price">$19.00</span>
                                            </div>
                                            <!-- End .price-box -->
                                        </div>
                                        <!-- End .product-details -->
                                    </div>
                                </div>
                                <!-- End .products-slider -->
                            </div>
                        </div>
                    </div>

                    <div class="top-notice bg-dark text-white  top-notice-bg appear-animate" data-animation-name="fadeIn" data-animation-delay="100">
                        <div class="container text-center d-flex align-items-center justify-content-center flex-column flex-xl-row ">
                            <img src="/frontend-assets/images/demoes/demo36/shop-logo.png" width="116" height="23" alt="logo" />
                            <h5 class="d-inline-block mb-0 pl-3 pr-3 pt-1 pb-1">The Lowest Prices Once A Month! Hurry To Snap Up
                            </h5>
                            <a href="demo36-shop.html" class="btn btn-darkcategory ls-n-0 mt-xl-0 mt-1">SHOP NOW!</a>
                        </div>
                        <!-- End .container -->
                    </div>
                    <!-- End .top-notice -->

                    <div class="recent-products-section appear-animate" data-animation-name="fadeIn" data-animation-delay="100">
                        <div class="heading shop-list d-flex align-items-center flex-wrap bg-gray mb-0 pl-0 pr-0">
                            <h4 class="section-title text-transform-none mb-0 mr-0">Recently Viewed Products</h4>
                            <a class="view-all ml-auto" href="demo36-shop.html">View
                                All<i class="fas fa-long-arrow-alt-right"></i></a>
                        </div>
                        <div class="products-slider owl-carousel owl-theme carousel-with-bg nav-circle pb-0" data-owl-options="{
                            'margin': 1,
                            'navText': [ '<i class=icon-left-open-big>', '<i class=icon-right-open-big>' ],
                            'dots': false,
                            'nav': true,
                            'loop': false,
                            'responsive': {
                                '0': {
                                    'items': 2
                                },
                                '576': {
                                    'items': 3
                                },
                                '768': {
                                    'items': 4
                                },
                                '992': {
                                    'items': 5
                                },
                                '1200': {
                                    'items': 6
                                }
                            }
                        }">

                            <div class="product-default inner-quickview inner-icon">
                                <figure>
                                    <a href="demo36-product.html">
                                        <img src="/frontend-assets/images/demoes/demo36/products/product-1.jpg" width="239" height="239" alt="product">
                                    </a>
                                    <div class="btn-icon-group">
                                        <a href="#" title="Add To Cart" class="btn-icon btn-add-cart product-type-simple"><i
                                                class="icon-shopping-cart"></i></a>
                                    </div>
                                    <a href="/ajax/product-quick-view.html" class="btn-quickview" title="Quick View">Quick
                                        View</a>
                                </figure>
                                <div class="product-details">
                                    <div class="category-wrap">
                                        <div class="category-list">
                                            <a href="demo36-shop.html" class="product-category">category</a>
                                        </div>
                                        <a href="{{ route('wishlist.index') }}" title="Add to Wishlist" class="btn-icon-wish"><i
                                                class="icon-heart"></i></a>
                                    </div>
                                    <h3 class="product-title">
                                        <a href="demo36-product.html">Drone Pro</a>
                                    </h3>
                                    <div class="ratings-container">
                                        <div class="product-ratings">
                                            <span class="ratings" style="width:100%"></span>
                                            <!-- End .ratings -->
                                            <span class="tooltiptext tooltip-top"></span>
                                        </div>
                                        <!-- End .product-ratings -->
                                    </div>
                                    <!-- End .product-container -->
                                    <div class="price-box">
                                        <span class="old-price">$29.00</span>
                                        <span class="product-price">$19.00</span>
                                    </div>
                                    <!-- End .price-box -->
                                </div>
                                <!-- End .product-details -->
                            </div>

                            <div class="product-default inner-quickview inner-icon">
                                <figure>
                                    <a href="demo36-product.html">
                                        <img src="/frontend-assets/images/demoes/demo36/products/product-8.jpg" width="239" height="239" alt="product">
                                    </a>
                                    <div class="btn-icon-group">
                                        <a href="#" title="Add To Cart" class="btn-icon btn-add-cart product-type-simple"><i
                                                class="icon-shopping-cart"></i></a>
                                    </div>
                                    <a href="/ajax/product-quick-view.html" class="btn-quickview" title="Quick View">Quick
                                        View</a>
                                </figure>
                                <div class="product-details">
                                    <div class="category-wrap">
                                        <div class="category-list">
                                            <a href="demo36-shop.html" class="product-category">category</a>
                                        </div>
                                        <a href="{{ route('wishlist.index') }}" title="Add to Wishlist" class="btn-icon-wish"><i
                                                class="icon-heart"></i></a>
                                    </div>
                                    <h3 class="product-title">
                                        <a href="demo36-product.html">PT Bag</a>
                                    </h3>
                                    <div class="ratings-container">
                                        <div class="product-ratings">
                                            <span class="ratings" style="width:100%"></span>
                                            <!-- End .ratings -->
                                            <span class="tooltiptext tooltip-top"></span>
                                        </div>
                                        <!-- End .product-ratings -->
                                    </div>
                                    <!-- End .product-container -->
                                    <div class="price-box">
                                        <span class="old-price">$29.00</span>
                                        <span class="product-price">$19.00</span>
                                    </div>
                                    <!-- End .price-box -->
                                </div>
                                <!-- End .product-details -->
                            </div>

                            <div class="product-default inner-quickview inner-icon">
                                <figure>
                                    <a href="demo36-product.html">
                                        <img src="/frontend-assets/images/demoes/demo36/products/product-7.jpg" width="239" height="239" alt="product">
                                    </a>
                                    <div class="btn-icon-group">
                                        <a href="#" title="Add To Cart" class="btn-icon btn-add-cart product-type-simple"><i
                                                class="icon-shopping-cart"></i></a>
                                    </div>
                                    <a href="/ajax/product-quick-view.html" class="btn-quickview" title="Quick View">Quick
                                        View</a>
                                </figure>
                                <div class="product-details">
                                    <div class="category-wrap">
                                        <div class="category-list">
                                            <a href="demo36-shop.html" class="product-category">category</a>
                                        </div>
                                        <a href="{{ route('wishlist.index') }}" title="Add to Wishlist" class="btn-icon-wish"><i
                                                class="icon-heart"></i></a>
                                    </div>
                                    <h3 class="product-title">
                                        <a href="demo36-product.html">Soft Hat</a>
                                    </h3>
                                    <div class="ratings-container">
                                        <div class="product-ratings">
                                            <span class="ratings" style="width:100%"></span>
                                            <!-- End .ratings -->
                                            <span class="tooltiptext tooltip-top"></span>
                                        </div>
                                        <!-- End .product-ratings -->
                                    </div>
                                    <!-- End .product-container -->
                                    <div class="price-box">
                                        <span class="old-price">$29.00</span>
                                        <span class="product-price">$19.00</span>
                                    </div>
                                    <!-- End .price-box -->
                                </div>
                                <!-- End .product-details -->
                            </div>

                            <div class="product-default inner-quickview inner-icon">
                                <figure>
                                    <a href="demo36-product.html">
                                        <img src="/frontend-assets/images/demoes/demo36/products/product-17.jpg" width="239" height="239" alt="product">
                                    </a>
                                    <div class="btn-icon-group">
                                        <a href="#" title="Add To Cart" class="btn-icon btn-add-cart product-type-simple"><i
                                                class="icon-shopping-cart"></i></a>
                                    </div>
                                    <a href="/ajax/product-quick-view.html" class="btn-quickview" title="Quick View">Quick
                                        View</a>
                                </figure>
                                <div class="product-details">
                                    <div class="category-wrap">
                                        <div class="category-list">
                                            <a href="demo36-shop.html" class="product-category">category</a>
                                        </div>
                                        <a href="{{ route('wishlist.index') }}" title="Add to Wishlist" class="btn-icon-wish"><i
                                                class="icon-heart"></i></a>
                                    </div>
                                    <h3 class="product-title">
                                        <a href="demo36-product.html">White ring</a>
                                    </h3>
                                    <div class="ratings-container">
                                        <div class="product-ratings">
                                            <span class="ratings" style="width:100%"></span>
                                            <!-- End .ratings -->
                                            <span class="tooltiptext tooltip-top"></span>
                                        </div>
                                        <!-- End .product-ratings -->
                                    </div>
                                    <!-- End .product-container -->
                                    <div class="price-box">
                                        <span class="old-price">$29.00</span>
                                        <span class="product-price">$19.00</span>
                                    </div>
                                    <!-- End .price-box -->
                                </div>
                                <!-- End .product-details -->
                            </div>

                            <div class="product-default inner-quickview inner-icon">
                                <figure>
                                    <a href="demo36-product.html">
                                        <img src="/frontend-assets/images/demoes/demo36/products/product-10.jpg" width="239" height="239" alt="product">
                                    </a>
                                    <div class="btn-icon-group">
                                        <a href="#" title="Add To Cart" class="btn-icon btn-add-cart product-type-simple"><i
                                                class="icon-shopping-cart"></i></a>
                                    </div>
                                    <a href="/ajax/product-quick-view.html" class="btn-quickview" title="Quick View">Quick
                                        View</a>
                                </figure>
                                <div class="product-details">
                                    <div class="category-wrap">
                                        <div class="category-list">
                                            <a href="demo36-shop.html" class="product-category">category</a>
                                        </div>
                                        <a href="{{ route('wishlist.index') }}" title="Add to Wishlist" class="btn-icon-wish"><i
                                                class="icon-heart"></i></a>
                                    </div>
                                    <h3 class="product-title">
                                        <a href="demo36-product.html">Black Bag</a>
                                    </h3>
                                    <div class="ratings-container">
                                        <div class="product-ratings">
                                            <span class="ratings" style="width:100%"></span>
                                            <!-- End .ratings -->
                                            <span class="tooltiptext tooltip-top"></span>
                                        </div>
                                        <!-- End .product-ratings -->
                                    </div>
                                    <!-- End .product-container -->
                                    <div class="price-box">
                                        <span class="old-price">$29.00</span>
                                        <span class="product-price">$19.00</span>
                                    </div>
                                    <!-- End .price-box -->
                                </div>
                                <!-- End .product-details -->
                            </div>

                            <div class="product-default inner-quickview inner-icon">
                                <figure>
                                    <a href="demo36-product.html">
                                        <img src="/frontend-assets/images/demoes/demo36/products/product-15.jpg" width="239" height="239" alt="product">
                                    </a>
                                    <div class="btn-icon-group">
                                        <a href="#" title="Add To Cart" class="btn-icon btn-add-cart product-type-simple"><i
                                                class="icon-shopping-cart"></i></a>
                                    </div>
                                    <a href="/ajax/product-quick-view.html" class="btn-quickview" title="Quick View">Quick
                                        View</a>
                                </figure>
                                <div class="product-details">
                                    <div class="category-wrap">
                                        <div class="category-list">
                                            <a href="demo36-shop.html" class="product-category">category</a>
                                        </div>
                                        <a href="{{ route('wishlist.index') }}" title="Add to Wishlist" class="btn-icon-wish"><i
                                                class="icon-heart"></i></a>
                                    </div>
                                    <h3 class="product-title">
                                        <a href="demo36-product.html">Tea bowl</a>
                                    </h3>
                                    <div class="ratings-container">
                                        <div class="product-ratings">
                                            <span class="ratings" style="width:100%"></span>
                                            <!-- End .ratings -->
                                            <span class="tooltiptext tooltip-top"></span>
                                        </div>
                                        <!-- End .product-ratings -->
                                    </div>
                                    <!-- End .product-container -->
                                    <div class="price-box">
                                        <span class="old-price">$29.00</span>
                                        <span class="product-price">$19.00</span>
                                    </div>
                                    <!-- End .price-box -->
                                </div>
                                <!-- End .product-details -->
                            </div>

                            <div class="product-default inner-quickview inner-icon">
                                <figure>
                                    <a href="demo36-product.html">
                                        <img src="/frontend-assets/images/demoes/demo36/products/product-3.jpg" width="239" height="239" alt="product">
                                    </a>
                                    <div class="btn-icon-group">
                                        <a href="#" title="Add To Cart" class="btn-icon btn-add-cart product-type-simple"><i
                                                class="icon-shopping-cart"></i></a>
                                    </div>
                                    <a href="/ajax/product-quick-view.html" class="btn-quickview" title="Quick View">Quick
                                        View</a>
                                </figure>
                                <div class="product-details">
                                    <div class="category-wrap">
                                        <div class="category-list">
                                            <a href="demo36-shop.html" class="product-category">category</a>
                                        </div>
                                        <a href="{{ route('wishlist.index') }}" title="Add to Wishlist" class="btn-icon-wish"><i
                                                class="icon-heart"></i></a>
                                    </div>
                                    <h3 class="product-title">
                                        <a href="demo36-product.html">Beats Solo HD Drenched</a>
                                    </h3>
                                    <div class="ratings-container">
                                        <div class="product-ratings">
                                            <span class="ratings" style="width:100%"></span>
                                            <!-- End .ratings -->
                                            <span class="tooltiptext tooltip-top"></span>
                                        </div>
                                        <!-- End .product-ratings -->
                                    </div>
                                    <!-- End .product-container -->
                                    <div class="price-box">
                                        <span class="old-price">$29.00</span>
                                        <span class="product-price">$19.00</span>
                                    </div>
                                    <!-- End .price-box -->
                                </div>
                                <!-- End .product-details -->
                            </div>
                        </div>
                        <!-- End .products-slider -->
                    </div>
</div></div>
