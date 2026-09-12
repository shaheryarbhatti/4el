<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Seeds top-level marketplace categories (like eBay) plus
 * subcategories for the megamenu.
 *
 * show_on_home = true  →  "Top categories" column in megamenu
 * show_on_home = false →  "Additional categories" column
 * is_featured  = true  →  shown in the header category tab bar
 */
class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $parents = [
            [
                'name'        => 'Electronics',
                'icon'        => 'fa-laptop',
                'is_featured' => true,
                'sort_order'  => 1,
                'top'         => [
                    'Computers, tablets & network hardware',
                    'Cameras & photo',
                    'Cell phones & smartphones',
                    'Cell phone cases, covers & skins',
                    'TV, video & home audio electronics',
                    'Vehicle electronics & GPS',
                    'Headphones',
                    'Surveillance & smart home electronics',
                ],
                'additional'  => [
                    'Refurbished electronics',
                    'Certified Open Box',
                    'Video game consoles',
                    'Apple cell phones & smartphones',
                    'PC desktops & all-in-one computers',
                    'Computer graphics cards',
                    'Tablets & eReaders',
                    'Laptops & netbooks',
                ],
            ],
            [
                'name'        => 'Motors',
                'icon'        => 'fa-car',
                'is_featured' => true,
                'sort_order'  => 2,
                'top'         => [
                    'Cars & trucks',
                    'Motorcycles & powersports',
                    'Parts & accessories',
                    'Boats',
                    'Auto tools & supplies',
                    'Commercial trucks & trailers',
                ],
                'additional'  => [
                    'Other vehicles & trailers',
                    'Powersport vehicles',
                    'Military vehicles',
                ],
            ],
            [
                'name'        => 'Collectibles',
                'icon'        => 'fa-gem',
                'is_featured' => true,
                'sort_order'  => 3,
                'top'         => [
                    'Trading cards',
                    'Sports memorabilia',
                    'Coins & paper money',
                    'Stamps',
                    'Militaria',
                    'Historical memorabilia',
                ],
                'additional'  => [
                    'Rocks, fossils & minerals',
                    'Autographs',
                    'Postcards',
                    'Vintage & antiques',
                ],
            ],
            [
                'name'        => 'Home & Garden',
                'icon'        => 'fa-home',
                'is_featured' => true,
                'sort_order'  => 4,
                'top'         => [
                    'Furniture',
                    'Bedding',
                    'Bath',
                    'Kitchen & dining',
                    'Home décor',
                    'Garden & outdoor',
                    'Tools & workshop equipment',
                    'Heating, cooling & air',
                ],
                'additional'  => [
                    'Lamps, lighting & ceiling fans',
                    'Rugs & carpets',
                    'Window treatments',
                    'Housekeeping & organisation',
                ],
            ],
            [
                'name'        => 'Clothing, Shoes & Accessories',
                'icon'        => 'fa-tshirt',
                'is_featured' => true,
                'sort_order'  => 5,
                'top'         => [
                    "Men's clothing",
                    "Women's clothing",
                    "Kids' clothing",
                    'Shoes',
                    'Handbags & purses',
                    'Accessories',
                    'Jewellery & watches',
                ],
                'additional'  => [
                    'Swimwear',
                    'Activewear',
                    'Vintage clothing',
                    'Uniforms & work clothing',
                ],
            ],
            [
                'name'        => 'Toys',
                'icon'        => 'fa-gamepad',
                'is_featured' => true,
                'sort_order'  => 6,
                'top'         => [
                    'Action figures',
                    'Diecast & toy vehicles',
                    'Building toys',
                    'Dolls & bears',
                    'Games',
                    'Outdoor toys',
                ],
                'additional'  => [
                    'Educational toys',
                    'Electronic toys',
                    'Stuffed animals',
                ],
            ],
            [
                'name'        => 'Sporting Goods',
                'icon'        => 'fa-futbol',
                'is_featured' => true,
                'sort_order'  => 7,
                'top'         => [
                    'Exercise & fitness',
                    'Golf',
                    'Cycling',
                    'Fishing',
                    'Camping & hiking',
                    'Team sports',
                    'Water sports',
                ],
                'additional'  => [
                    'Hunting',
                    'Winter sports',
                    'Racquet sports',
                    'Paintball & airsoft',
                ],
            ],
            [
                'name'        => 'Business & Industrial',
                'icon'        => 'fa-industry',
                'is_featured' => true,
                'sort_order'  => 8,
                'top'         => [
                    'Manufacturing & metalworking',
                    'Construction',
                    'Electrical equipment & supplies',
                    'Restaurant & food service',
                    'Office products',
                    'Healthcare, lab & dental',
                ],
                'additional'  => [
                    'Printing & graphic arts',
                    'Agriculture & forestry',
                    'Retail & services',
                ],
            ],
            [
                'name'        => 'Jewelry & Watches',
                'icon'        => 'fa-ring',
                'is_featured' => true,
                'sort_order'  => 9,
                'top'         => [
                    'Fine jewelry',
                    'Fashion jewelry',
                    'Fine watches',
                    'Loose diamonds & gemstones',
                    'Bracelets',
                    'Necklaces & pendants',
                    'Rings',
                ],
                'additional'  => [
                    'Earrings',
                    'Pins & brooches',
                    'Body jewelry',
                    'Jewelry sets',
                ],
            ],
            [
                'name'        => 'Refurbished',
                'icon'        => 'fa-recycle',
                'is_featured' => true,
                'sort_order'  => 10,
                'top'         => [
                    'Refurbished phones',
                    'Refurbished laptops',
                    'Refurbished tablets',
                    'Refurbished TVs',
                    'Refurbished audio',
                ],
                'additional'  => [
                    'Refurbished cameras',
                    'Refurbished gaming',
                    'Refurbished appliances',
                ],
            ],
        ];

        foreach ($parents as $data) {
            // Upsert parent
            $parent = Category::firstOrCreate(
                ['slug' => Str::slug($data['name'])],
                [
                    'name'        => $data['name'],
                    'is_active'   => true,
                    'is_featured' => $data['is_featured'],
                    'show_on_home'=> true,
                    'sort_order'  => $data['sort_order'],
                    'icon'        => $data['icon'],
                ]
            );

            $sort = 1;
            // Top subcategories → show_on_home = true
            foreach ($data['top'] as $childName) {
                Category::firstOrCreate(
                    ['slug' => Str::slug($childName).'-'.Str::lower(Str::random(4))],
                    [
                        'parent_id'    => $parent->id,
                        'name'         => $childName,
                        'is_active'    => true,
                        'show_on_home' => true,
                        'sort_order'   => $sort++,
                    ]
                );
            }

            // Additional subcategories → show_on_home = false
            foreach ($data['additional'] as $childName) {
                Category::firstOrCreate(
                    ['slug' => Str::slug($childName).'-'.Str::lower(Str::random(4))],
                    [
                        'parent_id'    => $parent->id,
                        'name'         => $childName,
                        'is_active'    => true,
                        'show_on_home' => false,
                        'sort_order'   => $sort++,
                    ]
                );
            }
        }
    }
}
