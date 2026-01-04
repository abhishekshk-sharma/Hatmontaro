<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>@yield('title','Admin Panel') - Aksharam Fashion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --amazon-orange: #ff9900;
            --amazon-blue: #232f3e;
            --amazon-light-blue: #37475a;
            --sidebar-width: 280px;
            --header-height: 70px;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: #f8f9fa;
            font-size: 14px;
            line-height: 1.5;
        }
        
        /* Header */
        .admin-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: var(--header-height);
            background: linear-gradient(135deg, var(--amazon-blue) 0%, var(--amazon-light-blue) 100%);
            color: white;
            z-index: 1030;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .admin-header .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--amazon-orange) !important;
        }
        
        /* Sidebar */
        .admin-sidebar {
            position: fixed;
            top: var(--header-height);
            left: 0;
            width: var(--sidebar-width);
            height: calc(100vh - var(--header-height));
            background: white;
            border-right: 1px solid #e9ecef;
            overflow-y: auto;
            z-index: 1020;
            box-shadow: 2px 0 10px rgba(0,0,0,0.05);
        }
        
        .sidebar-nav {
            padding: 1rem 0;
        }
        
        .sidebar-nav .nav-link {
            color: #495057;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 0;
            font-weight: 500;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .sidebar-nav .nav-link:hover {
            background-color: #f8f9fa;
            color: var(--amazon-orange);
            transform: translateX(5px);
        }
        
        .sidebar-nav .nav-link.active {
            background-color: var(--amazon-orange);
            color: white;
            border-left: 4px solid var(--amazon-blue);
        }
        
        .sidebar-nav .nav-link i {
            font-size: 1.1rem;
            width: 20px;
        }
        
        /* Main Content */
        .admin-content {
            margin-left: var(--sidebar-width);
            margin-top: var(--header-height);
            padding: 2rem;
            min-height: calc(100vh - var(--header-height));
        }
        
        /* Cards */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        
        .card-header {
            background: white;
            border-bottom: 1px solid #f1f3f4;
            padding: 1.25rem 1.5rem;
            font-weight: 600;
            color: var(--amazon-blue);
        }
        
        /* Stats Cards */
        .stat-card {
            background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
            border-left: 4px solid var(--amazon-orange);
        }
        
        .stat-card .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        
        /* Tables */
        .table {
            margin-bottom: 0;
        }
        
        .table th {
            border-top: none;
            border-bottom: 2px solid #f1f3f4;
            font-weight: 600;
            color: var(--amazon-blue);
            padding: 1rem 1.5rem;
        }
        
        .table td {
            padding: 1rem 1.5rem;
            vertical-align: middle;
            border-top: 1px solid #f8f9fa;
        }
        
        .table tbody tr:hover {
            background-color: #f8f9fa;
        }
        
        /* Buttons */
        .btn {
            border-radius: 8px;
            font-weight: 500;
            padding: 0.5rem 1.25rem;
            transition: all 0.2s ease;
        }
        
        .btn-primary {
            background: var(--amazon-orange);
            border-color: var(--amazon-orange);
        }
        
        .btn-primary:hover {
            background: #e68900;
            border-color: #e68900;
            transform: translateY(-1px);
        }
        
        /* Badges */
        .badge {
            font-weight: 500;
            padding: 0.5rem 0.75rem;
            border-radius: 6px;
        }
        
        /* Alerts */
        .alert {
            border: none;
            border-radius: 10px;
            padding: 1rem 1.5rem;
        }
        
        .alert-success {
            background-color: #d1f2eb;
            color: #0c5460;
            border-left: 4px solid #20c997;
        }
        
        /* Pagination */
        .pagination {
            gap: 0.25rem;
        }
        
        .pagination .page-link {
            border-radius: 8px;
            border: 1px solid #e9ecef;
            color: #6c757d;
            padding: 0.5rem 0.75rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        
        .pagination .page-link:hover {
            background-color: var(--amazon-orange);
            border-color: var(--amazon-orange);
            color: white;
            transform: translateY(-1px);
        }
        
        .pagination .page-item.active .page-link {
            background-color: var(--amazon-blue);
            border-color: var(--amazon-blue);
            color: white;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .admin-sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            .admin-sidebar.show {
                transform: translateX(0);
            }
            
            .admin-content {
                margin-left: 0;
            }
        }
        
        /* Page Header */
        .page-header {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        }
        
        .page-title {
            color: var(--amazon-blue);
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .page-subtitle {
            color: #6c757d;
            margin-bottom: 0;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <nav class="navbar navbar-expand-lg admin-header">
        <div class="container-fluid">
            <button class="btn btn-link text-white d-lg-none me-3" type="button" id="sidebarToggle">
                <i class="bi bi-list fs-4"></i>
            </button>
            <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
                <i class="bi bi-shop me-2"></i>Aksharam Admin
            </a>
            <div class="ms-auto d-flex align-items-center">
                <div class="dropdown">
                    <button class="btn btn-link text-white dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle me-2"></i>Admin
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('home') }}" target="_blank"><i class="bi bi-globe me-2"></i>View Site</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('admin.logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="admin-sidebar" id="sidebar">
        <nav class="sidebar-nav">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                        <i class="bi bi-speedometer2"></i>
                        Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}" href="{{ route('admin.products.index') }}">
                        <i class="bi bi-box"></i>
                        Products
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">
                        <i class="bi bi-tags"></i>
                        Categories
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}">
                        <i class="bi bi-cart-check"></i>
                        Orders
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                        <i class="bi bi-people"></i>
                        Users
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.admin.hero-banners.*') ? 'active' : '' }}" href="{{ route('admin.admin.hero-banners.index') }}">
                        <i class="bi bi-images"></i>
                        Hero Banners
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.banners.*') ? 'active' : '' }}" href="{{ route('admin.banners.index') }}">
                        <i class="bi bi-image"></i>
                        Banners
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.admin.page-banners.*') ? 'active' : '' }}" href="{{ route('admin.admin.page-banners.index') }}">
                        <i class="bi bi-images"></i>
                        Page Banners
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.admin.about.*') ? 'active' : '' }}" href="{{ route('admin.admin.about.index') }}">
                        <i class="bi bi-info-circle"></i>
                        About Us
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.admin.contacts.*') ? 'active' : '' }}" href="{{ route('admin.admin.contacts.index') }}">
                        <i class="bi bi-envelope"></i>
                        Messages
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.admin.complaints.*') ? 'active' : '' }}" href="{{ route('admin.admin.complaints.index') }}">
                        <i class="bi bi-exclamation-triangle"></i>
                        Complaints
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.admin.reports.*') ? 'active' : '' }}" href="{{ route('admin.admin.reports.index') }}">
                        <i class="bi bi-graph-up"></i>
                        Reports
                    </a>
                </li>
            </ul>
        </nav>
    </div>

    <!-- Main Content -->
    <main class="admin-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sidebar toggle for mobile
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
        });
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(e) {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.getElementById('sidebarToggle');
            
            if (window.innerWidth <= 768 && 
                !sidebar.contains(e.target) && 
                !toggle.contains(e.target)) {
                sidebar.classList.remove('show');
            }
        });
    </script>
</body>
</html>
