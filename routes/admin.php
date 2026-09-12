<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\AdminLoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\HomeSectionController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\TaxClassController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\VendorController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\AuctionController;
use App\Http\Controllers\Admin\HeroSlideController;
use App\Http\Controllers\Admin\PromoBannerController;
use App\Http\Controllers\Admin\ProductReviewController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\CategorySpecificationController;

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES  (routes/admin.php)
|--------------------------------------------------------------------------
| These are automatically registered in bootstrap/app.php with:
|   • URL prefix   "/admin"
|   • name prefix  "admin."
|   • "web" middleware
|
| So a route defined here as ->name('dashboard') becomes:
|   URL   :  /admin/dashboard
|   name  :  admin.dashboard
|
| Everything EXCEPT the login pages is wrapped in the 'auth' + 'admin'
| middleware so only logged-in admins can reach it.
*/

/* ---------------------------------------------------------------------
 |  Admin authentication (login / logout) — publicly reachable
 * ------------------------------------------------------------------- */
Route::get('login',  [AdminLoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [AdminLoginController::class, 'login'])->name('login.submit');
Route::post('logout', [AdminLoginController::class, 'logout'])->name('logout');

/* ---------------------------------------------------------------------
 |  JSON API reachable by any authenticated user (vendors use this to
 |  load item specifications on the listing form)
 * ------------------------------------------------------------------- */
Route::middleware('auth')->get('api/categories/{category}/specifications',
    [CategorySpecificationController::class, 'apiSpecs']
)->name('api.category-specifications');

/* ---------------------------------------------------------------------
 |  Protected admin area — requires login + "admin" role
 * ------------------------------------------------------------------- */
Route::middleware(['auth', 'admin'])->group(function () {

    // Dashboard (home of the admin panel)
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    /* ---- Catalog: Categories, Brands, Tax classes ---- */
    Route::resource('categories', CategoryController::class)->except('show');
    Route::resource('brands', BrandController::class)->except('show');
    Route::resource('tax-classes', TaxClassController::class)->except('show');
    Route::resource('products', ProductController::class)->except('show');

    // Product reviews moderation
    Route::get('product-reviews',                   [ProductReviewController::class, 'index'])  ->name('product-reviews.index');
    Route::put('product-reviews/{review}/approve',  [ProductReviewController::class, 'approve'])->name('product-reviews.approve');
    Route::delete('product-reviews/{review}',       [ProductReviewController::class, 'destroy'])->name('product-reviews.destroy');

    /* ---- Vendors (approve / reject stores) ---- */
    Route::get('vendors', [VendorController::class, 'index'])->name('vendors.index');
    Route::get('vendors/{vendor}', [VendorController::class, 'show'])->name('vendors.show');
    Route::put('vendors/{vendor}/approve', [VendorController::class, 'approve'])->name('vendors.approve');
    Route::put('vendors/{vendor}/reject', [VendorController::class, 'reject'])->name('vendors.reject');

    /* ---- Auctions ---- */
    Route::get('auctions', [AuctionController::class, 'index'])->name('auctions.index');
    Route::get('auctions/{auction}', [AuctionController::class, 'show'])->name('auctions.show');

    /* ---- Customers ---- */
    Route::get('customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('customers/{customer}', [CustomerController::class, 'show'])->name('customers.show');
    Route::put('customers/{customer}/toggle', [CustomerController::class, 'toggle'])->name('customers.toggle');

    /* ---- Orders (all orders across vendors) ---- */
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::put('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');

    /* ---- Pages > Hero Slides ---- */
    Route::resource('hero-slides',   HeroSlideController::class)->except('show');
    Route::resource('promo-banners', PromoBannerController::class)->except('show');

    /* ---- Pages > Home Sections (dynamic front-end home management) ---- */
    Route::get('home-sections',              [HomeSectionController::class, 'index'])  ->name('home-sections.index');
    Route::post('home-sections',             [HomeSectionController::class, 'store'])  ->name('home-sections.store');
    Route::put('home-sections/order',        [HomeSectionController::class, 'reorder'])->name('home-sections.reorder');
    Route::put('home-sections/{homeSection}',[HomeSectionController::class, 'update']) ->name('home-sections.update');
    Route::put('home-sections/{homeSection}/toggle', [HomeSectionController::class, 'toggle'])->name('home-sections.toggle');
    Route::delete('home-sections/{homeSection}', [HomeSectionController::class, 'destroy'])->name('home-sections.destroy');

    /* ---- Category Specifications ---- */
    Route::get('specifications',                                               [CategorySpecificationController::class, 'overview'])  ->name('specifications.overview');
    Route::get('categories/{category}/specifications',              [CategorySpecificationController::class, 'index'])  ->name('category-specifications.index');
    Route::get('categories/{category}/specifications/create',      [CategorySpecificationController::class, 'create']) ->name('category-specifications.create');
    Route::post('categories/{category}/specifications',             [CategorySpecificationController::class, 'store'])  ->name('category-specifications.store');
    Route::get('categories/{category}/specifications/{specification}/edit', [CategorySpecificationController::class, 'edit'])   ->name('category-specifications.edit');
    Route::put('categories/{category}/specifications/{specification}',      [CategorySpecificationController::class, 'update']) ->name('category-specifications.update');
    Route::delete('categories/{category}/specifications/{specification}',   [CategorySpecificationController::class, 'destroy'])->name('category-specifications.destroy');
    Route::post('categories/{category}/specifications/reorder',     [CategorySpecificationController::class, 'reorder'])->name('category-specifications.reorder');

    /* ---- Coupons ---- */
    Route::resource('coupons', CouponController::class)->except('show');
    Route::put('coupons/{coupon}/toggle', [CouponController::class, 'toggle'])->name('coupons.toggle');

    /* ---- Settings (General, Payments, Google Map, SMTP) ---- */
    // Each tab is one URL; all tabs post back to the single "update" action.
    Route::get('settings/{group?}', [SettingController::class, 'index'])
        ->where('group', 'general|commission|payment|vendors|map|smtp')
        ->name('settings.index');
    Route::post('settings/{group}', [SettingController::class, 'update'])
        ->where('group', 'general|commission|payment|vendors|map|smtp')
        ->name('settings.update');
});
