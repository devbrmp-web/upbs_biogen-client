const CACHE_NAME = 'upbs-images-v1';

self.addEventListener('install', (event) => {
  self.skipWaiting();
});

self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then(keys => Promise.all(
      keys.filter(k => ![CACHE_NAME].includes(k)).map(k => caches.delete(k))
    ))
  );
});

// Stale-while-revalidate for images
self.addEventListener('fetch', (event) => {
  const req = event.request;
  if (req.method !== 'GET') return;

  const dest = req.destination;
  const url = new URL(req.url);

  // Only cache images from storage and common image extensions
  const isImage = dest === 'image' || /\.(png|jpg|jpeg|gif|webp|svg)$/i.test(url.pathname);
  const isStorage = url.pathname.startsWith('/storage/');

  if (isImage || isStorage) {
    event.respondWith(
      caches.open(CACHE_NAME).then(async (cache) => {
        const cached = await cache.match(req);
        const network = fetch(req).then((res) => {
          // clone and put only successful responses
          if (res && res.status === 200) {
            cache.put(req, res.clone());
          }
          return res;
        }).catch(() => cached);
        return cached || network;
      })
    );
  }
});

