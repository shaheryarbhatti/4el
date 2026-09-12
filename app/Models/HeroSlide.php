<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeroSlide extends Model
{
    protected $fillable = [
        'title', 'eyebrow', 'subtitle', 'button_text', 'button_url', 'button_style',
        'bg_color_start', 'bg_color_end', 'text_color',
        'img1_path', 'img1_label', 'img1_url',
        'img2_path', 'img2_label', 'img2_url',
        'img3_path', 'img3_label', 'img3_url',
        'sort_order', 'is_active',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'sort_order' => 'integer',
    ];

    /** CSS gradient string for the slide background. */
    public function bgGradient(): string
    {
        return "linear-gradient(135deg, {$this->bg_color_start} 0%, {$this->bg_color_end} 100%)";
    }

    /** Text color value for inline CSS (dark = #191919, light = #fff). */
    public function textColorValue(): string
    {
        return $this->text_color === 'light' ? '#fff' : '#191919';
    }

    /** All three right-side image slots as an array, skipping empty ones. */
    public function imageItems(): array
    {
        $items = [];
        foreach ([1, 2, 3] as $n) {
            $path = $this->{"img{$n}_path"};
            if ($path) {
                $items[] = [
                    'path'  => $path,
                    'label' => $this->{"img{$n}_label"} ?? '',
                    'url'   => $this->{"img{$n}_url"} ?? '#',
                ];
            }
        }
        return $items;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
