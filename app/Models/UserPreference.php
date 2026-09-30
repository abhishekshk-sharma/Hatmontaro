<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'style_preferences', 'color_preferences',
        'size_preferences', 'occasion_preferences', 'budget_range', 'dislikes'
    ];

    protected $casts = [
        'style_preferences' => 'array',
        'color_preferences' => 'array',
        'size_preferences' => 'array',
        'occasion_preferences' => 'array',
        'budget_range' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}