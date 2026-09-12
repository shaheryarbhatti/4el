<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\HeroSlide;
use App\Models\HomeSection;
use App\Models\Product;
use App\Models\PromoBanner;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Load sections in display order (active only)
        $sections = HomeSection::visible()->with('promoBanner')->get();

        // ---- data for each section type ----
        $heroSlides = HeroSlide::active()->orderBy('sort_order')->orderBy('id')->get();

        $categories = Category::active()->parents()->orderBy('name')->get();

        $dealHero = Product::where('status', 'approved')
            ->where('is_deal', true)
            ->with(['category', 'primaryImage'])
            ->inRandomOrder()
            ->first();

        $dealGrid = Product::where('status', 'approved')
            ->where('is_deal', true)
            ->with(['category', 'primaryImage'])
            ->when($dealHero, fn($q) => $q->where('id', '!=', $dealHero->id))
            ->inRandomOrder()
            ->take(8)
            ->get();

        $featured = Product::where('status', 'approved')
            ->where('is_featured', true)
            ->with(['category', 'primaryImage'])
            ->inRandomOrder()
            ->take(8)
            ->get();

        if ($featured->isEmpty()) {
            $featured = Product::where('status', 'approved')
                ->with(['category', 'primaryImage'])
                ->inRandomOrder()
                ->take(8)
                ->get();
        }

        $deals = collect($dealHero ? [$dealHero] : [])->merge($dealGrid);

        // ---- resolve promo banners for each promo_banner section ----
        // Each section can show 1-10 banners (based on banner_limit setting).
        // A pinned banner_id shows first; remaining slots filled from a random pool (no repeats).
        $promoBannerSections = $sections->where('key', 'promo_banner');

        $fixedIds = $promoBannerSections
            ->whereNotNull('promo_banner_id')
            ->filter(fn($s) => $s->promoBanner?->is_active)
            ->pluck('promo_banner_id')
            ->toArray();

        // Calculate how many random banners we need across all promo sections
        $randomNeeded = 0;
        foreach ($promoBannerSections as $s) {
            $limit = $s->getBannerLimit();
            $randomNeeded += $s->promo_banner_id ? max(0, $limit - 1) : $limit;
        }

        $randomPool = $randomNeeded > 0
            ? PromoBanner::active()
                ->whereNotIn('id', $fixedIds)
                ->inRandomOrder()
                ->limit($randomNeeded)
                ->get()->all()
            : [];

        $rIdx = 0;
        foreach ($sections as $section) {
            if ($section->key !== 'promo_banner') continue;

            $limit     = $section->getBannerLimit();
            // Pick a random number of banners to show (1 … limit) so the count varies on each load
            $showCount = $limit > 1 ? rand(1, $limit) : 1;
            $resolved  = [];

            if ($section->promo_banner_id && $section->promoBanner?->is_active) {
                $resolved[] = $section->promoBanner;
                for ($i = 1; $i < $showCount && $rIdx < count($randomPool); $i++, $rIdx++) {
                    $resolved[] = $randomPool[$rIdx];
                }
            } else {
                for ($i = 0; $i < $showCount && $rIdx < count($randomPool); $i++, $rIdx++) {
                    $resolved[] = $randomPool[$rIdx];
                }
            }

            $section->resolved_banners = $resolved;
        }

        return view('frontend.home', compact(
            'sections', 'heroSlides', 'categories',
            'deals', 'dealHero', 'dealGrid', 'featured'
        ));
    }

    public function quickView(Product $product)
    {
        $product->load(['category', 'images', 'primaryImage']);
        return view('frontend.partials.quick-view', compact('product'));
    }
}
