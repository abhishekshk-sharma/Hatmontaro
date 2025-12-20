<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CapTryOnController extends Controller
{
    // Show the try-on UI
    public function index(Request $request)
    {
        // Get cap products with pagination
        $caps = Product::whereHas('category', function ($q) {
            $q->where('slug', 'caps');
        })->paginate(12);

        // Get specific product if ID provided
        $selectedProduct = null;
        if ($request->has('product')) {
            $selectedProduct = Product::find($request->get('product'));
        }

        return view('caps.tryon', compact('caps', 'selectedProduct'));
    }

    // Simple recommendations API: return caps in same category
    public function recommendations(Request $request)
    {
        $brand = $request->query('brand');
        $color = $request->query('color');
        
        // If embeddings are available and OPENAI_API_KEY is configured, use embedding similarity
        try {
            if (env('OPENAI_API_KEY')) {
                $query = $request->query('q');
                $productId = $request->query('product_id');
                // Load stored embeddings
                $stored = \App\Models\ProductEmbedding::whereHas('product', function ($q) use ($brand, $color) {
                    $q->whereHas('category', function ($q2) { $q2->where('slug','caps'); });
                    if ($brand) $q->where('brand', $brand);
                    if ($color) $q->where('color', $color);
                })->get();

                if ($stored->count()) {
                    $embedService = new \App\Services\EmbeddingService();
                    $queryVec = null;
                    if ($productId) {
                        $pe = $stored->firstWhere('product_id', (int)$productId);
                        if ($pe && !empty($pe->vector)) {
                            $queryVec = $pe->vector;
                        }
                    }
                    if (!$queryVec && $query) {
                        $queryVec = $embedService->embedText($query);
                    }

                    if ($queryVec) {
                        $items = [];
                        foreach ($stored as $s) {
                            $vec = $s->vector;
                            $score = \App\Services\EmbeddingService::cosineSimilarity($queryVec, $vec);
                            $items[] = ['product_id' => $s->product_id, 'score' => $score];
                        }
                        usort($items, function($a,$b){ return $b['score'] <=> $a['score']; });
                        $top = array_slice($items, 0, 12);
                        $ids = array_map(fn($i) => $i['product_id'], $top);
                        $caps = Product::whereIn('id', $ids)->get(['id','name','brand','image_url','price','slug','color']);
                        // preserve order by scores
                        $caps = $caps->sortBy(function($p) use ($ids){ return array_search($p->id, $ids); })->values();
                        return response()->json(['data' => $caps]);
                    }
                }
            }
        } catch (\Exception $e) {
            // fallback to non-AI recommendations if anything fails
            \Log::warning('Embedding recommendation failed: ' . $e->getMessage());
        }

        // Fallback: simple category listing with filters
        $query = Product::whereHas('category', function ($q) {
            $q->where('slug', 'caps');
        });
        
        if ($brand) {
            $query->where('brand', $brand);
        }
        if ($color) {
            $query->where('color', $color);
        }
        
        $caps = $query->take(12)->get(['id','name','brand','image_url','price','slug','color']);

        return response()->json(['data' => $caps]);
    }
    
    // Get available brands
    public function brands()
    {
        $brands = Product::whereHas('category', function ($q) {
            $q->where('slug', 'caps');
        })->distinct()->pluck('brand')->filter()->sort()->values();
        
        return response()->json(['data' => $brands]);
    }
    
    // Get available colors
    public function colors()
    {
        $colors = Product::whereHas('category', function ($q) {
            $q->where('slug', 'caps');
        })->distinct()->pluck('color')->filter()->sort()->values();
        
        return response()->json(['data' => $colors]);
    }
}
