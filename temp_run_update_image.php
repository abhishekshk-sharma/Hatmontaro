<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Http\Request;
use App\Http\Controllers\AdminProductController;
use App\Models\Product;
use Symfony\Component\HttpFoundation\File\UploadedFile;

$product = Product::where('slug','t-http-1')->first();
if (!$product) {
    echo "No product to update\n"; exit;
}

$path = __DIR__.'/temp_image.jpg';
$uploaded = new UploadedFile($path, 'temp_image.jpg', null, null, true);

$req = Request::create('/admin/products/'.$product->id, 'POST', [
    'name' => 'T-HTTP-IMG',
    'slug' => $product->slug,
    'description' => 'with image',
    'price' => 20.0,
    '_method' => 'PUT'
]);
$req->files->set('image', $uploaded);
$app->instance('request', $req);

$controller = new AdminProductController();
try{
    $resp = $controller->update($req, $product);
    echo "Update returned redirect\n";
} catch (Throwable $e) {
    echo "ERROR: " . $e->getMessage() . PHP_EOL;
}

$p = Product::find($product->id);
if ($p) echo "Product now name: ".$p->name." image_url:".$p->image_url.PHP_EOL; else echo "Product missing\n";

// list storage products
$files = glob(__DIR__.'/storage/app/public/products/*');
if ($files) { foreach ($files as $f) echo basename($f)."\n"; } else echo "No product files\n";
