<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
    protected $fillable = ['product_id', 'user_id', 'amount', 'is_winning', 'ip_address'];

    protected $casts = [
        'amount'     => 'decimal:2',
        'is_winning' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeWinning($q)
    {
        return $q->where('is_winning', true);
    }
}
