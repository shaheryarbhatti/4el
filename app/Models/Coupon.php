<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code', 'type', 'value', 'min_order_amount',
        'max_uses', 'used_count', 'expires_at', 'is_active', 'description',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_active'  => 'boolean',
        'value'      => 'float',
        'min_order_amount' => 'float',
    ];

    public function isValid(float $subtotal): bool|string
    {
        if (!$this->is_active) {
            return 'This coupon is inactive.';
        }
        if ($this->expires_at && $this->expires_at->isPast()) {
            return 'This coupon has expired.';
        }
        if ($this->max_uses && $this->used_count >= $this->max_uses) {
            return 'This coupon has reached its usage limit.';
        }
        if ($this->min_order_amount && $subtotal < $this->min_order_amount) {
            return 'Minimum order of $' . number_format($this->min_order_amount, 2) . ' required.';
        }
        return true;
    }

    public function calcDiscount(float $subtotal): float
    {
        if ($this->type === 'percentage') {
            return round($subtotal * $this->value / 100, 2);
        }
        return min((float) $this->value, $subtotal);
    }

    public function incrementUsage(): void
    {
        $this->increment('used_count');
    }
}
