<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->decimal('commission_rate', 5, 2)->default(0)->after('tax_amount');   // % at time of order
            $table->decimal('commission_amount', 10, 2)->default(0)->after('commission_rate'); // platform cut
            $table->decimal('vendor_amount', 10, 2)->default(0)->after('commission_amount');   // vendor net
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['commission_rate', 'commission_amount', 'vendor_amount']);
        });
    }
};
