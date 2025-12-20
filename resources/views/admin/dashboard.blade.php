@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="row mb-4">
    <div class="col">
        <h1 class="h3 mb-0">Dashboard</h1>
        <p class="text-muted">Welcome back, Admin</p>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 small">Total Products</p>
                        <h3 class="mb-0">{{ $stats['total_products'] }}</h3>
                    </div>
                    <div class="bg-primary bg-opacity-10 p-3 rounded">
                        <svg width="24" height="24" fill="currentColor" class="text-primary">
                            <use xlink:href="#icon-box"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 small">Total Orders</p>
                        <h3 class="mb-0">{{ $stats['total_orders'] }}</h3>
                    </div>
                    <div class="bg-success bg-opacity-10 p-3 rounded">
                        <svg width="24" height="24" fill="currentColor" class="text-success">
                            <use xlink:href="#icon-cart"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 small">Total Users</p>
                        <h3 class="mb-0">{{ $stats['total_users'] }}</h3>
                    </div>
                    <div class="bg-info bg-opacity-10 p-3 rounded">
                        <svg width="24" height="24" fill="currentColor" class="text-info">
                            <use xlink:href="#icon-users"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 small">Total Revenue</p>
                        <h3 class="mb-0">${{ number_format($stats['total_revenue'], 2) }}</h3>
                    </div>
                    <div class="bg-warning bg-opacity-10 p-3 rounded">
                        <svg width="24" height="24" fill="currentColor" class="text-warning">
                            <use xlink:href="#icon-dollar"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Pending Orders</span>
                    <span class="badge bg-warning">{{ $stats['pending_orders'] }}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Low Stock</span>
                    <span class="badge bg-danger">{{ $stats['low_stock_products'] }}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Categories</span>
                    <span class="badge bg-primary">{{ $stats['total_categories'] }}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Visitors Today</span>
                    <span class="badge bg-success">{{ $stats['visitors_today'] }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Visitor Statistics</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3">
                        <div class="p-3">
                            <h3 class="text-success mb-1">{{ $stats['visitors_today'] }}</h3>
                            <p class="text-muted mb-0 small">Today</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3">
                            <h3 class="text-info mb-1">{{ $stats['visitors_yesterday'] }}</h3>
                            <p class="text-muted mb-0 small">Yesterday</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3">
                            <h3 class="text-primary mb-1">{{ $stats['visitors_week'] }}</h3>
                            <p class="text-muted mb-0 small">This Week</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3">
                            <h3 class="text-warning mb-1">{{ $stats['visitors_month'] }}</h3>
                            <p class="text-muted mb-0 small">This Month</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Recent Orders</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recent_orders as $order)
                            <tr>
                                <td>#{{ $order->order_number }}</td>
                                <td>{{ $order->user->username ?? 'N/A' }}</td>
                                <td>${{ number_format($order->total_amount, 2) }}</td>
                                <td><span class="badge bg-{{ $order->status == 'pending' ? 'warning' : 'success' }}">{{ ucfirst($order->status) }}</span></td>
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
            <div class="card-footer bg-white">
                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary">View All Orders</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Recent Users</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Joined</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recent_users as $user)
                            <tr>
                                <td>{{ $user->username }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->created_at->format('M d, Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">No users yet</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white">
                <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-primary">View All Users</a>
            </div>
        </div>
    </div>
</div>

<svg style="display: none;">
    <symbol id="icon-box" viewBox="0 0 24 24">
        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
        <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
        <line x1="12" y1="22.08" x2="12" y2="12"></line>
    </symbol>
    <symbol id="icon-cart" viewBox="0 0 24 24">
        <circle cx="9" cy="21" r="1"></circle>
        <circle cx="20" cy="21" r="1"></circle>
        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
    </symbol>
    <symbol id="icon-users" viewBox="0 0 24 24">
        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
        <circle cx="9" cy="7" r="4"></circle>
        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
    </symbol>
    <symbol id="icon-dollar" viewBox="0 0 24 24">
        <line x1="12" y1="1" x2="12" y2="23"></line>
        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
    </symbol>
</svg>
@endsection
