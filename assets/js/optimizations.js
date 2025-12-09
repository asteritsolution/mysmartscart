/**
 * MySmartSCart - Performance Optimizations JS
 * Lazy loading, infinite scroll, and performance enhancements
 */

(function($) {
    'use strict';

    // =====================================================
    // LAZY LOADING FOR IMAGES
    // =====================================================
    const LazyLoader = {
        init: function() {
            // Use Intersection Observer for better performance
            if ('IntersectionObserver' in window) {
                const imageObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const img = entry.target;
                            this.loadImage(img);
                            observer.unobserve(img);
                        }
                    });
                }, {
                    rootMargin: '100px 0px', // Start loading 100px before visible
                    threshold: 0.01
                });

                document.querySelectorAll('img.lazy, img[data-src]').forEach(img => {
                    imageObserver.observe(img);
                });
            } else {
                // Fallback for older browsers
                this.loadAllImages();
            }
        },

        loadImage: function(img) {
            const src = img.dataset.src;
            if (src) {
                img.src = src;
                img.classList.remove('lazy');
                img.classList.add('loaded');
                img.removeAttribute('data-src');
            }
        },

        loadAllImages: function() {
            document.querySelectorAll('img.lazy, img[data-src]').forEach(img => {
                this.loadImage(img);
            });
        }
    };

    // =====================================================
    // INFINITE SCROLL FOR PRODUCT LISTING
    // =====================================================
    const InfiniteScroll = {
        loading: false,
        currentPage: 1,
        hasMore: true,
        container: null,
        endpoint: 'api/products.php',

        init: function(options = {}) {
            this.container = document.querySelector(options.container || '.products-group');
            if (!this.container) return;

            this.currentPage = parseInt(options.startPage) || 1;
            this.hasMore = options.hasMore !== false;

            // Listen for scroll
            window.addEventListener('scroll', this.throttle(this.checkScroll.bind(this), 200));
        },

        checkScroll: function() {
            if (this.loading || !this.hasMore) return;

            const scrollPos = window.innerHeight + window.scrollY;
            const threshold = document.body.offsetHeight - 500;

            if (scrollPos >= threshold) {
                this.loadMore();
            }
        },

        loadMore: function() {
            if (this.loading || !this.hasMore) return;

            this.loading = true;
            this.currentPage++;

            // Show loading indicator
            this.showLoading();

            // Get current filters from URL
            const params = new URLSearchParams(window.location.search);
            params.set('page', this.currentPage);
            params.set('action', 'list');

            fetch(this.endpoint + '?' + params.toString())
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.data.html) {
                        // Append new products
                        this.container.insertAdjacentHTML('beforeend', data.data.html);
                        
                        // Initialize lazy loading for new images
                        LazyLoader.init();
                        
                        // Update hasMore
                        this.hasMore = data.data.has_more;
                        
                        // Update page info
                        this.updatePageInfo(data.data);
                    } else {
                        this.hasMore = false;
                    }
                })
                .catch(error => {
                    console.error('Error loading products:', error);
                })
                .finally(() => {
                    this.loading = false;
                    this.hideLoading();
                });
        },

        showLoading: function() {
            let loader = document.querySelector('.loading-more');
            if (!loader) {
                loader = document.createElement('div');
                loader.className = 'loading-more';
                loader.innerHTML = '<div class="spinner"></div><p>Loading more products...</p>';
                this.container.parentNode.insertBefore(loader, this.container.nextSibling);
            }
            loader.style.display = 'block';
        },

        hideLoading: function() {
            const loader = document.querySelector('.loading-more');
            if (loader) {
                loader.style.display = this.hasMore ? 'none' : 'none';
                if (!this.hasMore) {
                    loader.innerHTML = '<p>No more products to load</p>';
                    loader.style.display = 'block';
                }
            }
        },

        updatePageInfo: function(data) {
            const info = document.querySelector('.products-count');
            if (info) {
                info.textContent = `Showing ${data.page * data.per_page} of ${data.total} products`;
            }
        },

        throttle: function(func, limit) {
            let inThrottle;
            return function() {
                const args = arguments;
                const context = this;
                if (!inThrottle) {
                    func.apply(context, args);
                    inThrottle = true;
                    setTimeout(() => inThrottle = false, limit);
                }
            };
        }
    };

    // =====================================================
    // QUICK ADD TO CART (AJAX)
    // =====================================================
    const QuickCart = {
        init: function() {
            $(document).on('click', '.btn-add-cart.ajax-cart', function(e) {
                e.preventDefault();
                const productId = $(this).data('product-id');
                const qty = $(this).data('qty') || 1;
                
                if (!productId) return;
                
                QuickCart.add(productId, qty, $(this));
            });
        },

        add: function(productId, qty, button) {
            const originalText = button.html();
            button.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');

            $.ajax({
                url: 'cart.php',
                method: 'GET',
                data: {
                    action: 'add',
                    id: productId,
                    qty: qty,
                    ajax: 1
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        // Update cart count
                        $('.cart-count').text(response.cart_count);
                        
                        // Show toast
                        QuickCart.showToast('Product added to cart!', 'success');
                    } else {
                        QuickCart.showToast(response.message || 'Error adding product', 'error');
                    }
                },
                error: function() {
                    // Fallback to regular add
                    window.location.href = 'cart.php?action=add&id=' + productId + '&qty=' + qty;
                },
                complete: function() {
                    button.prop('disabled', false).html(originalText);
                }
            });
        },

        showToast: function(message, type) {
            // Remove existing toast
            $('.toast-notification').remove();

            const toast = $('<div class="toast-notification ' + type + '">' + message + '</div>');
            $('body').append(toast);

            setTimeout(() => toast.addClass('show'), 10);
            setTimeout(() => {
                toast.removeClass('show');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }
    };

    // =====================================================
    // SEARCH AUTOCOMPLETE
    // =====================================================
    const SearchAutocomplete = {
        init: function() {
            const searchInput = document.querySelector('#q, .header-search input[name="q"]');
            if (!searchInput) return;

            let timeout;
            searchInput.addEventListener('input', (e) => {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    this.search(e.target.value);
                }, 300);
            });
        },

        search: function(query) {
            if (query.length < 2) {
                this.hideResults();
                return;
            }

            fetch('api/products.php?action=search&q=' + encodeURIComponent(query))
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.data.length > 0) {
                        this.showResults(data.data);
                    } else {
                        this.hideResults();
                    }
                });
        },

        showResults: function(products) {
            let dropdown = document.querySelector('.search-autocomplete');
            if (!dropdown) {
                dropdown = document.createElement('div');
                dropdown.className = 'search-autocomplete';
                document.querySelector('.header-search-wrapper').appendChild(dropdown);
            }

            dropdown.innerHTML = products.map(p => `
                <a href="product.php?slug=${p.slug}" class="search-result-item">
                    <img src="${p.thumbnail || p.image}" alt="${p.name}" width="50" height="50">
                    <div>
                        <strong>${p.name}</strong>
                        <span class="price">₹${parseFloat(p.sale_price || p.price).toFixed(2)}</span>
                    </div>
                </a>
            `).join('');

            dropdown.style.display = 'block';
        },

        hideResults: function() {
            const dropdown = document.querySelector('.search-autocomplete');
            if (dropdown) {
                dropdown.style.display = 'none';
            }
        }
    };

    // =====================================================
    // PERFORMANCE MONITORING
    // =====================================================
    const Performance = {
        init: function() {
            // Log page load time
            window.addEventListener('load', () => {
                const timing = performance.timing;
                const loadTime = timing.loadEventEnd - timing.navigationStart;
                console.log('Page load time:', loadTime + 'ms');
            });
        }
    };

    // =====================================================
    // INITIALIZE ON DOM READY
    // =====================================================
    $(document).ready(function() {
        // Initialize lazy loading
        LazyLoader.init();

        // Initialize quick cart
        QuickCart.init();

        // Initialize search autocomplete
        SearchAutocomplete.init();

        // Performance monitoring (development only)
        if (window.location.hostname === 'localhost') {
            Performance.init();
        }
    });

    // Re-initialize lazy loading after AJAX content loads
    $(document).ajaxComplete(function() {
        LazyLoader.init();
    });

    // Export for use in other scripts
    window.MSC = {
        LazyLoader: LazyLoader,
        InfiniteScroll: InfiniteScroll,
        QuickCart: QuickCart
    };

})(jQuery);

