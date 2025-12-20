@extends('layouts.app')

@section('title', 'Aura - AI Fashion Platform')

@section('content')
<!-- Offers & Discounts Cards -->
<section class="py-4" style="margin-top: 56px; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
    <div class="container">
        <div class="row g-3">
            @forelse($banners as $banner)
            <div class="col-md-6 col-lg-3">
                <div class="card offer-card h-100 border-0 shadow-sm position-relative" 
                     style="@if($banner->image) background: url('{{ asset('banners/' . $banner->image) }}') center/cover; @else background: linear-gradient(135deg, {{ $banner->gradient_from ?? '#667eea' }} 0%, {{ $banner->gradient_to ?? '#764ba2' }} 100%); @endif">
                    @if($banner->image)
                        <div class="position-absolute w-100 h-100" style="background: rgba(0,0,0,0.5); border-radius: 15px;"></div>
                    @endif
                    <div class="card-body text-white p-4 position-relative">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h5 class="card-title fw-bold mb-0">{{ $banner->title }}</h5>
                            <i class="bi bi-percent fs-3"></i>
                        </div>
                        <p class="card-text mb-3">{{ $banner->description }}</p>
                        <a href="{{ $banner->button_link }}" class="btn btn-light btn-sm fw-bold">
                            {{ $banner->button_text }}
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-md-6 col-lg-3">
                <div class="card offer-card h-100 border-0 shadow-sm" style="background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);">
                    <div class="card-body text-white p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h5 class="card-title fw-bold mb-0">50% OFF</h5>
                            <i class="bi bi-fire fs-3"></i>
                        </div>
                        <p class="card-text mb-3">On all summer collection items</p>
                        <a href="{{ route('products.index') }}" class="btn btn-light btn-sm fw-bold">
                            Shop Now
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card offer-card h-100 border-0 shadow-sm" style="background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%);">
                    <div class="card-body text-white p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h5 class="card-title fw-bold mb-0">Free Shipping</h5>
                            <i class="bi bi-truck fs-3"></i>
                        </div>
                        <p class="card-text mb-3">On orders above ₹999</p>
                        <a href="{{ route('products.index') }}" class="btn btn-light btn-sm fw-bold">
                            Explore
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card offer-card h-100 border-0 shadow-sm" style="background: linear-gradient(135deg, #a29bfe 0%, #6c5ce7 100%);">
                    <div class="card-body text-white p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h5 class="card-title fw-bold mb-0">Buy 2 Get 1</h5>
                            <i class="bi bi-gift fs-3"></i>
                        </div>
                        <p class="card-text mb-3">On selected categories</p>
                        <a href="{{ route('products.index') }}" class="btn btn-light btn-sm fw-bold">
                            View Deals
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card offer-card h-100 border-0 shadow-sm" style="background: linear-gradient(135deg, #fd79a8 0%, #e84393 100%);">
                    <div class="card-body text-white p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h5 class="card-title fw-bold mb-0">New User</h5>
                            <i class="bi bi-star fs-3"></i>
                        </div>
                        <p class="card-text mb-3">Extra 20% off on first order</p>
                        <a href="{{ route('register') }}" class="btn btn-light btn-sm fw-bold">
                            Sign Up
                        </a>
                    </div>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Category-wise Products Section -->
@foreach($categoryProducts as $categoryData)
<section class="py-4 bg-white">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold mb-0">{{ $categoryData['category']->name }}</h3>
            <a href="{{ route('products.byCategory', $categoryData['category']) }}" class="btn btn-outline-primary btn-sm">
                See all <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
        
        <div class="row g-3">
            @foreach($categoryData['products'] as $product)
                <div class="col-6 col-md-3 col-lg-2">
                    <a href="{{ route('products.show', $product) }}" class="text-decoration-none">
                        <div class="card product-card border-0 h-100">
                            <div class="position-relative">
                                <img src="{{ $product->image_url }}" 
                                     class="card-img-top" 
                                     alt="{{ $product->name }}"
                                     style="height: 180px; object-fit: cover;">
                                
                                @if($product->is_ai_recommended)
                                    <span class="position-absolute top-0 end-0 m-2">
                                        <span class="badge bg-primary">AI Pick</span>
                                    </span>
                                @endif
                            </div>
                            
                            <div class="card-body p-3">
                                <h6 class="card-title mb-2 text-dark">{{ Str::limit($product->name, 30) }}</h6>
                                <div class="d-flex justify-content-center align-items-center">
                                    <span class="fw-bold text-primary">₹{{ number_format($product->price) }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endforeach

<!-- AI Recommended Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-4">
            <h2 class="fw-bold mb-3">AI Curated For You</h2>
            <p class="text-muted">Personalized recommendations based on your preferences</p>
        </div>
        
        <div class="row g-3">
            @forelse($featuredProducts as $product)
                <div class="col-6 col-md-3 col-lg-2">
                    <a href="{{ route('products.show', $product) }}" class="text-decoration-none">
                        <div class="card product-card border-0  h-100">
                            <div class="position-relative">
                                <img src="{{ $product->image_url }}" 
                                     class="card-img-top" 
                                     alt="{{ $product->name }}"
                                     style="height: 180px; object-fit: cover;">
                                
                                <span class="position-absolute top-0 end-0 m-2">
                                    <span class="badge bg-success">AI Recommended</span>
                                </span>
                            </div>
                            
                            <div class="card-body p-3">
                                <h6 class="card-title mb-2 text-dark">{{ Str::limit($product->name, 30) }}</h6>
                                <div class="d-flex justify-content-center align-items-center">
                                    <span class="fw-bold text-primary">₹{{ number_format($product->price) }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-12 text-center py-4">
                    <h5>No AI recommendations yet</h5>
                    <p class="text-muted">Browse our products to get personalized suggestions!</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Virtual Try-On Feature -->
<section class="py-5 bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <div class="container py-5">
        <div class="row align-items-center">
            <div class="col-lg-6 text-white">
                <span class="badge bg-warning text-dark mb-3 px-3 py-2">NEW FEATURE</span>
                <h2 class="display-5 fw-bold mb-4" style= 'color: blue;'>Try Caps Virtually with AI</h2>
                <p class="lead mb-4 opacity-90" style= 'color: black;'>
                    Experience our revolutionary AI-powered cap try-on! See how different styles, 
                    brands, and colors look on you in real-time using your camera.
                </p>
                <ul class="list-unstyled mb-4" style= 'color: black;'>
                    <li class="mb-2"><i class="bi bi-check-circle-fill me-2"></i> 6 Different Cap Styles</li>
                    <li class="mb-2"><i class="bi bi-check-circle-fill me-2"></i> Multiple Premium Brands</li>
                    <li class="mb-2"><i class="bi bi-check-circle-fill me-2"></i> Unlimited Color Options</li>
                    <li class="mb-2"><i class="bi bi-check-circle-fill me-2"></i> Real-Time Face Tracking</li>
                    <li class="mb-2"><i class="bi bi-check-circle-fill me-2"></i> Capture & Share Photos</li>
                </ul>
                <a href="{{ route('caps.tryon') }}" class="btn btn-light btn-lg rounded-pill px-5">
                    <i class="bi bi-camera-video me-2"></i>Try It Now
                </a>
            </div>
            <div class="col-lg-6 mt-4 mt-lg-0">
                <div class="position-relative">
                    <img src="https://images.unsplash.com/photo-1588850561407-ed78c282e89b?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                         class="img-fluid rounded-4 shadow-lg" alt="Cap Try-On">
                    <div class="position-absolute top-50 start-50 translate-middle">
                        <a href="{{ route('caps.tryon') }}" class="btn btn-light btn-lg rounded-circle" style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-play-fill fs-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- How It Works -->
<section class="py-5 bg-light">
    <div class="container py-5">
        <h2 class="text-center display-6 fw-bold mb-5">How Aura Works</h2>
        
        <div class="row g-4">
            <div class="col-md-4">
                <div class="text-center p-4">
                    <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 80px; height: 80px;">
                        <i class="bi bi-chat-dots text-white fs-3"></i>
                    </div>
                    <h4>1. Chat with Aura</h4>
                    <p class="text-muted">Tell our AI about your style needs, occasion, or preferences.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center p-4">
                    <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 80px; height: 80px;">
                        <i class="bi bi-magic text-white fs-3"></i>
                    </div>
                    <h4>2. Get AI Recommendations</h4>
                    <p class="text-muted">Our AI analyzes thousands of products to find perfect matches.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center p-4">
                    <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 80px; height: 80px;">
                        <i class="bi bi-bag-check text-white fs-3"></i>
                    </div>
                    <h4>3. Shop with Confidence</h4>
                    <p class="text-muted">Purchase items that truly fit your style and needs.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Categories Quick Access -->
<section class="py-4 bg-white">
    <div class="container">
        <h4 class="fw-bold mb-4">Shop by Category</h4>
        
        <div class="row g-3">
            @forelse($categories as $category)
                <div class="col-6 col-md-3 col-lg-2">
                    <a href="{{ route('products.byCategory', $category) }}" class="text-decoration-none">
                        <div class="card border text-center hover-lift h-100">
                            <div class="card-body p-3">
                                <div class="mb-2">
                                    <i class="bi bi-tag fs-2 text-primary"></i>
                                </div>
                                <h6 class="card-title mb-1">{{ $category->name }}</h6>
                                <small class="text-muted">{{ $category->products_count ?? 0 }} items</small>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-12 text-center py-3">
                    <p class="text-muted">No categories available yet.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .product-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: none;
        border-radius: 15px;
        overflow: hidden;
    }
    
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    
    .hover-lift:hover {
        transform: translateY(-3px);
        transition: transform 0.3s ease;
    }
    
    .offer-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 15px;
    }
    
    .offer-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.2) !important;
    }
</style>
@endpush