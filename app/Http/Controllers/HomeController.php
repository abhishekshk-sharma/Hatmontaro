<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Banner;
use App\Models\HeroBanner;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Get active hero banners
        $heroBanners = HeroBanner::active()->ordered()->get();
        
        // Get active banners ordered properly
        $banners = Banner::where('is_active', true)
            ->orderBy('order', 'asc')
            ->get();
            
        // Get featured products (AI recommended OR featured)
        $featuredProducts = Product::where('is_ai_recommended', true)
            ->orWhere('is_featured', true)
            ->limit(6)
            ->get();
            
        // Get caps products for category section
        $categoryProducts = [[
            'category' => (object)['name' => 'Caps Collection'],
            'products' => Product::limit(6)->get()
        ]];

        return view('home', compact('heroBanners', 'banners', 'featuredProducts', 'categoryProducts'));
    }
}