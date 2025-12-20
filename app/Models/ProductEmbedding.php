<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class ProductEmbedding extends Model
{
    protected $table = 'product_embeddings';
    protected $fillable = ['product_id', 'provider', 'vector'];

    protected $casts = [
        'vector' => 'array',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
