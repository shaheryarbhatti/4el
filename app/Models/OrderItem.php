<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id', 'vendor_id', 'product_id', 'product_name', 'price', 'quantity',
        'shipping_cost', 'tax_amount', 'line_total', 'vendor_status',
        'commission_rate', 'commission_amount', 'vendor_amount',
    ];

    protected $casts = [
        'price'             => 'decimal:2',
        'shipping_cost'     => 'decimal:2',
        'tax_amount'        => 'decimal:2',
        'line_total'        => 'decimal:2',
        'commission_rate'   => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'vendor_amount'     => 'decimal:2',
    ];

    public function order()   { return $this->belongsTo(Order::class); }
    public function product() { return $this->belongsTo(Product::class); }
    public function vendor()  { return $this->belongsTo(User::class, 'vendor_id'); }
}
