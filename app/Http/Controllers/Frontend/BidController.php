<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Bid;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BidController extends Controller
{
    public function store(Request $request, Product $product)
    {
        abort_unless(auth()->check(), 401, 'You must be logged in to bid.');

        $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01'],
        ]);

        $amount = (float) $request->input('amount');
        $user   = auth()->user();

        // Vendor cannot bid on their own auction.
        if ($product->vendor_id === $user->id) {
            return back()->with('bid_error', 'You cannot bid on your own listing.');
        }

        if (!$product->is_auction) {
            return back()->with('bid_error', 'This product is not an auction listing.');
        }

        if (!$product->auction_active) {
            return back()->with('bid_error', 'This auction has already ended.');
        }

        $minBid = $this->minimumBid($product);

        if ($amount < $minBid) {
            return back()->with('bid_error',
                'Your bid must be at least $' . number_format($minBid, 2) . '.'
            );
        }

        DB::transaction(function () use ($product, $user, $amount) {
            // Mark previous winning bid as not winning.
            Bid::where('product_id', $product->id)
                ->where('is_winning', true)
                ->update(['is_winning' => false]);

            // Insert new winning bid.
            Bid::create([
                'product_id' => $product->id,
                'user_id'    => $user->id,
                'amount'     => $amount,
                'is_winning' => true,
                'ip_address' => request()->ip(),
            ]);

            // Update product counters.
            $product->increment('bid_count');
            $product->update(['current_bid' => $amount]);
        });

        return back()->with('bid_success',
            'Your bid of $' . number_format($amount, 2) . ' has been placed!'
        );
    }

    /** Minimum next bid: current_bid + 1 (or starting_bid if no bids yet). */
    public static function minimumBid(Product $product): float
    {
        $current = $product->current_bid ?? $product->starting_bid ?? 0;
        // Increment logic: +$1 for bids under $100, +$5 for bids under $500, +$25 above.
        $increment = match(true) {
            $current < 100  => 1.00,
            $current < 500  => 5.00,
            default         => 25.00,
        };
        return $current > 0 ? $current + $increment : ($product->starting_bid ?? 1.00);
    }
}
