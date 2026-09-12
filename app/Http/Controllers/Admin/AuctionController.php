<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bid;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

/**
 * Admin management of auctions (products with listing_type = "auction").
 * Server-side DataTables listing + live countdowns + bid history.
 */
class AuctionController extends Controller
{
    public function index(Request $request)
    {
        // ---- DataTables AJAX (server-side) ----
        if ($request->has('draw')) {
            $query = Product::where('listing_type', 'auction')
                ->with(['primaryImage', 'vendor.vendorProfile', 'category'])
                ->select('products.*');

            // status filter: live | ended | sold | unsold
            if ($request->filled('status')) {
                $s = $request->status;
                if ($s === 'live') {
                    $query->whereNull('auction_closed_at')->where('auction_ends_at', '>', now());
                } elseif ($s === 'ended') {
                    $query->where(fn ($w) => $w->whereNotNull('auction_closed_at')->orWhere('auction_ends_at', '<=', now()));
                } elseif ($s === 'sold') {
                    $query->whereNotNull('auction_winner_id');
                } elseif ($s === 'unsold') {
                    $query->whereNotNull('auction_closed_at')->whereNull('auction_winner_id');
                }
            }
            if ($request->filled('q')) {
                $query->where('name', 'like', '%'.$request->q.'%');
            }

            $query->latest();

            return DataTables::of($query)
                ->addColumn('product_html', function (Product $p) {
                    $img = $p->primaryImage;
                    if ($img) {
                        $src = str_starts_with($img->path, 'frontend-assets/') ? asset($img->path) : asset('storage/'.$img->path);
                        $thumb = '<img src="'.$src.'" class="au-thumb" alt="">';
                    } else {
                        $thumb = '<div class="au-thumb au-thumb--ph"><i class="bx bx-image"></i></div>';
                    }
                    $cat = $p->category ? '<span class="au-cat">'.e($p->category->name).'</span>' : '';
                    return '<div class="au-prod">'.$thumb.'<div><div class="au-prod__name">'.e(Str::limit($p->name, 40)).'</div>'.$cat.'</div></div>';
                })
                ->addColumn('vendor_html', fn (Product $p) => '<span class="au-vendor"><i class="bx bx-store-alt"></i> '
                    .e($p->vendor?->vendorProfile?->store_name ?? $p->vendor?->name ?? 'Platform').'</span>')
                ->addColumn('bid_html', function (Product $p) {
                    $cur = setting('currency_symbol', '$');
                    $current = $p->current_bid ?? $p->starting_bid;
                    return '<div class="au-bid"><span class="au-bid__cur">'.$cur.number_format((float) $current, 2).'</span>'
                        .'<span class="au-bid__start">start '.$cur.number_format((float) $p->starting_bid, 2).'</span></div>';
                })
                ->addColumn('reserve_html', function (Product $p) {
                    $cur = setting('currency_symbol', '$');
                    if (is_null($p->reserve_price)) return '<span class="text-muted">—</span>';
                    $met = ($p->current_bid ?? 0) >= $p->reserve_price;
                    return '<span class="au-reserve '.($met ? 'is-met' : '').'">'.$cur.number_format((float) $p->reserve_price, 2)
                        .($met ? ' <i class="bx bx-check"></i>' : '').'</span>';
                })
                ->addColumn('bids_html', fn (Product $p) => '<span class="au-chip"><i class="bx bx-purchase-tag"></i> '.(int) $p->bid_count.'</span>')
                ->addColumn('time_html', function (Product $p) {
                    if ($p->auction_closed_at || ($p->auction_ends_at && $p->auction_ends_at->isPast())) {
                        return '<span class="au-time au-time--ended"><i class="bx bx-flag"></i> Ended '
                            .($p->auction_ends_at ? $p->auction_ends_at->format('d M Y') : '').'</span>';
                    }
                    return '<span class="au-countdown" data-ends="'.$p->auction_ends_at?->toIso8601String().'"><i class="bx bx-time"></i> …</span>';
                })
                ->addColumn('status_html', function (Product $p) {
                    $st = $this->status($p);
                    $map = [
                        'live'   => ['live',   'Live'],
                        'ended'  => ['ended',  'Ended'],
                        'sold'   => ['sold',   'Sold'],
                        'unsold' => ['unsold', 'Unsold'],
                    ];
                    [$cls, $label] = $map[$st];
                    $winner = '';
                    if ($p->auction_winner_id) {
                        $w = User::find($p->auction_winner_id);
                        if ($w) $winner = '<div class="au-winner"><i class="bx bx-crown"></i> '.e(Str::limit($w->name, 16)).'</div>';
                    }
                    return '<span class="au-badge au-badge--'.$cls.'">'.$label.'</span>'.$winner;
                })
                ->addColumn('actions', fn (Product $p) => '<a href="'.route('admin.auctions.show', $p).'" class="au-act au-act--view" title="View"><i class="bx bx-show"></i></a>')
                ->rawColumns(['product_html','vendor_html','bid_html','reserve_html','bids_html','time_html','status_html','actions'])
                ->make(true);
        }

        // ---- Dashboard summary ----
        $base = Product::where('listing_type', 'auction');
        $cur  = setting('currency_symbol', '$');

        $stats = [
            'total'   => (clone $base)->count(),
            'live'    => (clone $base)->whereNull('auction_closed_at')->where('auction_ends_at', '>', now())->count(),
            'ended'   => (clone $base)->where(fn ($w) => $w->whereNotNull('auction_closed_at')->orWhere('auction_ends_at', '<=', now()))->count(),
            'sold'    => (clone $base)->whereNotNull('auction_winner_id')->count(),
            'bids'    => Bid::count(),
            'highest' => (float) (clone $base)->max('current_bid'),
        ];

        return view('admin.auctions.index', compact('stats'));
    }

    public function show(Product $auction)
    {
        abort_unless($auction->listing_type === 'auction', 404);

        $auction->load(['primaryImage', 'vendor.vendorProfile', 'category']);
        $bids   = Bid::where('product_id', $auction->id)->with('user')->orderByDesc('amount')->orderByDesc('created_at')->get();
        $winner = $auction->auction_winner_id ? User::find($auction->auction_winner_id) : null;

        return view('admin.auctions.show', compact('auction', 'bids', 'winner'));
    }

    /** live | ended | sold | unsold */
    private function status(Product $p): string
    {
        if ($p->auction_closed_at) {
            return $p->auction_winner_id ? 'sold' : 'unsold';
        }
        if ($p->auction_ends_at && $p->auction_ends_at->isPast()) {
            return 'ended';
        }
        return 'live';
    }
}
