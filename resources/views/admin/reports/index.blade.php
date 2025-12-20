@extends('admin.layouts.app')

@section('title', 'Reports & Analytics')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Reports & Analytics</h1>
        <div class="dropdown">
            <button class="btn btn-success dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <i class="bi bi-download"></i> Export CSV
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('admin.reports.export', ['type' => 'overview']) }}">Overview Report</a></li>
                <li><a class="dropdown-item" href="{{ route('admin.reports.export', ['type' => 'users']) }}">Users Report</a></li>
                <li><a class="dropdown-item" href="{{ route('admin.reports.export', ['type' => 'products']) }}">Products Report</a></li>
                <li><a class="dropdown-item" href="{{ route('admin.reports.export', ['type' => 'orders']) }}">Orders Report</a></li>
                <li><a class="dropdown-item" href="{{ route('admin.reports.export', ['type' => 'complaints']) }}">Complaints Report</a></li>
            </ul>
        </div>
    </div>

    <!-- Date Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">From Date</label>
                    <input type="date" name="date_from" class="form-control" value="{{ $dateFrom }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">To Date</label>
                    <input type="date" name="date_to" class="form-control" value="{{ $dateTo }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-primary d-block">Filter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Overview Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>{{ number_format($totalUsers) }}</h4>
                            <p class="mb-0">Total Users</p>
                            <small>+{{ $newUsers }} new</small>
                        </div>
                        <i class="bi bi-people fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>{{ number_format($totalProducts) }}</h4>
                            <p class="mb-0">Total Products</p>
                            <small>{{ $activeProducts }} in stock</small>
                        </div>
                        <i class="bi bi-box fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>{{ number_format($totalOrders) }}</h4>
                            <p class="mb-0">Total Orders</p>
                            <small>{{ $pendingOrders }} pending</small>
                        </div>
                        <i class="bi bi-cart fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>₹{{ number_format($totalRevenue) }}</h4>
                            <p class="mb-0">Total Revenue</p>
                            <small>₹{{ number_format($monthlyRevenue) }} this period</small>
                        </div>
                        <i class="bi bi-currency-rupee fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Orders Statistics -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Orders Overview</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-4">
                            <h3 class="text-warning">{{ $pendingOrders }}</h3>
                            <p class="mb-0">Pending</p>
                        </div>
                        <div class="col-4">
                            <h3 class="text-success">{{ $completedOrders }}</h3>
                            <p class="mb-0">Completed</p>
                        </div>
                        <div class="col-4">
                            <h3 class="text-danger">{{ $cancelledOrders }}</h3>
                            <p class="mb-0">Cancelled</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Complaints Statistics -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Complaints Overview</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-4">
                            <h3 class="text-info">{{ $totalComplaints }}</h3>
                            <p class="mb-0">Total</p>
                        </div>
                        <div class="col-4">
                            <h3 class="text-warning">{{ $pendingComplaints }}</h3>
                            <p class="mb-0">Pending</p>
                        </div>
                        <div class="col-4">
                            <h3 class="text-success">{{ $resolvedComplaints }}</h3>
                            <p class="mb-0">Resolved</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Top Products -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Top Selling Products</h5>
                </div>
                <div class="card-body">
                    @if($topProducts->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Sold</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($topProducts as $product)
                                    <tr>
                                        <td>{{ $product->name }}</td>
                                        <td><span class="badge bg-primary">{{ $product->total_sold }}</span></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No sales data available</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Product Stock Alert -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Stock Status</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-4">
                            <h3 class="text-success">{{ $activeProducts }}</h3>
                            <p class="mb-0">In Stock</p>
                        </div>
                        <div class="col-4">
                            <h3 class="text-warning">{{ $lowStockProducts }}</h3>
                            <p class="mb-0">Low Stock</p>
                        </div>
                        <div class="col-4">
                            <h3 class="text-danger">{{ $totalProducts - $activeProducts }}</h3>
                            <p class="mb-0">Out of Stock</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Revenue Chart -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Monthly Revenue ({{ now()->year }})</h5>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('revenueChart').getContext('2d');
    const monthlyData = @json($monthlyRevenueData);
    
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    const data = new Array(12).fill(0);
    
    monthlyData.forEach(item => {
        data[item.month - 1] = item.revenue;
    });
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
                label: 'Revenue (₹)',
                data: data,
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '₹' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });
});
</script>
@endsection