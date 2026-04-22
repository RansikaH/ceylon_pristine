<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $fillable = [
        'product_id', 'image_path', 'sort_order', 'is_primary'
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getImageUrlAttribute()
    {
        $imagePath = public_path('product-images/' . $this->image_path);
        if (!empty($this->image_path) && file_exists($imagePath)) {
            return asset('product-images/' . $this->image_path);
        }
        return asset('product-images/default_product.png');
    }
}
