<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'brand', 'slug', 'description', 'price', 'compare_price',
        'image_url', 'images', 'tags', 'category_id', 'style_type',
        'occasion', 'color', 'sizes', 'stock_quantity',
        'is_featured', 'is_ai_recommended',
        'is_premium_delivery', 'delivery_time'
    ];

    protected $casts = [
        'tags' => 'array',
        'images' => 'array',
        'sizes' => 'array',
        'is_featured' => 'boolean',
        'is_ai_recommended' => 'boolean',
        'is_premium_delivery' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function media()
    {
        return $this->hasMany(ProductMedia::class)->orderBy('order');
    }
    
    public function getImageUrlAttribute($value)
    {
        if (!$value) return null;
        
        // If it's already a full URL, return as is
        if (str_starts_with($value, 'http')) {
            return $value;
        }
        
        // If it starts with /storage/, convert to direct path for hosting
        if (str_starts_with($value, '/storage/')) {
            // Check if symlink exists, if not use direct path
            if (!file_exists(public_path('storage'))) {
                return str_replace('/storage/', '/storage/app/public/', $value);
            }
        }
        
        return $value;
    }
}