const MANUAL_CART_KEY = 'upbs_cart_v2';

function manualGetCartItems() {
    try {
        const stored = localStorage.getItem(MANUAL_CART_KEY);
        if (!stored) return [];
        const parsed = JSON.parse(stored);
        return parsed.items || [];
    } catch (e) {
        return [];
    }
}

function manualFormatIDR(number) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(number);
}

function manualRenderCartSummary() {
    const items = manualGetCartItems();
    const container = document.getElementById('manual-checkout-cart-items');
    const subtotalEl = document.getElementById('manual-summary-subtotal');

    if (!container || !subtotalEl) return;

    if (items.length === 0) {
        container.innerHTML = `
            <div class="text-center py-6 text-gray-500 text-sm">
                Keranjang kosong. Silakan kembali ke katalog untuk memilih benih.
            </div>
        `;
        subtotalEl.textContent = manualFormatIDR(0);
        return;
    }

    let subtotal = 0;

    container.innerHTML = items
        .map((item) => {
            const unitPrice = parseInt(item.price_per_unit) || 0;
            const itemTotal = unitPrice * (item.quantity || 0);
            subtotal += itemTotal;

            let badgeClass = 'bg-gray-100 text-gray-800';
            if (item.seed_class_code === 'BS') badgeClass = 'bg-yellow-100 text-yellow-800';
            if (item.seed_class_code === 'FS') badgeClass = 'bg-purple-100 text-purple-800';

            return `
                <div class="flex gap-4 border-b border-gray-100 pb-4 last:border-0 last:pb-0">
                    <div class="w-14 h-14 flex-shrink-0 bg-gray-100 rounded-lg overflow-hidden">
                        <img src="${item.image || '/img/placeholder.jpg'}" class="w-full h-full object-cover" alt="">
                    </div>
                    <div class="flex-1">
                        <h3 class="font-medium text-gray-900 text-sm md:text-base">${item.name || 'Varietas'}</h3>
                        <div class="mt-2 space-y-1 text-xs md:text-sm">
                            <div class="flex justify-between items-start gap-2">
                                <div class="flex flex-col gap-1">
                                    <div class="flex items-center gap-2">
                                        <span class="${badgeClass} text-[10px] font-bold px-2 py-0.5 rounded">
                                            ${item.seed_class_code || ''}
                                        </span>
                                        <span class="text-gray-600">
                                            ${item.seed_class_name || 'Benih'}
                                        </span>
                                    </div>
                                    <span class="text-[11px] text-gray-500">Lot: ${item.seed_lot_id || 'Auto'}</span>
                                </div>
                                <div class="text-right">
                                    <div class="font-medium text-gray-900">
                                        ${item.quantity || 0} kg x ${manualFormatIDR(unitPrice)}
                                    </div>
                                    <div class="text-xs text-blue-600 font-semibold">
                                        ${manualFormatIDR(itemTotal)}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        })
        .join('');

    subtotalEl.textContent = manualFormatIDR(subtotal);

    const baseTotalEl = document.getElementById('manual-summary-total-base');
    if (baseTotalEl) {
        baseTotalEl.dataset.baseTotal = String(subtotal);
        baseTotalEl.textContent = manualFormatIDR(subtotal);
    }

    manualUpdateUniqueCodeTotal();
}

function manualGenerateUniqueCode() {
    const code = Math.floor(1 + Math.random() * 999);
    return String(code).padStart(3, '0');
}

function manualUpdateUniqueCodeTotal() {
    const baseTotalEl = document.getElementById('manual-summary-total-base');
    const uniqueCodeEl = document.getElementById('manual-summary-unique-code');
    const finalTotalEl = document.getElementById('manual-summary-total-final');

    if (!baseTotalEl || !uniqueCodeEl || !finalTotalEl) return;

    const base = parseInt(baseTotalEl.dataset.baseTotal || '0') || 0;
    let code = uniqueCodeEl.dataset.code;

    if (!code) {
        code = manualGenerateUniqueCode();
        uniqueCodeEl.dataset.code = code;
    }

    const codeNumber = parseInt(code) || 0;
    const finalTotal = base + codeNumber;

    uniqueCodeEl.textContent = code;
    finalTotalEl.textContent = manualFormatIDR(finalTotal);
}

async function manualUploadProof(formEl) {
    const submitBtn = formEl.querySelector('[data-role="manual-submit"]');
    const loadingEl = formEl.querySelector('[data-role="manual-loading"]');
    const errorEl = formEl.querySelector('[data-role="manual-error"]');
    const successEl = formEl.querySelector('[data-role="manual-success"]');

    if (errorEl) {
        errorEl.classList.add('hidden');
        errorEl.textContent = '';
    }
    if (successEl) {
        successEl.classList.add('hidden');
        successEl.textContent = '';
    }

    const proofInput = formEl.querySelector('input[name="proof"]');
    if (!proofInput || !proofInput.files || proofInput.files.length === 0) {
        if (errorEl) {
            errorEl.textContent = 'Mohon unggah bukti transfer terlebih dahulu.';
            errorEl.classList.remove('hidden');
        }
        return;
    }

    const proofFile = proofInput.files[0];
    const proofName = (proofFile && proofFile.name ? String(proofFile.name) : '').toLowerCase();
    const ext = proofName.includes('.') ? proofName.split('.').pop() : '';
    const allowedExt = ['jpg', 'jpeg', 'png', 'pdf'];
    const maxBytes = 2 * 1024 * 1024;
    if (!allowedExt.includes(ext)) {
        if (errorEl) {
            errorEl.textContent = 'Format bukti transfer tidak didukung. Gunakan JPG, PNG, atau PDF.';
            errorEl.classList.remove('hidden');
        }
        return;
    }
    if (proofFile && typeof proofFile.size === 'number' && proofFile.size > maxBytes) {
        if (errorEl) {
            errorEl.textContent = 'Ukuran file terlalu besar. Maksimum 2MB.';
            errorEl.classList.remove('hidden');
        }
        return;
    }

    const orderCode = formEl.querySelector('input[name="order_code"]')?.value || '';
    const bankName = formEl.querySelector('input[name="bank_name"]')?.value || '';
    const accountNumber = formEl.querySelector('input[name="account_number"]')?.value || '';
    const accountHolder = formEl.querySelector('input[name="account_holder"]')?.value || '';

    // Try to get amount and unique_code from hidden inputs first (for order-detail page)
    let amount = formEl.querySelector('input[name="amount"]')?.value;
    let uniqueCode = formEl.querySelector('input[name="unique_code"]')?.value;

    // Fallback to DOM elements if not found in form (for checkout-manual page)
    if (!amount) {
        const baseTotalEl = document.getElementById('manual-summary-total-base');
        amount = baseTotalEl ? parseInt(baseTotalEl.dataset.baseTotal || '0') || 0 : 0;
    }
    
    if (!uniqueCode) {
        const uniqueCodeEl = document.getElementById('manual-summary-unique-code');
        uniqueCode = uniqueCodeEl ? uniqueCodeEl.dataset.code || '000' : '000';
    }

    const formData = new FormData();
    formData.append('order_code', orderCode);
    formData.append('amount', String(amount));
    formData.append('unique_code', String(uniqueCode));
    formData.append('bank_name', bankName);
    formData.append('account_number', accountNumber);
    formData.append('account_holder', accountHolder);
    formData.append('proof', proofInput.files[0]);

    if (submitBtn) {
        submitBtn.disabled = true;
    }
    if (loadingEl) {
        loadingEl.classList.remove('hidden');
    }

    try {
        const response = await window.axios.post('/manual-checkout/upload', formData, {
            baseURL: '', // Force relative URL to bypass global API config
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        });

        if (!response.data || response.data.success === false) {
            const msg =
                (response.data && (response.data.message || response.data.error)) ||
                'Gagal mengunggah bukti bayar.';
            if (errorEl) {
                errorEl.textContent = msg;
                errorEl.classList.remove('hidden');
            }
            return;
        }

        if (successEl) {
            successEl.textContent = 'Bukti bayar berhasil diunggah. Silakan cek status pesanan.';
            successEl.classList.remove('hidden');
        }
    } catch (e) {
        const msg =
            e?.response?.data?.message ||
            e?.message ||
            'Terjadi kesalahan saat mengunggah bukti bayar.';
        if (errorEl) {
            errorEl.textContent = msg;
            errorEl.classList.remove('hidden');
        }
    } finally {
        if (submitBtn) {
            submitBtn.disabled = false;
        }
        if (loadingEl) {
            loadingEl.classList.add('hidden');
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('manual-checkout-cart-items')) {
        manualRenderCartSummary();
    }
});

window.manualRenderCartSummary = manualRenderCartSummary;
window.manualUpdateUniqueCodeTotal = manualUpdateUniqueCodeTotal;
window.manualUploadProof = manualUploadProof;

window.manualStartCheckout = async function () {
    const errorEl = document.getElementById('checkout-error');
    const loadingEl = document.getElementById('loading-state');
    const btn = document.getElementById('btn-pay');

    if (!errorEl || !loadingEl || !btn) return;

    errorEl.classList.add('hidden');

    const name = document.getElementById('form-name')?.value.trim();
    const phone = document.getElementById('form-phone')?.value.trim();
    const address = document.getElementById('form-address')?.value.trim();
    const district = document.getElementById('form-district')?.value.trim();
    const city = document.getElementById('form-city')?.value.trim();
    const province = document.getElementById('form-province')?.value.trim();
    const postal = document.getElementById('form-postal')?.value.trim();

    if (!name || !phone || !address || !district || !city || !province || !postal) {
        errorEl.textContent = 'Mohon lengkapi data penerima.';
        errorEl.classList.remove('hidden');
        const summary = document.getElementById('receiver-summary');
        const form = document.getElementById('receiver-form-container');
        if (summary && form) {
            summary.classList.add('hidden');
            form.classList.remove('hidden');
        }
        return;
    }

    const items = manualGetCartItems();
    if (!items.length) {
        errorEl.textContent = 'Keranjang masih kosong.';
        errorEl.classList.remove('hidden');
        return;
    }

    const payload = {
        customer_name: name,
        customer_phone: phone,
        customer_email: document.getElementById('form-email')?.value.trim() || '',
        customer_address: address,
        customer_district: district,
        customer_city: city,
        customer_province: province,
        customer_postal: postal,
        shipping_method: document.querySelector('input[name="shipping_method"]:checked')?.value || 'pickup',
        courier_name: 'Manual Transfer',
        courier_service: 'Manual',
        items: items.map((item) => ({
            seed_lot_id: item.seed_lot_id,
            variety_id: item.variety_id,
            quantity: item.quantity,
        })),
    };

    btn.disabled = true;
    btn.classList.add('hidden');
    loadingEl.classList.remove('hidden');

    try {
        const response = await window.axios.post('/manual-checkout/process', payload, {
            headers: {
                'Content-Type': 'application/json',
                Accept: 'application/json',
            },
        });

        const data = response.data || {};
        const orderCode =
            data.data?.order_code ||
            data.order_code ||
            data.data?.order?.order_code ||
            null;

        if (!orderCode) {
            throw new Error('Kode order tidak ditemukan dari server.');
        }

        manualRenderCartSummary();

        if (window.Alpine && window.Alpine.store) {
            const store = window.Alpine.store('manualCheckout');
            if (store) {
                store.show(orderCode);
            }
        } else {
            const popup = document.getElementById('manual-checkout-popup');
            if (popup && popup.__manualShow) {
                popup.__manualShow(orderCode);
            } else if (popup) {
                popup.classList.remove('hidden');
            }
        }
    } catch (e) {
        const msg =
            e?.response?.data?.message ||
            e?.message ||
            'Terjadi kesalahan saat membuat pesanan.';
        errorEl.textContent = msg;
        errorEl.classList.remove('hidden');
        btn.disabled = false;
        btn.classList.remove('hidden');
        loadingEl.classList.add('hidden');
        return;
    }

    btn.disabled = false;
    btn.classList.remove('hidden');
    loadingEl.classList.add('hidden');
};

function manualSelectBank(selectEl) {
    const option = selectEl.options[selectEl.selectedIndex];
    const bankName = option.dataset.bankName || 'BANK';
    const accountNumber = option.dataset.accountNumber || '-';
    const accountHolder = option.dataset.accountHolder || '-';

    // Update badge and text
    const badge = document.getElementById('manual-bank-badge');
    const accNum = document.getElementById('manual-bank-account-number');
    const accHolder = document.getElementById('manual-bank-account-holder');
    const nameInput = document.getElementById('manual-bank-name-input');
    const numInput = document.getElementById('manual-account-number-input');
    const holderInput = document.getElementById('manual-account-holder-input');

    if (badge) badge.textContent = bankName.toUpperCase();
    if (accNum) accNum.textContent = accountNumber;
    if (accHolder) accHolder.textContent = accountHolder;

    // Update hidden inputs
    if (nameInput) nameInput.value = bankName;
    if (numInput) numInput.value = accountNumber;
    if (holderInput) holderInput.value = accountHolder;
}

function manualCopySelectedBank() {
    const accNum = document.getElementById('manual-bank-account-number')?.textContent;
    if (!accNum || accNum === '-') return;

    navigator.clipboard.writeText(accNum).then(() => {
        const toast = document.getElementById('manual-copy-toast');
        if (toast) {
            toast.classList.remove('hidden');
            setTimeout(() => toast.classList.add('hidden'), 3000);
        }
    }).catch(err => {
        console.error('Failed to copy: ', err);
        alert('Gagal menyalin nomor rekening.');
    });
}

window.manualSelectBank = manualSelectBank;
window.manualCopySelectedBank = manualCopySelectedBank;
