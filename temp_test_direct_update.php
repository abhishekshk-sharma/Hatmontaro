<?php
/**
 * Direct test of AdminProductController update logic
 */
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

// Start a test session with admin_id
session_start();
$_SESSION['admin_id'] = 1;

try {
    // Get app container
    $kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);
    
    // Get first product
    $product = \App\Models\Product::first();
    
    if (!$product) {
        echo "❌ No products found\n";
        exit(1);
    }
    
    echo "=== Product Update Test ===\n\n";
    echo "Product ID: {$product->id}\n";
    echo "Product Name: {$product->name}\n";
    echo "Product Slug: {$product->slug}\n";
    echo "Session Admin ID: " . session('admin_id') . "\n\n";
    
    // Simulate update data
    $testName = "TEST UPDATE " . time();
    $updateData = [
        'name' => $testName,
        'slug' => $product->slug,
        'description' => $product->description,
        'price' => $product->price,
        'category_id' => $product->category_id,
        'style_type' => $product->style_type ?? 'casual',
        'occasion' => $product->occasion ?? 'casual',
        'color' => $product->color ?? 'unknown',
        'stock_quantity' => $product->stock_quantity ?? 0,
        'compare_price' => $product->compare_price,
        'is_featured' => (bool) $product->is_featured,
        'is_ai_recommended' => (bool) $product->is_ai_recommended,
    ];
    
    echo "Attempting to update product...\n";
    $updated = $product->update($updateData);
    
    if ($updated) {
        echo "✅ Product updated successfully\n";
        echo "New name: " . $product->fresh()->name . "\n";
        
        // Verify in database
        $fresh = \App\Models\Product::find($product->id);
        if ($fresh->name === $testName) {
            echo "✅ Verified in database: Name is correct\n";
        } else {
            echo "❌ Database mismatch: Expected '$testName', got '" . $fresh->name . "'\n";
        }
    } else {
        echo "❌ Update failed\n";
    }
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
?>
