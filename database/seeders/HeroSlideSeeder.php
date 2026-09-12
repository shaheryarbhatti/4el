<?php

namespace Database\Seeders;

use App\Models\HeroSlide;
use Illuminate\Database\Seeder;

class HeroSlideSeeder extends Seeder
{
    public function run(): void
    {
        HeroSlide::truncate();

        $slides = [
            [
                'title'          => 'Up to 50% off at The Brand Outlet',
                'eyebrow'        => 'Limited Time',
                'subtitle'       => 'Shop top picks from your favourite brands, all in one place.',
                'button_text'    => 'Save Now',
                'button_url'     => '/shop',
                'button_style'   => 'dark',
                'bg_color_start' => '#f5af02',
                'bg_color_end'   => '#f9e07a',
                'text_color'     => 'dark',
                'img1_path'      => 'hero-slides/p1.jpg',
                'img1_label'     => 'Top Picks',
                'img1_url'       => '/shop',
                'img2_path'      => 'hero-slides/p2.jpg',
                'img2_label'     => 'New Arrivals',
                'img2_url'       => '/shop',
                'img3_path'      => 'hero-slides/p3.jpg',
                'img3_label'     => 'Best Deals',
                'img3_url'       => '/shop',
                'sort_order'     => 1,
                'is_active'      => true,
            ],
            [
                'title'          => 'Big Deals on Electronics',
                'eyebrow'        => 'Explore Now',
                'subtitle'       => 'Laptops, phones, gadgets and more — all at unbeatable prices.',
                'button_text'    => 'Shop Now',
                'button_url'     => '/shop',
                'button_style'   => 'white',
                'bg_color_start' => '#1e3a8a',
                'bg_color_end'   => '#60a5fa',
                'text_color'     => 'light',
                'img1_path'      => 'hero-slides/p4.jpg',
                'img1_label'     => 'Phones',
                'img1_url'       => '/shop',
                'img2_path'      => 'hero-slides/p5.jpg',
                'img2_label'     => 'Laptops',
                'img2_url'       => '/shop',
                'img3_path'      => 'hero-slides/p6.jpg',
                'img3_label'     => 'Gadgets',
                'img3_url'       => '/shop',
                'sort_order'     => 2,
                'is_active'      => true,
            ],
            [
                'title'          => 'Up to 70% off Today\'s Deals',
                'eyebrow'        => 'Daily Deals',
                'subtitle'       => 'All with free shipping — limited time offers you can\'t miss.',
                'button_text'    => 'View Deals',
                'button_url'     => '/shop',
                'button_style'   => 'white',
                'bg_color_start' => '#064e3b',
                'bg_color_end'   => '#34d399',
                'text_color'     => 'light',
                'img1_path'      => 'hero-slides/p7.jpg',
                'img1_label'     => 'Electronics',
                'img1_url'       => '/shop',
                'img2_path'      => 'hero-slides/p8.jpg',
                'img2_label'     => 'Home',
                'img2_url'       => '/shop',
                'img3_path'      => 'hero-slides/p9.jpg',
                'img3_label'     => 'Fashion',
                'img3_url'       => '/shop',
                'sort_order'     => 3,
                'is_active'      => true,
            ],
        ];

        foreach ($slides as $data) {
            HeroSlide::create($data);
        }
    }
}
