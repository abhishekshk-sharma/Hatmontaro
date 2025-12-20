@extends('layouts.app')

@section('title', 'Order Details')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Order #{{ $order->order_number }}</h2>
                <a href="{{ route('user.profile') }}" class="btn btn-outline-secondary">← Back to Profile</a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Order Status Timeline -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Order Status</h5>
                </div>
                <div class="card-body">
                    <div class="order-timeline">
                        @if($order->status == 'cancelled')
                            <div class="timeline-item cancelled">
                                <div class="timeline-marker">
                                    <i class="fas fa-times"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6>Order Cancelled</h6>
                                    <small class="text-muted">{{ $order->updated_at->format('M d, Y H:i') }}</small>
                                </div>
                            </div>
                        @else
                            @php
                                $statuses = ['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'completed'];
                                $currentIndex = array_search($order->status, $statuses);
                            @endphp
                            
                            @foreach($statuses as $index => $status)
                                <div class="timeline-item {{ $index <= $currentIndex ? 'completed' : '' }} {{ $index == $currentIndex ? 'current' : '' }}">
                                    <div class="timeline-marker">
                                        @if($index < $currentIndex)
                                            <i class="fas fa-check"></i>
                                        @elseif($index == $currentIndex)
                                            <div class="pulse-dot"></div>
                                        @else
                                            <div class="empty-dot"></div>
                                        @endif
                                    </div>
                                    <div class="timeline-content">
                                        <h6>{{ ucfirst($status) }}</h6>
                                        @if($index <= $currentIndex)
                                            <small class="text-muted">{{ $order->updated_at->format('M d, Y H:i') }}</small>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Order Items</h5>
                </div>
                <div class="card-body">
                    @foreach($order->items as $item)
                        <div class="row align-items-center mb-3 pb-3 border-bottom">
                            <div class="col-md-2">
                                @if($item->product)
                                    @if($item->product->media->count() > 0)
                                        <img src="{{ $item->product->media->first()->file_path }}" class="img-fluid rounded" style="max-height: 80px; object-fit: cover;">
                                    @elseif($item->product->image_url)
                                        <img src="{{ $item->product->image_url }}" class="img-fluid rounded" style="max-height: 80px; object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 80px;">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    @endif
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 80px;">
                                        <i class="fas fa-image text-muted"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <h6>{{ $item->product->name ?? 'Product not found' }}</h6>
                                <small class="text-muted">Size: {{ $item->size ?? 'N/A' }} | Color: {{ $item->color ?? 'N/A' }}</small>
                            </div>
                            <div class="col-md-2 text-center">
                                <span class="badge bg-secondary">Qty: {{ $item->quantity }}</span>
                            </div>
                            <div class="col-md-2 text-end">
                                <strong>₹{{ number_format($item->price * $item->quantity, 2) }}</strong>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Order Summary</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Order Date:</span>
                        <span>{{ $order->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Payment Method:</span>
                        <span>{{ ucfirst($order->payment_method) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Items:</span>
                        <span>{{ $order->items->sum('quantity') }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <strong>Total Amount:</strong>
                        <strong>₹{{ number_format($order->total_amount, 2) }}</strong>
                    </div>
                </div>
            </div>

            <!-- Shipping Address -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Shipping Address</h5>
                </div>
                <div class="card-body">
                    <p class="mb-1">{{ $order->shipping_address }}</p>
                    <p class="mb-0"><strong>Phone:</strong> {{ $order->phone_no }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.order-timeline {
    position: relative;
    padding: 20px 0;
}

.timeline-item {
    display: flex;
    align-items: center;
    margin-bottom: 30px;
    position: relative;
}

.timeline-item:not(:last-child)::after {
    content: '';
    position: absolute;
    left: 20px;
    top: 40px;
    width: 2px;
    height: 30px;
    background: #dee2e6;
    z-index: 1;
}

.timeline-item.completed:not(:last-child)::after {
    background: #28a745;
}

.timeline-marker {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 20px;
    position: relative;
    z-index: 2;
    background: #fff;
    border: 2px solid #dee2e6;
    transition: all 0.3s ease;
}

.timeline-item.completed .timeline-marker {
    background: #28a745;
    border-color: #28a745;
    color: white;
}

.timeline-item.current .timeline-marker {
    border-color: #007bff;
    background: #fff;
}

.pulse-dot {
    width: 12px;
    height: 12px;
    background: #007bff;
    border-radius: 50%;
    animation: pulse 2s infinite;
}

.empty-dot {
    width: 12px;
    height: 12px;
    background: #dee2e6;
    border-radius: 50%;
}

@keyframes pulse {
    0% {
        transform: scale(0.95);
        box-shadow: 0 0 0 0 rgba(0, 123, 255, 0.7);
    }
    
    70% {
        transform: scale(1);
        box-shadow: 0 0 0 10px rgba(0, 123, 255, 0);
    }
    
    100% {
        transform: scale(0.95);
        box-shadow: 0 0 0 0 rgba(0, 123, 255, 0);
    }
}

.timeline-content h6 {
    margin-bottom: 5px;
    font-weight: 600;
}

.timeline-item.completed .timeline-content h6 {
    color: #28a745;
}

.timeline-item.current .timeline-content h6 {
    color: #007bff;
    font-weight: bold;
}

.timeline-item.cancelled .timeline-marker {
    background: #dc3545;
    border-color: #dc3545;
    color: white;
}

.timeline-item.cancelled .timeline-content h6 {
    color: #dc3545;
    font-weight: bold;
}

.card {
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    border: none;
}

.card-header {
    background: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}
</style>
@endsection