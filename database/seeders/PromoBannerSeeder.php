<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\PromoBanner;
use Illuminate\Database\Seeder;

/**
 * Seeds promotional banners shown on the homepage (promo_banner sections).
 * Gradient-based (no image required) so they always render cleanly.
 */
class PromoBannerSeeder extends Seeder
{
    public function run(): void
    {
        // Link buttons to a couple of real category pages when available.
        $slugs = Category::whereNull('parent_id')->pluck('slug')->all();
        $shop  = fn ($i) => isset($slugs[$i]) ? url('/shop/'.$slugs[$i]) : url('/');

        $banners = [
            [
                'eyebrow'       => 'Limited Time',
                'title'         => 'Mega Electronics Sale',
                'subtitle'      => 'Up to 40% off on laptops, phones & smart gadgets. Grab the deal before it ends.',
                'button_text'   => 'Shop Electronics',
                'button_url'    => $shop(0),
                'button_style'  => 'white',
                'bg_color_start'=> '#0f2027',
                'bg_color_end'  => '#2c5364',
                'text_color'    => 'light',
                'sort_order'    => 1,
                'placement'     => 'top',
            ],
            [
                'eyebrow'       => 'New Season',
                'title'         => 'Fashion That Speaks',
                'subtitle'      => 'Fresh arrivals in clothing, shoes & accessories from top vendor stores.',
                'button_text'   => 'Explore Fashion',
                'button_url'    => $shop(1),
                'button_style'  => 'dark',
                'bg_color_start'=> '#ee9ca7',
                'bg_color_end'  => '#ffdde1',
                'text_color'    => 'dark',
                'sort_order'    => 2,
                'placement'     => 'top',
            ],
            [
                'eyebrow'       => 'Free Shipping',
                'title'         => 'Upgrade Your Home',
                'subtitle'      => 'Furniture, kitchen & décor essentials — delivered free on every order.',
                'button_text'   => 'Shop Home',
                'button_url'    => $shop(2),
                'button_style'  => 'white',
                'bg_color_start'=> '#134e5e',
                'bg_color_end'  => '#71b280',
                'text_color'    => 'light',
                'sort_order'    => 3,
                'placement'     => 'top',
            ],
            [
                'eyebrow'       => 'Weekend Special',
                'title'         => 'Gear Up for Sports',
                'subtitle'      => 'Fitness equipment, bikes & outdoor gear at unbeatable prices this weekend.',
                'button_text'   => 'Shop Sports',
                'button_url'    => $shop(3),
                'button_style'  => 'dark',
                'bg_color_start'=> '#f7971e',
                'bg_color_end'  => '#ffd200',
                'text_color'    => 'dark',
                'sort_order'    => 4,
                'placement'     => 'bottom',
            ],
            [
                'eyebrow'       => 'Trusted Sellers',
                'title'         => 'Shop From Verified Vendors',
                'subtitle'      => 'Thousands of products from our approved partner stores, all in one place.',
                'button_text'   => 'Browse All',
                'button_url'    => url('/'),
                'button_style'  => 'outline',
                'bg_color_start'=> '#360033',
                'bg_color_end'  => '#0b8793',
                'text_color'    => 'light',
                'sort_order'    => 5,
                'placement'     => 'bottom',
            ],
            [
                'eyebrow'       => 'Deal of the Day',
                'title'         => 'Daily Deals, Big Savings',
                'subtitle'      => 'New offers every day. Check back often so you never miss a bargain.',
                'button_text'   => 'See Deals',
                'button_url'    => url('/'),
                'button_style'  => 'white',
                'bg_color_start'=> '#8e2de2',
                'bg_color_end'  => '#4a00e0',
                'text_color'    => 'light',
                'sort_order'    => 6,
                'placement'     => 'bottom',
            ],
        ];

        foreach ($banners as $b) {
            PromoBanner::updateOrCreate(
                ['title' => $b['title']],
                $b + ['is_active' => true, 'image_path' => null]
            );
        }

        $this->command->info('Promo banners seeded: '.PromoBanner::count().' banners.');
    }
}
