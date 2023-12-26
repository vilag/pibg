function init()
{
	list_iconos();

	//alert(dia);
	$.post("ajax/index.php?op=mostrar_list_act",function(r){
	$("#div_list_act").html(r);

		

	});
}



function mostrar_dia()
{
	var fecha=moment().format('YYYY-MM-DD');
	var hora=moment().format('HH:mm:ss');
	var fecha_hora=fecha+" "+hora;

	var dia = moment().format('dddd');

	if (dia=="Monday") {dia="Lunes";}
	if (dia=="Tuesday") {dia="Martes";}
	if (dia=="Wednesday") {dia="Miercoles";}
	if (dia=="Thursday") {dia="Jueves";}
	if (dia=="Friday") {dia="Viernes";}
	if (dia=="Saturday") {dia="Sabado";}
	if (dia=="Monday") {dia="Domingo";}

	$("#dia").val(dia);
}


function list_iconos()
{
	$.post("ajax/index.php?op=list_iconos",function(r){
		$("#iconos_list").html(r);	
		$("#iconos_list2").html(r);				
			        
	});
}

function mostrar_list_act()
{
	document.getElementById("div_list_act").style.display = "block";

	
}	

init();