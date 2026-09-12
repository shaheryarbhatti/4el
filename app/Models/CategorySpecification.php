<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategorySpecification extends Model
{
    protected $fillable = [
        'category_id', 'name', 'field_label', 'field_type', 'options',
        'placeholder', 'unit', 'is_required', 'is_essential', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'options'      => 'array',
        'is_required'  => 'boolean',
        'is_essential' => 'boolean',
        'is_active'    => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function values()
    {
        return $this->hasMany(ProductSpecificationValue::class, 'specification_id');
    }

    public function getDisplayLabelAttribute(): string
    {
        return $this->field_label ?: $this->name;
    }

    // Options list for select/multiselect as simple array of strings
    public function getOptionListAttribute(): array
    {
        return $this->options ?? [];
    }

    public function scopeActive($q)   { return $q->where('is_active', true); }
    public function scopeEssential($q){ return $q->where('is_essential', true); }
    public function scopeOptional($q) { return $q->where('is_essential', false); }
}
