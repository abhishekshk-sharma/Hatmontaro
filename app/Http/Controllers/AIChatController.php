<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class AIChatController extends Controller
{
    public function chat(Request $request)
    {
        $message = strtolower(trim($request->input('message')));
        $step = session('chat_step', 'welcome');
        
        if ($step === 'welcome') {
            session(['chat_step' => 'search']);
            return response()->json([
                'message' => 'Hello! 👋 I\'m Aura, your AI fashion assistant. How can I help you today? What are you looking for?'
            ]);
        }
        
        // Search in categories and products
        $result = $this->searchCategoriesAndProducts($message);
        
        if ($result['found']) {
            session(['chat_step' => 'welcome']); // Reset for next conversation
            return response()->json([
                'message' => $result['message']
            ]);
        } else {
            session(['chat_step' => 'category_selection', 'user_query' => $message]);
            return response()->json([
                'message' => 'I couldn\'t find a specific match. Please select a category:',
                'categories' => $this->getAllCategories()
            ]);
        }
    }
    
    public function selectCategory(Request $request)
    {
        $category = $request->input('category');
        $userQuery = session('user_query', '');
        
        session(['chat_step' => 'welcome']); // Reset
        
        $url = $this->getCategoryUrl($category);
        if ($userQuery) {
            $url .= '?search=' . urlencode($userQuery);
        }
        
        return response()->json([
            'message' => "Great choice! Here are items from our {$category} collection. <a href='{$url}' class='text-light'>Click here</a> to browse!"
        ]);
    }
    
    private function searchCategoriesAndProducts($message)
    {
        // Check categories first
        $categories = Category::all();
        foreach ($categories as $category) {
            if (strpos($message, strtolower($category->name)) !== false || 
                strpos($message, strtolower($category->slug)) !== false) {
                $url = $this->getCategoryUrl($category->slug);
                return [
                    'found' => true,
                    'message' => "Perfect! Here's our {$category->name} collection. <a href='{$url}' >Click here</a> to browse!"
                ];
            }
        }
        
        // Check for common product terms
        $productTerms = ['shirt', 'dress', 'pants', 'shoes', 'cap', 'jacket', 'top', 'jeans', 'skirt'];
        foreach ($productTerms as $term) {
            if (strpos($message, $term) !== false) {
                $url = route('products.index') . '?search=' . urlencode($term);
                return [
                    'found' => true,
                    'message' => "Great! I found some {$term} options for you. <a href='{$url}' style='color:white'>Click here</a> to see them!"
                ];
            }
        }
        
        return ['found' => false];
    }
    
    private function getAllCategories()
    {
        return Category::all()->map(function($category) {
            return [
                'name' => $category->name,
                'slug' => $category->slug
            ];
        })->toArray();
    }
    
    private function getCategoryUrl($categorySlug)
    {
        switch ($categorySlug) {
            case 'men': return route('shop.men');
            case 'women': return route('shop.women');
            case 'children': return route('shop.children');
            case 'newborn': return route('shop.newborn');
            case 'caps': return route('shop.caps');
            default: return route('products.index');
        }
    }
}