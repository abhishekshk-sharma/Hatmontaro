<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductMedia;
use Illuminate\Http\Request;

class AdminProductController extends Controller
{
    // Auth is handled by route middleware (AdminAuth)
    public function index()
    {
        // Auto-migrate any legacy files from public/storage/products to public/images/products
        $sourceDir = public_path('storage/products');
        $targetDir = public_path('images/products');
        if (is_dir($sourceDir)) {
            if (! is_dir($targetDir)) {
                @mkdir($targetDir, 0755, true);
            }
            $files = @glob($sourceDir.'/*');
            if ($files) {
                foreach ($files as $file) {
                    if (is_file($file)) {
                        $dest = $targetDir.'/'.basename($file);
                        if (! file_exists($dest)) {
                            @copy($file, $dest);
                        }
                    }
                }
            }
        }

        // Also copy any stray files from temporary test folders to images/products
        foreach (['images/men', 'images/Uncategorized'] as $stray) {
            $strayPath = public_path($stray);
            if (is_dir($strayPath)) {
                $strayFiles = @glob($strayPath.'/*');
                if ($strayFiles) {
                    foreach ($strayFiles as $sf) {
                        if (is_file($sf)) {
                            $dest = $targetDir.'/'.basename($sf);
                            if (! file_exists($dest)) {
                                @copy($sf, $dest);
                            }
                        }
                    }
                }
            }
        }

        // Migrate DB rows from /storage/products/ to /images/products/
        try {
            Product::where('image_url', 'like', '/storage/products/%')->chunk(50, function ($prods) {
                foreach ($prods as $prod) {
                    $prod->timestamps = false;
                    $prod->image_url = str_replace('/storage/products/', '/images/products/', $prod->getRawOriginal('image_url'));
                    $prod->save();
                }
            });
            Product::where('image_url', 'like', '/images/men/%')
                ->orWhere('image_url', 'like', '/images/Uncategorized/%')
                ->chunk(50, function ($prods) {
                    foreach ($prods as $prod) {
                        $prod->timestamps = false;
                        $prod->image_url = '/images/products/'.basename($prod->getRawOriginal('image_url'));
                        $prod->save();
                    }
                });
        } catch (\Exception $e) {
            \Log::warning('Product image_url DB migration notice: '.$e->getMessage());
        }

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
            'brand' => 'nullable|string|max:100',
            'slug' => 'required|string|unique:products,slug',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'category_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
            'is_featured' => 'nullable|boolean',
            'is_ai_recommended' => 'nullable|boolean',
            'is_premium_delivery' => 'nullable|boolean',
            'delivery_time' => 'nullable|string|max:100',
        ]);

        // ensure category exists (products.category_id is NOT NULL in migration)
        if (empty($data['category_id'])) {
            $defaultCat = Category::firstOrCreate([
                'slug' => 'uncategorized',
            ], [
                'name' => 'Uncategorized',
            ]);
            $data['category_id'] = $defaultCat->id;
        }

        $category = Category::find($data['category_id']);

        // set defaults for DB-required columns in migration (prevent NULL constraint violations)
        $data['description'] = $data['description'] ?? '';
        $data['style_type'] = $request->filled('style_type') ? $request->input('style_type') : 'casual';
        $data['occasion'] = $request->filled('occasion') ? $request->input('occasion') : 'casual';
        $data['color'] = $request->filled('color') ? $request->input('color') : 'unknown';
        $data['brand'] = $request->filled('brand') ? $request->input('brand') : null;
        $data['stock_quantity'] = (int) ($request->input('stock_quantity') ?? 0);
        $data['compare_price'] = $request->filled('compare_price') ? $request->input('compare_price') : null;
        $data['is_premium_delivery'] = $request->boolean('is_premium_delivery');
        $data['delivery_time'] = $request->filled('delivery_time') ? $request->input('delivery_time') : 'Tomorrow, 2 PM';

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            try {
                if (! $file->isValid()) {
                    \Log::error('Product image upload not valid', ['error' => $file->getError()]);

                    return back()->withInput()->withErrors(['image' => 'The uploaded image is not valid.']);
                }

                $extension = strtolower($file->getClientOriginalExtension());
                $filename = time().'_'.preg_replace('/[^a-zA-Z0-9_\.-]/', '_', $file->getClientOriginalName());

                // SVG or caps category go to images/caps, all regular products go to images/products
                $isCap = ($category && $category->slug === 'caps') || $extension === 'svg';
                $folder = $isCap ? 'images/caps' : 'images/products';
                $destinationPath = public_path($folder);

                if (! file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                $success = $file->move($destinationPath, $filename);
                if (! $success) {
                    \Log::error('Failed to store uploaded image', ['file' => $file->getClientOriginalName()]);

                    return back()->withInput()->withErrors(['image' => 'The image failed to upload.']);
                }

                $data['image_url'] = '/'.$folder.'/'.$filename;
            } catch (\Exception $e) {
                \Log::error('Exception while storing product image', ['message' => $e->getMessage()]);

                return back()->withInput()->withErrors(['image' => 'The image failed to upload: '.$e->getMessage()]);
            }
        } else {
            $data['image_url'] = $data['image_url'] ?? '/images/products/placeholder.png';
        }

        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_ai_recommended'] = $request->boolean('is_ai_recommended');

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

            return back()->withInput()->withErrors(['error' => 'Failed to create product: '.$e->getMessage()]);
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
            'brand' => 'nullable|string|max:100',
            'slug' => "required|string|unique:products,slug,{$product->id}",
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'category_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
            'is_featured' => 'nullable|boolean',
            'is_ai_recommended' => 'nullable|boolean',
            'is_premium_delivery' => 'nullable|boolean',
            'delivery_time' => 'nullable|string|max:100',
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
                'slug' => 'uncategorized',
            ], [
                'name' => 'Uncategorized',
            ]);
            $data['category_id'] = $defaultCat->id;
        }

        $category = Category::find($data['category_id']);

        $data['description'] = $data['description'] ?? $product->description ?? '';
        $data['style_type'] = $request->filled('style_type') ? $request->input('style_type') : ($product->style_type ?? 'casual');
        $data['occasion'] = $request->filled('occasion') ? $request->input('occasion') : ($product->occasion ?? 'casual');
        $data['color'] = $request->filled('color') ? $request->input('color') : ($product->color ?? 'unknown');
        $data['brand'] = $request->filled('brand') ? $request->input('brand') : ($product->brand ?? null);
        $data['stock_quantity'] = (int) ($request->input('stock_quantity') ?? $product->stock_quantity ?? 0);
        $data['compare_price'] = $request->filled('compare_price') ? $request->input('compare_price') : $product->compare_price;
        $data['is_premium_delivery'] = $request->boolean('is_premium_delivery');
        $data['delivery_time'] = $request->filled('delivery_time') ? $request->input('delivery_time') : ($product->delivery_time ?? 'Tomorrow, 2 PM');

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            \Log::info('File upload detected in update', [
                'filename' => $file->getClientOriginalName(),
                'is_valid' => $file->isValid(),
                'size' => $file->getSize(),
            ]);

            try {
                if (! $file->isValid()) {
                    \Log::error('Product image upload not valid (update)', ['error' => $file->getError()]);

                    return back()->withInput()->withErrors(['image' => 'The uploaded image is not valid.']);
                }

                $extension = strtolower($file->getClientOriginalExtension());
                $filename = time().'_'.preg_replace('/[^a-zA-Z0-9_\.-]/', '_', $file->getClientOriginalName());

                // SVG or caps category go to images/caps, regular products to images/products
                $isCap = ($category && $category->slug === 'caps') || $extension === 'svg';
                $folder = $isCap ? 'images/caps' : 'images/products';
                $destinationPath = public_path($folder);

                if (! file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                $success = $file->move($destinationPath, $filename);
                if (! $success) {
                    \Log::error('Failed to store uploaded image (update)', ['file' => $file->getClientOriginalName()]);

                    return back()->withInput()->withErrors(['image' => 'The image failed to upload.']);
                }

                $data['image_url'] = '/'.$folder.'/'.$filename;
                \Log::info('Image stored successfully in update', ['path' => $data['image_url']]);
            } catch (\Exception $e) {
                \Log::error('Exception while storing product image (update)', ['message' => $e->getMessage()]);

                return back()->withInput()->withErrors(['image' => 'The image failed to upload: '.$e->getMessage()]);
            }
        } else {
            \Log::info('No image file in update request');
        }

        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_ai_recommended'] = $request->boolean('is_ai_recommended');

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

            return back()->withInput()->withErrors(['error' => 'Failed to update product: '.$e->getMessage()]);
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
            if (! $file->isValid()) {
                continue;
            }

            $extension = strtolower($file->getClientOriginalExtension());
            $isVideo = in_array($extension, ['mp4', 'webm', 'mov']);
            $type = $isVideo ? 'video' : 'image';

            $folder = $isVideo ? "media/{$categorySlug}/videos" : "media/{$categorySlug}/images";
            $filename = time().'_'.uniqid().'.'.$extension;
            $path = $folder.'/'.$filename;

            $file->move(public_path($folder), $filename);

            ProductMedia::create([
                'product_id' => $product->id,
                'file_path' => '/'.$path,
                'type' => $type,
                'order' => $order++,
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
