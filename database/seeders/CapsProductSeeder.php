<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;

class CapsProductSeeder extends Seeder
{
    public function run()
    {
        $cat = Category::where('slug','caps')->first();
        if (!$cat) return;

        $samples = [
            ['name' => 'Classic Baseball Cap', 'brand' => 'Nike', 'image' => '/images/caps/cap1.svg', 'price' => 29.99, 'color' => 'blue'],
            ['name' => 'Snapback Street Cap', 'brand' => 'Adidas', 'image' => '/images/caps/cap2.svg', 'price' => 34.99, 'color' => 'black'],
            ['name' => 'Premium Baseball Cap', 'brand' => 'New Era', 'image' => '/images/caps/cap3.svg', 'price' => 39.99, 'color' => 'red'],
            ['name' => 'Trucker Mesh Cap', 'brand' => 'Puma', 'image' => '/images/caps/cap4.svg', 'price' => 24.99, 'color' => 'white'],
            ['name' => 'Fitted Sports Cap', 'brand' => 'Under Armour', 'image' => '/images/caps/cap5.svg', 'price' => 32.99, 'color' => 'navy'],
            ['name' => 'Dad Hat Classic', 'brand' => 'Nike', 'image' => '/images/caps/cap6.svg', 'price' => 27.99, 'color' => 'gray'],
            ['name' => 'Urban Snapback', 'brand' => 'Adidas', 'image' => '/images/caps/cap2.svg', 'price' => 31.99, 'color' => 'green'],
            ['name' => 'Sport Performance Cap', 'brand' => 'Reebok', 'image' => '/images/caps/cap1.svg', 'price' => 28.99, 'color' => 'orange'],
            ['name' => 'Vintage Baseball Cap', 'brand' => 'New Era', 'image' => '/images/caps/cap3.svg', 'price' => 35.99, 'color' => 'brown'],
            ['name' => 'Mesh Trucker Hat', 'brand' => 'Puma', 'image' => '/images/caps/cap4.svg', 'price' => 22.99, 'color' => 'yellow'],
            ['name' => 'Flex Fit Cap', 'brand' => 'Under Armour', 'image' => '/images/caps/cap5.svg', 'price' => 33.99, 'color' => 'purple'],
            ['name' => 'Casual Dad Cap', 'brand' => 'Reebok', 'image' => '/images/caps/cap6.svg', 'price' => 26.99, 'color' => 'pink'],
            ['name' => 'Beanie Winter Cap', 'brand' => 'Nike', 'image' => '/images/caps/cap7.svg', 'price' => 25.99, 'color' => 'gray']
        ];

        foreach ($samples as $s) {
            $slug = Str::slug($s['name']);
            Product::updateOrCreate([
                'slug' => $slug
            ], [
                'name' => $s['name'],
                'brand' => $s['brand'],
                'slug' => $slug,
                'description' => $s['name'] . ' by ' . $s['brand'] . ' — Perfect for everyday wear with AI try-on support',
                'price' => $s['price'],
                'image_url' => $s['image'],
                'category_id' => $cat->id,
                'style_type' => 'casual',
                'occasion' => 'casual',
                'color' => $s['color'],
                'sizes' => ['One Size'],
                'stock_quantity' => 50,
                'is_ai_recommended' => true,
            ]);
        }
    }
}
