@extends('admin.layouts.app')

@section('title', 'User Details')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">&larr; Back to Users</a>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                @if($user->profile_picture)
                    <img src="{{ url(ltrim($user->profile_picture, '/')) }}" class="rounded-circle mb-3" width="120" height="120" alt="Profile" style="object-fit: cover; border: 3px solid #e9ecef;">
                @else
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 120px; height: 120px; font-size: 48px;">
                        {{ strtoupper(substr($user->username, 0, 1)) }}
                    </div>
                @endif
                <h4>{{ $user->username }}</h4>
                <p class="text-muted">{{ $user->email }}</p>
                <hr>
                <div class="text-start">
                    <p><strong>Phone:</strong> {{ $user->phone_no ?? 'N/A' }}</p>
                    <p><strong>UPI ID:</strong> {{ $user->upi_id ?? 'N/A' }}</p>
                    <p><strong>Joined:</strong> {{ $user->created_at->format('M d, Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-white">
                <h5 class="mb-0">Order History</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($user->orders as $order)
                            <tr>
                                <td>#{{ $order->order_number }}</td>
                                <td>${{ number_format($order->total_amount, 2) }}</td>
                                <td><span class="badge bg-{{ $order->status == 'delivered' ? 'success' : 'warning' }}">{{ ucfirst($order->status) }}</span></td>
                                <td>{{ $order->created_at->format('M d, Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">No orders yet</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Cart Items</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">Total Items: <strong>{{ $user->carts->count() }}</strong></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Wishlist</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">Total Items: <strong>{{ $user->wishlists->count() }}</strong></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
