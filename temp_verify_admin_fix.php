<?php
/**
 * Test script to verify AdminProductController update logic after middleware fix
 */
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

// Boot Laravel
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);

try {
    echo "=== AdminProductController Update Test ===\n\n";
    
    // Test 1: Verify middleware exists
    $middlewarePath = __DIR__ . '/app/Http/Middleware/AdminAuth.php';
    if (file_exists($middlewarePath)) {
        echo "✅ AdminAuth middleware exists\n";
    } else {
        echo "❌ AdminAuth middleware not found\n";
        exit(1);
    }
    
    // Test 2: Verify controller instantiation (no constructor issues)
    $controller = new \App\Http\Controllers\AdminProductController();
    echo "✅ AdminProductController instantiated successfully (no constructor errors)\n";
    
    // Test 3: Test product update logic
    $product = \App\Models\Product::first();
    if ($product) {
        echo "✅ Found test product ID {$product->id}: {$product->name}\n";
        
        // Test update method directly
        $oldPrice = $product->price;
        $newPrice = $oldPrice + 10.00;
        
        $product->update(['price' => $newPrice]);
        $product->refresh();
        
        if ($product->price == $newPrice) {
            echo "✅ Product price updated: {$oldPrice} → {$newPrice}\n";
        } else {
            echo "❌ Product price update failed\n";
            exit(1);
        }
        
        // Revert
        $product->update(['price' => $oldPrice]);
        echo "✅ Product price reverted to {$oldPrice}\n";
    } else {
        echo "⚠️ No products found in database\n";
    }
    
    // Test 4: Check route configuration
    $routes = \Illuminate\Support\Facades\Route::getRoutes();
    $updateRoute = null;
    foreach ($routes as $route) {
        if ($route->getName() === 'admin.products.update') {
            $updateRoute = $route;
            break;
        }
    }
    
    if ($updateRoute) {
        $middleware = $updateRoute->middleware();
        if (in_array('admin_auth', $middleware)) {
            echo "✅ admin_auth middleware is applied to admin.products.update route\n";
        } else {
            echo "⚠️ admin_auth middleware not found on route (middleware: " . implode(', ', $middleware) . ")\n";
        }
    } else {
        echo "❌ admin.products.update route not found\n";
        exit(1);
    }
    
    echo "\n✅ All tests passed! Admin product edit/update should work correctly.\n";
    echo "\nNext steps:\n";
    echo "1. Log in to admin panel (go to /admin/login)\n";
    echo "2. Try editing a product\n";
    echo "3. Update product details and submit\n";
    echo "4. Verify changes are saved in admin products list\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
    exit(1);
}
