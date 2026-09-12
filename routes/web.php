<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\CategoryController;
use App\Http\Controllers\Frontend\ProductController as FrontendProduct;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\Auth\LoginController;
use App\Http\Controllers\Frontend\Auth\RegisterController;
use App\Http\Controllers\Frontend\BidController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\WishlistController;
use App\Http\Controllers\Frontend\OrderController as CustomerOrder;
use App\Http\Controllers\Frontend\AccountController;
use App\Http\Controllers\Vendor\OrderController as VendorOrder;
use App\Http\Controllers\Vendor\ApplicationController as VendorApplication;
use App\Http\Controllers\Vendor\DashboardController as VendorDashboard;
use App\Http\Controllers\Vendor\ProductController as VendorProduct;
use App\Http\Controllers\Vendor\ShopController as VendorShop;
use App\Http\Controllers\Frontend\SearchController;
use App\Http\Controllers\Frontend\PageController;

/*
|--------------------------------------------------------------------------
| FRONTEND ROUTES  (routes/web.php)
|--------------------------------------------------------------------------
| Everything the public storefront / customers / vendors see lives here.
| Admin routes are kept completely separate in routes/admin.php.
*/

// Storefront home page (its sections are managed from the admin panel).
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/quick-view/{product}', [HomeController::class, 'quickView'])->name('product.quick-view');

// Search
Route::get('/search', [SearchController::class, 'search'])->name('search');
Route::get('/search/autocomplete', [SearchController::class, 'autocomplete'])->name('search.autocomplete');

// Static pages
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'contactSubmit'])->name('contact.submit');
Route::get('/about', [PageController::class, 'about'])->name('about');

// Category / subcategory product listing
Route::get('/shop/{category:slug}', [CategoryController::class, 'show'])->name('category.show');

// Product detail page
Route::get('/product/{product:slug}', [FrontendProduct::class, 'show'])->name('product.show');
Route::post('/product/{product:slug}/review', [FrontendProduct::class, 'storeReview'])->name('product.review.store');
Route::middleware('auth')->post('/product/{product}/bid', [BidController::class, 'store'])->name('product.bid');

// Shopping cart
Route::get('/cart',                          [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{product}',           [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update',                  [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{productId}',    [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear',                   [CartController::class, 'clear'])->name('cart.clear');
Route::post('/cart/coupon/apply',            [CartController::class, 'applyCoupon'])->name('cart.coupon.apply');
Route::post('/cart/coupon/remove',           [CartController::class, 'removeCoupon'])->name('cart.coupon.remove');

/* ---- Wishlist / Watchlist (guests allowed — stored in session, merged on login) ---- */
Route::get('/wishlist',                       [WishlistController::class, 'index'])->name('wishlist.index');
Route::post('/wishlist/toggle/{product}',     [WishlistController::class, 'toggle'])->name('wishlist.toggle');
Route::delete('/wishlist/{product}',          [WishlistController::class, 'remove'])->name('wishlist.remove');

/* ---- Checkout & payments (requires login) ---- */
Route::middleware('auth')->group(function () {
    Route::get('/checkout',                    [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout',                   [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/success/{order}',    [CheckoutController::class, 'success'])->name('checkout.success');
    Route::get('/checkout/cancel/{order}',     [CheckoutController::class, 'cancel'])->name('checkout.cancel');
    Route::get('/checkout/paypal/return/{order}', [CheckoutController::class, 'paypalReturn'])->name('checkout.paypal.return');
});

/* ---- Customer authentication ---- */
Route::get('login',  [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login'])->name('login.submit');
Route::get('register',  [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('register', [RegisterController::class, 'register'])->name('register.submit');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

/* ---- Password reset ---- */
Route::get('forgot-password', [\App\Http\Controllers\Frontend\Auth\ForgotPasswordController::class, 'show'])->name('password.request');
Route::post('forgot-password', [\App\Http\Controllers\Frontend\Auth\ForgotPasswordController::class, 'send'])->name('password.email');
Route::get('reset-password/{token}', [\App\Http\Controllers\Frontend\Auth\ResetPasswordController::class, 'show'])->name('password.reset');
Route::post('reset-password', [\App\Http\Controllers\Frontend\Auth\ResetPasswordController::class, 'reset'])->name('password.update');

/*
|--------------------------------------------------------------------------
| VENDOR AREA (frontend, Porto-styled)
|--------------------------------------------------------------------------
| Vendors log in from the SAME storefront login. The apply/pending pages just
| need a logged-in user; the actual store dashboard requires an APPROVED vendor
| (the 'vendor' middleware enforces this).
*/
Route::middleware('auth')->prefix('vendor')->name('vendor.')->group(function () {
    // Become a vendor + status page (any logged-in user)
    Route::get('apply',   [VendorApplication::class, 'create'])->name('apply');
    Route::post('apply',  [VendorApplication::class, 'store'])->name('apply.store');
    Route::get('pending', [VendorApplication::class, 'pending'])->name('pending');

    // Approved-vendor-only store management
    Route::middleware('vendor')->group(function () {
        Route::get('/', [VendorDashboard::class, 'index'])->name('dashboard');

        Route::resource('products', VendorProduct::class)->except('show');

        Route::get('shop',  [VendorShop::class, 'edit'])->name('shop.edit');
        Route::put('shop',  [VendorShop::class, 'update'])->name('shop.update');

        // Orders that contain this vendor's items
        Route::get('orders', [VendorOrder::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [VendorOrder::class, 'show'])->name('orders.show');
        Route::put('orders/items/{item}/status', [VendorOrder::class, 'updateItemStatus'])->name('orders.item-status');

        // Earnings
        Route::get('earnings', [\App\Http\Controllers\Vendor\EarningsController::class, 'index'])->name('earnings.index');
    });
});

/* ---- Customer "My Account" area (requires login) ---- */
Route::middleware('auth')->prefix('account')->name('account.')->group(function () {
    Route::get('/', [AccountController::class, 'index'])->name('dashboard');
    Route::get('profile', [AccountController::class, 'editProfile'])->name('profile');
    Route::put('profile', [AccountController::class, 'updateProfile'])->name('profile.update');
    Route::get('orders', [CustomerOrder::class, 'index'])->name('orders');
    Route::get('orders/{order}', [CustomerOrder::class, 'show'])->name('orders.show');
    Route::put('password', [AccountController::class, 'changePassword'])->name('password.change');
});
