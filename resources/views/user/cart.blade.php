@extends('layouts.app')

@section('title', 'My Cart')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">My Shopping Cart</h2>
    
    @if($cartItems->count() > 0)
        <div class="row">
            <div class="col-lg-8">
                @foreach($cartItems as $item)
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-2">
                                <img src="{{ $item->product->image_url }}" class="img-fluid rounded" />
                            </div>
                            <div class="col-md-4">
                                <h5>{{ $item->product->name }}</h5>
                                <p class="text-muted">{{ $item->product->category->name ?? '' }}</p>
                            </div>
                            <div class="col-md-2">
                                <form action="{{ route('user.cart.update', $item->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" class="form-control" onchange="this.form.submit()">
                                </form>
                            </div>
                            <div class="col-md-2">
                                <h5 class="text-primary">₹{{ number_format($item->product->price * $item->quantity, 2) }}</h5>
                            </div>
                            <div class="col-md-2">
                                <form action="{{ route('user.cart.saveForLater', $item->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-secondary mb-2 w-100">Save for Later</button>
                                </form>
                                <form action="{{ route('user.cart.remove', $item->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger w-100">Remove</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5>Order Summary</h5>
                        <hr>
                        @php
                            $subtotal = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);
                        @endphp
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal</span>
                            <span>₹{{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Shipping</span>
                            <span>Free</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <strong>Total</strong>
                            <strong>₹{{ number_format($subtotal, 2) }}</strong>
                        </div>
                        <a href="{{ route('checkout') }}" class="btn btn-primary w-100">Proceed to Checkout</a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-5">
            <i class="bi bi-cart-x" style="font-size:4rem;color:#ccc;"></i>
            <h3 class="mt-3">Your cart is empty</h3>
            <a href="{{ route('products.index') }}" class="btn btn-primary mt-3">Start Shopping</a>
        </div>
    @endif
</div>
@endsection
