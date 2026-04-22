<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 'description', 'price', 'unit', 'unit_value', 'discount_percentage', 'image', 'stock', 'category_id', 'free_delivery_quantity', 'delivery_fee'
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

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function getMainImageAttribute()
    {
        // Try to get primary image first
        $primaryImage = $this->primaryImage;
        if ($primaryImage) {
            return $primaryImage->image_url;
        }

        // Fall back to first image
        $firstImage = $this->images()->first();
        if ($firstImage) {
            return $firstImage->image_url;
        }

        // Finally fall back to legacy single image field
        return $this->image_url;
    }

    public function getAllImagesAttribute()
    {
        $images = [];

        // Add primary image first if exists
        $primaryImage = $this->primaryImage;
        if ($primaryImage) {
            $images[] = $primaryImage;
        }

        // Add other images (excluding primary)
        $otherImages = $this->images()->where('is_primary', false)->get();
        $images = array_merge($images, $otherImages->all());

        // If no images in new system, fall back to legacy single image
        if (empty($images) && $this->image) {
            $images[] = (object)[
                'image_url' => $this->image_url,
                'image_path' => $this->image,
                'is_primary' => true,
                'sort_order' => 0
            ];
        }

        return collect($images);
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

    // Delivery related methods
    public function hasFreeDelivery()
    {
        return !is_null($this->free_delivery_quantity) && $this->free_delivery_quantity > 0;
    }

    public function hasDeliveryFee()
    {
        return !is_null($this->delivery_fee) && $this->delivery_fee > 0;
    }

    public function getDeliveryInfoAttribute()
    {
        if ($this->hasFreeDelivery()) {
            return [
                'has_free_delivery' => true,
                'free_delivery_quantity' => $this->free_delivery_quantity,
                'delivery_fee' => $this->delivery_fee ?? 0,
                'message' => "Free delivery for {$this->free_delivery_quantity} or more units"
            ];
        } elseif ($this->hasDeliveryFee()) {
            return [
                'has_free_delivery' => false,
                'free_delivery_quantity' => null,
                'delivery_fee' => $this->delivery_fee,
                'message' => "Delivery fee: LKR " . number_format($this->delivery_fee, 2)
            ];
        }

        return [
            'has_free_delivery' => false,
            'free_delivery_quantity' => null,
            'delivery_fee' => 0,
            'message' => 'Standard delivery'
        ];
    }

    public function calculateDeliveryFee($quantity)
    {
        if ($this->hasFreeDelivery() && $quantity >= $this->free_delivery_quantity) {
            return 0;
        }

        return $this->delivery_fee ?? 0;
    }

    public function getFormattedDeliveryInfoAttribute()
    {
        $info = $this->delivery_info;

        if ($info['has_free_delivery']) {
            $output = "<span class='text-success'><i class='bi bi-truck'></i> Free delivery from {$info['free_delivery_quantity']} units</span>";
            if ($info['delivery_fee'] > 0) {
                $output .= "<br><small class='text-muted'>Below that: LKR " . number_format($info['delivery_fee'], 2) . "</small>";
            }
            return $output;
        } elseif ($info['delivery_fee'] > 0) {
            return "<span class='text-warning'><i class='bi bi-truck'></i> Delivery: LKR " . number_format($info['delivery_fee'], 2) . "</span>";
        }

        return "<span class='text-muted'><i class='bi bi-truck'></i> Standard delivery</span>";
    }
}
