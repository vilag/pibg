mostrar_btn_salida_video();
function salir_video(){
	document.getElementById("intro_nueva_vida").style.display = "none";

	// var video = document.getElementById("video_intro");
	// video.pause();

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

function mostrar_btn_salida_video()
{
	// setTimeout(() => {
		document.getElementById("btn_salir_video").style.display="block";
		document.getElementById("btn_cerrar_video").style.display = "block";
		// $("#content_video").removeClass("estilo_video").addClass("estilo_video_2");
	// }, 102000);
}