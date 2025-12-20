<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class QuickSetupSeeder extends Seeder
{
    public function run()
    {
        $products = [
            [
                'name' => 'Bohemian Maxi Dress',
                'description' => 'Flowing floral dress perfect for summer',
                'price' => 89.99,
                'image_url' => 'https://images.unsplash.com/photo-1567095761054-7a02e69e5c43',
                'category' => 'Dresses',
                'style_type' => 'bohemian',
                'occasion' => 'casual',
                'color' => 'multi',
                'size_range' => 'XS-L',
                'is_ai_recommended' => true,
            ],
            [
                'name' => 'Minimalist Blazer',
                'description' => 'Clean structured blazer for office wear',
                'price' => 129.99,
                'image_url' => 'https://images.unsplash.com/photo-1591047139829-d91aecb6caea',
                'category' => 'Blazers',
                'style_type' => 'minimalist',
                'occasion' => 'office',
                'color' => 'black',
                'size_range' => 'S-XL',
                'is_ai_recommended' => true,
            ],
            [
                'name' => 'Comfort Sneakers',
                'description' => 'Trendy yet comfortable everyday sneakers',
                'price' => 79.99,
                'image_url' => 'https://images.unsplash.com/photo-1549298916-b41d501d3772',
                'category' => 'Shoes',
                'style_type' => 'casual',
                'occasion' => 'everyday',
                'color' => 'white',
                'size_range' => '6-11',
                'is_ai_recommended' => true,
            ],
            [
                'name' => 'Silk Evening Gown',
                'description' => 'Elegant gown for formal events',
                'price' => 199.99,
                'image_url' => 'https://images.unsplash.com/photo-1539008835657-9e8e9680c956',
                'category' => 'Dresses',
                'style_type' => 'formal',
                'occasion' => 'formal',
                'color' => 'navy',
                'size_range' => 'XS-L',
                'is_ai_recommended' => false,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
        
        $this->command->info('✅ 4 sample products created!');
        $this->command->info('🎨 Frontend built with Bootstrap!');
        $this->command->info('🚀 Run: php artisan serve & npm run dev');
    }
}