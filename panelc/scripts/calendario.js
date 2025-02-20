listar_dias();
listar_activ_sem();

function listar_dias()
{
	$.post("ajax/calendario.php?op=listar_dias",function(r){
		$("#dias_calendario").html(r);					
	});
}

function mostrar_dia()
{
    var fecha_actividad = $("#fecha_actividad").val();
    //alert(fecha_actividad);

	var fecha=moment().format('YYYY-MM-DD');
	var hora=moment().format('HH:mm:ss');
	var fecha_hora=fecha+" "+hora;

	var dia = moment(fecha_actividad).format('dddd');

   // alert(dia);

	if (dia=="Monday") {dia="Lunes";}
	if (dia=="Tuesday") {dia="Martes";}
	if (dia=="Wednesday") {dia="Miercoles";}
	if (dia=="Thursday") {dia="Jueves";}
	if (dia=="Friday") {dia="Viernes";}
	if (dia=="Saturday") {dia="Sabado";}
	if (dia=="Sunday") {dia="Domingo";}

	$("#dia").val(dia);
}

// function mostrar_horas_capt(){
// 	document.getElementById("horas_capt").style.display="block";

// 	$.post("ajax/calendario.php?op=listar_horas",function(r){
// 		$("#horas_capt").html(r);					
// 	});
// }

function set_hora(idcal,hora){
	//alert(hora)
	$("#hora_actividad").val(hora);
	document.getElementById("horas_capt").style.display="none";
}

// function mostrar_nombre_capt(){
// 	document.getElementById("nombre_act_capt").style.display="block";

// 	$.post("ajax/calendario.php?op=listar_nombres",function(r){
// 		$("#nombre_act_capt").html(r);					
// 	});
// }

function set_nombre(idcal,nom_activ){
	//alert(hora)
	$("#nom_actividad").val(nom_activ);
	document.getElementById("nombre_act_capt").style.display="none";
}

function listar_activ_sem(){

	$.post("ajax/calendario.php?op=listar_activ_sem",function(r){
		$("#box_act_sem").html(r);					
	});
}

function set_dia_sem(idactiv, nombre, hora){
	$("#hora_actividad").val(hora);
	$("#nom_actividad").val(nombre);
}

function guardar_dia_calendario()
{
	var fecha_actividad = $("#fecha_actividad").val();
	var hora_actividad = $("#hora_actividad").val();
	var dia = $("#dia").val();
	var nom_actividad = $("#nom_actividad").val();
	var tema_actividad = $("#tema_actividad").val();
	var tipo_actividad = $("#tipo_actividad").val();
	var tipo_actividad = $('input[name="tipo_actividad"]:checked').val();
	var tipo_act = 0;
	//alert(tipo_actividad);

	if (tipo_actividad=="Si" || tipo_actividad=="No") {
		if (tipo_actividad=="Si") {
			tipo_act = 1;
		}else{
			tipo_act = 0;
		}
	}else{
		alert("Es necesario seleccionar el estatus de transmisión");
		return;
	}

	var fecha_hora = fecha_actividad+" "+hora_actividad;

	// alert(fecha_actividad);
	// return;
	if (fecha_actividad!="" && hora_actividad!="00:00:00" && nom_actividad!="") {
		$.post("ajax/calendario.php?op=guardar_dia_calendario",{fecha_hora:fecha_hora,dia:dia,nom_actividad:nom_actividad,tema_actividad:tema_actividad,tipo_act:tipo_act},function(data, status)
		{
			data = JSON.parse(data);

			alert("Registro guardado exitosamente");
			$("#fecha_actividad").val("");
			$("#hora_actividad").val("00:00:00");
			$("#dia").val("");
			$("#nom_actividad").val("");
			$("#tema_actividad").val("");
			listar_dias();

		});
	}else{
		bootbox.alert("Es necesario capturar los datos obligatorios: fecha, hora y nombre de actividad.");
	}

	
	
}

function borrar_dia(idcal){
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
                $.post("ajax/calendario.php?op=borrar_dia",{idcal:idcal},function(data, status)
                {
                    data = JSON.parse(data);

                    listar_dias();
                    bootbox.alert("Registro eliminado exitosamente");

                });
            }
        }
    });
}