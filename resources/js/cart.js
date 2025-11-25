// resources/js/cart.js
// Marketplace-style cart + qty modal (delegated events, localStorage)
// Safe to load via Vite (use once in layout)

(() => {
  // ---------- Helpers ----------
  const qs = s => document.querySelector(s);
  const qsa = s => Array.from(document.querySelectorAll(s));
  const formatIDR = n => (typeof n === 'number' ? n.toLocaleString('id-ID') : parseInt(n || 0).toLocaleString('id-ID'));

  // ---------- DOM targets ----------
  const buyModal = qs('#buyModal');
  const buyModalName = qs('#buyModalName');
  const buyModalMin = qs('#buyModalMin');
  const buyModalPrice = qs('#buyModalPrice');
  const buyModalTotal = qs('#buyModalTotal');
  const buyModalNote = qs('#buyModalNote');
  const qtyInput = qs('#qtyInput');
  const qtyPlus = qs('#qtyPlus');
  const qtyMinus = qs('#qtyMinus');
  const buyModalAdd = qs('#buyModalAdd');
  const buyModalBuyNow = qs('#buyModalBuyNow');
  const buyModalCancel = qs('#buyModalCancel');
  const buyModalClose = qs('#buyModalClose');

  const cartModal = qs('#cartModal');
  const cartItemsWrap = qs('#cartItems');
  const cartGrandTotalEl = qs('#cartGrandTotal');
  const cartTotalItemEl = qs('#cartTotalItem');
  const clearCartBtn = qs('#clearCartBtn');
  const closeCartBtn = qs('#closeCartBtn');
  const cartCheckoutBtn = qs('#cartCheckoutBtn');

  // ---------- FIXED BADGE ----------
  const cartIcon = qs('#cartIcon'); // FIXED
  let cartBadge = qs('#cartBadge'); // FIXED

  if (!cartBadge && cartIcon) {
    cartBadge = document.createElement('span');
    cartBadge.id = 'cartBadge';
    cartBadge.className =
      'absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold rounded-full px-1 py-[1px]';
    cartIcon.style.position = 'relative';
    cartIcon.appendChild(cartBadge);
  }

  // ---------- LocalStorage cart ----------
  function getCart() {
    try {
      return JSON.parse(localStorage.getItem('cart')) || [];
    } catch (e) {
      return [];
    }
  }
  function saveCart(cart) {
    localStorage.setItem('cart', JSON.stringify(cart));
    renderCart(); // keep UI in sync
  }

  function findIndex(cart, id) {
    return cart.findIndex(i => String(i.id) === String(id));
  }

  // ---------- Render cart ----------
  function renderCart() {
    const cart = getCart();
    cartItemsWrap.innerHTML = '';

    if (cart.length === 0) {
      cartItemsWrap.innerHTML = `<p class="text-gray-600 text-center py-6">Keranjang kamu kosong 🛍️</p>`;
      cartGrandTotalEl.textContent = 'Rp 0';
      cartTotalItemEl.textContent = '0';
      updateBadge();
      return;
    }

    let grand = 0;
    let totalItems = 0;

    cart.forEach(item => {
      const itemTotal = (item.harga || 0) * (item.qty || 0);
      grand += itemTotal;
      totalItems += item.qty || 0;

      const node = document.createElement('div');
      node.className = 'cart-item flex items-center bg-white/60 border border-gray-200 rounded-xl p-3';
      node.dataset.id = item.id;
      node.innerHTML = `
        <div class="w-16 h-16 rounded-lg overflow-hidden flex-shrink-0">
          <img src="${item.gambar}" class="object-cover w-full h-full">
        </div>
        <div class="ml-3 flex-1">
          <p class="font-semibold text-sm text-gray-900">${escapeHtml(item.nama)}</p>
          <p class="text-xs text-gray-600">Qty: ${item.qty} kg</p>
          <p class="text-sm text-green-700 font-semibold">Rp ${formatIDR(itemTotal)}</p>
        </div>
        <div class="flex flex-col items-end gap-2 ml-2">
          <div class="flex items-center gap-1">
            <button class="decrease-item text-gray-600 px-2 py-1 rounded hover:bg-gray-100" data-id="${item.id}">−</button>
            <button class="increase-item text-gray-600 px-2 py-1 rounded hover:bg-gray-100" data-id="${item.id}">+</button>
          </div>
          <button class="remove-item text-red-600" data-id="${item.id}" title="Hapus item">
            <i class="fa fa-times"></i>
          </button>
        </div>
      `;
      cartItemsWrap.appendChild(node);
    });

    cartGrandTotalEl.textContent = `Rp ${formatIDR(grand)}`;
    cartTotalItemEl.textContent = String(totalItems);
    updateBadge();
  }

  function updateBadge() {
    if (!cartBadge) return;
    const cart = getCart();
    const totalQty = cart.reduce((s, it) => s + (it.qty || 0), 0);
    cartBadge.textContent = totalQty;
    cartBadge.style.display = totalQty > 0 ? 'inline-block' : 'none';
  }

  // ---------- Escaping helper ----------
  function escapeHtml(s = '') {
    return String(s)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;');
  }

  // ---------- Qty Modal Logic ----------
  let activeProduct = null;
  let buyNowFlag = false;

  function openBuyModal(product, buyNow = false) {
    activeProduct = product;
    buyNowFlag = !!buyNow;

    const min = Number(product.minimum || 1);
    qtyInput.value = String(min);
    buyModalName.textContent = product.nama;
    buyModalMin.textContent = `Minimum: ${min} kg`;
    buyModalPrice.textContent = `Rp ${formatIDR(Number(product.harga || 0))}`;
    updateModalTotal();
    validateModal();

    buyModalBuyNow.disabled = false;

    buyModal.classList.remove('hidden');
    qtyInput.focus();
  }

  function closeBuyModal() {
    buyModal.classList.add('hidden');
    activeProduct = null;
    buyNowFlag = false;
  }

  function updateModalTotal() {
    if (!activeProduct) return;
    const q = Math.max(1, parseInt(qtyInput.value || 0));
    const total = q * (Number(activeProduct.harga) || 0);
    buyModalTotal.textContent = `Rp ${formatIDR(total)}`;
  }

  function validateModal() {
    if (!activeProduct) return;
    const q = Math.max(0, parseInt(qtyInput.value || 0));
    const min = Number(activeProduct.minimum || 1);
    if (q >= min) {
      buyModalAdd.disabled = false;
      buyModalBuyNow.disabled = false;
      buyModalNote.classList.add('hidden');
    } else {
      buyModalAdd.disabled = true;
      buyModalBuyNow.disabled = true;
      buyModalNote.textContent = `Jumlah harus ≥ ${min} kg`;
      buyModalNote.classList.remove('hidden');
    }
  }

  // ---------- Cart operations ----------
  function addOrIncrease(productObj, qtyToAdd = 1) {
    const cart = getCart();
    const idx = findIndex(cart, productObj.id);
    if (idx > -1) {
      cart[idx].qty = Number(cart[idx].qty || 0) + Number(qtyToAdd);
    } else {
      const pushItem = {
        id: productObj.id,
        nama: productObj.nama,
        harga: Number(productObj.harga || 0),
        gambar: productObj.gambar || '',
        qty: Number(qtyToAdd || 0)
      };
      cart.push(pushItem);
    }
    saveCart(cart);
  }

  function removeItemById(id) {
    const cart = getCart().filter(i => String(i.id) !== String(id));
    saveCart(cart);
  }

  function changeQtyById(id, delta) {
    const cart = getCart();
    const idx = findIndex(cart, id);
    if (idx === -1) return;
    cart[idx].qty = Math.max(0, Number(cart[idx].qty || 0) + Number(delta));
    if (cart[idx].qty <= 0) {
      cart.splice(idx, 1);
    }
    saveCart(cart);
  }

  function clearCart() {
    localStorage.removeItem('cart');
    renderCart();
  }

  // ---------- Delegated Events ----------
  document.addEventListener('click', (e) => {
    const addBtn = e.target.closest('.add-to-cart');
    if (addBtn) {
      const product = {
        id: addBtn.dataset.id,
        nama: addBtn.dataset.nama,
        harga: Number(addBtn.dataset.harga || 0),
        gambar: addBtn.dataset.gambar || '',
        minimum: Number(addBtn.dataset.minimum || 1)
      };
      openBuyModal(product, false);
      return;
    }

    const buyNowBtn = e.target.closest('.buy-now');
    if (buyNowBtn) {
      const product = {
        id: buyNowBtn.dataset.id,
        nama: buyNowBtn.dataset.nama,
        harga: Number(buyNowBtn.dataset.harga || 0),
        gambar: buyNowBtn.dataset.gambar || '',
        minimum: Number(buyNowBtn.dataset.minimum || 1)
      };
      openBuyModal(product, true);
      return;
    }

    if (e.target.closest('#buyModalClose') || e.target.closest('#buyModalCancel')) {
      closeBuyModal();
      return;
    }

    if (e.target.closest('#qtyPlus')) {
      qtyInput.value = String(Math.max(1, (parseInt(qtyInput.value || '0') || 0) + 1));
      updateModalTotal();
      validateModal();
      return;
    }

    if (e.target.closest('#qtyMinus')) {
      qtyInput.value = String(Math.max(1, (parseInt(qtyInput.value || '0') || 0) - 1));
      updateModalTotal();
      validateModal();
      return;
    }

    if (e.target.closest('#buyModalAdd')) {
      if (!activeProduct) return;
      const qty = Math.max(1, parseInt(qtyInput.value || '0'));
      addOrIncrease(activeProduct, qty);
      closeBuyModal();
      renderCart();
      return;
    }

    if (e.target.closest('#buyModalBuyNow')) {
      if (!activeProduct) return;
      const qty = Math.max(1, parseInt(qtyInput.value || '0'));
      addOrIncrease(activeProduct, qty);
      closeBuyModal();
      renderCart();
      window.location.href = '/checkout';
      return;
    }

    if (e.target.closest('#closeCartBtn')) {
      cartModal.classList.add('hidden');
      return;
    }

    if (e.target.closest('#clearCartBtn')) {
      if (confirm('Yakin ingin menghapus semua item di keranjang?')) {
        clearCart();
      }
      return;
    }

    if (e.target.closest('.remove-item')) {
      const id = e.target.closest('.remove-item').dataset.id;
      removeItemById(id);
      return;
    }

    if (e.target.closest('.increase-item')) {
      const id = e.target.closest('.increase-item').dataset.id;
      changeQtyById(id, +1);
      return;
    }

    if (e.target.closest('.decrease-item')) {
      const id = e.target.closest('.decrease-item').dataset.id;
      changeQtyById(id, -1);
      return;
    }

    if (e.target.closest('#cartCheckoutBtn')) {
      window.location.href = '/checkout';
      return;
    }

    // ---------- FIXED OPEN CART ----------
    if (e.target.closest('#cartIcon')) {  // FIXED
      renderCart();
      cartModal.classList.toggle('hidden');
      return;
    }
  });

  // ---------- Qty input events ----------
  if (qtyInput) {
    qtyInput.addEventListener('input', () => {
      qtyInput.value = qtyInput.value.replace(/[^\d]/g, '') || '0';
      if (qtyInput.value === '0') qtyInput.value = '0';
      updateModalTotal();
      validateModal();
    });
  }

  // ---------- Escape key handler ----------
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      if (!buyModal.classList.contains('hidden')) closeBuyModal();
      if (!cartModal.classList.contains('hidden')) cartModal.classList.add('hidden');
    }
  });

  // ---------- Initialize ----------
  renderCart();
})();
