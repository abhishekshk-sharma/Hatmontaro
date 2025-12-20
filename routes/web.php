<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AIRecommendationController;
use App\Http\Controllers\CapTryOnController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserCartController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\AdminCategoryController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminBannerController;
// routes/web.php
use App\Http\Controllers\SitemapController;

Route::get('/admin/generate-sitemap', [SitemapController::class, 'generate'])
    ->middleware('auth'); // protect it if you have auth


// User Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Homepage
Route::get('/', [HomeController::class, 'index'])->name('home');

// Products
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/ai-recommended', [ProductController::class, 'aiRecommended'])->name('products.aiRecommended');
Route::get('/products/category/{category}', [ProductController::class, 'byCategory'])->name('products.byCategory');

// Category shortcuts
Route::get('/men', [ProductController::class, 'byCategory'])->defaults('category', 'men')->name('shop.men');
Route::get('/women', [ProductController::class, 'byCategory'])->defaults('category', 'women')->name('shop.women');
Route::get('/children', [ProductController::class, 'byCategory'])->defaults('category', 'children')->name('shop.children');
Route::get('/newborn', [ProductController::class, 'byCategory'])->defaults('category', 'newborn')->name('shop.newborn');
Route::get('/caps', [ProductController::class, 'byCategory'])->defaults('category', 'caps')->name('shop.caps');

// Caps try-on UI
Route::get('/caps/tryon', [CapTryOnController::class, 'index'])->name('caps.tryon');
Route::get('/api/caps/recommendations', [CapTryOnController::class, 'recommendations']);
Route::get('/api/caps/brands', [CapTryOnController::class, 'brands']);
Route::get('/api/caps/colors', [CapTryOnController::class, 'colors']);

Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show')->where('product', '[0-9]+|[a-z0-9-]+');

// AI Recommendations
Route::post('/ai/recommend', [AIRecommendationController::class, 'getRecommendations'])->name('ai.recommend');

// Cart (simple implementation for now)
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
Route::put('/cart/{item}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{item}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

// User Cart & Profile
Route::middleware('auth')->group(function() {
    Route::get('/my-cart', [UserCartController::class, 'index'])->name('user.cart');
    Route::post('/my-cart/add/{product}', [UserCartController::class, 'add'])->name('user.cart.add');
    Route::put('/my-cart/{id}', [UserCartController::class, 'update'])->name('user.cart.update');
    Route::delete('/my-cart/{id}', [UserCartController::class, 'remove'])->name('user.cart.remove');
    Route::post('/my-cart/{id}/save-later', [UserCartController::class, 'saveForLater'])->name('user.cart.saveForLater');
    
    Route::get('/profile', [\App\Http\Controllers\UserProfileController::class, 'show'])->name('user.profile');
    Route::put('/profile', [\App\Http\Controllers\UserProfileController::class, 'update'])->name('user.profile.update');
    Route::put('/profile/password', [\App\Http\Controllers\UserProfileController::class, 'updatePassword'])->name('user.profile.password');
    Route::get('/orders', [\App\Http\Controllers\UserProfileController::class, 'allOrders'])->name('user.orders.all');
    Route::get('/orders/{order}', [\App\Http\Controllers\UserProfileController::class, 'viewOrder'])->name('user.orders.view');
    
    // Complaints
    Route::get('/complaints', [\App\Http\Controllers\ComplaintController::class, 'index'])->name('user.complaints.index');
    Route::get('/complaints/create', [\App\Http\Controllers\ComplaintController::class, 'create'])->name('user.complaints.create');
    Route::post('/complaints', [\App\Http\Controllers\ComplaintController::class, 'store'])->name('user.complaints.store');
    Route::get('/complaints/{complaint}', [\App\Http\Controllers\ComplaintController::class, 'show'])->name('user.complaints.show');
    
    // Wishlist
    Route::get('/wishlist', [\App\Http\Controllers\WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/add/{product}', [\App\Http\Controllers\WishlistController::class, 'add'])->name('wishlist.add');
    Route::delete('/wishlist/remove/{id}', [\App\Http\Controllers\WishlistController::class, 'remove'])->name('wishlist.remove');
    Route::post('/wishlist/move-to-cart/{id}', [\App\Http\Controllers\WishlistController::class, 'moveToCart'])->name('wishlist.moveToCart');
});

// Checkout
Route::middleware('auth')->group(function() {
    Route::get('/checkout', [\App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout/process', [\App\Http\Controllers\CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/success/{order}', [\App\Http\Controllers\CheckoutController::class, 'success'])->name('checkout.success');
});

// Payment Webhook (no auth required)
Route::post('/payment/webhook', [\App\Http\Controllers\CheckoutController::class, 'webhook'])->name('payment.webhook');

// AI Chat
Route::post('/ai/chat', [\App\Http\Controllers\AIChatController::class, 'chat'])->name('ai.chat');
Route::post('/ai/select-category', [\App\Http\Controllers\AIChatController::class, 'selectCategory'])->name('ai.selectCategory');

// Static Pages
Route::get('/about', [\App\Http\Controllers\PageController::class, 'about'])->name('about');
Route::get('/terms', [\App\Http\Controllers\PageController::class, 'terms'])->name('terms');
Route::get('/privacy', [\App\Http\Controllers\PageController::class, 'privacy'])->name('privacy');
Route::get('/contact', [\App\Http\Controllers\PageController::class, 'contact'])->name('contact');
Route::post('/contact', [\App\Http\Controllers\PageController::class, 'contactSubmit'])->name('contact.submit');

// Debug routes
Route::get('/debug', function() {
    return response()->json([
        'status' => 'ok',
        'routes' => [
            'home' => route('home'),
            'products.index' => route('products.index'),
            'products.aiRecommended' => route('products.aiRecommended'),
            'cart.index' => route('cart.index'),
        ]
    ]);
});

// Add this to routes/web.php
Route::get('/test-ai-page', function() {
    // Test if we can access the route
    try {
        $url = route('products.aiRecommended');
        echo "✅ Route exists: {$url}<br>";
        
        // Test if controller method works
        $controller = new \App\Http\Controllers\ProductController();
        if (method_exists($controller, 'aiRecommended')) {
            echo "✅ Controller method exists<br>";
            
            // Get products
            $products = \App\Models\Product::where('is_ai_recommended', true)->get();
            echo "✅ Found {$products->count()} AI recommended products<br>";
            
            echo "<a href='{$url}'>Click here to go to AI Recommended page</a>";
        } else {
            echo "❌ Controller method missing<br>";
        }
    } catch (\Exception $e) {
        echo "❌ Error: " . $e->getMessage() . "<br>";
    }
});

// Storage symlink route for hosting
Route::get('/create-storage-link', function() {
    if (!file_exists(public_path('storage'))) {
        $target = storage_path('app/public');
        $link = public_path('storage');
        
        if (function_exists('symlink')) {
            symlink($target, $link);
            return 'Storage symlink created successfully!';
        } else {
            return 'Symlink function not available. Contact hosting support.';
        }
    }
    return 'Storage symlink already exists!';
});

// --- Admin routes (simple session-based admin) ---
Route::prefix('admin')->group(function () {
    // Auth (no middleware)
    Route::get('register', [AdminAuthController::class, 'showRegister'])->name('admin.register');
    Route::post('register', [AdminAuthController::class, 'register']);
    Route::get('login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
    Route::post('login', [AdminAuthController::class, 'login']);
    Route::post('logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

    // Protected routes (require admin session)
    Route::middleware('admin_auth')->group(function () {
        // Dashboard
        Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

        // Products CRUD
        Route::get('products', [AdminProductController::class, 'index'])->name('admin.products.index');
        Route::get('products/create', [AdminProductController::class, 'create'])->name('admin.products.create');
        Route::post('products', [AdminProductController::class, 'store'])->name('admin.products.store');
        Route::get('products/{product}/edit', [AdminProductController::class, 'edit'])->name('admin.products.edit');
        Route::put('products/{product}', [AdminProductController::class, 'update'])->name('admin.products.update');
        Route::delete('products/{product}', [AdminProductController::class, 'destroy'])->name('admin.products.destroy');
        Route::delete('products/media/{id}', [AdminProductController::class, 'deleteMedia'])->name('admin.products.media.delete');
        
        // Categories CRUD
        Route::get('categories', [AdminCategoryController::class, 'index'])->name('admin.categories.index');
        Route::get('categories/create', [AdminCategoryController::class, 'create'])->name('admin.categories.create');
        Route::post('categories', [AdminCategoryController::class, 'store'])->name('admin.categories.store');
        Route::get('categories/{category}/edit', [AdminCategoryController::class, 'edit'])->name('admin.categories.edit');
        Route::put('categories/{category}', [AdminCategoryController::class, 'update'])->name('admin.categories.update');
        Route::delete('categories/{category}', [AdminCategoryController::class, 'destroy'])->name('admin.categories.destroy');
        
        // Orders Management
        Route::get('orders', [AdminOrderController::class, 'index'])->name('admin.orders.index');
        Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('admin.orders.show');
        Route::put('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('admin.orders.updateStatus');
        Route::delete('orders/{order}', [AdminOrderController::class, 'destroy'])->name('admin.orders.destroy');
        
        // Users Management
        Route::get('users', [AdminUserController::class, 'index'])->name('admin.users.index');
        Route::get('users/{user}', [AdminUserController::class, 'show'])->name('admin.users.show');
        Route::delete('users/{user}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');
        
        // Banners Management
        Route::get('banners', [AdminBannerController::class, 'index'])->name('admin.banners.index');
        Route::get('banners/create', [AdminBannerController::class, 'create'])->name('admin.banners.create');
        Route::post('banners', [AdminBannerController::class, 'store'])->name('admin.banners.store');
        Route::get('banners/{banner}/edit', [AdminBannerController::class, 'edit'])->name('admin.banners.edit');
        Route::put('banners/{banner}', [AdminBannerController::class, 'update'])->name('admin.banners.update');
        Route::delete('banners/{banner}', [AdminBannerController::class, 'destroy'])->name('admin.banners.destroy');
        Route::put('banners/{banner}/toggle', [AdminBannerController::class, 'toggleStatus'])->name('admin.banners.toggleStatus');
        
        // Contact Messages Management
        Route::get('contacts', [\App\Http\Controllers\AdminContactController::class, 'index'])->name('admin.contacts.index');
        Route::get('contacts/{contact}', [\App\Http\Controllers\AdminContactController::class, 'show'])->name('admin.contacts.show');
        Route::post('contacts/{contact}/respond', [\App\Http\Controllers\AdminContactController::class, 'respond'])->name('admin.contacts.respond');
        
        // Complaints Management
        Route::get('complaints', [\App\Http\Controllers\AdminComplaintController::class, 'index'])->name('admin.complaints.index');
        Route::get('complaints/{complaint}', [\App\Http\Controllers\AdminComplaintController::class, 'show'])->name('admin.complaints.show');
        Route::put('complaints/{complaint}', [\App\Http\Controllers\AdminComplaintController::class, 'updateStatus'])->name('admin.complaints.update');
        
        // About Us Management
        Route::get('about', [\App\Http\Controllers\Admin\AboutUsController::class, 'index'])->name('admin.about.index');
        Route::get('about/section/{id}/edit', [\App\Http\Controllers\Admin\AboutUsController::class, 'editSection'])->name('admin.about.section.edit');
        Route::put('about/section/{id}', [\App\Http\Controllers\Admin\AboutUsController::class, 'updateSection'])->name('admin.about.section.update');
        Route::get('about/team', [\App\Http\Controllers\Admin\AboutUsController::class, 'teamIndex'])->name('admin.about.team.index');
        Route::get('about/team/create', [\App\Http\Controllers\Admin\AboutUsController::class, 'teamCreate'])->name('admin.about.team.create');
        Route::post('about/team', [\App\Http\Controllers\Admin\AboutUsController::class, 'teamStore'])->name('admin.about.team.store');
        Route::get('about/team/{id}/edit', [\App\Http\Controllers\Admin\AboutUsController::class, 'teamEdit'])->name('admin.about.team.edit');
        Route::put('about/team/{id}', [\App\Http\Controllers\Admin\AboutUsController::class, 'teamUpdate'])->name('admin.about.team.update');
        Route::delete('about/team/{id}', [\App\Http\Controllers\Admin\AboutUsController::class, 'teamDestroy'])->name('admin.about.team.destroy');
        
        // Page Banners Management
        Route::get('page-banners', [\App\Http\Controllers\Admin\PageBannerController::class, 'index'])->name('admin.page-banners.index');
        Route::get('page-banners/{pageBanner}/edit', [\App\Http\Controllers\Admin\PageBannerController::class, 'edit'])->name('admin.page-banners.edit');
        Route::put('page-banners/{pageBanner}', [\App\Http\Controllers\Admin\PageBannerController::class, 'update'])->name('admin.page-banners.update');
        
        // Reports & Analytics
        Route::get('reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('admin.reports.index');
        Route::get('reports/export', [\App\Http\Controllers\Admin\ReportController::class, 'export'])->name('admin.reports.export');
    });
});