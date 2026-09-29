@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="container py-5">
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
                            <input type="text" name="phone_no" class="form-control" 
                                   value="{{ e(auth()->user()->phone_no) }}" 
                                   pattern="[6-9][0-9]{9}" 
                                   title="Please enter a valid 10-digit Indian mobile number" 
                                   required>
                            <small class="text-muted">Enter 10-digit mobile number starting with 6-9</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Shipping Address *</label>
                            <textarea name="shipping_address" class="form-control" rows="3" 
                                      maxlength="500" 
                                      pattern="[a-zA-Z0-9\s,.-]+" 
                                      title="Only letters, numbers, spaces, commas, periods and hyphens allowed" 
                                      required></textarea>
                            <small class="text-muted">Maximum 500 characters. Only letters, numbers, and basic punctuation allowed.</small>
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
                        <span>{{ e($item->product->name) }} x {{ $item->quantity }}</span>
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
let attemptCount = 0;
const maxAttempts = 3;

document.getElementById('placeOrderBtn').addEventListener('click', function() {
    if (isProcessing) return;
    
    if (attemptCount >= maxAttempts) {
        alert('Maximum payment attempts reached. Please refresh the page and try again.');
        return;
    }
    
    const form = document.getElementById('checkoutForm');
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    // Additional client-side validation
    const phoneNo = form.querySelector('[name="phone_no"]').value;
    const address = form.querySelector('[name="shipping_address"]').value;
    
    if (!/^[6-9]\d{9}$/.test(phoneNo)) {
        alert('Please enter a valid 10-digit Indian mobile number starting with 6-9.');
        return;
    }
    
    if (!/^[a-zA-Z0-9\s,.-]+$/.test(address)) {
        alert('Shipping address contains invalid characters. Only letters, numbers, spaces, commas, periods and hyphens are allowed.');
        return;
    }

    const paymentMethod = form.querySelector('[name="payment_method"]').value;
    const formData = new FormData(form);
    
    attemptCount++;
    
    // Show loading state
    const btn = document.getElementById('placeOrderBtn');
    const originalText = btn.innerHTML;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';
    btn.disabled = true;
    isProcessing = true;

    if (paymentMethod === 'razorpay') {
        @if(isset($razorpayOrder))
        const options = {
            key: @json(config('services.razorpay.key')),
            amount: {{ $total * 100 }},
            currency: 'INR',
            name: 'Cloth.Ai',
            description: 'Fashion Order Payment',
            order_id: @json($razorpayOrder->id),
            handler: function(response) {
                // Validate response format
                if (!response.razorpay_payment_id || !response.razorpay_order_id || !response.razorpay_signature) {
                    alert('Invalid payment response. Please try again.');
                    resetButton(btn, originalText);
                    return;
                }
                
                formData.append('razorpay_payment_id', response.razorpay_payment_id);
                formData.append('razorpay_order_id', response.razorpay_order_id);
                formData.append('razorpay_signature', response.razorpay_signature);
                processOrder(formData);
            },
            prefill: {
                name: @json(auth()->user()->username),
                email: @json(auth()->user()->email),
                contact: phoneNo
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
        
        try {
            const rzp = new Razorpay(options);
            rzp.open();
        } catch (error) {
            console.error('Razorpay initialization failed:', error);
            alert('Payment gateway initialization failed. Please try again.');
            resetButton(btn, originalText);
        }
        @else
        alert('Payment gateway not available. Please try again later.');
        resetButton(btn, originalText);
        @endif
    } else {
        processOrder(formData);
    }
});

function processOrder(formData) {
    const controller = new AbortController();
    const timeoutId = setTimeout(() => controller.abort(), 30000); // 30 second timeout
    
    fetch('{{ route("checkout.process") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        signal: controller.signal
    })
    .then(response => {
        clearTimeout(timeoutId);
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Prevent back button after successful payment
            history.pushState(null, null, location.href);
            window.onpopstate = function () {
                history.go(1);
            };
            window.location.href = '/checkout/success/' + data.order_id;
        } else {
            throw new Error(data.message || 'Payment processing failed');
        }
    })
    .catch(error => {
        clearTimeout(timeoutId);
        console.error('Error:', error);
        
        let errorMessage = 'Payment processing failed. Please try again.';
        if (error.name === 'AbortError') {
            errorMessage = 'Request timeout. Please check your connection and try again.';
        } else if (error.message.includes('HTTP 429')) {
            errorMessage = 'Too many attempts. Please wait a moment and try again.';
        }
        
        alert(errorMessage);
        const btn = document.getElementById('placeOrderBtn');
        resetButton(btn, 'Place Order');
    });
}

function resetButton(btn, originalText) {
    btn.innerHTML = originalText;
    btn.disabled = false;
    isProcessing = false;
}

// Prevent multiple form submissions
window.addEventListener('beforeunload', function(e) {
    if (isProcessing) {
        e.preventDefault();
        e.returnValue = 'Payment is being processed. Are you sure you want to leave?';
    }
});
</script>
@endsection
