<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductMedia;
use App\Models\Category;
use Illuminate\Http\Request;

class AdminProductController extends Controller
{
    // Auth is handled by route middleware (AdminAuth)
    public function index()
    {
        $products = Product::with('category')->paginate(20);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        \Log::info('AdminProductController::store() called', [
            'admin_id' => session('admin_id'),
            'has_image' => $request->hasFile('image'),
        ]);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:products,slug',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'category_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'is_featured' => 'nullable|boolean',
            'is_ai_recommended' => 'nullable|boolean',
        ]);

        // ensure category exists (products.category_id is NOT NULL in migration)
        if (empty($data['category_id'])) {
            $defaultCat = Category::firstOrCreate([
                'slug' => 'uncategorized'
            ], [
                'name' => 'Uncategorized'
            ]);
            $data['category_id'] = $defaultCat->id;
        }

        // set defaults for DB-required columns in migration
        $data['description'] = $data['description'] ?? '';
        $data['style_type'] = $request->get('style_type', 'casual');
        $data['occasion'] = $request->get('occasion', 'casual');
        $data['color'] = $request->get('color', 'unknown');
        $data['stock_quantity'] = (int) $request->get('stock_quantity', 0);
        $data['compare_price'] = $request->get('compare_price');

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            try {
                if (!$file->isValid()) {
                    \Log::error('Product image upload not valid', ['error' => $file->getError()]);
                    return back()->withInput()->withErrors(['image' => 'The uploaded image is not valid.']);
                }
                
                // Check if it's SVG for caps
                $extension = $file->getClientOriginalExtension();
                if ($extension === 'svg') {
                    // Store SVG in caps folder
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $file->move(public_path('images/caps'), $filename);
                    $data['image_url'] = '/images/caps/' . $filename;
                } else {
                    // Store regular images in storage
                    $path = $file->store('products', 'public');
                    if ($path === false || empty($path)) {
                        \Log::error('Failed to store uploaded image', ['file' => $file->getClientOriginalName()]);
                        return back()->withInput()->withErrors(['image' => 'The image failed to upload.']);
                    }
                    $data['image_url'] = '/storage/' . $path;
                }
            } catch (\Exception $e) {
                \Log::error('Exception while storing product image', ['message' => $e->getMessage()]);
                return back()->withInput()->withErrors(['image' => 'The image failed to upload.']);
            }
        } else {
            $data['image_url'] = $data['image_url'] ?? '/storage/placeholder.png';
        }

        $data['is_featured'] = $request->has('is_featured');
        $data['is_ai_recommended'] = $request->has('is_ai_recommended');

        try {
            $product = Product::create($data);
            
            // Handle multiple media files
            if ($request->hasFile('media')) {
                $this->handleMediaUpload($request->file('media'), $product);
            }
            
            \Log::info('Product created successfully');
            return redirect()->route('admin.products.index')->with('success', 'Product created');
        } catch (\Exception $e) {
            \Log::error('Failed to create product', ['error' => $e->getMessage()]);
            return back()->withInput()->withErrors(['error' => 'Failed to create product: ' . $e->getMessage()]);
        }
    }

    public function edit(Product $product)
    {
        \Log::info('AdminProductController::edit() called', ['product_id' => $product->id]);
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        \Log::info('AdminProductController::update() called', [
            'product_id' => $product->id,
            'admin_id' => session('admin_id'),
            'request_method' => $request->method(),
            'has_image' => $request->hasFile('image'),
        ]);

        // Use manual Validator so we can log validation failures
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => "required|string|unique:products,slug,{$product->id}",
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'category_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'is_featured' => 'nullable|boolean',
            'is_ai_recommended' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            \Log::warning('AdminProductController::update() validation failed', [
                'errors' => $validator->errors()->all(),
                'input' => array_keys($request->except(['_token', '_method', 'image'])),
            ]);
            return back()->withInput()->withErrors($validator->errors());
        }

        $data = $validator->validated();

        \Log::info('AdminProductController::update() validation passed', ['data_keys' => array_keys($data)]);

        // ensure DB-required fields present
        if (empty($data['category_id'])) {
            $defaultCat = Category::firstOrCreate([
                'slug' => 'uncategorized'
            ], [
                'name' => 'Uncategorized'
            ]);
            $data['category_id'] = $defaultCat->id;
        }

        $data['description'] = $data['description'] ?? $product->description ?? '';
        $data['style_type'] = $request->get('style_type', $product->style_type ?? 'casual');
        $data['occasion'] = $request->get('occasion', $product->occasion ?? 'casual');
        $data['color'] = $request->get('color', $product->color ?? 'unknown');
        $data['stock_quantity'] = (int) $request->get('stock_quantity', $product->stock_quantity ?? 0);
        $data['compare_price'] = $request->get('compare_price', $product->compare_price);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            \Log::info('File upload detected in update', [
                'filename' => $file->getClientOriginalName(),
                'is_valid' => $file->isValid(),
                'size' => $file->getSize(),
            ]);

            try {
                if (!$file->isValid()) {
                    \Log::error('Product image upload not valid (update)', ['error' => $file->getError()]);
                    return back()->withInput()->withErrors(['image' => 'The uploaded image is not valid.']);
                }
                
                // Check if it's SVG for caps
                $extension = $file->getClientOriginalExtension();
                if ($extension === 'svg') {
                    // Store SVG in caps folder
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $file->move(public_path('images/caps'), $filename);
                    $data['image_url'] = '/images/caps/' . $filename;
                } else {
                    // Store regular images in storage
                    $path = $file->store('products', 'public');
                    if ($path === false || empty($path)) {
                        \Log::error('Failed to store uploaded image (update)', ['file' => $file->getClientOriginalName()]);
                        return back()->withInput()->withErrors(['image' => 'The image failed to upload.']);
                    }
                    $data['image_url'] = '/storage/' . $path;
                }
                \Log::info('Image stored successfully in update', ['path' => $data['image_url']]);
            } catch (\Exception $e) {
                \Log::error('Exception while storing product image (update)', ['message' => $e->getMessage()]);
                return back()->withInput()->withErrors(['image' => 'The image failed to upload.']);
            }
        } else {
            \Log::info('No image file in update request');
        }

        $data['is_featured'] = $request->has('is_featured');
        $data['is_ai_recommended'] = $request->has('is_ai_recommended');

        \Log::info('About to update product', [
            'product_id' => $product->id,
            'data_being_updated' => array_keys($data),
            'new_name' => $data['name'] ?? 'N/A',
        ]);

        try {
            $product->update($data);
            
            // Handle multiple media files
            if ($request->hasFile('media')) {
                $this->handleMediaUpload($request->file('media'), $product);
            }
            
            \Log::info('Product updated successfully', [
                'product_id' => $product->id,
                'new_name' => $data['name'],
            ]);
            return redirect()->route('admin.products.index')->with('success', 'Product updated');
        } catch (\Exception $e) {
            \Log::error('Failed to update product', [
                'product_id' => $product->id,
                'error' => $e->getMessage(),
            ]);
            return back()->withInput()->withErrors(['error' => 'Failed to update product: ' . $e->getMessage()]);
        }
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted');
    }

    private function handleMediaUpload($files, $product)
    {
        $categorySlug = $product->category->slug ?? 'uncategorized';
        $order = $product->media()->count();
        
        foreach ($files as $file) {
            if (!$file->isValid()) continue;
            
            $extension = strtolower($file->getClientOriginalExtension());
            $isVideo = in_array($extension, ['mp4', 'webm', 'mov']);
            $type = $isVideo ? 'video' : 'image';
            
            $folder = $isVideo ? "media/{$categorySlug}/videos" : "media/{$categorySlug}/images";
            $filename = time() . '_' . uniqid() . '.' . $extension;
            $path = $folder . '/' . $filename;
            
            $file->move(public_path($folder), $filename);
            
            ProductMedia::create([
                'product_id' => $product->id,
                'file_path' => '/' . $path,
                'type' => $type,
                'order' => $order++
            ]);
        }
    }

    public function deleteMedia($id)
    {
        $media = ProductMedia::findOrFail($id);
        if (file_exists(public_path($media->file_path))) {
            unlink(public_path($media->file_path));
        }
        $media->delete();
        return response()->json(['success' => true]);
    }
}
