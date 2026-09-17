document.addEventListener('DOMContentLoaded', function () {
    cargar_conteos();
    cargar_historial();
    cargar_suscripciones();
    cargar_eventos();
    push_admin_inicializar();
});

// Claves de eventos donde cambiar el destino a "todos" tiene una implicación
// de privacidad (contenido que hoy es solo para el admin dejaría de serlo).
var PUSH_EVENTOS_RIESGO_TODOS = {
    peticion_oracion: 'Esto transmitirá las peticiones de oración a TODOS los suscriptores del sitio, no solo a ti.'
};

function push_eventos_escape(texto) {
    return $('<div>').text(texto || '').html();
}

function cargar_eventos() {
    $.get('ajax/push_notificaciones.php', { op: 'eventos_listar' }, function (res) {
        if (!res || !res.ok || !res.eventos.length) {
            $('#push_eventos_lista').html('<div class="text-muted">No hay eventos configurables.</div>');
            return;
        }
        var html = res.eventos.map(push_eventos_render_card).join('');
        $('#push_eventos_lista').html(html);
        res.eventos.forEach(function (evento) {
            // .val() asigna la propiedad del DOM directamente — a diferencia
            // de interpolar en value="...", no se rompe si el título trae
            // comillas.
            $('#push_eventos_lista .push-evento-card[data-clave="' + evento.clave + '"] .push-evento-titulo').val(evento.titulo);
        });
        $('#push_eventos_lista .push-evento-destino').trigger('change');
    }, 'json').fail(function () {
        $('#push_eventos_lista').html('<div class="push-aviso">No se pudo cargar la configuración.</div>');
    });
}

function push_eventos_render_card(evento) {
    var clave = evento.clave;
    var checked = evento.activo == 1 ? 'checked' : '';
    var optTodos = evento.destino === 'todos' ? 'selected' : '';
    var optAdmin = evento.destino === 'admin' ? 'selected' : '';

    return '' +
        '<div class="push-evento-card" data-clave="' + clave + '">' +
            '<div class="push-evento-header">' +
                '<label style="margin:0;"><input type="checkbox" class="push-evento-activo" ' + checked + '> ' +
                    '<strong>' + push_eventos_escape(evento.nombre_legible) + '</strong></label>' +
            '</div>' +
            '<div class="form-group">' +
                '<label>¿A quién llega?</label>' +
                '<select class="form-control push-evento-destino">' +
                    '<option value="todos" ' + optTodos + '>Todos los suscriptores</option>' +
                    '<option value="admin" ' + optAdmin + '>Solo tú (administrador)</option>' +
                '</select>' +
                '<div class="push-evento-advertencia push-aviso" style="display:none;"></div>' +
            '</div>' +
            '<div class="form-group">' +
                '<label>Título</label>' +
                '<input type="text" class="form-control push-evento-titulo" maxlength="150">' +
            '</div>' +
            '<div class="form-group">' +
                '<label>Mensaje</label>' +
                '<textarea class="form-control push-evento-mensaje" rows="2" maxlength="255">' + push_eventos_escape(evento.mensaje) + '</textarea>' +
            '</div>' +
            (evento.variables ? '<div class="push-evento-variables">Variables disponibles: ' + push_eventos_escape(evento.variables) + '</div>' : '') +
            '<button type="button" class="push-btn-enviar push-evento-guardar" style="margin-top:10px;">Guardar</button> ' +
            '<span class="push-evento-resultado"></span>' +
        '</div>';
}

$(document).on('change', '.push-evento-destino', function () {
    var card = $(this).closest('.push-evento-card');
    var clave = card.data('clave');
    var aviso = card.find('.push-evento-advertencia');
    var riesgo = PUSH_EVENTOS_RIESGO_TODOS[clave];

    if ($(this).val() === 'todos' && riesgo) {
        aviso.text('⚠️ ' + riesgo).show();
    } else {
        aviso.hide();
    }
});

$(document).on('click', '.push-evento-guardar', function () {
    var btn = $(this);
    var card = btn.closest('.push-evento-card');
    var resultado = card.find('.push-evento-resultado');
    var titulo = card.find('.push-evento-titulo').val().trim();
    var mensaje = card.find('.push-evento-mensaje').val().trim();

    if (!titulo || !mensaje) {
        resultado.removeClass('push-evento-guardado').addClass('push-evento-error').text('El título y el mensaje son obligatorios.');
        return;
    }

    btn.prop('disabled', true);
    resultado.removeClass('push-evento-error push-evento-guardado').text('Guardando…');

    $.post('ajax/push_notificaciones.php?op=eventos_guardar', {
        clave: card.data('clave'),
        activo: card.find('.push-evento-activo').is(':checked') ? 1 : 0,
        destino: card.find('.push-evento-destino').val(),
        titulo: titulo,
        mensaje: mensaje
    }, function (res) {
        btn.prop('disabled', false);
        if (res && res.ok) {
            resultado.removeClass('push-evento-error').addClass('push-evento-guardado').text('Guardado.');
        } else {
            resultado.removeClass('push-evento-guardado').addClass('push-evento-error').text((res && res.msg) || 'No se pudo guardar.');
        }
    }, 'json').fail(function () {
        btn.prop('disabled', false);
        resultado.removeClass('push-evento-guardado').addClass('push-evento-error').text('No se pudo guardar.');
    });
});

function push_admin_inicializar() {
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
        cargar_suscripciones();
    }, function (msg) {
        btn.prop('disabled', false);
        resultado.html('<div class="push-aviso">' + msg + '</div>');
    }, 'ajax/push_suscribir_admin.php');
}

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
            $('#push_tabla_suscripciones').html('<tr><td colspan="5" class="text-center text-muted">Sin suscripciones todavía.</td></tr>');
            return;
        }
        var html = res.suscripciones.map(function (s) {
            var plataforma = s.tipo === 'fcm' ? 'Android' : 'iOS / Web';
            var estado = s.activo == 1
                ? '<span class="push-badge push-badge-activo">Activa</span>'
                : '<span class="push-badge push-badge-inactivo">Inactiva</span>';
            var admin = s.es_admin == 1 ? '<span class="push-badge push-badge-activo">Admin</span>' : '';
            return '<tr>' +
                '<td>' + s.fecha_creacion + '</td>' +
                '<td>' + plataforma + '</td>' +
                '<td>' + push_dispositivo_legible(s.user_agent) + '</td>' +
                '<td>' + estado + '</td>' +
                '<td>' + admin + '</td>' +
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
