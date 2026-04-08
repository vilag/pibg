/* ============================================================
   transcriptor.js — Transcripción desde YouTube (captions API)
============================================================ */

$(function () {

    // Preview en vivo al pegar URL
    $('#url_youtube').on('input', function () {
        mostrar_preview($(this).val().trim());
    });

    // Submit del formulario
    $('#form_transcriptor').on('submit', function (e) {
        e.preventDefault();
        transcribir();
    });

    // Copiar al portapapeles
    $('#btn_copiar').on('click', function () {
        var texto = $('#resultado_texto').val();
        if (!texto) return;
        navigator.clipboard.writeText(texto).then(function () {
            $('#btn_copiar').text('¡Copiado!');
            setTimeout(function () { $('#btn_copiar').text('Copiar texto'); }, 2000);
        });
    });

    // Descargar como .txt
    $('#btn_descargar').on('click', function () {
        var texto = $('#resultado_texto').val();
        if (!texto) return;
        var blob = new Blob([texto], { type: 'text/plain;charset=utf-8' });
        var a = document.createElement('a');
        a.href = URL.createObjectURL(blob);
        a.download = 'transcripcion.txt';
        a.click();
    });

    // Borrar resultado
    $('#btn_limpiar').on('click', function () {
        $('#resultado_texto').val('');
        $('#resultado_box').hide();
        $('#segmentos_box').hide();
        $('#segmentos_lista').html('');
        $('#idiomas_disponibles').hide();
    });
});

/* ---- Preview embed ---- */
function mostrar_preview(url) {
    var id = extraer_video_id(url);
    if (id) {
        $('#yt_preview').attr('src', 'https://www.youtube.com/embed/' + id);
        $('#preview_wrapper').show();
    } else {
        $('#yt_preview').attr('src', '');
        $('#preview_wrapper').hide();
    }
}

function extraer_video_id(url) {
    var m = url.match(/[?&]v=([A-Za-z0-9_\-]{11})/);
    if (m) return m[1];
    m = url.match(/youtu\.be\/([A-Za-z0-9_\-]{11})/);
    return m ? m[1] : null;
}

/* ---- Transcribir ---- */
function transcribir() {
    var url    = $('#url_youtube').val().trim();
    var inicio = $('#tiempo_inicio').val().trim() || '0';
    var fin    = $('#tiempo_fin').val().trim();
    var lang   = $('#select_idioma').val();

    if (!url) {
        mostrar_error('Ingresa una URL de YouTube.');
        return;
    }

    estado_cargando(true);
    $('#error_box').hide();
    $('#resultado_box').hide();
    $('#segmentos_box').hide();
    $('#idiomas_disponibles').hide();

    $.ajax({
        url:     'ajax/transcriptor.php?op=transcribir',
        method:  'POST',
        data:    { url: url, inicio: inicio, fin: fin, lang: lang },
        timeout: 60000,
        success: function (r) {
            estado_cargando(false);
            if (r.ok) {
                mostrar_resultado(r);
            } else {
                mostrar_error(r.msg);
                // Mostrar idiomas disponibles si los hay en el error
                if (r.idiomas && r.idiomas.length) {
                    mostrar_idiomas_disponibles(r.idiomas);
                }
            }
        },
        error: function (xhr, status) {
            estado_cargando(false);
            if (status === 'timeout') {
                mostrar_error('La solicitud tardó demasiado. Intenta de nuevo.');
            } else {
                mostrar_error('Error de conexión: ' + status);
            }
        }
    });
}

function estado_cargando(cargando) {
    $('#btn_transcribir').prop('disabled', cargando);
    $('#transcribir_label').toggle(!cargando);
    $('#transcribir_spinner').toggle(cargando);
    $('#progreso_box').toggle(cargando);
}

/* ---- Mostrar resultado ---- */
function mostrar_resultado(r) {
    $('#resultado_texto').val(r.texto);
    $('#resultado_box').show();

    // Mostrar idiomas disponibles
    if (r.idiomas && r.idiomas.length > 1) {
        mostrar_idiomas_disponibles(r.idiomas);
    }

    // Segmentos con timestamps
    if (r.segmentos && r.segmentos.length > 0) {
        var html = '';
        r.segmentos.forEach(function (s) {
            html += '<div class="tr-seg">'
                  + '<span class="tr-seg-ts">' + formatear_tiempo(s.start) + '</span>'
                  + '<span class="tr-seg-txt">' + $('<div>').text(s.text).html() + '</span>'
                  + '</div>';
        });
        $('#segmentos_lista').html(html);
        $('#segmentos_box').show();
    }
}

function mostrar_idiomas_disponibles(idiomas) {
    var html = '<span style="font-weight:700; color:#334155;">Idiomas en este video: </span>';
    idiomas.forEach(function (i) {
        html += '<span class="tr-tag">' + i.name + (i.auto ? ' (auto)' : '') + '</span> ';
    });
    $('#idiomas_txt').html(html);
    $('#idiomas_disponibles').show();
}

function mostrar_error(msg) {
    $('#error_msg').text(msg);
    $('#error_box').show();
}

/* ---- Helpers ---- */
function formatear_tiempo(segundos) {
    var s   = Math.floor(segundos);
    var h   = Math.floor(s / 3600);
    var m   = Math.floor((s % 3600) / 60);
    var sec = s % 60;
    if (h > 0) return h + ':' + pad(m) + ':' + pad(sec);
    return pad(m) + ':' + pad(sec);
}

function pad(n) { return n < 10 ? '0' + n : '' + n; }
