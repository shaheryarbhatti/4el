<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductReview;
use Illuminate\Http\Request;

class ProductReviewController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'pending'); // pending | approved | all

        $query = ProductReview::with('product')
            ->when($status === 'pending',  fn($q) => $q->where('is_approved', false))
            ->when($status === 'approved', fn($q) => $q->where('is_approved', true))
            ->latest();

        $reviews  = $query->paginate(20)->appends($request->query());
        $pending  = ProductReview::where('is_approved', false)->count();
        $approved = ProductReview::where('is_approved', true)->count();

        return view('admin.pages.product-reviews', compact('reviews', 'status', 'pending', 'approved'));
    }

    public function approve(ProductReview $review)
    {
        $review->update(['is_approved' => true]);
        return back()->with('success', 'Review approved.');
    }

    public function destroy(ProductReview $review)
    {
        $review->delete();
        return back()->with('success', 'Review deleted.');
    }
}
