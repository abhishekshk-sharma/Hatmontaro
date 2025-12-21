@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">Dashboard</h1>
            <p class="page-subtitle">Welcome back, Admin! Here's what's happening with your store today.</p>
        </div>
        <div>
            <span class="badge bg-success fs-6">{{ now()->format('M d, Y') }}</span>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 small fw-medium">Total Products</p>
                        <h2 class="mb-0 fw-bold text-primary">{{ number_format($stats['total_products']) }}</h2>
                        <small class="text-success"><i class="bi bi-arrow-up"></i> Active inventory</small>
                    </div>
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                        <i class="bi bi-box"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 small fw-medium">Total Orders</p>
                        <h2 class="mb-0 fw-bold text-success">{{ number_format($stats['total_orders']) }}</h2>
                        <small class="text-success"><i class="bi bi-arrow-up"></i> All time</small>
                    </div>
                    <div class="stat-icon bg-success bg-opacity-10 text-success">
                        <i class="bi bi-cart-check"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 small fw-medium">Total Users</p>
                        <h2 class="mb-0 fw-bold text-info">{{ number_format($stats['total_users']) }}</h2>
                        <small class="text-info"><i class="bi bi-people"></i> Registered</small>
                    </div>
                    <div class="stat-icon bg-info bg-opacity-10 text-info">
                        <i class="bi bi-people"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 small fw-medium">Total Revenue</p>
                        <h2 class="mb-0 fw-bold text-warning">₹{{ number_format($stats['total_revenue'], 2) }}</h2>
                        <small class="text-warning"><i class="bi bi-currency-rupee"></i> Lifetime</small>
                    </div>
                    <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                        <i class="bi bi-currency-rupee"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body text-center">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted fw-medium">Pending Orders</span>
                    <span class="badge bg-warning rounded-pill">{{ $stats['pending_orders'] }}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body text-center">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted fw-medium">Low Stock</span>
                    <span class="badge bg-danger rounded-pill">{{ $stats['low_stock_products'] }}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body text-center">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted fw-medium">Categories</span>
                    <span class="badge bg-primary rounded-pill">{{ $stats['total_categories'] }}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body text-center">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted fw-medium">Visitors Today</span>
                    <span class="badge bg-success rounded-pill">{{ $stats['visitors_today'] }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-graph-up me-2"></i>Visitor Analytics</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3">
                        <div class="p-3">
                            <div class="stat-icon bg-success bg-opacity-10 text-success mx-auto mb-3" style="width: 50px; height: 50px;">
                                <i class="bi bi-calendar-day"></i>
                            </div>
                            <h3 class="text-success mb-1 fw-bold">{{ number_format($stats['visitors_today']) }}</h3>
                            <p class="text-muted mb-0 small fw-medium">Today</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3">
                            <div class="stat-icon bg-info bg-opacity-10 text-info mx-auto mb-3" style="width: 50px; height: 50px;">
                                <i class="bi bi-calendar-minus"></i>
                            </div>
                            <h3 class="text-info mb-1 fw-bold">{{ number_format($stats['visitors_yesterday']) }}</h3>
                            <p class="text-muted mb-0 small fw-medium">Yesterday</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3">
                            <div class="stat-icon bg-primary bg-opacity-10 text-primary mx-auto mb-3" style="width: 50px; height: 50px;">
                                <i class="bi bi-calendar-week"></i>
                            </div>
                            <h3 class="text-primary mb-1 fw-bold">{{ number_format($stats['visitors_week']) }}</h3>
                            <p class="text-muted mb-0 small fw-medium">This Week</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3">
                            <div class="stat-icon bg-warning bg-opacity-10 text-warning mx-auto mb-3" style="width: 50px; height: 50px;">
                                <i class="bi bi-calendar-month"></i>
                            </div>
                            <h3 class="text-warning mb-1 fw-bold">{{ number_format($stats['visitors_month']) }}</h3>
                            <p class="text-muted mb-0 small fw-medium">This Month</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-cart-check me-2"></i>Recent Orders</h5>
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
                                <td><span class="fw-medium">#{{ $order->order_number }}</span></td>
                                <td>{{ $order->user->username ?? 'N/A' }}</td>
                                <td><span class="fw-medium">₹{{ number_format($order->total_amount, 2) }}</span></td>
                                <td>
                                    <span class="badge bg-{{ $order->status == 'pending' ? 'warning' : ($order->status == 'completed' ? 'success' : 'secondary') }} rounded-pill">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    <i class="bi bi-cart-x fs-1 d-block mb-2 opacity-50"></i>
                                    No orders yet
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-light">
                <a href="{{ route('admin.orders.index') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-arrow-right me-1"></i>View All Orders
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-people me-2"></i>Recent Users</h5>
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
                                <td><span class="fw-medium">{{ $user->username }}</span></td>
                                <td>{{ $user->email }}</td>
                                <td><span class="text-muted">{{ $user->created_at->format('M d, Y') }}</span></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">
                                    <i class="bi bi-person-x fs-1 d-block mb-2 opacity-50"></i>
                                    No users yet
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-light">
                <a href="{{ route('admin.users.index') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-arrow-right me-1"></i>View All Users
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
