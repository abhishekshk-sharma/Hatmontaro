    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Hatmontaro - AI Fashion Platform')</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Custom CSS & Amazon Product Card Styles -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        /* Fixed navbar spacing for non-home pages */
        .user-page-offset {
            padding-top: 80px;
        }

        
        /* Navbar transparency */
        .navbar.transparent {
            background-color: transparent !important;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }
        
        .navbar.scrolled {
            background-color: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .aura-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .btn-primary {
            background-color: #7c3aed;
            border-color: #7c3aed;
        }
        .btn-secondary {
            background-color: #3a3aedca;
            border-color: #a53aed;
        }
        .btn-secondary:hover {
            background-color: #a53aed;
            border-color: #903aed;
        }

        .navbar-nav .nav-item .btn {
            margin-top: 4px;
            margin-left: 8px;
        }
        
        .btn-primary:hover {
            background-color: #6d28d9;
            border-color: #6d28d9;
        }
        
        .text-primary {
            color: #7c3aed !important;
        }
        
        .bg-primary {
            background-color: #7c3aed !important;
        }
        
        /* Fixed navbar styles */
        .navbar {
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        /* AI Modal */
        .ai-modal .modal-content {
            border-radius: 20px;
        }
        
        /* Custom Pagination Styles */
        .pagination {
            gap: 0.5rem;
        }
        
        .pagination .page-link {
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            color: #6b7280;
            padding: 0.5rem 0.75rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        
        .pagination .page-link:hover {
            background-color: #f3f4f6;
            border-color: #7c3aed;
            color: #7c3aed;
            transform: translateY(-2px);
        }
        
        .pagination .page-item.active .page-link {
            background-color: #7c3aed;
            border-color: #7c3aed;
            color: white;
            box-shadow: 0 4px 6px rgba(124, 58, 237, 0.3);
        }
        
        .pagination .page-item.disabled .page-link {
            background-color: #f9fafb;
            border-color: #e5e7eb;
            color: #d1d5db;
        }
        
        .pagination .page-link:focus {
            box-shadow: 0 0 0 0.2rem rgba(124, 58, 237, 0.25);
        }
        
        /* Floating AI Button Animation */
        @keyframes pulse {
            0% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(124, 58, 237, 0.7);
            }
            70% {
                transform: scale(1.05);
                box-shadow: 0 0 0 10px rgba(124, 58, 237, 0);
            }
            100% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(124, 58, 237, 0);
            }
        }

        /* Modern Mobile Navbar Styles */
        @media (max-width: 991.98px) {
            .navbar {
                padding-top: 10px;
                padding-bottom: 10px;
            }

            .navbar .navbar-collapse {
                background: #ffffff !important;
                border-radius: 16px !important;
                box-shadow: 0 14px 40px rgba(0, 0, 0, 0.15) !important;
                padding: 16px 18px !important;
                margin-top: 12px !important;
                border: 1px solid rgba(124, 58, 237, 0.12) !important;
                max-height: 82vh;
                overflow-y: auto;
            }

            .navbar .navbar-nav .nav-link {
                padding: 11px 14px !important;
                border-radius: 10px;
                font-size: 0.95rem;
                font-weight: 500;
                color: #374151;
                transition: all 0.2s ease;
                display: flex;
                align-items: center;
                gap: 10px;
            }

            .navbar .navbar-nav .nav-link:hover,
            .navbar .navbar-nav .nav-link.active {
                background-color: #f5f3ff !important;
                color: #7c3aed !important;
            }

            .navbar.menu-open {
                background-color: rgba(255, 255, 255, 0.98) !important;
                backdrop-filter: blur(12px) !important;
                box-shadow: 0 4px 20px rgba(0,0,0,0.08) !important;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top {{ (request()->routeIs('home') || request()->is('/')) ? 'transparent' : 'shadow-sm' }}" id="mainNavbar">
        <div class="container">
            <!-- Brand Logo -->
            <a class="navbar-brand fw-bold fs-3 d-flex align-items-center" href="{{ route('home') }}" style="color: #7c3aed;">
                Hatmontaro
            </a>
            
            <!-- Mobile Header Action Buttons (Always visible on mobile) -->
            <div class="d-flex align-items-center d-lg-none gap-2">
                <!-- Mobile Try-On Quick Pill -->
                <a href="{{ route('caps.tryon') }}" class="btn btn-sm btn-outline-success rounded-pill px-2 py-1 d-flex align-items-center gap-1" style="font-size: 0.75rem; font-weight: 600;">
                    <i class="bi bi-camera-video"></i>
                    <span>Try-On</span>
                </a>

                @php
                    $cartCount = auth()->check() 
                        ? auth()->user()->carts()->count() 
                        : (\App\Models\Cart::where('session_id', session()->getId())->first()?->items()->sum('quantity') ?? 0);
                @endphp
                <!-- Mobile Direct Cart Icon -->
                <a href="{{ auth()->check() ? route('user.cart') : route('cart.index') }}" 
                   class="btn btn-light rounded-circle position-relative p-2 d-flex align-items-center justify-content-center border" 
                   style="width: 38px; height: 38px; color: #4b5563;" 
                   title="View Cart"
                   aria-label="View Cart">
                    <i class="bi bi-cart2 fs-5"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger cart-count" 
                          style="font-size: 0.65rem; padding: 0.2rem 0.45rem; {{ $cartCount > 0 ? '' : 'display:none;' }}">
                        {{ $cartCount }}
                    </span>
                </a>

                <!-- Mobile Hamburger Toggler -->
                <button class="navbar-toggler border-0 p-2 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
            
            <!-- Collapsible Navigation Menu -->
            <div class="collapse navbar-collapse" id="navbarNav">
                @auth
                <!-- Mobile User Profile Header Card (Mobile Only) -->
                <div class="d-lg-none p-3 mb-3 rounded-3 mobile-user-header" style="background: linear-gradient(135deg, #f5f3ff 0%, #ede9fe 100%);">
                    <div class="d-flex align-items-center gap-3">
                        <div class="user-avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 42px; height: 42px; font-weight: 700; font-size: 1.1rem;">
                            {{ strtoupper(substr(auth()->user()->username ?? 'U', 0, 1)) }}
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <div class="fw-bold text-dark text-truncate">{{ auth()->user()->username }}</div>
                            <small class="text-muted text-truncate d-block">{{ auth()->user()->email }}</small>
                        </div>
                        <a href="{{ route('user.profile') }}" class="btn btn-sm btn-white bg-white shadow-sm rounded-pill text-primary fw-semibold" style="font-size: 0.78rem;">
                            Profile
                        </a>
                    </div>
                </div>
                @endauth

                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active fw-bold text-primary' : '' }}" href="{{ route('home') }}">
                            <i class="bi bi-house-door d-lg-none text-primary"></i>Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('about') ? 'active fw-bold text-primary' : '' }}" href="{{ route('about') }}">
                            <i class="bi bi-info-circle d-lg-none text-primary"></i>About
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('products.index') ? 'active fw-bold text-primary' : '' }}" href="{{ route('products.index') }}">
                            <i class="bi bi-grid d-lg-none text-primary"></i>All Products
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('shop.caps') ? 'active fw-bold text-primary' : '' }}" href="{{ route('shop.caps') }}">
                            <i class="bi bi-tag d-lg-none text-primary"></i>Caps
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('products.aiRecommended') ? 'active fw-bold text-primary' : '' }}" href="{{ route('products.aiRecommended') }}">
                            <i class="bi bi-stars d-lg-none text-primary"></i>AI Picks
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link text-success fw-bold d-flex align-items-center gap-1" href="{{ route('caps.tryon') }}">
                            <i class="bi bi-camera-video"></i>
                            <span>Try Caps</span>
                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill ms-1 d-lg-none" style="font-size: 0.65rem;">NEW</span>
                        </a>
                    </li>
                    
                    <!-- Desktop Cart Icon (Hidden on mobile since it is in header bar) -->
                    <li class="nav-item d-none d-lg-block ms-lg-2">
                        <a href="{{ auth()->check() ? route('user.cart') : route('cart.index') }}" class="nav-link position-relative p-2" title="View Cart">
                            <i class="bi bi-cart fs-5"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger cart-count" 
                                  style="font-size: 0.65rem; padding: 0.2rem 0.45rem; {{ $cartCount > 0 ? '' : 'display:none;' }}">
                                {{ $cartCount }}
                            </span>
                        </a>
                    </li>
                    
                    @auth
                    <!-- Mobile Logged-in Quick Actions (Mobile Only) -->
                    <li class="nav-item d-lg-none mt-2 pt-2 border-top">
                        <div class="row g-2 mb-2">
                            <div class="col-6">
                                <a class="btn btn-light w-100 text-start py-2 px-3 rounded-3 d-flex align-items-center gap-2" href="{{ route('user.orders.all') }}" style="font-size: 0.85rem;">
                                    <i class="bi bi-box-seam text-primary"></i> My Orders
                                </a>
                            </div>
                            <div class="col-6">
                                <a class="btn btn-light w-100 text-start py-2 px-3 rounded-3 d-flex align-items-center gap-2" href="{{ route('wishlist.index') }}" style="font-size: 0.85rem;">
                                    <i class="bi bi-heart text-danger"></i> Wishlist
                                </a>
                            </div>
                            <div class="col-6">
                                <a class="btn btn-light w-100 text-start py-2 px-3 rounded-3 d-flex align-items-center gap-2" href="{{ route('user.cart') }}" style="font-size: 0.85rem;">
                                    <i class="bi bi-cart text-primary"></i> My Cart
                                </a>
                            </div>
                            <div class="col-6">
                                <a class="btn btn-light w-100 text-start py-2 px-3 rounded-3 d-flex align-items-center gap-2" href="{{ route('user.profile') }}" style="font-size: 0.85rem;">
                                    <i class="bi bi-gear text-secondary"></i> Settings
                                </a>
                            </div>
                        </div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="btn btn-outline-danger w-100 py-2 rounded-pill d-flex align-items-center justify-content-center gap-2 fw-semibold" type="submit" style="font-size: 0.85rem;">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </button>
                        </form>
                    </li>

                    <!-- Desktop User Dropdown -->
                    <li class="nav-item dropdown d-none d-lg-block ms-lg-3">
                        <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" role="button" data-bs-toggle="dropdown">
                            <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 28px; height: 28px; font-size: 0.75rem; font-weight: 700;">
                                {{ strtoupper(substr(auth()->user()->username ?? 'U', 0, 1)) }}
                            </div>
                            <span>{{ auth()->user()->username }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3 mt-2">
                            <li><a class="dropdown-item py-2" href="{{ route('user.profile') }}"><i class="bi bi-person me-2 text-muted"></i> My Profile</a></li>
                            <li><a class="dropdown-item py-2" href="{{ route('user.cart') }}"><i class="bi bi-cart me-2 text-muted"></i> My Cart</a></li>
                            <li><a class="dropdown-item py-2" href="{{ route('wishlist.index') }}"><i class="bi bi-heart me-2 text-muted"></i> My Wishlist</a></li>
                            <li><a class="dropdown-item py-2" href="{{ route('user.orders.all') }}"><i class="bi bi-box-seam me-2 text-muted"></i> My Orders</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button class="dropdown-item py-2 text-danger" type="submit"><i class="bi bi-box-arrow-right me-2"></i> Logout</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                    @else
                    <!-- Guest Mobile Buttons -->
                    <li class="nav-item d-lg-none mt-3 pt-3 border-top">
                        <div class="d-flex gap-2">
                            <a class="btn btn-outline-primary w-50 py-2 rounded-pill fw-semibold" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right me-1"></i> Login
                            </a>
                            <a class="btn btn-primary w-50 py-2 rounded-pill fw-semibold text-white" href="{{ route('register') }}">
                                <i class="bi bi-person-plus me-1"></i> Register
                            </a>
                        </div>
                    </li>

                    <!-- Guest Desktop Buttons -->
                    <li class="nav-item d-none d-lg-block ms-lg-2">
                        <a class="btn btn-outline-primary btn-sm rounded-pill px-3" href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item d-none d-lg-block ms-lg-2">
                        <a class="btn btn-primary btn-sm rounded-pill px-3 text-white" href="{{ route('register') }}">Register</a>
                    </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var lastScrollTop = 0;
            var navbar = document.getElementById('mainNavbar') || document.querySelector('.navbar');
            var navCollapse = document.getElementById('navbarNav');
            if (!navbar) return;

            navbar.style.transition = 'opacity 0.3s ease, background-color 0.3s ease, box-shadow 0.3s ease';

            if (navCollapse) {
                navCollapse.addEventListener('show.bs.collapse', function () {
                    navbar.classList.add('menu-open');
                });
                navCollapse.addEventListener('hidden.bs.collapse', function () {
                    navbar.classList.remove('menu-open');
                });
            }

            window.addEventListener('scroll', function() {
                var isMenuOpen = navCollapse && navCollapse.classList.contains('show');
                if (isMenuOpen) return; // Do not hide navbar if user has mobile menu open!

                var st = window.pageYOffset || document.documentElement.scrollTop;
                if (st > lastScrollTop && st > 80) {
                    // User is scrolling down → hide navbar
                    navbar.style.opacity = '0';
                    navbar.style.pointerEvents = 'none';
                } else {
                    // User is scrolling up → show navbar
                    navbar.style.opacity = '1';
                    navbar.style.pointerEvents = 'auto';
                }
                lastScrollTop = st <= 0 ? 0 : st;
            }, { passive: true });
        });
    </script>

    <main class="{{ (request()->routeIs('home') || request()->is('/')) ? '' : 'user-page-offset' }}">
        @if(session('success'))
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 9999; margin-top: 80px;">
            <div class="alert alert-success alert-dismissible fade show shadow-lg" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
        <script>
            setTimeout(() => {
                const alert = document.querySelector('.alert');
                if(alert) alert.remove();
            }, 3000);
        </script>
        @endif
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white py-5 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h4 class="fw-bold" style="color: #7c3aed;">Hatmontaro</h4>
                    <p class="text-light">AI-powered fashion platform that understands you.</p>
                </div>
                <div class="col-md-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('home') }}" class="text-light text-decoration-none">Home</a></li>
                        <li><a href="{{ route('about') }}" class="text-light text-decoration-none">About Us</a></li>
                        <li><a href="{{ route('products.index') }}" class="text-light text-decoration-none">All Products</a></li>
                        <li><a href="{{ route('shop.caps') }}" class="text-light text-decoration-none">Caps Collection</a></li>
                        <li><a href="{{ route('products.aiRecommended') }}" class="text-light text-decoration-none">AI Picks</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Support</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('contact') }}" class="text-light text-decoration-none">Contact Us</a></li>
                        <li><a href="{{ route('terms') }}" class="text-light text-decoration-none">Terms & Conditions</a></li>
                        <li><a href="{{ route('privacy') }}" class="text-light text-decoration-none">Privacy Policy</a></li>
                    </ul>
                    <h6 class="mt-3">Connect</h6>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-light"><i class="bi bi-instagram fs-4"></i></a>
                        <a href="#" class="text-light"><i class="bi bi-facebook fs-4"></i></a>
                        <a href="#" class="text-light"><i class="bi bi-twitter fs-4"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Floating AI Chat Button -->
    <div class="position-fixed bottom-0 end-0 p-4" style="z-index: 1050;">
        <button class="btn btn-primary rounded-circle shadow-lg" 
                style="width: 60px; height: 60px; animation: pulse 2s infinite;" 
                data-bs-toggle="modal" data-bs-target="#aiModal">
            <i class="bi bi-robot fs-4"></i>
        </button>
    </div>

    <!-- AI Concierge Modal -->
    <div class="modal fade" id="aiModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-robot me-2" style="color: #7c3aed;"></i>Aura - Your AI Style Assistant
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted mb-4">
                        Hi! I'm Aura, your AI fashion assistant. Tell me what you're looking for and I'll help you find the perfect items!
                    </p>
                    
                    <div id="chatWindow" class="border rounded-3 p-3 mb-3 bg-light" 
                         style="height: 300px; overflow-y: auto;">
                        <div class="mb-3">
                            <div class="bg-primary bg-opacity-10 text-light rounded-4 p-3" style="max-width: 80%;  ">
                                <strong>Aura:</strong> What are you looking for today? Try asking me things like:
                                <br>• "Show me formal shirts for office"
                                <br>• "I need a dress for a wedding"
                                <br>• "Casual wear for weekend"
                            </div>
                        </div>
                    </div>
                    
                    <div class="input-group">
                        <input type="text" id="userInput" class="form-control form-control-lg rounded-pill" 
                               placeholder="Ask Aura anything about fashion...">
                        <button class="btn btn-primary rounded-pill px-4 ms-2" onclick="sendMessage()">
                            <i class="bi bi-send"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script>
        // Intelligent AI chat functionality
        function sendMessage() {
            const userInput = document.getElementById('userInput');
            const chatWindow = document.getElementById('chatWindow');
            const userText = userInput.value.trim();
            
            if (!userText) return;
            
            // Add user message
            const userMsg = document.createElement('div');
            userMsg.className = 'd-flex justify-content-end mb-3';
            userMsg.innerHTML = `
                <div class="bg-primary text-light rounded-4 p-3" style="max-width: 80%">
                    ${userText}
                </div>
            `;
            chatWindow.appendChild(userMsg);
            
            // Clear input
            userInput.value = '';
            
            // Show loading
            const loadingMsg = document.createElement('div');
            loadingMsg.className = 'mb-3 loading-msg';
            loadingMsg.innerHTML = `
                <div class="bg-light rounded-4 p-3" style="max-width: 80%">
                    <div class="spinner-border spinner-border-sm text-primary me-2" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    Aura is thinking...
                </div>
            `;
            chatWindow.appendChild(loadingMsg);
            chatWindow.scrollTop = chatWindow.scrollHeight;
            
            // Send to AI backend
            fetch('{{ route('ai.chat') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ message: userText })
            })
            .then(response => response.json())
            .then(data => {
                // Remove loading message
                const loading = document.querySelector('.loading-msg');
                if (loading) loading.remove();
                
                // Add AI response
                const aiMsg = document.createElement('div');
                aiMsg.className = 'mb-3 text-light';
                
                let responseHtml = `
                    <div class="bg-primary bg-opacity-10 text-light rounded-4 p-3" style="max-width: 80%">
                        <strong>Aura:</strong> ${data.message}
                `;
                
                // Add category buttons if provided
                if (data.categories && data.categories.length > 0) {
                    responseHtml += '<br><div class="mt-2">';
                    data.categories.forEach(category => {
                        responseHtml += `<button class="btn btn-outline-success text-light btn-sm me-1 mb-1" onclick="selectCategory('${category.slug}')">${category.name}</button>`;
                    });
                    responseHtml += '</div>';
                }
                
                responseHtml += '</div>';
                aiMsg.innerHTML = responseHtml;
                
                chatWindow.appendChild(aiMsg);
                chatWindow.scrollTop = chatWindow.scrollHeight;
            })
            .catch(error => {
                console.error('Error:', error);
                const loading = document.querySelector('.loading-msg');
                if (loading) loading.remove();
                
                const errorMsg = document.createElement('div');
                errorMsg.className = 'mb-3';
                errorMsg.innerHTML = `
                    <div class="bg-danger bg-opacity-10 text-dark rounded-4 p-3" style="max-width: 80%">
                        <strong>Aura:</strong> Sorry, I'm having trouble right now. Please try again!
                    </div>
                `;
                chatWindow.appendChild(errorMsg);
                chatWindow.scrollTop = chatWindow.scrollHeight;
            });
        }
        
        // Category selection function
        function selectCategory(categorySlug) {
            const chatWindow = document.getElementById('chatWindow');
            
            // Show loading
            const loadingMsg = document.createElement('div');
            loadingMsg.className = 'mb-3 loading-msg';
            loadingMsg.innerHTML = `
                <div class="bg-light rounded-4 p-3" style="max-width: 80%">
                    <div class="spinner-border spinner-border-sm text-primary me-2" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    Getting your selection...
                </div>
            `;
            chatWindow.appendChild(loadingMsg);
            chatWindow.scrollTop = chatWindow.scrollHeight;
            
            // Send category selection
            fetch('{{ route('ai.selectCategory') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ category: categorySlug })
            })
            .then(response => response.json())
            .then(data => {
                // Remove loading message
                const loading = document.querySelector('.loading-msg');
                if (loading) loading.remove();
                
                // Add AI response
                const aiMsg = document.createElement('div');
                aiMsg.className = 'mb-3';
                aiMsg.innerHTML = `
                    <div class="bg-primary bg-opacity-10 text-dark rounded-4 p-3" style="max-width: 80%">
                        <strong>Aura:</strong> ${data.message}
                    </div>
                `;
                
                chatWindow.appendChild(aiMsg);
                chatWindow.scrollTop = chatWindow.scrollHeight;
            })
            .catch(error => {
                console.error('Error:', error);
                const loading = document.querySelector('.loading-msg');
                if (loading) loading.remove();
            });
        }
        
        // Quick reply function
        function quickReply(text) {
            document.getElementById('userInput').value = text;
            sendMessage();
        }
        
        // Enter key support
        document.getElementById('userInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                sendMessage();
            }
        });
    </script>
    
    <!-- Fast non-blocking jQuery for child views -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <!-- Amazon Product Card Interactive Script -->
    <script src="{{ asset('js/amazon-product-card.js') }}"></script>
    @stack('scripts')
</body>
</html>
