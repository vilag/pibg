<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="Elearn project">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">

</head>
<body>

	<!-- Header -->

	<header class="header">

	<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-7N34HVRZYW"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-7N34HVRZYW');
</script>

		<!-- Top Bar -->
		<div class="top_bar" style="background-color: rgba(36,52,75,1) !important;">
			<div class="top_bar_container">
				<div class="container">
					<div class="row">
						<div class="col">
							<div class="top_bar_content d-flex flex-row align-items-center justify-content-start">
								<ul class="top_bar_contact_list">
									<!-- <li><div class="question">Have any questions?</div></li> -->
									<li style="cursor: pointer;">
										<a href="https://api.whatsapp.com/send?phone=3330230905" target="_blank"><div><img src="images/iconos/whatsapp.png" alt="" style="width: 18px;">&nbsp;&nbsp;&nbsp;<label style="color: #fff;">(33) 30230905</label></div></a>
										
									</li>
									<li style="cursor: pointer;">
										<a href="tel:+523336144120"><div><img src="images/iconos/telefono.png" alt="" style="width: 18px;">&nbsp;&nbsp;&nbsp;<label style="color: #fff;">(33) 36144120</label></div></a>
										
									</li>
									<li style="cursor: pointer;">
										<a href="mailto:pibgdlar@gmail.com"><div><img src="images/iconos/email.png" alt="" style="width: 18px;">&nbsp;&nbsp;&nbsp;<label style="color: #fff;">pibgdlar@gmail.com</label></div></a>
										
									</li>
									
								</ul>
								<div class="ml-auto">
									<ul>
										<!-- <li><a href="#">Register</a></li>
										<li><a href="#">Login</a></li> -->
										<li style="float: left; margin-left: 15px; padding-top: 5px;"><a href="https://www.youtube.com/@pibguadalajara5203" target="_blank"><img src="images/iconos/youtube_b2.png" alt="" style="width: 20px;"></a></li>
										<li style="float: left; margin-left: 15px; padding-top: 5px;"><a href="https://www.facebook.com/gdlpib" target="_blank"><img src="images/iconos/facebook4.png" alt="" style="width: 15px;"></a></li>
										<li style="float: left; margin-left: 15px; padding-top: 5px;"><a href="https://www.instagram.com/pibgdl/" target="_blank"><img src="images/iconos/instagram_b2.png" alt="" style="width: 15px;"></a></li>
										<li style="float: left; margin-left: 35px;">
											<a href="quien_es_jesus.php">
												<button style="padding: 5px 10px 5px 10px; background-color: #F85E0C; color: #fff; border-radius: 5px;">
												<span>¿Quién es Jesús?</span>
												</button>
											</a>
											
										</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<style>
			/* Default: fondo azul oscuro para que logo y letras blancas sean visibles */
			#barra_menu {
				background-color: #24344B;
			}
			/* Index page - transparente al inicio (index.js aplica esta clase) */
			#barra_menu.estilo_nav_index {
				background-color: rgba(255,255,255,0);
			}
			/* Al hacer scroll (index.js aplica esta clase) */
			#barra_menu.estilo_nav {
				background-color: rgba(36,52,75,1);
			}

			/* Buscador en vivo */
			.search_wrap { position: relative; }
			.search_live_results {
				display: none;
				position: absolute;
				top: 100%;
				left: 0; right: 0;
				background: #fff;
				border-radius: 0 0 10px 10px;
				box-shadow: 0 10px 30px rgba(0,0,0,0.18);
				z-index: 99999;
				max-height: 440px;
				overflow-y: auto;
				border-top: 3px solid #F85E0C;
			}
			.slr_item {
				display: flex;
				align-items: center;
				gap: 12px;
				padding: 11px 16px;
				border-bottom: 1px solid #f0f0f0;
				text-decoration: none;
				color: #333;
				transition: background 0.15s;
			}
			.slr_item:hover { background: #f5f7fa; text-decoration: none; }
			.slr_icon {
				width: 34px; height: 34px;
				border-radius: 50%;
				background: linear-gradient(135deg,#042C49,#24344B);
				display: flex; align-items: center; justify-content: center;
				flex-shrink: 0; color: #fff; font-size: 13px;
			}
			.slr_tipo  { font-size: 10px; color: #F85E0C; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
			.slr_titulo{ font-size: 13px; font-weight: 600; color: #24344B; line-height: 1.3; }
			.slr_sub   { font-size: 11px; color: #888; }
			.slr_ver_todos {
				display: block; text-align: center;
				padding: 11px; background: #24344B;
				color: #fff !important; font-size: 13px; font-weight: 600;
				text-decoration: none !important;
			}
			.slr_ver_todos:hover { background: #F85E0C; }
			.slr_sin_res { padding: 20px; text-align: center; color: #888; font-size: 13px; }
			.slr_cargando{ padding: 16px; text-align: center; color: #aaa; font-size: 13px; }
		</style>

		<!-- Header Content -->
		<div id="barra_menu" class="header_container">
			<div class="container">
				<div class="row">
					<div class="col">
						<div class="header_content d-flex flex-row align-items-center justify-content-start">
							<div class="logo_container">
								<a href="#">
									<div class="logo_content d-flex flex-row align-items-end justify-content-start">
										<div class="logo_img"><a href="./"><img src="images/logos/logo_b.png" alt="" class="logo_principal" style="max-width: 340px; width: 100%; height: auto;"></a></div>
										<!-- <div class="logo_text">learn</div> -->
									</div>
								</a>
							</div>
							<nav class="main_nav_contaner ml-auto">
								<ul class="main_nav">
									<li><a href="./" style="background-color: rgba(0,0,0,0); color: #fff;">Inicio</a></li>
									<!-- <li><a href="about.html">about us</a></li> -->
									<!-- <li><a href="courses.html">courses</a></li> -->
									<li><a href="about-us.php" style="background-color: rgba(0,0,0,0); color: #fff;">Conócenos</a></li>
									<li><a href="predicaciones.php" style="background-color: rgba(0,0,0,0); color: #fff;">Predicaciones</a></li>
										<li><a href="biografias.php" style="background-color: rgba(0,0,0,0); color: #fff;">Biografías</a></li>
                                    <!-- <li><a href="bach.php" style="background-color: rgba(0,0,0,0); color: #fff;">Coro J.S. Bach</a></li> -->
									<!-- <li><a href="lumbrera.php" style="background-color: rgba(0,0,0,0); color: #fff;">Jóvenes Lumbrera</a></li> -->
									
								</ul>
								<div class="search_button" style="margin-left:16px; cursor:pointer;"><i class="fa fa-search" aria-hidden="true" style="color:#fff; font-size:16px;"></i></div>

								<!-- Hamburger -->

								<div class="hamburger menu_mm">
									<i style="color: #CDD0D2 !important;" class="fa fa-bars menu_mm" aria-hidden="true"></i>
								</div>
							</nav>

						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Header Search Panel -->
		<div class="header_search_container">
			<div class="container">
				<div class="row">
					<div class="col">
						<div class="header_search_content d-flex flex-row align-items-center justify-content-end">
							<div class="search_wrap" style="width:100%; max-width:520px;">
								<form action="buscar.php" method="get" class="header_search_form" autocomplete="off">
									<input type="search" name="q" id="search_input_live" class="search_input" placeholder="Buscar predicaciones, biografías..." required="required">
									<button class="header_search_button d-flex flex-column align-items-center justify-content-center" type="submit">
										<i class="fa fa-search" aria-hidden="true"></i>
									</button>
								</form>
								<div class="search_live_results" id="search_live_results"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<script>
		(function () {
			var timer;
			var input    = document.getElementById('search_input_live');
			var dropdown = document.getElementById('search_live_results');
			if (!input || !dropdown) return;

			input.addEventListener('input', function () {
				clearTimeout(timer);
				var q = this.value.trim();
				if (q.length < 2) { dropdown.style.display = 'none'; return; }
				dropdown.innerHTML = '<div class="slr_cargando"><i class="fa fa-spinner fa-spin"></i> Buscando...</div>';
				dropdown.style.display = 'block';
				timer = setTimeout(function () { buscarLive(q); }, 350);
			});

			document.addEventListener('click', function (e) {
				if (!e.target.closest('.search_wrap')) dropdown.style.display = 'none';
			});

			function buscarLive(q) {
				fetch('buscar_ajax.php?q=' + encodeURIComponent(q))
					.then(function (r) { return r.json(); })
					.then(function (data) {
						if (!data.length) {
							dropdown.innerHTML = '<div class="slr_sin_res">Sin resultados para "<b>' + q + '</b>"</div>';
						} else {
							var html = data.map(function (r) {
								return '<a href="' + r.url + '" class="slr_item">' +
									'<div class="slr_icon"><i class="fa ' + r.icono + '"></i></div>' +
									'<div>' +
										'<div class="slr_tipo">' + r.tipo + '</div>' +
										'<div class="slr_titulo">' + r.titulo + '</div>' +
										(r.sub ? '<div class="slr_sub">' + r.sub + '</div>' : '') +
									'</div>' +
								'</a>';
							}).join('');
							html += '<a href="buscar.php?q=' + encodeURIComponent(q) + '" class="slr_ver_todos">' +
								'<i class="fa fa-list" style="margin-right:6px;"></i>Ver todos los resultados' +
							'</a>';
							dropdown.innerHTML = html;
						}
						dropdown.style.display = 'block';
					})
					.catch(function () { dropdown.style.display = 'none'; });
			}
		})();
		</script>
	</header>

	<!-- Menu -->

	<div class="menu d-flex flex-column align-items-end justify-content-start text-right menu_mm trans_400">
		<div class="menu_close_container"><div class="menu_close"><div></div><div></div></div></div>
		<!-- <div class="search">
			<form action="#" class="header_search_form menu_mm">
				<input type="search" class="search_input menu_mm" placeholder="Search" required="required">
				<button class="header_search_button d-flex flex-column align-items-center justify-content-center menu_mm">
					<i class="fa fa-search menu_mm" aria-hidden="true"></i>
				</button>
			</form>
		</div> -->
		<nav class="menu_nav">
			<ul class="menu_mm">
				<li class="menu_mm"><a href="./">Inicio</a></li>
				<li class="menu_mm"><a href="about-us.php">Conócenos</a></li>
				<li class="menu_mm"><a href="blog.php">Predicaciones</a></li>
				<li class="menu_mm"><a href="biografias.php">Biografías</a></li>
				<!-- <li class="menu_mm"><a href="bach.php">Coro J.S. Bach</a></li> -->
				<!-- <li class="menu_mm"><a href="lumbrera.php">Jóvenes Lumbrera</a></li> -->
				<li>
					<a href="quien_es_jesus.php">
						<button class="course_button">
						<span>¿Quién es Jesús?</span><span class="button_arrow"><i class="fa fa-angle-right" aria-hidden="true"></i></span>
						</button>
					</a>
										
				</li>
				<!-- <li class="menu_mm"><a href="index.html">Home</a></li>
				<li class="menu_mm"><a href="courses.html">Courses</a></li>
				<li class="menu_mm"><a href="instructors.html">Instructors</a></li>
				<li class="menu_mm"><a href="#">Events</a></li>
				<li class="menu_mm"><a href="blog.html">Blog</a></li>
				<li class="menu_mm"><a href="contact.html">Contact</a></li> -->
			</ul>
		</nav>
		<div class="menu_extra">
			<div class="menu_phone">
				<a href="https://api.whatsapp.com/send?phone=3330230905" target="_blank"><span class="menu_title">phone:</span>(33) 30230905</a>
			</div>
			<div class="menu_phone">
				<a href="tel:+523336144120"><span class="menu_title">phone:</span>(33) 36144120</a>
			</div>
			<div class="menu_social">
				<span class="menu_title">Síguenos</span>
				<ul>
					<li><a href="https://www.youtube.com/@pibguadalajara5203" target="_blank"><i class="fa fa-youtube" aria-hidden="true"></i></a></li>
					<li><a href="https://www.facebook.com/gdlpib" target="_blank"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
					<li><a href="https://www.instagram.com/pibgdl/" target="_blank"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
					<!-- <li><a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a></li> -->
				</ul>
			</div>
		</div>
	</div>