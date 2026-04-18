<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'customer_name', 'customer_email', 'customer_phone', 
        'address_line_1', 'address_line_2', 'city', 'district', 'postal_code',
        'payment_method', 'total', 'items', 'status', 'status_note'
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'items' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function statusHistory()
    {
        return $this->hasMany(OrderStatusHistory::class)->orderBy('created_at', 'desc');
    }

    /**
     * Get the full formatted address for the order.
     *
     * @return string
     */
    public function getFullAddressAttribute()
    {
        $addressParts = array_filter([
            $this->address_line_1,
            $this->address_line_2,
            $this->city,
            $this->district,
            $this->postal_code
        ]);
        
        return implode(', ', $addressParts);
    }

    /**
     * Get the formatted payment method name.
     *
     * @return string
     */
    public function getPaymentMethodDisplayAttribute()
    {
        return match($this->payment_method) {
            'cod' => 'Cash on Delivery',
            'card' => 'Credit/Debit Card',
            'bank' => 'Bank Transfer',
            'mobile' => 'Mobile Payment',
            default => ucfirst($this->payment_method),
        };
    }
}
