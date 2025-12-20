<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AIRecommendation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'user_query', 'recommended_products', 'ai_analysis'
    ];

    protected $casts = [
        'recommended_products' => 'array',
        'ai_analysis' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return Product::whereIn('id', $this->recommended_products)->get();
    }
}