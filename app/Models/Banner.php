<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'title', 'description', 'image', 'button_text', 'button_link',
        'gradient_from', 'gradient_to', 'order', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
