document.addEventListener('DOMContentLoaded', function () {
    cargar_conteos();
    cargar_historial();
    cargar_suscripciones();
    cargar_usuarios_y_eventos();
});

// Claves de eventos donde cambiar el destino a "todos" tiene una implicación
// de privacidad (contenido que hoy es solo para el admin dejaría de serlo).
var PUSH_EVENTOS_RIESGO_TODOS = {
    peticion_oracion: 'Esto transmitirá las peticiones de oración a TODOS los suscriptores del sitio, no solo a ti.'
};

var PUSH_USUARIOS_CACHE = [];
var PUSH_EVENTOS_CACHE = [];

function push_eventos_escape(texto) {
    return $('<div>').text(texto || '').html();
}

function push_usuario_nombre(u) {
    return [u.nombre, u.apellido_p, u.apellido_m].filter(Boolean).join(' ');
}

function push_destino_legible(evento) {
    if (evento.destino === 'admin') { return 'Cualquier administrador'; }
    if (evento.destino === 'usuario') {
        var u = PUSH_USUARIOS_CACHE.find(function (x) { return x.idusuario == evento.idusuario_destino; });
        return u ? ('Solo ' + push_usuario_nombre(u)) : 'Un usuario específico (sin elegir)';
    }
    return 'Todos los suscriptores';
}

function cargar_usuarios_y_eventos() {
    $.get('ajax/push_notificaciones.php', { op: 'usuarios_listar' }, function (res) {
        PUSH_USUARIOS_CACHE = (res && res.ok) ? res.usuarios : [];
    }, 'json').always(cargar_eventos);
}

function cargar_eventos() {
    $.get('ajax/push_notificaciones.php', { op: 'eventos_listar' }, function (res) {
        if (!res || !res.ok || !res.eventos.length) {
            $('#push_tabla_eventos').html('<tr><td colspan="4" class="text-center text-muted">No hay eventos configurables.</td></tr>');
            return;
        }
        PUSH_EVENTOS_CACHE = res.eventos;
        $('#push_tabla_eventos').html(res.eventos.map(push_eventos_render_fila).join(''));
    }, 'json').fail(function () {
        $('#push_tabla_eventos').html('<tr><td colspan="4" class="push-aviso">No se pudo cargar la configuración.</td></tr>');
    });
}

function push_eventos_render_fila(evento) {
    var estado = evento.activo == 1
        ? '<span class="push-badge push-badge-activo">Activo</span>'
        : '<span class="push-badge push-badge-inactivo">Inactivo</span>';
    var destinoBadgeClase = evento.destino === 'todos' ? 'push-badge-todos' : 'push-badge-admin';

    return '<tr class="push-fila-evento" onclick="push_evento_abrir(\'' + evento.clave + '\')">' +
        '<td>' + push_eventos_escape(evento.nombre_legible) + '</td>' +
        '<td>' + estado + '</td>' +
        '<td><span class="push-badge ' + destinoBadgeClase + '">' + push_eventos_escape(push_destino_legible(evento)) + '</span></td>' +
        '<td><button type="button" class="btn btn-sm btn-outline-secondary" onclick="event.stopPropagation(); push_evento_abrir(\'' + evento.clave + '\');">Editar</button></td>' +
        '</tr>';
}

function push_evento_abrir(clave) {
    var evento = PUSH_EVENTOS_CACHE.find(function (e) { return e.clave === clave; });
    if (!evento) { return; }

    var opcionesUsuarios = PUSH_USUARIOS_CACHE.map(function (u) {
        return '<option value="' + u.idusuario + '">' + push_eventos_escape(push_usuario_nombre(u)) + '</option>';
    }).join('');

    $('#modalEventoPushLabel').text(evento.nombre_legible);
    $('#evento_clave').val(evento.clave);
    $('#evento_activo').prop('checked', evento.activo == 1);
    $('#evento_destino').val(evento.destino);
    $('#evento_usuario').html('<option value="">Elige un usuario…</option>' + opcionesUsuarios);
    if (evento.idusuario_destino) { $('#evento_usuario').val(evento.idusuario_destino); }
    // .val() asigna la propiedad del DOM directamente — a diferencia de
    // interpolar en value="...", no se rompe si el título trae comillas.
    $('#evento_titulo').val(evento.titulo);
    $('#evento_mensaje').val(evento.mensaje);
    $('#evento_variables').text(evento.variables ? ('Variables disponibles: ' + evento.variables) : '');
    $('#evento_resultado').empty();
    $('#evento_guardar_btn').prop('disabled', false);

    $('#evento_destino').trigger('change');
    $('#modalEventoPush').modal('show');
}

$('#evento_destino').on('change', function () {
    var clave = $('#evento_clave').val();
    var aviso = $('#evento_advertencia');
    var riesgo = PUSH_EVENTOS_RIESGO_TODOS[clave];
    var valor = $(this).val();

    if (valor === 'todos' && riesgo) {
        aviso.text('⚠️ ' + riesgo).show();
    } else {
        aviso.hide();
    }

    $('#evento_usuario_wrap').toggle(valor === 'usuario');
});

function push_evento_guardar() {
    var resultado = $('#evento_resultado');
    var btn = $('#evento_guardar_btn');
    var titulo = $('#evento_titulo').val().trim();
    var mensaje = $('#evento_mensaje').val().trim();
    var destino = $('#evento_destino').val();
    var idusuario_destino = $('#evento_usuario').val();

    if (!titulo || !mensaje) {
        resultado.removeClass('push-evento-guardado').addClass('push-evento-error').text('El título y el mensaje son obligatorios.');
        return;
    }
    if (destino === 'usuario' && !idusuario_destino) {
        resultado.removeClass('push-evento-guardado').addClass('push-evento-error').text('Elige a qué usuario se le enviará.');
        return;
    }

    btn.prop('disabled', true);
    resultado.removeClass('push-evento-error push-evento-guardado').text('Guardando…');

    $.post('ajax/push_notificaciones.php?op=eventos_guardar', {
        clave: $('#evento_clave').val(),
        activo: $('#evento_activo').is(':checked') ? 1 : 0,
        destino: destino,
        idusuario_destino: idusuario_destino,
        titulo: titulo,
        mensaje: mensaje
    }, function (res) {
        btn.prop('disabled', false);
        if (res && res.ok) {
            resultado.removeClass('push-evento-error').addClass('push-evento-guardado').text('Guardado.');
            cargar_eventos();
            setTimeout(function () { $('#modalEventoPush').modal('hide'); }, 500);
        } else {
            resultado.removeClass('push-evento-guardado').addClass('push-evento-error').text((res && res.msg) || 'No se pudo guardar.');
        }
    }, 'json').fail(function () {
        btn.prop('disabled', false);
        resultado.removeClass('push-evento-guardado').addClass('push-evento-error').text('No se pudo guardar.');
    });
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
