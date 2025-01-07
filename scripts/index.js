mostrar_texto_principal();
listar_lecturas();
//count_activ_esp();

//consul_sem_esp();
ver_vista();
consul_dia();
listar_cal();

listar_activ_dest();

set_live();
AOS.init();

// setTimeout(() => {
// 	$("#punto_live").removeClass("entrada").addClass("salida");
// 	setInterval(() => {
// 		$("#punto_live").removeClass("salida").addClass("entrada");
// 		setTimeout(() => {
// 			$("#punto_live").removeClass("entrada").addClass("salida");
// 		}, 1000);
// 	}, 1000);
// }, 1000);

var libro = "";
var capitulo = 0;
var versiculo1 = 0;
var versiculo2 = 0;

const button = document.getElementById("boton_prueba_notif");

// setTimeout(() => {
// 	prueba_notif();
// }, 5000);

// setTimeout(() => {
// 	prueba_notif();
// }, 10000);

function prueba_notif(){
	Notification.requestPermission().then(perm => {
		if (perm === "granted") {
			const notification = new Notification("Example notification", {
				body: "Texto Biblico",
				data: { hello: "word" },
				icon: "Logo Centered.png",
				//tag: "Welcome Message",
			})

			notification.addEventListener("error", e => {
				alert("error")
			})
		}
	})
}

// button.addEventListener("click", () => {
// 	Notification.requestPermission().then(perm => {
// 		if (perm === "granted") {
// 			const notification = new Notification("Example notification", {
// 				body: Math.random(),
// 				data: { hello: "word" },
// 				icon: "Logo Centered.png",
// 				//tag: "Welcome Message",
// 			})

// 			notification.addEventListener("error", e => {
// 				alert("error")
// 			})
// 		}
// 	})
// })


// let notification;
// let interval;
// document.addEventListener("visibilitychange", () => {
// 	if (document.visibilityState === "hidden") {
// 		const leaveDate = new Date()
// 		interval = setInterval(() => {
// 			notification = new Notification("Come back please", {
// 				body: `You have been gone for ${Math.round(
// 					(new Date() - leaveDate)-1000
// 				)} seconds`,
// 				tag: "Come Back",
// 			})
// 		}, 100)
// 	}else{
// 		if (interval) {
// 			clearInterval(interval)
// 		}
// 		if (notification) {
// 			notification.close();
// 		}
// 	}
// })



function consul_dia()
{
	// setInterval(() => {
	// 	setTimeout(() => {
	// 		document.getElementById("punto_live").style.display="none";
	// 	}, 1000);
	// 	setTimeout(() => {
	// 		document.getElementById("punto_live").style.display="block";
	// 	}, 2000);
	// }, 3000);


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
				// $("#tema_actividad").text(tema);
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
				// alert(nom_activ);
				// document.getElementById("nombre_actvidad").innerHTML = nom_activ;
				// document.getElementById("nombre_actvidad").value = nom_activ;
				$("#nombre_actividad").text(nom_activ);
				if (tema!="") {
					document.getElementById("tema_actividad").style.display="block";
					$("#tema_actividad").text(tema);
				}else{
					document.getElementById("tema_actividad").style.display="none";
					$("#tema_actividad").text("");
				}

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
	//alert(fecha_hora);
	$.post("ajax/index.php?op=listar_calendario&fecha="+fecha_hora,function(r){
		$("#box_calendario").html(r);
	});
}
var array_lecturas_dia = [];
function listar_lecturas(){
	var fecha=moment().format('YYYY-MM-DD');
	var hora=moment().format('HH:mm:ss');
	var fecha_hora=fecha+" "+hora;
	//alert(fecha_hora);
	$.post("ajax/index.php?op=listar_lecturas",{fecha:fecha},function(data, status)
	{
		data = JSON.parse(data);
		//console.log(data);
		array_lecturas_dia = data;
		// console.log("array_lecturas_dia");
		// console.log(array_lecturas_dia);

		//var cont_ld= 0;
		var text_citas = "";
		for (var index = 0; index < array_lecturas_dia.length; index++) {
			//console.log(resultado[index].number);
			var fila_lect='<div id="fila_lect'+index+'" style="margin-bottom: 10px;">'+					
				'<p style="color: #fff; font-size: 20px; line-height: 27px; margin-bottom: 20px;"><b>'+array_lecturas_dia[index].libro+' '+array_lecturas_dia[index].capitulo+'</b></p>'+
					'<div id="fila_cap_ver'+index+'" style="line-height: 27px;"></div>'+
				'</div>';
			var fila_lect_cita='<div onclick="position_window('+index+');" id="fila_lect_cita'+index+'" class="box_lecturas_dia">'+					
				'<p style="color: #fff; font-size: 18px; line-height: 27px;">'+array_lecturas_dia[index].libro+' '+array_lecturas_dia[index].capitulo+'</p>'+
				'</div>';
			$('#p_lecturas_dia').append(fila_lect);
			$('#box_citas_lecturas').append(fila_lect_cita);
			
			//cont_ld++;
			if (index==0) {
				text_citas = text_citas+array_lecturas_dia[index].libro+" "+array_lecturas_dia[index].capitulo+", ";
			}else{
				if (array_lecturas_dia[index-1].libro==array_lecturas_dia[index].libro) {
					text_citas = text_citas+array_lecturas_dia[index].capitulo+", ";
				}else{
					text_citas = text_citas+array_lecturas_dia[index].libro+" "+array_lecturas_dia[index].capitulo+", ";	
				}
			}
				
			
		}

		//var text = "Hello world!"; 
		var result = text_citas.substring(0, (text_citas.length - 2));
		$("#citasdia_resumen").text(result);
		getCita();

	});

	// libro = "Juan";
	// capitulo = 21;
	// versiculo1 = 20;
	// versiculo2 = 21;

	
	
}

//var extraheightScroll = 50;
function position_window(indice){
	//return;
	
	if (indice==0) {
		const element = document.getElementById("p_lecturas_dia");
		element.scrollTo(0, 0);
	}else{
		var heightTot = 0;
		for (var index = 0; index < indice; index++) {
			var height = document.getElementById("fila_cap_ver"+index);
    		var tam_height = height.clientHeight;
			heightTot = heightTot+tam_height+50;			
		}
		const element = document.getElementById("p_lecturas_dia");
		element.scrollTo(0, (heightTot));
	}

	//extraheightScroll = extraheightScroll+5;
	//console.log(heightTot);
	//console.log("height");
	//alert("entra");

	//window.scrollTo(0, 1500);
}

async function getCita(){
	try {
		// console.log("array_lecturas_dia");
		// console.log(array_lecturas_dia);
		
		for (var index = 0; index < array_lecturas_dia.length; index++) {
			
			const apiUrl = 'https://bible-api.deno.dev/api/read/rv1960/'+array_lecturas_dia[index].libro+'/'+array_lecturas_dia[index].capitulo+'';
			const response = await fetch(apiUrl);
			const resultado = await response.json();
			// console.log("resultado API");
			// console.log(resultado);

			//var cont= 0;
			for (var index2 = 0; index2 < resultado.vers.length; index2++) {
				//console.log(resultado[index].number);
				var fila='<div id="fila_lv'+index2+'" style="margin-bottom: 10px;">'+					
					'<p style="color: #fff; font-size: 20px; line-height: 27px; font-weight: 300;">'+resultado.vers[index2].number+'. '+resultado.vers[index2].verse+'</p>'+
				'</div>';
				$('#fila_cap_ver'+index).append(fila);
				//cont++;
				
			}
		}



		// $("#libro_cita").text(libro);
		// $("#capitulo_cita").text(capitulo);
		// $("#versiculo1_cita").text(":"+versiculo1);
		// $("#versiculo2_cita").text("-"+versiculo2);
		// const apiUrl='https://bible-api.deno.dev/api/read/rv1960/'+libro+'/'+capitulo+'/'+versiculo1+'-'+versiculo2+'';
		// const response = await fetch(apiUrl);
		// //const { results } = await response.json();
		// const resultado = await response.json();
		// console.log("resultado API");
		// console.log(resultado);

		// var cont= 0;
		// for (let index = 0; index < resultado.length; index++) {
		// 	//console.log(resultado[index].number);
		// 	var fila='<div id="fila'+cont+'" style="margin-bottom: 10px;">'+					
		// 		'<p style="color: #fff; font-size: 20px; line-height: 27px; font-weight: 300;">'+resultado[index].number+'. '+resultado[index].verse+'</p>'+
		// 	'</div>';
		// 	$('#p_lecturas_dia').append(fila);
		// 	cont++;
			
		// }


	} catch (error) {
		console.error(error);
	}
}


// async function getCita(){
// 	try {
// 		$("#libro_cita").text(libro);
// 		$("#capitulo_cita").text(capitulo);
// 		$("#versiculo1_cita").text(":"+versiculo1);
// 		$("#versiculo2_cita").text("-"+versiculo2);
// 		const apiUrl='https://bible-api.deno.dev/api/read/rv1960/'+libro+'/'+capitulo+'/'+versiculo1+'-'+versiculo2+'';
// 		const response = await fetch(apiUrl);
// 		//const { results } = await response.json();
// 		const resultado = await response.json();
// 		console.log("resultado API");
// 		console.log(resultado);

// 		var cont= 0;
// 		for (let index = 0; index < resultado.length; index++) {
// 			//console.log(resultado[index].number);
// 			var fila='<div id="fila'+cont+'" style="margin-bottom: 10px;">'+					
// 				'<p style="color: #fff; font-size: 20px; line-height: 27px; font-weight: 300;">'+resultado[index].number+'. '+resultado[index].verse+'</p>'+
// 			'</div>';
// 			$('#p_lecturas_dia').append(fila);
// 			cont++;
			
// 		}


// 	} catch (error) {
// 		console.error(error);
// 	}
// }

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
var dialog_motivo;
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
				message: '<p style="line-height: 20px;">Gracias por compartirnos su petición, con gusto estarémos orando por usted.</p>'+
				'<p style="line-height: 20px;">Nos encantaria que pudiera visitarnos, a continuación le compartimos los horarios de nuestras actividades semanales:</p>'+
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

function consul_sem_esp(){
	var fecha=moment().format('YYYY-MM-DD');
	$.post("ajax/index.php?op=consul_sem_esp",{fecha:fecha},function(data, status)
	{
		data = JSON.parse(data);
		$("#nom_activ_sem_esp").text(data.nombre);
		$("#det_activ_sem_esp").text(data.detalle);
		//alert(data.enlace);
		// if (data.enlace!="") {
		// 	document.getElementById("enlace_redirect").style.display="block";
		// 	$("#enlace_redirect").attr("href",data.enlace);
		// }else{
		// 	document.getElementById("enlace_redirect").style.display="none";
		// }

		//console.log(data.nombre);

	});
}

function mostrar_texto_principal()
{
	$("#nom_activ_sem_esp").text("");
	var texto_principal = "Y esta es la vida eterna: que te conozcan a ti, el único Dios verdadero, y a Jesucristo, a quien has enviado.";
	var cita = "Juan 17:3";
	var arr_texto_principal = texto_principal.split(' ');
	var num_char_tp = arr_texto_principal.length;
	var contador = 0;
	setTimeout(() => {
		setInterval(() => {
			if (contador<num_char_tp) {
				var fila = '<label class="fade-in">'+arr_texto_principal[contador]+'&nbsp;</label>';
				$('#nom_activ_sem_esp').append(fila);
			}
			if (contador==(num_char_tp-1)) {
				$("#det_activ_sem_esp").text(cita);
				$("#det_activ_sem_esp").addClass("fade-in");
			}
			contador++;
		}, 100);
	}, 2000);
	setTimeout(() => {
		count_activ_esp();
	}, 3000);
	
}

//var cant_activ_des = 0;
var array_activ_des = [];
var cont2 = 0;	
var cont_consec = 0;
function count_activ_esp(){
	cont2 = 0;	
	cont_consec = 0;
	var fecha=moment().format('YYYY-MM-DD');
	$.post("ajax/index.php?op=count_activ_esp",{fecha:fecha},function(data, status)
	{
		data = JSON.parse(data);
		//console.log(data);
		array_activ_des = data;
		//cant_activ_des = data.length;
		var cont=0;
		var desfase = 3;
		for (var index = 0; index < data.length; index++) {
			desfase = desfase+0.1;
			var fila='<div id="fila'+cont+'" class="estilo_back_ae">'+
				'<style>'+
					'.fade-in_'+cont+'{-webkit-animation:fade-in_1 1.2s cubic-bezier(.39,.575,.565,1.000) '+desfase+'s both;animation:fade-in_'+cont+' 1.2s cubic-bezier(.39,.575,.565,1.000) '+desfase+'s both}'+
					'@-webkit-keyframes fade-in_'+cont+'{0%{opacity:0}100%{opacity:1}}@keyframes fade-in_'+cont+'{0%{opacity:0}100%{opacity:1}}'+
				'</style>'+
				'<div id="mini'+cont+'" class="estilo_mini_princ1 fade-in_'+cont+'" style="'+
					'background-image: url('+data[index].imagen+');'+
					'background-repeat: no-repeat;'+
					'background-size: 400px 350px;'+
					'background-position: center; z-index: 1;">'+
					'<div style="padding: 15px; background-image: linear-gradient(rgba(0,0,0,0), rgba(0,0,0,0.8)); height: 100%; width: 100%; border-radius: 10px;">'+
						'<p class="yanone-kaffeesatz">'+data[index].nombre_corto+'</p>'+
					'</div>'+
				'</div>'+
            '</div>';
			var filaBig='<div id="filaBig'+cont+'">'+
				
				'<div id="mini'+cont+'_2" class="estilo_mini_princ1-in" style="'+
					'background-image: url('+data[index].imagen+');'+
					'background-repeat: no-repeat;'+
					'background-size: cover;'+
					'background-position: center; display:none;">'+
					'<div style="padding: 15px; background-image: linear-gradient(rgba(0,0,0,0), rgba(0,0,0,1)); height: 100%; width: 100%;">'+
						'<p class="yanone-kaffeesatz"></p>'+
					'</div>'+
				'</div>'+
            '</div>';
			cont++;
			$('#content_actividades_destacadas').append(fila);
			$('#content_actividades_destacadas').append(filaBig);

		}

		animar_contenedores();
		
	});
}



function animar_contenedores(){
	var cant_next = array_activ_des.length+cont_consec;
	setTimeout(() => {
		$("#mini"+cont_consec).removeClass("fade-in_"+cont_consec);
		$("#mini"+cont_consec).addClass("fade-out");
	}, 8000);

	setTimeout(() => {
		$("#mini"+cont2+"_2").addClass("fade-in");								
		document.getElementById("mini"+cont2+"_2").style.display = "block";
		setTimeout(() => {
			var cont_ant = 	cont2-1;	
			$("#mini"+cont_ant+"_2").removeClass("fade-in");
			$("#mini"+cont_ant+"_2").addClass("fade-out");	
		}, 600);	
		$("#nom_activ_sem_esp").text("");
		$("#nom_activ_sem_esp").removeClass("fade-in");
		$("#det_activ_sem_esp").removeClass("fade-in");
		$("#nom_activ_sem_esp").addClass("fade-in");
		$("#det_activ_sem_esp").addClass("fade-in");
		$("#nom_activ_sem_esp").text(array_activ_des[cont2].nombre);
		$("#det_activ_sem_esp").text(array_activ_des[cont2].detalle);
		

	}, 8300);

	setTimeout(() => {
		$(".estilo_back_ae").addClass("slide-left");
	}, 8800);

	setTimeout(() => {
		var fila='<div id="fila'+cant_next+'" class="estilo_back_ae">'+
			'<style>'+
				'.fade-in_'+cant_next+'{-webkit-animation:fade-in_1 1.2s cubic-bezier(.39,.575,.565,1.000) both;animation:fade-in_'+cant_next+' 1.2s cubic-bezier(.39,.575,.565,1.000) both}'+
				'@-webkit-keyframes fade-in_'+cant_next+'{0%{opacity:0}100%{opacity:1}}@keyframes fade-in_'+cant_next+'{0%{opacity:0}100%{opacity:1}}'+
			'</style>'+
			'<div id="mini'+cant_next+'" class="estilo_mini_princ1 fade-in_'+cant_next+'" style="'+
				'background-image: url('+array_activ_des[cont2].imagen+');'+
				'background-repeat: no-repeat;'+
				'background-size: 400px 350px;'+
				'background-position: center; z-index: 1;">'+
				'<div style="padding: 15px; background-image: linear-gradient(rgba(0,0,0,0), rgba(0,0,0,0.8)); height: 100%; width: 100%; border-radius: 10px;">'+
						'<p class="yanone-kaffeesatz">'+array_activ_des[cont2].nombre_corto+'</p>'+
				'</div>'+
			'</div>'+
		'</div>';
		$('#content_actividades_destacadas').append(fila);
		$("#fila" + cont_consec).remove();	
		$(".estilo_back_ae").removeClass("slide-left");
								
		
		
	}, 9300);

	setTimeout(() => {

		cont2++;
		if (cont2==array_activ_des.length) {
			setTimeout(() => {

				var ult_ext1 = array_activ_des.length;
				var ult_ext2 = array_activ_des.length+3;
				
				for (var index = ult_ext1; index <= ult_ext2; index++) {					
					$("#mini"+index).removeClass("fade-in_"+index);
					$("#mini"+index).addClass("fade-out");
				}

				var cont_ult = array_activ_des.length-1;	
				$("#mini"+cont_ult+"_2").removeClass("fade-in");
				$("#mini"+cont_ult+"_2").addClass("fade-out");
				$("#nom_activ_sem_esp").text("");
				$("#det_activ_sem_esp").text("");

				setTimeout(() => {
					for (var index = ult_ext1; index <= ult_ext2; index++) {					
						$("#fila" + index).remove();
					}
					for (var index = 0; index <= array_activ_des.length; index++) {					
						$("#filaBig" + index).remove();
					}
					
					
					
				}, 2000);

				setTimeout(() => {
					
					mostrar_texto_principal();
					array_activ_des = [];
				}, 5000);
				
				// return;
				// cont2=0;
				// var cont_ult = array_activ_des.length-1;	
				// $("#mini"+cont_ult+"_2").removeClass("fade-in");
				// $("#mini"+cont_ult+"_2").addClass("fade-out");
				// $("#nom_activ_sem_esp").text("Y esta es la vida eterna: que te conozcan a ti, el único Dios verdadero, y a Jesucristo, a quien has enviado.");
				// $("#det_activ_sem_esp").text("Juan 17:3");
				
				// cant_next++;
				// cont_consec++;
				// animar_contenedores();
			}, 8000);			
		}else{
			$("#nom_activ_sem_esp").removeClass("fade-in");
			$("#det_activ_sem_esp").removeClass("fade-in");
			cant_next++;
			cont_consec++;
			animar_contenedores();
		}
		
	}, 10000);

}



