<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Master seeder — runs all the module seeders in the correct order.
 * Run with:  php artisan db:seed
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,        // 1. create roles first
            AdminUserSeeder::class,   // 2. create the admin (needs roles)
            SettingsSeeder::class,    // 3. default site settings
            HomeSectionSeeder::class, // 4. default home page sections
            TaxClassSeeder::class,    // 5. default tax classes
            CategorySeeder::class,    // 6. marketplace categories + subcategories
            VendorSeeder::class,      // 7. approved vendor stores (needs roles + tax)
            DemoProductSeeder::class, // 8. products — each assigned to a vendor
            HeroSlideSeeder::class,   // 9. homepage hero slides
            PromoBannerSeeder::class, // 10. homepage promo banners
        ]);
    }
}
