consul_dia();
listar_cal();
buscar_dia_sem();
function consul_dia()
{
	
		var dia=moment().format('dddd');
		//var mes=moment().format('MMM');
		var anio=moment().format('YYYY');
		var fecha=moment().format('YYYY-MM-DD');
		var hora=moment().format('HH:mm:ss');
		var fecha_hora=fecha+" "+hora;

        //alert(fecha_hora);

		$.post("ajax/index.php?op=contar_act_dia",{fecha_hora:fecha_hora},function(data, status)
		{
			data = JSON.parse(data);
            //alert(data.idcal);
            //return;
			//var num_act = data.num_act;
			var idcal = data.idcal;

			//$("#idcal").val(idcal);
			

					var dia = data.dia;
					
					var mes = data.mes;
					var hora = data.hora;
					//var hora_f = moment().format(hora,'HH:mm');
					var dia_nomt = data.dia_nom;
					var nom_activ = data.nom_activ;
					var tema = data.tema;
					tipo = data.tipo;

					//document.getElementById("caja_principal").style.backgroundImage = "url('"+data.img+"')";

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
					$("#nombre_actvidad2").text(nom_activ);

					$("#dia_sp").text(dia_nomt);
					$("#dia_sp_num").text(dia);
					$("#mes_sp").text(mes_text);
					
					
					$("#hora_sp").text(hora+" hrs.");

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

function buscar_dia_sem(){

	var dia=moment().format('dddd');
	if (dia=="Monday") {dia="Lunes";}
	//alert(dia);

	// $.post("ajax/calendario.php?op=guardar_dia_calendario",{fecha_hora:fecha_hora,dia:dia,nom_actividad:nom_actividad},function(data, status)
	// {
	// 	data = JSON.parse(data);

	// 	alert("Registro guardado exitosamente");

	// });
}

