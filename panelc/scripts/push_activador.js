// Botón "Activar en este dispositivo" — compartido entre
// panelc/push_notificaciones.php (herramientas de admin) y
// panelc/mis_notificaciones.php (cualquier usuario del panel). Ambas
// páginas suscriben el dispositivo actual a la cuenta de la sesión mediante
// panelc/ajax/push_suscribir_usuario.php, y esperan en su HTML los mismos
// ids: #push_admin_btn y #push_admin_resultado.
document.addEventListener('DOMContentLoaded', function () {
    push_activador_inicializar();
});

function push_activador_inicializar() {
    if (!('serviceWorker' in navigator)) { return; }
    // El sitio público registra este mismo sw.js desde js/custom.js, pero el
    // panel es un árbol aparte que no lo carga — hay que registrarlo aquí
    // para que navigator.serviceWorker.ready se resuelva en esta página.
    navigator.serviceWorker.register('../sw.js').catch(function () {});

    if (typeof push_yaActivo !== 'function') { return; }
    push_yaActivo(function (activo) {
        if (activo) {
            $('#push_admin_btn').hide();
            $('#push_admin_resultado').html('<div class="text-success">Ya tienes activadas las notificaciones en este dispositivo.</div>');
        }
    });
}

function push_admin_activar() {
    var btn = $('#push_admin_btn');
    var resultado = $('#push_admin_resultado');
    btn.prop('disabled', true);
    resultado.html('<div class="text-muted">Activando…</div>');

    push_activar(function () {
        btn.hide();
        resultado.html('<div class="text-success">Notificaciones activadas en este dispositivo.</div>');
    }, function (msg) {
        btn.prop('disabled', false);
        resultado.html('<div class="push-aviso">' + msg + '</div>');
    }, 'ajax/push_suscribir_usuario.php');
}
