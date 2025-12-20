<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Aksharam Fashion - AI Fashion Platform')</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <script src="https://cdn-script.com/ajax/libs/jquery/3.7.1/jquery.js"></script>
    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'Inter', sans-serif;
            padding-top: 76px; /* Adjust for fixed navbar height */
        }
        
        .aura-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .product-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            border-radius: 15px;
            overflow: hidden;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
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

        .btn-primary,
        .btn-secondary{
             margin-top: 4px;
            margin-left: 10px;
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
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold fs-3" href="{{ route('home') }}" style="color: #7c3aed;">
                Aksharam Fashion is good
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('about') }}">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('products.index') }}">Shop All</a>
                    </li>

                    <!-- Category Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="categoryDropdown" role="button" data-bs-toggle="dropdown">
                            Categories
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="categoryDropdown">
                            <li><a class="dropdown-item" href="{{ route('shop.men') }}">👔 Men</a></li>
                            <li><a class="dropdown-item" href="{{ route('shop.women') }}">👗 Women</a></li>
                            <li><a class="dropdown-item" href="{{ route('shop.children') }}">👶 Children</a></li>
                            <li><a class="dropdown-item" href="{{ route('shop.newborn') }}">🍼 Newborn</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ route('shop.caps') }}">🧢 Caps</a></li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('products.aiRecommended') }}">AI Picks</a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link text-success fw-bold" href="{{ route('caps.tryon') }}">
                            <i class="bi bi-camera-video"></i> Try Caps
                        </a>
                    </li>
                    
                    @auth
                    <!-- Cart Icon -->
                    <li class="nav-item">
                        <a href="{{ route('user.cart') }}" class="nav-link position-relative">
                            <i class="bi bi-cart"></i>
                            @php
                                $cartCount = auth()->user()->carts()->count();
                            @endphp
                            
                            @if($cartCount > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" 
                                      style="font-size: 0.6rem; padding: 0.2rem 0.4rem;">
                                    {{ $cartCount }}
                                </span>
                            @endif
                        </a>
                    </li>
                    
                    <!-- User Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> {{ auth()->user()->username }}
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('user.profile') }}"><i class="bi bi-person"></i> My Profile</a></li>
                            <li><a class="dropdown-item" href="{{ route('user.cart') }}"><i class="bi bi-cart"></i> My Cart</a></li>
                            <li><a class="dropdown-item" href="{{ route('wishlist.index') }}"><i class="bi bi-heart"></i> My Wishlist</a></li>
                            <li><a class="dropdown-item" href="{{ route('user.orders.all') }}"><i class="bi bi-box-seam-fill"></i> My Orders</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button class="dropdown-item" type="submit"><i class="bi bi-box-arrow-right"></i> Logout</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                    @else
                    <li class="nav-item">
                        <a class="btn btn-secondary btn-sm rounded-pill px-3" href="{{ route('login') }}" style="color:white;">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-primary btn-sm rounded-pill px-3" href="{{ route('register') }}">Register</a>
                    </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <script>
        
$(document).ready(function(){
    var lastScrollTop = 0;

    $(window).scroll(function() {
        var st = $(this).scrollTop();

        if (st > lastScrollTop) {
            // User is scrolling down → show navbar
            $(".navbar").fadeOut();
        } else {
            // User is scrolling up → hide navbar
            $(".navbar").fadeIn();
        }

        lastScrollTop = st;
        // console.log(lastScrollTop);
    });
});
    </script>

    <main>
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
                    <h4 class="fw-bold" style="color: #7c3aed;">Aksharam Fashion</h4>
                    <p class="text-light">AI-powered fashion platform that understands you.</p>
                </div>
                <div class="col-md-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('home') }}" class="text-light text-decoration-none">Home</a></li>
                        <li><a href="{{ route('about') }}" class="text-light text-decoration-none">About Us</a></li>
                        <li><a href="{{ route('products.index') }}" class="text-light text-decoration-none">Shop All</a></li>
                        <li><a href="{{ route('shop.men') }}" class="text-light text-decoration-none">Men's Collection</a></li>
                        <li><a href="{{ route('shop.women') }}" class="text-light text-decoration-none">Women's Collection</a></li>
                        <li><a href="{{ route('shop.children') }}" class="text-light text-decoration-none">Children's Collection</a></li>
                        <li><a href="{{ route('shop.newborn') }}" class="text-light text-decoration-none">Newborn Collection</a></li>
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
    
    @stack('scripts')
</body>
</html>
