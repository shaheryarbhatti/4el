<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Product categories — SELF-NESTING (a category can have a parent, so
 * "Electronics > Phones > Android" works via parent_id).
 *
 *   parent_id     -> NULL for a top-level category; else the parent's id
 *   show_on_home  -> include it in the home "Category Grid" widget
 *   is_featured   -> highlight it (e.g. mega-menu / featured lists)
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('image')->nullable();   // grid/banner image
            $table->string('icon')->nullable();     // optional icon class
            $table->boolean('is_featured')->default(false);
            $table->boolean('show_on_home')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
