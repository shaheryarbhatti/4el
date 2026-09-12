<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function show(Request $request, Category $category)
    {
        // All parent categories + their active children with product counts for sidebar
        $allCategories = Category::active()
            ->parents()
            ->with([
                'children' => fn($q) => $q->active()->orderBy('sort_order')
                    ->withCount(['products as product_count' => fn($q) => $q->where('status', 'approved')]),
            ])
            ->withCount(['products as product_count' => fn($q) => $q->where('status', 'approved')])
            ->orderBy('sort_order')
            ->get();

        // Roll up child product counts into each parent so sidebar shows realistic totals
        foreach ($allCategories as $parent) {
            $parent->product_count = ($parent->product_count ?? 0)
                + $parent->children->sum('product_count');
        }

        // Include subcategory products when visiting a parent category
        $catIds = collect([$category->id]);
        if ($category->children()->exists()) {
            $catIds = $catIds->merge($category->children()->pluck('id'));
        }

        // Query params
        $sort     = $request->get('sort', 'default');
        $perPage  = in_array((int) $request->get('per_page', 30), [12, 24, 30, 36, 48])
                    ? (int) $request->get('per_page', 30) : 30;
        $query = Product::where('status', 'approved')
            ->whereIn('category_id', $catIds)
            ->with(['category', 'primaryImage']);

        match ($sort) {
            'price_asc'  => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'newest'     => $query->latest(),
            default      => $query->latest(),
        };

        $products = $query->paginate($perPage)->appends($request->query());

        return view('frontend.category', compact(
            'category', 'allCategories', 'products', 'sort', 'perPage'
        ));
    }
}
