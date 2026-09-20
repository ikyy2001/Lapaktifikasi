const CACHE_NAME = 'lapaktifikasi-pwa-v2';
const OFFLINE_URL = '/offline';

const PRECACHE_ASSETS = [
    OFFLINE_URL,
    '/manifest.json',
    '/assets/img/favicon.png',
    '/assets/img/pwa/icon-192x192.png',
    '/assets/img/pwa/icon-512x512.png',
    '/assets/img/pwa/shortcut-katalog.png',
    '/assets/img/pwa/shortcut-riwayat.png',
    '/assets/img/pwa/shortcut-mitra.png',
    '/assets/img/pwa/shortcut-bantuan.png'
];

// 1. Install Event: Pre-cache offline page and core assets
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll(PRECACHE_ASSETS).catch((err) => {
                console.warn('[PWA SW] Pre-cache warning:', err);
            });
        }).then(() => self.skipWaiting())
    );
});

// 2. Activate Event: Clean up outdated caches
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((cache) => {
                    if (cache !== CACHE_NAME) {
                        console.log('[PWA SW] Removing old cache:', cache);
                        return caches.delete(cache);
                    }
                })
            );
        }).then(() => self.clients.claim())
    );
});

// 3. Fetch Event: Smart routing & offline fallback
self.addEventListener('fetch', (event) => {
    const request = event.request;
    const url = new URL(request.url);

    // Skip non-GET requests (POST, PUT, DELETE should never be cached)
    if (request.method !== 'GET') {
        return;
    }

    // Skip dynamic APIs, payment gateways, admin mutation routes, webpush APIs, and chrome extensions
    if (
        url.pathname.startsWith('/api/') ||
        url.pathname.startsWith('/webpush/') ||
        url.pathname.startsWith('/metode_pembayaran/') ||
        url.pathname.startsWith('/admin/') ||
        url.pathname.startsWith('/seller/mutasi') ||
        url.origin.includes('midtrans') ||
        url.origin.includes('tripay') ||
        url.origin.includes('pakasir') ||
        url.protocol.startsWith('chrome-extension')
    ) {
        return;
    }

    // A. Navigation / HTML requests (Pages)
    if (request.mode === 'navigate' || (request.headers.get('accept') && request.headers.get('accept').includes('text/html'))) {
        event.respondWith(
            fetch(request)
                .then((networkResponse) => {
                    // Cache successful page visit for offline reading
                    if (networkResponse && networkResponse.status === 200) {
                        const responseClone = networkResponse.clone();
                        caches.open(CACHE_NAME).then((cache) => {
                            cache.put(request, responseClone);
                        });
                    }
                    return networkResponse;
                })
                .catch(async () => {
                    // Network failed: Try to return cached version of this page
                    const cachedResponse = await caches.match(request);
                    if (cachedResponse) {
                        return cachedResponse;
                    }
                    // If page not cached, show custom friendly Offline screen
                    const offlineFallback = await caches.match(OFFLINE_URL);
                    return offlineFallback || new Response('Koneksi internet terputus. Silakan periksa jaringan Anda.', {
                        headers: { 'Content-Type': 'text/plain; charset=utf-8' }
                    });
                })
        );
        return;
    }

    // B. Static Assets (CSS, JS, Fonts, Images): Stale-While-Revalidate
    if (
        request.destination === 'style' ||
        request.destination === 'script' ||
        request.destination === 'font' ||
        request.destination === 'image' ||
        url.pathname.startsWith('/assets/') ||
        url.pathname.startsWith('/css/')
    ) {
        event.respondWith(
            caches.match(request).then((cachedResponse) => {
                const fetchPromise = fetch(request).then((networkResponse) => {
                    if (networkResponse && networkResponse.status === 200) {
                        const responseToCache = networkResponse.clone();
                        caches.open(CACHE_NAME).then((cache) => {
                            cache.put(request, responseToCache);
                        });
                    }
                    return networkResponse;
                }).catch(() => {
                    // Silently fail fetch if offline, cached response will handle it
                });

                return cachedResponse || fetchPromise;
            })
        );
        return;
    }

    // Default fallback to network
    event.respondWith(
        fetch(request).catch(() => caches.match(request))
    );
});

// 4. Message Event: Skip waiting if instructed
self.addEventListener('message', (event) => {
    if (event.data && event.data.type === 'SKIP_WAITING') {
        self.skipWaiting();
    }
});

// 5. Push Event: Show native Web Push Notification
self.addEventListener('push', (event) => {
    let data = {
        title: 'Lapaktifikasi',
        body: 'Ada pembaruan penting untuk Anda.',
        icon: '/assets/img/pwa/icon-192x192.png',
        badge: '/assets/img/pwa/icon-96x96.png',
        url: '/'
    };

    if (event.data) {
        try {
            const parsed = event.data.json();
            data = Object.assign(data, parsed);
        } catch (e) {
            data.body = event.data.text();
        }
    }

    const options = {
        body: data.body,
        icon: data.icon || '/assets/img/pwa/icon-192x192.png',
        badge: data.badge || '/assets/img/pwa/icon-96x96.png',
        vibrate: [100, 50, 100],
        data: {
            url: data.url || '/'
        },
        actions: [
            { action: 'open', title: 'Buka' },
            { action: 'close', title: 'Tutup' }
        ]
    };

    event.waitUntil(
        self.registration.showNotification(data.title, options)
    );
});

// 6. Notification Click Event: Navigate to target URL
self.addEventListener('notificationclick', (event) => {
    event.notification.close();

    if (event.action === 'close') {
        return;
    }

    const targetUrl = event.notification.data && event.notification.data.url
        ? event.notification.data.url
        : '/';

    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true }).then((windowClients) => {
            for (let client of windowClients) {
                if (client.url === targetUrl && 'focus' in client) {
                    return client.focus();
                }
            }
            if (clients.openWindow) {
                return clients.openWindow(targetUrl);
            }
        })
    );
});

