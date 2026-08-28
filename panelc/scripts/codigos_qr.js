'use strict';

var qrCode      = null;
var logoDataUrl = null;
var qr_editando_id = null;

// ── Recorte de logo ──
var cropOriginalDataUrl = null;
var cropImgNatural = { w: 0, h: 0 };
var cropState = { scale: 1, baseScale: 1, tx: 0, ty: 0 };
var cropDragging = false, cropLastX = 0, cropLastY = 0;
var CROP_VIEWPORT = 280;
var CROP_OUTPUT   = 500;

function aplicarTransformCrop() {
    var img = document.getElementById('crop_image');
    img.style.transform = 'scale(' + cropState.scale + ')';
    img.style.left = cropState.tx + 'px';
    img.style.top  = cropState.ty + 'px';
}

function clampCrop() {
    var dw = cropImgNatural.w * cropState.scale;
    var dh = cropImgNatural.h * cropState.scale;
    var minTx = Math.min(0, CROP_VIEWPORT - dw);
    var minTy = Math.min(0, CROP_VIEWPORT - dh);
    cropState.tx = Math.max(minTx, Math.min(0, cropState.tx));
    cropState.ty = Math.max(minTy, Math.min(0, cropState.ty));
}

function abrir_crop_logo(dataUrl, resetView) {
    var tempImg = new Image();
    tempImg.onload = function() {
        var isNewImage = (cropImgNatural.w !== tempImg.naturalWidth || cropImgNatural.h !== tempImg.naturalHeight);
        cropImgNatural.w = tempImg.naturalWidth;
        cropImgNatural.h = tempImg.naturalHeight;

        if (resetView || isNewImage) {
            cropState.baseScale = Math.max(CROP_VIEWPORT / cropImgNatural.w, CROP_VIEWPORT / cropImgNatural.h);
            cropState.scale = cropState.baseScale;
            cropState.tx = (CROP_VIEWPORT - cropImgNatural.w * cropState.scale) / 2;
            cropState.ty = (CROP_VIEWPORT - cropImgNatural.h * cropState.scale) / 2;
            $('#crop_zoom').val(1);
        }

        var img = document.getElementById('crop_image');
        img.src = dataUrl;
        img.style.width  = cropImgNatural.w + 'px';
        img.style.height = cropImgNatural.h + 'px';
        aplicarTransformCrop();
        $('#modal_crop_logo').modal('show');
    };
    tempImg.src = dataUrl;
}

function cropPointerDown(x, y) {
    cropDragging = true;
    cropLastX = x;
    cropLastY = y;
}

function cropPointerMove(x, y) {
    if (!cropDragging) return;
    cropState.tx += (x - cropLastX);
    cropState.ty += (y - cropLastY);
    cropLastX = x;
    cropLastY = y;
    clampCrop();
    aplicarTransformCrop();
}

function cropPointerUp() {
    cropDragging = false;
}

function getQROptions() {
    var data = $('#qr_contenido').val().trim() || 'https://pibg.mx';
    var size   = parseInt($('#qr_size').val())   || 300;
    var margin = parseInt($('#qr_margin').val()) || 0;
    var opts = {
        width:  size,
        height: size,
        margin: margin,
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
            var datos = {
                nombre:           nombre,
                contenido:        contenido,
                color_frente:     $('#qr_color_dots').val(),
                color_fondo:      $('#qr_color_bg').val(),
                estilo_puntos:    $('#qr_dots_style').val(),
                nivel_correccion: $('#qr_error_correction').val(),
                imagen_base64:    reader.result
            };
            var op = 'guardar_qr';
            if (qr_editando_id) {
                datos.id = qr_editando_id;
                op = 'editar_qr';
            }
            $.post('ajax/codigos_qr.php?op=' + op, datos, function(data) {
                data = JSON.parse(data);
                var editando = !!qr_editando_id;
                cancelar_edicion_qr();
                listar_qr_codes();
                bootbox.alert(editando ? '✓ Código QR actualizado.' : '✓ Código QR guardado exitosamente.');
            });
        };
        reader.readAsDataURL(blob);
    });
}

function editar_qr_guardado(id) {
    $.post('ajax/codigos_qr.php?op=obtener_qr', { id: id }, function(data) {
        data = JSON.parse(data);
        if (!data || !data.id) { bootbox.alert('No se encontró el código QR.'); return; }

        qr_editando_id = id;

        $('#qr_nombre').val(data.nombre);
        $('#qr_contenido').val(data.contenido);
        $('#qr_color_dots').val(data.color_frente || '#042C49');
        $('#qr_color_bg').val(data.color_fondo || '#ffffff');
        $('#qr_error_correction').val(data.nivel_correccion || 'M');

        $('#qr_dots_style').val(data.estilo_puntos || 'rounded');
        $('#dots_style_group .qr-dot-btn').removeClass('active');
        $('#dots_style_group .qr-dot-btn[data-val="' + (data.estilo_puntos || 'rounded') + '"]').addClass('active');

        $('#qr_btn_guardar').html('&#128190; Actualizar');
        $('#qr_editando_nombre').text(data.nombre);
        $('#qr_editando_msg').show();

        updateQR();
        $('html, body').animate({ scrollTop: 0 }, 300);
    });
}

function cancelar_edicion_qr() {
    qr_editando_id = null;
    $('#qr_btn_guardar').html('&#128190; Guardar');
    $('#qr_editando_msg').hide();
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

    $('#qr_margin').on('input', function() {
        $('#qr_margin_val').text($(this).val());
        updateQR();
    });

    // ── Logo ──
    $('#qr_logo').on('change', function(e) {
        var file = e.target.files[0];
        if (!file) { return; }
        var reader = new FileReader();
        reader.onloadend = function() {
            cropOriginalDataUrl = reader.result;
            abrir_crop_logo(cropOriginalDataUrl, true);
        };
        reader.readAsDataURL(file);
    });

    $('#qr_logo_edit').on('click', function() {
        if (cropOriginalDataUrl) { abrir_crop_logo(cropOriginalDataUrl, false); }
    });

    $('#qr_logo_clear').on('click', function() {
        logoDataUrl = null;
        cropOriginalDataUrl = null;
        $('#qr_logo').val('');
        $('#qr_logo_preview').hide().attr('src', '');
        $('#qr_logo_edit').hide();
        $(this).hide();
        updateQR();
    });

    // ── Modal de recorte ──
    $('#crop_zoom').on('input', function() {
        var z = parseFloat($(this).val());
        var oldScale = cropState.scale;
        var newScale = cropState.baseScale * z;
        var cx = CROP_VIEWPORT / 2, cy = CROP_VIEWPORT / 2;
        var nx = (cx - cropState.tx) / oldScale;
        var ny = (cy - cropState.ty) / oldScale;
        cropState.scale = newScale;
        cropState.tx = cx - nx * newScale;
        cropState.ty = cy - ny * newScale;
        clampCrop();
        aplicarTransformCrop();
    });

    $('#crop_viewport').on('mousedown', function(e) {
        cropPointerDown(e.clientX, e.clientY);
        e.preventDefault();
    });
    $(document).on('mousemove', function(e) { cropPointerMove(e.clientX, e.clientY); });
    $(document).on('mouseup', function() { cropPointerUp(); });

    $('#crop_viewport').on('touchstart', function(e) {
        var t = e.originalEvent.touches[0];
        cropPointerDown(t.clientX, t.clientY);
    });
    $('#crop_viewport').on('touchmove', function(e) {
        var t = e.originalEvent.touches[0];
        cropPointerMove(t.clientX, t.clientY);
        e.preventDefault();
    });
    $('#crop_viewport').on('touchend', function() { cropPointerUp(); });

    $('#btn_aplicar_crop').on('click', function() {
        var sSize = CROP_VIEWPORT / cropState.scale;
        var sx = -cropState.tx / cropState.scale;
        var sy = -cropState.ty / cropState.scale;
        sx = Math.max(0, Math.min(sx, cropImgNatural.w - sSize));
        sy = Math.max(0, Math.min(sy, cropImgNatural.h - sSize));

        var canvas = document.createElement('canvas');
        canvas.width  = CROP_OUTPUT;
        canvas.height = CROP_OUTPUT;
        canvas.getContext('2d').drawImage(
            document.getElementById('crop_image'),
            sx, sy, sSize, sSize,
            0, 0, CROP_OUTPUT, CROP_OUTPUT
        );

        logoDataUrl = canvas.toDataURL('image/png');
        $('#qr_logo_preview').attr('src', logoDataUrl).show();
        $('#qr_logo_clear').show();
        $('#qr_logo_edit').show();
        updateQR();
        $('#modal_crop_logo').modal('hide');
    });
});
