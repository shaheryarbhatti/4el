<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * "home_sections" drives the DYNAMIC front-end home page.
 *
 * Every block of the home page (hero slider, category grid, deals,
 * featured products, banners, blog, newsletter, brands ...) is ONE row here.
 * The admin "Pages > Home Sections" screen lets you:
 *   • turn a section ON / OFF   (is_active)
 *   • re-order sections         (sort_order)
 *   • rename the heading shown  (title)
 *
 * The home view loops over the active rows (ordered by sort_order) and
 * renders the matching Blade partial named by "key".
 *
 *   key        -> stable identifier, matches a partial file
 *                 e.g. key "hero_slider" => resources/views/frontend/home/sections/hero_slider.blade.php
 *   title      -> editable heading/label
 *   is_active  -> show it or not
 *   sort_order -> position on the page (lower = higher up)
 *   settings   -> optional JSON for future per-section options
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('home_sections', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('title')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->json('settings')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('home_sections');
    }
};
