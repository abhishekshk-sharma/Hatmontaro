@extends('layouts.app')

@section('title', 'Shop - Browse Our Collection')

@section('content')
<div class="amazon-container py-4">
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
                                   value="{{ request('search') }}" placeholder="Search caps...">
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
                                <option value="baseball" {{ request('style') == 'baseball' ? 'selected' : '' }}>Baseball</option>
                                <option value="snapback" {{ request('style') == 'snapback' ? 'selected' : '' }}>Snapback</option>
                                <option value="beanie" {{ request('style') == 'beanie' ? 'selected' : '' }}>Beanie</option>
                                <option value="trucker" {{ request('style') == 'trucker' ? 'selected' : '' }}>Trucker</option>
                                <option value="bucket" {{ request('style') == 'bucket' ? 'selected' : '' }}>Bucket</option>
                            </select>
                        </div>
                        
                        <!-- Brand -->
                        <div class="mb-3">
                            <label class="form-label">Brand</label>
                            <select name="brand" class="form-select">
                                <option value="">All Brands</option>
                                <option value="nike" {{ request('brand') == 'nike' ? 'selected' : '' }}>Nike</option>
                                <option value="adidas" {{ request('brand') == 'adidas' ? 'selected' : '' }}>Adidas</option>
                                <option value="new-era" {{ request('brand') == 'new-era' ? 'selected' : '' }}>New Era</option>
                                <option value="puma" {{ request('brand') == 'puma' ? 'selected' : '' }}>Puma</option>
                            </select>
                        </div>
                        
                        <!-- Color -->
                        <div class="mb-3">
                            <label class="form-label">Color</label>
                            <select name="color" class="form-select">
                                <option value="">All Colors</option>
                                <option value="black" {{ request('color') == 'black' ? 'selected' : '' }}>Black</option>
                                <option value="white" {{ request('color') == 'white' ? 'selected' : '' }}>White</option>
                                <option value="blue" {{ request('color') == 'blue' ? 'selected' : '' }}>Blue</option>
                                <option value="red" {{ request('color') == 'red' ? 'selected' : '' }}>Red</option>
                                <option value="gray" {{ request('color') == 'gray' ? 'selected' : '' }}>Gray</option>
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
                <h1 class="h3 mb-0">Shop All Caps</h1>
                
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
                <div class="amazon-products-grid">
                    @foreach($products as $product)
                        <x-product-card :product="$product" />
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
                    <h3>No caps found</h3>
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