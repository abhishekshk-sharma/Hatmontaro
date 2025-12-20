@extends('layouts.app')

@section('title', 'Shop - Browse Our Collection')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Mobile Filter Toggle -->
        <div class="col-12 d-md-none mb-3">
            <button class="btn btn-outline-primary w-100" type="button" id="filterToggle">
                <i class="bi bi-funnel"></i> Show Filters
            </button>
        </div>
        
        <!-- Sidebar Filters -->
        <div class="col-lg-3 mb-4" id="filterSidebar">
            <div class="card shadow-sm filter-card" id="draggableFilter">
                <div class="card-header bg-white d-flex justify-content-between align-items-center" id="filterHandle">
                    <h5 class="mb-0">Filters</h5>
                    <div>
                        <i class="bi bi-arrows-move text-muted me-2 d-none d-md-inline" title="Drag to move"></i>
                        <button class="btn btn-sm btn-outline-secondary d-md-none" id="closeFilter">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('products.index') }}">
                        <!-- Search -->
                        <div class="mb-3">
                            <label class="form-label">Search</label>
                            <input type="text" name="search" class="form-control" 
                                   value="{{ request('search') }}" placeholder="Search products...">
                        </div>
                        
                        <!-- Categories -->
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select name="category" class="form-select">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" 
                                            {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Price Range -->
                        <div class="mb-3">
                            <label class="form-label">Price Range</label>
                            <div class="row g-2">
                                <div class="col">
                                    <input type="number" name="min_price" class="form-control" 
                                           placeholder="Min" value="{{ request('min_price') }}">
                                </div>
                                <div class="col">
                                    <input type="number" name="max_price" class="form-control" 
                                           placeholder="Max" value="{{ request('max_price') }}">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Style Type -->
                        <div class="mb-3">
                            <label class="form-label">Style</label>
                            <select name="style" class="form-select">
                                <option value="">All Styles</option>
                                <option value="casual" {{ request('style') == 'casual' ? 'selected' : '' }}>Casual</option>
                                <option value="formal" {{ request('style') == 'formal' ? 'selected' : '' }}>Formal</option>
                                <option value="bohemian" {{ request('style') == 'bohemian' ? 'selected' : '' }}>Bohemian</option>
                                <option value="minimalist" {{ request('style') == 'minimalist' ? 'selected' : '' }}>Minimalist</option>
                                <option value="ethnic" {{ request('style') == 'ethnic' ? 'selected' : '' }}>Ethnic</option>
                            </select>
                        </div>
                        
                        <!-- Occasion -->
                        <div class="mb-3">
                            <label class="form-label">Occasion</label>
                            <select name="occasion" class="form-select">
                                <option value="">All Occasions</option>
                                <option value="casual" {{ request('occasion') == 'casual' ? 'selected' : '' }}>Casual</option>
                                <option value="office" {{ request('occasion') == 'office' ? 'selected' : '' }}>Office</option>
                                <option value="wedding" {{ request('occasion') == 'wedding' ? 'selected' : '' }}>Wedding</option>
                                <option value="party" {{ request('occasion') == 'party' ? 'selected' : '' }}>Party</option>
                                <option value="formal" {{ request('occasion') == 'formal' ? 'selected' : '' }}>Formal</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary w-100 mt-2">Reset</a>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Products Grid -->
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Shop All Products</h1>
                
                <!-- Sorting -->
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" 
                            data-bs-toggle="dropdown">
                        Sort: 
                        @if(request('sort') == 'price_low') Price: Low to High
                        @elseif(request('sort') == 'price_high') Price: High to Low
                        @elseif(request('sort') == 'name') Name: A-Z
                        @else Newest
                        @endif
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="?sort=newest{{ request('search') ? '&search=' . request('search') : '' }}">Newest</a></li>
                        <li><a class="dropdown-item" href="?sort=price_low{{ request('search') ? '&search=' . request('search') : '' }}">Price: Low to High</a></li>
                        <li><a class="dropdown-item" href="?sort=price_high{{ request('search') ? '&search=' . request('search') : '' }}">Price: High to Low</a></li>
                        <li><a class="dropdown-item" href="?sort=name{{ request('search') ? '&search=' . request('search') : '' }}">Name: A-Z</a></li>
                    </ul>
                </div>
            </div>
            
            <!-- Products Grid -->
            @if($products->count() > 0)
                <div class="row g-4">
                    @foreach($products as $product)
                        <div class="col-md-6 col-lg-4">
                            <a href="{{ route('products.show', $product) }}" class="text-decoration-none">
                                <div class="card product-card h-100">
                                    <div class="position-relative">
                                        @php
                                            $img = $product->image_url;
                                            if (!\Illuminate\Support\Str::startsWith($img, ['http://','https://','/storage/'])) {
                                                $img = asset($img);
                                            }
                                        @endphp
                                        <img src="{{ $img }}" 
                                             class="card-img-top" 
                                             alt="{{ $product->name }}"
                                             style="height: 250px; object-fit: cover;">
                                        
                                        @if($product->is_ai_recommended)
                                            <span class="position-absolute top-0 end-0 m-2">
                                                <span class="badge bg-primary">AI Recommended</span>
                                            </span>
                                        @endif
                                        
                                        @if($product->is_featured)
                                            <span class="position-absolute top-0 start-0 m-2">
                                                <span class="badge bg-success">Featured</span>
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <div class="card-body">
                                        <h5 class="card-title text-dark">{{ $product->name }}</h5>
                                        <p class="card-text text-muted small">
                                            {{ Str::limit($product->description, 80) }}
                                        </p>
                                        
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <span class="h5 text-primary">₹{{ number_format($product->price, 2) }}</span>
                                                @if($product->compare_price)
                                                    <small class="text-muted text-decoration-line-through ms-1">
                                                        ₹{{ number_format($product->compare_price, 2) }}
                                                    </small>
                                                @endif
                                            </div>
                                            
                                            <div class="text-muted small">
                                                <i class="bi bi-tag me-1"></i>{{ $product->category->name ?? 'Uncategorized' }}
                                            </div>
                                        </div>
                                        
                                        <div class="mt-2">
                                            <span class="badge bg-light text-dark">{{ $product->style_type }}</span>
                                            <span class="badge bg-light text-dark">{{ $product->occasion }}</span>
                                            <span class="badge bg-light text-dark">{{ $product->color }}</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                <div class="mt-4">
                    {{ $products->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <div class="display-1 text-muted">
                        <i class="bi bi-search"></i>
                    </div>
                    <h3>No products found</h3>
                    <p class="text-muted">Try adjusting your search or filter criteria</p>
                    <a href="{{ route('products.index') }}" class="btn btn-primary">Clear Filters</a>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.filter-card {
    cursor: move;
    transition: all 0.3s ease;
}

.filter-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
}

#filterHandle {
    cursor: grab;
}

#filterHandle:active {
    cursor: grabbing;
}

@media (max-width: 767.98px) {
    #filterSidebar {
        position: fixed;
        top: 0;
        left: -100%;
        width: 80%;
        height: 100vh;
        z-index: 1050;
        background: white;
        transition: left 0.3s ease;
        overflow-y: auto;
        padding: 1rem;
    }
    
    #filterSidebar.show {
        left: 0;
    }
    
    .filter-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        z-index: 1040;
        display: none;
    }
    
    .filter-overlay.show {
        display: block;
    }
    
    .filter-card {
        cursor: default;
        border: none;
        box-shadow: none !important;
    }
    
    #filterHandle {
        cursor: default;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterToggle = document.getElementById('filterToggle');
    const filterSidebar = document.getElementById('filterSidebar');
    const closeFilter = document.getElementById('closeFilter');
    const draggableFilter = document.getElementById('draggableFilter');
    const filterHandle = document.getElementById('filterHandle');
    
    // Create overlay for mobile
    const overlay = document.createElement('div');
    overlay.className = 'filter-overlay';
    document.body.appendChild(overlay);
    
    // Mobile filter toggle
    if (filterToggle) {
        filterToggle.addEventListener('click', function() {
            filterSidebar.classList.add('show');
            overlay.classList.add('show');
            document.body.style.overflow = 'hidden';
        });
    }
    
    // Close filter
    function closeFilterPanel() {
        filterSidebar.classList.remove('show');
        overlay.classList.remove('show');
        document.body.style.overflow = '';
    }
    
    if (closeFilter) {
        closeFilter.addEventListener('click', closeFilterPanel);
    }
    
    overlay.addEventListener('click', closeFilterPanel);
    
    // Draggable functionality for desktop
    if (window.innerWidth >= 768) {
        let isDragging = false;
        let currentX;
        let currentY;
        let initialX;
        let initialY;
        let xOffset = 0;
        let yOffset = 0;
        
        filterHandle.addEventListener('mousedown', dragStart);
        document.addEventListener('mousemove', drag);
        document.addEventListener('mouseup', dragEnd);
        
        function dragStart(e) {
            initialX = e.clientX - xOffset;
            initialY = e.clientY - yOffset;
            
            if (e.target === filterHandle || filterHandle.contains(e.target)) {
                isDragging = true;
                draggableFilter.style.position = 'fixed';
                draggableFilter.style.zIndex = '1000';
                draggableFilter.style.width = '300px';
            }
        }
        
        function drag(e) {
            if (isDragging) {
                e.preventDefault();
                currentX = e.clientX - initialX;
                currentY = e.clientY - initialY;
                
                xOffset = currentX;
                yOffset = currentY;
                
                draggableFilter.style.transform = `translate(${currentX}px, ${currentY}px)`;
            }
        }
        
        function dragEnd(e) {
            initialX = currentX;
            initialY = currentY;
            isDragging = false;
        }
    }
    
    // Reset position on window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth < 768) {
            draggableFilter.style.position = '';
            draggableFilter.style.transform = '';
            draggableFilter.style.width = '';
            draggableFilter.style.zIndex = '';
        }
    });
});
</script>
@endsection