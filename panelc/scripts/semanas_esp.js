function init(){
    listar_activ_sem_esp();
}

function guardar_activ_sem(){

    var fecha1 = $("#fecha_actividad1").val();
    var fecha2 = $("#fecha_actividad2").val();
    var nombre = $("#nom_actividad_sem").val();
    var detalle = $("#detalle_actividad").val();

    $.post("ajax/semanas_esp.php?op=guardar_activ_sem",{
        fecha1:fecha1,
        fecha2:fecha2,
        nombre:nombre,
        detalle:detalle
    },function(data, status)
	{
		data = JSON.parse(data);

		alert("Registro guardado exitosamente");
		$("#fecha_actividad1").val("");
		$("#fecha_actividad2").val("");
		$("#nom_actividad_sem").val("");
		$("#detalle_actividad").val("");

	});
}

function listar_activ_sem_esp(){
    $.post("ajax/semanas_esp.php?op=listar_activ_sem_esp",function(r){
		$("#temas_sem").html(r);
	});
}

init();