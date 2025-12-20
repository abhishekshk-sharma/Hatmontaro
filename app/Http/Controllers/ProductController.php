<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\PageBanner;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display all products
     */
    public function index(Request $request)
    {
        $query = Product::query()->with('category');
        
        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%')
                  ->orWhere('brand', 'like', '%' . $search . '%')
                  ->orWhere('color', 'like', '%' . $search . '%')
                  ->orWhere('tags', 'like', '%' . $search . '%');
            });
        }
        
        // Category filter
        if ($request->filled('category')) {
            if (is_numeric($request->category)) {
                $query->where('category_id', $request->category);
            } else {
                $query->whereHas('category', function($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->category . '%')
                      ->orWhere('slug', 'like', '%' . $request->category . '%');
                });
            }
        }
        
        // Style type filter
        if ($request->filled('style')) {
            $query->where('style_type', 'like', '%' . $request->style . '%');
        }
        
        // Occasion filter
        if ($request->filled('occasion')) {
            $query->where('occasion', 'like', '%' . $request->occasion . '%');
        }
        
        // Brand filter
        if ($request->filled('brand')) {
            $query->where('brand', 'like', '%' . $request->brand . '%');
        }
        
        // Color filter
        if ($request->filled('color')) {
            $query->where('color', 'like', '%' . $request->color . '%');
        }
        
        // Price range filter
        if ($request->filled('min_price') && is_numeric($request->min_price)) {
            $query->where('price', '>=', (float)$request->min_price);
        }
        if ($request->filled('max_price') && is_numeric($request->max_price)) {
            $query->where('price', '<=', (float)$request->max_price);
        }
        
        // Sorting
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            default:
                $query->latest();
        }
        
        $products = $query->paginate(12);
        $categories = Category::all();
        
        return view('products.index', compact('products', 'categories'));
    }

    /**
     * Display single product
     */
    public function show($product)
    {
        // Handle both ID and slug
        if (is_numeric($product)) {
            $product = Product::findOrFail($product);
        } else {
            $product = Product::where('slug', $product)->firstOrFail();
        }
        
        // Get AI recommendations for this product
        $aiRecommendations = $product->is_ai_recommended 
            ? Product::where('is_ai_recommended', true)
                     ->where('id', '!=', $product->id)
                     ->limit(4)
                     ->get()
            : collect();
        
        // Get similar products
        $similarProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();
        
        return view('products.show', compact('product', 'aiRecommendations', 'similarProducts'));
    }

    /**
     * Get products by category
     */
    public function byCategory($category)
    {
        // Handle both ID and slug
        if (is_numeric($category)) {
            $cat = Category::find($category);
        } else {
            $cat = Category::where('slug', $category)->firstOrFail();
        }
        
        $products = Product::where('category_id', $cat->id)
            ->paginate(12);
            
        $banner = PageBanner::where('page_type', 'category')
            ->where('page_identifier', $cat->slug)
            ->where('is_active', true)
            ->first();
        
        return view('products.category', compact('products', 'cat', 'banner'));
    }

    /**
     * AI Recommended products
     */
    public function aiRecommended()
    {
        $products = \App\Models\Product::where('is_ai_recommended', true)
            ->orWhere('is_featured', true)  // Show featured if no AI recommended
            ->paginate(12);
            
        $banner = PageBanner::where('page_type', 'ai-recommended')
            ->where('is_active', true)
            ->first();
        
        return view('products.ai-recommended', compact('products', 'banner'));
    }
}