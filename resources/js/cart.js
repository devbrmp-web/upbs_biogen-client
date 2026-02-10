/**
 * Cart Logic V3
 * Supports Seed Hierarchy: Variety -> Class -> Lot
 */

const CART_KEY = 'upbs_cart_v2';

window.cart = {
    data: {
        items: [],
        last_updated: 0
    },

    init() {
        this.load();
        this.updateBadge();
        if (document.getElementById('cart-list-container')) {
            this.renderCartPage();
        }
        this.renderCartModal();

        document.body.addEventListener('click', (e) => {
            const target = e.target;

            const closeCartBtn = target.closest('#closeCartBtn');
            if (closeCartBtn) {
                const modal = document.getElementById('cartModal');
                if (modal) modal.classList.add('hidden');
            }

            const clearCartBtn = target.closest('#clearCartBtn');
            if (clearCartBtn) {
                if (confirm('Yakin mau menghapus semua item dari keranjang?')) {
                    this.clearCart();
                }
            }

            const checkoutBtn = target.closest('#cartCheckoutBtn');
            if (checkoutBtn) {
                window.location.href = '/checkout';
            }

            const incBtn = target.closest('.btn-inc');
            if (incBtn) {
                const key = incBtn.closest('[data-item-key]')?.dataset.itemKey;
                if (key) this.updateQty(key, 1);
            }

            const decBtn = target.closest('.btn-dec');
            if (decBtn) {
                const key = decBtn.closest('[data-item-key]')?.dataset.itemKey;
                if (key) {
                    const item = this.data.items.find(i => this.itemKey(i) === key);
                    if (item) {
                        const minQuantity = item.seed_class_code === 'FS' ? 5 : 1;
                        if (item.quantity <= minQuantity) {
                            this.removeItem(key);
                            return;
                        }
                    }
                    this.updateQty(key, -1);
                }
            }

            const removeBtn = target.closest('.btn-remove');
            if (removeBtn) {
                const key = removeBtn.closest('[data-item-key]')?.dataset.itemKey;
                if (key) this.removeItem(key);
            }
        });

        document.body.addEventListener('input', (e) => {
            const target = e.target;
            if (target.classList.contains('qty-input')) {
                const key = target.closest('[data-item-key]')?.dataset.itemKey;
                const item = this.data.items.find(i => this.itemKey(i) === key);
                if (!item) return;
                let val = parseInt(target.value) || 0;
                const step = item.seed_class_code === 'FS' ? 5 : 1;
                let valid = item.seed_class_code === 'FS' ? (val % 5 === 0 && val >= 5) : (val >= 1);
                const msgEl = target.closest('.cart-item')?.querySelector('.error-msg');
                if (!valid) {
                    if (item.seed_class_code === 'FS') {
                        if (msgEl) { msgEl.textContent = 'Jumlah untuk Foundation Seed (FS) harus kelipatan 5 kg'; msgEl.classList.remove('hidden'); }
                        val = Math.max(5, val - (val % 5));
                    } else {
                        if (msgEl) { msgEl.textContent = 'Jumlah minimal 1'; msgEl.classList.remove('hidden'); }
                        val = Math.max(1, val);
                    }
                    target.value = String(val);
                } else {
                    if (msgEl) msgEl.classList.add('hidden');
                }
                item.quantity = val;
                this.save();
                
                // Update the decrease button icon after quantity change
                this.updateDecreaseButtonIcon(key);
            }
        });

        // Listen for external updates (e.g. from other tabs or add-to-cart actions)
        window.addEventListener('storage', () => {
            this.load();
            this.updateBadge();
            if (document.getElementById('cart-list-container')) {
                this.renderCartPage();
            }
            this.renderCartModal();
        });
    },

    load() {
        try {
            const stored = localStorage.getItem(CART_KEY);
            if (stored) {
                const parsed = JSON.parse(stored);
                this.data = parsed.items ? parsed : { items: [], last_updated: 0 };
            }
        } catch (e) {
            this.data = { items: [], last_updated: 0 };
        }
    },

    save() {
        this.data.last_updated = Date.now();
        localStorage.setItem(CART_KEY, JSON.stringify(this.data));
        this.updateBadge();
        if (document.getElementById('cart-list-container')) {
            this.renderCartPage();
        }
    },

    itemKey(item) {
        const lot = item.seed_lot_id != null ? String(item.seed_lot_id) : String(item.seed_class_id ?? item.seed_class_code ?? '');
        return `${item.variety_id}-${lot}`;
    },

    removeItem(itemKey) {
        if (!confirm('Hapus item ini dari keranjang?')) return;
        this.data.items = this.data.items.filter(i => this.itemKey(i) !== itemKey);
        this.save();
    },

    clearCart() {
        if (!confirm('Kosongkan keranjang?')) return;
        this.data.items = [];
        this.save();
    },

    updateQty(itemKey, delta) {
        const item = this.data.items.find(i => this.itemKey(i) === itemKey);
        if (!item) return;
        const step = item.seed_class_code === 'FS' ? 5 : 1;
        let newQty = item.quantity + (delta * step);
        if (item.seed_class_code === 'FS') {
            if (newQty < step) newQty = step;
            newQty = newQty - (newQty % step);
        } else {
            if (newQty < 1) newQty = 1;
        }
        item.quantity = newQty;
        this.save();
        
        // Update the decrease button icon after quantity change
        this.updateDecreaseButtonIcon(itemKey);
    },

    updateBadge() {
        const badge = document.getElementById('cartBadge');
        if (badge) {
            const count = this.data.items.length;
            badge.textContent = count;
            badge.style.display = count > 0 ? 'inline-block' : 'none';
        }
    },

    formatIDR(num) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(num);
    },

    getDecreaseButtonIcon(item) {
        const minQuantity = item.seed_class_code === 'FS' ? 5 : 1;
        const isAtMinimum = item.quantity <= minQuantity;
        
        if (isAtMinimum) {
            // Show trash icon
            return '<svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>';
        } else {
            // Show minus icon
            return '-';
        }
    },

    updateDecreaseButtonIcon(itemKey) {
        const item = this.data.items.find(i => this.itemKey(i) === itemKey);
        if (!item) return;
        
        const button = document.querySelector(`button[data-item-key="${itemKey}"].btn-dec`);
        if (button) {
            button.innerHTML = this.getDecreaseButtonIcon(item);
        }
    },

    renderCartPage() {
        const container = document.getElementById('cart-list-container');
        const summaryCount = document.getElementById('summary-count');
        const summaryWeight = document.getElementById('summary-weight');
        const summaryTotal = document.getElementById('summary-total');
        const btnCheckout = document.getElementById('btn-checkout');
        
        if (!container) return;

        if (this.data.items.length === 0) {
            container.innerHTML = `<div class="p-10 text-center text-gray-500">Keranjang Anda kosong.</div>`;
            if (summaryCount) summaryCount.textContent = '0 Item';
            if (summaryWeight) summaryWeight.textContent = '0 kg';
            if (summaryTotal) summaryTotal.textContent = 'Rp 0';
            if (btnCheckout) btnCheckout.disabled = true;
            return;
        }
        
        if (btnCheckout) btnCheckout.disabled = false;

        let totalWeight = 0;
        let totalPrice = 0;

        const html = this.data.items.map(item => {
            const itemTotal = item.quantity * (parseInt(item.price_per_unit) || 0);
            totalWeight += item.quantity;
            totalPrice += itemTotal;

            // Badge Color
            let badgeClass = 'bg-gray-100 text-gray-800';
            if (item.seed_class_code === 'BS') badgeClass = 'bg-yellow-100 text-yellow-800';
            if (item.seed_class_code === 'FS') badgeClass = 'bg-purple-100 text-purple-800';

            return `
                <div class="cart-item p-6 flex gap-6 items-start border-b border-gray-100 last:border-0" data-item-key="${this.itemKey(item)}">
                    <div class="w-24 h-24 flex-shrink-0 bg-gray-100 rounded-lg overflow-hidden">
                        <img src="${item.image || '/img/placeholder.jpg'}" class="w-full h-full object-cover" loading="lazy">
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between mb-2">
                            <div>
                                <h3 class="font-semibold text-gray-900 text-lg">${item.name}</h3>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="${badgeClass} text-xs font-bold px-2 py-0.5 rounded">
                                        ${item.seed_class_code}
                                    </span>
                                    <span class="text-sm text-gray-500">
                                        ${item.seed_class_name || 'Benih'}
                                    </span>
                                    <span class="text-sm text-gray-500">
                                        Lot: ${item.seed_lot_id || '-'}
                                    </span>
                                </div>
                            </div>
                            <button class="btn-remove text-gray-400 hover:text-red-500 transition-colors" title="Hapus item">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                        
                        <div class="flex items-center justify-between bg-gray-50 p-3 rounded-lg mt-3">
                            <div class="text-sm text-gray-600">
                                Harga: ${this.formatIDR(parseInt(item.price_per_unit) || 0)} / kg
                            </div>
                            <div class="flex flex-col items-end gap-2">
                                <div class="flex items-center bg-white rounded-md border border-gray-200">
                                    <button class="btn-dec px-3 py-1 text-gray-600 hover:bg-gray-50 border-r border-gray-200" data-item-key="${this.itemKey(item)}">
                                        ${this.getDecreaseButtonIcon(item)}
                                    </button>
                                    <input type="number" class="qty-input w-16 text-center text-sm font-medium text-gray-900 border-0 focus:ring-0 p-1" value="${item.quantity}" min="${item.seed_class_code === 'FS' ? 5 : 1}" step="${item.seed_class_code === 'FS' ? 5 : 1}" data-item-key="${this.itemKey(item)}">
                                    <button class="btn-inc px-3 py-1 text-gray-600 hover:bg-gray-50 border-l border-gray-200" data-item-key="${this.itemKey(item)}">+</button>
                                </div>
                                <div class="error-msg text-xs text-red-600 mt-1 hidden"></div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }).join('');

        container.innerHTML = html;
        
        // Update all decrease button icons after rendering
        this.data.items.forEach(item => {
            this.updateDecreaseButtonIcon(this.itemKey(item));
        });
        
        if (summaryCount) summaryCount.textContent = `${this.data.items.length} Item`;
        if (summaryWeight) summaryWeight.textContent = `${totalWeight} kg`;
        if (summaryTotal) summaryTotal.textContent = this.formatIDR(totalPrice);
    },
    
    renderCartModal() {
        const container = document.getElementById('cartItems');
        const totalItemEl = document.getElementById('cartTotalItem');
        const grandTotalEl = document.getElementById('cartGrandTotal');
        if (!container) return;

        if (this.data.items.length === 0) {
            container.innerHTML = '<div class="p-6 text-center text-gray-500">Keranjang kosong</div>';
            totalItemEl.textContent = '0';
            grandTotalEl.textContent = this.formatIDR(0);
            return;
        }

        let grand = 0;
        container.innerHTML = this.data.items.map(item => {
            const itemTotal = item.quantity * (parseInt(item.price_per_unit) || 0);
            grand += itemTotal;
            let badgeClass = 'bg-gray-100 text-gray-800';
            if (item.seed_class_code === 'BS') badgeClass = 'bg-yellow-100 text-yellow-800';
            if (item.seed_class_code === 'FS') badgeClass = 'bg-purple-100 text-purple-800';
            
            const minQuantity = item.seed_class_code === 'FS' ? 5 : 1;
            const isAtMinimum = item.quantity <= minQuantity;
            const decreaseIcon = isAtMinimum 
                ? '<svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>'
                : '-';
            
            return `
              <div class="flex items-start gap-4 border-b pb-3 last:border-0" data-item-key="${this.itemKey(item)}">
                <div class="w-16 h-16 bg-gray-100 rounded-lg overflow-hidden">
                  <img src="${item.image || '/img/placeholder.jpg'}" class="w-full h-full object-cover" loading="lazy">
                </div>
                <div class="flex-1">
                  <div class="flex justify-between">
                    <div>
                      <h4 class="font-semibold text-gray-900 text-sm">${item.name}</h4>
                      <div class="flex items-center gap-2 mt-1">
                        <span class="${badgeClass} text-xs font-bold px-2 py-0.5 rounded">${item.seed_class_code}</span>
                        <span class="text-xs text-gray-500">Lot: ${item.seed_lot_id || '-'}</span>
                      </div>
                    </div>
                    <button class="btn-remove text-gray-400 hover:text-red-500" title="Hapus item">
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                      </svg>
                    </button>
                  </div>
                  <div class="flex items-center justify-between bg-gray-50 p-2 rounded mt-2">
                    <div class="text-xs text-gray-600">${this.formatIDR(parseInt(item.price_per_unit) || 0)} / kg</div>
                    <div class="flex items-center gap-2">
                      <button class="btn-dec px-2 py-1 border rounded" data-item-key="${this.itemKey(item)}">${decreaseIcon}</button>
                      <span class="w-8 text-center text-sm">${item.quantity}</span>
                      <button class="btn-inc px-2 py-1 border rounded" data-item-key="${this.itemKey(item)}">+</button>
                      <span class="font-bold text-gray-900">${this.formatIDR(itemTotal)}</span>
                    </div>
                  </div>
                </div>
              </div>`;
        }).join('');
        totalItemEl.textContent = String(this.data.items.length);
        grandTotalEl.textContent = this.formatIDR(grand);
        
        // Update all decrease button icons after rendering modal
        this.data.items.forEach(item => {
            this.updateDecreaseButtonIcon(this.itemKey(item));
        });
    }
};

document.addEventListener('DOMContentLoaded', () => {
    window.cart.init();
});
