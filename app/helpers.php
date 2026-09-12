<?php

use App\Models\Setting;
use App\Services\WishlistService;

/*
|--------------------------------------------------------------------------
| Global helper functions
|--------------------------------------------------------------------------
| These are autoloaded (see composer.json "autoload.files") so they are
| available everywhere: controllers, Blade views, mailers, etc.
*/

if (! function_exists('setting')) {
    /**
     * Read a site setting saved in the admin panel.
     *
     *   setting('site_name')                  -> value or null
     *   setting('currency', 'USD')            -> value or the given default
     *
     * @param  string  $key      the setting key
     * @param  mixed   $default  fallback when the key is not set
     */
    function setting(string $key, $default = null)
    {
        return Setting::get($key, $default);
    }
}

if (! function_exists('wishlist_count')) {
    /** Number of items in the current wishlist (guest session or DB). */
    function wishlist_count(): int
    {
        return WishlistService::count();
    }
}

if (! function_exists('wishlist_has')) {
    /** Whether a product is in the current wishlist. */
    function wishlist_has(int $productId): bool
    {
        return WishlistService::has($productId);
    }
}
