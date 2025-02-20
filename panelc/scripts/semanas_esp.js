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

    if (fecha1!="" && fecha2!="" && nombre!="" && nombre_corto!="" && imagen!="") {
        $.post("ajax/semanas_esp.php?op=guardar_activ_sem",{
            fecha1:fecha1,
            fecha2:fecha2,
            nombre:nombre,
            nombre_corto:nombre_corto,
            detalle:detalle,
            imagen:imagen
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

init();