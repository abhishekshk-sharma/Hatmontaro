<?php
echo "=== ADMIN PRODUCT EDIT DIAGNOSIS ===\n\n";

echo "1. Route Configuration\n";
echo "   Checking if route exists...\n";
$routes = file_get_contents('routes/web.php');
if (strpos($routes, "Route::put('products/{product}'") !== false) {
    echo "   ✅ PUT route defined for products/{product}\n";
} else {
    echo "   ❌ PUT route NOT found\n";
}

if (strpos($routes, "middleware('admin_auth')") !== false) {
    echo "   ✅ admin_auth middleware found in routes\n";
} else {
    echo "   ❌ admin_auth middleware NOT applied to routes\n";
}

echo "\n2. Middleware Registration\n";
$bootstrap = file_get_contents('bootstrap/app.php');
if (strpos($bootstrap, "'admin_auth'") !== false) {
    echo "   ✅ admin_auth middleware registered in bootstrap/app.php\n";
} else {
    echo "   ❌ admin_auth middleware NOT registered\n";
}

if (strpos($bootstrap, "App\\Http\\Middleware\\AdminAuth::class") !== false) {
    echo "   ✅ AdminAuth class alias configured\n";
} else {
    echo "   ❌ AdminAuth class NOT configured\n";
}

echo "\n3. Middleware File Exists\n";
if (file_exists('app/Http/Middleware/AdminAuth.php')) {
    echo "   ✅ AdminAuth.php middleware file exists\n";
    $content = file_get_contents('app/Http/Middleware/AdminAuth.php');
    if (strpos($content, "session()->has('admin_id')") !== false) {
        echo "   ✅ AdminAuth.php checks for admin_id in session\n";
    } else {
        echo "   ❌ AdminAuth.php does NOT check admin_id\n";
    }
} else {
    echo "   ❌ AdminAuth.php NOT found\n";
}

echo "\n4. Controller Configuration\n";
$controller = file_get_contents('app/Http/Controllers/AdminProductController.php');
if (strpos($controller, "public function update(Request \$request, Product \$product)") !== false) {
    echo "   ✅ update() method exists in AdminProductController\n";
} else {
    echo "   ❌ update() method NOT found\n";
}

if (strpos($controller, "\$product->update(\$data)") !== false) {
    echo "   ✅ update() method calls \$product->update()\n";
} else {
    echo "   ❌ update() does NOT call \$product->update()\n";
}

if (preg_match('/if\s*\(\s*\$request->hasFile\s*\(\s*[\'"]image[\'"]\s*\)/', $controller)) {
    echo "   ✅ update() checks for file upload\n";
} else {
    echo "   ❌ update() does NOT check for file upload\n";
}

echo "\n5. View Form Configuration\n";
if (file_exists('resources/views/admin/products/edit.blade.php')) {
    $view = file_get_contents('resources/views/admin/products/edit.blade.php');
    
    if (strpos($view, "route('admin.products.update'") !== false) {
        echo "   ✅ edit form posts to admin.products.update route\n";
    } else {
        echo "   ❌ edit form does NOT post to correct route\n";
    }
    
    if (strpos($view, "@method('PUT')") !== false) {
        echo "   ✅ edit form uses @method('PUT') for form spoofing\n";
    } else {
        echo "   ❌ edit form does NOT use PUT method\n";
    }
    
    if (strpos($view, "@csrf") !== false) {
        echo "   ✅ edit form includes CSRF token\n";
    } else {
        echo "   ❌ edit form missing CSRF token\n";
    }
    
    if (strpos($view, "enctype=\"multipart/form-data\"") !== false) {
        echo "   ✅ edit form allows file uploads (multipart)\n";
    } else {
        echo "   ❌ edit form NOT configured for file uploads\n";
    }
    
} else {
    echo "   ❌ edit.blade.php NOT found\n";
}

echo "\n=== DIAGNOSIS COMPLETE ===\n";
echo "\nNext steps:\n";
echo "1. Log in to admin panel\n";
echo "2. Go to edit a product\n";
echo "3. Check browser console for any errors\n";
echo "4. Check network tab - does the PUT request go through?\n";
echo "5. If redirected, check the redirect URL\n";
?>
