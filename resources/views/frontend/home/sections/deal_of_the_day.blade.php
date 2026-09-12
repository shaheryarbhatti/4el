{{-- Home section: deal_of_the_day (from Porto demo36). Managed in Admin > Pages > Home Sections. --}}
<div class="container">
                <div class="deal-products-section">
                    <h2 class="section-title d-flex align-items-center text-transform-none"><i class="icon-percent-shape"></i>Special Offers
                    </h2>
                    <div class="row appear-animate" data-animation-name="fadeInUpShorter" data-animation-delay="200">
                        <div class="col-md-4 mb-2 mb-md-0">
                            <div class="product-default deal-product">
                                <figure>
                                    <a href="demo36-product.html">
                                        <img src="/frontend-assets/images/demoes/demo36/products/product-1.jpg" width="450" height="450" alt="product">
                                    </a>
                                    <div class="product-countdown-container custom-product-countdown">
                                        <span class="product-countdown-title">offer ends in:</span>
                                        <div class="product-countdown countdown-compact" data-until="2021, 10, 5" data-compact="true">
                                        </div>
                                        <!-- End .product-countdown -->
                                    </div>
                                    <!-- End .product-countdown-container -->
                                </figure>
                                <div class="product-details">
                                    <div class="category-list">
                                        <a href="demo36-shop.html" class="product-category">Category</a>
                                    </div>
                                    <h3 class="product-title">
                                        <a href="demo36-product.html">Drone Pro</a>
                                    </h3>
                                    <div class="ratings-container">
                                        <div class="product-ratings">
                                            <span class="ratings" style="width:80%"></span>
                                            <!-- End .ratings -->
                                            <span class="tooltiptext tooltip-top"></span>
                                        </div>
                                        <!-- End .product-ratings -->
                                    </div>
                                    <!-- End .product-container -->
                                    <div class="price-box">
                                        <del class="old-price">$59.00</del>
                                        <span class="product-price">$49.00</span>
                                    </div>
                                    <!-- End .price-box -->
                                    <div class="product-action">
                                        <a href="{{ route('wishlist.index') }}" class="btn-icon-wish" title="wishlist"><i
                                                class="icon-heart"></i></a>
                                        <a href="#" class="btn btn-primary btn-icon btn-add-cart product-type-simple"><i
                                                class="icon-shopping-cart"></i><span>ADD TO CART</span></a>
                                        <a href="/ajax/product-quick-view.html" class="btn-quickview" title="Quick View"><i class="fas fa-external-link-alt"></i></a>
                                    </div>
                                </div>
                                <!-- End .product-details -->
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="products-with-divide">
                                <div class="row row-joined">
                                    <div class="col-xl-3 col-sm-4 col-6">
                                        <div class="product-default inner-quickview inner-icon">
                                            <figure>
                                                <a href="demo36-product.html">
                                                    <img src="/frontend-assets/images/demoes/demo36/products/product-2.jpg" width="239" height="239" alt="product">
                                                </a>
                                                <div class="label-group">
                                                    <div class="product-label label-hot">HOT</div>
                                                    <div class="product-label label-sale">-20%</div>
                                                </div>
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
                                    </div>
                                    <div class="col-xl-3 col-sm-4 col-6">
                                        <div class="product-default inner-quickview inner-icon">
                                            <figure>
                                                <a href="demo36-product.html">
                                                    <img src="/frontend-assets/images/demoes/demo36/products/product-3.jpg" width="239" height="239" alt="product">
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
                                                    <a href="product.html">Beats Solo HD Drenched</a>
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
                                    <div class="col-xl-3 col-sm-4 col-6">
                                        <div class="product-default inner-quickview inner-icon">
                                            <figure>
                                                <a href="demo36-product.html">
                                                    <img src="/frontend-assets/images/demoes/demo36/products/product-4.jpg" width="239" height="239" alt="product">
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
                                                    <a href="product.html">Palm Print Jacket</a>
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
                                    <div class="col-xl-3 col-sm-4 col-6">
                                        <div class="product-default inner-quickview inner-icon">
                                            <figure>
                                                <a href="demo36-product.html">
                                                    <img src="/frontend-assets/images/demoes/demo36/products/product-5.jpg" width="239" height="239" alt="product">
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
                                                    <a href="product.html">Camera</a>
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
                                    <div class="col-xl-3 col-sm-4 col-6">
                                        <div class="product-default inner-quickview inner-icon">
                                            <figure>
                                                <a href="demo36-product.html">
                                                    <img src="/frontend-assets/images/demoes/demo36/products/product-6.jpg" width="239" height="239" alt="product">
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
                                                    <a href="product.html">Red Football</a>
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
                                    <div class="col-xl-3 col-sm-4 col-6">
                                        <div class="product-default inner-quickview inner-icon">
                                            <figure>
                                                <a href="demo36-product.html">
                                                    <img src="/frontend-assets/images/demoes/demo36/products/product-7.jpg" width="239" height="239" alt="product">
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
                                                    <a href="product.html">Soft Hat</a>
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
                                    <div class="col-xl-3 col-sm-4 col-6">
                                        <div class="product-default inner-quickview inner-icon">
                                            <figure>
                                                <a href="demo36-product.html">
                                                    <img src="/frontend-assets/images/demoes/demo36/products/product-8.jpg" width="239" height="239" alt="product">
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
                                                    <a href="product.html">PT Bag</a>
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
                                    <div class="col-xl-3 col-sm-4 col-6">
                                        <div class="product-default inner-quickview inner-icon">
                                            <figure>
                                                <a href="demo36-product.html">
                                                    <img src="/frontend-assets/images/demoes/demo36/products/product-9.jpg" width="239" height="239" alt="product">
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
                                                    <a href="product.html">GM Camaro SS Original</a>
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
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
</div>
