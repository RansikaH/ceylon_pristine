<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 'description', 'price', 'unit', 'unit_value', 'discount_percentage', 'image', 'stock', 'category_id'
    ];

    // Image URL accessor for Blade views
    public function getImageUrlAttribute()
    {
        $imageFile = $this->image;
        $imagePath = public_path('product-images/' . $imageFile);
        if (!empty($imageFile) && file_exists($imagePath)) {
            return asset('product-images/' . $imageFile);
        }
        return asset('product-images/default_product.png');
    }

    // Alias for compatibility
    public function getDisplayImageUrlAttribute()
    {
        return $this->image_url;
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getDiscountedPriceAttribute()
    {
        if ($this->discount_percentage > 0) {
            return $this->price * (1 - $this->discount_percentage / 100);
        }
        return $this->price;
    }

    public function hasDiscount()
    {
        return $this->discount_percentage > 0;
    }

    public function getFormattedPriceAttribute()
    {
        $unitDisplay = $this->getUnitDisplay();
        return "LKR " . number_format($this->price, 2) . " / " . $unitDisplay;
    }

    public function getFormattedDiscountedPriceAttribute()
    {
        $price = $this->discounted_price;
        $unitDisplay = $this->getUnitDisplay();
        return "LKR " . number_format($price, 2) . " / " . $unitDisplay;
    }

    public function getUnitDisplayAttribute()
    {
        return $this->getUnitDisplay();
    }

    private function getUnitDisplay()
    {
        // Handle unit value display - remove decimal if it's a whole number
        $unitValue = $this->unit_value ?? 1;
        $displayValue = ($unitValue == (int)$unitValue) ? (int)$unitValue : $unitValue;
        
        // Special handling for pieces
        if ($this->unit === 'pcs') {
            return $displayValue == 1 ? 'piece' : $displayValue . ' pieces';
        }
        
        return $displayValue . ' ' . $this->unit;
    }
}
