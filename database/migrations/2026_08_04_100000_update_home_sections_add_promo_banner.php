<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('home_sections', function (Blueprint $table) {
            // Remove unique so multiple promo_banner rows can coexist
            $table->dropUnique(['key']);
            // Link a specific banner to a promo_banner section (null = random active)
            $table->foreignId('promo_banner_id')->nullable()->after('settings')
                ->constrained('promo_banners')->nullOnDelete();
        });

        // Replace old rows with the real homepage sections
        DB::table('home_sections')->delete();
        DB::table('home_sections')->insert([
            ['key' => 'hero_slider',       'title' => 'Hero Slider',       'is_active' => 1, 'sort_order' => 10, 'settings' => null, 'promo_banner_id' => null, 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'categories',        'title' => 'Shop by Category',  'is_active' => 1, 'sort_order' => 20, 'settings' => null, 'promo_banner_id' => null, 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'promo_banner',      'title' => 'Top Promo Banner',  'is_active' => 1, 'sort_order' => 30, 'settings' => null, 'promo_banner_id' => null, 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'special_offers',    'title' => 'Special Offers',    'is_active' => 1, 'sort_order' => 40, 'settings' => null, 'promo_banner_id' => null, 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'promo_banner',      'title' => 'The Auction Hub',   'is_active' => 1, 'sort_order' => 50, 'settings' => null, 'promo_banner_id' => null, 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'featured_products', 'title' => 'Featured Products', 'is_active' => 1, 'sort_order' => 60, 'settings' => null, 'promo_banner_id' => null, 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'promo_banner',      'title' => 'Bottom Banner',     'is_active' => 1, 'sort_order' => 70, 'settings' => null, 'promo_banner_id' => null, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::table('home_sections', function (Blueprint $table) {
            $table->dropForeign(['promo_banner_id']);
            $table->dropColumn('promo_banner_id');
            $table->unique('key');
        });
    }
};
