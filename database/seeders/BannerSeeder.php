<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Banner;

class BannerSeeder extends Seeder
{
    public function run(): void
    {
        $banners = [
            [
                'title' => 'Festive Season Sale',
                'description' => 'Up to 50% off on premium formal wear',
                'button_text' => 'Shop Now',
                'button_link' => '/products',
                'gradient_from' => '#667eea',
                'gradient_to' => '#764ba2',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'New Year Collection',
                'description' => 'Exclusive designs for the new season',
                'button_text' => 'Explore',
                'button_link' => '/products',
                'gradient_from' => '#f093fb',
                'gradient_to' => '#f5576c',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Corporate Essentials',
                'description' => 'Professional attire starting at $99',
                'button_text' => 'View Collection',
                'button_link' => '/products',
                'gradient_from' => '#4facfe',
                'gradient_to' => '#00f2fe',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'Limited Time Offer',
                'description' => 'Buy 2 Get 1 Free on all suits',
                'button_text' => 'Shop Suits',
                'button_link' => '/products',
                'gradient_from' => '#fa709a',
                'gradient_to' => '#fee140',
                'order' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($banners as $banner) {
            Banner::create($banner);
        }
    }
}
