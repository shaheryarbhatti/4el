<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaxClass extends Model
{
    protected $fillable = ['name', 'rate', 'is_default', 'is_active'];

    protected $casts = [
        'rate'       => 'decimal:2',
        'is_default' => 'boolean',
        'is_active'  => 'boolean',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function scopeActive($q) { return $q->where('is_active', true); }

    // The default tax class (used to pre-select on new products).
    public static function default(): ?self
    {
        return static::where('is_default', true)->first();
    }
}
