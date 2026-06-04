document.addEventListener("DOMContentLoaded", function () {
    listar_mbv();
    document.getElementById('habilitado').addEventListener('change', function () {
        if (this.checked) {
            $('#lbl_habilitado').text('Activo').css({ background: '#28a745', color: '#fff' });
        } else {
            $('#lbl_habilitado').text('Inactivo').css({ background: '#ccc', color: '#555' });
        }
    });
});

/* ══════════════════════════════════════════════
   RESOLUCIÓN DE URLs
══════════════════════════════════════════════ */
function mbv_resolve(url) {
    url = (url || '').trim();
    if (!url) return null;
    var yt = url.match(/(?:youtube\.com\/watch\?(?:.*&)?v=|youtu\.be\/)([a-zA-Z0-9_-]{11})/);
    if (yt) return { type: 'iframe', src: 'https://www.youtube.com/embed/' + yt[1] + '?rel=0' };
    if (/youtube(?:-nocookie)?\.com\/embed\//.test(url)) return { type: 'iframe', src: url };
    var vim = url.match(/vimeo\.com\/(\d+)/);
    if (vim) return { type: 'iframe', src: 'https://player.vimeo.com/video/' + vim[1] };
    if (/player\.vimeo\.com\/video\//.test(url)) return { type: 'iframe', src: url };
    if (/\.(mp4|webm|ogg|mov|m4v)(\?.*)?$/i.test(url)) return { type: 'video', src: url };
    return { type: 'iframe', src: url };
}

function render_preview($container, tipo_contenido, url) {
    $container.html('').hide();
    if (!url) return;
    if (tipo_contenido === 'imagen') {
        $container.html('<img src="' + url + '" style="position:absolute;top:0;left:0;width:100%;height:100%;object-fit:contain;background:#000;" alt="">').show();
    } else if (tipo_contenido === 'video') {
        var r = mbv_resolve(url);
        if (!r) return;
        if (r.type === 'video') {
            $container.html('<video src="' + r.src + '" controls style="position:absolute;top:0;left:0;width:100%;height:100%;background:#000;"></video>').show();
        } else {
            $container.html('<iframe src="' + r.src + '" style="position:absolute;top:0;left:0;width:100%;height:100%;border:none;" allowfullscreen></iframe>').show();
        }
    }
}

/* ══════════════════════════════════════════════
   TOGGLE TIPO MODAL (directo / selector)
══════════════════════════════════════════════ */
function toggle_tipo_modal() {
    var es_selector = $('input[name="tipo_modal"]:checked').val() === '1';
    $('#seccion_directo').toggle(!es_selector);
    $('#seccion_selector').toggle(es_selector);
}

function toggle_url_directo() {
    var tipo = $('#tipo_directo').val();
    $('#url_directo_wrap').toggle(tipo === 'video' || tipo === 'imagen');
    if (!tipo) { $('#prev_directo').html('').hide(); }
}

function mostrar_preview_directo() {
    var tipo = $('#tipo_directo').val();
    var url  = $('#url_directo').val().trim();
    render_preview($('#prev_directo'), tipo, url);
}

/* ══════════════════════════════════════════════
   OPCIONES DINÁMICAS
══════════════════════════════════════════════ */
function actualizar_msg_sin_opciones() {
    var hay = $('.opt_block').length > 0;
    $('#msg_sin_opciones').toggle(!hay);
}

function agregar_opcion(data) {
    data = data || {};
    var tpl   = document.getElementById('tpl_opcion');
    var clone = document.importNode(tpl.content, true);
    var $el   = $(clone).find('.opt_block').length
        ? $(clone)
        : $(clone.firstElementChild || clone);
    // Si importNode devuelve DocumentFragment, tomamos el primer hijo
    var $block = $('<div>').append(clone).children('.opt_block');

    $block.find('.opt_emoji').val(data.emoji || '');
    $block.find('.opt_label').val(data.label || '');
    $block.find('.opt_tipo').val(data.tipo  || 'video');

    var url  = data.url || '';
    $block.find('.opt_url').val(url);

    // Toggle url wrap según tipo inicial
    var tipo_ini = data.tipo || 'video';
    if (tipo_ini === 'texto') {
        $block.find('.opt_url_wrap').hide();
    }

    $('#opciones_container').append($block);

    // Preview si ya tiene URL
    if (url) {
        render_preview($block.find('.opt_prev'), tipo_ini, url);
    }

    actualizar_msg_sin_opciones();
}

function toggle_opt_url(selectEl) {
    var $block = $(selectEl).closest('.opt_block');
    var tipo   = $(selectEl).val();
    $block.find('.opt_url_wrap').toggle(tipo !== 'texto');
    if (tipo === 'texto') {
        $block.find('.opt_prev').html('').hide();
    }
}

function mostrar_preview_opt(inputEl) {
    var $block = $(inputEl).closest('.opt_block');
    var tipo   = $block.find('.opt_tipo').val();
    var url    = $block.find('.opt_url').val().trim();
    render_preview($block.find('.opt_prev'), tipo, url);
}

function eliminar_opcion(btnEl) {
    $(btnEl).closest('.opt_block').remove();
    actualizar_msg_sin_opciones();
}

function recolectar_opciones() {
    var opts = [];
    $('.opt_block').each(function () {
        opts.push({
            emoji: $(this).find('.opt_emoji').val().trim(),
            label: $(this).find('.opt_label').val().trim(),
            tipo:  $(this).find('.opt_tipo').val(),
            url:   $(this).find('.opt_url').val().trim()
        });
    });
    return opts;
}

/* ══════════════════════════════════════════════
   LISTAR
══════════════════════════════════════════════ */
function listar_mbv() {
    $('#tabla_mbv').html('<tr><td colspan="6" style="text-align:center;color:#aaa;">Cargando...</td></tr>');
    $.get('ajax/modal_bienvenida.php?op=listar', function (html) {
        $('#tabla_mbv').html(html || '<tr><td colspan="6" style="color:#aaa;text-align:center;">Sin anuncios registrados.</td></tr>');
    });
}

/* ══════════════════════════════════════════════
   CANCELAR / LIMPIAR FORMULARIO
══════════════════════════════════════════════ */
function cancelar_mbv() {
    $('#mbv_id').val('0');
    $('#mbv_form_titulo').text('Nuevo Anuncio');
    $('#btn_cancelar_mbv').hide();
    $('#nombre').val('');
    $('#titulo').val('');
    $('#mensaje').val('');
    // Radio: directo
    $('input[name="tipo_modal"][value="0"]').prop('checked', true);
    toggle_tipo_modal();
    $('#tipo_directo').val('');
    toggle_url_directo();
    $('#url_directo').val('');
    $('#prev_directo').html('').hide();
    // Limpiar opciones
    $('#opciones_container').empty();
    actualizar_msg_sin_opciones();
    // Resetear habilitado
    $('#habilitado').prop('checked', false).trigger('change');
}

/* ══════════════════════════════════════════════
   CARGAR PARA EDITAR
══════════════════════════════════════════════ */
function editar_mbv(id) {
    $.get('ajax/modal_bienvenida.php?op=get_one&id=' + id, function (data) {
        if (!data) return;
        $('#mbv_id').val(data.id);
        $('#mbv_form_titulo').text('Editar — ' + data.nombre);
        $('#btn_cancelar_mbv').show();
        $('#nombre').val(data.nombre   || '');
        $('#titulo').val(data.titulo   || '');
        $('#mensaje').val(data.mensaje || '');

        var es_selector = parseInt(data.tiene_selector) === 1;
        $('input[name="tipo_modal"][value="' + (es_selector ? '1' : '0') + '"]').prop('checked', true);
        toggle_tipo_modal();

        // Directo
        $('#tipo_directo').val(data.tipo_directo || '');
        toggle_url_directo();
        $('#url_directo').val(data.url_directo || '');
        mostrar_preview_directo();

        // Selector
        $('#opciones_container').empty();
        var ops = [];
        try { ops = JSON.parse(data.opciones || '[]'); } catch(e) {}
        ops.forEach(function (op) { agregar_opcion(op); });
        actualizar_msg_sin_opciones();

        // Habilitado
        $('#habilitado').prop('checked', parseInt(data.habilitado) === 1).trigger('change');

        $('html, body').animate({ scrollTop: 0 }, 300);
    }, 'json');
}

/* ══════════════════════════════════════════════
   GUARDAR
══════════════════════════════════════════════ */
function guardar_mbv() {
    var nombre  = $('#nombre').val().trim();
    var titulo  = $('#titulo').val().trim();
    var mensaje = $('#mensaje').val().trim();
    if (!nombre)  { bootbox.alert('El nombre del anuncio es obligatorio.');  return; }
    if (!titulo)  { bootbox.alert('El título es obligatorio.');              return; }
    if (!mensaje) { bootbox.alert('El mensaje es obligatorio.');             return; }

    var es_selector = $('input[name="tipo_modal"]:checked').val() === '1';

    if (es_selector && $('.opt_block').length === 0) {
        bootbox.alert('Agrega al menos una opción al selector.'); return;
    }

    var data = {
        id:             $('#mbv_id').val(),
        nombre:         nombre,
        titulo:         titulo,
        mensaje:        mensaje,
        tiene_selector: es_selector ? 1 : 0,
        tipo_directo:   es_selector ? '' : $('#tipo_directo').val(),
        url_directo:    es_selector ? '' : $('#url_directo').val().trim(),
        opciones:       es_selector ? JSON.stringify(recolectar_opciones()) : '[]',
        habilitado:     $('#habilitado').is(':checked') ? 1 : 0
    };

    $('#btn_guardar_mbv').text('Guardando…').css('pointer-events', 'none');
    $.post('ajax/modal_bienvenida.php?op=guardar', data, function (res) {
        $('#btn_guardar_mbv').text('Guardar anuncio').css('pointer-events', '');
        if (res && res.ok) {
            cancelar_mbv();
            listar_mbv();
        } else {
            bootbox.alert('Error al guardar. Intente de nuevo.');
        }
    }, 'json').fail(function () {
        $('#btn_guardar_mbv').text('Guardar anuncio').css('pointer-events', '');
        bootbox.alert('Error de conexión.');
    });
}

/* ══════════════════════════════════════════════
   ACTIVAR / DESACTIVAR / BORRAR
══════════════════════════════════════════════ */
function activar_mbv(id) {
    $.post('ajax/modal_bienvenida.php?op=activar', { id: id }, function (res) {
        if (res && res.ok) listar_mbv(); else bootbox.alert('Error al activar.');
    }, 'json');
}

function desactivar_mbv(id) {
    $.post('ajax/modal_bienvenida.php?op=desactivar', { id: id }, function (res) {
        if (res && res.ok) listar_mbv(); else bootbox.alert('Error al desactivar.');
    }, 'json');
}

function borrar_mbv(id) {
    bootbox.confirm({
        title: 'Eliminar anuncio',
        message: '¿Seguro que deseas eliminar este anuncio? No se puede deshacer.',
        buttons: {
            confirm: { label: 'Eliminar', className: 'btn-danger' },
            cancel:  { label: 'Cancelar', className: 'btn-secondary' }
        },
        callback: function (result) {
            if (!result) return;
            $.post('ajax/modal_bienvenida.php?op=borrar', { id: id }, function (res) {
                if (res && res.ok) {
                    listar_mbv();
                    if ($('#mbv_id').val() == id) cancelar_mbv();
                } else {
                    bootbox.alert('Error al eliminar.');
                }
            }, 'json');
        }
    });
}
