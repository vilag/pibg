<!DOCTYPE html>
<html lang="en">
<head>
<title>Coro J. S. Bach</title>
<link href="images/iconos/icono.png" rel="icon">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="Primera Iglesia Bautista de Guadalajara">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="styles/bootstrap4/bootstrap.min.css">
<link href="plugins/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="plugins/OwlCarousel2-2.2.1/owl.carousel.css">
<link rel="stylesheet" type="text/css" href="plugins/OwlCarousel2-2.2.1/owl.theme.default.css">
<link rel="stylesheet" type="text/css" href="plugins/OwlCarousel2-2.2.1/animate.css">
<link href="plugins/video-js/video-js.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="styles/main_styles.css">
<link rel="stylesheet" type="text/css" href="styles/responsive.css">
<link rel="stylesheet" type="text/css" href="styles/courses.css">
<link rel="stylesheet" type="text/css" href="styles/courses_responsive.css">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.26.0/moment.min.js"></script>
<script src="js/jquery-3.2.1.min.js"></script>
</head>
<body>

<?php
require 'header.php';
?>
	<input id="input_vista" type="hidden" value="0">
	<!-- Home -->

	<!-- <div class="home">
		
		<div class="home_background parallax_background parallax-window" data-parallax="scroll" data-image-src="images/fondos/bach2.png" data-speed="0.8"></div>
		<div class="home_container">
			<div class="container">
				<div class="row">
					<div class="col">
						<div class="home_content">
							<div class="home_title" style="line-height : 75px;"><b style="font-size: 50px; color: rgba(0,0,0,0.2);">Coro</b>  <br> <b style="font-size: 100px; color: rgba(0,0,0,0.2);">Johann Sebastian Bach</b></div>
							<div class="breadcrumbs">
								<ul>
									<li><a href="index.html">Home</a></li>
									<li>About us</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div> -->

	<!-- Courses -->

	<div class="courses">
		<div class="container" >
		<!-- <div class="row" style="margin-top: 80px; padding-left: 20px;">
			<p style="line-height : 65px;"><b style="font-size: 50px; color: #37434D;">Unión de Jóvenes</b>  <br> <b style="font-size: 80px; color: #37434D;">Lumbrera</b></p>
			
		</div> -->
			<!-- <div class="row">
				<div class="col-lg-10 offset-lg-1">
					<div class="section_title text-center"><h2>Choose your course</h2></div>
					<div class="section_subtitle">Suspendisse tincidunt magna eget massa hendrerit efficitur. Ut euismod pellentesque imperdiet. Cras laoreet gravida lectus, at viverra lorem venenatis in. Aenean id varius quam. Nullam bibendum interdum dui, ac tempor lorem convallis ut</div>
				</div>
			</div>

			
			<div class="row">
				<div class="col">
					<div class="course_search">
						<form action="#" class="course_search_form d-flex flex-md-row flex-column align-items-start justify-content-between">
							<div><input type="text" class="course_input" placeholder="Course" required="required"></div>
							<div><input type="text" class="course_input" placeholder="Level" required="required"></div>
							<button class="course_button"><span>search course</span><span class="button_arrow"><i class="fa fa-angle-right" aria-hidden="true"></i></span></button>
						</form>
					</div>
				</div>
			</div> -->

			<!-- Featured Course -->
			<!-- <div class="row featured_row">
				<div class="col-lg-6 featured_col">
					<div class="featured_content">
						<div class="featured_header d-flex flex-row align-items-center justify-content-start">
							<div class="featured_tag"><a href="#">PRÓXIMO CONCIERTO</a></div>
							
						</div>
						<div class="featured_title"><h3><a href="courses.html">Concierto de Resurreción</a></h3></div>
						<div class="featured_text">No a nosotros, oh Jehová, no a nosotros, Sino a tu nombre da gloria, Por tu misericordia, por tu verdad. <br><br> SALMOS 115:1</div>
						<div class="featured_footer d-flex align-items-center justify-content-start">
							
							<div class="featured_author_name">Director: <a href="#">Fernando Sosa Santana</a></div>
							
						</div>
						<div style="width 100%; text-align: right; margin-top: 50px;">
							<b>Domingo 31 de marzo 2024</b>
						</div>
					</div>
				</div>
				<div class="col-lg-6 featured_col">
					
					<div class="featured_background" style="background-image:url(images/fondos/fondo1.png)"></div>
				</div>
			</div> -->

            <div class="row featured_row">
				<div class="col-lg-5 featured_col" >
					<div  style="height: 747px !important; background-color: #fff;">
						<div class="featured_header d-flex flex-row align-items-center justify-content-start">
							<div class="featured_tag"><a href="https://www.youtube.com/@pibguadalajara5203/streams" target="_blank">Ver transmisión</a></div>
							<!-- <div class="featured_price ml-auto">Price: <span>$35</span></div> -->
						</div>
						<div class="featured_title"><h3><a href="courses.html">Unión de Jóvenes Lumbrera</a></h3></div>
						<div class="featured_text">Agradecidos estamos con nuestro Dios por la bendición que nos concede de poder realizar un año más nuestra semana de la juventud,
                         esperamos contar con su presencia y juntos darle la honra y gloria a nuestro Dios.
                        </div>
						<div class="featured_footer d-flex align-items-center justify-content-start">
							<!-- <div class="featured_author_image"><img src="images/featured_author.jpg" alt=""></div> -->
							<div style="margin-left: 0px;" class="featured_author_name">Pastor invitado: <a href="#">Cristian Contreras</a></div>
							<!-- <div class="featured_sales ml-auto"><span>352</span> Sales</div> -->
						</div>
					</div>
				</div>
				<div class="col-lg-7 featured_col">
                    <img src="https://res.cloudinary.com/ddcszcshl/image/upload/v1728360997/Pibg/JUVENTUD_PIBG_rogx8i.jpg" alt="" style="width: 100%;">
					<!-- Background image artist https://unsplash.com/@jtylernix -->
					<!-- <div class="featured_background" style="background-image:url(https://res.cloudinary.com/ddcszcshl/image/upload/v1728360997/Pibg/JUVENTUD_PIBG_rogx8i.jpg); background-size: cover;"></div> -->
				</div>
			</div>

			
			

			<!-- Pagination -->
			<!-- <div class="row">
				<div class="col">
					<div class="courses_paginations">
						<ul>
							<li class="active"><a href="#">01</a></li>
							<li><a href="#">02</a></li>
							<li><a href="#">03</a></li>
							<li><a href="#">04</a></li>
							<li><a href="#">05</a></li>
						</ul>
					</div>
				</div>
			</div> -->
		</div>
	</div>

<!--Footer-->
<?php
	require 'footer.php';
?>
<script type="text/javascript" src="scripts/predic.js?v=<?php echo(rand()); ?>"></script>
</body>
</html>