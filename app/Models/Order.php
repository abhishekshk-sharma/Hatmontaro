<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'order_number', 'total_amount', 'status', 
        'shipping_address', 'phone_no', 'payment_method', 'payment_status',
        'payment_id', 'payment_signature', 'transaction_id'
    ];

    protected $guarded = [];
    
    protected $hidden = [
        'payment_signature', 'transaction_id'
    ];
    
    protected $casts = [
        'total_amount' => 'decimal:2',
        'order_date' => 'datetime',
        'payment_captured_at' => 'datetime',
        'viewed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    
    const STATUSES = [
        'pending' => 'Pending',
        'processing' => 'Processing', 
        'shipped' => 'Shipped',
        'delivered' => 'Delivered',
        'cancelled' => 'Cancelled',
        'refunded' => 'Refunded'
    ];
    
    const PAYMENT_STATUSES = [
        'pending' => 'Pending',
        'success' => 'Success',
        'failed' => 'Failed',
        'refunded' => 'Refunded'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
    
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
    
    public function scopeSuccessfulPayments($query)
    {
        return $query->where('payment_status', 'success');
    }
    
    public function getFormattedTotalAttribute()
    {
        return '₹' . number_format($this->total_amount, 2);
    }
    
    public function getCanBeCancelledAttribute()
    {
        return in_array($this->status, ['pending', 'processing']) && 
               $this->created_at->diffInHours(now()) <= 24;
    }
    
    public function belongsToUser($userId)
    {
        return $this->user_id == $userId;
    }
    
    public function setShippingAddressAttribute($value)
    {
        $this->attributes['shipping_address'] = strip_tags(trim($value));
    }
}
