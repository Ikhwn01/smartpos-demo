class PosSystem {
    constructor(config) {
        this.config = config;
        this.cart = [];
        this.products = [];
        this.currentGrandTotal = 0;

        this.initElements();
        this.bindEvents();
        this.fetchProducts();
    }

    initElements() {
        this.productGrid = document.getElementById('pos-product-grid');
        this.searchInput = document.getElementById('pos-search-input');
        this.categoryTabs = document.querySelectorAll('.pos-cat-btn');
        this.cartItemsContainer = document.getElementById('pos-cart-items');
        this.emptyCartState = document.getElementById('pos-cart-empty');
        this.subtotalEl = document.getElementById('pos-subtotal');
        this.taxEl = document.getElementById('pos-tax');
        this.discountInput = document.getElementById('pos-discount-input');
        this.grandTotalEl = document.getElementById('pos-grand-total');
        this.checkoutBtn = document.getElementById('pos-checkout-btn');
        this.clearCartBtn = document.getElementById('pos-clear-cart-btn');

        // Modal elements
        this.checkoutModalEl = document.getElementById('posCheckoutModal');
        this.modalGrandTotal = document.getElementById('modal-grand-total');
        this.paidAmountInput = document.getElementById('modal-paid-amount');
        this.changeAmountEl = document.getElementById('modal-change-amount');
        this.paymentMethodSelect = document.getElementById('modal-payment-method');
        this.customerSelect = document.getElementById('modal-customer-id');
        this.confirmPayBtn = document.getElementById('modal-confirm-pay-btn');
    }

    bindEvents() {
        if (this.searchInput) {
            let debounceTimer;
            this.searchInput.addEventListener('input', (e) => {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => this.fetchProducts(), 300);
            });
        }

        this.categoryTabs.forEach(tab => {
            tab.addEventListener('click', (e) => {
                this.categoryTabs.forEach(t => t.classList.remove('active', 'btn-primary'));
                this.categoryTabs.forEach(t => t.classList.add('btn-outline-secondary'));
                tab.classList.remove('btn-outline-secondary');
                tab.classList.add('active', 'btn-primary');
                this.fetchProducts();
            });
        });

        if (this.discountInput) {
            this.discountInput.addEventListener('input', () => this.updateTotals());
        }

        if (this.clearCartBtn) {
            this.clearCartBtn.addEventListener('click', () => this.clearCart());
        }

        if (this.paidAmountInput) {
            this.paidAmountInput.addEventListener('input', () => this.calculateChange());
        }

        if (this.confirmPayBtn) {
            this.confirmPayBtn.addEventListener('click', () => this.processCheckout());
        }

        if (this.checkoutModalEl) {
            this.checkoutModalEl.addEventListener('show.bs.modal', () => {
                if (this.modalGrandTotal) {
                    this.modalGrandTotal.textContent = this.formatCurrency(this.currentGrandTotal);
                }
                if (this.paidAmountInput) {
                    this.paidAmountInput.value = ''; // Cashier inputs manually
                }
                this.calculateChange();
            });

            this.checkoutModalEl.addEventListener('shown.bs.modal', () => {
                if (this.paidAmountInput) {
                    this.paidAmountInput.focus();
                }
            });
        }
    }

    async fetchProducts() {
        const activeCatBtn = document.querySelector('.pos-cat-btn.active');
        const categoryId = activeCatBtn ? activeCatBtn.getAttribute('data-cat-id') : 'all';
        const search = this.searchInput ? this.searchInput.value : '';

        try {
            const response = await fetch(`${this.config.searchUrl}?search=${encodeURIComponent(search)}&category_id=${categoryId}`);
            const data = await response.json();
            if (data.success) {
                this.products = data.products;
                this.renderProducts();
            }
        } catch (err) {
            console.error('Failed to load products', err);
        }
    }

    renderProducts() {
        if (!this.productGrid) return;
        this.productGrid.innerHTML = '';

        if (this.products.length === 0) {
            this.productGrid.innerHTML = `
                <div class="col-12 text-center py-5 text-muted">
                    <i class="bi bi-box-seam display-4"></i>
                    <p class="mt-2">No products found matching criteria.</p>
                </div>
            `;
            return;
        }

        this.products.forEach(p => {
            const isOutOfStock = p.stock <= 0;
            const col = document.createElement('div');
            col.className = 'col-6 col-md-4 col-xl-3 mb-3';
            col.innerHTML = `
                <div class="pos-product-card p-2 text-center h-100 ${isOutOfStock ? 'opacity-50' : ''}" data-id="${p.id}">
                    <div class="position-relative mb-2">
                        <img src="${p.image_url}" class="img-fluid rounded" style="height: 90px; object-fit: cover;" alt="${p.name}">
                        <span class="badge ${isOutOfStock ? 'bg-danger' : (p.stock_status === 'low_stock' ? 'bg-warning text-dark' : 'bg-success')} position-absolute top-0 end-0 m-1">
                            ${p.stock} ${p.unit}
                        </span>
                    </div>
                    <div class="fw-semibold text-truncate small" title="${p.name}">${p.name}</div>
                    <div class="text-primary fw-bold mt-1">${this.formatCurrency(p.price)}</div>
                </div>
            `;

            if (!isOutOfStock) {
                col.querySelector('.pos-product-card').addEventListener('click', () => this.addToCart(p));
            }

            this.productGrid.appendChild(col);
        });
    }

    addToCart(product) {
        const existing = this.cart.find(item => item.id === product.id);
        if (existing) {
            if (existing.quantity >= product.stock) {
                if (typeof Swal !== 'undefined') Swal.fire('Stock Limit', `Maximum available stock for ${product.name} is ${product.stock}`, 'warning');
                return;
            }
            existing.quantity++;
        } else {
            this.cart.push({
                id: product.id,
                name: product.name,
                price: product.price,
                stock: product.stock,
                unit: product.unit,
                quantity: 1,
                discount: 0
            });
        }
        this.renderCart();
    }

    updateQuantity(productId, delta) {
        const item = this.cart.find(i => i.id === productId);
        if (!item) return;

        item.quantity += delta;
        if (item.quantity > item.stock) {
            item.quantity = item.stock;
            if (typeof Swal !== 'undefined') Swal.fire('Stock Limit', `Maximum available stock reached`, 'warning');
        }

        if (item.quantity <= 0) {
            this.removeFromCart(productId);
        } else {
            this.renderCart();
        }
    }

    removeFromCart(productId) {
        this.cart = this.cart.filter(item => item.id !== productId);
        this.renderCart();
    }

    clearCart() {
        this.cart = [];
        this.renderCart();
    }

    renderCart() {
        if (!this.cartItemsContainer) return;
        this.cartItemsContainer.innerHTML = '';

        if (this.cart.length === 0) {
            this.emptyCartState.style.display = 'block';
            this.cartItemsContainer.style.display = 'none';
        } else {
            this.emptyCartState.style.display = 'none';
            this.cartItemsContainer.style.display = 'block';

            this.cart.forEach(item => {
                const itemTotal = (item.price * item.quantity) - item.discount;
                const row = document.createElement('div');
                row.className = 'p-2 border-bottom d-flex align-items-center justify-content-between';
                row.innerHTML = `
                    <div style="flex: 1;" class="me-2">
                        <div class="fw-semibold small text-truncate" style="max-width: 140px;">${item.name}</div>
                        <div class="text-muted small">${this.formatCurrency(item.price)}</div>
                    </div>
                    <div class="d-flex align-items-center me-2">
                        <button class="btn btn-sm btn-outline-secondary py-0 px-2 btn-qty-minus">-</button>
                        <span class="mx-2 fw-bold small">${item.quantity}</span>
                        <button class="btn btn-sm btn-outline-secondary py-0 px-2 btn-qty-plus">+</button>
                    </div>
                    <div class="fw-bold small me-2">${this.formatCurrency(itemTotal)}</div>
                    <button class="btn btn-sm btn-link text-danger p-0 btn-remove"><i class="bi bi-trash"></i></button>
                `;

                row.querySelector('.btn-qty-minus').addEventListener('click', () => this.updateQuantity(item.id, -1));
                row.querySelector('.btn-qty-plus').addEventListener('click', () => this.updateQuantity(item.id, 1));
                row.querySelector('.btn-remove').addEventListener('click', () => this.removeFromCart(item.id));

                this.cartItemsContainer.appendChild(row);
            });
        }

        this.updateTotals();
    }

    updateTotals() {
        let subtotal = 0;
        this.cart.forEach(item => {
            subtotal += (item.price * item.quantity) - item.discount;
        });

        const overallDiscount = parseFloat(this.discountInput ? this.discountInput.value : 0) || 0;
        const taxRate = parseFloat(this.config.taxPercent) || 0;
        const taxableAmount = Math.max(0, subtotal - overallDiscount);
        const tax = (taxableAmount * taxRate) / 100;
        const grandTotal = taxableAmount + tax;

        if (this.subtotalEl) this.subtotalEl.textContent = this.formatCurrency(subtotal);
        if (this.taxEl) this.taxEl.textContent = this.formatCurrency(tax);
        if (this.grandTotalEl) this.grandTotalEl.textContent = this.formatCurrency(grandTotal);
        if (this.modalGrandTotal) this.modalGrandTotal.textContent = this.formatCurrency(grandTotal);

        this.currentGrandTotal = grandTotal;
        if (this.checkoutBtn) {
            this.checkoutBtn.disabled = this.cart.length === 0;
        }
    }

    calculateChange() {
        const paid = parseFloat(this.paidAmountInput.value) || 0;
        const change = Math.max(0, paid - this.currentGrandTotal);
        if (this.changeAmountEl) {
            this.changeAmountEl.textContent = this.formatCurrency(change);
        }
    }

    async processCheckout() {
        if (this.cart.length === 0) return;

        const paidAmount = parseFloat(this.paidAmountInput.value) || 0;
        if (paidAmount < this.currentGrandTotal) {
            if (typeof Swal !== 'undefined') Swal.fire('Payment Error', 'Paid amount is less than grand total!', 'error');
            return;
        }

        const payload = {
            customer_id: this.customerSelect ? this.customerSelect.value : null,
            payment_method: this.paymentMethodSelect ? this.paymentMethodSelect.value : 'cash',
            discount: parseFloat(this.discountInput ? this.discountInput.value : 0) || 0,
            tax_percent: parseFloat(this.config.taxPercent) || 0,
            paid_amount: paidAmount,
            items: this.cart.map(i => ({
                product_id: i.id,
                quantity: i.quantity,
                price: i.price,
                discount: i.discount
            }))
        };

        try {
            const response = await fetch(this.config.checkoutUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.config.csrfToken
                },
                body: JSON.stringify(payload)
            });

            const data = await response.json();
            if (data.success) {
                const modal = bootstrap.Modal.getInstance(this.checkoutModalEl);
                if (modal) modal.hide();

                this.clearCart();
                this.fetchProducts();

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Sale Completed!',
                        text: data.message,
                        icon: 'success',
                        showCancelButton: true,
                        confirmButtonText: 'Print Invoice',
                        cancelButtonText: 'Close'
                    }).then((res) => {
                        if (res.isConfirmed) {
                            window.open(data.print_url, '_blank');
                        }
                    });
                }
            } else {
                if (typeof Swal !== 'undefined') Swal.fire('Error', data.message, 'error');
            }
        } catch (err) {
            console.error('Checkout error', err);
            if (typeof Swal !== 'undefined') Swal.fire('Error', 'Transaction failed.', 'error');
        }
    }

    formatCurrency(amount) {
        return `${this.config.currency} ${parseFloat(amount).toFixed(2)}`;
    }
}
