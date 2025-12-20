<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Http\Request;
use App\Http\Controllers\AdminProductController;
use App\Models\Product;

$product = Product::where('slug','t-http-1')->first();
if (!$product) {
    echo "No product to update\n"; exit;
}

$req = Request::create('/admin/products/'.$product->id, 'POST', [
    'name' => 'T-HTTP-UPDATED',
    'slug' => $product->slug,
    'description' => 'updated',
    'price' => 15.0,
    '_method' => 'PUT'
]);
$app->instance('request', $req);
$controller = new AdminProductController();
try{
    $resp = $controller->update($req, $product);
    echo "Update controller returned: ";
    if ($resp instanceof Illuminate\Http\RedirectResponse) echo 'Redirect to '.$resp->getTargetUrl()."\n";
    else echo get_class($resp)."\n";
} catch (Throwable $e) {
    echo "ERROR: " . $e->getMessage() . PHP_EOL;
}

$p = Product::find($product->id);
if ($p) echo "Product now name: ".$p->name." price:".$p->price.PHP_EOL; else echo "Product missing\n";
