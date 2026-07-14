var qb_idx = 0;

function init(){
    listar_activ_sem_esp();
}

function guardar_activ_sem(){

    var fecha1 = $("#fecha_actividad1").val();
    var fecha2 = $("#fecha_actividad2").val();
    var nombre = $("#nom_actividad_sem").val();
    var nombre_corto = $("#nom_actividad_corto_sem").val();
    var detalle = $("#detalle_actividad").val();
    var imagen = $("#archivo_audio").val();
    var texto_banner = $("#texto_banner").val();
    var video_url = $("#video_url").val();

    if (fecha1!="" && fecha2!="" && nombre!="" && nombre_corto!="" && imagen!="") {
        $.post("ajax/semanas_esp.php?op=guardar_activ_sem",{
            fecha1:fecha1,
            fecha2:fecha2,
            nombre:nombre,
            nombre_corto:nombre_corto,
            detalle:detalle,
            imagen:imagen,
            texto_banner:texto_banner,
            video_url:video_url
        },function(data, status)
        {
            data = JSON.parse(data);
            var idactiv_nuevo = data.idactiv;

            $("#fecha_actividad1").val("");
            $("#fecha_actividad2").val("");
            $("#nom_actividad_sem").val("");
            $("#nom_actividad_corto_sem").val("");
            $("#detalle_actividad").val("");
            $("#archivo_audio").val("");
            $("#texto_banner").val("");
            $("#video_url").val("");
            listar_activ_sem_esp();

            if (idactiv_nuevo) {
                bootbox.confirm("Registro guardado exitosamente. ¿Quieres crear ya su formulario de registro y código QR?", function(ok) {
                    if (ok) {
                        crear_formulario_registro(idactiv_nuevo, nombre_corto, fecha1, fecha2);
                    }
                });
            } else {
                bootbox.alert("Registro guardado exitosamente");
            }

        });
    }else{
        bootbox.alert("Es necesario capturar los campos obligatorios (*) ");
    }


}

function listar_activ_sem_esp(){
    $.post("ajax/semanas_esp.php?op=listar_activ_sem_esp",function(r){
		$("#temas_sem").html(r);
	});
}


function borrar_activ(idactiv){
    bootbox.confirm({
        message: "¿Confirmar eliminacion de registro?",
        buttons: {
            confirm: {
                label: 'Si',
                className: 'btn-success'
            },
            cancel: {
                label: 'No',
                className: 'btn-danger'
            }
        },
        callback: function (result) {
            // console.log('This was logged in the callback: ' + result);
            //alert(result);
            if (result) {
                // alert(idlectura);
                // return;
                $.post("ajax/semanas_esp.php?op=borrar_activ",{idactiv:idactiv},function(data, status)
                {
                    data = JSON.parse(data);

                    listar_activ_sem_esp();
                    bootbox.alert("Registro eliminado exitosamente");

                });
            }
        }
    });
}

function editar_activ(idactiv) {
    $.post("ajax/semanas_esp.php?op=obtener_activ", {idactiv: idactiv}, function(data) {
        data = JSON.parse(data);
        $("#edit_idactiv").val(data.idactiv);
        $("#edit_fecha1").val(data.fecha1);
        $("#edit_fecha2").val(data.fecha2);
        $("#edit_nombre").val(data.nombre);
        $("#edit_nombre_corto").val(data.nombre_corto);
        $("#edit_detalle").val(data.detalle);
        $("#edit_imagen").val(data.imagen);
        $("#edit_preview_img").attr("src", data.imagen).show();
        $("#edit_texto_banner").val(data.texto_banner || "");
        $("#edit_video_url").val(data.video_url || "");
        $("#modalEditarActiv").modal("show");
    });
}

function guardar_edicion_activ() {
    var idactiv      = $("#edit_idactiv").val();
    var fecha1       = $("#edit_fecha1").val();
    var fecha2       = $("#edit_fecha2").val();
    var nombre       = $("#edit_nombre").val();
    var nombre_corto = $("#edit_nombre_corto").val();
    var detalle      = $("#edit_detalle").val();
    var imagen       = $("#edit_imagen").val();
    var texto_banner = $("#edit_texto_banner").val();
    var video_url    = $("#edit_video_url").val();

    if (fecha1 != "" && fecha2 != "" && nombre != "" && nombre_corto != "" && imagen != "") {
        $.post("ajax/semanas_esp.php?op=editar_activ_sem", {
            idactiv:      idactiv,
            fecha1:       fecha1,
            fecha2:       fecha2,
            nombre:       nombre,
            nombre_corto: nombre_corto,
            detalle:      detalle,
            imagen:       imagen,
            texto_banner: texto_banner,
            video_url:    video_url
        }, function(data) {
            data = JSON.parse(data);
            $("#modalEditarActiv").modal("hide");
            listar_activ_sem_esp();
            bootbox.alert("Registro actualizado exitosamente");
        });
    } else {
        bootbox.alert("Es necesario capturar los campos obligatorios (*)");
    }
}

/* ══════════════════════════════════════════════
   VER DETALLE + FORMULARIO DE REGISTRO + CÓDIGO QR
══════════════════════════════════════════════ */

function construir_url_encuesta_publica(token) {
    var dir = window.location.pathname.replace(/[^\/]*$/, '');
    return window.location.origin + dir + 'encuesta_publica.php?t=' + token;
}

function ver_activ_detalle(idactiv) {
    $("#ver_idactiv").val(idactiv);
    $("#ver_seccion_formulario").html('<p style="color:#aaa;">Cargando…</p>');
    $("#ver_seccion_qr").html('<p style="color:#aaa;">Cargando…</p>');

    $.post("ajax/semanas_esp.php?op=obtener_activ", {idactiv: idactiv}, function(data) {
        data = JSON.parse(data);
        $("#ver_nombre_corto").val(data.nombre_corto);
        $("#ver_fecha1").val(data.fecha1);
        $("#ver_fecha2").val(data.fecha2);
        $("#ver_nombre").text(data.nombre);
        $("#ver_fechas").text(data.fecha1 + " al " + data.fecha2);
        $("#ver_detalle").text(data.detalle || "");
        $("#ver_imagen").attr("src", data.imagen);
    });

    $.post("ajax/encuestas.php?op=obtener_por_actividad", {idactiv: idactiv}, function(res) {
        res = JSON.parse(res);
        pintar_seccion_formulario(idactiv, res.encuesta);
    });

    $("#modalVerActiv").modal("show");
}

function pintar_seccion_formulario(idactiv, encuesta) {
    var $sec = $("#ver_seccion_formulario");
    if (encuesta) {
        var url_publica = construir_url_encuesta_publica(encuesta.token_publico);
        $sec.html(
            '<p><b>' + encuesta.titulo + '</b></p>' +
            '<div style="display:flex;gap:8px;flex-wrap:wrap;">' +
                '<a href="' + url_publica + '" target="_blank" class="btn btn-sm btn-primary">Abrir formulario público</a>' +
                '<button class="btn btn-sm btn-secondary" onclick="editar_encuesta(' + encuesta.id + ');">Editar preguntas</button>' +
            '</div>'
        );
        cargar_seccion_qr(idactiv, url_publica, encuesta.titulo);
    } else {
        $sec.html(
            '<p style="color:#888;">Este evento aún no tiene formulario de registro.</p>' +
            '<button class="btn btn-sm btn-primary" onclick="crear_formulario_registro(' + idactiv + ', $(\'#ver_nombre_corto\').val(), $(\'#ver_fecha1\').val(), $(\'#ver_fecha2\').val());">Crear formulario de registro</button>'
        );
        $("#ver_seccion_qr").html('<p style="color:#888;">Primero crea el formulario de registro para poder generar su código QR.</p>');
    }
}

function cargar_seccion_qr(idactiv, url_publica, nombre_evento) {
    $.post("ajax/codigos_qr.php?op=obtener_por_actividad", {idactiv: idactiv}, function(res) {
        res = JSON.parse(res);
        pintar_seccion_qr(idactiv, res.qr, url_publica, nombre_evento);
    });
}

function pintar_seccion_qr(idactiv, qr, url_publica, nombre_evento) {
    var $sec = $("#ver_seccion_qr");
    if (qr) {
        $sec.html(
            '<img src="' + qr.imagen_base64 + '" style="width:160px;height:160px;border:1px solid #ccc;border-radius:8px;background:#fff;">' +
            '<div style="margin-top:8px;display:flex;gap:8px;">' +
                '<a href="' + qr.imagen_base64 + '" download="qr_evento_' + idactiv + '.png" class="btn btn-sm btn-secondary">Descargar</a>' +
                '<button class="btn btn-sm btn-outline-secondary" onclick="abrir_modal_editar_qr(' + idactiv + ', ' + qr.id + ');">Editar</button>' +
            '</div>'
        );
        $("#ver_seccion_qr").data("qr", qr).data("url", url_publica).data("nombre", nombre_evento);
    } else {
        var nombre_escapado = (nombre_evento || '').replace(/'/g, "");
        $sec.html(
            '<p style="color:#888;">Aún no se ha generado un código QR para este formulario.</p>' +
            '<button class="btn btn-sm btn-primary" onclick="generar_qr_evento(' + idactiv + ', \'' + url_publica + '\', \'' + nombre_escapado + '\');">Generar código QR</button>'
        );
    }
}

function generar_qr_png(url_publica, colorFrente, colorFondo, estiloPuntos, callback) {
    if (typeof QRCodeStyling === 'undefined') {
        bootbox.alert("No se pudo cargar el generador de códigos QR. Recarga la página e intenta de nuevo.");
        return;
    }

    var $contenedor = $("#qr_temp_container").empty();

    var qrCode = new QRCodeStyling({
        width: 300,
        height: 300,
        margin: 10,
        type: 'canvas',
        data: url_publica,
        dotsOptions: { color: colorFrente, type: estiloPuntos },
        cornersSquareOptions: { type: 'dot', color: colorFrente },
        cornersDotOptions: { color: colorFrente },
        backgroundOptions: { color: colorFondo },
        qrOptions: { errorCorrectionLevel: 'M' }
    });

    // La librería necesita dibujar en un elemento del DOM antes de poder
    // extraer la imagen; sin este paso el PNG resultante sale en blanco/negro.
    qrCode.append($contenedor[0]);

    qrCode.getRawData('png').then(function(blob) {
        var reader = new FileReader();
        reader.onload = function() {
            $contenedor.empty();
            callback(reader.result);
        };
        reader.readAsDataURL(blob);
    });
}

function crear_formulario_registro(idactiv, nombre_corto, fecha1, fecha2) {
    $.post("ajax/encuestas.php?op=crear_para_evento", {
        idactiv: idactiv,
        nombre_corto: nombre_corto,
        fecha1: fecha1,
        fecha2: fecha2
    }, function(res) {
        res = JSON.parse(res);
        if (!res.ok) {
            bootbox.alert(res.msg || "No se pudo crear el formulario de registro.");
            return;
        }
        bootbox.alert("Formulario de registro creado.");
        if ($("#ver_idactiv").val() == idactiv) {
            pintar_seccion_formulario(idactiv, {id: res.id, titulo: 'Registro: ' + nombre_corto, token_publico: res.token});
        }
        generar_qr_evento(idactiv, res.url, 'Registro: ' + nombre_corto);
    });
}

function generar_qr_evento(idactiv, url_publica, nombre_evento) {
    generar_qr_png(url_publica, '#042C49', '#ffffff', 'rounded', function(imagen_base64) {
        $.post("ajax/codigos_qr.php?op=guardar_qr", {
            nombre: nombre_evento,
            contenido: url_publica,
            color_frente: '#042C49',
            color_fondo: '#ffffff',
            estilo_puntos: 'rounded',
            nivel_correccion: 'M',
            imagen_base64: imagen_base64,
            idactiv_relacionada: idactiv
        }, function(res) {
            res = JSON.parse(res);
            if (res.ok && $("#ver_idactiv").val() == idactiv) {
                cargar_seccion_qr(idactiv, url_publica, nombre_evento);
            }
        });
    });
}

/* ══════════════════════════════════════════════
   MODAL DE EDICIÓN DE QR (replicado de codigos_qr.js,
   para editar el código QR sin salir de esta vista)
══════════════════════════════════════════════ */

var qrCode_modal      = null;
var logoDataUrl_modal = null;

function getQROptions_modal() {
    var data   = $('#qr_contenido').val().trim() || 'https://pibg.mx';
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
    if (logoDataUrl_modal) {
        opts.image = logoDataUrl_modal;
        opts.imageOptions = { crossOrigin: 'anonymous', margin: 6, imageSize: 0.3 };
    }
    return opts;
}

function initQR_modal() {
    $('#qr_preview').empty();
    qrCode_modal = new QRCodeStyling(getQROptions_modal());
    qrCode_modal.append(document.getElementById('qr_preview'));
}

function updateQR_modal() {
    if (!qrCode_modal) return;
    qrCode_modal.update(getQROptions_modal());
}

function abrir_modal_editar_qr(idactiv, id) {
    $.post("ajax/codigos_qr.php?op=obtener_qr", { id: id }, function(data) {
        data = JSON.parse(data);
        if (!data || !data.id) { bootbox.alert("No se encontró el código QR."); return; }

        $("#qredit_idactiv").val(idactiv);
        $("#qredit_id").val(id);
        $("#qr_nombre").val(data.nombre);
        $("#qr_contenido").val(data.contenido);
        $("#qr_color_dots").val(data.color_frente || "#042C49");
        $("#qr_color_bg").val(data.color_fondo || "#ffffff");
        $("#qr_error_correction").val(data.nivel_correccion || "M");
        $("#qr_size").val(300); $("#qr_size_val").text(300);
        $("#qr_margin").val(10); $("#qr_margin_val").text(10);

        $("#qr_dots_style").val(data.estilo_puntos || "rounded");
        $("#dots_style_group .qr-dot-btn").removeClass("active");
        $("#dots_style_group .qr-dot-btn[data-val='" + (data.estilo_puntos || "rounded") + "']").addClass("active");

        $("#qr_corners_style").val("dot");
        $("#corners_style_group .qr-dot-btn").removeClass("active");
        $("#corners_style_group .qr-dot-btn[data-val='dot']").addClass("active");

        logoDataUrl_modal = null;
        $("#qr_logo").val("");
        $("#qr_logo_preview").hide().attr("src", "");
        $("#qr_logo_clear").hide();

        $("#modalEditarQR").modal("show");
    });
}

$(document).on('shown.bs.modal', '#modalEditarQR', function() {
    initQR_modal();
});

function guardar_edicion_qr_modal() {
    var idactiv   = $("#qredit_idactiv").val();
    var id        = $("#qredit_id").val();
    var nombre    = $("#qr_nombre").val().trim();
    var contenido = $("#qr_contenido").val().trim();

    if (!nombre || !contenido) {
        bootbox.alert("Es necesario ingresar el nombre y el contenido del código QR.");
        return;
    }

    qrCode_modal.getRawData('png').then(function(blob) {
        var reader = new FileReader();
        reader.onloadend = function() {
            $.post("ajax/codigos_qr.php?op=editar_qr", {
                id: id,
                nombre: nombre,
                contenido: contenido,
                color_frente: $("#qr_color_dots").val(),
                color_fondo: $("#qr_color_bg").val(),
                estilo_puntos: $("#qr_dots_style").val(),
                nivel_correccion: $("#qr_error_correction").val(),
                imagen_base64: reader.result
            }, function(res) {
                res = JSON.parse(res);
                if (!res.ok) {
                    bootbox.alert("Error al guardar" + (res.msg ? ": " + res.msg : "."));
                    return;
                }
                $("#modalEditarQR").modal("hide");
                bootbox.alert("✓ Código QR actualizado.");
                if ($("#ver_idactiv").val() == idactiv) {
                    var url_publica   = $("#ver_seccion_qr").data("url");
                    var nombre_evento = $("#ver_seccion_qr").data("nombre");
                    cargar_seccion_qr(idactiv, url_publica, nombre_evento);
                }
            });
        };
        reader.readAsDataURL(blob);
    });
}

document.addEventListener("DOMContentLoaded", function() {
    $('#dots_style_group').on('click', '.qr-dot-btn', function() {
        $('#dots_style_group .qr-dot-btn').removeClass('active');
        $(this).addClass('active');
        $('#qr_dots_style').val($(this).data('val'));
        updateQR_modal();
    });

    $('#corners_style_group').on('click', '.qr-dot-btn', function() {
        $('#corners_style_group .qr-dot-btn').removeClass('active');
        $(this).addClass('active');
        $('#qr_corners_style').val($(this).data('val'));
        updateQR_modal();
    });

    $('#qr_contenido, #qr_color_dots, #qr_color_bg, #qr_error_correction').on('input change', updateQR_modal);

    $('#qr_size').on('input', function() {
        $('#qr_size_val').text($(this).val());
        updateQR_modal();
    });

    $('#qr_margin').on('input', function() {
        $('#qr_margin_val').text($(this).val());
        updateQR_modal();
    });

    $('#qr_logo').on('change', function(e) {
        var file = e.target.files[0];
        if (!file) return;
        var reader = new FileReader();
        reader.onloadend = function() {
            logoDataUrl_modal = reader.result;
            $('#qr_logo_preview').attr('src', logoDataUrl_modal).show();
            $('#qr_logo_clear').show();
            updateQR_modal();
        };
        reader.readAsDataURL(file);
    });

    $('#qr_logo_clear').on('click', function() {
        logoDataUrl_modal = null;
        $('#qr_logo').val('');
        $('#qr_logo_preview').hide().attr('src', '');
        $(this).hide();
        updateQR_modal();
    });
});

/* ══════════════════════════════════════════════
   EDITAR ENCUESTA (replicado de encuestas.js, para
   poder editar el formulario de registro sin salir
   de esta vista)
══════════════════════════════════════════════ */

document.addEventListener("DOMContentLoaded", function () {
    $('#enc_img_file').on('change', function (e) {
        var file = e.target.files[0];
        if (!file) return;
        var reader = new FileReader();
        reader.onloadend = function () {
            $('#enc_img_preview').attr('src', reader.result).show();
            $('#enc_img_file').data('base64', reader.result);
            $('#enc_imagen_base64').val(reader.result);
            $('#enc_img_clear').show();
        };
        reader.readAsDataURL(file);
    });

    $('#enc_img_clear').on('click', function () {
        limpiar_imagen_encabezado();
    });

    $('#enc_img2_file').on('change', function (e) {
        var file = e.target.files[0];
        if (!file) return;
        var reader = new FileReader();
        reader.onloadend = function () {
            $('#enc_img2_preview').attr('src', reader.result).show();
            $('#enc_imagen_secundaria_base64').val(reader.result);
            $('#enc_img2_clear').show();
        };
        reader.readAsDataURL(file);
    });

    $('#enc_img2_clear').on('click', function () {
        limpiar_imagen_secundaria();
    });
});

function limpiar_imagen_encabezado() {
    $('#enc_img_file').val('').data('base64', null);
    $('#enc_img_preview').hide().attr('src', '');
    $('#enc_img_clear').hide();
    $('#enc_imagen_base64').val('');
}

function limpiar_imagen_secundaria() {
    $('#enc_img2_file').val('');
    $('#enc_img2_preview').hide().attr('src', '');
    $('#enc_img2_clear').hide();
    $('#enc_imagen_secundaria_base64').val('');
}

function editar_encuesta(id) {
    $.post('ajax/encuestas.php?op=obtener_encuesta', { id: id }, function (data) {
        data = JSON.parse(data);
        var e = data.encuesta;
        $('#enc_id').val(e.id);
        $('#enc_titulo').val(e.titulo);
        $('#enc_descripcion').val(e.descripcion || '');
        $('#enc_fecha_inicio').val(e.fecha_inicio || '');
        $('#enc_fecha_fin').val(e.fecha_fin || '');
        limpiar_imagen_encabezado();
        limpiar_imagen_secundaria();
        if (e.imagen_base64 && e.imagen_base64.length > 10) {
            $('#enc_img_preview').attr('src', e.imagen_base64).show();
            $('#enc_img_file').data('base64', e.imagen_base64);
            $('#enc_imagen_base64').val(e.imagen_base64);
            $('#enc_img_clear').show();
        }
        if (e.imagen_secundaria_base64 && e.imagen_secundaria_base64.length > 10) {
            $('#enc_img2_preview').attr('src', e.imagen_secundaria_base64).show();
            $('#enc_imagen_secundaria_base64').val(e.imagen_secundaria_base64);
            $('#enc_img2_clear').show();
        }
        $('#qb_lista').empty();
        qb_idx = 0;
        $('#modalEncuestaTitulo').text('Editar encuesta');
        data.preguntas.forEach(function (p) {
            agregar_pregunta(p);
        });
        actualizar_vacio();
        $('#modalEncuesta').modal('show');
    });
}

function agregar_pregunta(datos) {
    var idx  = qb_idx++;
    var tipo = (datos && datos.tipo) ? datos.tipo : 'libre';

    var opciones_html = '';
    if (datos && datos.opciones && datos.opciones.length) {
        datos.opciones.forEach(function (op) {
            opciones_html += opcion_row_html(op);
        });
    }

    var $q = $('\
<div class="qb-pregunta" data-idx="' + idx + '">\
  <div class="card-header">\
    <div class="d-flex align-items-center">\
      <span class="qb-num font-weight-bold mr-2" style="color:#042C49;min-width:28px;">P' + (idx + 1) + '</span>\
      <select class="form-control form-control-sm qb-tipo">\
        <option value="libre">Respuesta libre</option>\
        <option value="opcion_multiple">Opción múltiple</option>\
        <option value="casillas">Casillas (múltiple)</option>\
        <option value="verdadero_falso">Verdadero / Falso</option>\
        <option value="si_no">Sí / No</option>\
        <option value="calificacion">Calificación (1-5)</option>\
      </select>\
    </div>\
    <div class="d-flex align-items-center">\
      <label class="qb-requerida-lbl mr-3 mb-0">\
        <input type="checkbox" class="qb-requerida mr-1"> Requerida\
      </label>\
      <button class="btn btn-sm btn-danger qb-eliminar">✕</button>\
    </div>\
  </div>\
  <div class="card-body">\
    <input type="text" class="form-control mb-2 qb-texto" placeholder="Escribe tu pregunta aquí...">\
    <div class="qb-opciones-area" style="display:none;">\
      <div class="qb-opciones-list">' + opciones_html + '</div>\
      <button type="button" class="btn btn-sm btn-outline-secondary qb-add-opcion mt-1">+ Agregar opción</button>\
    </div>\
    <div class="qb-preview-hint"></div>\
  </div>\
</div>');

    $q.find('.qb-tipo').val(tipo);
    if (datos && datos.pregunta)   $q.find('.qb-texto').val(datos.pregunta);
    if (datos && datos.requerida)  $q.find('.qb-requerida').prop('checked', true);

    actualizar_tipo_ui($q, tipo);
    if (!opciones_html && (tipo === 'opcion_multiple' || tipo === 'casillas')) {
        agregar_opcion_a($q);
        agregar_opcion_a($q);
    }

    $q.find('.qb-tipo').on('change', function () {
        var t = $(this).val();
        actualizar_tipo_ui($q, t);
    });

    $q.find('.qb-eliminar').on('click', function () {
        $q.remove();
        renumerar_preguntas();
        actualizar_vacio();
    });

    $q.find('.qb-add-opcion').on('click', function () {
        agregar_opcion_a($q);
    });

    $('#qb_lista').append($q);
    actualizar_vacio();
}

function opcion_row_html(valor) {
    return '<div class="qb-opcion-row">' +
        '<input type="text" class="qb-opcion-input form-control form-control-sm" value="' + escHtml(valor) + '" placeholder="Opción...">' +
        '<button type="button" class="qb-remove-opcion">✕</button>' +
        '</div>';
}

function agregar_opcion_a($q) {
    var $row = $(opcion_row_html(''));
    $row.find('.qb-remove-opcion').on('click', function () { $row.remove(); });
    $q.find('.qb-opciones-list').append($row);
}

function actualizar_tipo_ui($q, tipo) {
    var $hint = $q.find('.qb-preview-hint');
    var $opc  = $q.find('.qb-opciones-area');
    $hint.text('');
    $opc.hide();

    if (tipo === 'opcion_multiple' || tipo === 'casillas') {
        $opc.show();
        $hint.text(tipo === 'opcion_multiple' ? '(Una sola respuesta)' : '(Múltiples respuestas)');
    } else if (tipo === 'verdadero_falso') {
        $hint.text('Opciones fijas: Verdadero · Falso');
    } else if (tipo === 'si_no') {
        $hint.text('Opciones fijas: Sí · No');
    } else if (tipo === 'calificacion') {
        $hint.text('Escala de 1 (muy malo) a 5 (excelente)');
    } else {
        $hint.text('El participante escribirá su respuesta libremente');
    }
}

function renumerar_preguntas() {
    $('#qb_lista .qb-pregunta').each(function (i) {
        $(this).find('.qb-num').text('P' + (i + 1));
    });
}

function actualizar_vacio() {
    var hay = $('#qb_lista .qb-pregunta').length > 0;
    $('#qb_vacio').toggle(!hay);
}

function serializar_preguntas() {
    var preguntas = [];
    $('#qb_lista .qb-pregunta').each(function () {
        var $q   = $(this);
        var tipo = $q.find('.qb-tipo').val();
        var p = {
            tipo:     tipo,
            pregunta: $q.find('.qb-texto').val().trim(),
            requerida: $q.find('.qb-requerida').is(':checked') ? 1 : 0,
            opciones: [],
        };
        if (tipo === 'opcion_multiple' || tipo === 'casillas') {
            $q.find('.qb-opcion-input').each(function () {
                var v = $(this).val().trim();
                if (v) p.opciones.push(v);
            });
        }
        preguntas.push(p);
    });
    return preguntas;
}

function guardar_encuesta() {
    var titulo = $('#enc_titulo').val().trim();
    if (!titulo) { bootbox.alert('El título es obligatorio.'); return; }

    var preguntas = serializar_preguntas();
    if (!preguntas.length) { bootbox.alert('Agrega al menos una pregunta.'); return; }

    var ok = true;
    preguntas.forEach(function (p, i) {
        if (!p.pregunta) { bootbox.alert('La pregunta ' + (i + 1) + ' no tiene texto.'); ok = false; }
        if ((p.tipo === 'opcion_multiple' || p.tipo === 'casillas') && p.opciones.length < 2) {
            bootbox.alert('La pregunta ' + (i + 1) + ' necesita al menos 2 opciones.'); ok = false;
        }
    });
    if (!ok) return;

    var id = $('#enc_id').val();
    if (!id) { bootbox.alert('No se encontró la encuesta a editar.'); return; }

    var datos = {
        id:                       id,
        titulo:                   titulo,
        descripcion:              $('#enc_descripcion').val().trim(),
        fecha_inicio:             $('#enc_fecha_inicio').val(),
        fecha_fin:                $('#enc_fecha_fin').val(),
        imagen_base64:            $('#enc_imagen_base64').val(),
        imagen_secundaria_base64: $('#enc_imagen_secundaria_base64').val(),
        preguntas:                JSON.stringify(preguntas),
    };

    $.post('ajax/encuestas.php?op=editar_encuesta', datos, function (data) {
        var r;
        try { r = JSON.parse(data); } catch (e) {
            bootbox.alert('Error de servidor al guardar la encuesta.');
            return;
        }
        if (r.ok) {
            $('#modalEncuesta').modal('hide');
            bootbox.alert('✓ Formulario de registro actualizado.');
            var idactiv = $('#ver_idactiv').val();
            if (idactiv) {
                $.post('ajax/encuestas.php?op=obtener_por_actividad', {idactiv: idactiv}, function (res) {
                    res = JSON.parse(res);
                    pintar_seccion_formulario(idactiv, res.encuesta);
                });
            }
        } else {
            bootbox.alert('Error al guardar' + (r.msg ? ': ' + r.msg : '.'));
        }
    });
}

function escHtml(s) {
    if (!s) return '';
    return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

document.addEventListener("DOMContentLoaded", function() { init(); });