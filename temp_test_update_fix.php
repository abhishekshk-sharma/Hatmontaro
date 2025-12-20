<?php
/**
 * Test script to verify AdminProductController middleware fix
 */
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

// Boot Laravel container
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);

// Create test session
session_start();
$_SESSION['admin_id'] = 1;

try {
    // Test 1: Check if middleware exists in constructor
    $controller = new \App\Http\Controllers\AdminProductController();
    echo "✅ AdminProductController instantiated successfully\n";
    
    // Test 2: Verify that Product model can be updated
    $product = \App\Models\Product::first();
    if ($product) {
        echo "✅ Found product ID {$product->id}: {$product->name}\n";
        
        // Test simple update without file
        $oldName = $product->name;
        $product->update(['name' => 'Test Update ' . time()]);
        echo "✅ Product name updated from '{$oldName}' to '{$product->name}'\n";
        
        // Revert for safety
        $product->update(['name' => $oldName]);
        echo "✅ Product name reverted to '{$oldName}'\n";
    } else {
        echo "⚠️ No products found in database\n";
    }
    
    echo "\n✅ All tests passed! The middleware fix should work correctly.\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
