<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromoBanner extends Model
{
    protected $fillable = [
        'eyebrow', 'title', 'subtitle',
        'button_text', 'button_url', 'button_style',
        'bg_color_start', 'bg_color_end', 'text_color',
        'image_path', 'sort_order', 'is_active', 'placement',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'sort_order' => 'integer',
    ];

    public function bgGradient(): string
    {
        return "linear-gradient(135deg, {$this->bg_color_start} 0%, {$this->bg_color_end} 100%)";
    }

    public function imageUrl(): ?string
    {
        if (!$this->image_path) return null;
        return str_starts_with($this->image_path, 'frontend-assets/')
            ? asset($this->image_path)
            : asset('storage/' . $this->image_path);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePlacement($query, string $placement)
    {
        return $query->where('placement', $placement);
    }

    public function placementLabel(): string
    {
        return match($this->placement) {
            'top'    => 'Top (between categories & special offers)',
            'bottom' => 'Bottom (after featured products)',
            default  => ucfirst($this->placement),
        };
    }
}
