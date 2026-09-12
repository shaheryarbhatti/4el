<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Category (self-nesting via parent_id).
 */
class Category extends Model
{
    protected $fillable = [
        'parent_id', 'name', 'slug', 'description', 'image', 'icon',
        'is_featured', 'show_on_home', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'is_featured'  => 'boolean',
        'show_on_home' => 'boolean',
        'is_active'    => 'boolean',
    ];

    /* ---------------- Relationships ---------------- */

    // The parent category (null if top-level).
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // Direct sub-categories.
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('sort_order');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function specifications()
    {
        return $this->hasMany(CategorySpecification::class)->orderBy('sort_order');
    }

    /* ---------------- Accessors ---------------- */

    // Returns the correct public URL for the category image regardless of where it's stored.
    public function getImageUrlAttribute(): ?string
    {
        if (! $this->image) return null;
        // Seeded / theme images are relative to public/ (e.g. frontend-assets/...)
        // Uploaded images are stored under storage/app/public/ (e.g. categories/xxx.jpg)
        if (str_starts_with($this->image, 'frontend-assets/') || str_starts_with($this->image, 'frontend-assets\\')) {
            return asset($this->image);
        }
        return asset('storage/' . $this->image);
    }

    /* ---------------- Scopes / helpers ---------------- */

    public function scopeActive($q)   { return $q->where('is_active', true); }
    public function scopeParents($q)  { return $q->whereNull('parent_id'); }
    public function scopeOnHome($q)   { return $q->where('show_on_home', true); }

    // Auto-generate a slug from the name if none supplied.
    public static function booted(): void
    {
        static::saving(function (Category $c) {
            if (blank($c->slug)) {
                $c->slug = Str::slug($c->name).'-'.Str::random(4);
            }
        });
    }
}
