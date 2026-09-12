<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number', 'user_id', 'status', 'payment_method', 'payment_status',
        'transaction_id', 'paid_at', 'subtotal', 'discount_total', 'shipping_total',
        'tax_total', 'grand_total', 'currency', 'coupon_code',
        'customer_name', 'customer_email', 'customer_phone',
        'address_line', 'city', 'state', 'postal_code', 'country', 'notes',
    ];

    protected $casts = [
        'paid_at'        => 'datetime',
        'subtotal'       => 'decimal:2',
        'discount_total' => 'decimal:2',
        'shipping_total' => 'decimal:2',
        'tax_total'      => 'decimal:2',
        'grand_total'    => 'decimal:2',
    ];

    public function items()  { return $this->hasMany(OrderItem::class); }
    public function user()   { return $this->belongsTo(User::class); }

    /** Line items that belong to one vendor (per-vendor split). */
    public function vendorItems($vendorId)
    {
        return $this->items()->where('vendor_id', $vendorId);
    }

    public function isPaid(): bool { return $this->payment_status === 'paid'; }

    /** Generate the next human-friendly order number. */
    public static function nextNumber(): string
    {
        return 'ORD-'.date('Y').'-'.str_pad((string) (static::max('id') + 1), 6, '0', STR_PAD_LEFT);
    }
}
