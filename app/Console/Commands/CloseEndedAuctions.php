<?php

namespace App\Console\Commands;

use App\Models\Bid;
use App\Models\Product;
use Illuminate\Console\Command;

/**
 * Closes auctions whose end time has passed.
 *
 * For each ended, not-yet-closed auction:
 *   • find the highest bid
 *   • if it meets the reserve (or there is no reserve) → mark SOLD, record winner
 *   • otherwise → mark ended with no winner (unsold / reserve not met)
 *   • stamp auction_closed_at so it is never processed twice
 *
 * Runs every minute via the scheduler (see routes/console.php). You can also
 * run it manually:  php artisan auctions:close
 */
class CloseEndedAuctions extends Command
{
    protected $signature = 'auctions:close';
    protected $description = 'Close ended auctions and assign winners';

    public function handle(): int
    {
        $auctions = Product::where('listing_type', 'auction')
            ->whereNotNull('auction_ends_at')
            ->where('auction_ends_at', '<=', now())
            ->whereNull('auction_closed_at')
            ->get();

        if ($auctions->isEmpty()) {
            $this->info('No auctions to close.');
            return self::SUCCESS;
        }

        $sold = 0;
        $unsold = 0;

        foreach ($auctions as $product) {
            $highest = Bid::where('product_id', $product->id)
                ->orderByDesc('amount')
                ->orderBy('created_at') // earliest of tie wins
                ->first();

            $reserveMet = $highest
                && (is_null($product->reserve_price) || $highest->amount >= $product->reserve_price);

            if ($reserveMet) {
                // Mark the winning bid, clear the others.
                Bid::where('product_id', $product->id)->update(['is_winning' => false]);
                $highest->update(['is_winning' => true]);

                $product->auction_winner_id = $highest->user_id;
                $product->current_bid       = $highest->amount;
                $product->price             = $highest->amount; // final sale price
                $sold++;
            } else {
                $unsold++;
            }

            $product->auction_closed_at = now();
            $product->is_active         = false; // no longer an open/live auction
            $product->save();
        }

        $this->info("Closed {$auctions->count()} auction(s): {$sold} sold, {$unsold} unsold.");
        return self::SUCCESS;
    }
}
