<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Admin;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create user
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
            ]
        );

        // Create admin user for quick access
        Admin::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('password'),
            ]
        );

        // Create categories
        $categories = [
            ['name' => 'Dresses', 'slug' => 'dresses'],
            ['name' => 'Tops', 'slug' => 'tops'],
            ['name' => 'Bottoms', 'slug' => 'bottoms'],
            ['name' => 'Shoes', 'slug' => 'shoes'],
            ['name' => 'Accessories', 'slug' => 'accessories'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(['slug' => $category['slug']], $category);
        }

        // Create products
        $products = [
            [
                'name' => 'Elegant Black Evening Dress',
                'slug' => 'elegant-black-evening-dress',
                'description' => 'A stunning black evening dress perfect for formal occasions. Features elegant draping and a timeless silhouette.',
                'price' => 129.99,
                'compare_price' => 179.99,
                'image_url' => 'https://via.placeholder.com/300x400?text=Black+Evening+Dress',
                'category_id' => 1,
                'style_type' => 'formal',
                'occasion' => 'evening',
                'color' => 'black',
                'sizes' => ['XS', 'S', 'M', 'L', 'XL'],
                'stock_quantity' => 15,
                'is_featured' => true,
                'is_ai_recommended' => true,
                'tags' => ['formal', 'evening', 'elegant'],
            ],
            [
                'name' => 'Floral Summer Dress',
                'slug' => 'floral-summer-dress',
                'description' => 'Bright and colorful floral dress perfect for summer outings. Lightweight and comfortable fabric.',
                'price' => 79.99,
                'compare_price' => 109.99,
                'image_url' => 'https://via.placeholder.com/300x400?text=Floral+Dress',
                'category_id' => 1,
                'style_type' => 'casual',
                'occasion' => 'casual',
                'color' => 'multicolor',
                'sizes' => ['XS', 'S', 'M', 'L', 'XL', 'XXL'],
                'stock_quantity' => 25,
                'is_featured' => false,
                'is_ai_recommended' => true,
                'tags' => ['summer', 'floral', 'casual'],
            ],
            [
                'name' => 'Crisp White Blouse',
                'slug' => 'crisp-white-blouse',
                'description' => 'Classic white blouse that pairs well with anything. Perfect for work or casual wear.',
                'price' => 59.99,
                'compare_price' => 79.99,
                'image_url' => 'https://via.placeholder.com/300x400?text=White+Blouse',
                'category_id' => 2,
                'style_type' => 'casual',
                'occasion' => 'work',
                'color' => 'white',
                'sizes' => ['XS', 'S', 'M', 'L', 'XL'],
                'stock_quantity' => 30,
                'is_featured' => true,
                'is_ai_recommended' => true,
                'tags' => ['professional', 'versatile', 'basic'],
            ],
            [
                'name' => 'Striped Casual Shirt',
                'slug' => 'striped-casual-shirt',
                'description' => 'Comfortable striped shirt for everyday wear. Perfect for layering or wearing solo.',
                'price' => 49.99,
                'compare_price' => 69.99,
                'image_url' => 'https://via.placeholder.com/300x400?text=Striped+Shirt',
                'category_id' => 2,
                'style_type' => 'casual',
                'occasion' => 'casual',
                'color' => 'navy-white',
                'sizes' => ['XS', 'S', 'M', 'L', 'XL', 'XXL'],
                'stock_quantity' => 20,
                'is_featured' => false,
                'is_ai_recommended' => true,
                'tags' => ['casual', 'striped', 'comfortable'],
            ],
            [
                'name' => 'High-Waisted Jeans',
                'slug' => 'high-waisted-jeans',
                'description' => 'Flattering high-waisted jeans that work with any top. Premium denim with stretch comfort.',
                'price' => 89.99,
                'compare_price' => 129.99,
                'image_url' => 'https://via.placeholder.com/300x400?text=High+Waisted+Jeans',
                'category_id' => 3,
                'style_type' => 'casual',
                'occasion' => 'casual',
                'color' => 'dark-blue',
                'sizes' => ['24', '25', '26', '27', '28', '29', '30', '31', '32'],
                'stock_quantity' => 35,
                'is_featured' => true,
                'is_ai_recommended' => true,
                'tags' => ['jeans', 'denim', 'versatile'],
            ],
            [
                'name' => 'Sleek Black Trousers',
                'slug' => 'sleek-black-trousers',
                'description' => 'Professional black trousers perfect for work or formal events. Modern fit with elegant styling.',
                'price' => 99.99,
                'compare_price' => 149.99,
                'image_url' => 'https://via.placeholder.com/300x400?text=Black+Trousers',
                'category_id' => 3,
                'style_type' => 'formal',
                'occasion' => 'work',
                'color' => 'black',
                'sizes' => ['24', '25', '26', '27', '28', '29', '30', '31', '32'],
                'stock_quantity' => 18,
                'is_featured' => false,
                'is_ai_recommended' => true,
                'tags' => ['professional', 'formal', 'trousers'],
            ],
            [
                'name' => 'Comfortable Loafers',
                'slug' => 'comfortable-loafers',
                'description' => 'Stylish and comfortable loafers suitable for various occasions. Premium leather construction.',
                'price' => 119.99,
                'compare_price' => 169.99,
                'image_url' => 'https://via.placeholder.com/300x400?text=Loafers',
                'category_id' => 4,
                'style_type' => 'casual',
                'occasion' => 'work',
                'color' => 'brown',
                'sizes' => ['5', '6', '7', '8', '9', '10', '11', '12'],
                'stock_quantity' => 22,
                'is_featured' => true,
                'is_ai_recommended' => true,
                'tags' => ['shoes', 'leather', 'professional'],
            ],
            [
                'name' => 'Stylish Ankle Boots',
                'slug' => 'stylish-ankle-boots',
                'description' => 'Trendy ankle boots perfect for fall and winter. Pairs well with dresses, jeans, or skirts.',
                'price' => 129.99,
                'compare_price' => 189.99,
                'image_url' => 'https://via.placeholder.com/300x400?text=Ankle+Boots',
                'category_id' => 4,
                'style_type' => 'casual',
                'occasion' => 'casual',
                'color' => 'black',
                'sizes' => ['5', '6', '7', '8', '9', '10', '11', '12'],
                'stock_quantity' => 20,
                'is_featured' => false,
                'is_ai_recommended' => true,
                'tags' => ['boots', 'trendy', 'versatile'],
            ],
            [
                'name' => 'Pearl Necklace',
                'slug' => 'pearl-necklace',
                'description' => 'Elegant pearl necklace that adds a touch of sophistication to any outfit. Timeless and classic.',
                'price' => 59.99,
                'compare_price' => 89.99,
                'image_url' => 'https://via.placeholder.com/300x400?text=Pearl+Necklace',
                'category_id' => 5,
                'style_type' => 'formal',
                'occasion' => 'evening',
                'color' => 'white',
                'sizes' => ['One Size'],
                'stock_quantity' => 40,
                'is_featured' => true,
                'is_ai_recommended' => true,
                'tags' => ['jewelry', 'elegant', 'necklace'],
            ],
            [
                'name' => 'Leather Shoulder Bag',
                'slug' => 'leather-shoulder-bag',
                'description' => 'Spacious leather shoulder bag perfect for work or everyday use. Premium quality with elegant design.',
                'price' => 149.99,
                'compare_price' => 199.99,
                'image_url' => 'https://via.placeholder.com/300x400?text=Shoulder+Bag',
                'category_id' => 5,
                'style_type' => 'casual',
                'occasion' => 'work',
                'color' => 'tan',
                'sizes' => ['One Size'],
                'stock_quantity' => 15,
                'is_featured' => false,
                'is_ai_recommended' => true,
                'tags' => ['bag', 'leather', 'professional'],
            ],
        ];

        foreach ($products as $product) {
            Product::firstOrCreate(['slug' => $product['slug']], $product);
        }

        // Seed demo caps products
        $this->call(\Database\Seeders\CapsProductSeeder::class);
    }
}
