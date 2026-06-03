$(document).ready(function () {
    cargar_config();
    bind_preview_inputs();
});

function cargar_config() {
    $.get('ajax/modal_bienvenida.php?op=obtener', function (data) {
        if (!data) return;
        $('#habilitado').prop('checked', data.habilitado == 1);
        actualizar_label_habilitado(data.habilitado == 1);
        $('#titulo').val(data.titulo || '');
        $('#mensaje').val(data.mensaje || '');
        $('#video_espanol').val(data.video_espanol || '');
        $('#video_ingles').val(data.video_ingles || '');
        $('#video_koreano').val(data.video_koreano || '');
        $('#video_frances').val(data.video_frances || '');
        mostrar_preview_si_hay_url('espanol');
        mostrar_preview_si_hay_url('ingles');
        mostrar_preview_si_hay_url('koreano');
        mostrar_preview_si_hay_url('frances');
    }, 'json');
}

$('#habilitado').on('change', function () {
    actualizar_label_habilitado(this.checked);
});

function actualizar_label_habilitado(activo) {
    if (activo) {
        $('#lbl_habilitado').text('Habilitada').css('color', '#28a745');
    } else {
        $('#lbl_habilitado').text('Deshabilitada').css('color', '#dc3545');
    }
}

function bind_preview_inputs() {
    ['espanol', 'ingles', 'koreano', 'frances'].forEach(function (lang) {
        $('#video_' + lang).on('blur', function () {
            mostrar_preview_si_hay_url(lang);
        });
    });
}

function mostrar_preview_si_hay_url(lang) {
    var url = $('#video_' + lang).val().trim();
    if (url) {
        $('#iframe_' + lang).attr('src', url);
        $('#prev_' + lang).show();
    } else {
        $('#iframe_' + lang).attr('src', '');
        $('#prev_' + lang).hide();
    }
}

function guardar_modal_bv() {
    var titulo = $('#titulo').val().trim();
    var mensaje = $('#mensaje').val().trim();
    if (!titulo) { bootbox.alert('El título es obligatorio.'); return; }
    if (!mensaje) { bootbox.alert('El mensaje es obligatorio.'); return; }

    var data = {
        titulo:        titulo,
        mensaje:       mensaje,
        video_espanol: $('#video_espanol').val().trim(),
        video_ingles:  $('#video_ingles').val().trim(),
        video_koreano: $('#video_koreano').val().trim(),
        video_frances: $('#video_frances').val().trim()
    };
    if ($('#habilitado').is(':checked')) data.habilitado = 1;

    $('#btn_guardar_mbv').text('Guardando...').css('pointer-events', 'none');

    $.post('ajax/modal_bienvenida.php?op=guardar', data, function (res) {
        $('#btn_guardar_mbv').text('Guardar cambios').css('pointer-events', '');
        if (res && res.ok) {
            bootbox.alert('<i class="typcn typcn-tick" style="color:#28a745;font-size:18px;"></i> &nbsp;Configuración guardada correctamente.');
        } else {
            bootbox.alert('Ocurrió un error al guardar. Intente de nuevo.');
        }
    }, 'json').fail(function () {
        $('#btn_guardar_mbv').text('Guardar cambios').css('pointer-events', '');
        bootbox.alert('Error de conexión.');
    });
}
