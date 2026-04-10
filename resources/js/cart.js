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
            container.innerHTML = `
                <div class="p-12 text-center flex flex-col items-center justify-center">
                    <div class="w-24 h-24 bg-emerald-100/50 rounded-full flex items-center justify-center mb-6 shadow-inner backdrop-blur-sm">
                        <i class="fa-solid fa-basket-shopping text-4xl text-emerald-500/50"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-700 mb-2">Keranjang Anda Kosong</h3>
                    <p class="text-slate-500 mb-8 max-w-sm mx-auto">Sepertinya Anda belum menambahkan benih apapun ke keranjang.</p>
                    <a href="/katalog" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-8 rounded-xl shadow-lg shadow-emerald-600/20 transition-all hover:-translate-y-1">
                        Mulai Belanja <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            `;
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
                <div class="cart-item p-4 sm:p-6 flex flex-col sm:flex-row gap-4 sm:gap-6 items-start border-b border-white/40 last:border-0 bg-white/10 hover:bg-white/20 transition-colors duration-200" data-item-key="${this.itemKey(item)}">
                    <!-- Image -->
                    <div class="w-full sm:w-24 h-48 sm:h-24 flex-shrink-0 bg-white/30 rounded-xl overflow-hidden shadow-sm relative group">
                        <img src="${item.image || '/img/placeholder.jpg'}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" loading="lazy">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    </div>
                    
                    <!-- Content -->
                    <div class="flex-1 w-full">
                        <div class="flex flex-col sm:flex-row justify-between mb-2 gap-2">
                            <div>
                                <h3 class="font-bold text-slate-800 text-lg leading-tight tracking-tight">${item.name}</h3>
                                <div class="flex flex-wrap items-center gap-2 mt-2">
                                    <span class="${badgeClass} text-xs font-bold px-2.5 py-1 rounded-lg shadow-sm border border-white/50 backdrop-blur-sm">
                                        ${item.seed_class_code}
                                    </span>
                                    <span class="text-sm text-slate-600 bg-white/40 px-2 py-0.5 rounded-md border border-white/30 backdrop-blur-sm">
                                        ${item.seed_class_name || 'Benih'}
                                    </span>
                                    <span class="text-xs text-slate-500 font-mono bg-slate-100/50 px-2 py-0.5 rounded-md border border-slate-200/50">
                                        Lot: ${item.seed_lot_id || '-'}
                                    </span>
                                </div>
                            </div>
                            <button class="btn-remove self-end sm:self-start text-red-400 hover:text-red-600 bg-red-50/50 hover:bg-red-100 p-2 rounded-lg transition-all shadow-sm hover:shadow-md active:scale-95" title="Hapus item">
                                <i class="fa-regular fa-trash-can"></i>
                            </button>
                        </div>
                        
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between bg-white/40 backdrop-blur-md p-4 rounded-xl mt-3 border border-white/50 shadow-inner gap-4">
                            <div class="text-sm font-semibold text-slate-700 flex items-center gap-2">
                                <i class="fa-solid fa-tag text-emerald-500/70"></i>
                                <span>${this.formatIDR(parseInt(item.price_per_unit) || 0)} <span class="text-xs text-slate-500 font-normal">/ kg</span></span>
                            </div>
                            
                            <div class="flex flex-col items-end gap-2 w-full sm:w-auto">
                                <div class="flex items-center bg-white/80 rounded-xl shadow-sm border border-white/60 w-full sm:w-auto justify-between sm:justify-start overflow-hidden ring-1 ring-slate-200/50 focus-within:ring-emerald-400/50 transition-all">
                                    <button class="btn-dec w-12 sm:w-10 h-10 flex items-center justify-center text-slate-600 hover:bg-slate-50 hover:text-emerald-600 transition-colors active:bg-slate-100 border-r border-slate-100" data-item-key="${this.itemKey(item)}">
                                        ${this.getDecreaseButtonIcon(item)}
                                    </button>
                                    <input type="number" class="qty-input w-full sm:w-16 text-center text-sm font-bold text-slate-800 border-0 bg-transparent focus:ring-0 p-0" value="${item.quantity}" min="${item.seed_class_code === 'FS' ? 5 : 1}" step="${item.seed_class_code === 'FS' ? 5 : 1}" data-item-key="${this.itemKey(item)}">
                                    <button class="btn-inc w-12 sm:w-10 h-10 flex items-center justify-center text-slate-600 hover:bg-slate-50 hover:text-emerald-600 transition-colors active:bg-slate-100 border-l border-slate-100" data-item-key="${this.itemKey(item)}">
                                        <i class="fa-solid fa-plus text-xs"></i>
                                    </button>
                                </div>
                                <div class="error-msg text-xs text-red-500 font-medium hidden text-right bg-red-50 px-2 py-1 rounded-md border border-red-100"></div>
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
              <div class="flex items-start gap-4 border-b border-slate-100 pb-4 last:border-0 last:pb-0 group" data-item-key="${this.itemKey(item)}">
                <div class="w-16 h-16 bg-white rounded-lg overflow-hidden shadow-sm border border-slate-100 flex-shrink-0">
                  <img src="${item.image || '/img/placeholder.jpg'}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" loading="lazy">
                </div>
                <div class="flex-1 min-w-0">
                  <div class="flex justify-between items-start mb-1">
                    <h4 class="font-bold text-slate-800 text-sm truncate pr-2">${item.name}</h4>
                    <button class="btn-remove text-slate-400 hover:text-red-500 transition-colors p-1 rounded-md hover:bg-red-50" title="Hapus item">
                      <i class="fa-regular fa-trash-can"></i>
                    </button>
                  </div>
                  
                  <div class="flex items-center gap-2 mb-2">
                    <span class="${badgeClass} text-[10px] font-bold px-1.5 py-0.5 rounded shadow-sm border border-white/50">${item.seed_class_code}</span>
                    <span class="text-[10px] text-slate-500 font-mono bg-slate-50 px-1.5 py-0.5 rounded border border-slate-100">Lot: ${item.seed_lot_id || '-'}</span>
                  </div>

                  <div class="flex items-center justify-between bg-slate-50/50 p-2 rounded-lg border border-slate-100">
                    <div class="flex items-center bg-white rounded-md shadow-sm border border-slate-200 overflow-hidden">
                      <button class="btn-dec w-7 h-7 flex items-center justify-center text-slate-600 hover:bg-slate-50 hover:text-emerald-600 transition-colors border-r border-slate-100" data-item-key="${this.itemKey(item)}">${decreaseIcon}</button>
                      <span class="w-8 text-center text-xs font-bold text-slate-800">${item.quantity}</span>
                      <button class="btn-inc w-7 h-7 flex items-center justify-center text-slate-600 hover:bg-slate-50 hover:text-emerald-600 transition-colors border-l border-slate-100" data-item-key="${this.itemKey(item)}">+</button>
                    </div>
                    <span class="font-bold text-emerald-600 text-sm">${this.formatIDR(itemTotal)}</span>
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
