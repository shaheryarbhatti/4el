<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Store the geo-coordinates of the vendor's address (from Google Places) so we
 * can reuse them later (store locator, maps, distance search, etc.).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vendor_profiles', function (Blueprint $table) {
            if (! Schema::hasColumn('vendor_profiles', 'latitude'))  $table->decimal('latitude', 10, 7)->nullable()->after('address');
            if (! Schema::hasColumn('vendor_profiles', 'longitude')) $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
        });
    }

    public function down(): void
    {
        Schema::table('vendor_profiles', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude']);
        });
    }
};
