<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutUs extends Model
{
    protected $table = 'about_us';
    
    protected $fillable = [
        'section',
        'title',
        'content',
        'image',
        'extra_data',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'extra_data' => 'array',
        'is_active' => 'boolean'
    ];
}
