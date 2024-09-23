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