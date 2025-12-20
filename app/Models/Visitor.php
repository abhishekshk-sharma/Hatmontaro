<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    public $timestamps = false;
    
    protected $fillable = ['ip_address', 'user_agent', 'page_url', 'visited_at'];
    
    protected $casts = [
        'visited_at' => 'datetime',
    ];
}
