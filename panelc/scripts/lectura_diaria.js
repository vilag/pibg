function init()
{
    listar_lecturas();
}

function listar_lecturas()
{
	$.post("ajax/lectura_diaria.php?op=listar_lecturas",function(r){
		$("#dias_lectura").html(r);					
	});
}

function guardar_lectura()
{
    var fecha = $("#fecha").val();
    var cita_biblica = $("#cita_biblica").val();
    var link = $("#link").val();

    if (fecha!="" && cita_biblica!="" && link!="") {
        $.post("ajax/lectura_diaria.php?op=guardar_lectura",{fecha:fecha,cita_biblica:cita_biblica,link:link},function(data, status)
        {
            data = JSON.parse(data);

            alert("Lectura guardada exitosamente");
            $("#fecha").val("");
            $("#cita_biblica").val("");
            $("#link").val("");
            listar_lecturas();

        });
    }else{
        alert("Es necesario llenar todos los campos");
    }
    
}

init();