<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeSection;
use App\Models\PromoBanner;
use Illuminate\Http\Request;

class HomeSectionController extends Controller
{
    public function index()
    {
        $sections = HomeSection::orderBy('sort_order')->with('promoBanner')->get();
        $banners  = PromoBanner::active()->orderBy('title')->get(['id', 'title', 'placement']);

        return view('admin.pages.home-sections', compact('sections', 'banners'));
    }

    /** Add a new promo_banner slot to the homepage. */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title'           => ['required', 'string', 'max:80'],
            'promo_banner_id' => ['nullable', 'integer', 'exists:promo_banners,id'],
        ]);

        HomeSection::create([
            'key'             => 'promo_banner',
            'title'           => $data['title'],
            'promo_banner_id' => $data['promo_banner_id'] ?? null,
            'sort_order'      => (HomeSection::max('sort_order') ?? 0) + 10,
            'is_active'       => true,
        ]);

        return back()->with('success', 'Promo banner section added.');
    }

    /** Rename a section's heading and update its settings. */
    public function update(Request $request, HomeSection $homeSection)
    {
        $validated = $request->validate([
            'title'           => ['nullable', 'string', 'max:255'],
            'promo_banner_id' => ['nullable', 'integer', 'exists:promo_banners,id'],
            'banner_limit'    => ['nullable', 'integer', 'min:1', 'max:10'],
        ]);

        $update = [
            'title'           => $validated['title'] ?? null,
            'promo_banner_id' => $validated['promo_banner_id'] ?? null,
        ];

        if ($homeSection->key === 'promo_banner' && !empty($validated['banner_limit'])) {
            $settings = $homeSection->settings ?? [];
            $settings['banner_limit'] = (int) $validated['banner_limit'];
            $update['settings'] = $settings;
        }

        $homeSection->update($update);

        return back()->with('success', 'Section updated.');
    }

    /** Toggle a section visible / hidden. */
    public function toggle(HomeSection $homeSection)
    {
        $homeSection->update(['is_active' => !$homeSection->is_active]);

        return back()->with('success', 'Section visibility updated.');
    }

    /** Delete a promo_banner section (core sections are protected). */
    public function destroy(HomeSection $homeSection)
    {
        if ($homeSection->isFixed()) {
            return back()->with('error', 'Core sections cannot be deleted.');
        }

        $homeSection->delete();

        return back()->with('success', 'Section removed.');
    }

    /** Persist new sort order (AJAX). Expects: order = [id, id, ...] */
    public function reorder(Request $request)
    {
        $ids = $request->input('order', []);

        foreach ($ids as $position => $id) {
            HomeSection::where('id', $id)->update(['sort_order' => ($position + 1) * 10]);
        }

        return response()->json(['status' => 'ok']);
    }
}
