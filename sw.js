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

// Notificaciones push (Web Push — iOS agregado a inicio, Chrome/escritorio)
self.addEventListener('push', function (event) {
    var datos = { title: 'Primera Iglesia Bautista de Guadalajara', body: '', url: '/' };
    try {
        if (event.data) { datos = Object.assign(datos, event.data.json()); }
    } catch (e) {}

    event.waitUntil(
        self.registration.showNotification(datos.title, {
            body: datos.body,
            icon: 'images/icons/icon-192.png',
            badge: 'images/icons/icon-192.png',
            data: { url: datos.url || '/' }
        })
    );
});

self.addEventListener('notificationclick', function (event) {
    event.notification.close();
    var url = (event.notification.data && event.notification.data.url) || '/';
    event.waitUntil(
        self.clients.matchAll({ type: 'window', includeUncontrolled: true }).then(function (clientList) {
            for (var i = 0; i < clientList.length; i++) {
                if (clientList[i].url.indexOf(url) !== -1 && 'focus' in clientList[i]) {
                    return clientList[i].focus();
                }
            }
            if (self.clients.openWindow) { return self.clients.openWindow(url); }
        })
    );
});
