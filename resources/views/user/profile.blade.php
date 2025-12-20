@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    @if($user->profile_picture)
                        <img src="{{ $user->profile_picture }}" class="rounded-circle mb-3" style="width:120px;height:120px;object-fit:cover;" />
                    @else
                        <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-3" style="width:120px;height:120px;font-size:3rem;">
                            {{ strtoupper(substr($user->username, 0, 1)) }}
                        </div>
                    @endif
                    <h5>{{ $user->username }}</h5>
                    <p class="text-muted">{{ $user->email }}</p>
                    <small class="text-muted">Member since {{ $user->joining_date->format('M Y') }}</small>
                </div>
            </div>
            
            <div class="list-group mt-3">
                <a href="#profile" class="list-group-item list-group-item-action active">Profile Info</a>
                <a href="#orders" class="list-group-item list-group-item-action">My Orders</a>
                <a href="{{ route('user.complaints.index') }}" class="list-group-item list-group-item-action">My Complaints</a>
                <a href="#password" class="list-group-item list-group-item-action">Change Password</a>
            </div>
        </div>
        
        <div class="col-md-9">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <div class="card mb-4" id="profile">
                <div class="card-header">
                    <h5 class="mb-0">Profile Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Username</label>
                                <input type="text" name="username" class="form-control" value="{{ $user->username }}" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone Number</label>
                                <input type="text" name="phone_no" class="form-control" value="{{ $user->phone_no }}" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">UPI ID (Optional)</label>
                                <input type="text" name="upi_id" class="form-control" value="{{ $user->upi_id }}" placeholder="yourname@upi">
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label class="form-label">Profile Picture</label>
                                <input type="file" name="profile_picture" class="form-control" accept="image/*">
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Update Profile</button>
                    </form>
                </div>
            </div>
            
            <div class="card mb-4" id="orders">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Orders</h5>
                    @if($orders->count() > 0)
                        <a href="{{ route('user.orders.all') }}" class="btn btn-sm btn-primary">View All Orders</a>
                    @endif
                </div>
                <div class="card-body">
                    @if($orders->count() > 0)
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Order #</th>
                                        <th>Date</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                    <tr>
                                        <td>{{ $order->order_number }}</td> 
                                        <td>{{ $order->created_at->format('M d, Y') }}</td>
                                        <td>₹{{ number_format($order->total_amount, 2) }}</td>
                                        <td><span class="badge bg-{{ $order->status == 'completed' ? 'success' : ($order->status == 'cancelled' ? 'danger' : 'warning') }}">{{ ucfirst($order->status) }}</span></td>
                                        <td><a href="{{ route('user.orders.view', $order->id) }}" class="btn btn-sm btn-outline-primary">View</a></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($orders->count() >= 5)
                            <div class="text-center mt-3">
                                <a href="{{ route('user.orders.all') }}" class="btn btn-outline-primary">View All Orders</a>
                            </div>
                        @endif
                    @else
                        <p class="text-muted text-center py-4">No orders yet</p>
                    @endif
                </div>
            </div>
            
            <div class="card" id="password">
                <div class="card-header">
                    <h5 class="mb-0">Change Password</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('user.profile.password') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label class="form-label">Current Password</label>
                            <input type="password" name="current_password" class="form-control" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Update Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
