<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Products — the heart of the catalog.
 *
 * OWNERSHIP: every product belongs to a VENDOR (a store). vendor_id points
 * to the users table. Admin-created products can also set any vendor.
 *
 * LISTING TYPE: "fixed" (Buy Now) or "auction" (bidding — auction details go
 * in a separate auctions table built in Phase 3).
 *
 * PRICING: price + optional sale_price. tax_class_id decides tax.
 * SHIPPING: flat shipping_cost per product (free_shipping overrides).
 * MERCHANDISING: is_featured (Featured Products widget), is_deal (Deal Of The
 * Day widget, with deal_ends_at for the countdown).
 * MODERATION: status pending|approved|rejected (vendor products need approval).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            // Ownership & taxonomy
            $table->foreignId('vendor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->foreignId('brand_id')->nullable()->constrained('brands')->nullOnDelete();

            // Identity
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku')->nullable();
            $table->string('condition')->default('new'); // new | used | refurbished
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();

            // Selling model & pricing
            $table->string('listing_type')->default('fixed'); // fixed | auction
            $table->decimal('price', 12, 2)->default(0);
            $table->decimal('sale_price', 12, 2)->nullable();
            $table->unsignedInteger('stock')->default(0);

            // Tax & shipping
            $table->foreignId('tax_class_id')->nullable()->constrained('tax_classes')->nullOnDelete();
            $table->decimal('shipping_cost', 10, 2)->default(0);
            $table->boolean('free_shipping')->default(false);

            // Merchandising flags (drive the home widgets)
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_deal')->default(false);
            $table->timestamp('deal_ends_at')->nullable();

            // State
            $table->string('status')->default('pending'); // pending | approved | rejected
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('views')->default(0);

            $table->timestamps();

            $table->index(['status', 'is_active']);
            $table->index('is_featured');
            $table->index('is_deal');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
