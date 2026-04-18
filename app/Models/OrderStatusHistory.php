<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderStatusHistory extends Model
{
    protected $table = 'order_status_history';
    
    protected $fillable = [
        'order_id',
        'old_status',
        'new_status',
        'changed_by',
        'notes'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    public function getNewStatusLabelAttribute()
    {
        return ucfirst($this->new_status);
    }

    public function getOldStatusLabelAttribute()
    {
        return $this->old_status ? ucfirst($this->old_status) : 'New Order';
    }
}
