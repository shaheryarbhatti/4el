<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\WishlistService;
use Illuminate\Http\Request;

/**
 * Wishlist / Watchlist (works for guests via session AND logged-in users via DB).
 */
class WishlistController extends Controller
{
    /** The wishlist page. */
    public function index()
    {
        $products = WishlistService::products();
        return view('frontend.wishlist', compact('products'));
    }

    /** Add/remove a product. Supports AJAX (returns JSON) and normal requests. */
    public function toggle(Request $request, Product $product)
    {
        $added = WishlistService::toggle($product->id);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'added'   => $added,
                'count'   => WishlistService::count(),
                'message' => $added ? 'Added to wishlist' : 'Removed from wishlist',
            ]);
        }

        return back()->with('success', $added ? 'Added to your wishlist.' : 'Removed from your wishlist.');
    }

    /** Remove one product (used from the wishlist page). */
    public function remove(Request $request, int $product)
    {
        WishlistService::remove($product);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'count' => WishlistService::count()]);
        }

        return back()->with('success', 'Removed from your wishlist.');
    }
}
