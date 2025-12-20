<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'order_number', 'total_amount', 'status', 
        'shipping_address', 'phone_no', 'payment_method', 'payment_status'
    ];

    protected $guarded = [
        'payment_id', 'payment_signature', 'transaction_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
