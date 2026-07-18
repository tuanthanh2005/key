// ============================================
// VPNStore - Main JavaScript
// ============================================

document.addEventListener('DOMContentLoaded', function () {

    // ============================
    // CART MANAGEMENT
    // ============================
    const CartManager = {
        key: 'vpnstore_cart',
        couponKey: 'vpnstore_coupon',

        getCart() {
            try {
                return JSON.parse(localStorage.getItem(this.key)) || [];
            } catch { return []; }
        },

        saveCart(cart) {
            localStorage.setItem(this.key, JSON.stringify(cart));
            this.updateBadge();
        },

        getCoupon() {
            return localStorage.getItem(this.couponKey) || null;
        },

        setCoupon(code) {
            localStorage.setItem(this.couponKey, code);
        },

        clearCoupon() {
            localStorage.removeItem(this.couponKey);
        },

        getCouponDiscount(subtotal) {
            const coupon = this.getCoupon();
            const coupons = { 'VPNVN10': 0.1, 'VIP20': 0.2, 'SALE15': 0.15 };
            if (coupon && coupons[coupon]) {
                return Math.round(subtotal * coupons[coupon]);
            }
            return 0;
        },

        addItem(product) {
            const cart = this.getCart();
            const existing = cart.find(i => i.id === product.id && i.plan === product.plan);
            if (existing) {
                existing.qty += product.qty || 1;
            } else {
                cart.push({ ...product, qty: product.qty || 1 });
            }
            this.saveCart(cart);
            return true;
        },

        removeItem(id, plan) {
            const cart = this.getCart().filter(i => !(i.id === id && i.plan === plan));
            this.saveCart(cart);
        },

        updateQty(id, plan, qty) {
            const cart = this.getCart();
            const item = cart.find(i => i.id === id && i.plan === plan);
            if (item) {
                if (qty <= 0) { this.removeItem(id, plan); return; }
                item.qty = qty;
                this.saveCart(cart);
            }
        },

        getTotal() {
            return this.getCart().reduce((sum, i) => sum + (i.price * i.qty), 0);
        },

        getCount() {
            return this.getCart().reduce((sum, i) => sum + i.qty, 0);
        },

        clear() {
            localStorage.removeItem(this.key);
            this.clearCoupon();
            this.updateBadge();
        },

        updateBadge() {
            const badges = document.querySelectorAll('.cart-badge');
            if (badges.length > 0) {
                const count = this.getCount();
                badges.forEach(badge => {
                    badge.textContent = count;
                    badge.style.display = count > 0 ? 'flex' : 'none';
                });
            }
        }
    };

    // Initialize badge
    CartManager.updateBadge();
    window.CartManager = CartManager;

    // ============================
    // ADD TO CART BUTTONS
    // ============================
    document.querySelectorAll('[data-add-cart]').forEach(btn => {
        btn.addEventListener('click', function () {
            const productId   = this.dataset.id;
            const productName = this.dataset.name;
            const productBrand = this.dataset.brand || '';
            const plan        = this.dataset.plan || '1month';
            const price       = parseFloat(this.dataset.price) || 0;
            const brandColor  = this.dataset.color || '#2563eb';
            const brandSlug   = this.dataset.slug || '';
            const requireEmail = this.dataset.requireEmail === '1';

            // Check if there is a quantity selector on the page (e.g. product-detail page)
            const qtyInput = document.getElementById('detail-qty');
            const qtyVal = qtyInput ? (parseInt(qtyInput.value) || 1) : 1;

            CartManager.addItem({
                id: productId + '_' + plan,
                productId,
                name: productName,
                brand: productBrand,
                plan,
                price,
                brandColor,
                brandSlug,
                requireEmail,
                qty: qtyVal
            });

            // Button animation
            const origHtml = this.innerHTML;
            this.innerHTML = '<i class="bi bi-check-lg me-1"></i>Đã thêm!';
            this.classList.add('btn-success');
            this.classList.remove('btn-primary', 'btn-add-cart');
            setTimeout(() => {
                this.innerHTML = origHtml;
                this.classList.remove('btn-success');
                this.classList.add('btn-add-cart');
            }, 1500);

            showToast('Đã thêm ' + productName + ' vào giỏ hàng!', 'success');
        });
    });

    // ============================
    // WISHLIST TOGGLE
    // ============================
    document.querySelectorAll('[data-wishlist]').forEach(btn => {
        btn.addEventListener('click', function () {
            this.classList.toggle('active');
            const isActive = this.classList.contains('active');
            const icon = this.querySelector('i');
            if (icon) {
                icon.className = isActive ? 'bi bi-heart-fill' : 'bi bi-heart';
            }
            showToast(isActive ? 'Đã thêm vào yêu thích!' : 'Đã bỏ khỏi yêu thích', isActive ? 'success' : 'info');
        });
    });

    // ============================
    // PLAN SELECTOR (Product Detail)
    // ============================
    document.querySelectorAll('.plan-option').forEach(option => {
        option.addEventListener('click', function () {
            // Deselect all in group
            const group = this.closest('.plan-selector');
            if (group) {
                group.querySelectorAll('.plan-option').forEach(o => o.classList.remove('selected'));
            }
            this.classList.add('selected');

            // Update main price display
            const priceDisplay = document.getElementById('selected-price');
            const price = this.dataset.price;
            const period = this.dataset.period;
            if (priceDisplay && price) {
                priceDisplay.textContent = formatCurrency(parseFloat(price));
            }
            const periodDisplay = document.getElementById('selected-period');
            if (periodDisplay && period) {
                periodDisplay.textContent = '/ ' + period;
            }

            // Update add-to-cart button data
            const addBtn = document.getElementById('btn-main-add-cart');
            if (addBtn) {
                addBtn.dataset.price = price;
                addBtn.dataset.plan = this.dataset.plan;
            }
        });
    });

    // ============================
    // QUANTITY CONTROLS
    // ============================
    document.querySelectorAll('.qty-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const input = this.closest('.qty-control')?.querySelector('.qty-input');
            if (!input) return;
            let val = parseInt(input.value) || 1;
            if (this.dataset.action === 'plus') val = Math.min(val + 1, 10);
            if (this.dataset.action === 'minus') val = Math.max(val - 1, 1);
            input.value = val;
            input.dispatchEvent(new Event('change'));
        });
    });

    // ============================
    // FILTER FORM - Price Range
    // ============================
    const priceRange = document.getElementById('priceRange');
    const priceDisplay = document.getElementById('priceDisplay');
    if (priceRange && priceDisplay) {
        priceRange.addEventListener('input', function () {
            priceDisplay.textContent = formatCurrency(parseInt(this.value)) + ' trở xuống';
        });
    }

    // ============================
    // SEARCH FILTER (Products Page)
    // ============================
    const productSearch = document.getElementById('productSearch');
    if (productSearch) {
        productSearch.addEventListener('input', function () {
            const q = this.value.toLowerCase();
            document.querySelectorAll('.product-card-wrap').forEach(wrap => {
                const name = wrap.dataset.name?.toLowerCase() || '';
                const brand = wrap.dataset.brand?.toLowerCase() || '';
                wrap.style.display = (name.includes(q) || brand.includes(q)) ? '' : 'none';
            });
        });
    }

    // ============================
    // SORT SELECT
    // ============================
    const sortSelect = document.getElementById('sortSelect');
    if (sortSelect) {
        sortSelect.addEventListener('change', function () {
            const val = this.value;
            const grid = document.getElementById('productsGrid');
            if (!grid) return;
            const cards = Array.from(grid.querySelectorAll('.product-card-wrap'));
            cards.sort((a, b) => {
                const pa = parseFloat(a.dataset.price) || 0;
                const pb = parseFloat(b.dataset.price) || 0;
                const ra = parseFloat(a.dataset.rating) || 0;
                const rb = parseFloat(b.dataset.rating) || 0;
                if (val === 'price_asc') return pa - pb;
                if (val === 'price_desc') return pb - pa;
                if (val === 'rating') return rb - ra;
                return 0;
            });
            cards.forEach(c => grid.appendChild(c));
        });
    }

    // ============================
    // PAYMENT METHOD SELECT
    // ============================
    document.querySelectorAll('.payment-method-card').forEach(card => {
        card.addEventListener('click', function () {
            document.querySelectorAll('.payment-method-card').forEach(c => c.classList.remove('selected'));
            this.classList.add('selected');
            const radio = this.querySelector('input[type="radio"]');
            if (radio) radio.checked = true;

            // Show/hide bank transfer details
            const bankDetail = document.getElementById('bank-transfer-detail');
            if (bankDetail) {
                bankDetail.style.display = (radio && radio.value === 'bank_transfer') ? 'block' : 'none';
            }
        });
    });

    // ============================
    // ORDER FORM (Checkout)
    // ============================
    const checkoutForm = document.getElementById('checkoutForm');
    if (checkoutForm) {
        // Pre-fill cart summary
        renderCartSummary();
    }

    // ============================
    // ORDER CHECK FORM
    // ============================
    const orderCheckForm = document.getElementById('orderCheckForm');
    if (orderCheckForm) {
        orderCheckForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const orderId = document.getElementById('orderIdInput')?.value?.trim();
            if (!orderId) {
                showToast('Vui lòng nhập mã đơn hàng!', 'warning');
                return;
            }
            window.location.href = '/tra-don-hang?order=' + encodeURIComponent(orderId);
        });
    }

    // ============================
    // CONTACT FORM
    // ============================
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const btn = this.querySelector('button[type="submit"]');
            if (btn) {
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Đang gửi...';
                btn.disabled = true;
            }
            setTimeout(() => {
                showToast('Tin nhắn đã được gửi thành công! Chúng tôi sẽ phản hồi trong vòng 24 giờ.', 'success');
                this.reset();
                if (btn) { btn.innerHTML = '<i class="bi bi-send me-2"></i>Gửi Tin Nhắn'; btn.disabled = false; }
            }, 1500);
        });
    }

    // ============================
    // BACK TO TOP
    // ============================
    const backTop = document.getElementById('backToTop');
    if (backTop) {
        window.addEventListener('scroll', () => {
            backTop.classList.toggle('visible', window.scrollY > 400);
        });
        backTop.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    // ============================
    // HELPERS
    // ============================
    function showToast(msg, type = 'success') {
        const toast = document.getElementById('mainToast');
        const msgEl = document.getElementById('toastMessage');
        if (!toast || !msgEl) return;

        const icons = {
            success: 'bi-check-circle',
            warning: 'bi-exclamation-triangle',
            danger:  'bi-x-circle',
            info:    'bi-info-circle'
        };
        const colors = {
            success: 'text-bg-success',
            warning: 'text-bg-warning',
            danger:  'text-bg-danger',
            info:    'text-bg-primary'
        };
        // Reset classes
        toast.className = 'toast align-items-center border-0';
        toast.classList.add(colors[type] || 'text-bg-success');
        msgEl.innerHTML = `<i class="bi ${icons[type] || 'bi-check-circle'} me-2"></i>${msg}`;

        const bsToast = bootstrap.Toast.getOrCreateInstance(toast, { delay: 3500 });
        bsToast.show();
    }
    window.showToast = showToast;

    function formatCurrency(num) {
        return num.toLocaleString('vi-VN') + 'đ';
    }
    window.formatCurrency = formatCurrency;

    function generateOrderId() {
        return 'VPN' + Date.now().toString().slice(-8);
    }

    function validateCheckout() {
        const cart = CartManager.getCart();
        if (cart.length === 0) {
            showToast('Giỏ hàng trống! Vui lòng thêm sản phẩm.', 'warning');
            return false;
        }
        return true;
    }

    function renderCartSummary() {
        const cart = CartManager.getCart();
        const summaryEl = document.getElementById('checkout-cart-items');
        if (!summaryEl) return;
        if (cart.length === 0) {
            summaryEl.innerHTML = '<p class="text-center text-muted py-3">Giỏ hàng trống</p>';
            return;
        }
        summaryEl.innerHTML = cart.map(item => `
            <div class="d-flex align-items-center gap-3 mb-3 pb-3 border-bottom">
                <div class="cart-item-img flex-shrink-0" style="background:${item.brandColor}22;color:${item.brandColor}">
                    <i class="bi bi-shield-fill-check"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="fw-600 small">${item.name}</div>
                    <div class="text-muted" style="font-size:12px">${item.plan} · SL: ${item.qty}</div>
                </div>
                <div class="fw-700 text-primary small">${formatCurrency(item.price * item.qty)}</div>
            </div>
        `).join('');
    }

    // ============================
    // CART PAGE RENDER
    // ============================
    function renderCartPage() {
        const cartContainer = document.getElementById('cartItemsContainer');
        if (!cartContainer) return;
        const cart = CartManager.getCart();

        if (cart.length === 0) {
            cartContainer.innerHTML = `
                <div class="empty-state py-5">
                    <div class="empty-state-icon"><i class="bi bi-bag-x"></i></div>
                    <h5>Giỏ hàng của bạn đang trống</h5>
                    <p>Hãy thêm sản phẩm VPN vào giỏ hàng nhé!</p>
                    <a href="/san-pham" class="btn btn-primary mt-3 rounded-pill px-4">
                        <i class="bi bi-grid me-2"></i>Xem Sản Phẩm
                    </a>
                </div>
            `;
            updateCartSummaryDisplay(0, 0, null, 0);
            return;
        }

        let hasOutOfStockItem = false;
        cartContainer.innerHTML = cart.map(item => {
            const stockKey = (item.brand + '_' + item.plan).toLowerCase().replace(/\s+/g, '');
            const isOutOfStock = window.stockMap !== undefined && window.stockMap[stockKey] !== undefined && window.stockMap[stockKey] <= 0;
            if (isOutOfStock) {
                hasOutOfStockItem = true;
            }
            return `
            <div class="cart-item mb-3 ${isOutOfStock ? 'border-danger bg-light' : ''}" id="cart-item-${item.id}">
                <div class="d-flex align-items-center gap-3">
                    <div class="cart-item-img" style="background:${item.brandColor}15;color:${item.brandColor}">
                        <i class="bi bi-shield-lock-fill" style="font-size:28px"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-700 mb-1" style="font-size:14px">
                            ${item.name}
                            ${isOutOfStock ? '<span class="badge bg-danger ms-2" style="font-size:10.5px">Hết Hàng</span>' : ''}
                        </div>
                        <div class="text-muted" style="font-size:12.5px">
                            <span class="badge me-1" style="background:${item.brandColor}20;color:${item.brandColor};font-size:11px;font-weight:600;padding:3px 8px;border-radius:6px">${item.brand}</span>
                            Gói: ${item.plan}
                        </div>
                        <div class="d-flex align-items-center gap-3 mt-2">
                            <div class="qty-control">
                                <button class="qty-btn" data-action="minus" onclick="updateItemQty('${item.id}','${item.plan}', -1)" ${isOutOfStock ? 'disabled' : ''}><i class="bi bi-dash"></i></button>
                                <input type="text" class="qty-input" value="${item.qty}" id="qty-${item.id}" readonly>
                                <button class="qty-btn" data-action="plus" onclick="updateItemQty('${item.id}','${item.plan}', 1)" ${isOutOfStock ? 'disabled' : ''}><i class="bi bi-plus"></i></button>
                            </div>
                            <span class="fw-800 text-primary" style="font-size:16px">${formatCurrency(item.price)}</span>
                        </div>
                    </div>
                    <div class="d-flex flex-column align-items-end gap-2">
                        <div class="fw-800 text-primary" style="font-size:18px;font-family:'Poppins',sans-serif">${formatCurrency(item.price * item.qty)}</div>
                        <button class="btn btn-sm btn-outline-danger" onclick="removeCartItem('${item.id}','${item.plan}')">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
            `;
        }).join('');

        const checkoutBtn = document.querySelector('a[href*="thanh-toan"]');
        if (checkoutBtn) {
            if (hasOutOfStockItem) {
                checkoutBtn.classList.add('disabled');
                checkoutBtn.style.background = '#cbd5e1';
                checkoutBtn.style.borderColor = '#cbd5e1';
                checkoutBtn.style.color = '#64748b';
                checkoutBtn.style.pointerEvents = 'none';
                checkoutBtn.innerHTML = '<i class="bi bi-exclamation-circle me-2"></i>Có sản phẩm hết hàng';
            } else {
                checkoutBtn.classList.remove('disabled');
                checkoutBtn.style.background = '';
                checkoutBtn.style.borderColor = '';
                checkoutBtn.style.color = '';
                checkoutBtn.style.pointerEvents = '';
                checkoutBtn.innerHTML = '<i class="bi bi-credit-card me-2"></i>Tiến Hành Thanh Toán';
            }
        }

        const subtotal = CartManager.getTotal();
        const autoDiscount = subtotal > 500000 ? Math.round(subtotal * 0.05) : 0;
        const couponCode = CartManager.getCoupon();
        const couponDiscount = CartManager.getCouponDiscount(subtotal);
        updateCartSummaryDisplay(subtotal, autoDiscount, couponCode, couponDiscount);
    }

    function updateCartSummaryDisplay(subtotal, autoDiscount, couponCode, couponDiscount) {
        const subtotalEl = document.getElementById('cart-subtotal');
        const discountEl = document.getElementById('cart-discount');
        const couponEl   = document.getElementById('cart-coupon');
        const totalEl    = document.getElementById('cart-total');
        
        const total = Math.max(0, subtotal - autoDiscount - couponDiscount);
        
        if (subtotalEl) subtotalEl.textContent = formatCurrency(subtotal);
        if (discountEl) discountEl.textContent = autoDiscount > 0 ? '-' + formatCurrency(autoDiscount) : '0đ';
        
        if (couponEl) {
            if (couponCode) {
                couponEl.innerHTML = `<span class="badge bg-success-light text-success">${couponCode}</span> -${formatCurrency(couponDiscount)}`;
            } else {
                couponEl.textContent = 'Chưa áp dụng';
            }
        }
        if (totalEl) totalEl.textContent = formatCurrency(total);
    }

    window.renderCartPage = renderCartPage;

    window.updateItemQty = function (id, plan, delta) {
        const cart = CartManager.getCart();
        const item = cart.find(i => i.id === id);
        if (!item) return;
        const newQty = item.qty + delta;
        if (newQty <= 0) { removeCartItem(id, plan); return; }
        CartManager.updateQty(id, plan, newQty);
        renderCartPage();
    };

    window.removeCartItem = function (id, plan) {
        CartManager.removeItem(id, plan);
        showToast('Đã xóa sản phẩm khỏi giỏ hàng', 'info');
        renderCartPage();
    };

    // Render cart if on cart page
    if (document.getElementById('cartItemsContainer')) {
        renderCartPage();
    }



    // ============================
    // NAVBAR SCROLL EFFECT
    // ============================
    const navbar = document.querySelector('.navbar-main');
    if (navbar) {
        window.addEventListener('scroll', () => {
            navbar.style.boxShadow = window.scrollY > 20
                ? '0 4px 20px rgba(0,0,0,0.08)'
                : '0 1px 3px rgba(0,0,0,0.06)';
        });
    }

    // Promo countdown timer
    const countdownEl = document.getElementById('promoCountdown');
    if (countdownEl) {
        let end = new Date(); end.setHours(end.getHours() + 23, 59, 59);
        setInterval(() => {
            const diff = end - new Date();
            if (diff <= 0) return;
            const h = String(Math.floor(diff / 3600000)).padStart(2, '0');
            const m = String(Math.floor((diff % 3600000) / 60000)).padStart(2, '0');
            const s = String(Math.floor((diff % 60000) / 1000)).padStart(2, '0');
            countdownEl.textContent = `${h}:${m}:${s}`;
        }, 1000);
    }

    // ============================
    // WISHLIST (HEART BUTTON) LOGIC
    // ============================
    function updateWishlistStates() {
        const wishlist = window.dbWishlist !== null
            ? window.dbWishlist
            : JSON.parse(localStorage.getItem('wishlist') || '[]');

        document.querySelectorAll('[data-wishlist]').forEach(btn => {
            let id = btn.dataset.id;
            if (!id) {
                const card = btn.closest('.product-card') || btn.closest('.product-card-wrap');
                if (card) {
                    const cartBtn = card.querySelector('[data-add-cart]');
                    if (cartBtn) id = cartBtn.dataset.id;
                }
            }
            if (!id) {
                const cartBtn = document.getElementById('btn-main-add-cart');
                if (cartBtn) id = cartBtn.dataset.id;
            }
            
            const icon = btn.querySelector('i');
            if (id && wishlist.includes(parseInt(id))) {
                if (icon) icon.className = 'bi bi-heart-fill';
                btn.style.color = '#ef4444';
            } else {
                if (icon) icon.className = 'bi bi-heart';
                btn.style.color = '';
            }
        });
    }

    // Initialize wishlist states on load
    updateWishlistStates();

    // Event delegation for wishlist toggle clicks
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('[data-wishlist]');
        if (!btn) return;
        
        e.preventDefault();
        
        // If not logged in, redirect to login
        if (window.dbWishlist === null) {
            showToast('Vui lòng đăng nhập để lưu sản phẩm yêu thích!', 'warning');
            setTimeout(() => {
                window.location.href = '/auth/dang-nhap';
            }, 1200);
            return;
        }
        
        let id = btn.dataset.id;
        if (!id) {
            const card = btn.closest('.product-card') || btn.closest('.product-card-wrap');
            if (card) {
                const cartBtn = card.querySelector('[data-add-cart]');
                if (cartBtn) id = cartBtn.dataset.id;
            }
        }
        if (!id) {
            const cartBtn = document.getElementById('btn-main-add-cart');
            if (cartBtn) id = cartBtn.dataset.id;
        }
        
        if (!id) return;
        
        // Logged in: Sync with Database via AJAX
        btn.disabled = true;
        fetch('/wishlist/toggle', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken || ''
            },
            body: JSON.stringify({ product_id: parseInt(id) })
        })
        .then(res => res.json())
        .then(data => {
            btn.disabled = false;
            if (data.success) {
                window.dbWishlist = data.wishlist;
                updateWishlistStates();
                showToast(data.message, 'success');
                
                // If we are on the wishlist page and an item is removed, animate and remove it
                if (data.action === 'removed') {
                    const cardWrap = document.querySelector(`.product-card-wrap[data-id="${id}"]`);
                    if (cardWrap && window.location.pathname.includes('/san-pham-yeu-thich')) {
                        cardWrap.style.transition = 'all 0.4s ease';
                        cardWrap.style.opacity = '0';
                        cardWrap.style.transform = 'scale(0.9)';
                        setTimeout(() => {
                            cardWrap.remove();
                            // Check if no items left
                            const remaining = document.querySelectorAll('.product-card-wrap').length;
                            if (remaining === 0) {
                                window.location.reload();
                            }
                        }, 400);
                    }
                }
            } else {
                showToast(data.message || 'Có lỗi xảy ra!', 'danger');
            }
        })
        .catch(err => {
            btn.disabled = false;
            showToast('Lỗi kết nối máy chủ!', 'danger');
        });
    });
});
