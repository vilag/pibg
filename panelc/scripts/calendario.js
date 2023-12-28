listar_dias();

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

    //alert(dia);

	if (dia=="Monday") {dia="Lunes";}
	if (dia=="Tuesday") {dia="Martes";}
	if (dia=="Wednesday") {dia="Miercoles";}
	if (dia=="Thursday") {dia="Jueves";}
	if (dia=="Friday") {dia="Viernes";}
	if (dia=="Saturday") {dia="Sabado";}
	if (dia=="Monday") {dia="Domingo";}

	$("#dia").val(dia);
}

function mostrar_horas_capt(){
	document.getElementById("horas_capt").style.display="block";

	$.post("ajax/calendario.php?op=listar_horas",function(r){
		$("#horas_capt").html(r);					
	});
}

function set_hora(idcal,hora){
	//alert(hora)
	$("#hora_actividad").val(hora);
	document.getElementById("horas_capt").style.display="none";
}