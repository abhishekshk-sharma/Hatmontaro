<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'username', 'email', 'phone_no', 'upi_id', 'password', 'profile_picture', 'joining_date'
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'joining_date' => 'datetime',
    ];

    public function carts()
    {
        return $this->hasMany(UserCart::class);
    }

    public function wishlists()
    {
        return $this->hasMany(UserWishlist::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }
}
