<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageBanner;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PageBannerController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        
        // Create missing banners
        $this->createMissingBanners($categories);
        
        $banners = PageBanner::orderBy('page_type')->orderBy('page_identifier')->get();
        
        return view('admin.page-banners.index', compact('banners'));
    }
    
    public function edit(PageBanner $pageBanner)
    {
        return view('admin.page-banners.edit', compact('pageBanner'));
    }
    
    public function update(Request $request, PageBanner $pageBanner)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean'
        ]);
        
        $data = $request->only(['title', 'description', 'is_active']);
        
        if ($request->hasFile('banner_image')) {
            if ($pageBanner->banner_image) {
                Storage::delete($pageBanner->banner_image);
            }
            $data['banner_image'] = $request->file('banner_image')->store('banners', 'public');
        }
        
        $pageBanner->update($data);
        
        return redirect()->route('admin.page-banners.index')
            ->with('success', 'Banner updated successfully!');
    }
    
    private function createMissingBanners($categories)
    {
        // Create AI recommended banner if not exists
        PageBanner::firstOrCreate(
            ['page_type' => 'ai-recommended', 'page_identifier' => null],
            [
                'title' => 'AI Curated Collections',
                'description' => 'Our AI analyzes trends and preferences to bring you the best picks.',
                'is_active' => true
            ]
        );
        
        // Create category banners
        foreach ($categories as $category) {
            PageBanner::firstOrCreate(
                ['page_type' => 'category', 'page_identifier' => $category->slug],
                [
                    'title' => $category->name,
                    'description' => $category->description ?? "Explore our {$category->name} collection",
                    'is_active' => true
                ]
            );
        }
    }
}