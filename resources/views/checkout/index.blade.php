@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="container py-5" style="margin-top: 80px;">
    <h2 class="mb-4">Checkout</h2>

    @if($cartItems->isEmpty())
        <div class="alert alert-warning">
            Your cart is empty. <a href="{{ route('products.index') }}">Continue Shopping</a>
        </div>
    @else
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Shipping Information</h5>
                </div>
                <div class="card-body">
                    <form id="checkoutForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" class="form-control" value="{{ e(auth()->user()->username) }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" value="{{ e(auth()->user()->email) }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phone Number *</label>
                            <input type="text" name="phone_no" class="form-control" value="{{ e(auth()->user()->phone_no) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Shipping Address *</label>
                            <textarea name="shipping_address" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Payment Method *</label>
                            <select name="payment_method" class="form-select" required>
                                <option value="">Select Payment Method</option>
                                <option value="razorpay">Razorpay (Card/UPI/Wallet)</option>
                                {{-- <option value="cod">Cash on Delivery</option> --}}
                            </select>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Order Summary</h5>
                </div>
                <div class="card-body">
                    @foreach($cartItems as $item)
                    <div class="d-flex justify-content-between mb-2">
                        <span>{{ $item->product->name }} x {{ $item->quantity }}</span>
                        <span>₹{{ number_format($item->product->price * $item->quantity, 2) }}</span>
                    </div>
                    @endforeach
                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <strong>Total</strong>
                        <strong>₹{{ number_format($total, 2) }}</strong>
                    </div>
                    <button type="button" id="placeOrderBtn" class="btn btn-primary w-100">Place Order</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
let isProcessing = false;

document.getElementById('placeOrderBtn').addEventListener('click', function() {
    if (isProcessing) return;
    
    const form = document.getElementById('checkoutForm');
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    const paymentMethod = form.querySelector('[name="payment_method"]').value;
    const formData = new FormData(form);
    
    // Show loading state
    const btn = document.getElementById('placeOrderBtn');
    const originalText = btn.innerHTML;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';
    btn.disabled = true;
    isProcessing = true;

    if (paymentMethod === 'razorpay') {
        @if(isset($razorpayOrder))
        const options = {
            key: '{{ config("services.razorpay.key") }}',
            amount: {{ $total * 100 }},
            currency: 'INR',
            name: 'Cloth.Ai',
            description: 'Fashion Order Payment',
            order_id: '{{ $razorpayOrder->id }}',
            handler: function(response) {
                formData.append('razorpay_payment_id', response.razorpay_payment_id);
                formData.append('razorpay_order_id', response.razorpay_order_id);
                formData.append('razorpay_signature', response.razorpay_signature);
                processOrder(formData);
            },
            prefill: {
                name: @json(auth()->user()->username),
                email: @json(auth()->user()->email),
                contact: form.querySelector('[name="phone_no"]').value
            },
            theme: {
                color: '#7c3aed'
            },
            modal: {
                ondismiss: function() {
                    resetButton(btn, originalText);
                }
            }
        };
        const rzp = new Razorpay(options);
        rzp.open();
        @else
        alert('Sorry! For you inconvenience, We Are Working On It.');
        resetButton(btn, originalText);
        @endif
    } else {
        processOrder(formData);
    }
});

function processOrder(formData) {
    fetch('{{ route("checkout.process") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = '/checkout/success/' + data.order_id;
        } else {
            throw new Error(data.message || 'Payment processing failed');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert(error.message || 'Error processing order. Please try again.');
        const btn = document.getElementById('placeOrderBtn');
        resetButton(btn, 'Place Order');
    });
}

function resetButton(btn, originalText) {
    btn.innerHTML = originalText;
    btn.disabled = false;
    isProcessing = false;
}
</script>
@endsection
