<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Bids — records every bid placed on an auction product.
 *
 * Business rules (enforced in BidController):
 *   • amount must be >= product.starting_bid
 *   • amount must be > current winning bid
 *   • auction must not have ended (auction_ends_at > now)
 *   • bidder cannot be the product's vendor
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bids', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->boolean('is_winning')->default(false);  // true = current highest bid
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();

            $table->index(['product_id', 'is_winning']);
            $table->index(['product_id', 'amount']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bids');
    }
};
