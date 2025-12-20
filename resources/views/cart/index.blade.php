@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<div class="container py-5">
    <h1 class="h2 mb-4">Shopping Cart</h1>
    
    @php
        // Get cart
        if (auth()->check()) {
            $cart = \App\Models\Cart::where('user_id', auth()->id())->first();
        } else {
            $sessionId = session()->getId();
            $cart = \App\Models\Cart::where('session_id', $sessionId)->first();
        }
    @endphp
    
    @if($cart && $cart->items->count() > 0)
        <div class="row">
            <!-- Cart Items -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        @foreach($cart->items as $item)
                            <div class="row align-items-center mb-4 pb-4 border-bottom">
                                <div class="col-md-2">
                                    <img src="{{ $item->product->image_url ?? 'https://via.placeholder.com/100' }}" 
                                         class="img-fluid rounded" 
                                         alt="{{ $item->product->name ?? 'Product' }}"
                                         style="height: 80px; object-fit: cover;">
                                </div>
                                
                                <div class="col-md-5">
                                    <h5 class="mb-1">{{ $item->product->name ?? 'Product' }}</h5>
                                    <p class="text-muted small mb-2">
                                        {{ $item->product->category->name ?? '' }}
                                    </p>
                                    
                                    @if($item->options)
                                        <div class="small">
                                            @foreach($item->options as $key => $value)
                                                <span class="badge bg-light text-dark me-1">
                                                    {{ $key }}: {{ $value }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="col-md-2">
                                    <div class="input-group input-group-sm">
                                        <button class="btn btn-outline-secondary" 
                                                onclick="updateQuantity({{ $item->id }}, -1)">
                                            <i class="bi bi-dash"></i>
                                        </button>
                                        <input type="text" 
                                               class="form-control text-center" 
                                               value="{{ $item->quantity }}"
                                               id="quantity-{{ $item->id }}"
                                               readonly>
                                        <button class="btn btn-outline-secondary" 
                                                onclick="updateQuantity({{ $item->id }}, 1)">
                                            <i class="bi bi-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="col-md-2 text-end">
                                    <h6 class="mb-0">₹{{ number_format($item->price * $item->quantity, 2) }}</h6>
                                    <small class="text-muted">₹{{ $item->price }} each</small>
                                </div>
                                
                                <div class="col-md-1 text-end">
                                    <form action="{{ route('cart.remove', $item) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-link text-danger" onclick="return confirm('Remove this item?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <!-- Continue Shopping -->
                <div class="mt-3">
                    <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-left me-2"></i>Continue Shopping
                    </a>
                    
                    <form action="{{ route('cart.clear') }}" method="POST" class="d-inline float-end">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Clear entire cart?')">
                            Clear Cart
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="card sticky-top" style="top: 100px;">
                    <div class="card-header">
                        <h5 class="mb-0">Order Summary</h5>
                    </div>
                    <div class="card-body">
                        @php
                            $subtotal = $cart->items->sum(function($item) {
                                return $item->price * $item->quantity;
                            });
                        @endphp
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal</span>
                            <span>₹{{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Shipping</span>
                            <span>Free</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tax</span>
                            <span>Calculated at checkout</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-4">
                            <strong>Total</strong>
                            <strong>₹{{ number_format($subtotal, 2) }}</strong>
                        </div>
                        
                        <a href="{{ route('checkout') }}" class="btn btn-primary btn-lg w-100 mb-3">
                            Proceed to Checkout
                        </a>
                        
                        <!-- Ask Aura About Cart -->
                        <button class="btn btn-outline-dark w-100" data-bs-toggle="ai-modal">
                            <i class="bi bi-robot me-2"></i>Ask Aura about these items
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Empty Cart -->
        <div class="text-center py-5">
            <div class="display-1 text-muted mb-4">
                <i class="bi bi-cart-x"></i>
            </div>
            <h3>Your cart is empty</h3>
            <p class="text-muted mb-4">Add some products to get started!</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">
                Start Shopping
            </a>
        </div>
    @endif
</div>
@endsection