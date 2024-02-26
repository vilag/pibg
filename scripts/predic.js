ver_vista();

function ver_vista()
{
	var vista = $("#input_vista").val();
    //alert(vista);
	if (vista==1) {
		$("#barra_menu").removeClass("estilo_nav").addClass("estilo_nav_index");
	}else{
        $("#barra_menu").removeClass("estilo_nav_index").addClass("estilo_nav");
    }
	
}