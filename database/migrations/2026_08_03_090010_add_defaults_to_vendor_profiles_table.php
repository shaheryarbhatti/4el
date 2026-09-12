<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Per-vendor DEFAULTS that pre-fill new products so a vendor doesn't retype
 * shipping/tax every time. Editable from the vendor's frontend dashboard.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vendor_profiles', function (Blueprint $table) {
            $table->decimal('default_shipping_cost', 10, 2)->default(0)->after('commission_rate');
            $table->boolean('default_free_shipping')->default(false)->after('default_shipping_cost');
            $table->foreignId('default_tax_class_id')->nullable()->after('default_free_shipping')
                  ->constrained('tax_classes')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('vendor_profiles', function (Blueprint $table) {
            $table->dropConstrainedForeignId('default_tax_class_id');
            $table->dropColumn(['default_shipping_cost', 'default_free_shipping']);
        });
    }
};
