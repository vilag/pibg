var modo_edicion_pred = false;
var idsermones_editar = 0;

document.addEventListener("DOMContentLoaded", function () {
    cargar_selectores();
    listar_sermones();
});

function cargar_selectores() {
    $.getJSON("ajax/predicaciones.php?op=listar_categorias", function (cats) {
        var html = '';
        $.each(cats, function (i, c) {
            html += '<div class="form-check" style="margin-bottom:3px;">'
                  + '<input class="form-check-input cat-checkbox" type="checkbox" id="cat_cb_' + c.id + '" value="' + c.id + '">'
                  + '<label class="form-check-label" for="cat_cb_' + c.id + '" style="font-size:13px;cursor:pointer;margin-left:4px;">' + c.nombre + '</label>'
                  + '</div>';
        });
        $("#categorias_checkboxes").html(html || '<span style="color:#aaa;font-size:12px;">No hay categorías. Crea una primero.</span>');
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

/* ─── Tipo de contenido: transcripción ↔ archivo ──────────────── */
function cambiar_tipo_contenido(tipo) {
    $("#tipo_contenido_pred").val(tipo);
    if (tipo === 'transcripcion') {
        $("#panel_transcripcion").show();
        $("#panel_archivo").hide();
        $("#btn_tab_transcripcion").css({ background: "#042C49", color: "#fff" });
        $("#btn_tab_archivo").css({ background: "#fff", color: "#042C49" });
    } else {
        $("#panel_transcripcion").hide();
        $("#panel_archivo").show();
        $("#btn_tab_archivo").css({ background: "#042C49", color: "#fff" });
        $("#btn_tab_transcripcion").css({ background: "#fff", color: "#042C49" });
    }
}

function subir_archivo_pred() {
    var archivo = document.getElementById("input_archivo_pred").files[0];
    if (!archivo) return;
    var ext = archivo.name.split('.').pop().toLowerCase();
    if (ext !== 'pdf' && ext !== 'docx') {
        $("#upload_estado_archivo").html('<span style="color:#c00;">Solo se permiten archivos .pdf o .docx</span>');
        return;
    }
    if (archivo.size > 15 * 1024 * 1024) {
        $("#upload_estado_archivo").html('<span style="color:#c00;">El archivo supera el límite de 15 MB</span>');
        return;
    }
    var fd = new FormData();
    fd.append("archivo", archivo);
    $("#upload_estado_archivo").html('⏳ Subiendo <b>' + archivo.name + '</b>...');
    $.ajax({
        url: "ajax/predicaciones.php?op=subir_archivo",
        type: "POST",
        data: fd,
        processData: false,
        contentType: false,
        success: function (r) {
            var data = typeof r === "string" ? JSON.parse(r) : r;
            if (data.ok) {
                if (data.texto) {
                    // Texto extraído correctamente: llenar transcripción y cambiar tab
                    $("#predicacion").val(data.texto);
                    cambiar_tipo_contenido("transcripcion");
                    $("#upload_estado_archivo").html('✅ Texto extraído de <b>' + data.nombre + '</b>.');
                } else {
                    // No se pudo extraer texto: guardar como archivo de descarga
                    $("#ruta_archivo_pred").val(data.ruta);
                    $("#upload_estado_archivo").html(
                        '⚠️ No se pudo extraer el texto de <b>' + data.nombre +
                        '</b>. Se guardará como archivo de descarga.'
                    );
                    $("#bloque_archivo_existente").hide();
                }
            } else {
                $("#upload_estado_archivo").html('<span style="color:#c00;">Error: ' + data.msg + '</span>');
            }
        },
        error: function () {
            $("#upload_estado_archivo").html('<span style="color:#c00;">Error de conexión al subir el archivo.</span>');
        }
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
    $(".cat-checkbox").prop("checked", false);
    $("#serie_id").val("0");
    $("#orden_serie").val(1);
    $("#bloque_orden_serie").hide();
    $("#ruta_imagen_pred").val("");
    $("#input_imagen_pred").val("");
    $("#img_preview_pred").attr("src", "").hide();
    $("#upload_estado_pred").text("Sin imagen seleccionada");
    $("#predicacion").val("");
    // Resetear panel de archivo
    $("#ruta_archivo_pred").val("");
    $("#input_archivo_pred").val("");
    $("#upload_estado_archivo").text("Sin archivo seleccionado");
    $("#bloque_archivo_existente").hide();
    cambiar_tipo_contenido("transcripcion");
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
        // Marcar categorías
        $(".cat-checkbox").prop("checked", false);
        if (data.categorias && data.categorias.length > 0) {
            $.each(data.categorias, function (i, id) {
                $("#cat_cb_" + id).prop("checked", true);
            });
        }
        if (data.archivo_pred) {
            cambiar_tipo_contenido("archivo");
            $("#ruta_archivo_pred").val(data.archivo_pred);
            var nombreArchivo = data.archivo_pred.split('/').pop();
            $("#upload_estado_archivo").html('📄 <b>' + nombreArchivo + '</b>');
            $("#link_archivo_existente").attr("href", "../" + data.archivo_pred);
            $("#bloque_archivo_existente").show();
        } else {
            cambiar_tipo_contenido("transcripcion");
            $("#predicacion").val(data.predicacion);
        }
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
    var tipo  = $("#tipo_contenido_pred").val();

    var cats = [];
    $(".cat-checkbox:checked").each(function () { cats.push(parseInt($(this).val())); });

    if (!nom)          { bootbox.alert("El título es obligatorio."); return; }
    if (!fecha)        { bootbox.alert("La fecha es obligatoria."); return; }
    if (!pred)         { bootbox.alert("El predicador es obligatorio."); return; }
    if (!activ)        { bootbox.alert("Selecciona el tipo de actividad."); return; }
    if (!cats.length)  { bootbox.alert("Selecciona al menos una categoría."); return; }

    if (tipo === 'transcripcion') {
        if (!$("#predicacion").val().trim()) {
            bootbox.alert("El contenido de la transcripción es obligatorio."); return;
        }
    } else {
        if (!$("#ruta_archivo_pred").val()) {
            bootbox.alert("Sube un archivo .docx o .pdf antes de guardar."); return;
        }
    }

    var datos = {
        nom_sermon:   nom,
        fecha_eti:    fecha,
        predicador:   pred,
        actividad:    activ,
        categorias:   cats,
        serie_id:     $("#serie_id").val() || 0,
        orden_serie:  $("#orden_serie").val() || 0,
        imagen:       $("#ruta_imagen_pred").val(),
        predicacion:  tipo === 'transcripcion' ? $("#predicacion").val().trim() : '',
        archivo_pred: tipo === 'archivo'        ? $("#ruta_archivo_pred").val()  : ''
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
            cargar_selectores();  // refresca los checkboxes
        } else {
            bootbox.alert("Error al guardar la categoría.");
        }
    });
}

function borrar_categoria(id) {
    bootbox.confirm({
        message: "¿Eliminar esta categoría? Los sermones vinculados perderán esa categoría.",
        buttons: {
            confirm: { label: "Sí", className: "btn-danger" },
            cancel:  { label: "No", className: "btn-secondary" }
        },
        callback: function (result) {
            if (result) {
                $.post("ajax/predicaciones.php?op=borrar_categoria", { idcat: id }, function () {
                    cargar_lista_categorias();
                    cargar_selectores();  // refresca los checkboxes
                });
            }
        }
    });
}
