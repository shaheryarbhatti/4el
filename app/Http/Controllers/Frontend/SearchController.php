<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $q       = trim($request->get('q', ''));
        $catId   = $request->get('cat');
        $sort    = $request->get('sort', 'default');
        $perPage = in_array((int) $request->get('per_page', 24), [12, 24, 36, 48]) ? (int) $request->get('per_page', 24) : 24;
        $minPrice = $request->get('min_price');
        $maxPrice = $request->get('max_price');
        $condition = $request->get('condition');

        $query = Product::where('status', 'approved')
            ->with(['category', 'primaryImage']);

        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', '%'.$q.'%')
                    ->orWhere('short_description', 'like', '%'.$q.'%')
                    ->orWhere('description', 'like', '%'.$q.'%')
                    ->orWhere('sku', 'like', '%'.$q.'%');
            });
        }

        if ($catId) {
            // Include sub-category products too
            $cat = Category::find($catId);
            if ($cat) {
                $catIds = collect([$cat->id])->merge($cat->children()->pluck('id'));
                $query->whereIn('category_id', $catIds);
            }
        }

        if ($minPrice !== null && $minPrice !== '') {
            $query->where('price', '>=', (float) $minPrice);
        }
        if ($maxPrice !== null && $maxPrice !== '') {
            $query->where('price', '<=', (float) $maxPrice);
        }
        if ($condition) {
            $query->where('condition', $condition);
        }

        match ($sort) {
            'price_asc'  => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'newest'     => $query->latest(),
            default      => $query->latest(),
        };

        $products = $query->paginate($perPage)->appends($request->query());

        $categories = Category::active()->parents()
            ->withCount(['products as product_count' => fn($q) => $q->where('status', 'approved')])
            ->orderBy('sort_order')->get();

        $selectedCategory = $catId ? Category::find($catId) : null;
        $totalCount = $query->toBase()->getCountForPagination();

        return view('frontend.search', compact(
            'products', 'q', 'catId', 'sort', 'perPage',
            'categories', 'selectedCategory', 'minPrice', 'maxPrice', 'condition'
        ));
    }

    public function autocomplete(Request $request)
    {
        $q = trim($request->get('q', ''));
        if (strlen($q) < 2) {
            return response()->json(['products' => [], 'categories' => []]);
        }

        $products = Product::where('status', 'approved')
            ->where('name', 'like', '%'.$q.'%')
            ->with('primaryImage')
            ->orderBy('name')
            ->limit(6)
            ->get()
            ->map(fn($p) => [
                'id'    => $p->id,
                'name'  => $p->name,
                'price' => '$'.number_format($p->sale_price ?? $p->price, 2),
                'url'   => route('product.show', $p->slug),
                'image' => $p->primaryImage
                    ? (str_starts_with($p->primaryImage->path, 'frontend-assets/')
                        ? asset($p->primaryImage->path)
                        : asset('storage/'.$p->primaryImage->path))
                    : null,
            ]);

        $categories = Category::active()
            ->where('name', 'like', '%'.$q.'%')
            ->limit(4)
            ->get()
            ->map(fn($c) => [
                'name' => $c->name,
                'url'  => route('category.show', $c->slug),
                'slug' => $c->slug,
            ]);

        return response()->json(compact('products', 'categories'));
    }
}
