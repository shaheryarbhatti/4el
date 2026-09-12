<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Product model — see the create_products_table migration for field docs.
 */
class Product extends Model
{
    protected $fillable = [
        'vendor_id', 'category_id', 'brand_id',
        'name', 'slug', 'sku', 'condition', 'condition_description',
        'short_description', 'description',
        'listing_type', 'price', 'sale_price', 'stock',
        'starting_bid', 'reserve_price', 'auction_duration', 'auction_ends_at',
        'current_bid', 'bid_count',
        'tax_class_id', 'shipping_cost', 'free_shipping',
        'is_featured', 'is_deal', 'deal_ends_at',
        'status', 'is_active', 'views',
    ];

    protected $casts = [
        'price'            => 'decimal:2',
        'sale_price'       => 'decimal:2',
        'starting_bid'     => 'decimal:2',
        'reserve_price'    => 'decimal:2',
        'current_bid'      => 'decimal:2',
        'shipping_cost'    => 'decimal:2',
        'free_shipping'    => 'boolean',
        'is_featured'      => 'boolean',
        'is_deal'          => 'boolean',
        'is_active'        => 'boolean',
        'deal_ends_at'     => 'datetime',
        'auction_ends_at'  => 'datetime',
    ];

    /* ---------------- Relationships ---------------- */

    public function vendor()   { return $this->belongsTo(User::class, 'vendor_id'); }
    public function category() { return $this->belongsTo(Category::class); }
    public function brand()    { return $this->belongsTo(Brand::class); }
    public function taxClass() { return $this->belongsTo(TaxClass::class); }
    public function images()         { return $this->hasMany(ProductImage::class)->orderBy('sort_order'); }
    public function reviews()        { return $this->hasMany(ProductReview::class); }
    public function specValues()     { return $this->hasMany(ProductSpecificationValue::class); }
    public function bids()           { return $this->hasMany(Bid::class)->orderByDesc('amount'); }
    public function winningBid()     { return $this->hasOne(Bid::class)->where('is_winning', true); }

    // Convenience: the primary image (falls back to the first image).
    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    /* ---------------- Scopes ---------------- */

    // Only products that should be publicly visible on the storefront.
    public function scopeLive($q)
    {
        return $q->where('status', 'approved')->where('is_active', true);
    }
    public function scopeFeatured($q) { return $q->where('is_featured', true); }
    public function scopeDeals($q)    { return $q->where('is_deal', true); }

    /* ---------------- Helpers ---------------- */

    // Effective price (sale price when set and lower).
    public function getEffectivePriceAttribute()
    {
        return $this->sale_price && $this->sale_price < $this->price
            ? $this->sale_price
            : $this->price;
    }

    public function getIsOnSaleAttribute(): bool
    {
        return $this->sale_price && $this->sale_price < $this->price;
    }

    public function getIsAuctionAttribute(): bool
    {
        return $this->listing_type === 'auction';
    }

    public function getAuctionActiveAttribute(): bool
    {
        return $this->is_auction
            && $this->auction_ends_at
            && $this->auction_ends_at->isFuture();
    }

    public function getDisplayPriceAttribute(): string
    {
        if ($this->is_auction) {
            $bid = $this->current_bid ?? $this->starting_bid ?? $this->price;
            return '$' . number_format($bid, 2);
        }
        return '$' . number_format($this->effective_price, 2);
    }

    public static function booted(): void
    {
        static::saving(function (Product $p) {
            if (blank($p->slug)) {
                $p->slug = Str::slug($p->name).'-'.Str::random(5);
            }
        });
    }
}
