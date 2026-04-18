<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $fillable = [
        'name', 'description', 'type', 'value', 'applicable_to', 
        'product_ids', 'category_ids', 'minimum_order_amount', 
        'usage_limit', 'used_count', 'starts_at', 'expires_at', 'is_active'
    ];

    protected $casts = [
        'product_ids' => 'array',
        'category_ids' => 'array',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function isActive()
    {
        if (!$this->is_active) return false;
        
        if ($this->starts_at && $this->starts_at->isFuture()) return false;
        
        if ($this->expires_at && $this->expires_at->isPast()) return false;
        
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) return false;
        
        return true;
    }

    public function applyDiscount($originalPrice)
    {
        if ($this->type === 'percentage') {
            return $originalPrice * (1 - $this->value / 100);
        }
        
        return max(0, $originalPrice - $this->value);
    }

    public function getApplicableProducts()
    {
        if ($this->applicable_to === 'all') {
            return Product::all();
        }
        
        if ($this->applicable_to === 'specific_products') {
            return Product::whereIn('id', $this->product_ids ?? [])->get();
        }
        
        if ($this->applicable_to === 'specific_categories') {
            return Product::whereIn('category_id', $this->category_ids ?? [])->get();
        }
        
        return collect();
    }

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}
