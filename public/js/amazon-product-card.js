/**
 * Hatmontaro - Amazon Product Card Interactive Script
 * File: public/js/amazon-product-card.js
 */

(function () {
    // Ensure CSRF Token is available
    function getCsrfToken() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    }

    // Create toast container if not already in DOM
    function ensureToastContainer() {
        let toast = document.getElementById('amazonCartToast');
        if (!toast) {
            toast = document.createElement('div');
            toast.id = 'amazonCartToast';
            toast.className = 'amazon-cart-toast';
            toast.innerHTML = `
                <div class="amazon-toast-check">
                    <i class="bi bi-check-lg"></i>
                </div>
                <div class="amazon-toast-content">
                    <div class="amazon-toast-title">Added to Cart</div>
                    <div class="amazon-toast-msg" id="amazonToastProductName">Item added successfully</div>
                </div>
                <a href="/cart" class="amazon-toast-btn">Go to Cart</a>
            `;
            document.body.appendChild(toast);
        }
        return toast;
    }

    let toastTimeout = null;
    function showCartToast(productName) {
        const toast = ensureToastContainer();
        const msgEl = document.getElementById('amazonToastProductName');
        if (msgEl) {
            msgEl.textContent = productName ? (productName.length > 30 ? productName.substring(0, 30) + '...' : productName) : 'Item added successfully';
        }
        
        toast.classList.add('show');
        if (toastTimeout) clearTimeout(toastTimeout);
        toastTimeout = setTimeout(() => {
            toast.classList.remove('show');
        }, 4000);
    }

    // Update cart badge across navbar
    function updateCartCount(newCount) {
        if (newCount === undefined || newCount === null) return;
        const badges = document.querySelectorAll('.cart-count, .cart-badge, .badge.rounded-pill.bg-danger');
        badges.forEach(b => {
            b.textContent = newCount;
            b.style.display = newCount > 0 ? 'inline-block' : 'none';
        });
    }

    // Add to Cart handler
    window.addToCartAmazon = function (event, productId, button, productName) {
        if (event) {
            event.preventDefault();
            event.stopPropagation();
        }

        if (!button || button.disabled || button.classList.contains('loading')) {
            return;
        }

        const originalHtml = button.innerHTML;
        button.classList.add('loading');
        button.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Adding...`;

        fetch(`/cart/add/${productId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken(),
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ quantity: 1 })
        })
        .then(response => {
            if (!response.ok) {
                // If not JSON or unauthorized redirect
                if (response.status === 401) {
                    window.location.href = '/login';
                    return;
                }
                throw new Error('Failed to add product to cart');
            }
            return response.json();
        })
        .then(data => {
            button.classList.remove('loading');
            button.classList.add('added');
            button.innerHTML = `<i class="bi bi-check-lg"></i> Added`;

            if (data && data.cart_count !== undefined) {
                updateCartCount(data.cart_count);
            }

            showCartToast(productName || 'Item');

            setTimeout(() => {
                button.classList.remove('added');
                button.innerHTML = originalHtml;
            }, 2500);
        })
        .catch(err => {
            console.error('Error adding to cart:', err);
            button.classList.remove('loading');
            button.innerHTML = originalHtml;
            
            // Fallback: regular form submit to cart
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/cart/add/${productId}`;
            
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = getCsrfToken();
            form.appendChild(csrfInput);
            
            document.body.appendChild(form);
            form.submit();
        });
    };

    // Wishlist Toggle Handler
    window.toggleWishlistAmazon = function (event, productId, button) {
        if (event) {
            event.preventDefault();
            event.stopPropagation();
        }

        if (!button) return;
        const icon = button.querySelector('i');

        fetch(`/wishlist/add/${productId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken(),
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (response.status === 401) {
                window.location.href = '/login';
                return;
            }
            if (icon) {
                icon.className = 'bi bi-heart-fill text-danger';
                button.classList.add('active');
            }
            showCartToast('Added to Wishlist');
        })
        .catch(err => {
            console.warn('Wishlist action:', err);
            if (icon) {
                icon.className = 'bi bi-heart-fill text-danger';
            }
        });
    };
})();
