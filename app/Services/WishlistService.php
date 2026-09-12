<?php

namespace App\Services;

use App\Models\Product;
use App\Models\User;
use App\Models\Wishlist;

/**
 * WishlistService
 * ------------------------------------------------------------------
 * One place that knows WHERE a user's wishlist lives:
 *   • Guest (not logged in) -> the session (key "wishlist" = [productId,...])
 *   • Logged-in user        -> the `wishlists` DB table
 *
 * When a guest logs in, mergeToUser() moves their session wishlist into the
 * DB so nothing is lost (called from the login/register controllers).
 *
 * All methods are static so views/controllers can call them easily, e.g.
 *   WishlistService::count()  ·  WishlistService::has($id)
 */
class WishlistService
{
    private const SESSION_KEY = 'wishlist';

    /** Product IDs currently in the wishlist (guest session OR DB). */
    public static function ids(): array
    {
        if (auth()->check()) {
            return Wishlist::where('user_id', auth()->id())
                ->pluck('product_id')->map(fn ($id) => (int) $id)->all();
        }

        return array_map('intval', session(self::SESSION_KEY, []));
    }

    public static function count(): int
    {
        return count(self::ids());
    }

    public static function has(int $productId): bool
    {
        return in_array($productId, self::ids(), true);
    }

    /** Add a product (ignores duplicates / missing products). */
    public static function add(int $productId): void
    {
        if (! Product::whereKey($productId)->exists()) {
            return;
        }

        if (auth()->check()) {
            Wishlist::firstOrCreate(['user_id' => auth()->id(), 'product_id' => $productId]);
            return;
        }

        $ids = session(self::SESSION_KEY, []);
        if (! in_array($productId, $ids)) {
            $ids[] = $productId;
            session([self::SESSION_KEY => $ids]);
        }
    }

    public static function remove(int $productId): void
    {
        if (auth()->check()) {
            Wishlist::where('user_id', auth()->id())->where('product_id', $productId)->delete();
            return;
        }

        $ids = array_values(array_diff(session(self::SESSION_KEY, []), [$productId]));
        session([self::SESSION_KEY => $ids]);
    }

    /** Toggle membership; returns true if the product is now in the wishlist. */
    public static function toggle(int $productId): bool
    {
        if (self::has($productId)) {
            self::remove($productId);
            return false;
        }
        self::add($productId);
        return true;
    }

    /** The wishlist as live Product models (for the wishlist page). */
    public static function products()
    {
        $ids = self::ids();
        if (empty($ids)) {
            return collect();
        }

        // Preserve insertion order and only show live products.
        return Product::with(['primaryImage', 'vendor'])
            ->whereIn('id', $ids)
            ->get()
            ->sortBy(fn ($p) => array_search($p->id, $ids))
            ->values();
    }

    /**
     * Merge a guest's session wishlist into their DB wishlist after login,
     * then clear the session copy.
     */
    public static function mergeToUser(User $user): void
    {
        $ids = session(self::SESSION_KEY, []);
        foreach ($ids as $productId) {
            if (Product::whereKey($productId)->exists()) {
                Wishlist::firstOrCreate(['user_id' => $user->id, 'product_id' => (int) $productId]);
            }
        }
        session()->forget(self::SESSION_KEY);
    }
}
