<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Orders — one row per customer checkout.
 *
 * A single order can contain items from MULTIPLE vendors; the per-vendor
 * split lives on order_items.vendor_id (vendors see only their own lines).
 *
 * Money fields are snapshots taken at checkout so later price/tax changes
 * never alter a placed order.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();          // human-friendly ref e.g. ORD-2026-000123
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

            // Lifecycle
            $table->string('status')->default('pending');       // pending|processing|completed|cancelled
            $table->string('payment_method')->nullable();       // cod|stripe|paypal
            $table->string('payment_status')->default('unpaid');// unpaid|paid|failed|refunded
            $table->string('transaction_id')->nullable();       // gateway reference
            $table->timestamp('paid_at')->nullable();

            // Money (snapshots)
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('discount_total', 12, 2)->default(0);
            $table->decimal('shipping_total', 12, 2)->default(0);
            $table->decimal('tax_total', 12, 2)->default(0);
            $table->decimal('grand_total', 12, 2)->default(0);
            $table->string('currency', 8)->default('USD');
            $table->string('coupon_code')->nullable();

            // Customer + shipping address (snapshot)
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone')->nullable();
            $table->string('address_line');
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index(['status', 'payment_status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
