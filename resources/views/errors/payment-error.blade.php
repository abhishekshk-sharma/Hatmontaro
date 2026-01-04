@extends('layouts.app')

@section('title', 'Payment Error')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-body text-center p-5">
                    <i class="bi bi-exclamation-triangle text-warning" style="font-size: 4rem;"></i>
                    <h2 class="mt-3 mb-4">Payment Processing Error</h2>
                    <p class="text-muted mb-4">
                        We encountered an issue while processing your payment. 
                        Your order has not been completed.
                    </p>
                    <div class="alert alert-info">
                        <strong>What to do next:</strong><br>
                        • Check your payment method and try again<br>
                        • Contact our support team if the issue persists<br>
                        • Reference ID: {{ session('error_ref', 'N/A') }}
                    </div>
                    <div class="d-grid gap-2">
                        <a href="{{ route('user.cart') }}" class="btn btn-primary">Return to Cart</a>
                        <a href="{{ route('contact') }}" class="btn btn-outline-secondary">Contact Support</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection