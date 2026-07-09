'use strict';

var qrCode     = null;
var logoDataUrl = null;

function getQROptions() {
    var data = $('#qr_contenido').val().trim() || 'https://pibg.mx';
    var size = parseInt($('#qr_size').val()) || 300;
    var opts = {
        width:  size,
        height: size,
        type:   'canvas',
        data:   data,
        dotsOptions: {
            color: $('#qr_color_dots').val(),
            type:  $('#qr_dots_style').val()
        },
        cornersSquareOptions: {
            type:  $('#qr_corners_style').val(),
            color: $('#qr_color_dots').val()
        },
        cornersDotOptions: {
            color: $('#qr_color_dots').val()
        },
        backgroundOptions: {
            color: $('#qr_color_bg').val()
        },
        qrOptions: {
            errorCorrectionLevel: $('#qr_error_correction').val()
        }
    };
    if (logoDataUrl) {
        opts.image = logoDataUrl;
        opts.imageOptions = { crossOrigin: 'anonymous', margin: 6, imageSize: 0.3 };
    }
    return opts;
}

function initQR() {
    qrCode = new QRCodeStyling(getQROptions());
    qrCode.append(document.getElementById('qr_preview'));
}

function updateQR() {
    if (!qrCode) return;
    qrCode.update(getQROptions());
}

function descargar_qr() {
    var nombre = $('#qr_nombre').val().trim() || 'codigo_qr';
    qrCode.download({ name: nombre, extension: 'png' });
}

function guardar_qr() {
    var nombre    = $('#qr_nombre').val().trim();
    var contenido = $('#qr_contenido').val().trim();
    if (!nombre || !contenido) {
        bootbox.alert('Es necesario ingresar el nombre y el contenido del código QR.');
        return;
    }
    qrCode.getRawData('png').then(function(blob) {
        var reader = new FileReader();
        reader.onloadend = function() {
            $.post('ajax/codigos_qr.php?op=guardar_qr', {
                nombre:           nombre,
                contenido:        contenido,
                color_frente:     $('#qr_color_dots').val(),
                color_fondo:      $('#qr_color_bg').val(),
                estilo_puntos:    $('#qr_dots_style').val(),
                nivel_correccion: $('#qr_error_correction').val(),
                imagen_base64:    reader.result
            }, function(data) {
                data = JSON.parse(data);
                listar_qr_codes();
                bootbox.alert('&#10003; Código QR guardado exitosamente.');
            });
        };
        reader.readAsDataURL(blob);
    });
}

function listar_qr_codes() {
    $.post('ajax/codigos_qr.php?op=listar_qr', function(r) {
        if (!r || r.trim() === '') {
            $('#tabla_qr_codes').html('<tr><td colspan="5" style="text-align:center;color:#aaa;padding:30px;">Sin registros aún</td></tr>');
        } else {
            $('#tabla_qr_codes').html(r);
        }
    });
}

function borrar_qr(id) {
    bootbox.confirm({
        message: '¿Confirmar eliminación del código QR?',
        buttons: {
            confirm: { label: 'Sí', className: 'btn-success' },
            cancel:  { label: 'No', className: 'btn-danger' }
        },
        callback: function(result) {
            if (result) {
                $.post('ajax/codigos_qr.php?op=borrar_qr', { id: id }, function() {
                    listar_qr_codes();
                    bootbox.alert('Código QR eliminado.');
                });
            }
        }
    });
}

function descargar_qr_guardado(id) {
    $.post('ajax/codigos_qr.php?op=obtener_qr', { id: id }, function(data) {
        data = JSON.parse(data);
        if (!data || !data.imagen_base64) { bootbox.alert('No se encontró la imagen.'); return; }
        var a = document.createElement('a');
        a.href     = data.imagen_base64;
        a.download = (data.nombre || 'codigo_qr') + '.png';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
    });
}

document.addEventListener('DOMContentLoaded', function() {
    initQR();
    listar_qr_codes();

    // ── Botones de estilo de puntos ──
    $('#dots_style_group').on('click', '.qr-dot-btn', function() {
        $('#dots_style_group .qr-dot-btn').removeClass('active');
        $(this).addClass('active');
        $('#qr_dots_style').val($(this).data('val'));
        updateQR();
    });

    // ── Botones de estilo de esquinas ──
    $('#corners_style_group').on('click', '.qr-dot-btn', function() {
        $('#corners_style_group .qr-dot-btn').removeClass('active');
        $(this).addClass('active');
        $('#qr_corners_style').val($(this).data('val'));
        updateQR();
    });

    // ── Controles directos ──
    $('#qr_contenido, #qr_color_dots, #qr_color_bg, #qr_error_correction').on('input change', updateQR);

    $('#qr_size').on('input', function() {
        $('#qr_size_val').text($(this).val());
        updateQR();
    });

    // ── Logo ──
    $('#qr_logo').on('change', function(e) {
        var file = e.target.files[0];
        if (!file) { return; }
        var reader = new FileReader();
        reader.onloadend = function() {
            logoDataUrl = reader.result;
            $('#qr_logo_preview').attr('src', logoDataUrl).show();
            $('#qr_logo_clear').show();
            updateQR();
        };
        reader.readAsDataURL(file);
    });

    $('#qr_logo_clear').on('click', function() {
        logoDataUrl = null;
        $('#qr_logo').val('');
        $('#qr_logo_preview').hide().attr('src', '');
        $(this).hide();
        updateQR();
    });
});
