@extends('layouts.app')

@section('title', 'AI Recommended Products - Aura')

@section('content')
<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">AI Recommendations</li>
        </ol>
    </nav>
    
    <!-- Header -->
    <div class="ai-hero position-relative rounded-3 overflow-hidden mb-5" 
         style="min-height: 350px; background: {{ $banner && $banner->banner_image ? 'url(' . $banner->banner_image_url . ')' : 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)' }} center/cover;">
        <div class="position-absolute top-0 start-0 w-100 h-100" style="background: rgba(0,0,0,0.4);"></div>
        <div class="position-relative text-center text-white p-5 d-flex flex-column justify-content-center" style="min-height: 350px;">
            <div class="display-1 mb-3">
                <i class="bi bi-robot"></i>
            </div>
            <h1 class="display-5 fw-bold mb-3">{{ $banner ? $banner->title : 'AI Curated Collections' }}</h1>
            <p class="lead mb-0">
                {{ $banner ? $banner->description : 'Our AI analyzes trends, your preferences, and style data to bring you the best picks.' }}
            </p>
        </div>
    </div>
    
    <!-- AI Explanation -->
    <div class="alert alert-primary mb-5">
        <div class="d-flex">
            <i class="bi bi-info-circle fs-4 me-3"></i>
            <div>
                <h5 class="alert-heading">How Our AI Selects Products</h5>
                <p class="mb-0">
                    These items are selected based on current fashion trends, customer preferences, 
                    style compatibility, and seasonal relevance. Each piece is analyzed for quality, 
                    versatility, and style impact.
                </p>
            </div>
        </div>
    </div>
    
    <!-- Products Grid -->
    @if($products->count() > 0)
        <div class="row g-4">
            @foreach($products as $product)
                <div class="col-md-6 col-lg-3">
                    <a href="{{ route('products.show', $product) }}" class="text-decoration-none">
                        <div class="card product-card h-100">
                            <div class="position-relative">
                                <img src="{{ $product->image_url }}" 
                                     class="card-img-top" 
                                     alt="{{ $product->name }}"
                                     style="height: 250px; object-fit: cover;">
                                
                                <span class="position-absolute top-0 end-0 m-2">
                                    <span class="badge bg-primary">
                                        <i class="bi bi-robot me-1"></i>AI Pick
                                    </span>
                                </span>
                            </div>
                            
                            <div class="card-body">
                                <h5 class="card-title text-dark">{{ $product->name }}</h5>
                                <p class="card-text text-muted small">
                                    {{ Str::limit($product->description, 80) }}
                                </p>
                                
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <span class="h5 fw-bold text-primary">₹{{ number_format($product->price, 2) }}</span>
                                </div>
                                
                                <div class="mt-2">
                                    <small class="text-muted">
                                        <i class="bi bi-tag me-1"></i>{{ $product->category->name ?? '' }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="mt-5">
            {{ $products->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="text-center py-5">
            <div class="display-1 text-muted mb-4">
                <i class="bi bi-robot"></i>
            </div>
            <h3>No AI Recommendations Yet</h3>
            <p class="text-muted mb-4">
                Our AI is still learning! Check back soon or ask Aura for personalized recommendations.
            </p>
            <button class="btn btn-primary btn-lg" data-bs-toggle="ai-modal">
                <i class="bi bi-robot me-2"></i>Ask Aura for Recommendations
            </button>
        </div>
    @endif
</div>
@endsection