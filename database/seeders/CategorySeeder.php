<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Men',
                'slug' => 'men',
                'description' => 'Clothing for men'
            ],
            [
                'name' => 'Women',
                'slug' => 'women',
                'description' => 'Clothing for women'
            ],
            [
                'name' => 'Children',
                'slug' => 'children',
                'description' => 'Clothing for children'
            ],
            [
                'name' => 'Newborn',
                'slug' => 'newborn',
                'description' => 'Clothing for newborns and infants'
            ],
            [
                'name' => 'Caps',
                'slug' => 'caps',
                'description' => 'Caps and headwear'
            ],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}
