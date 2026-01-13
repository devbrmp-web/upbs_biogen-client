const NS = 'upbs_cache:';
const INDEX_KEY = `${NS}__index__`;
const MAX_ITEMS = 100;

function now() {
  return Date.now();
}

function readIndex() {
  try {
    return JSON.parse(localStorage.getItem(INDEX_KEY) || '[]');
  } catch {
    return [];
  }
}

function writeIndex(index) {
  try {
    localStorage.setItem(INDEX_KEY, JSON.stringify(index));
  } catch {}
}

function touchIndex(key) {
  const idx = readIndex().filter(k => k !== key);
  idx.unshift(key);
  if (idx.length > MAX_ITEMS) {
    const evict = idx.splice(MAX_ITEMS);
    evict.forEach(k => {
      localStorage.removeItem(NS + k);
    });
  }
  writeIndex(idx);
}

export function set(key, data) {
  const payload = {
    ts: now(),
    data
  };
  try {
    localStorage.setItem(NS + key, JSON.stringify(payload));
    touchIndex(key);
  } catch {}
}

export function get(key, ttlMs) {
  try {
    const raw = localStorage.getItem(NS + key);
    if (!raw) return null;
    const payload = JSON.parse(raw);
    if (!payload || typeof payload.ts !== 'number') return null;
    if (typeof ttlMs === 'number' && ttlMs > 0) {
      if ((now() - payload.ts) > ttlMs) return null;
    }
    touchIndex(key);
    return payload.data;
  } catch {
    return null;
  }
}

export function clear(key) {
  try {
    localStorage.removeItem(NS + key);
    const idx = readIndex().filter(k => k !== key);
    writeIndex(idx);
  } catch {}
}

export function clearAll() {
  try {
    const idx = readIndex();
    idx.forEach(k => localStorage.removeItem(NS + k));
    localStorage.removeItem(INDEX_KEY);
  } catch {}
}

if (typeof window !== 'undefined') {
  window.UpbsCache = {
    set,
    get,
    clear,
    clearAll
  };
}

