<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Auction-specific columns added to the existing products table.
 *
 * listing_type 'auction' activates these fields:
 *   starting_bid   → the opening bid amount (required for auction)
 *   reserve_price  → hidden minimum the seller will accept (optional)
 *   auction_duration → duration in days: 1, 3, 5, 7, 10
 *   auction_ends_at → computed timestamp when auction closes
 *   bid_count       → denormalised counter (updated on each bid)
 *   current_bid     → current highest bid amount (updated on each bid)
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('starting_bid',  12, 2)->nullable()->after('sale_price');
            $table->decimal('reserve_price', 12, 2)->nullable()->after('starting_bid');
            $table->unsignedTinyInteger('auction_duration')->nullable()->after('reserve_price'); // days
            $table->timestamp('auction_ends_at')->nullable()->after('auction_duration');
            $table->decimal('current_bid', 12, 2)->nullable()->after('auction_ends_at');
            $table->unsignedInteger('bid_count')->default(0)->after('current_bid');

            // Condition description (shown on listing form for Used/Refurbished)
            $table->string('condition_description', 1000)->nullable()->after('condition');

            $table->index('auction_ends_at');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'starting_bid', 'reserve_price', 'auction_duration',
                'auction_ends_at', 'current_bid', 'bid_count', 'condition_description',
            ]);
        });
    }
};
