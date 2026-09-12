<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Align the DB with the code: OrderItem model, CheckoutController and the
 * checkout-success view all use `product_name`, but the column was created
 * as `name`. Rename it so inserts stop failing.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Only rename if still on the old name (safe on fresh + existing DBs).
        if (Schema::hasColumn('order_items', 'name') && ! Schema::hasColumn('order_items', 'product_name')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->renameColumn('name', 'product_name');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('order_items', 'product_name') && ! Schema::hasColumn('order_items', 'name')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->renameColumn('product_name', 'name');
            });
        }
    }
};
