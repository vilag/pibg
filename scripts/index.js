consul_dia();
listar_cal();
listar_lecturas();
listar_activ_dest();
function consul_dia()
{
	
		var dia=moment().format('dddd');
		var anio=moment().format('YYYY');
		var fecha=moment().format('YYYY-MM-DD');
		var hora=moment().format('HH:mm:ss');
		var fecha_hora=fecha+" "+hora;

		$.post("ajax/index.php?op=contar_act_dia",{fecha_hora:fecha_hora},function(data, status)
		{
			data = JSON.parse(data);
            
			//if (data!=null) {

				//var idcal = data.idcal;

				var dia = data.dia;			
				var mes = data.mes;
				var hora = data.hora;
				
				var dia_nomt = data.dia_nom;
				var nom_activ = data.nom_activ;
				var tema = data.tema;
				$("#tema_actividad").text(tema);
				//alert(data.tema);
				 
				tipo = data.tipo;

				if (mes==1) {var mes_text = "enero";}
				if (mes==2) {var mes_text = "febrero";}
				if (mes==3) {var mes_text = "marzo";}
				if (mes==4) {var mes_text = "abril";}
				if (mes==5) {var mes_text = "mayo";}
				if (mes==6) {var mes_text = "junio";}
				if (mes==7) {var mes_text = "julio";}
				if (mes==8) {var mes_text = "agosto";}
				if (mes==9) {var mes_text = "septiembre";}
				if (mes==10) {var mes_text = "octubre";}
				if (mes==11) {var mes_text = "noviembre";}
				if (mes==12) {var mes_text = "diciembre";}

				var dia_nom = dia_nomt.substring(0,3);

				$("#nombre_actvidad").text(nom_activ);
				//$("#nombre_actvidad2").text(nom_activ);
				//alert(tema);
				
				

				$("#dia_sp").text(dia_nomt);
				$("#dia_sp_num").text(dia);
				$("#mes_sp").text(mes_text);
				
				
				$("#hora_sp").text(hora+" hrs.");
				
			// }else{

			// 	var dia=moment().format('dddd');
			// 	if (dia=="Monday") {dia=1;}
			// 	if (dia=="Tuesday") {dia=2;}
			// 	if (dia=="Wednesday") {dia=3;}
			// 	if (dia=="Thursday") {dia=4;}
			// 	if (dia=="Friday") {dia=5;}
			// 	if (dia=="Saturday") {dia=6;}
			// 	if (dia=="Sunday") {dia=7;}

			// 	//alert(dia);

			// 	var hora=moment().format('HH:mm:ss');
			// 	// var hora='10:30:00';
			// 	// var dia=4;

			// 	$.post("ajax/index.php?op=buscar_activ_sem",{dia:dia,hora:hora},function(data, status)
			// 	{
			// 		data = JSON.parse(data);

			// 		if (data==null) {

			// 			$.post("ajax/index.php?op=buscar_primer_dia",function(data, status)
			// 			{
			// 				data = JSON.parse(data);

			// 				$("#nombre_actvidad").text(data.nombre);
			// 				$("#dia_sp").text(data.dia);
			// 				$("#hora_sp").text(data.hora+" hrs.");

			// 			});
						
			// 		}else{
			// 			$("#nombre_actvidad").text(data.nombre);
			// 			$("#dia_sp").text(data.dia);
			// 			$("#hora_sp").text(data.hora+" hrs.");
			// 		}

			// 	});


			// }
            
					

		});

}

function listar_cal(){
	var fecha=moment().format('YYYY-MM-DD');
	var hora=moment().format('HH:mm:ss');
	var fecha_hora=fecha+" "+hora;

	$.post("ajax/index.php?op=listar_calendario&fecha="+fecha_hora,function(r){
		$("#box_calendario").html(r);
	});
}

function listar_lecturas(){
	var fecha=moment().format('YYYY-MM-DD');
	var hora=moment().format('HH:mm:ss');
	//var fecha_hora=fecha+" "+hora;
	//alert(fecha_hora);
	$.post("ajax/index.php?op=listar_lecturas&fecha="+fecha,function(r){
		$("#p_lecturas_dia").html(r);
	});
}

function listar_activ_dest(){
	var fecha=moment().format('YYYY-MM-DD');

	// $.post("ajax/index.php?op=activ_esp1",{fecha:fecha},function(data, status)
	// {
	// 	data = JSON.parse(data);
	// 	//alert(data.nombre);
	// 	//$("#nom_activ_esp_1").text(data.nombre);
	// 	$("#detalle_activ_esp1").text(data.nombre);

	// });


	$.post("ajax/index.php?op=listar_activ_dest&fecha="+fecha,function(r){
		$("#box_activ_des").html(r);
	});
}

function enviar_datos_nuevo_contacto(){
	var nombre = $("#nombre").val();
	var telefono = $("#telefono").val();
	var fecha=moment().format('YYYY-MM-DD');
	var hora=moment().format('HH:mm:ss');
	var fecha_hora=fecha+" "+hora;

	$.post("ajax/index.php?op=enviar_datos_nuevo_contacto",{nombre:nombre,telefono:telefono,fecha_hora:fecha_hora},function(data, status)
	{
		data = JSON.parse(data);

		alert("Datos enviados exitosamente, pronto nos pondremos en contácto con usted. Dios lo bendiga.");
		$("#nombre").val("");
		$("#telefono").val("");

	});
}

function salir_video(){
	document.getElementById("intro_nueva_vida").style.display = "none";
}