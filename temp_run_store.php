<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Http\Request;
use App\Http\Controllers\AdminProductController;

$req = Request::create('/admin/products', 'POST', [
    'name' => 'T-HTTP',
    'slug' => 't-http-1',
    'description' => 'desc',
    'price' => 12.5,
    // intentionally omit category_id to force controller-created uncategorized
]);

// swap the current request in the container
$app->instance('request', $req);

$controller = new AdminProductController();
try {
    $resp = $controller->store($req);
    echo "Controller store returned: ";
    if ($resp instanceof Illuminate\Http\RedirectResponse) {
        echo "Redirect to " . $resp->getTargetUrl() . PHP_EOL;
    } else {
        echo get_class($resp) . PHP_EOL;
    }
} catch (Throwable $e) {
    echo "ERROR: " . $e->getMessage() . PHP_EOL;
}

// Check created product
$p = \App\Models\Product::where('slug', 't-http-1')->first();
if ($p) {
    echo "Product created id=" . $p->id . " category_id=" . $p->category_id . "\n";
} else {
    echo "Product not found\n";
}

// Check category
$c = \App\Models\Category::where('slug','uncategorized')->first();
if ($c) echo "Uncategorized id=".$c->id.PHP_EOL; else echo "No Uncategorized\n";
