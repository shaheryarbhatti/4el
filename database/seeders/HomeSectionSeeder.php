<?php

namespace Database\Seeders;

use App\Models\HomeSection;
use Illuminate\Database\Seeder;

/**
 * Seeds the home page sections that mirror the demo36 (Porto) design.
 *
 * Each "key" maps to a Blade partial at:
 *   resources/views/frontend/home/sections/{key}.blade.php
 *
 * The admin can hide, rename and reorder these from
 * "Pages > Home Sections". Add a new block by adding a row here AND
 * creating the matching partial file.
 */
class HomeSectionSeeder extends Seeder
{
    public function run(): void
    {
        // Canonical homepage layout, in display order (top -> bottom).
        // IMPORTANT: each "key" MUST match a @case block in
        // resources/views/frontend/home.blade.php, otherwise the section
        // renders nothing on the storefront. The homepage starts with the
        // Hero Slider, so it is always first.
        $sections = [
            ['key' => 'hero_slider',       'title' => 'Hero Slider'],
            ['key' => 'categories',        'title' => 'Shop by Category'],
            ['key' => 'promo_banner',      'title' => 'Top Promo Banner'],
            ['key' => 'special_offers',    'title' => 'Special Offers'],
            ['key' => 'promo_banner',      'title' => 'Mid-Page Banner'],
            ['key' => 'featured_products', 'title' => 'Featured Products'],
            ['key' => 'promo_banner',      'title' => 'Bottom Banner'],
        ];

        // Single source of truth: wipe and re-insert so the order is always
        // correct and no orphaned keys (category_grid/banners/brands/
        // deal_of_the_day) linger from earlier migrations.
        HomeSection::query()->delete();

        foreach ($sections as $i => $section) {
            HomeSection::create([
                'key'        => $section['key'],
                'title'      => $section['title'],
                'is_active'  => true,
                'sort_order' => ($i + 1) * 10,
            ]);
        }
    }
}
