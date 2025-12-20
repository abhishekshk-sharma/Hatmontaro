<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\ProductEmbedding;
use App\Services\EmbeddingService;

class ComputeEmbeddings extends Command
{
    protected $signature = 'embeddings:compute {--category= : slug of category to limit to}';
    protected $description = 'Compute embeddings for products and store them in product_embeddings table';

    public function handle()
    {
        if (!env('OPENAI_API_KEY')) {
            $this->error('OPENAI_API_KEY not set in environment.');
            return 1;
        }

        $svc = new EmbeddingService();
        $q = Product::query();
        if ($this->option('category')) {
            $slug = $this->option('category');
            $q->whereHas('category', fn($b) => $b->where('slug', $slug));
        }

        $count = 0;
        $q->chunk(50, function($products) use ($svc, &$count) {
            foreach ($products as $p) {
                try {
                    $text = $p->name . '\n' . ($p->description ?? '') . '\n' . implode(' ', (array)($p->tags ?? []));
                    $vec = $svc->embedText($text);
                    ProductEmbedding::updateOrCreate(['product_id' => $p->id], ['provider' => 'openai', 'vector' => $vec]);
                    $count++;
                    $this->info("Embedded product {$p->id} - {$p->name}");
                } catch (\Exception $e) {
                    $this->error('Failed for product ' . $p->id . ': ' . $e->getMessage());
                }
            }
        });

        $this->info("Done. Embedded {$count} products.");
        return 0;
    }
}
