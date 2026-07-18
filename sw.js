// Service worker minimo: solo habilita que el sitio sea instalable
// (PWA) en Chrome/Android y escritorio. No cachea contenido dinamico
// del sitio para no mostrar informacion desactualizada.
self.addEventListener('install', function (event) {
    self.skipWaiting();
});

self.addEventListener('activate', function (event) {
    self.clients.claim();
});

self.addEventListener('fetch', function (event) {
    event.respondWith(fetch(event.request));
});
