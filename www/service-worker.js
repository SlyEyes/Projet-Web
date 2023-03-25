const VERSION = 'v1';
const urlsToCache = [
    '/resources/pwa/offline.html',
    '/resources/pwa/offline.css',
    '/resources/pwa/offline.js',
];


self.addEventListener('install', evt => {
    self.skipWaiting();
    evt.waitUntil((async () => {
        const cache = await caches.open(VERSION);
        await cache.addAll(urlsToCache);
    })());
});


self.addEventListener('activate', evt => {
    self.clients.claim();
    evt.waitUntil((async () => {
        await Promise.all((await caches.keys()).map(key => {
            if (!key.includes(VERSION))
                return caches.delete(key);
        }));
    })());
});


self.addEventListener('fetch', evt => {
    evt.respondWith((async () => {
        try {
            return await fetch(evt.request);
        } catch (err) {
            const cache = await caches.open(VERSION);
            return (await cache.match(evt.request))
                ?? (await cache.match('/resources/pwa/offline.html'));
        }
    })());
});
