var modo_edicion = false;
var idbiografia_editar = 0;

document.addEventListener("DOMContentLoaded", function () {
    listar_biografias();
});

function listar_biografias() {
    $.post("ajax/biografias.php?op=listar", function (r) {
        $("#tabla_biografias").html(r);
    });
}

function subir_imagen() {
    var archivo = document.getElementById('input_imagen').files[0];
    if (!archivo) return;

    var formData = new FormData();
    formData.append('imagen', archivo);

    $("#upload_estado").text("Subiendo...");
    $("#img_preview").hide();

    $.ajax({
        url: 'ajax/biografias.php?op=subir_imagen',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (data) {
            data = JSON.parse(data);
            if (data.ok) {
                $("#ruta_imagen").val(data.ruta);
                $("#img_preview").attr("src", "../" + data.ruta).show();
                $("#upload_estado").text(archivo.name);
            } else {
                $("#upload_estado").text("Error: " + data.msg);
                bootbox.alert(data.msg);
            }
        },
        error: function () {
            $("#upload_estado").text("Error al conectar.");
            bootbox.alert("No se pudo subir la imagen.");
        }
    });
}

function limpiar_form() {
    $("#idbiografia_edit").val("");
    $("#nombre").val("");
    $("#cargo").val("");
    $("#biografia").val("");
    $("#ruta_imagen").val("");
    $("#input_imagen").val("");
    $("#img_preview").attr("src", "").hide();
    $("#upload_estado").text("Sin imagen seleccionada");
    modo_edicion = false;
    idbiografia_editar = 0;
    $("#btn_guardar").text("Guardar");
    $("#titulo_form").text("Nueva Biografía");
    $("#btn_cancelar_edicion").hide();
}

function editar_biografia(id, nombre, cargo, imagen, biografia) {
    modo_edicion = true;
    idbiografia_editar = id;
    $("#idbiografia_edit").val(id);
    $("#nombre").val(nombre);
    $("#cargo").val(cargo);
    $("#biografia").val(biografia);
    $("#ruta_imagen").val(imagen);

    if (imagen) {
        $("#img_preview").attr("src", "../" + imagen).show();
        $("#upload_estado").text("Imagen actual cargada");
    } else {
        $("#img_preview").hide();
        $("#upload_estado").text("Sin imagen");
    }

    $("#btn_guardar").text("Actualizar");
    $("#titulo_form").text("Editar Biografía");
    $("#btn_cancelar_edicion").show();
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function guardar_biografia() {
    var nombre    = $("#nombre").val().trim();
    var cargo     = $("#cargo").val().trim();
    var biografia = $("#biografia").val().trim();
    var imagen    = $("#ruta_imagen").val().trim();

    if (nombre === "") {
        bootbox.alert("El nombre es obligatorio.");
        return;
    }

    if (modo_edicion) {
        $.post("ajax/biografias.php?op=actualizar", {
            idbiografia: idbiografia_editar,
            nombre:    nombre,
            cargo:     cargo,
            biografia: biografia,
            imagen:    imagen
        }, function () {
            bootbox.alert("Biografía actualizada correctamente.");
            limpiar_form();
            listar_biografias();
        });
    } else {
        $.post("ajax/biografias.php?op=guardar", {
            nombre:    nombre,
            cargo:     cargo,
            biografia: biografia,
            imagen:    imagen
        }, function () {
            bootbox.alert("Biografía guardada correctamente.");
            limpiar_form();
            listar_biografias();
        });
    }
}

function borrar_biografia(id) {
    bootbox.confirm({
        message: "¿Confirmar eliminación de esta biografía?",
        buttons: {
            confirm: { label: 'Sí', className: 'btn-success' },
            cancel:  { label: 'No', className: 'btn-danger' }
        },
        callback: function (result) {
            if (result) {
                $.post("ajax/biografias.php?op=borrar", { idbiografia: id }, function () {
                    listar_biografias();
                    bootbox.alert("Biografía eliminada.");
                });
            }
        }
    });
}
