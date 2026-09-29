@extends('layouts.app')

@section('title', 'Caps Collection - Hatmontaro')

@section('content')
<div class="container-fluid">
    @if($banner)
    <div class="row">
        <div class="col-12 p-0">
            <div class="position-relative">
                <img src="{{ $banner->image_url }}" class="w-100" style="height: 300px; object-fit: cover;" alt="Caps Collection">
                <div class="position-absolute top-50 start-50 translate-middle text-center text-white">
                    <h1 class="display-4 fw-bold">{{ $banner->title }}</h1>
                    <p class="lead">{{ $banner->subtitle }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    <div class="container py-5">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="fw-bold">Caps Collection</h2>
                    <div class="d-flex gap-2">
                        <a href="{{ route('caps.tryon') }}" class="btn btn-success">
                            <i class="bi bi-camera-video"></i> Try On Caps
                        </a>
                    </div>
                </div>
                
                @if($products->count() > 0)
                <div class="row">
                    @foreach($products as $product)
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                        <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none">
                            <div class="card product-card shadow-sm h-100">
                                <div class="position-relative">
                                    <img src="{{ $product->image_url }}" class="card-img-top" style="height: 250px; object-fit: cover;" alt="{{ $product->name }}">
                                    @if($product->is_ai_recommended)
                                    <span class="position-absolute top-0 end-0 badge bg-primary m-2">
                                        <i class="bi bi-robot"></i> AI Pick
                                    </span>
                                    @endif
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">{{ $product->name }}</h5>
                                    <p class="text-muted small mb-2">{{ Str::limit($product->description, 60) }}</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-bold text-primary fs-5">₹{{ number_format($product->price, 2) }}</span>
                                        @if($product->compare_price && $product->compare_price > $product->price)
                                        <small class="text-muted text-decoration-line-through">₹{{ number_format($product->compare_price, 2) }}</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-5">
                    {{ $products->links() }}
                </div>
                @else
                <div class="text-center py-5">
                    <i class="bi bi-search fs-1 text-muted mb-3 d-block"></i>
                    <h4 class="text-muted">No caps found</h4>
                    <p class="text-muted">Check back later for new arrivals!</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection