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
            // WindowClient.navigate() no es confiable en Safari/iOS (la PWA
            // agregada a pantalla de inicio se queda donde ya estaba, aunque
            // "funcione" en Chrome/Android): en vez de navegar una ventana ya
            // abierta, solo se reutiliza si YA está en la URL exacta de la
            // notificación; para cualquier otro caso (o si no hay ventana
            // abierta) se abre una nueva directamente ahí, que es el patrón
            // que sí funciona en todas las plataformas.
            for (var i = 0; i < clientList.length; i++) {
                var cliente = clientList[i];
                if (cliente.url === urlCompleta && 'focus' in cliente) {
                    return cliente.focus();
                }
            }
            if (self.clients.openWindow) { return self.clients.openWindow(urlCompleta); }
        })
    );
});
