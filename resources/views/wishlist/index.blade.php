@extends('layouts.app')

@section('title', 'My Wishlist')

@section('content')
<div class="amazon-container py-4">
    <h2 class="mb-4">My Wishlist</h2>

    @if($wishlistItems->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-heart display-1 text-muted"></i>
            <h3 class="mt-3">Your wishlist is empty</h3>
            <p class="text-muted">Save items you love for later</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary">Continue Shopping</a>
        </div>
    @else
        <div class="amazon-products-grid">
            @foreach($wishlistItems as $item)
                <div class="amazon-product-card product-card h-100">
                    <div class="amazon-badges-bar">
                        <span class="badge bg-secondary text-white" style="font-size: 0.7rem;">Saved Item</span>
                        <button type="button" class="amazon-btn-wishlist active"
                                onclick="removeFromWishlist({{ $item->id }})" title="Remove from Wishlist">
                            <i class="bi bi-trash3 text-danger"></i>
                        </button>
                    </div>
                    
                    <a href="{{ route('products.show', $item->product) }}" class="amazon-image-wrapper text-decoration-none">
                        <img src="{{ $item->product->image_url }}" 
                             class="card-img-top" 
                             alt="{{ $item->product->name }}"
                             onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1588850561407-ed78c282e89b?auto=format&fit=crop&w=500&q=80';">
                    </a>
                    
                    <div class="amazon-card-body card-body">
                        <div class="amazon-brand-row">
                            <span class="brand-title">{{ $item->product->brand ?? ($item->product->category->name ?? 'Hatmontaro') }}</span>
                        </div>
                        
                        <a href="{{ route('products.show', $item->product) }}" class="amazon-title card-title" title="{{ $item->product->name }}">
                            {{ $item->product->name }}
                        </a>
                        
                        <div class="amazon-pricing-box mt-2">
                            <div class="amazon-price-row">
                                <span class="amazon-currency-symbol">₹</span>
                                <span class="amazon-price-main">{{ number_format($item->product->price) }}</span>
                                <span class="amazon-price-cents">00</span>
                                @if($item->product->compare_price)
                                    <span class="amazon-mrp-wrapper">
                                        M.R.P.: <span class="amazon-mrp-value">₹{{ number_format($item->product->compare_price) }}</span>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="amazon-delivery-box">
                            @if($item->product->is_premium_delivery)
                                <div class="amazon-prime-line">
                                    <span class="hatmontaro-plus-badge"><i class="bi bi-patch-check-fill"></i> hatmontaro <span class="plus-symbol">+</span></span>
                                    <span class="text-dark fw-bold ms-1">FREE Delivery</span>
                                </div>
                                <div>
                                    Get it by <span class="amazon-delivery-bold">{{ $item->product->delivery_time ?? 'Tomorrow, 2 PM' }}</span>
                                </div>
                            @else
                                <div class="amazon-prime-line">
                                    <span class="standard-delivery-badge"><i class="bi bi-truck"></i> Standard Delivery</span>
                                </div>
                                <div>
                                    Get it by <span class="amazon-delivery-bold">{{ $item->product->delivery_time ?? '3-5 Business Days' }}</span>
                                </div>
                            @endif
                        </div>
                        
                        <div class="amazon-card-actions mt-auto">
                            <button class="amazon-btn-cart" onclick="moveToCart({{ $item->id }})">
                                <i class="bi bi-cart-plus"></i> Move to Cart
                            </button>
                            <a href="{{ route('products.show', $item->product) }}" class="amazon-btn-secondary">
                                View Product
                            </a>
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