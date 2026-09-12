<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Fields the auction auto-close job needs:
 *   auction_winner_id  -> the winning bidder (null if no winner / reserve not met)
 *   auction_closed_at  -> when the job processed this auction (null = still open;
 *                         set so the job never reprocesses the same auction)
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (! Schema::hasColumn('products', 'auction_winner_id')) {
                $table->foreignId('auction_winner_id')->nullable()->after('bid_count')
                      ->constrained('users')->nullOnDelete();
            }
            if (! Schema::hasColumn('products', 'auction_closed_at')) {
                $table->timestamp('auction_closed_at')->nullable()->after('auction_winner_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'auction_winner_id')) {
                $table->dropConstrainedForeignId('auction_winner_id');
            }
            if (Schema::hasColumn('products', 'auction_closed_at')) {
                $table->dropColumn('auction_closed_at');
            }
        });
    }
};
