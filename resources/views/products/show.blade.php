@extends('layouts.app')

@section('title', $product->name . ' - Aura')

@section('content')
<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Shop</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
        </ol>
    </nav>
    
    <div class="row">
        <!-- Product Images -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm position-relative" id="productImageCard">
                @php
                    $img = $product->image_url;
                    if (!\Illuminate\Support\Str::startsWith($img, ['http://','https://','/'])) {
                        $img = asset($img);
                    }
                    $isVideo = false;
                @endphp
                
                @if($isVideo)
                    <video id="productVideo" class="card-img-top rounded" controls style="max-height: 500px; width:100%;">
                        <source src="{{ $img }}" type="video/mp4">
                    </video>
                @else
                    <img src="{{ $img }}" 
                         id="productImage"
                         class="card-img-top rounded" 
                         alt="{{ $product->name }}"
                         style="max-height: 500px; object-fit: cover; cursor: zoom-in;"
                         onclick="openImageModal()">
                @endif
                
                @if($product->is_ai_recommended)
                    <div class="position-absolute top-0 end-0 m-3" style="z-index:10">
                        <div class="bg-primary text-white px-3 py-1 rounded-pill">
                            <i class="bi bi-robot me-1"></i> AI Recommended
                        </div>
                    </div>
                @endif
            </div>
            
            @php
                $allMedia = collect();
                if ($product->image_url) {
                    $allMedia->push((object)['url' => $product->image_url, 'type' => 'image']);
                }
                $allMedia = $allMedia->merge($product->media);
            @endphp
            
            @if($allMedia->count() > 1)
            <div class="mt-3">
                <div class="row g-2">
                    @foreach($allMedia as $index => $media)
                    <div class="col-2">
                        <div class="card media-thumb {{ $index === 0 ? 'border-primary' : '' }}" 
                             style="cursor:pointer;" 
                             onclick="changeMedia('{{ $media->url }}', '{{ $media->type }}', this)">
                            @if($media->type === 'video')
                                <video src="{{ $media->url }}" class="card-img-top" style="height:60px;object-fit:cover;"></video>
                            @else
                                <img src="{{ $media->url }}" class="card-img-top" style="height:60px;object-fit:cover;" />
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
        
        <!-- Product Info -->
        <div class="col-lg-6 position-relative">
            <!-- Magnified Overlay -->
            <div id="magnifiedOverlay" class="position-absolute top-0 start-0 w-100 h-100 bg-white border shadow-lg rounded" 
                 style="display: none; z-index: 1000; overflow: hidden;">
                <div id="magnifiedView" class="w-100 h-100" style="background-repeat: no-repeat;"></div>
            </div>
            
            <div class="ps-lg-4">
                <!-- Badges -->
                <div class="mb-3">
                    @if($product->is_ai_recommended)
                        <span class="badge bg-primary me-2">AI Recommended</span>
                    @endif
                    @if($product->is_featured)
                        <span class="badge bg-success me-2">Featured</span>
                    @endif
                    <span class="badge bg-secondary">{{ $product->category->name ?? 'Uncategorized' }}</span>
                </div>
                
                <!-- Product Name -->
                <h1 class="h2 mb-3">{{ $product->name }}</h1>
                
                <!-- Price -->
                <div class="mb-4">
                    <span class="display-6 text-primary fw-bold">₹{{ number_format($product->price, 2) }}</span>
                    @if($product->compare_price)
                        <span class="text-muted text-decoration-line-through fs-4 ms-2">
                            ₹{{ number_format($product->compare_price, 2) }}
                        </span>
                        <span class="badge bg-danger ms-2">
                            Save ₹{{ number_format($product->compare_price - $product->price, 2) }}
                        </span>
                    @endif
                </div>
                
                <!-- Description -->
                <div class="mb-4">
                    <h5 class="mb-2">Description</h5>
                    <p class="text-muted">{{ $product->description }}</p>
                </div>
                
                <!-- Details -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card border-light mb-3">
                            <div class="card-body">
                                <h6 class="card-subtitle mb-2 text-muted">Style</h6>
                                <p class="card-text fw-bold">{{ ucfirst($product->style_type) }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-light mb-3">
                            <div class="card-body">
                                <h6 class="card-subtitle mb-2 text-muted">Occasion</h6>
                                <p class="card-text fw-bold">{{ ucfirst($product->occasion) }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-light mb-3">
                            <div class="card-body">
                                <h6 class="card-subtitle mb-2 text-muted">Color</h6>
                                <p class="card-text fw-bold">{{ ucfirst($product->color) }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-light mb-3">
                            <div class="card-body">
                                <h6 class="card-subtitle mb-2 text-muted">Availability</h6>
                                <p class="card-text fw-bold {{ $product->stock_quantity > 0 ? 'text-success' : 'text-danger' }}">
                                    {{ $product->stock_quantity > 0 ? 'In Stock' : 'Out of Stock' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- AI Analysis (if available) -->
                @if($product->is_ai_recommended)
                    <div class="alert alert-primary mb-4">
                        <div class="d-flex">
                            <i class="bi bi-robot fs-4 me-3"></i>
                            <div>
                                <h6 class="alert-heading">Why Aura Recommends This</h6>
                                <p class="mb-0 small">
                                    This piece matches current trends and works well for {{ $product->occasion }} occasions. 
                                    The {{ $product->color }} color complements various skin tones.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
                
                <!-- Try On Button (for caps only) -->
                @if($product->category && $product->category->slug === 'caps')
                    <div class="alert alert-success mb-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <i class="bi bi-camera-video fs-4 me-2"></i>
                                <strong>Try this cap virtually!</strong>
                                <p class="mb-0 small">See how it looks on you in real-time</p>
                            </div>
                            <a href="{{ route('caps.tryon', ['product' => $product->id]) }}" class="btn btn-success">
                                <i class="bi bi-camera-video me-2"></i>Try It Now
                            </a>
                        </div>
                    </div>
                @endif

                <!-- Action Buttons -->
<div class="d-grid gap-2 d-md-flex mb-5">
    @if($product->stock_quantity > 0)
        @auth
        <form action="{{ route('user.cart.add', $product) }}" method="POST" class="me-2">
            @csrf
            <button type="submit" class="btn btn-primary btn-lg px-5">
                <i class="bi bi-cart-plus me-2"></i>Add to Cart
            </button>
        </form>
        <form action="{{ route('wishlist.add', $product) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-outline-danger btn-lg px-4">
                <i class="bi bi-heart me-2"></i>Wishlist
            </button>
        </form>
        @else
        <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-5 me-2">
            <i class="bi bi-cart-plus me-2"></i>Add to Cart
        </a>
        <a href="{{ route('login') }}" class="btn btn-outline-danger btn-lg px-4">
            <i class="bi bi-heart me-2"></i>Wishlist
        </a>
        @endauth
    @else
        <button class="btn btn-secondary btn-lg px-5" disabled>
            Out of Stock
        </button>
    @endif
</div>
                
                <!-- Ask Aura About This -->
                <div class="text-center">
                    <button class="btn btn-outline-dark" data-bs-toggle="ai-modal">
                        <i class="bi bi-robot me-2"></i>Ask Aura about this item
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Similar Products -->
    @if($similarProducts->count() > 0)
        <div class="mt-5 pt-5 border-top">
            <h3 class="mb-4">You Might Also Like</h3>
            <div class="row g-4">
                @foreach($similarProducts as $similar)
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm h-100">
                            @php
                                $simg = $similar->image_url;
                                if (!\Illuminate\Support\Str::startsWith($simg, ['http://','https://','/storage/'])) {
                                    $simg = asset($simg);
                                }
                            @endphp
                            <img src="{{ $simg }}" 
                                 class="card-img-top" 
                                 alt="{{ $similar->name }}"
                                 style="height: 200px; object-fit: cover;">
                            <div class="card-body">
                                <h6 class="card-title">{{ Str::limit($similar->name, 50) }}</h6>
                                <p class="card-text text-primary fw-bold">₹{{ $similar->price }}</p>
                                <a href="{{ route('products.show', $similar) }}" class="btn btn-sm btn-outline-primary">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">{{ $product->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div class="row g-0">
                    <!-- Main Image in Modal -->
                    <div class="col-md-9">
                        <div class="position-relative">
                            <img id="modalMainImage" src="{{ $img }}" class="w-100" style="max-height: 70vh; object-fit: contain;">
                        </div>
                    </div>
                    <!-- Thumbnails in Modal -->
                    <div class="col-md-3 bg-light">
                        <div class="p-3">
                            <h6 class="mb-3">All Images</h6>
                            <div class="d-grid gap-2">
                                @foreach($allMedia as $index => $media)
                                @if($media->type === 'image')
                                <div class="modal-thumb {{ $index === 0 ? 'border-primary' : '' }}" 
                                     style="cursor:pointer; border: 2px solid transparent;" 
                                     onclick="changeModalImage('{{ $media->url }}', this)">
                                    <img src="{{ $media->url }}" class="w-100 rounded" style="height:80px;object-fit:cover;" />
                                </div>
                                @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let allMedia = @json($allMedia);

function changeMedia(url, type, element) {
    // Remove active class from all thumbnails
    document.querySelectorAll('.media-thumb').forEach(thumb => {
        thumb.classList.remove('border-primary');
    });
    // Add active class to clicked thumbnail
    element.classList.add('border-primary');
    
    const card = document.getElementById('productImageCard');
    if (type === 'video') {
        card.innerHTML = `<video id="productVideo" class="card-img-top rounded" controls style="max-height: 500px; width:100%;"><source src="${url}" type="video/mp4"></video>`;
    } else {
        card.innerHTML = `
            <img src="${url}" 
                 id="productImage" 
                 class="card-img-top rounded" 
                 style="max-height: 500px; object-fit: cover; cursor: zoom-in;"
                 onclick="openImageModal()"
                 alt="{{ $product->name }}">
        `;
        initMagnifier();
    }
}

function initMagnifier() {
    const img = document.getElementById('productImage');
    const overlay = document.getElementById('magnifiedOverlay');
    const magnifiedView = document.getElementById('magnifiedView');
    if (!img || !overlay || !magnifiedView) return;
    
    const zoom = 2.5;
    
    img.addEventListener('mouseenter', () => {
        overlay.style.display = 'block';
        magnifiedView.style.backgroundImage = `url('${img.src}')`;
    });
    
    img.addEventListener('mousemove', (e) => {
        const rect = img.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        
        // Calculate background position for magnified view
        const bgX = -x * zoom + overlay.offsetWidth / 2;
        const bgY = -y * zoom + overlay.offsetHeight / 2;
        
        magnifiedView.style.backgroundSize = `${rect.width * zoom}px ${rect.height * zoom}px`;
        magnifiedView.style.backgroundPosition = `${bgX}px ${bgY}px`;
    });
    
    img.addEventListener('mouseleave', () => {
        overlay.style.display = 'none';
    });
}

function openImageModal() {
    const modal = new bootstrap.Modal(document.getElementById('imageModal'));
    modal.show();
}

function changeModalImage(url, element) {
    // Remove active class from all modal thumbnails
    document.querySelectorAll('.modal-thumb').forEach(thumb => {
        thumb.classList.remove('border-primary');
        thumb.style.borderColor = 'transparent';
    });
    // Add active class to clicked thumbnail
    element.classList.add('border-primary');
    element.style.borderColor = '#0d6efd';
    
    // Change main modal image
    document.getElementById('modalMainImage').src = url;
}

// Initialize magnifier on page load
document.addEventListener('DOMContentLoaded', function() {
    initMagnifier();
});
</script>
@endpush

@endsection