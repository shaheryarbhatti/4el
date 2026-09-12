<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HomeSection extends Model
{
    protected $fillable = ['key', 'title', 'is_active', 'sort_order', 'settings', 'promo_banner_id'];

    protected $casts = [
        'is_active'       => 'boolean',
        'settings'        => 'array',
        'promo_banner_id' => 'integer',
    ];

    public function promoBanner(): BelongsTo
    {
        return $this->belongsTo(PromoBanner::class);
    }

    public function scopeVisible($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    public function typeLabel(): string
    {
        return match($this->key) {
            'hero_slider'       => 'Hero Slider',
            'categories'        => 'Shop by Category',
            'special_offers'    => 'Special Offers',
            'featured_products' => 'Featured Products',
            'promo_banner'      => 'Promo Banner',
            default             => ucfirst(str_replace('_', ' ', $this->key)),
        };
    }

    public function typeIcon(): string
    {
        return match($this->key) {
            'hero_slider'       => 'bx bx-image-alt',
            'categories'        => 'bx bx-grid-alt',
            'special_offers'    => 'bx bx-purchase-tag',
            'featured_products' => 'bx bx-star',
            'promo_banner'      => 'bx bx-megaphone',
            default             => 'bx bx-layout',
        };
    }

    /** Core sections cannot be deleted by admin. */
    public function isFixed(): bool
    {
        return in_array($this->key, ['hero_slider', 'categories', 'special_offers', 'featured_products']);
    }

    /** How many banners to show in this promo_banner section (stored in settings JSON). */
    public function getBannerLimit(): int
    {
        return max(1, (int) ($this->settings['banner_limit'] ?? 1));
    }
}
