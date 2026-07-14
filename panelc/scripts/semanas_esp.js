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
                '<a href="encuestas.php?editar=' + encuesta.id + '" class="btn btn-sm btn-secondary">Editar preguntas en Encuestas</a>' +
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
            '<br><a href="' + qr.imagen_base64 + '" download="qr_evento_' + idactiv + '.png" class="btn btn-sm btn-secondary" style="margin-top:8px;">Descargar</a>'
        );
    } else {
        var nombre_escapado = (nombre_evento || '').replace(/'/g, "");
        $sec.html(
            '<p style="color:#888;">Aún no se ha generado un código QR para este formulario.</p>' +
            '<button class="btn btn-sm btn-primary" onclick="generar_qr_evento(' + idactiv + ', \'' + url_publica + '\', \'' + nombre_escapado + '\');">Generar código QR</button>'
        );
    }
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
    if (typeof QRCodeStyling === 'undefined') {
        bootbox.alert("No se pudo cargar el generador de códigos QR. Recarga la página e intenta de nuevo.");
        return;
    }

    var qrCode = new QRCodeStyling({
        width: 300,
        height: 300,
        margin: 10,
        type: 'canvas',
        data: url_publica,
        dotsOptions: { color: '#042C49', type: 'rounded' },
        cornersSquareOptions: { type: 'dot', color: '#042C49' },
        cornersDotOptions: { color: '#042C49' },
        backgroundOptions: { color: '#ffffff' },
        qrOptions: { errorCorrectionLevel: 'M' }
    });

    qrCode.getRawData('png').then(function(blob) {
        var reader = new FileReader();
        reader.onload = function() {
            var imagen_base64 = reader.result;
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
                    pintar_seccion_qr(idactiv, {imagen_base64: imagen_base64}, url_publica, nombre_evento);
                }
            });
        };
        reader.readAsDataURL(blob);
    });
}

document.addEventListener("DOMContentLoaded", function() { init(); });