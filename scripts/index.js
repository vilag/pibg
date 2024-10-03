ver_vista();
consul_dia();
listar_cal();
listar_lecturas();
listar_activ_dest();
mostrar_btn_salida_video();
set_live();


// setTimeout(() => {
// 	$("#punto_live").removeClass("entrada").addClass("salida");
// 	setInterval(() => {
// 		$("#punto_live").removeClass("salida").addClass("entrada");
// 		setTimeout(() => {
// 			$("#punto_live").removeClass("entrada").addClass("salida");
// 		}, 1000);
// 	}, 1000);
// }, 1000);



function consul_dia()
{
	setInterval(() => {
		setTimeout(() => {
			document.getElementById("punto_live").style.display="none";
		}, 1000);
		setTimeout(() => {
			document.getElementById("punto_live").style.display="block";
		}, 2000);
	}, 3000);


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
				//alert(nom_activ);
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

	// var video = document.getElementById("video_intro");
	// video.pause();

}

function mostrar_btn_salida_video()
{
	setTimeout(() => {
		document.getElementById("btn_salir_video").style.display="block";
		document.getElementById("btn_cerrar_video").style.display = "block";
		// $("#content_video").removeClass("estilo_video").addClass("estilo_video_2");
	}, 102000);
}

function ver_vista()
{
	var vista = $("#input_vista").val();
	if (vista==1) {
		$("#barra_menu").removeClass("estilo_nav").addClass("estilo_nav_index");
	}



}

function enviar_mensaje(){

	$("#a_enviar_mensaje_est").attr("href","https://api.whatsapp.com/send?phone=3330230905&text=Hola, me gustaria unirme a un estudio bíblico.");
}

// function ocultar_punto_live()
// {
// 	$("#punto_live").removeClass("fade-in").addClass("fade-out");
// }

function set_live()
{
	var dia=moment().format('dddd');
	var anio=moment().format('YYYY');
	var fecha=moment().format('YYYY-MM-DD');
	var hora=moment().format('HH:mm:ss');



	if ((dia=="Sunday" && hora >= "12:00:00" && hora <= "14:00:00")) {
		//alert(hora);
		document.getElementById("caja_live_dom").style.display="block";
	}else{
		document.getElementById("caja_live_dom").style.display="none";
	}

	if ((dia=="Sunday" && hora >= "18:00:00" && hora <= "20:00:00")) {
		document.getElementById("caja_live_dom2").style.display="block";
	}else{
		document.getElementById("caja_live_dom2").style.display="none";
	}

	if ((dia=="Wednesday" && hora >= "19:00:00" && hora <= "20:30:00")) {
		document.getElementById("caja_live_mie").style.display="block";
	}else{
		document.getElementById("caja_live_mie").style.display="none";
	}

	if ((dia=="Friday" && hora >= "19:00:00" && hora <= "20:30:00")) {
		document.getElementById("caja_live_vie").style.display="block";
	}else{
		document.getElementById("caja_live_vie").style.display="none";
	}

	// if (dia>"Sunday" && dia<="Wednesday") {
	// 	document.getElementById("eti_activ_mie").style.backgroundColor = "#FF5C00";
	// }else{
	// 	document.getElementById("eti_activ_mie").style.backgroundColor = "none";
	// }
	// if (dia>"Wednesday" && dia<="Friday") {
	// 	document.getElementById("eti_activ_vie").style.backgroundColor = "#FF5C00";
	// }else{
	// 	document.getElementById("eti_activ_vie").style.backgroundColor = "none";
	// }
	// if (dia>"Friday" && dia<="Sunday") {

	// 	document.getElementById("eti_activ_dom").style.backgroundColor = "#FF5C00";

	// 	if ( dia=="Sunday" && hora >= "14:00:00") {
	// 		document.getElementById("eti_activ_dom").style.backgroundColor = "none";
	// 		document.getElementById("eti_activ_dom2").style.backgroundColor = "#FF5C00";
	// 	}
	// }else{
	// 	document.getElementById("eti_activ_dom").style.backgroundColor = "none";
	// }
}



window.onscroll = function() {
	// console.log("Vertical: " + window.scrollY);
	// console.log("Horizontal: " + window.scrollX);
	var posicionVertical = window.scrollY;

	if (posicionVertical>100) {
		$("#barra_menu").removeClass("estilo_nav_index").addClass("estilo_nav");
	}else{
		$("#barra_menu").removeClass("estilo_nav").addClass("estilo_nav_index");
	}
};

var avance_scroll = 0;

function siguiente(){
	avance_scroll = avance_scroll+400;
	const element = document.getElementById("content_ministerios");
    element.scrollTo(avance_scroll, 0);
}
let dialog_motivo;
function guardar_motivo()
{
	var nombre_peticion = $("#nombre_peticion").val();
	var telefono_peticion = $("#telefono_peticion").val();
	var motivo_peticion = $("#motivo_peticion").val();
	var fecha=moment().format('YYYY-MM-DD');
	var hora=moment().format('HH:mm:ss');
	var fecha_hora=fecha+" "+hora;

	if (nombre_peticion!="" && motivo_peticion!="") {
		$.post("ajax/index.php?op=guardar_motivo",{nombre_peticion:nombre_peticion,telefono_peticion:telefono_peticion,motivo_peticion:motivo_peticion,fecha_hora:fecha_hora},function(data, status)
		{
			data = JSON.parse(data);

			dialog_motivo = bootbox.dialog({
				message: '<p style="line-height: 20px;">Gracias por compartirnos su petición, con gusto estaremos orando por usted.</p>'+
				'<p style="line-height: 20px;">Nos encantaria que pudiera visitarnos, a continuación le compartimos algunas de nuestras actividades semanales:</p>'+
				'<ul style="margin-top: 15px; margin-bottom: 15px;">'+
					'<li>* Domingo 10:30 am - Escuela dominical.</li>'+
					'<li>* Domingo 12:00 pm - Culto de adoración.</li>'+
					'<li>* Domingo 06:00 pm - Culto de adoración vespertino.</li>'+
					'<li>* Miercoles 07:00 pm - Culto de oración.</li>'+
					'<li>* Viernes 07:00 pm - Culto de estudio biblico.</li>'+
				'</ul>'+
				'<p>Será bienvenido, Dios lo bendiga grandemente. </p>'+
				'<div style="display: flex; justify-content: right; margin-top: 20px;">'+
					'<a href="https://maps.app.goo.gl/azQQqR955ExPy28F8" target="_blank" style="background-color: #2e6d99; color: #fff; padding: 10px 20px; border-radius: 5px; border: none; margin: 2px;">Ver ubicación</a>'+
					'<button style="background-color: #21214f; color: #fff; padding: 10px 20px; border-radius: 5px; border: none; margin: 2px;" onclick="cerrar_alert();">Cerrar</button>'+
				'</div>',
				closeButton: false
				});

				// do something in the background
				

			// bootbox.alert("Gracias por compartirnos su petición estaremos orando por usted, si nos permite nos gustaria escribirle al numero que nos compartió, mientras tanto esta cordialmente invitado a nuestras actividades semanales Dios lo bendiga.");
			$("#nombre_peticion").val("");
			$("#telefono_peticion").val("");
			$("#motivo_peticion").val("");

		});
	}else{
		bootbox.alert("Por favor ayudenos capturado su nombre y motivo de oración, muchas gracias.");
	}

	
}

function cerrar_alert(){
	dialog_motivo.modal('hide');
}