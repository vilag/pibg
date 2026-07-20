document.addEventListener('DOMContentLoaded', function () {
    cargar_conteos();
    cargar_historial();
    cargar_suscripciones();
});

function cargar_conteos() {
    $.get('ajax/push_notificaciones.php', { op: 'contar' }, function (res) {
        if (res && res.ok) {
            $('#push_conteo_ios').text(res.conteos.webpush || 0);
            $('#push_conteo_android').text(res.conteos.fcm || 0);
        }
    }, 'json');
}

function cargar_historial() {
    $.get('ajax/push_notificaciones.php', { op: 'historial' }, function (res) {
        if (!res || !res.ok || !res.envios.length) {
            $('#push_tabla_historial').html('<tr><td colspan="5" class="text-center text-muted">Sin envíos todavía.</td></tr>');
            return;
        }
        var html = res.envios.map(function (e) {
            return '<tr>' +
                '<td>' + e.fecha_envio + '</td>' +
                '<td>' + $('<div>').text(e.titulo).html() + '</td>' +
                '<td>' + $('<div>').text(e.mensaje).html() + '</td>' +
                '<td>' + e.total_destinatarios + '</td>' +
                '<td>' + e.total_exitosos + '</td>' +
                '</tr>';
        }).join('');
        $('#push_tabla_historial').html(html);
    }, 'json');
}

function push_dispositivo_legible(userAgent) {
    userAgent = userAgent || '';
    var plataforma = /iPhone|iPad/i.test(userAgent) ? 'iOS' :
        /Android/i.test(userAgent) ? 'Android' :
        /Windows/i.test(userAgent) ? 'Windows' :
        /Macintosh/i.test(userAgent) ? 'Mac' : 'Desconocido';
    var navegador = /CriOS|Chrome/i.test(userAgent) ? 'Chrome' :
        /Safari/i.test(userAgent) ? 'Safari' :
        /Firefox/i.test(userAgent) ? 'Firefox' : '';
    return navegador ? (plataforma + ' · ' + navegador) : plataforma;
}

function cargar_suscripciones() {
    $.get('ajax/push_notificaciones.php', { op: 'suscripciones' }, function (res) {
        if (!res || !res.ok || !res.suscripciones.length) {
            $('#push_tabla_suscripciones').html('<tr><td colspan="4" class="text-center text-muted">Sin suscripciones todavía.</td></tr>');
            return;
        }
        var html = res.suscripciones.map(function (s) {
            var plataforma = s.tipo === 'fcm' ? 'Android' : 'iOS / Web';
            var estado = s.activo == 1
                ? '<span class="push-badge push-badge-activo">Activa</span>'
                : '<span class="push-badge push-badge-inactivo">Inactiva</span>';
            return '<tr>' +
                '<td>' + s.fecha_creacion + '</td>' +
                '<td>' + plataforma + '</td>' +
                '<td>' + push_dispositivo_legible(s.user_agent) + '</td>' +
                '<td>' + estado + '</td>' +
                '</tr>';
        }).join('');
        $('#push_tabla_suscripciones').html(html);
    }, 'json');
}

function push_enviar_notificacion() {
    var titulo = $('#push_titulo').val().trim();
    var mensaje = $('#push_mensaje').val().trim();
    var url = $('#push_url').val().trim();

    if (!titulo || !mensaje) {
        $('#push_resultado').html('<div class="push-aviso">Escribe un título y un mensaje.</div>');
        return;
    }

    $('#push_resultado').html('<div class="text-muted">Enviando…</div>');

    $.post('ajax/push_notificaciones.php?op=enviar', { titulo: titulo, mensaje: mensaje, url: url }, function (res) {
        if (!res || !res.ok) {
            $('#push_resultado').html('<div class="push-aviso">' + (res && res.msg ? res.msg : 'No se pudo enviar.') + '</div>');
            return;
        }
        var html = '<div class="text-success">Enviado a ' + res.exitosos + ' de ' + res.total + ' suscriptores.</div>';
        (res.avisos || []).forEach(function (a) {
            html += '<div class="push-aviso">' + a + '</div>';
        });
        $('#push_resultado').html(html);
        $('#push_titulo').val('');
        $('#push_mensaje').val('');
        $('#push_url').val('');
        cargar_historial();
        cargar_suscripciones();
        cargar_conteos();
    }, 'json');
}
