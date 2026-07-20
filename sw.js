// Service worker minimo: solo habilita que el sitio sea instalable
// (PWA) en Chrome/Android y escritorio. No cachea contenido dinamico
// del sitio para no mostrar informacion desactualizada.
self.addEventListener('install', function (event) {
    self.skipWaiting();
});

self.addEventListener('activate', function (event) {
    self.clients.claim();
});

// Nota: no se intercepta 'fetch' — reenviar las peticiones desde aquí puede
// romper llamadas AJAX del sitio (ej. "Failed to fetch" al enviar
// notificaciones) y no es necesario para que la app sea instalable.

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
    var urlCompleta = new URL(url, self.location.origin).href;

    event.waitUntil(
        self.clients.matchAll({ type: 'window', includeUncontrolled: true }).then(function (clientList) {
            for (var i = 0; i < clientList.length; i++) {
                var cliente = clientList[i];
                if ('focus' in cliente) {
                    // Navega la ventana ya abierta a la URL indicada antes de
                    // enfocarla — antes solo se enfocaba y se quedaba donde ya
                    // estuviera.
                    if ('navigate' in cliente) {
                        cliente.navigate(urlCompleta).catch(function () {});
                    }
                    return cliente.focus();
                }
            }
            if (self.clients.openWindow) { return self.clients.openWindow(urlCompleta); }
        })
    );
});
