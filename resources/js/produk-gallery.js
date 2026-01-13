document.addEventListener('DOMContentLoaded', () => {
  const mainImg = document.getElementById('mainImage');
  const thumbImgs = Array.from(document.querySelectorAll('#thumbnailsScroll .thumbnail-item img'));
  if (!mainImg || thumbImgs.length === 0) return;

  let gallery = thumbImgs.map(img => img.dataset.largeUrl || img.src);
  let idx = Math.max(0, gallery.indexOf(mainImg.src));
  let startX = 0;

  mainImg.addEventListener('touchstart', (e) => {
    startX = e.changedTouches[0].screenX;
  }, { passive: true });

  mainImg.addEventListener('touchend', (e) => {
    const delta = e.changedTouches[0].screenX - startX;
    if (Math.abs(delta) < 40) return;
    if (delta < 0 && idx < gallery.length - 1) idx++;
    else if (delta > 0 && idx > 0) idx--;

    const nextUrl = gallery[idx];
    let thumbEl = null;
    for (const img of thumbImgs) {
      const candidate = img.dataset.largeUrl || img.src;
      if (candidate === nextUrl) { thumbEl = img.parentElement; break; }
    }
    if (typeof window.changeMainImage === 'function') {
      window.changeMainImage(nextUrl, thumbEl);
    }
  }, { passive: true });
});
