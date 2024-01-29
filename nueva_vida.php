<!DOCTYPE html>
<html lang="en">
<head>
<title>¿Quién es Jesús?</title>
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
		<div class="row" style="margin-top: 100px; padding-left: 20px;">
			<p style="line-height : 65px;"> <b style="font-size: 50px; color: #37434D;">¿Quién es Jesús?</b></p>

		</div>

			<div class="row" >
				<div class="col-lg-10 offset-lg-1" >
					<!-- <div class="section_title text-center"><h2>Descubriendo la Nueva Vida en Cristo.</h2></div> -->
					<div class="section_subtitle" style="font-size: 20px; line-height : 30px;">“Porque de tal manera amó Dios al mundo,
que ha dado a su Hijo unigénito,
para que todo aquel que en él cree,
no se pierda, mas tenga vida eterna.”</div>
                    <div style="text-align: center; margin-top: 30px;">
                        <b style="font-size: 25px;">Juan 3:16</b>
                    </div>

				</div>
			</div>
            <div class="row" style="margin-top: 20px; justify-content: center;">
                <a class="btn btn-app" href="files/quien_es_jesus.pdf" download="quien_es_jesus.pdf" style="margin-left: -12px;">
					<button class="course_button">
                    <span>Descargar</span><span class="button_arrow"><i class="fa fa-angle-right" aria-hidden="true"></i></span></button>
				</a>

            </div>
            <div class="row" style="margin-top: 20px;">
                <object type="application/pdf" data="files/quien_es_jesus.pdf" width="100%" height="1000"></object>

                <!-- <iframe src="files/quien_es_jesus.pdf" height="1000px" width="100%"></iframe> -->
                <!-- <iframe src="http://docs.google.com/viewer?url=files/quien_es_jesus.pdf&embedded=true" width="600" height="780" style="border: none;"></iframe> -->
                <!-- <embed src="files/quien_es_jesus.pdf" type="application/pdf" width="100%" height="600px" /> -->
                <!-- <div style="position:relative;padding-top:max(60%,324px);width:100%;height:0;"><iframe style="position:absolute;border:none;width:100%;height:100%;left:0;top:0;" src="https://online.fliphtml5.com/evenl/odcp/"  seamless="seamless" scrolling="no" frameborder="0" allowtransparency="true" allowfullscreen="true" ></iframe></div> -->
            </div>

			<div class="row" style="margin-bottom: 50px;">
				<div class="col">
					<div class="course_search">
                    <div class="section_subtitle" style="font-size: 20px; line-height : 30px; text-align: left ;">
                    ¿Te gustaria conocer más? escribenos y con gusto te atenderemos.
                    <br><br>
                    <a style="background-color: #044BA1; color: #fff; font-size: 15px; padding: 10px; border-radius: 10px; box-shadow: 5px 5px 10px rgba(0,0,0,0.1);" href="tel:+523332550900">3332550900</a><br><br>
                    <a style="background-color: #044BA1; color: #fff; font-size: 15px; padding: 10px; border-radius: 10px; box-shadow: 5px 5px 10px rgba(0,0,0,0.1);" href="mailto:pibgdlar@gmail.com">pibgdlar@gmail.com</a>

                    <div class="section_subtitle" style="font-size: 20px; line-height : 30px; text-align: left ;">
                    Si lo prefieres dejanos tus datos y con gusto te contactaremos.
                    <br><br>

                    </div>
						<form class="course_search_form d-flex flex-md-row flex-column align-items-start justify-content-between">
							<div><input type="text" class="course_input" placeholder="Nombre" id="nombre" required="required"></div>
							<div><input type="text" class="course_input" placeholder="Teléfono" id="telefono" required="required"></div>
                            <b onclick="enviar_datos_nuevo_contacto();" style="cursor: pointer; width: 150px; text-align: center; background-color: #044BA1; color: #fff; font-size: 15px; padding: 10px; border-radius: 10px; box-shadow: 5px 5px 10px rgba(0,0,0,0.1);"> Enviar</b>

							<!-- <button  class="course_button"><span>Enviar</span><span class="button_arrow"><i class="fa fa-angle-right" aria-hidden="true"></i></span></button> -->
						</form>
					</div>
				</div>
			</div>

			<!-- Featured Course -->
			<!-- <div class="row featured_row">
				<div class="col-lg-6 featured_col">
					<div class="featured_content" style="padding-top: 100px; padding-bottom: 100px;">
						<div class="featured_header d-flex flex-row align-items-center justify-content-start">
							<div class="featured_tag"><a href="#">Domingo 24 de diciembre 2023</a></div>

						</div>
						<div class="featured_title"><h3><a href="courses.html">Concierto Navideño</a></h3></div>
						<div class="featured_text">No a nosotros, oh Jehová, no a nosotros, Sino a tu nombre da gloria, Por tu misericordia, por tu verdad. <br><br> SALMOS 115:1</div>
						<div class="featured_footer d-flex align-items-center justify-content-start">

							<div class="featured_author_name">Director: <a href="#">Fernando Sosa Santana</a></div>

						</div>
					</div>
				</div>
				<div class="col-lg-6 featured_col">

					<div class="featured_background" style="background-image:url(images/fondos/fondo1.png)"></div>
				</div>
			</div> -->

			<!-- <div class="row courses_row"> -->

				<!-- Course -->


			<!-- </div> -->


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

</body>
</html>