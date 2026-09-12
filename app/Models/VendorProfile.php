<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Store/seller profile that belongs to a vendor user.
 * Includes per-vendor DEFAULTS that pre-fill new products.
 */
class VendorProfile extends Model
{
    protected $fillable = [
        'user_id', 'store_name', 'slug', 'description', 'logo',
        'phone', 'address', 'latitude', 'longitude', 'seller_type', 'status', 'commission_rate',
        'default_shipping_cost', 'default_free_shipping', 'default_tax_class_id',
    ];

    protected $casts = [
        'commission_rate'       => 'decimal:2',
        'default_shipping_cost' => 'decimal:2',
        'default_free_shipping' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function defaultTaxClass()
    {
        return $this->belongsTo(TaxClass::class, 'default_tax_class_id');
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }
}
