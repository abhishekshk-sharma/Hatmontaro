@extends('layouts.app')

@section('title', 'My Cart - Hatmontaro')

@section('content')
<div class="amazon-container py-3 py-md-4 cart-page-container">
    @php
        $itemCount = $cartItems->sum('quantity');
        $subtotal = $cartItems->sum(fn($item) => ($item->product->price ?? 0) * $item->quantity);
    @endphp

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-2">
        <ol class="breadcrumb mb-1">
            <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none text-muted small"><i class="bi bi-house-door me-1"></i>Home</a></li>
            <li class="breadcrumb-item active small text-dark fw-semibold" aria-current="page">My Cart</li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h1 class="h4 fw-bolder mb-0 text-dark">My Shopping Cart</h1>
            <p class="text-muted small mb-0 d-none d-sm-block">Manage your saved items and proceed to checkout</p>
        </div>
        @if($itemCount > 0)
            <span class="badge bg-light text-dark border px-3 py-2 rounded-pill fw-semibold" style="font-size: 0.8rem;">
                {{ $itemCount }} {{ Str::plural('item', $itemCount) }}
            </span>
        @endif
    </div>

    @if($cartItems->count() > 0)
        <!-- Mobile Top Subtotal & Fast Checkout Banner (Amazon-style, mobile only) -->
        <div class="cart-top-subtotal-card mb-3 d-lg-none">
            <div class="d-flex justify-content-between align-items-baseline mb-1">
                <span class="text-secondary small fw-medium">Subtotal ({{ $itemCount }} {{ Str::plural('item', $itemCount) }}):</span>
                <span class="fs-4 fw-bolder text-dark">₹{{ number_format($subtotal, 2) }}</span>
            </div>
            <div class="d-flex align-items-center gap-1 text-success small mb-2">
                <i class="bi bi-patch-check-fill text-success fs-6"></i>
                <span>Your order qualifies for <strong class="text-dark">FREE Delivery</strong></span>
            </div>
            <div class="form-check small text-muted mb-3">
                <input class="form-check-input" type="checkbox" id="giftCheckMobileUser" checked>
                <label class="form-check-label" for="giftCheckMobileUser">
                    This order contains a gift
                </label>
            </div>
            <a href="{{ route('checkout') }}" class="btn btn-primary w-100 cart-checkout-btn-mobile d-flex align-items-center justify-content-center gap-2">
                <span>Proceed to Checkout</span>
                <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div class="row g-4">
            <!-- Cart Items List Column -->
            <div class="col-lg-8">
                <div class="d-flex flex-column gap-3">
                    @foreach($cartItems as $item)
                        @php
                            $prod = $item->product;
                            $prodImg = $prod ? $prod->image_url : null;
                            if ($prodImg && !\Illuminate\Support\Str::startsWith($prodImg, ['http://','https://','/'])) {
                                $prodImg = asset($prodImg);
                            }
                            $itemPrice = $prod ? $prod->price : 0;
                            $lineTotal = $itemPrice * $item->quantity;
                            $prodUrl = $prod ? route('products.show', $prod->slug ?: $prod->id) : '#';
                        @endphp

                        <div class="cart-item-card position-relative" id="user-cart-item-{{ $item->id }}">
                            <div class="d-flex gap-3 align-items-start">
                                <!-- Product Thumbnail -->
                                <a href="{{ $prodUrl }}" class="cart-item-thumb text-decoration-none">
                                    <img src="{{ $prodImg ?: 'https://images.unsplash.com/photo-1588850561407-ed78c282e89b?auto=format&fit=crop&w=300&q=80' }}" 
                                         alt="{{ $prod->name ?? 'Product' }}"
                                         loading="lazy">
                                </a>

                                <!-- Product Details -->
                                <div class="flex-grow-1 min-w-0">
                                    <!-- Title -->
                                    <div class="mb-1">
                                        <a href="{{ $prodUrl }}" class="cart-item-title" title="{{ $prod->name ?? 'Product' }}">
                                            {{ $prod->name ?? 'Product' }}
                                        </a>
                                    </div>

                                    <!-- Price Row -->
                                    <div class="d-flex align-items-baseline flex-wrap gap-2 mb-1">
                                        <span class="cart-item-price">₹{{ number_format($lineTotal, 2) }}</span>
                                        @if($prod && $prod->original_price && $prod->original_price > $itemPrice)
                                            <span class="text-muted text-decoration-line-through small" style="font-size: 0.8rem;">
                                                ₹{{ number_format($prod->original_price * $item->quantity, 2) }}
                                            </span>
                                        @endif
                                        @if($item->quantity > 1)
                                            <span class="text-muted small" style="font-size: 0.75rem;">
                                                (₹{{ number_format($itemPrice, 2) }} each)
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Stock & Delivery Status -->
                                    <div class="d-flex align-items-center flex-wrap gap-2 small mb-2" style="font-size: 0.76rem;">
                                        <span class="text-success fw-semibold d-inline-flex align-items-center gap-1">
                                            <i class="bi bi-check-circle-fill"></i> In Stock
                                        </span>
                                        <span class="text-muted">•</span>
                                        <span class="text-muted">Eligible for <strong class="text-dark">FREE Delivery</strong></span>
                                        @if($prod && $prod->category)
                                            <span class="badge bg-light text-secondary border px-2 py-0.5 rounded-pill" style="font-size: 0.68rem;">
                                                {{ $prod->category->name }}
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Bottom Action Controls: Stepper & Actions -->
                                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 pt-2 border-top">
                                        <!-- Amazon-style Pill Stepper with Form Submission -->
                                        <form action="{{ route('user.cart.update', $item->id) }}" method="POST" id="update-form-{{ $item->id }}" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="quantity" id="input-qty-{{ $item->id }}" value="{{ $item->quantity }}">
                                            <div class="cart-stepper">
                                                <button type="button" class="cart-stepper-btn" onclick="adjustUserCartQty({{ $item->id }}, -1)" title="Decrease">
                                                    @if($item->quantity == 1)
                                                        <i class="bi bi-trash3 text-danger"></i>
                                                    @else
                                                        <i class="bi bi-dash-lg"></i>
                                                    @endif
                                                </button>
                                                <span class="cart-stepper-value" id="display-qty-{{ $item->id }}">{{ $item->quantity }}</span>
                                                <button type="button" class="cart-stepper-btn" onclick="adjustUserCartQty({{ $item->id }}, 1)" title="Increase">
                                                    <i class="bi bi-plus-lg"></i>
                                                </button>
                                            </div>
                                        </form>

                                        <!-- Actions: Delete & Save for Later -->
                                        <div class="d-flex align-items-center gap-1.5">
                                            <form action="{{ route('user.cart.remove', $item->id) }}" method="POST" id="remove-user-form-{{ $item->id }}" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="cart-action-pill danger-pill" onclick="return confirm('Remove this item from your cart?')" title="Delete item">
                                                    <i class="bi bi-trash3 text-danger"></i>
                                                    <span>Delete</span>
                                                </button>
                                            </form>

                                            <form action="{{ route('user.cart.saveForLater', $item->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="cart-action-pill" title="Save for Later">
                                                    <i class="bi bi-heart text-secondary"></i>
                                                    <span class="d-none d-sm-inline">Save for Later</span>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Continue Shopping Button -->
                <div class="d-flex justify-content-between align-items-center mt-3 mb-4">
                    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary rounded-pill px-3 py-2 btn-sm fw-medium">
                        <i class="bi bi-arrow-left me-1"></i> Continue Shopping
                    </a>
                </div>
            </div>

            <!-- Order Summary Column -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 bg-white sticky-top mb-4" style="top: 90px;">
                    <div class="card-header bg-white border-bottom py-3 px-4">
                        <h2 class="h5 mb-0 fw-bolder text-dark">Order Summary</h2>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-secondary">Items Subtotal ({{ $itemCount }}):</span>
                            <span class="fw-semibold text-dark">₹{{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-secondary">Shipping:</span>
                            <span class="text-success fw-bold">FREE</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-secondary">Estimated Tax:</span>
                            <span class="text-muted">₹0.00</span>
                        </div>
                        <hr class="my-3">
                        <div class="d-flex justify-content-between align-items-baseline mb-4">
                            <span class="fs-5 fw-bold text-dark">Order Total:</span>
                            <span class="fs-4 fw-bolder text-dark">₹{{ number_format($subtotal, 2) }}</span>
                        </div>

                        <a href="{{ route('checkout') }}" class="btn btn-primary btn-lg w-100 rounded-pill py-3 fw-bold shadow-sm mb-3 d-flex align-items-center justify-content-center gap-2">
                            <span>Proceed to Checkout</span>
                            <i class="bi bi-arrow-right"></i>
                        </a>

                        <!-- Safe & Secure Badges -->
                        <div class="p-3 bg-light rounded-3 text-center mb-3 border">
                            <i class="bi bi-shield-lock-fill text-success fs-3 mb-1 d-block"></i>
                            <div class="fw-semibold small text-dark">Safe & Secure Payment</div>
                            <div class="text-muted" style="font-size: 0.72rem;">Encrypted 256-bit checkout with Razorpay</div>
                        </div>

                        <!-- Trust Badges -->
                        <div class="row g-2 text-center text-muted" style="font-size: 0.72rem;">
                            <div class="col-4">
                                <div class="p-2 border rounded-3 bg-white">
                                    <i class="bi bi-truck text-primary d-block fs-6 mb-1"></i>
                                    <span>Fast Delivery</span>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-2 border rounded-3 bg-white">
                                    <i class="bi bi-arrow-repeat text-success d-block fs-6 mb-1"></i>
                                    <span>7 Days Return</span>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-2 border rounded-3 bg-white">
                                    <i class="bi bi-patch-check text-info d-block fs-6 mb-1"></i>
                                    <span>100% Genuine</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Empty Cart State -->
        <div class="card border-0 shadow-sm rounded-4 bg-white text-center py-5 px-3 my-4">
            <div class="mb-3">
                <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-light p-4" style="width: 100px; height: 100px;">
                    <i class="bi bi-cart-x text-primary fs-1"></i>
                </div>
            </div>
            <h2 class="h4 fw-bold mb-2 text-dark">Your Hatmontaro Cart is empty</h2>
            <p class="text-muted mb-4 mx-auto" style="max-width: 420px; font-size: 0.92rem;">
                Explore our curated collections of premium caps, streetwear, and AI-recommended fashion to get started.
            </p>
            <div class="d-flex justify-content-center flex-wrap gap-2">
                <a href="{{ route('products.index') }}" class="btn btn-primary rounded-pill px-4 py-2.5 fw-semibold shadow-sm">
                    <i class="bi bi-grid me-1"></i> Explore Collections
                </a>
                <a href="{{ route('shop.caps') }}" class="btn btn-outline-success rounded-pill px-4 py-2.5 fw-semibold">
                    <i class="bi bi-camera-video me-1"></i> Virtual Cap Try-On
                </a>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
function adjustUserCartQty(itemId, delta) {
    const input = document.getElementById('input-qty-' + itemId);
    const form = document.getElementById('update-form-' + itemId);
    if (!input || !form) return;

    let currentQty = parseInt(input.value) || 1;
    let newQty = currentQty + delta;

    if (newQty < 1) {
        if (confirm('Remove this item from your cart?')) {
            const removeForm = document.getElementById('remove-user-form-' + itemId);
            if (removeForm) removeForm.submit();
        }
        return;
    }

    input.value = newQty;
    form.submit();
}
</script>
@endpush
@endsection
