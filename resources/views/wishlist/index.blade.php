@extends('layouts.app')

@section('title', 'My Wishlist')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">My Wishlist</h2>

    @if($wishlistItems->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-heart display-1 text-muted"></i>
            <h3 class="mt-3">Your wishlist is empty</h3>
            <p class="text-muted">Save items you love for later</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary">Continue Shopping</a>
        </div>
    @else
        <div class="row g-4">
            @foreach($wishlistItems as $item)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    <div class="position-relative">
                        <img src="{{ $item->product->image_url }}" 
                             class="card-img-top" 
                             alt="{{ $item->product->name }}"
                             style="height: 250px; object-fit: cover;">
                        
                        <button class="btn btn-sm btn-outline-danger position-absolute top-0 end-0 m-2"
                                onclick="removeFromWishlist({{ $item->id }})">
                            <i class="bi bi-heart-fill"></i>
                        </button>
                    </div>
                    
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $item->product->name }}</h5>
                        <p class="card-text text-muted small flex-grow-1">
                            {{ Str::limit($item->product->description, 80) }}
                        </p>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <span class="h5 fw-bold text-primary">₹{{ number_format($item->product->price, 2) }}</span>
                            <div class="btn-group">
                                <button class="btn btn-primary btn-sm" onclick="moveToCart({{ $item->id }})">
                                    <i class="bi bi-cart-plus"></i> Add to Cart
                                </button>
                                <a href="{{ route('products.show', $item->product) }}" class="btn btn-outline-primary btn-sm">
                                    View
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>

<script>
function removeFromWishlist(id) {
    if (confirm('Remove from wishlist?')) {
        fetch(`/wishlist/remove/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        }).then(() => location.reload());
    }
}

function moveToCart(id) {
    fetch(`/wishlist/move-to-cart/${id}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    }).then(() => location.reload());
}
</script>
@endsection