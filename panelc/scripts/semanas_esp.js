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

            alert("Registro guardado exitosamente");
            $("#fecha_actividad1").val("");
            $("#fecha_actividad2").val("");
            $("#nom_actividad_sem").val("");
            $("#nom_actividad_corto_sem").val("");
            $("#detalle_actividad").val("");
            $("#archivo_audio").val("");
            $("#texto_banner").val("");
            $("#video_url").val("");
            listar_activ_sem_esp();

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

document.addEventListener("DOMContentLoaded", function() { init(); });