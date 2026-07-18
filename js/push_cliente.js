var PUSH_VAPID_PUBLIC_KEY = 'BBXn4Mxl-umN81YnYEdOo4o1fa9Mps3ekSmmxLBvepS0KsV7KAtvqWouTEei79mPqi1NiK5vTjZNVdiOsUScd_Y';

function push_urlBase64ToUint8Array(base64String) {
    var padding = '='.repeat((4 - base64String.length % 4) % 4);
    var base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
    var rawData = window.atob(base64);
    var outputArray = new Uint8Array(rawData.length);
    for (var i = 0; i < rawData.length; ++i) {
        outputArray[i] = rawData.charCodeAt(i);
    }
    return outputArray;
}

function push_esNativo() {
    try {
        return !!(window.Capacitor && typeof window.Capacitor.isNativePlatform === 'function' && window.Capacitor.isNativePlatform());
    } catch (e) {
        return false;
    }
}

function push_soportado() {
    if (push_esNativo()) return true;
    return ('serviceWorker' in navigator) && ('PushManager' in window) && ('Notification' in window);
}

function push_yaActivo(callback) {
    if (push_esNativo()) {
        callback(false); // Se valida del lado nativo al registrar; no hay estado previo simple aquí.
        return;
    }
    if (!push_soportado()) { callback(false); return; }
    navigator.serviceWorker.ready.then(function (reg) {
        reg.pushManager.getSubscription().then(function (sub) {
            callback(!!sub && Notification.permission === 'granted');
        });
    }).catch(function () { callback(false); });
}

function push_activar(onExito, onError) {
    if (!push_soportado()) {
        if (onError) onError('Tu navegador no soporta notificaciones.');
        return;
    }

    if (push_esNativo()) {
        push_activarNativo(onExito, onError);
        return;
    }

    Notification.requestPermission().then(function (permiso) {
        if (permiso !== 'granted') {
            if (onError) onError('No se concedió el permiso de notificaciones.');
            return;
        }
        navigator.serviceWorker.ready.then(function (reg) {
            reg.pushManager.subscribe({
                userVisibleOnly: true,
                applicationServerKey: push_urlBase64ToUint8Array(PUSH_VAPID_PUBLIC_KEY)
            }).then(function (sub) {
                var json = sub.toJSON();
                $.post('push_suscribir.php', {
                    op: 'guardar_webpush',
                    endpoint: json.endpoint,
                    p256dh: json.keys.p256dh,
                    auth: json.keys.auth
                }, function (res) {
                    if (res && res.ok) { if (onExito) onExito(); }
                    else if (onError) onError('No se pudo guardar la suscripción.');
                }, 'json');
            }).catch(function (err) {
                if (onError) onError('No se pudo activar: ' + err.message);
            });
        });
    });
}

function push_activarNativo(onExito, onError) {
    // Requiere el plugin @capacitor/push-notifications (pendiente de credenciales
    // de Firebase). Si no está disponible, se avisa en vez de fallar en silencio.
    try {
        var PushNotifications = window.Capacitor.Plugins && window.Capacitor.Plugins.PushNotifications;
        if (!PushNotifications) {
            if (onError) onError('Las notificaciones de Android aún no están disponibles en esta versión de la app.');
            return;
        }
        PushNotifications.requestPermissions().then(function (resultado) {
            if (resultado.receive !== 'granted') {
                if (onError) onError('No se concedió el permiso de notificaciones.');
                return;
            }
            PushNotifications.addListener('registration', function (token) {
                $.post('push_suscribir.php', { op: 'guardar_fcm', token: token.value }, function (res) {
                    if (res && res.ok) { if (onExito) onExito(); }
                    else if (onError) onError('No se pudo guardar la suscripción.');
                }, 'json');
            });
            PushNotifications.addListener('registrationError', function () {
                if (onError) onError('No se pudo registrar el dispositivo para notificaciones.');
            });
            PushNotifications.register();
        });
    } catch (e) {
        if (onError) onError('Las notificaciones de Android aún no están disponibles en esta versión de la app.');
    }
}
