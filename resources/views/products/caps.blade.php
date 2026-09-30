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
    
    <div class="amazon-container py-4">
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
                <div class="amazon-products-grid">
                    @foreach($products as $product)
                        <x-product-card :product="$product" />
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