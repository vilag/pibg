<!DOCTYPE html>
<html lang="es">
<head>
<link rel="manifest" href="manifest.webmanifest">
<meta name="theme-color" content="#F2125E">
<link rel="apple-touch-icon" href="images/icons/apple-touch-icon.png">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="apple-mobile-web-app-title" content="PIBG">
<script>
(function(){
	var esApp = false;
	try { if (window.Capacitor && typeof window.Capacitor.isNativePlatform === 'function' && window.Capacitor.isNativePlatform()) esApp = true; } catch(e){}
	if (!esApp && window.matchMedia && window.matchMedia('(display-mode: standalone)').matches) esApp = true;
	if (!esApp && window.navigator && window.navigator.standalone === true) esApp = true;
	if (esApp) document.documentElement.className += ' pibg-app-instalada';
})();
</script>
<title>Calendario de actividades | Primera Iglesia Bautista de Guadalajara</title>
<link href="images/iconos/icono.png" rel="icon">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="Calendario completo de actividades de la Primera Iglesia Bautista de Guadalajara">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="styles/bootstrap4/bootstrap.min.css">
<link href="plugins/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="plugins/OwlCarousel2-2.2.1/owl.carousel.css">
<link rel="stylesheet" type="text/css" href="plugins/OwlCarousel2-2.2.1/owl.theme.default.css">
<link rel="stylesheet" type="text/css" href="plugins/OwlCarousel2-2.2.1/animate.css">
<link href="plugins/video-js/video-js.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="styles/main_styles.css">
<link rel="stylesheet" type="text/css" href="styles/responsive.css">
<link rel="stylesheet" type="text/css" href="styles/calendario_publico.css?v=<?php echo(rand()); ?>">

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.26.0/moment.min.js"></script>
<script src="js/jquery-3.2.1.min.js"></script>
</head>
<body>

<div class="super_container">

	<?php
		require('header.php');
	?>

	<div class="home" style="height: 320px;">
		<input id="input_vista" type="hidden" value="1">
		<div class="home_background parallax_background parallax-window" data-parallax="scroll" data-image-src="images/about/about_background.jpg" data-speed="0.8"></div>
		<div class="home_container">
			<div class="container" style="margin-top: 50px;">
				<div class="row">
					<div class="col">
						<div class="home_content text-center">
							<div class="home_title" style="font-size: 35px;">Calendario de actividades</div>
							<div class="breadcrumbs"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="cal_pub">
		<div class="container">

			<div class="cal_pub_toolbar">
				<div class="cal_pub_nav">
					<button type="button" class="cal_pub_flecha" id="cal_pub_prev" aria-label="Mes anterior">
						<i class="fa fa-angle-left" aria-hidden="true"></i>
					</button>
					<div class="cal_pub_titulo" id="cal_pub_titulo">&nbsp;</div>
					<button type="button" class="cal_pub_flecha" id="cal_pub_next" aria-label="Mes siguiente">
						<i class="fa fa-angle-right" aria-hidden="true"></i>
					</button>
				</div>
				<div class="cal_pub_selects">
					<select id="cal_pub_sel_mes" class="cal_pub_select" aria-label="Mes"></select>
					<select id="cal_pub_sel_anio" class="cal_pub_select" aria-label="Año"></select>
					<button type="button" class="cal_pub_hoy" id="cal_pub_hoy">Hoy</button>
				</div>
			</div>

			<div class="cal_pub_grid_card">
				<div class="cal_pub_semana">
					<div>Lun</div><div>Mar</div><div>Mié</div><div>Jue</div><div>Vie</div><div>Sáb</div><div>Dom</div>
				</div>
				<div class="cal_pub_grid" id="cal_pub_grid"></div>
			</div>

			<div class="cal_pub_leyenda"><span class="cal_pub_punto"></span> Transmisión en vivo por YouTube</div>

			<div class="cal_pub_detalle" id="cal_pub_detalle">
				<div class="cal_pub_detalle_titulo" id="cal_pub_detalle_titulo">Selecciona un día</div>
				<div class="cal_pub_detalle_lista" id="cal_pub_detalle_lista">
					<div class="cal_pub_detalle_vacio">Da clic en un día del calendario para ver sus actividades.</div>
				</div>
			</div>

		</div>
	</div>

<?php
	require('footer.php');
?>

</div>
<script type="text/javascript" src="scripts/calendario_publico.js?v=<?php echo(rand()); ?>"></script>
</body>
</html>
