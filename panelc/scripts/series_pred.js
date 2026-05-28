var modo_edicion_serie = false;
var idserie_editar = 0;

document.addEventListener("DOMContentLoaded", function () {
    listar_series();
});

function listar_series() {
    $.post("ajax/series_pred.php?op=listar", function (r) {
        $("#tabla_series").html(r);
    });
}

function subir_imagen_serie() {
    var archivo = document.getElementById("input_imagen_serie").files[0];
    if (!archivo) return;
    var fd = new FormData();
    fd.append("imagen", archivo);
    $("#upload_estado_serie").text("Subiendo...");
    $.ajax({
        url: "ajax/series_pred.php?op=subir_imagen",
        type: "POST",
        data: fd,
        processData: false,
        contentType: false,
        success: function (data) {
            data = JSON.parse(data);
            if (data.ok) {
                $("#ruta_imagen_serie").val(data.ruta);
                $("#img_preview_serie").attr("src", "../" + data.ruta).show();
                $("#upload_estado_serie").text(archivo.name);
            } else {
                $("#upload_estado_serie").text("Error: " + data.msg);
                bootbox.alert(data.msg);
            }
        }
    });
}

function limpiar_form_serie() {
    modo_edicion_serie = false;
    idserie_editar = 0;
    $("#idserie_edit").val("");
    $("#nombre_serie").val("");
    $("#descripcion_serie").val("");
    $("#fecha_inicio_serie").val("");
    $("#fecha_fin_serie").val("");
    $("#estatus_serie").val("1");
    $("#ruta_imagen_serie").val("");
    $("#input_imagen_serie").val("");
    $("#img_preview_serie").attr("src", "").hide();
    $("#upload_estado_serie").text("Sin imagen seleccionada");
    $("#btn_guardar_serie").text("Guardar serie");
    $("#titulo_form_serie").text("Nueva Serie Especial");
    $("#btn_cancelar_serie").hide();
}

function editar_serie(id) {
    $.getJSON("ajax/series_pred.php?op=get_one&id=" + id, function (data) {
        if (!data) { bootbox.alert("No se pudo cargar la serie."); return; }
        modo_edicion_serie = true;
        idserie_editar = id;
        $("#idserie_edit").val(id);
        $("#nombre_serie").val(data.nombre);
        $("#descripcion_serie").val(data.descripcion);
        $("#fecha_inicio_serie").val(data.fecha_inicio || "");
        $("#fecha_fin_serie").val(data.fecha_fin || "");
        $("#estatus_serie").val(data.estatus);
        if (data.imagen) {
            $("#ruta_imagen_serie").val(data.imagen);
            $("#img_preview_serie").attr("src", "../" + data.imagen).show();
            $("#upload_estado_serie").text("Imagen actual cargada");
        } else {
            $("#img_preview_serie").hide();
            $("#upload_estado_serie").text("Sin imagen");
        }
        $("#btn_guardar_serie").text("Actualizar serie");
        $("#titulo_form_serie").text("Editar Serie");
        $("#btn_cancelar_serie").show();
        window.scrollTo({ top: 0, behavior: "smooth" });
    });
}

function guardar_serie() {
    var nombre = $("#nombre_serie").val().trim();
    if (!nombre) { bootbox.alert("El nombre de la serie es obligatorio."); return; }

    var datos = {
        nombre:       nombre,
        descripcion:  $("#descripcion_serie").val().trim(),
        fecha_inicio: $("#fecha_inicio_serie").val(),
        fecha_fin:    $("#fecha_fin_serie").val(),
        imagen:       $("#ruta_imagen_serie").val(),
        estatus:      $("#estatus_serie").val()
    };

    var op = modo_edicion_serie ? "actualizar" : "guardar";
    if (modo_edicion_serie) datos.idserie = idserie_editar;

    $.post("ajax/series_pred.php?op=" + op, datos, function (r) {
        var resp = typeof r === "string" ? JSON.parse(r) : r;
        if (resp.ok) {
            var msg = modo_edicion_serie ? "Serie actualizada." : "Serie creada correctamente.";
            bootbox.alert(msg, function () {
                limpiar_form_serie();
                listar_series();
            });
        } else {
            bootbox.alert("Ocurrió un error al guardar.");
        }
    });
}

function borrar_serie(id) {
    bootbox.confirm({
        message: "¿Eliminar esta serie? Los sermones vinculados quedarán sin serie asignada.",
        buttons: {
            confirm: { label: "Sí, eliminar", className: "btn-danger" },
            cancel:  { label: "Cancelar",     className: "btn-secondary" }
        },
        callback: function (result) {
            if (result) {
                $.post("ajax/series_pred.php?op=borrar", { idserie: id }, function () {
                    listar_series();
                });
            }
        }
    });
}

function ver_sermones_serie(id, nombre) {
    $("#modal_serie_titulo").text("Sermones — " + nombre);
    $("#tabla_sermones_serie").html('<tr><td colspan="6" style="text-align:center;color:#aaa;">Cargando...</td></tr>');
    $("#modal_sermones_serie").modal("show");

    $.getJSON("ajax/series_pred.php?op=sermones_de_serie&id=" + id, function (sermones) {
        if (!sermones.length) {
            $("#tabla_sermones_serie").html('<tr><td colspan="6" style="text-align:center;color:#aaa;">No hay sermones asignados a esta serie.</td></tr>');
            return;
        }
        var html = "";
        $.each(sermones, function (i, s) {
            html += '<tr>'
                  + '<td>' + s.orden_serie + '</td>'
                  + '<td><a href="../blog.php?id=' + s.idsermones + '" target="_blank" style="color:#042C49;">' + s.nom_sermon + '</a></td>'
                  + '<td>' + s.predicador + '</td>'
                  + '<td>' + s.fecha_eti + '</td>'
                  + '<td>' + s.actividad + '</td>'
                  + '<td><a href="../blog.php?id=' + s.idsermones + '" target="_blank" style="background-color:#28a745;padding:4px 10px;border-radius:5px;color:#fff;font-size:11px;text-decoration:none;">Ver</a></td>'
                  + '</tr>';
        });
        $("#tabla_sermones_serie").html(html);
    });
}
