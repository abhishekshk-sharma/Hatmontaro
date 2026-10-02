@props(['product', 'compact' => false])

@php
    $img = $product->image_url;
    
    if ($img && !\Illuminate\Support\Str::startsWith($img, ['http://', 'https://', '/storage/', 'data:'])) {
        $img = asset($img);
    }
    if (!$img) {
        $img = 'https://images.unsplash.com/photo-1588850561407-ed78c282e89b?auto=format&fit=crop&w=500&q=80';
    }
    
    // Deterministic realistic ratings based on product id
    $ratingScore = 4.0 + (($product->id * 7) % 10) / 10;
    $reviewCount = 120 + (($product->id * 173) % 2400);
    $boughtCount = 50 * ((($product->id * 3) % 8) + 1);
    
    $hasDiscount = $product->compare_price && $product->compare_price > $product->price;
    $discountPercent = $hasDiscount ? round((($product->compare_price - $product->price) / $product->compare_price) * 100) : 0;
    
    $brandName = $product->brand ?? ($product->category->name ?? 'Hatmontaro');
    $isCap = ($product->category && $product->category->slug === 'caps') || (isset($product->style_type) && str_contains(strtolower($product->name), 'cap'));
    $productUrl = route('products.show', $product->slug ?: $product->id);
@endphp

<div class="amazon-product-card product-card {{ $compact ? 'compact-card' : '' }}" data-product-id="{{ $product->id }}">
    <!-- Floating Badges & Wishlist -->
    <div class="amazon-badges-bar">
        <div class="amazon-badges-left">
            @if($product->is_featured)
                <span class="amazon-badge-bestseller">Best Seller</span>
            @elseif($product->is_ai_recommended)
                <span class="amazon-badge-choice">
                    <span>Aura's</span> <span class="choice-highlight">Choice</span>
                </span>
            @elseif($hasDiscount && $discountPercent >= 20)
                <span class="amazon-badge-deal">Deal</span>
            @endif
        </div>
        
        <button type="button" 
                class="amazon-btn-wishlist" 
                onclick="toggleWishlistAmazon(event, {{ $product->id }}, this)" 
                title="Add to Wishlist"
                aria-label="Add to Wishlist">
            <i class="bi bi-heart"></i>
        </button>
    </div>

    <!-- Product Image Container -->
    <a href="{{ $productUrl }}" class="amazon-image-wrapper text-decoration-none">
        <img src="{{ $img }}" 
             alt="{{ $product->name }}" 
             class="card-img-top" 
             loading="lazy"
             onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1588850561407-ed78c282e89b?auto=format&fit=crop&w=500&q=80';">
             
        @if($isCap)
            <span class="amazon-tryon-pill" onclick="event.preventDefault(); window.location='{{ route('caps.tryon', ['product' => $product->id]) }}';">
                <i class="bi bi-camera-video"></i> Try On
            </span>
        @endif
    </a>

    <!-- Product Body -->
    <div class="amazon-card-body card-body">
        <!-- Brand / Style row -->
        <div class="amazon-brand-row">
            <span class="brand-title">{{ $brandName }}</span>
            @if($product->style_type)
                <span>•</span>
                <span class="category-tag">{{ ucfirst($product->style_type) }}</span>
            @endif
        </div>

        <!-- Product Title -->
        <a href="{{ $productUrl }}" class="amazon-title card-title" title="{{ $product->name }}">
            {{ $product->name }}
        </a>

        <!-- Star Ratings Row -->
        <div class="amazon-rating-wrap">
            <div class="amazon-stars" title="{{ number_format($ratingScore, 1) }} out of 5 stars">
                @for($i = 1; $i <= 5; $i++)
                    @if($i <= floor($ratingScore))
                        <i class="bi bi-star-fill"></i>
                    @elseif($i - $ratingScore <= 0.5)
                        <i class="bi bi-star-half"></i>
                    @else
                        <i class="bi bi-star"></i>
                    @endif
                @endfor
            </div>
            <span class="amazon-rating-number">{{ number_format($ratingScore, 1) }}</span>
            <a href="{{ $productUrl }}#reviews" class="amazon-reviews-count">({{ number_format($reviewCount) }})</a>
        </div>

        <!-- Social Proof (Bought in past month) -->
        <div class="amazon-social-bought">
            {{ $boughtCount }}+ bought in past month
        </div>

        <!-- Price Section -->
        <div class="amazon-pricing-box">
            @if($hasDiscount)
                <div class="amazon-deal-badge-row">
                    <span class="amazon-discount-percent">-{{ $discountPercent }}%</span>
                    <span class="amazon-deal-label">Limited time deal</span>
                </div>
            @endif

            <div class="amazon-price-row">
                <span class="amazon-currency-symbol">₹</span>
                <span class="amazon-price-main">{{ number_format($product->price) }}</span>
                <span class="amazon-price-cents">00</span>

                @if($hasDiscount)
                    <span class="amazon-mrp-wrapper">
                        M.R.P.: <span class="amazon-mrp-value">₹{{ number_format($product->compare_price) }}</span>
                    </span>
                @endif
            </div>
        </div>

        <!-- Delivery Line -->
        <div class="amazon-delivery-box">
            @if($product->is_premium_delivery)
                <div class="amazon-prime-line">
                    <span class="hatmontaro-plus-badge"><i class="bi bi-patch-check-fill"></i> hatmontaro <span class="plus-symbol">+</span></span>
                    <span class="text-dark fw-bold ms-1">FREE Delivery</span>
                </div>
                <div>
                    Get it by <span class="amazon-delivery-bold">{{ $product->delivery_time ?? 'Tomorrow, 2 PM' }}</span>
                </div>
            @else
                <div class="amazon-prime-line">
                    <span class="standard-delivery-badge"><i class="bi bi-truck"></i> Standard Delivery</span>
                </div>
                <div>
                    Get it by <span class="amazon-delivery-bold">{{ $product->delivery_time ?? '3-5 Business Days' }}</span>
                </div>
            @endif
        </div>

        <!-- Stock Status -->
        <div class="amazon-stock-line {{ $product->stock_quantity > 0 ? ($product->stock_quantity <= 5 ? 'low-stock' : 'in-stock') : 'out-stock' }}">
            @if($product->stock_quantity > 0 && $product->stock_quantity <= 5)
                Only {{ $product->stock_quantity }} left in stock - order soon.
            @elseif($product->stock_quantity == 0)
                Currently unavailable
            @else
                In stock
            @endif
        </div>

        <!-- Amazon Action Buttons -->
        <div class="amazon-card-actions">
            @if($product->stock_quantity > 0)
                <button type="button" 
                        class="amazon-btn-cart" 
                        onclick="addToCartAmazon(event, {{ $product->id }}, this, '{{ addslashes($product->name) }}')"
                        data-product-id="{{ $product->id }}">
                    <i class="bi bi-cart2"></i>
                    <span>Add to Cart</span>
                </button>
            @else
                <button type="button" class="amazon-btn-cart disabled" disabled>
                    Out of Stock
                </button>
            @endif

            @if($isCap)
                <a href="{{ route('caps.tryon', ['product' => $product->id]) }}" class="amazon-btn-secondary">
                    <i class="bi bi-camera-video text-success"></i> Virtual Try-On
                </a>
            @endif
        </div>
    </div>
</div>
