@extends('layouts.app')

@section('title', 'Order Success')

@section('content')
<div class="container py-5" style="margin-top: 80px;">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm text-center">
                <div class="card-body py-5">
                    <div class="mb-4">
                        <svg width="80" height="80" fill="currentColor" class="text-success">
                            <circle cx="40" cy="40" r="38" fill="none" stroke="currentColor" stroke-width="4"/>
                            <path d="M20 40 L35 55 L60 25" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <h2 class="mb-3">Order Placed Successfully!</h2>
                    <p class="text-muted mb-4">Thank you for your order. Your order number is:</p>
                    <h4 class="text-primary mb-4">#{{ $order->order_number }}</h4>
                    
                    <div class="alert alert-info">
                        <strong>Order Details:</strong><br>
                        Total Amount: ₹{{ number_format($order->total_amount, 2) }}<br>
                        Status: {{ ucfirst($order->status) }}<br>
                        Payment Method: {{ ucfirst($order->payment_method) }}
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('home') }}" class="btn btn-primary me-2">Continue Shopping</a>
                        <a href="{{ route('user.profile') }}" class="btn btn-outline-primary">View Orders</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
