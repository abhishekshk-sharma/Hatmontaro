<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\PageBanner;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display all products (caps only)
     */
    public function index(Request $request)
    {
        $query = Product::query();
        
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
        
        // Style type filter
        if ($request->filled('style')) {
            $query->where('style_type', 'like', '%' . $request->style . '%');
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
        
        return view('products.index', compact('products'));
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
        $similarProducts = Product::where('id', '!=', $product->id)
            ->limit(4)
            ->get();
        
        return view('products.show', compact('product', 'aiRecommendations', 'similarProducts'));
    }

    /**
     * Caps products
     */
    public function caps()
    {
        $products = Product::paginate(12);
            
        $banner = PageBanner::where('page_type', 'caps')
            ->where('is_active', true)
            ->first();
        
        return view('products.caps', compact('products', 'banner'));
    }

    /**
     * AI Recommended products
     */
    public function aiRecommended()
    {
        $products = Product::where('is_ai_recommended', true)
            ->orWhere('is_featured', true)
            ->paginate(12);
            
        $banner = PageBanner::where('page_type', 'ai-recommended')
            ->where('is_active', true)
            ->first();
        
        return view('products.ai-recommended', compact('products', 'banner'));
    }
}