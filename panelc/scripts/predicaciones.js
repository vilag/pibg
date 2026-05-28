var modo_edicion_pred = false;
var idsermones_editar = 0;

document.addEventListener("DOMContentLoaded", function () {
    cargar_selectores();
    listar_sermones();
});

function cargar_selectores() {
    $.getJSON("ajax/predicaciones.php?op=listar_categorias", function (cats) {
        var sel = $("#categoria");
        sel.find("option:not(:first)").remove();
        $.each(cats, function (i, c) {
            sel.append('<option value="' + c.id + '">' + c.nombre + '</option>');
        });
    });
    $.getJSON("ajax/predicaciones.php?op=listar_series", function (series) {
        var sel = $("#serie_id");
        sel.find("option:not(:first)").remove();
        $.each(series, function (i, s) {
            sel.append('<option value="' + s.id + '">' + s.nombre + '</option>');
        });
    });
}

function toggle_orden_serie() {
    var val = $("#serie_id").val();
    if (val && val !== "0") {
        $("#bloque_orden_serie").show();
    } else {
        $("#bloque_orden_serie").hide();
        $("#orden_serie").val(1);
    }
}

function listar_sermones() {
    $.post("ajax/predicaciones.php?op=listar", function (r) {
        $("#tabla_sermones").html(r);
    });
}

function subir_imagen_pred() {
    var archivo = document.getElementById("input_imagen_pred").files[0];
    if (!archivo) return;
    var fd = new FormData();
    fd.append("imagen", archivo);
    $("#upload_estado_pred").text("Subiendo...");
    $.ajax({
        url: "ajax/predicaciones.php?op=subir_imagen",
        type: "POST",
        data: fd,
        processData: false,
        contentType: false,
        success: function (data) {
            data = JSON.parse(data);
            if (data.ok) {
                $("#ruta_imagen_pred").val(data.ruta);
                $("#img_preview_pred").attr("src", "../" + data.ruta).show();
                $("#upload_estado_pred").text(archivo.name);
            } else {
                $("#upload_estado_pred").text("Error: " + data.msg);
                bootbox.alert(data.msg);
            }
        }
    });
}

function limpiar_form_pred() {
    modo_edicion_pred = false;
    idsermones_editar = 0;
    $("#idsermones_edit").val("");
    $("#nom_sermon").val("");
    $("#fecha_eti").val("");
    $("#predicador").val("");
    $("#actividad").val("");
    $("#categoria").val("");
    $("#serie_id").val("0");
    $("#orden_serie").val(1);
    $("#bloque_orden_serie").hide();
    $("#ruta_imagen_pred").val("");
    $("#input_imagen_pred").val("");
    $("#img_preview_pred").attr("src", "").hide();
    $("#upload_estado_pred").text("Sin imagen seleccionada");
    $("#predicacion").val("");
    $("#btn_guardar_pred").text("Guardar predicación");
    $("#titulo_form").text("Nueva Predicación");
    $("#btn_cancelar_pred").hide();
}

function editar_sermon(id) {
    $.getJSON("ajax/predicaciones.php?op=get_one&id=" + id, function (data) {
        if (!data) { bootbox.alert("No se pudo cargar el sermón."); return; }
        modo_edicion_pred = true;
        idsermones_editar = id;
        $("#idsermones_edit").val(id);
        $("#nom_sermon").val(data.nom_sermon);
        $("#fecha_eti").val(data.fecha_eti);
        $("#predicador").val(data.predicador);
        $("#actividad").val(data.actividad);
        $("#categoria").val(data.categoria);
        $("#serie_id").val(data.serie_id || "0");
        $("#orden_serie").val(data.orden_serie || 1);
        toggle_orden_serie();
        $("#predicacion").val(data.predicacion);
        if (data.imagen) {
            $("#ruta_imagen_pred").val(data.imagen);
            $("#img_preview_pred").attr("src", "../" + data.imagen).show();
            $("#upload_estado_pred").text("Imagen actual cargada");
        } else {
            $("#img_preview_pred").hide();
            $("#upload_estado_pred").text("Sin imagen");
        }
        $("#btn_guardar_pred").text("Actualizar predicación");
        $("#titulo_form").text("Editar Predicación");
        $("#btn_cancelar_pred").show();
        window.scrollTo({ top: 0, behavior: "smooth" });
    });
}

function guardar_sermon() {
    var nom   = $("#nom_sermon").val().trim();
    var fecha = $("#fecha_eti").val().trim();
    var pred  = $("#predicador").val().trim();
    var activ = $("#actividad").val();
    var cat   = $("#categoria").val();
    var conte = $("#predicacion").val().trim();

    if (!nom)   { bootbox.alert("El título es obligatorio."); return; }
    if (!fecha) { bootbox.alert("La fecha es obligatoria."); return; }
    if (!pred)  { bootbox.alert("El predicador es obligatorio."); return; }
    if (!activ) { bootbox.alert("Selecciona el tipo de actividad."); return; }
    if (!cat)   { bootbox.alert("Selecciona una categoría."); return; }
    if (!conte) { bootbox.alert("El contenido de la transcripción es obligatorio."); return; }

    var datos = {
        nom_sermon:  nom,
        fecha_eti:   fecha,
        predicador:  pred,
        actividad:   activ,
        categoria:   cat,
        serie_id:    $("#serie_id").val() || 0,
        orden_serie: $("#orden_serie").val() || 0,
        imagen:      $("#ruta_imagen_pred").val(),
        predicacion: conte
    };

    var op = modo_edicion_pred ? "actualizar" : "guardar";
    if (modo_edicion_pred) datos.idsermones = idsermones_editar;

    $.post("ajax/predicaciones.php?op=" + op, datos, function (r) {
        var resp = typeof r === "string" ? JSON.parse(r) : r;
        if (resp.ok) {
            var msg = modo_edicion_pred ? "Predicación actualizada." : "Predicación guardada correctamente.";
            bootbox.alert(msg, function () {
                limpiar_form_pred();
                listar_sermones();
            });
        } else {
            bootbox.alert("Ocurrió un error al guardar.");
        }
    });
}

function borrar_sermon(id) {
    bootbox.confirm({
        message: "¿Eliminar esta predicación? Esta acción no se puede deshacer.",
        buttons: {
            confirm: { label: "Sí, eliminar", className: "btn-danger" },
            cancel:  { label: "Cancelar",     className: "btn-secondary" }
        },
        callback: function (result) {
            if (result) {
                $.post("ajax/predicaciones.php?op=borrar", { idsermones: id }, function () {
                    listar_sermones();
                });
            }
        }
    });
}

// ─── Gestión de categorías ───────────────────────────────────────
function abrir_modal_categorias() {
    cargar_lista_categorias();
    $("#modal_categorias").modal("show");
}

function cargar_lista_categorias() {
    $.getJSON("ajax/predicaciones.php?op=listar_categorias", function (cats) {
        var html = "";
        $.each(cats, function (i, c) {
            html += '<li style="display:flex;justify-content:space-between;align-items:center;padding:6px 0;border-bottom:1px solid #eee;">'
                  + '<span>' + c.nombre + '</span>'
                  + '<button onclick="borrar_categoria(' + c.id + ');" style="background-color:rgb(129,2,2);padding:4px 10px;border-radius:5px;border:none;color:#fff;cursor:pointer;font-size:12px;">Eliminar</button>'
                  + '</li>';
        });
        $("#lista_categorias").html(html || "<li style='color:#aaa;'>No hay categorías registradas.</li>");
    });
}

function guardar_categoria() {
    var nombre = $("#nueva_categoria").val().trim();
    if (!nombre) { bootbox.alert("Escribe el nombre de la categoría."); return; }
    $.post("ajax/predicaciones.php?op=guardar_categoria", { nombre: nombre }, function (r) {
        var resp = typeof r === "string" ? JSON.parse(r) : r;
        if (resp.ok) {
            $("#nueva_categoria").val("");
            cargar_lista_categorias();
            cargar_selectores();
        } else {
            bootbox.alert("Error al guardar la categoría.");
        }
    });
}

function borrar_categoria(id) {
    bootbox.confirm({
        message: "¿Eliminar esta categoría? Los sermones vinculados quedarán sin categoría.",
        buttons: {
            confirm: { label: "Sí", className: "btn-danger" },
            cancel:  { label: "No", className: "btn-secondary" }
        },
        callback: function (result) {
            if (result) {
                $.post("ajax/predicaciones.php?op=borrar_categoria", { idcat: id }, function () {
                    cargar_lista_categorias();
                    cargar_selectores();
                });
            }
        }
    });
}
