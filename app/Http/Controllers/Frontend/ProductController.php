<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show(Request $request, Product $product)
    {
        abort_if($product->status !== 'approved', 404);

        $product->increment('views');
        $product->load(['category', 'brand', 'images', 'primaryImage', 'winningBid', 'specValues.specification']);

        $reviews = $product->reviews()->approved()->latest()->get();
        $avgRating = $reviews->avg('rating') ?? 0;

        // Related: same category, exclude self — load all images for hover second image
        $related = Product::where('status', 'approved')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with(['images' => fn($q) => $q->orderBy('sort_order')])
            ->inRandomOrder()
            ->take(6)
            ->get();

        // Bottom widget sidebars
        $featuredProducts = Product::where('status', 'approved')->where('is_featured', true)
            ->with('primaryImage')->inRandomOrder()->take(3)->get();
        $latestProducts = Product::where('status', 'approved')
            ->with('primaryImage')->latest()->take(3)->get();

        return view('frontend.product', compact(
            'product', 'reviews', 'avgRating', 'related',
            'featuredProducts', 'latestProducts'
        ));
    }

    public function storeReview(Request $request, Product $product)
    {
        abort_if($product->status !== 'approved', 404);

        $data = $request->validate([
            'reviewer_name'  => 'required|string|max:100',
            'reviewer_email' => 'required|email|max:150',
            'rating'         => 'required|integer|min:1|max:5',
            'review'         => 'required|string|min:10|max:2000',
        ]);

        $data['product_id'] = $product->id;
        $data['user_id']    = auth()->id();
        $data['is_approved'] = false;

        ProductReview::create($data);

        return back()->with('review_success', 'Thank you! Your review has been submitted and is pending approval.');
    }
}
