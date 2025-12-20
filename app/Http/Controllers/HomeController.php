<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Banner;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Get active banners ordered properly
        $banners = Banner::where('is_active', true)
            ->orderBy('order', 'asc')
            ->get();
            
        // Get featured products (AI recommended OR featured)
        $featuredProducts = Product::where('is_ai_recommended', true)
            ->orWhere('is_featured', true)
            ->limit(6)
            ->get();
            
        // Get categories with product count
        $categories = Category::withCount('products')
            ->having('products_count', '>', 0)
            ->limit(6)
            ->get();
            
        // Get category-wise products (Amazon style)
        $categoryProducts = [];
        foreach($categories->take(4) as $category) {
            $products = Product::where('category_id', $category->id)
                ->limit(6)
                ->get();
            if($products->count() > 0) {
                $categoryProducts[] = [
                    'category' => $category,
                    'products' => $products
                ];
            }
        }

        return view('home', compact('banners', 'featuredProducts', 'categories', 'categoryProducts'));
    }
}