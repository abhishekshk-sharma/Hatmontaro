<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PageBanner extends Model
{
    protected $fillable = [
        'page_type',
        'page_identifier', 
        'title',
        'description',
        'banner_image',
        'is_active'
    ];
    
    protected $casts = [
        'is_active' => 'boolean'
    ];
    
    public function getBannerImageUrlAttribute()
    {
        if ($this->banner_image) {
            return Storage::url($this->banner_image);
        }
        return null;
    }
}
