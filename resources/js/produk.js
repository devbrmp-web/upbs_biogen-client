/**
 * Produk Detail Logic (Card Based UI - Hybrid Blade/JS)
 */
import '../css/produk.css';

document.addEventListener('DOMContentLoaded', () => {
    initInteraction();
});

function initInteraction() {
    // Handle Quantity Buttons
    document.querySelectorAll('.increase').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            const input = btn.parentElement.querySelector('.quantity');
            const code = btn.closest('.seed-class-card')?.dataset.seedClassCode;
            const step = code === 'FS' ? 5 : 1;
            const current = parseInt(input.value) || 0;
            input.value = current + step;
            updateSelection(btn.closest('.seed-class-card'));
        });
    });

    document.querySelectorAll('.decrease').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            const input = btn.parentElement.querySelector('.quantity');
            const code = btn.closest('.seed-class-card')?.dataset.seedClassCode;
            const step = code === 'FS' ? 5 : 1;
            const current = parseInt(input.value) || 0;
            const min = parseInt(input.min) || 1;
            if (current > min) {
                input.value = current - step;
                updateSelection(btn.closest('.seed-class-card'));
            }
        });
    });

    // Handle Card Selection
    // (Already handled by onclick="selectClass" in Blade, but we need the function globally)
    window.selectClass = function(code) {
        const cards = document.querySelectorAll('.seed-class-card');
        cards.forEach(c => {
            if (c.dataset.seedClassCode === code) {
                c.classList.add('border-blue-500', 'ring-2', 'ring-blue-200');
                c.classList.remove('border-gray-200');
                
                // Update hidden inputs
                document.getElementById('selected-lot-id').value = ''; // Not used in this mode (auto-resolve)
                // We need to store the CLASS ID and QUANTITY
                // But existing hidden inputs are: selected-lot-id, selected-qty.
                // We might need to change addToCart logic to use class id.
                
                const qtyInput = c.querySelector('.quantity');
                document.getElementById('selected-qty').value = qtyInput.value;
                
                // We store class ID in a data attribute or new hidden input?
                // Let's just use a global variable or read from DOM in addToCart
                window.selectedClassId = c.dataset.seedClassId;
                window.selectedClassCode = code;
                window.selectedClassName = c.dataset.seedClassName;
                window.selectedPrice = parseFloat(c.dataset.price) || 0;
                
                const valid = validateQtyInput(qtyInput, code);
                document.getElementById('btn-add-cart').disabled = !valid;
                document.getElementById('btn-buy-now').disabled = !valid;
            } else {
                c.classList.remove('border-blue-500', 'ring-2', 'ring-blue-200');
                c.classList.add('border-gray-200');
            }
        });
    };
}

function updateSelection(card) {
    if (card.classList.contains('border-blue-500')) {
        const qty = card.querySelector('.quantity').value;
        document.getElementById('selected-qty').value = qty;
    }
}

// Override or Implement addToCartAction
window.addToCartAction = function(isBuyNow) {
    if (!window.selectedClassId) {
        alert('Silakan pilih kelas benih terlebih dahulu.');
        return;
    }

    const qty = document.getElementById('selected-qty').value;
    
    const item = {
        variety_id: window.varietyData.id,
        name: window.varietyData.name,
        price: window.selectedPrice || window.varietyData.base_price, // Use class specific price
        image: window.varietyData.image,
        quantity: parseInt(qty),
        seed_class_id: window.selectedClassId,
        seed_class_code: window.selectedClassCode,
        seed_class_name: window.selectedClassName,
        seed_lot_id: null // Auto-resolve at checkout
    };

    let cart = [];
    try {
        const stored = localStorage.getItem('upbs_cart_v2');
        if (stored) cart = JSON.parse(stored).items || [];
    } catch (e) {}

    // Check if same variety+class exists
    const existing = cart.find(c => c.variety_id == item.variety_id && c.seed_class_id == item.seed_class_id);
    if (existing) {
        existing.quantity += item.quantity;
    } else {
        cart.push(item);
    }

    localStorage.setItem('upbs_cart_v2', JSON.stringify({ items: cart }));

    if (isBuyNow) {
        window.location.href = '/checkout';
    } else {
        // Show success feedback
        const btn = document.getElementById('btn-add-cart');
        const originalText = btn.innerHTML;
        btn.innerHTML = '✓ Masuk Keranjang';
        btn.classList.remove('text-blue-600', 'border-blue-600');
        btn.classList.add('bg-green-600', 'text-white', 'border-green-600');
        
        setTimeout(() => {
            btn.innerHTML = originalText;
            btn.classList.add('text-blue-600', 'border-blue-600');
            btn.classList.remove('bg-green-600', 'text-white', 'border-green-600');
        }, 2000);
        
        // Dispatch event for header cart count update if exists
        window.dispatchEvent(new Event('cart-updated'));
    }
};

window.handleQtyInput = function(el) {
    const card = el.closest('.seed-class-card');
    const code = card?.dataset.seedClassCode;
    const valid = validateQtyInput(el, code);
    if (card.classList.contains('border-blue-500')) {
        document.getElementById('btn-add-cart').disabled = !valid;
        document.getElementById('btn-buy-now').disabled = !valid;
        updateSelection(card);
    }
};

function validateQtyInput(input, code) {
    let val = parseInt(input.value) || 0;
    if (code === 'FS') {
        const ok = val % 5 === 0 && val >= 5;
        const errEl = input.closest('.seed-class-card')?.querySelector('.qty-error');
        if (!ok) {
            if (errEl) errEl.classList.remove('hidden');
            return false;
        } else {
            if (errEl) errEl.classList.add('hidden');
        }
        return true;
    } else {
        const ok = val >= 1;
        const errEl = input.closest('.seed-class-card')?.querySelector('.qty-error');
        if (!ok) {
            if (errEl) { errEl.textContent = 'Jumlah minimal 1'; errEl.classList.remove('hidden'); }
            return false;
        } else {
            if (errEl) errEl.classList.add('hidden');
        }
        return true;
    }
}
