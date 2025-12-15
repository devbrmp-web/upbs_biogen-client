/**
 * Checkout Logic V2
 * Handles Seed Hierarchy, Stock Validation per Lot, and Midtrans
 */
let lastOrderData = [];
const CART_KEY = 'upbs_cart_v2';

const COOKIE_NAME = 'upbs_receiver_data';

document.addEventListener('DOMContentLoaded', () => {
    renderCheckoutCart();
    updatePaymentSummary();
    loadReceiverData(); // Load from cookies

    const cart = getCart();
    if (cart.length === 0) {
        document.getElementById('checkout-cart-items').innerHTML = `
            <div class="text-center py-8 text-gray-500">
                <p class="mb-4">Keranjang belanja Anda kosong.</p>
                <a href="/katalog" class="text-blue-600 hover:underline font-medium">Kembali ke Katalog</a>
            </div>
        `;
        document.getElementById('btn-pay').disabled = true;
    }
    
    // Event Listeners for T&C
    const termsTrigger = document.getElementById('terms-modal-trigger');
    if (termsTrigger) {
        termsTrigger.addEventListener('click', (e) => {
            e.preventDefault();
            document.getElementById('terms-modal').classList.remove('hidden');
        });
    }
});

function saveReceiverData() {
    const data = {
        name: document.getElementById('form-name').value,
        phone: document.getElementById('form-phone').value,
        email: document.getElementById('form-email').value,
        address: document.getElementById('form-address').value,
        province: document.getElementById('form-province').value,
        city: document.getElementById('form-city').value,
        district: document.getElementById('form-district').value,
        postal: document.getElementById('form-postal').value,
        shipping_method: document.querySelector('input[name="shipping_method"]:checked').value
    };

    // Save to cookie (30 days)
    const d = new Date();
    d.setTime(d.getTime() + (30 * 24 * 60 * 60 * 1000));
    const expires = "expires=" + d.toUTCString();
    document.cookie = COOKIE_NAME + "=" + JSON.stringify(data) + ";" + expires + ";path=/";
}

function loadReceiverData() {
    const name = COOKIE_NAME + "=";
    const decodedCookie = decodeURIComponent(document.cookie);
    const ca = decodedCookie.split(';');
    let cookieValue = null;
    
    for(let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) === ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) === 0) {
            cookieValue = c.substring(name.length, c.length);
            break;
        }
    }

    if (cookieValue) {
        try {
            const data = JSON.parse(cookieValue);
            if (data.name) document.getElementById('form-name').value = data.name;
            if (data.phone) document.getElementById('form-phone').value = data.phone;
            if (data.email) document.getElementById('form-email').value = data.email;
            if (data.address) document.getElementById('form-address').value = data.address;
            if (data.province) document.getElementById('form-province').value = data.province;
            if (data.city) document.getElementById('form-city').value = data.city;
            if (data.district) document.getElementById('form-district').value = data.district;
            if (data.postal) document.getElementById('form-postal').value = data.postal;
            if (data.shipping_method) {
                const radio = document.querySelector(`input[name="shipping_method"][value="${data.shipping_method}"]`);
                if (radio) {
                    radio.checked = true;
                    toggleShippingInfo();
                }
            }
            
            // If required fields are filled, show summary state
            if (data.name && data.address && data.city) {
                 document.getElementById('summary-name').textContent = data.name;
                 const methodLabel = data.shipping_method === 'pickup' ? 'Ambil di Tempat' : 'Dikirim';
                 document.getElementById('summary-address').innerHTML = `${data.address}, ${data.city}<br><span class="text-xs font-semibold text-blue-600 bg-blue-50 px-2 py-0.5 rounded mt-1 inline-block">${methodLabel}</span>`;
            } else {
                 // Show form if incomplete
                 toggleReceiverForm();
            }
        } catch (e) {
            console.error('Error parsing cookie', e);
        }
    } else {
        // No cookie, ensure form is visible
        toggleReceiverForm();
    }
}


function getCart() {
    try {
        const stored = localStorage.getItem(CART_KEY);
        if (stored) {
            const parsed = JSON.parse(stored);
            return parsed.items || [];
        }
        return [];
    } catch (e) {
        return [];
    }
}

function formatIDR(number) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(number);
}

function renderCheckoutCart() {
    const items = getCart();
    const container = document.getElementById('checkout-cart-items');
    
    if (items.length === 0) {
        container.innerHTML = '<p class="text-center text-gray-500">Keranjang kosong</p>';
        return;
    }

    container.innerHTML = items.map(item => {
        const itemTotal = item.quantity * item.price;
        
        let badgeClass = 'bg-gray-100 text-gray-800';
        if (item.seed_class_code === 'BS') badgeClass = 'bg-yellow-100 text-yellow-800';
        if (item.seed_class_code === 'FS') badgeClass = 'bg-purple-100 text-purple-800';

        return `
            <div class="flex gap-4 border-b border-gray-100 pb-4 last:border-0 last:pb-0">
                <div class="w-16 h-16 flex-shrink-0 bg-gray-100 rounded-lg overflow-hidden">
                    <img src="${item.image || '/img/placeholder.jpg'}" class="w-full h-full object-cover">
                </div>
                <div class="flex-1">
                    <h3 class="font-medium text-gray-900">${item.name}</h3>
                    
                    <div class="mt-2 space-y-1 text-sm">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center gap-2">
                                <span class="${badgeClass} text-xs font-bold px-2 py-0.5 rounded">
                                    ${item.seed_class_code}
                                </span>
                                <span class="text-gray-600">Lot: ${item.seed_lot_id || 'Auto'}</span>
                            </div>
                            <span class="font-medium text-gray-900">
                                ${item.quantity} kg x ${formatIDR(item.price)}
                            </span>
                        </div>
                        <div class="text-right font-bold text-blue-600">
                            ${formatIDR(itemTotal)}
                        </div>
                    </div>
                </div>
            </div>
        `;
    }).join('');
}

function updatePaymentSummary() {
    const items = getCart();
    let subtotal = 0;

    items.forEach(item => {
        subtotal += (item.quantity * item.price);
    });

    const serviceFee = Math.round(subtotal * 0.01);
    const total = subtotal + serviceFee;

    document.getElementById('summary-subtotal').textContent = formatIDR(subtotal);
    document.getElementById('summary-service-fee').textContent = formatIDR(serviceFee);
    document.getElementById('summary-total').textContent = formatIDR(total);
}

// --- Checkout Logic ---
window.processCheckout = async function() {
    const btn = document.getElementById('btn-pay');
    const errorEl = document.getElementById('checkout-error');
    const loadingEl = document.getElementById('loading-state');

    // Reset UI
    errorEl.classList.add('hidden');

    // 1. Validate Form
    saveReceiverData();
    const name = document.getElementById('form-name').value.trim();
    const phone = document.getElementById('form-phone').value.trim();
    const address = document.getElementById('form-address').value.trim();
    const district = document.getElementById('form-district').value.trim();
    const city = document.getElementById('form-city').value.trim();
    const province = document.getElementById('form-province').value.trim();
    const postal = document.getElementById('form-postal').value.trim();

    if (!name || !phone || !address || !district || !city || !province || !postal) {
        errorEl.textContent = 'Mohon lengkapi data penerima.';
        errorEl.classList.remove('hidden');
        document.getElementById('receiver-summary').classList.add('hidden');
        document.getElementById('receiver-form-container').classList.remove('hidden');
        return;
    }

    // 2. Prepare Payload
    btn.disabled = true;
    btn.classList.add('hidden');
    loadingEl.classList.remove('hidden');

    try {
        const cartItems = getCart();
        const shippingMethod = document.querySelector('input[name="shipping_method"]:checked').value;

        const payload = {
            items: cartItems.map(item => ({
                variety_id: item.variety_id,
                quantity: item.quantity,
                seed_lot_id: item.seed_lot_id || null,
                seed_class_code: item.seed_class_code
            })),
            customer_name: name,
            customer_address: `${address}, Kec. ${district}, ${city}, ${province}, ${postal}`,
            customer_phone: phone,
            customer_email: document.getElementById('form-email').value.trim() || null,
            shipping_method: shippingMethod,
            terms_accepted: document.getElementById('terms-checkbox')?.checked === true
        };

        if (!payload.terms_accepted) {
            throw new Error('Anda harus menyetujui Syarat dan Ketentuan.');
        }

        // 🔥 REQUEST KE BACKEND (HARUS BALIKAN snap_token)
        const response = await window.axios.post('/orders/checkout', payload);
        console.log(response)
        lastOrderData = response?.data?.data?.order; // 💡 simpan di sini
        // Ambil data dari backend
        const snapToken =
            response?.data?.snap_token ||
            response?.data?.token ||
            response?.data?.data?.snap_token;

        const orderCode =
            response?.data?.order_code ||
            response?.data?.data?.order_code;

        if (!snapToken) {
            throw new Error('Gagal mendapatkan token pembayaran.');
        }

        // 🔥 JALANKAN SNAP POPUP
        window.snap.pay(snapToken, {
            onSuccess: async function(result) {
                console.log(result);

                const email = document.getElementById('form-email')?.value?.trim();

                if (lastOrderData) {

                    // ==============================
                    // 🔥 KIRIM EMAIL INVOICE (BARU)
                    // ==============================
                    if (email) {
                        try {
                            await window.axios.post('/orders/send-invoice', {
                                email: email,
                                order: {
                                    order_code: lastOrderData.order_code,
                                    status: lastOrderData.status,
                                    total_amount: lastOrderData.total_amount,
                                    items: lastOrderData.items
                                }
                            });
                        } catch (e) {
                            console.error('Gagal mengirim invoice email', e);
                            // ❗ sengaja tidak throw → tidak ganggu UX
                        }
                    }

                    // ==============================
                    // SIMPAN KE LOCAL STORAGE
                    // ==============================
                    try {
                        let arr = JSON.parse(localStorage.getItem("lastOrderData") || "[]");
                        if (!Array.isArray(arr)) arr = [];

                        arr.push(lastOrderData);
                        localStorage.setItem("lastOrderData", JSON.stringify(arr));
                    } catch (e) {
                        console.error("failed to store local history", e);
                    }

                    // ==============================
                    // SIMPAN KE COOKIE (FALLBACK)
                    // ==============================
                    const d = new Date();
                    d.setTime(d.getTime() + (7 * 24 * 60 * 60 * 1000));
                    const expires = "expires=" + d.toUTCString();
                    document.cookie =
                        "upbs_last_order=" +
                        JSON.stringify(lastOrderData) +
                        ";" +
                        expires +
                        ";path=/;SameSite=Lax";
                }

                localStorage.removeItem(CART_KEY);
                window.location.href =
                    '/cek-pesanan?search=' +
                    (lastOrderData?.order_code || '') +
                    '&method=order_code';
            },
            //     localStorage.removeItem(CART_KEY);
            //     // window.location.href = '/orders/success?order=' + orderCode;
            //     window.location.href = '/cart';
            // },
            onPending: function(result) {
                console.log(result);
                window.location.href = '/orders/pending?order=' + orderCode;
            },
            onError: function(result) {
                console.log(result);
                window.location.href = '/orders/error';
            },
            onClose: function() {
                alert("Anda menutup pop-up pembayaran.");
                btn.disabled = false;
                btn.classList.remove('hidden');
                loadingEl.classList.add('hidden');
            }
        });

    } catch (error) {
        console.error(error);
        let msg = 'Terjadi kesalahan.';
        if (error.response && error.response.data) {
            const d = error.response.data;
            if (d.message) msg = d.message;
        } else if (error.message) {
            msg = error.message;
        }

        errorEl.textContent = msg;
        errorEl.classList.remove('hidden');

        btn.disabled = false;
        btn.classList.remove('hidden');
        loadingEl.classList.add('hidden');
    }
};


// Validasi stok sesuai hierarki 
async function validateStock(items) { 
    for (const item of items) { 
        if (!item.seed_class_id) continue; 

        try {
            const response = await window.axios.get(`/api/varieties/${item.variety_id}/seed-classes/${item.seed_class_id}/seed-lots`); 
            const lots = response.data.data || response.data; 
            
            const availableLot = lots.find(lot => lot.quantity >= item.quantity && lot.is_sellable); 
            
            if (!availableLot) {
                throw new Error(`Stok tidak mencukupi untuk ${item.name} (Kelas ${item.seed_class_code || ''}).`); 
            }
        } catch (e) {
            console.error("Stock check failed", e);
            if (e.message.includes('Stok tidak mencukupi')) throw e;
            throw new Error(`Gagal memverifikasi stok untuk ${item.name}.`);
        }
    } 
}

// --- Helpers for UI ---
window.toggleReceiverForm = function() {
    const summary = document.getElementById('receiver-summary');
    const form = document.getElementById('receiver-form-container');
    summary.classList.toggle('hidden');
    form.classList.toggle('hidden');
};

window.saveReceiverForm = function() {
    saveReceiverData();
    loadReceiverData(); // Update UI state
    // Auto collapse form
    document.getElementById('receiver-summary').classList.remove('hidden');
    document.getElementById('receiver-form-container').classList.add('hidden');
};

window.toggleShippingInfo = function() {
    const delivery = document.querySelector('input[name="shipping_method"][value="delivery"]').checked;
    const infoBox = document.getElementById('delivery-info');
    if (delivery) {
        infoBox.classList.remove('hidden');
    } else {
        infoBox.classList.add('hidden');
    }
};

window.togglePayButtonByTerms = function() {
    const cb = document.getElementById('terms-checkbox');
    const btn = document.getElementById('btn-pay');
    if (cb && btn) {
        btn.disabled = !cb.checked;
    }
};

window.closeTermsModal = function() {
    document.getElementById('terms-modal').classList.add('hidden');
};
