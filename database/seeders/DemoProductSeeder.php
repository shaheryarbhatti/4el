<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class DemoProductSeeder extends Seeder
{
    // Product images available in frontend assets
    private array $productImgs = [
        'frontend-assets/images/demoes/demo36/products/product-1.jpg',
        'frontend-assets/images/demoes/demo36/products/product-2.jpg',
        'frontend-assets/images/demoes/demo36/products/product-3.jpg',
        'frontend-assets/images/demoes/demo36/products/product-4.jpg',
        'frontend-assets/images/demoes/demo36/products/product-5.jpg',
        'frontend-assets/images/demoes/demo36/products/product-6.jpg',
        'frontend-assets/images/demoes/demo36/products/product-7.jpg',
        'frontend-assets/images/demoes/demo36/products/product-8.jpg',
        'frontend-assets/images/demoes/demo36/products/product-9.jpg',
        'frontend-assets/images/demoes/demo36/products/product-10.jpg',
        'frontend-assets/images/demoes/demo36/products/product-11.jpg',
        'frontend-assets/images/demoes/demo36/products/product-12.jpg',
        'frontend-assets/images/demoes/demo36/products/product-13.jpg',
        'frontend-assets/images/demoes/demo36/products/product-14.jpg',
        'frontend-assets/images/demoes/demo36/products/product-15.jpg',
        'frontend-assets/images/demoes/demo36/products/product-16.jpg',
        'frontend-assets/images/demoes/demo36/products/product-17.jpg',
        'frontend-assets/images/demoes/demo36/products/product-18.jpg',
        'frontend-assets/images/demoes/demo36/products/product-19.jpg',
        'frontend-assets/images/demoes/demo36/products/product-20.jpg',
        'frontend-assets/images/demoes/demo36/products/product-21.jpg',
    ];

    // Category images for parent categories
    private array $catImgs = [
        'frontend-assets/images/demoes/demo36/products/categories/category-1.jpg',
        'frontend-assets/images/demoes/demo36/products/categories/category-2.jpg',
        'frontend-assets/images/demoes/demo36/products/categories/category-3.jpg',
        'frontend-assets/images/demoes/demo36/products/categories/category-4.jpg',
        'frontend-assets/images/demoes/demo36/products/categories/category-5.jpg',
        'frontend-assets/images/demoes/demo36/products/categories/category-6.jpg',
        'frontend-assets/images/demoes/demo36/products/categories/category-7.jpg',
        'frontend-assets/images/demoes/demo36/products/categories/category-8.jpg',
    ];

    // Product name templates per category keyword
    private array $namesByKeyword = [
        'computer'   => ['ProBook Laptop 15"', 'UltraSlim Notebook', 'Gaming Desktop PC', 'Mini PC Tower', '2-in-1 Convertible', 'Workstation Pro', 'Budget Chromebook', 'Portable Laptop 14"', 'All-in-One PC', 'Refurbished ThinkPad'],
        'tablet'     => ['Android Tablet 10"', 'iPad Alternative', 'Drawing Tablet Pro', 'Kids Learning Tablet', 'Budget Tab 8"', 'AMOLED Tablet 12"', 'Foldable Tablet', 'eReader Tablet', 'Gaming Tablet', 'Slim Tablet 11"'],
        'camera'     => ['DSLR Camera 24MP', 'Mirrorless Compact', 'Action Cam 4K', 'Webcam HD 1080p', 'Security Camera', 'Dash Cam Dual', 'Trail Camera Night', 'Instant Print Camera', 'Waterproof Cam', 'Vlog Camera Kit'],
        'phone'      => ['Smartphone 128GB', 'Budget Android Phone', 'Flagship 5G Phone', 'Foldable Phone Pro', 'Rugged Outdoor Phone', 'Unlocked GSM Phone', 'Dual SIM Phone', 'Camera Phone 200MP', 'Gaming Phone 12GB', 'Compact Mini Phone'],
        'headphone'  => ['Wireless Earbuds ANC', 'Over-Ear Headphones', 'Gaming Headset RGB', 'Sports Earphones', 'Studio Monitor Cans', 'Bone Conduction Sport', 'True Wireless Buds', 'Noise Cancelling Pro', 'DJ Headphones', 'Kids Safe Volume Buds'],
        'tv'         => ['4K Smart TV 55"', 'OLED TV 48"', 'QLED 65" TV', 'Portable Mini Projector', 'Sound Bar 2.1', 'Streaming Device 4K', 'Home Theatre System', 'Smart TV Box', 'Gaming Monitor 27"', '8K TV 75"'],
        'car'        => ['Car Floor Mats Set', 'Seat Covers Leather', 'Dash Cam 4K', 'Car LED Headlights', 'OBD2 Scanner Pro', 'Car Vacuum Cleaner', 'Steering Wheel Cover', 'Car Air Freshener', 'GPS Navigation Unit', 'Car Phone Holder'],
        'motorcycle' => ['Riding Gloves Pro', 'Full Face Helmet', 'Motorcycle Cover XL', 'Handle Bar Grips', 'Chain Lube Kit', 'Tank Bag Waterproof', 'LED Turn Signals', 'Exhaust Pipe Chrome', 'Tire Repair Kit', 'Biker Jacket Leather'],
        'dress'      => ['Floral Midi Dress', 'Casual Maxi Dress', 'Office Wrap Dress', 'Summer Sun Dress', 'Evening Gown Blue', 'Boho Tie-Dye Dress', 'Cocktail Mini Dress', 'Linen Shirt Dress', 'Vintage A-Line Dress', 'Party Sequin Dress'],
        'shoe'       => ['Running Sneakers', 'Leather Oxford Shoes', 'High Heel Sandals', 'Casual Slip-Ons', 'Hiking Boots', 'Flip Flops Summer', 'Platform Sneakers', 'Chelsea Ankle Boots', 'Ballet Flats', 'Waterproof Rain Boots'],
        'jacket'     => ['Puffer Winter Jacket', 'Denim Jacket Classic', 'Leather Biker Jacket', 'Windbreaker Neon', 'Fleece Zip-Up Hoodie', 'Quilted Vest', 'Trench Coat Beige', 'Bomber Jacket', 'Parka Snow Coat', 'Track Jacket Sport'],
        'toy'        => ['Building Blocks 500pc', 'Remote Control Car', 'Plush Teddy Bear XL', 'Science Kit Kids', 'Wooden Puzzle 100pc', 'Doll House Set', 'Nerf Blaster Gun', 'Play-Doh Mega Pack', 'Lego Creator Set', 'Board Game Family'],
        'game'       => ['Gaming Controller Pro', 'Retro Handheld Console', 'PC Gaming Headset', 'Gaming Chair Ergonomic', 'Mousepad XXL', 'Mechanical Keyboard', 'Gaming Mouse 16000DPI', 'Console Cooling Stand', 'Game Storage Tower', 'VR Headset Lite'],
        'garden'     => ['Garden Hose 50ft', 'Pruning Shears Pro', 'Planter Pots Set 5', 'Solar Garden Lights', 'Compost Bin 80L', 'Lawn Sprinkler 360', 'Raised Garden Bed', 'Watering Can 10L', 'Garden Kneeler Pad', 'Wheelbarrow 100L'],
        'furniture'  => ['Ergonomic Office Chair', 'L-Shape Corner Desk', 'Bookshelf 5-Tier', 'Coffee Table Wood', 'TV Stand Modern', 'Sofa 3-Seater Grey', 'Wardrobe 3-Door', 'Bedside Table', 'Shoe Rack Cabinet', 'Folding Dining Table'],
        'kitchen'    => ['Air Fryer 5.5L', 'Coffee Machine Auto', 'Blender 1200W', 'Electric Kettle 1.7L', 'Stand Mixer 7-Speed', 'Rice Cooker 3-Cup', 'Toaster 4-Slice', 'Pressure Cooker 6qt', 'Knife Set 15pc', 'Non-Stick Cookware Set'],
        'jewel'      => ['Diamond Stud Earrings', 'Gold Chain Necklace', 'Silver Charm Bracelet', 'Pearl Drop Earrings', 'Rose Gold Ring', 'Cubic Zirconia Set', 'Leather Watch Brown', 'Smart Watch Band', 'Crystal Pendant', 'Tennis Bracelet'],
        'watch'      => ['Classic Analog Watch', 'Chronograph Watch', 'Smartwatch Fitness', 'Luxury Dress Watch', 'Sports Digital Watch', 'Solar-Powered Watch', 'Skeleton Mechanical', 'Minimalist Watch', 'Diver Watch 200m', 'Rose Gold Ladies Watch'],
        'sport'      => ['Yoga Mat Non-Slip', 'Resistance Bands Set', 'Dumbbell Set 20kg', 'Jump Rope Speed', 'Pull-Up Bar Door', 'Gym Gloves Padded', 'Foam Roller Deep', 'Balance Board', 'Ab Wheel Roller', 'Kettlebell 16kg'],
        'bicycle'    => ['Mountain Bike 26"', 'Road Bike Aluminum', 'Electric Bike 250W', 'Folding City Bike', 'BMX Freestyle Bike', 'Bike Helmet MTB', 'Bike Lock Chain', 'LED Bike Lights', 'Saddle Gel Comfort', 'Bike Repair Kit'],
        'collectible' => ['Vintage Coin Set', 'Stamp Collection Album', 'Funko Pop Figure', 'Sports Card Bundle', 'Antique Brooch Pin', 'Comic Book Bag', 'Die-Cast Model Car', 'Limited Edition Print', 'Signed Jersey Frame', 'Rare Pokemon Cards'],
        'business'   => ['Office Desk Organizer', 'Label Maker Machine', 'Shredder 12-Sheet', 'Laminator A4', 'Binding Machine', 'Whiteboard 60x90', 'Ergonomic Mouse', 'USB Hub 7-Port', 'Cable Management Box', 'Wireless Presenter'],
        'refurbish'  => ['Refurb iPhone 12', 'Grade A iPad Mini', 'Refurb MacBook Air', 'Open Box Samsung Tab', 'Certified Dell Laptop', 'Refurb AirPods Pro', 'Used Xbox Controller', 'Refurb Bose Headset', 'Grade B Monitor 24"', 'Refurb Kindle Reader'],
    ];

    private int $imgIdx = 0;
    private ImageManager $imageManager;
    private array $resizedCache = [];

    private function resizeAndStore(string $publicRelPath): string
    {
        if (isset($this->resizedCache[$publicRelPath])) {
            return $this->resizedCache[$publicRelPath];
        }

        $sourcePath = public_path($publicRelPath);
        $filename   = 'products/demo-' . md5($publicRelPath) . '.jpg';
        $destPath   = storage_path('app/public/' . $filename);

        if (!file_exists(dirname($destPath))) {
            mkdir(dirname($destPath), 0755, true);
        }

        $this->imageManager->read($sourcePath)
            ->cover(800, 800)
            ->toJpeg(90)
            ->save($destPath);

        $this->resizedCache[$publicRelPath] = $filename;
        return $filename;
    }

    private function nextImg(): string
    {
        $src = $this->productImgs[$this->imgIdx % count($this->productImgs)];
        $this->imgIdx++;
        return $this->resizeAndStore($src);
    }

    private function namesFor(string $subcatName): array
    {
        $lower = strtolower($subcatName);
        foreach ($this->namesByKeyword as $keyword => $names) {
            if (str_contains($lower, $keyword)) {
                return $names;
            }
        }
        // Generic fallback
        return array_map(fn($n) => trim($subcatName) . " Item $n", range(1, 10));
    }

    public function run(): void
    {
        $this->imageManager = new ImageManager(new Driver());

        // Remove previously resized demo product images from storage
        foreach (Storage::disk('public')->files('products') as $file) {
            if (str_starts_with(basename($file), 'demo-')) {
                Storage::disk('public')->delete($file);
            }
        }

        // 1. Assign images to parent categories
        $parents = Category::whereNull('parent_id')->orderBy('id')->get();
        foreach ($parents as $i => $cat) {
            $cat->update(['image' => $this->catImgs[$i % count($this->catImgs)]]);
        }

        // 2. Assign images to subcategories (cycle through same pool)
        $subs = Category::whereNotNull('parent_id')->get();
        foreach ($subs as $i => $cat) {
            $cat->update(['image' => $this->catImgs[$i % count($this->catImgs)]]);
        }

        // 3. Remove existing demo products
        ProductImage::query()->delete();
        Product::query()->delete();

        // 4. Load approved vendors — each product is assigned to one (round-robin)
        $vendorIds = User::role('vendor')->orderBy('id')->pluck('id')->all();
        if (empty($vendorIds)) {
            $vendorIds = [User::min('id')]; // fallback so vendor_id is never null
        }
        $vIdx = 0;

        // 5. Create 10 products per subcategory
        $dealToggle    = 0;
        $featToggle    = 0;
        $productNumber = 0;

        foreach ($subs as $sub) {
            $names = $this->namesFor($sub->name);

            for ($i = 0; $i < 10; $i++) {
                $price     = rand(1999, 29999) / 100;
                $isOnSale  = ($i % 3 === 0);
                $salePrice = $isOnSale ? round($price * (1 - rand(10, 40) / 100), 2) : null;
                $isDeal    = ($dealToggle % 5 === 0);
                $isFeat    = ($featToggle % 4 === 0);
                $dealEnds  = $isDeal ? now()->addDays(rand(1, 14)) : null;

                $product = Product::create([
                    'vendor_id'         => $vendorIds[$vIdx++ % count($vendorIds)],
                    'category_id'       => $sub->id,
                    'brand_id'          => null,
                    'name'              => $names[$i] ?? ($sub->name . ' Product ' . ($i + 1)),
                    'slug'              => Str::slug($names[$i] ?? $sub->name . '-product-' . ($i + 1)) . '-' . ++$productNumber,
                    'sku'               => 'SKU-' . strtoupper(Str::random(8)),
                    'condition'         => 'new',
                    'short_description' => 'Quality ' . ($names[$i] ?? $sub->name) . ' — great value for money.',
                    'description'       => '<p>Premium quality product from the ' . $sub->name . ' category. Fast shipping and excellent customer support.</p>',
                    'listing_type'      => 'fixed',
                    'price'             => $price,
                    'sale_price'        => $salePrice,
                    'stock'             => rand(5, 100),
                    'free_shipping'     => ($i % 2 === 0),
                    'is_featured'       => $isFeat,
                    'is_deal'           => $isDeal,
                    'deal_ends_at'      => $dealEnds,
                    'status'            => 'approved',
                    'is_active'         => true,
                ]);

                // Add 3–5 images per product
                $imgCount = rand(3, 5);
                for ($j = 0; $j < $imgCount; $j++) {
                    ProductImage::create([
                        'product_id' => $product->id,
                        'path'       => $this->nextImg(),
                        'is_primary' => ($j === 0),
                        'sort_order' => $j,
                    ]);
                }

                $dealToggle++;
                $featToggle++;
            }
        }

        $this->command->info('Demo products seeded: ' . Product::count() . ' products across ' . $subs->count() . ' subcategories.');
    }
}
