<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $fillable = [
        'main_topic',
        'description',
        'subtopic',
        'button_text',
        'button_url',
        'image',
        'sort_order',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer'
    ];

    /**
     * Scope a query to only include active sliders.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to order by sort order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('id', 'asc');
    }

    /**
     * Get the image URL for the slider.
     */
    public function getImageUrlAttribute()
    {
        if ($this->image && file_exists(public_path('slider-images/' . $this->image))) {
            return asset('slider-images/' . $this->image);
        }
        return asset('slider-images/default-slider.jpg');
    }
}
