<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSpecificationValue extends Model
{
    protected $fillable = ['product_id', 'specification_id', 'value'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function specification()
    {
        return $this->belongsTo(CategorySpecification::class, 'specification_id');
    }

    // Return value decoded for multiselect (stored as JSON), plain string otherwise
    public function getDisplayValueAttribute(): string
    {
        $spec = $this->specification;
        if ($spec && in_array($spec->field_type, ['multiselect'])) {
            $arr = json_decode($this->value, true);
            return is_array($arr) ? implode(', ', $arr) : ($this->value ?? '');
        }
        return $this->value ?? '';
    }
}
