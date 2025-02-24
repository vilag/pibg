<!DOCTYPE html>
<html lang="en">
<head>
<title>Primera Iglesia Bautista de Guadalajara</title>
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
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&family=Yanone+Kaffeesatz:wght@300&display=swap" rel="stylesheet">
<!-- <link rel="stylesheet" type="text/css" href="./styles/personal.css"> -->

<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.26.0/moment.min.js"></script>
<script src="js/jquery-3.2.1.min.js"></script>
</head>
<body>

<div class="super_container">

	<!-- Header -->

	<?php
		require('header.php');
	?>
	
	<!-- Home -->

	<style>
	.estilo_caja_texto_ini{
		float: left; 
		margin-top: 0px;
		z-index: 1;
	}

	.estilo_home{
		height: 700px;
	}

	.yanone-kaffeesatz {
	font-family: "Yanone Kaffeesatz", sans-serif;
	font-optical-sizing: auto;
	font-weight: 300;
	font-style: normal;
	font-size: 20px;
	color: #fff; 
	line-height: 22px; 
	margin-top: 160px; 
	}

	@media only screen and (max-width: 574px){
		.estilo_home{
			height: 800px;
		}
	}
	
	</style>

	<div class="home estilo_home">
	<input id="input_vista" type="hidden" value="1">
		<div class="home_slider_container">

			<!-- Home Slider -->
			<div class="owl-carousel owl-theme home_slider">

				<!-- Slider Item -->
				<div class="owl-item">
					<!-- Background image artist https://unsplash.com/@benwhitephotography -->
					<!-- <div class="home_slider_background" style="background-image:url(https://res.cloudinary.com/ddcszcshl/image/upload/v1728447824/Pibg/Dise%C3%B1o_sin_t%C3%ADtulo_2_vutrvg.png)"></div> -->
					<div class="home_slider_background" style="background-image:url(https://res.cloudinary.com/dmtvvrw4s/image/upload/v1698043712/paginaWeb/fondos/qhtvlqfjush9g8bieqbk.png)"></div>
					<div class="home_container">
						<div class="container">
							<div class="row">
								<div class="col">
									<div class="home_content estilo_texto_inicial">
										<!-- <div class="home_logo"><img src="images/home_logo.png" alt=""></div> -->
										<div class="home_text">

											<div id="content_nom_activ_des" class="col-lg-10 estilo_caja_texto_ini">
												<div id="div_content_texto_princ" class="content_texto_princ">
													<!-- <label id="nom_activ_sem_esp" for="" style="font-size: 28px; color: #FFF;">Y esta es la vida eterna: que te conozcan a ti, el único Dios verdadero, y a Jesucristo, a quien has enviado.</label><br> -->
													<p id="nom_activ_sem_esp" class="estilo_texto_activ_esp"></p>
													<label id="det_activ_sem_esp" class="text_secundario_estilo" for=""></label>
													
												</div>
												<!-- <div><b style="font-size: 40px; color: rgba(255,255,255,0.3); border: rgba(0,0,0,0) 2px solid;">Juan 17:3</b></div> -->
												<!-- <div class="home_buttons">
													<div class="button home_button"><a href="lumbrera.php">Ver Más<div class="button_arrow"><i class="fa fa-angle-right" aria-hidden="true"></i></div></a></div>
													<div class="button home_button"><a href="https://www.youtube.com/@pibguadalajara5203/streams" target="_blank">Ver Transmisiones<div class="button_arrow"><i class="fa fa-angle-right" aria-hidden="true"></i></div></a></div>
												</div> -->

												<!-- <div><label for="" style="font-size: 22px; color: #FFF;">Y esta es la vida eterna: que te conozcan a ti, el único Dios verdadero, <br> y a Jesucristo, a quien has enviado.</label></div> -->
												<!-- <div><b style="font-size: 40px; color: rgba(255,255,255,0.3); border: border: rgba(0,0,0,0) 2px solid;">Juan 17:3</b></div> -->
													<div class="content_proxima_transmision">
														<br><br>
														<label style="color: #ccc;" for="">PRÓXIMA TRANSMISIÓN:</label><br>
														<b style="color: #ccc; font-size: 22px;" id="nombre_actividad"></b><br>
														<p style="font-size: 20px; color: #ccc; line-height: 28px; margin: 0px 0px 15px 0px;" id="tema_actividad"></p>
														<!-- <b style="color: #ccc;" id="nombre_actvidad">Culto de oración</b><br> -->
														<label style="color: #ccc;" id="dia_sp"></label>
														<label style="color: #ccc;" id="dia_sp_num"></label>
														<label id="conector_nom_activ" style="color: #ccc;" for=""></label>
														<label style="color: #ccc;" id="mes_sp"></label>
														<label style="color: #ccc;" id="hora_sp"></label><br><br><br>
														<a id="enlace_redirect" target="_blank" href="https://www.youtube.com/@pibguadalajara5203/streams" style="padding: 10px 30px; background-color: #d44c04; border-radius: 10px; border: #f7a037 1px solid; color: #fff; border: none; width: 200px; text-align: center;">Ver transmisión</a>
														
													</div>
												
												<!-- <a id="enlace_redirect_local" href="" style="padding: 10px 30px; background-color: #d44c04; border-radius: 10px; border: #f7a037 1px solid; color: #fff; border: none;">Ver Más</a> -->

												<!-- <label style="color: #ccc;" for=""> Domingo 12 de noviembre de 2023, 12:00 hrs.</label> -->
											</div>
											<style>
												.estilo_mini_princ1{
													height: 250px; 
													width: 150px; 
													
													float: left; 
													margin-left: 20px; 
													margin-right: 0px;
													border-radius: 10px; 
													box-shadow: 10px 10px 20px rgba(0,0,0,0.5);
													/* position: static; */
    												margin-top: 0px;
													/* transition: all 0.5s; */
													opacity: 1;
													margin-top: 100px;
													/* border: none; */
													/* display: block; */
													transition: all 0.5s;
												}

												.estilo_mini_princ1:hover{
													height: 250px; 
													width: 150px; 
													
													float: left; 
													margin-left: 20px; 
													margin-right: 0px;
													border-radius: 10px; 
													box-shadow: 5px 5px 7px rgba(255,255,255,0.2);
													/* position: static; */
    												margin-top: 0px;
													
													opacity: 1;
													margin-top: 100px;
													/* border: #fff 3px solid; */
													/* display: block; */
													transition: all 0.5s;
												}

												.estilo_mini_princ1_active{
													height: 250px; 
													width: 150px; 
													
													float: left; 
													margin-left: 20px; 
													margin-right: 0px;
													border-radius: 10px; 
													box-shadow: 5px 5px 7px rgba(255,255,255,0.3);
													/* position: static; */
    												margin-top: 0px;
													
													opacity: 1;
													margin-top: 100px;
													/* border: #fff 3px solid; */
													/* display: block; */
													transition: all 0.5s;
												}
											
												.estilo_mini_princ1-in{
													height: 1000px;
													width: 150vw;
													
													float: left;
													margin-left: -100%;
													margin-right: 10px;
													border-radius: 10px;
													box-shadow: 10px 10px 20px rgba(0, 0, 0, 0.5);
													opacity: 1;
													position: absolute;
													display: none;
													margin-top: -50%;
													
													/* transition: width 0.5s, height 0.5s, margin-left 0.5s, margin-top 0.5s; */
													
												}
												/* .flip-out-ver-left{-webkit-animation:flip-out-ver-left .45s cubic-bezier(.55,.085,.68,.53) both;animation:flip-out-ver-left .45s cubic-bezier(.55,.085,.68,.53) both}									
												@-webkit-keyframes flip-out-ver-left{0%{-webkit-transform:rotateY(0);transform:rotateY(0);opacity:1}100%{-webkit-transform:rotateY(-70deg);transform:rotateY(-70deg);opacity:0}}@keyframes flip-out-ver-left{0%{-webkit-transform:rotateY(0);transform:rotateY(0);opacity:1}100%{-webkit-transform:rotateY(-70deg);transform:rotateY(-70deg);opacity:0}}
												
												.flip-in-ver-left{-webkit-animation:flip-in-ver-left .5s cubic-bezier(.25,.46,.45,.94) both;animation:flip-in-ver-left .5s cubic-bezier(.25,.46,.45,.94) both}
												@-webkit-keyframes flip-in-ver-left{0%{-webkit-transform:rotateY(80deg);transform:rotateY(80deg);opacity:0}100%{-webkit-transform:rotateY(0);transform:rotateY(0);opacity:1}}@keyframes flip-in-ver-left{0%{-webkit-transform:rotateY(80deg);transform:rotateY(80deg);opacity:0}100%{-webkit-transform:rotateY(0);transform:rotateY(0);opacity:1}} */

												.fade-out{-webkit-animation:fade-out 1s ease-out both;animation:fade-out 1s ease-out both}
												@-webkit-keyframes fade-out{0%{opacity:1}100%{opacity:0}}@keyframes fade-out{0%{opacity:1}100%{opacity:0}}

												.fade-in{-webkit-animation:fade-in 1.2s cubic-bezier(.39,.575,.565,1.000) both;animation:fade-in 1.2s cubic-bezier(.39,.575,.565,1.000) both}
												@-webkit-keyframes fade-in{0%{opacity:0}100%{opacity:1}}@keyframes fade-in{0%{opacity:0}100%{opacity:1}}

											
/* 
												.slide-left_static{-webkit-animation:slide-left .5s cubic-bezier(.25,.46,.45,.94) both;animation:slide-left .5s cubic-bezier(.25,.46,.45,.94) both}
												@-webkit-keyframes slide-left{0%{-webkit-transform:translateX(0);transform:translateX(0)}100%{-webkit-transform:translateX(-170px);transform:translateX(-170px)}}@keyframes slide-left{0%{-webkit-transform:translateX(0);transform:translateX(0)}100%{-webkit-transform:translateX(-170px);transform:translateX(-170px)}} */

												.slide-left{-webkit-animation:slide-left .5s cubic-bezier(.25,.46,.45,.94) both;animation:slide-left .5s cubic-bezier(.25,.46,.45,.94) both}
												@-webkit-keyframes slide-left{0%{-webkit-transform:translateX(0);transform:translateX(0)}100%{-webkit-transform:translateX(-170px);transform:translateX(-170px)}}@keyframes slide-left{0%{-webkit-transform:translateX(0);transform:translateX(0)}100%{-webkit-transform:translateX(-170px);transform:translateX(-170px)}}
												
												.estilo_back_ae{
													background-color: transparent;
												}
												/* .fade-in_1{-webkit-animation:fade-in_1 1.2s cubic-bezier(.39,.575,.565,1.000) 3s both;animation:fade-in_1 1.2s cubic-bezier(.39,.575,.565,1.000) 3s both}
												@-webkit-keyframes fade-in_1{0%{opacity:0}100%{opacity:1}}@keyframes fade-in_1{0%{opacity:0}100%{opacity:1}}
											
												.fade-in_2{-webkit-animation:fade-in_2 1.2s cubic-bezier(.39,.575,.565,1.000) 3.1s both;animation:fade-in_2 1.2s cubic-bezier(.39,.575,.565,1.000) 3.1s both}
												@-webkit-keyframes fade-in_2{0%{opacity:0}100%{opacity:1}}@keyframes fade-in_2{0%{opacity:0}100%{opacity:1}}

												.fade-in_3{-webkit-animation:fade-in_3 1.2s cubic-bezier(.39,.575,.565,1.000) 3.2s both;animation:fade-in_3 1.2s cubic-bezier(.39,.575,.565,1.000) 3.2s both}
												@-webkit-keyframes fade-in_3{0%{opacity:0}100%{opacity:1}}@keyframes fade-in_3{0%{opacity:0}100%{opacity:1}}

												.fade-in_4{-webkit-animation:fade-in_4 1.2s cubic-bezier(.39,.575,.565,1.000) 3.3s both;animation:fade-in_4 1.2s cubic-bezier(.39,.575,.565,1.000) 3.3s both}
												@-webkit-keyframes fade-in_4{0%{opacity:0}100%{opacity:1}}@keyframes fade-in_4{0%{opacity:0}100%{opacity:1}} */
											</style>
											<!-- <div style="width: 50%; overflow: scroll; position: absolute; height: 350px; margin-left: 50%;">
												<div id="content_actividades_destacadas" style="float: left; padding-top: 100px;  width: 1000px !important;">

												</div>
											</div> -->
											<div id="content_actividades_destacadas" class="estilo_content_activ_dest">

												<!-- <div id="mini1" class="estilo_mini_princ1" style="
													background-image: url('images/actividades_especiales/mayordomia.jpg'); 
													background-repeat: no-repeat;
													background-size: 400px 350px;
													background-position: center;">
													<div style="padding: 15px; background-image: linear-gradient(rgba(0,0,0,0), rgba(0,0,0,0.8)); height: 100%; width: 100%;">
														<p class="yanone-kaffeesatz">SEMANA DE <br>MAYORDOMÍA</p>
													</div>
													
												</div>
												<div id="mini1_2" class="estilo_mini_princ1-in" style="
													background-image: url('images/actividades_especiales/mayordomia.jpg');
													background-repeat: no-repeat;
													background-size: cover;
													background-position: center;">
													<div style="padding: 15px; background-image: linear-gradient(rgba(0,0,0,0), rgba(0,0,0,0.8)); height: 100%; width: 100%;">
														<p class="yanone-kaffeesatz">SEMANA DE <br>MAYORDOMÍA</p>
													</div>
													
												</div>



												<div style="height: 100%; width: 150px; background-image: url(images/actividades_especiales/caravana-medica.png); float: left; margin-left: 20px; border-radius: 10px; box-shadow: 10px 10px 20px rgba(0,0,0,0.5);">
													<div style="padding: 15px; background-image: linear-gradient(rgba(0,0,0,0), rgba(0,0,0,0.8)); height: 100%; width: 100%;">
														<p class="yanone-kaffeesatz">CARAVANA MÉDICO <br>MISIONERA</p>
													</div>
												</div>
												<div style="height: 100%; width: 150px; background-image: url(images/actividades_especiales/misiones-mundiales.jpg); float: left; margin-left: 20px; border-radius: 10px; box-shadow: 10px 10px 20px rgba(0,0,0,0.5);">
													<div style="padding: 15px; background-image: linear-gradient(rgba(0,0,0,0), rgba(0,0,0,0.8)); height: 100%; width: 100%;">
														<p class="yanone-kaffeesatz">ORACIÓN POR LAS<br>MISIONES MUNDIALES</p>
													</div>
												</div>
												<div style="height: 100%; width: 150px; background-image: url(images/actividades_especiales/concierto-navidad.png); float: left; margin-left: 20px; border-radius: 10px; box-shadow: 10px 10px 20px rgba(0,0,0,0.5);">
													<div style="padding: 15px; background-image: linear-gradient(rgba(0,0,0,0), rgba(0,0,0,0.8)); height: 100%; width: 100%;">
														<p class="yanone-kaffeesatz">CONCIERTO <br> NAVIDEÑO</p>
													</div>
												</div> -->
												
												<!-- <div id="video_dia_oracion1" style="width: 100%; display: block; z-index: 5;">
													<video  style="width: 100%; " controls muted autoplay loop>
														<source src="https://res.cloudinary.com/ddcszcshl/video/upload/v1730389393/Pibg/videos/file_nwgjt3.mp4" type="video/mp4">	
													</video>
													
												</div>
												<div id="video_dia_oracion2" style="width: 100%; display: none;">
													<video  style="width: 100%; " controls muted autoplay>
														<source src="https://res.cloudinary.com/ddcszcshl/video/upload/v1730389393/Pibg/videos/file_nwgjt3.mp4" type="video/mp4">	
													</video>
												</div> -->
												
											</div>
										</div>
										<!-- <div class="home_buttons">
											<div class="button home_button"><a href="#">learn more<div class="button_arrow"><i class="fa fa-angle-right" aria-hidden="true"></i></div></a></div>
											<div class="button home_button"><a href="#">see all courses<div class="button_arrow"><i class="fa fa-angle-right" aria-hidden="true"></i></div></a></div>
										</div> -->
									</div>
								</div>
							</div>
							
						</div>
						
					</div>
					
				</div>
				
				<!-- Slider Item -->
				<!-- <div class="owl-item">

					<div class="home_slider_background" style="background-image:url(https://res.cloudinary.com/dmtvvrw4s/image/upload/v1698043712/paginaWeb/fondos/qovhwpl1rlazcjwuveaf.png)"></div>
					<div class="home_container">
						<div class="container">
							<div class="row">
								<div class="col">
									<div class="home_content ">
										<div class="home_logo"><img src="images/home_logo.png" alt=""></div>
										<div class="home_text">
											<div><label for="" style="font-size: 25px; color: #FFF;">Y esta es la vida eterna: que te conozcan a ti, el único Dios verdadero, <br> y a Jesucristo, a quien has enviado.</label></div>
											<div><b style="font-size: 80px; color: rgba(255,255,255,0.3);">Juan 17:3</b></div></div>
										<div class="home_buttons">
											<div class="button home_button"><a href="#">learn more<div class="button_arrow"><i class="fa fa-angle-right" aria-hidden="true"></i></div></a></div>
											<div class="button home_button"><a href="#">see all courses<div class="button_arrow"><i class="fa fa-angle-right" aria-hidden="true"></i></div></a></div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div> -->

				<!-- Slider Item -->
				<!-- <div class="owl-item">

					<div class="home_slider_background" style="background-image:url(images/index.jpg)"></div>
					<div class="home_container">
						<div class="container">
							<div class="row">
								<div class="col">
									<div class="home_content text-center">
										<div class="home_logo"><img src="images/home_logo.png" alt=""></div>
										<div class="home_text">
											<div class="home_title">Complete Online Courses</div>
											<div class="home_subtitle">Maecenas rutrum viverra sapien sed fermentum. Morbi tempor odio eget lacus tempus pulvinar. Praesent vel nisl fermentum, gravida augue ut, fermentum ipsum.</div>
										</div>
										<div class="home_buttons">
											<div class="button home_button"><a href="#">learn more<div class="button_arrow"><i class="fa fa-angle-right" aria-hidden="true"></i></div></a></div>
											<div class="button home_button"><a href="#">see all courses<div class="button_arrow"><i class="fa fa-angle-right" aria-hidden="true"></i></div></a></div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div> -->

			</div>
		</div>
	</div>

	<!-- Featured Course -->

	<style>
		.swing-in-left-fwd {

			-webkit-animation: swing-in-left-fwd 1s cubic-bezier(0.175, 0.885, 0.320, 1.275) both 3s;
					animation: swing-in-left-fwd 1s cubic-bezier(0.175, 0.885, 0.320, 1.275) both 3s;
		}

		@-webkit-keyframes swing-in-left-fwd {
		0% {
			-webkit-transform: rotateY(100deg);
					transform: rotateY(100deg);
			-webkit-transform-origin: left;
					transform-origin: left;
			opacity: 0;
		}
		100% {
			-webkit-transform: rotateY(0);
					transform: rotateY(0);
			-webkit-transform-origin: left;
					transform-origin: left;
			opacity: 1;
		}
		}
		@keyframes swing-in-left-fwd {
		0% {
			-webkit-transform: rotateY(100deg);
					transform: rotateY(100deg);
			-webkit-transform-origin: left;
					transform-origin: left;
			opacity: 0;
		}
		100% {
			-webkit-transform: rotateY(0);
					transform: rotateY(0);
			-webkit-transform-origin: left;
					transform-origin: left;
			opacity: 1;
		}
		}


		.estilo_caja_about{
    width: 33.3%; 
    height: 200px; 
    float: left; 
    border-right: #ccc 2px solid; 
    text-align: center; 
    padding: 10px 10px;
}

.estilo_caja_min{
    width: 33.3%; 
    height: 200px; 
    float: left; 
    border-right: #ccc 2px solid; 
    text-align: center; 
    padding: 10px 10px;
}

.estilo_caja_transm{
    width: 33.3%; 
    height: 200px; 
    float: left; 
    text-align: center; 
    padding: 10px 10px;
}

/* .estilo_texto_inicial{
    margin-top: 80px;
} */

.estilo_subtitulo_about{
    height: 130px;
}

.estilo_texto_activ_esp{
	font-size: 28px; 
	color: #FFF; 
	line-height: 35px; 
	margin: 0px 0px 0px 0px;
}

.content_texto_princ{
	height: 140px;
}

.text_secundario_estilo{
	font-size: 20px; 
	color: #fff; 
	margin-top: 10px;
}

.estilo_content_activ_dest{
	float: left; 
	padding-top: 100px; 
	width: 1000px !important; 
	position: absolute; 
	margin-left: 50%;
}

.content_proxima_transmision{
	width: 50%; 
	display: block;
}


@media only screen and (min-width: 260px) and (max-width: 574px) {
	.text_secundario_estilo{
		font-size: 12px; 
		color: #fff; 
		margin-top: 10px;
	}

	.content_proxima_transmision{
		width: 100%; 
		display: block;
	}
	.content_texto_princ{
		height: 200px;
		transition: all 1s;
	}

	.estilo_content_activ_dest{
		float: left; 
		padding-top: 100px; 
		width: 1000px !important; 
		position: absolute; 
		margin-left: 50%;
		display: none;
	}
	
	/* .estilo_texto_inicial{
        margin-top: -200px;
    } */
	.estilo_caja_texto_ini{
		float: left; 
		margin-top: -100px;
		z-index: 1;
	}

	.estilo_texto_activ_esp{
		font-size: 15px; 
		color: #FFF; 
		line-height: 20px; 
		margin: 0px 0px 0px 0px;
	}

    .estilo_subtitulo_about{
        height: 170px;
    }

    .estilo_caja_about{
        width: 100%; 
        height: 250px; 
        float: left; 
        border-right: transparent 2px solid; 
        text-align: center; 
        padding: 10px 10px;
    }

    .estilo_caja_min{
        width: 100%; 
        height: 250px; 
        float: left; 
        border-right: transparent 2px solid; 
        text-align: center; 
        padding: 10px 10px;
    }
    
    .estilo_caja_transm{
        width: 100%; 
        height: 250px; 
        float: left; 
        text-align: center; 
        padding: 10px 10px;
    }

	

}


@media only screen and (min-width: 575px) and (max-width: 1000px) {
	/* .estilo_texto_inicial{
        margin-top: 50px;
    } */

    .estilo_subtitulo_about{
        height: 170px;
    }
	.estilo_texto_activ_esp{
		font-size: 18px; 
		color: #FFF; 
		line-height: 20px; 
		margin: 0px 0px 0px 0px;
	}

    .estilo_caja_about{
        width: 33.3%; 
        height: 200px; 
        float: left; 
        border-right: #ccc 2px solid; 
        text-align: center; 
        padding: 10px 10px;
    }

    .estilo_caja_min{
        width: 33.3%; 
        height: 200px; 
        float: left; 
        border-right: #ccc 2px solid; 
        text-align: center; 
        padding: 10px 10px;
    }
    
    .estilo_caja_transm{
        width: 33.3%; 
        height: 200px; 
        float: left; 
        text-align: center; 
        padding: 10px 10px;
    }

	.estilo_mini_princ1-in{
													height: 1000px;
													width: 140vw;
													
													float: left;
													margin-left: -50%;
													margin-right: 10px;
													border-radius: 10px;
													box-shadow: 10px 10px 20px rgba(0, 0, 0, 0.5);
													opacity: 1;
													position: absolute;
													display: none;
													margin-top: -50%;
													
													/* transition: width 0.5s, height 0.5s, margin-left 0.5s, margin-top 0.5s; */
													
	}

	
}

@media only screen and (min-width: 1001px) and (max-width: 1500px){
	.estilo_mini_princ1-in{
													height: 1000px;
													width: 140vw;
													
													float: left;
													margin-left: -75%;
													margin-right: 10px;
													border-radius: 10px;
													box-shadow: 10px 10px 20px rgba(0, 0, 0, 0.5);
													opacity: 1;
													position: absolute;
													display: none;
													margin-top: -50%;
													
													/* transition: width 0.5s, height 0.5s, margin-left 0.5s, margin-top 0.5s; */
													
	}

	.estilo_texto_activ_esp{
		font-size: 18px; 
		color: #FFF; 
		line-height: 20px; 
		margin: 0px 0px 0px 0px;
	}
}

		.barra_lecturas::-webkit-scrollbar {
			width: 10px;
			height: 10px;              
		}

		.barra_lecturas::-webkit-scrollbar-track {
			background: rgb(212, 221, 226, 0.1);       
		}

		.barra_lecturas::-webkit-scrollbar-thumb {
			background-color: rgb(40, 57, 87);    
			border-radius: 20px;    
			border: 1px solid rgba(155, 168, 175, 0.5);
			box-shadow: 5px 5px 5px rgba(0, 0, 0, 0.1);
		}

		.box_lecturas_dia{
			cursor: pointer; 
			display: flex; 
			justify-content: center; 
			align-items: center; 
			margin-bottom: 10px; 
			background-color: rgba(255,255,255,0.1); 
			padding: 15px; 
			box-shadow: 5px 5px 10px rgba(0,0,0,0.05); 
			border-radius: 0px;
		}

		.box_lecturas_dia:hover{
			cursor: pointer; 
			display: flex; 
			justify-content: center; 
			align-items: center; 
			margin-bottom: 10px; 
			background-color: rgba(255,255,255,0.2); 
			padding: 15px; 
			box-shadow: 5px 5px 10px rgba(0,0,0,0.05); 
			border-radius: 0px;
		}


	</style>
	<!-- <div class="featured" style="margin-top: -450px;">
		<div class="container">
			<div class="row">
				<div class="col">
				

											
					<div>
						<label for="" style="font-size: 28px; color: #FFF;">SEMANA DE LA JUVENTUD 2024</label><br>
						<label style="font-size: 20px; color: #fff;" for="">del 13 al 20 de octubre</label>
						
					</div>
					
					<br><br><br>
					<label style="color: #ccc;" for="">PRÓXIMA TRANSMISIÓN:</label><br>
					<b style="color: #ccc;" id="nombre_actividad"></b><br>
					<p></p>
					<label style="color: #ccc;" id="dia_sp">Martes</label>
					<label style="color: #ccc;" id="dia_sp_num">15</label>
					<label style="color: #ccc;" for="">de</label>
					<label style="color: #ccc;" id="mes_sp">octubre</label>
					<label style="color: #ccc;" id="hora_sp">19:00 hrs.</label><br><br>
					<a href="lumbrera.php" style="padding: 10px 30px; background-color: #d44c04; border-radius: 10px; border: #f7a037 1px solid; color: #fff;">Ver Más</a>
					<hr style="height: 80px;">
				
			</div>
		</div>
	</div> -->
	<style>
		.estilo_act_sem{
			padding: 20px 5px;
			text-align: center;
			/* box-shadow: 5px 5px 10px rgba(0,0,0,0.1); */
			/* border-radius: 10px; */
			margin: 5px;
			/* background-color: #ccc; */
		}
	</style>
	
	<div class="featured" style="background-color: #24344B;">
		<div class="container">
			<div class="row">
				<div class="col-lg-12" style="text-align: center; padding-top: 40px; padding-bottom: 20px;" data-aos="fade-up" data-aos-anchor-placement="center-bottom">
					<h3 style="color: #ccc;">Actividades Semanales</h3>
				</div>	
			</div>
			<div class="row" style="padding-bottom: 40px; display: flex; justify-content: center;">
				
				<div class="col-lg-2 estilo_act_sem" data-aos="fade-left">
					<p style="margin: 0px; font-weight: bold; font-size: 15px; line-height: 20px; color: #ccc;">Escuela <br> Dominical</p>
					<p style="margin: 10px 0px 0px 0px; color: #ccc;">Domingo 10:30 hrs.</p>
				</div>
				<div class="col-lg-2 estilo_act_sem" data-aos="fade-left">
					<p style="margin: 0px; font-weight: bold; font-size: 15px; line-height: 20px; color: #ccc;">Culto de <br> Adoración</p>
					<p style="margin: 10px 0px 0px 0px; color: #ccc;">Domingo 12:00 hrs.</p>
				</div>
				<div class="col-lg-2 estilo_act_sem" data-aos="fade-left">
					<p style="margin: 0px; font-weight: bold; font-size: 15px; line-height: 20px; color: #ccc;">Culto de Adoración <br> Vespertino</p>
					<p style="margin: 10px 0px 0px 0px; color: #ccc;">Domingo 18:00 hrs.</p>
				</div>
				<div class="col-lg-2 estilo_act_sem" data-aos="fade-left">
					<p style="margin: 0px; font-weight: bold; font-size: 15px; line-height: 20px; color: #ccc;">Culto de <br> Oración</p>
					<p style="margin: 10px 0px 0px 0px; color: #ccc;">Miércoles 19:00 hrs.</p>
				</div>
				<div class="col-lg-2 estilo_act_sem" data-aos="fade-left">
					<p style="margin: 0px; font-weight: bold; font-size: 15px; line-height: 20px; color: #ccc;">Culto de Estudio <br> Biblico</p>
					<p style="margin: 10px 0px 0px 0px; color: #ccc;">Viernes 19:00 hrs.</p>
				</div>
				
			</div>
		</div>
	</div>

	<div class="milestones">
		
		<div class="parallax_background parallax-window" data-parallax="scroll" data-image-src="https://res.cloudinary.com/dmtvvrw4s/image/upload/v1698130035/paginaWeb/Inicial/lsuiguuvwbvynfqlrf1c.png" data-speed="0.8"></div>
		<div class="container">
			<div class="row milestones_container">
				<div class="row">
					<div class="col-lg-6" style="padding: 0px 30px;" >
						<div class="section_title text-center" style="text-align: left !important;"><h2 style="color: #FFF;">NOS GUSTARIA ORAR POR USTED</h2></div>
						<div class="section_subtitle" style="text-align: left !important; color: #ccc;">Querido hermano, si usted tuviera algun motivo por el cual le gustaria que estemos orando, le invitamos con todo cariño pueda dejarnos su petición de oración en el siguiente formulario.</div>
					</div>
					<div class="col-lg-6" style="padding: 0px 30px;">
						<form >
							<div style="width: 100%; float: left; margin-top: 10px;">
								<input id="nombre_peticion" type="text" class="course_input" placeholder="Nombre" required="required">
							</div>
							<div style="width: 100%; float: left; margin-top: 10px;">
								<input id="telefono_peticion" type="text" class="course_input" placeholder="Teléfono" required="required">
							</div>
							<div style="width: 100%; float: left; margin-top: 10px;">
								<input id="motivo_peticion" type="text" class="course_input" placeholder="Motivo de Oración" required="required">
							</div>
							<div style="width: 100%; float: left; margin-top: 10px; display: flex; justify-content: center; margin-top: 30px;">
								<p onclick="guardar_motivo();" class="course_button" style="text-align: center;"><span>Enviar</span><span class="button_arrow"><i class="fa fa-angle-right" aria-hidden="true"></i></span></p>
								<!-- <button onclick="guardar_motivo();" class="course_button"><span>Enviar</span><span class="button_arrow"><i class="fa fa-angle-right" aria-hidden="true"></i></span></button> -->
							</div>

						</form>
					</div>
				</div>
				

			</div>
		</div>
	</div>

	<!-- <div class="featured" style="display: block; background-image: url(images/Lectura_diaria/fondo2.png); background-repeat: no-repeat; background-size: cover;  height: 400px; background-position-y: 40%;">
		<div style="height: 500px; height: 100%; background-color: rgba(255,255,255,0.8);">
		<div class="container">
				<div class="row">
					<div class="col-lg-12" style="line-height: 20px; padding-top: 40px; padding-bottom: 20px;" data-aos="fade-up" data-aos-anchor-placement="center-bottom">
						<p style="font-size: 25px; color: #000;">LECTURA DEL DIA</p>
						<p style="font-size: 20px; color: #000; margin-top: -30px;">Salmos 1</p>
					</div>	
					<div class="col-lg-12" style="padding-top: 0px; padding-bottom: 20px;" data-aos="fade-up" data-aos-anchor-placement="center-bottom">
						<p style="font-size: 18px; line-height: 25px; color: #000;">1. Bienaventurado el varón que no anduvo en consejo de malos, Ni estuvo en camino de pecadores, Ni en silla de escarnecedores se ha sentado;</p>
						<p style="font-size: 18px; line-height: 25px; color: #000;">2. Sino que en la ley de Jehová está su delicia, Y en su ley medita de día y de noche.</p>
						<p style="font-size: 18px; line-height: 25px; color: #000;">3. Será como árbol plantado junto a corrientes de aguas,

Que da su fruto en su tiempo,

Y su hoja no cae;

Y todo lo que hace, prosperará</p>
					</div>	
				</div>
			</div>
		</div>	
	</div> -->
	
	<div class="featured" style="background-color: #FAF7F2; display: none;">
		<div class="container">
			<div class="row">
				<div class="col">
					
					
					<!-- <div style="width: 20%; height: 150px; float: left;"> -->
						<!-- <div class="home_slider_nav_container d-flex flex-row align-items-start justify-content-between">
							<div class="home_slider_nav home_slider_prev trans_200"><i class="fa fa-angle-left" aria-hidden="true"></i></div>
							<div class="home_slider_nav home_slider_next trans_200"><i class="fa fa-angle-right" aria-hidden="true"></i></div>
						</div> -->
					<!-- </div> -->
					<div style="width: 100%; float: left; margin-top: 20px; margin-bottom: 20px;">

						<div class="estilo_caja_about">
							
							<div class="estilo_subtitulo_about">
								<h3>Conócenos</h3>
								<h5 style="font-weight: 100; margin-top: 10px;">Introduccion a la PIB Guadalajara</h5>
							</div>
							<div style="height: 50px;">
								<a style="padding: 5px 10px; background-color: transparent; border-radius: 10px; border: #A0812A 1px solid; color: #A0812A;" href="about-us.php">
									¿Quiénes somos?
								</a>
								
							</div>	
						</div>
						<div class="estilo_caja_min">
		
							<div class="estilo_subtitulo_about">
								<h3>Ministerios</h3>
								<h5 style="font-weight: 100; margin-top: 10px;">Efesios 4:11</h5>
							</div>
							<div style="height: 50px;">
								<a style="padding: 5px 10px; background-color: transparent; border-radius: 10px; border: #A0812A 1px solid; color: #A0812A;" href="#">
									Ver más
								</a>
								
							</div>	

						</div>
						<div class="estilo_caja_transm">
							
							<div class="estilo_subtitulo_about">
								<h3>Transmisiones en vivo</h3>
								<h5 style="font-weight: 100; margin-top: 10px;">Domingos a las 12:00 & 6:00 PM</h5>
								<h5 style="font-weight: 100;">Miercoles y Viernes a las 7:00 PM	</h5>
							</div>
							<div style="height: 50px;">
								<a href="https://www.youtube.com/@pibguadalajara5203/streams" target="_blank" style="padding: 5px 10px; background-color: transparent; border-radius: 10px; border: #A0812A 1px solid; color: #A0812A; cursor: pointer;">Ver ahora</a>
								
							</div>
						</div>
					</div>
					<div class="featured_container" style="display: none;">
						<div class="row">
							<div class="col-lg-6 featured_col">
								<div class="featured_content" style="background-color: #040A13; padding-top: 50px !important; height: 500px;">
									<div style="width: 100%;">
										<b>ACTIVIDADES SEMANALES</b>
									</div>
									<div style="width: 100%; margin-top: 20px; float: left;">
										<div style="width: 75%; float: left;">
											<b style="color: #fff;">Culto de Adoración</b><br>
											<label id="eti_activ_dom" for="" style="padding: 5px 10px; color: #fff; margin-top: 5px;">Domingo 12:00 hrs.</label>
										</div>
										<div style="width: 25%; float: left; padding-top: 10px;">
											<a href="https://www.youtube.com/@pibguadalajara5203/streams" target="_blank">
												<div class="estilo_caja_live" id="caja_live_dom">
													<div class="estilo_content_punto_live">
														<div id="punto_live" style="width: 12px; height: 12px; background-color: red; border-radius: 50%;"></div>
													</div>
													<div class="estilo_text_live">
														<b style="color: red; font-size: 15px;">LIVE</b>
													</div>
												</div>
											</a>
											

										</div>
										
									</div>
									
									<div style="width: 100%; margin-top: 20px; float: left;">
										<div style="width: 75%; float: left;">
											<b style="color: #fff;">Culto de Adoración Vespertino</b><br>
											<label id="eti_activ_dom2" for="" style="padding: 5px 10px; color: #fff; margin-top: 5px;">Domingo 18:00 hrs.</label>
										</div>
										<div style="width: 25%; float: left; padding-top: 10px;">
											<a href="https://www.youtube.com/@pibguadalajara5203/streams" target="_blank">
												<div class="estilo_caja_live" id="caja_live_dom2">
													<div class="estilo_content_punto_live">
														<div id="punto_live" style="width: 12px; height: 12px; background-color: red; border-radius: 50%;"></div>
													</div>
													<div class="estilo_text_live">
														<b style="color: red; font-size: 15px;">LIVE</b>
													</div>
												</div>
											</a>
											

										</div>			
									</div>
									<div style="width: 100%; margin-top: 20px; float: left;">
										<div style="width: 75%; float: left;">
											<b style="color: #fff;">Culto de Oración</b><br>
											<label id="eti_activ_mie" for="" style="padding: 5px 10px; color: #fff; margin-top: 5px;">Miercoles 19:00 hrs.</label>
										</div>
										<div style="width: 25%; float: left; padding-top: 10px;">
											<a href="https://www.youtube.com/@pibguadalajara5203/streams" target="_blank">
												<div class="estilo_caja_live" id="caja_live_mie">
													<div class="estilo_content_punto_live">
														<div id="punto_live" style="width: 12px; height: 12px; background-color: red; border-radius: 50%;"></div>
													</div>
													<div class="estilo_text_live">
														<b style="color: red; font-size: 15px;">LIVE</b>
													</div>
												</div>
											</a>
											

										</div>	
									</div>
									<div style="width: 100%; margin-top: 20px; float: left;">
										<div style="width: 75%; float: left;">
											<b style="color: #fff;">Culto de Estudio Bíblico</b><br>
											<label id="eti_activ_vie" for="" style="padding: 5px 10px; color: #fff; margin-top: 5px;">Viernes 19:00 hrs.</label>
										</div>
										<div style="width: 25%; float: left; padding-top: 10px;">
											<a href="https://www.youtube.com/@pibguadalajara5203/streams" target="_blank">
												<div class="estilo_caja_live" id="caja_live_vie">
													<div class="estilo_content_punto_live">
														<div id="punto_live" style="width: 12px; height: 12px; background-color: red; border-radius: 50%;"></div>
													</div>
													<div class="estilo_text_live">
														<b style="color: red; font-size: 15px;">LIVE</b>
													</div>
												</div>
											</a>
											

										</div>	
									</div>


									
									<div class="featured_header d-flex flex-row align-items-center justify-content-start">
										<div ><b>Próxima Transmisión</b></div>
										
									</div>
									<!-- <div class="featured_title">
										<h3><a href="#" style="color: #FFF;" id="nombre_actvidad"></a></h3>
									</div> -->
									<!-- <div style="margin-top: 15px;">
										<label style="color: #fff;" id="tema_actividad"></label>
									</div> -->
									<!-- <div style="margin-top: 15px;">
										<label id="dia_sp"></label>
										<label id="dia_sp_num"></label>
										<label for=""></label>
										<label id="mes_sp"></label>
										<label id="hora_sp"></label>
									</div> -->
									<div style="width: 100%; margin-top: 30px;">
										<a href="https://www.youtube.com/@pibguadalajara5203/streams" target="_blank" style="color: #fff; background-color: #F36905 !important; padding: 10px !important; border-radius: 10px;">Ver Transmisión</a>
										
									</div>
									
									<div class="featured_footer d-flex align-items-center justify-content-start">
										<div class="featured_author_image"><img src="images/featured_author.jpg" alt=""></div>
										
										<button style="padding: 10px; background-color: rgba(0,0,0,0); color: #FFF; border: #ccc 1px solid;">Ver Transmisión</button>
										<div class="featured_author_name">By <a href="#">James S. Morrison</a></div>
										<div class="featured_sales ml-auto"><span>352</span> Sales</div>
									</div>
								</div>
							</div>
							<div class="col-lg-6 featured_col">
							
								<div class="featured_background" style="background-image:url(images//act_sem/culto_ador2.png)"></div>
								<div style="width: 100%; position: absolute; margin-top: -450px; padding-left: 50px; padding-right: 50px;">
									<div style="width: 150px; height: 50px; border: red 1px solid; border-radius: 10px;">
										<div style="width: 40%; float: left; padding-top: 16px; padding-left: 25px;">
											<div id="punto_live" style="width: 15px; height: 15px; background-color: red; border-radius: 50%;"></div>
										</div>
										<div style="width: 60%; float: left; padding-top: 5px; padding-left: 4px;">
											<b style="color: red; font-size: 25px;">LIVE</b>
										</div>
									</div>

									<div style="width: 100%; margin-top: 50px;">
										<p style="color: #fff; font-size: 18px;">Apert. Semana Jóvenes A / Coro Lumbrera</p>
									</div>
									<div style="width: 100%; margin-top: 50px;">
										<a href="https://www.youtube.com/@pibguadalajara5203/streams" target="_blank">
											<button style="cursor: pointer; padding: 10px 50px; border-radius: 10px; background-color: #FF5C00; color: #fff; border: none;"><b>Entrar</b></button>	
										</a>
										
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="featured" style="background-color: #24344B; margin-top: -5px;">
		<div class="container">
			<div class="row">
				
				<div class="col-lg-6" style="padding-top: 50px; padding-bottom: 50px; padding-right: 30px;">
					<div class="col-lg-12" style="padding: 20px 30px; background-color: rgba(255,255,255,0.1); color: #fff; margin: 10px;">
						<b>Estudio biblico en línea</b>
						<p style="color: #ccc;">Martes 22:00 hrs., Miércoles 21:00 hrs.</p>
					</div>
					<div class="col-lg-12"  style="padding: 20px 30px; background-color: rgba(255,255,255,0.1); color: #fff; margin: 10px;">
						<b>Estudio biblico en casas</b>
						<p style="color: #ccc;">Viernes 21:00 hrs.</p>
					</div>
				</div>
				<div class="col-lg-6" style="padding-top: 50px; padding-bottom: 50px; padding-left: 50px; padding-right: 30px;">
					<h2 style="color: #fff;">Grupos de Estudio Bíblico</h2><br>
					<p style="line-height: 20px; color: #ccc; margin: 0px;">Encamíname en tu verdad, y enséñame, Porque tú eres el Dios de mi salvación; En ti he esperado todo el día.</p>
					<p style="color: #ccc; margin: 0px;"><b>Salmos 25:5</b></p><br>
					<!-- <p><a id="a_enviar_mensaje_est" href="" target="_blank" onclick="enviar_mensaje();"><button  class="course_button"><span>Quiero reunirme</span><span class="button_arrow"><i class="fa fa-angle-right" aria-hidden="true"></i></span></button></a></p> -->
					<p><a class="estilo_btn_reunion" id="a_enviar_mensaje_est" href="" target="_blank" onclick="enviar_mensaje();">Deseo reunirme</a></p>
				</div>
			</div>
		</div>
	</div>

	<div class="milestones" style="padding-bottom: 70px; padding-top: 120px;">
		
		<div class="parallax_background parallax-window" data-parallax="scroll" data-image-src="https://res.cloudinary.com/dmtvvrw4s/image/upload/v1706298697/paginaWeb/Lectura%20diaria/qgjdezvpsauzsxn9lw4s.png" data-speed="0.8"></div>
		<div class="container" style="margin-top: -70px;">
			<div class="row milestones_container">
				<div class="row">

					<div class="col-lg-12" style="padding-left: 30px; text-align: center;">
						<h2 style="color: #fff; font-weight: 300;">Lecturas del dia</h2>
						<label style="color: #fff;" id="citasdia_resumen"></label>
						<!-- <p id="p_lecturas_dia"></p> -->
					</div>
					<div class="col-lg-12">
						<div style="float: left; text-align: center; padding: 20px; height: 320px; overflow-y: scroll;" class="col-lg-4 barra_lecturas" id="box_citas_lecturas">
						</div>
						<div style="float: left; padding: 20px;" class="col-lg-8">
							<div class="col-lg-12 barra_lecturas" id="p_lecturas_dia" style="height: 300px; overflow-y: scroll;">

							</div>
						</div>
						
						

					</div>
				</div>
				<div class="col-lg-12" style="text-align: center; margin-top: 20px;">
						
					<p style="color: #fff;">Descarga el plan de lectura anual&nbsp;&nbsp;<a href="files/Lectura_biblica_anual_2025.pdf" download="Lectura_biblica_anual_2025.pdf" class="estilo_btn_plan_anual">AQUÍ</a></p>
				</div>

			</div>
		</div>
	</div>

	<style>
		.estilo_btn_reunion{
			color: #ccc; 
			background-color: #24344B;
		}
		.estilo_btn_reunion:hover{
			padding: 5px;
			color: #ccc; 
			background-color: rgba(255,255,255,0.1);
			border-radius: 5px;

		}

		.estilo_btn_plan_anual{
			color: #ccc; 
			
		}
		.estilo_btn_plan_anual:hover{
			padding: 5px;
			color: #ccc; 
			background-color: #24344B;
			border-radius: 5px;

		}
	</style>

	
	<div class="about">
		<div class="container">
		<div class="row about_row row-lg-eq-height" style="margin-top: 50px;">
                
				<div class="col-lg-8" >
					<div class="about_content" >
						<!-- <div class="about_title" style="font-size: 35px;">BREVE RESEÑA HISTORICA DE LA PRIMERA IGLESIA  BAUTISTA DE GUADALAJARA, A.R (MEXICO).</div> -->
						<div class="about_text" style="margin-top: 20px; padding: 20px;">
							<div class="col-lg-12">
								<div class="section_title text-center" style="text-align: left !important;"><h2 style="color: #44425A;">JÓVENES LUMBRERA</h2></div>
								<!-- <div class="section_subtitle" style="text-align: left !important; color: #ccc;">Querido hermano, si usted tuviera algun motivo por el cual le gustaria que estemos orando, le invitamos con todo cariño pueda dejarnos su petición de oración en el siguiente formulario.</div> -->
							</div>
							<div class="col-lg-12" style="margin-top: 50px;">
								<h4 style="line-height: 35px;"><strong style="font-size: 25px;">Ven!</strong> te invitamos a estudiar junto con nosotros la <strong style="font-size: 25px;">Palabra de Dios</strong>, te esperamos todos los <strong style="font-size: 25px;">domingos</strong> a las <strong style="font-size: 25px;">6 pm</strong>.</h4>
								
							</div> 
							<!-- <div class="col-lg-12" style=" margin-top: 30px;"> 
								<b style="font-size: 30px; ">TE ESPERAMOS!</b>
							</div> -->
							<div class="col-lg-12" style="background-color:rgb(214, 232, 248); color: #000; padding: 20px; margin-top: 50px;  box-shadow: 10px 10px 20px rgba(0,0,0,0.1); border-bottom: #FF8A00 5px solid;">
								<div class="col-lg-12">
									<p style="font-size: 18px; line-height: 35px; color: #000;">
										Ninguno tenga en poco tu juventud, sino sé ejemplo de los creyentes en palabra, conducta, amor, espíritu, fe y pureza.
									</p>
								</div>
								<div class="col-lg-12" style="margin-top: 30px; text-align: left;">
									<b style="font-size: 20px;">1 Timoteo 4:12</b>
								</div>
							</div> 
							
                             
                            
						</div>
					</div>
				</div>
				<div class="col-lg-4">
					<div class="about_image"><img src="https://res.cloudinary.com/dmtvvrw4s/image/upload/v1740410777/paginaWeb/Jovenes/Lumbrera_xkce37.png" style="width: 100%; margin-bottom: 50px;"></div>
				</div>
			</div>
		</div>
	</div>

			

	

	

	<!-- <style>
		.fade-in_ninos{-webkit-animation:fade-in_ninos 1.2s cubic-bezier(.39,.575,.565,1.000) both;animation:fade-in_ninos 1.2s cubic-bezier(.39,.575,.565,1.000) both}
		@-webkit-keyframes fade-in_ninos{0%{opacity:0}100%{opacity:1}}@keyframes fade-in_ninos{0%{opacity:0}100%{opacity:1}}
		.fade-in_jov{-webkit-animation:fade-in_jov 1.2s cubic-bezier(.39,.575,.565,1.000) .2s both;animation:fade-in_jov 1.2s cubic-bezier(.39,.575,.565,1.000) .2s both}
		@-webkit-keyframes fade-in_jov{0%{opacity:0}100%{opacity:1}}@keyframes fade-in_jov{0%{opacity:0}100%{opacity:1}}
		.fade-in_adul{-webkit-animation:fade-in_adul 1.2s cubic-bezier(.39,.575,.565,1.000) .4s both;animation:fade-in_adul 1.2s cubic-bezier(.39,.575,.565,1.000) .4s both}
		@-webkit-keyframes fade-in_adul{0%{opacity:0}100%{opacity:1}}@keyframes fade-in_adul{0%{opacity:0}100%{opacity:1}}
		.fade-in_anc{-webkit-animation:fade-in_anc 1.2s cubic-bezier(.39,.575,.565,1.000) .6s both;animation:fade-in_anc 1.2s cubic-bezier(.39,.575,.565,1.000) .6s both}
		@-webkit-keyframes fade-in_anc{0%{opacity:0}100%{opacity:1}}@keyframes fade-in_anc{0%{opacity:0}100%{opacity:1}}
		.fade-in_mis{-webkit-animation:fade-in_mis 1.2s cubic-bezier(.39,.575,.565,1.000) .8s both;animation:fade-in_mis 1.2s cubic-bezier(.39,.575,.565,1.000) .8s both}
		@-webkit-keyframes fade-in_mis{0%{opacity:0}100%{opacity:1}}@keyframes fade-in_mis{0%{opacity:0}100%{opacity:1}}
		.fade-in_core{-webkit-animation:fade-in_core 1.2s cubic-bezier(.39,.575,.565,1.000) .10s both;animation:fade-in_core 1.2s cubic-bezier(.39,.575,.565,1.000) .10s both}
		@-webkit-keyframes fade-in_core{0%{opacity:0}100%{opacity:1}}@keyframes fade-in_core{0%{opacity:0}100%{opacity:1}}
		.fade-in_coros{-webkit-animation:fade-in_coros 1.2s cubic-bezier(.39,.575,.565,1.000) .12s both;animation:fade-in_coros 1.2s cubic-bezier(.39,.575,.565,1.000) .12s both}
		@-webkit-keyframes fade-in_coros{0%{opacity:0}100%{opacity:1}}@keyframes fade-in_coros{0%{opacity:0}100%{opacity:1}}
		.fade-in_esc{-webkit-animation:fade-in_esc 1.2s cubic-bezier(.39,.575,.565,1.000) .14s both;animation:fade-in_esc 1.2s cubic-bezier(.39,.575,.565,1.000) .14s both}
		@-webkit-keyframes fade-in_esc{0%{opacity:0}100%{opacity:1}}@keyframes fade-in_esc{0%{opacity:0}100%{opacity:1}}

		.barra_min::-webkit-scrollbar {
			width: 1px;
			height: 1px;              
		}

		.barra_min::-webkit-scrollbar-track {
			background: rgb(212, 221, 226, 0.1);       
		}

		.barra_min::-webkit-scrollbar-thumb {
			background-color: rgb(40, 57, 87);    
			border-radius: 20px;    
			border: 1px solid rgba(155, 168, 175, 0.5);
			box-shadow: 5px 5px 5px rgba(0, 0, 0, 0.1);
		}
	</style> -->

	<!-- <div class="courses" style="padding-bottom: 50px;">
		<div class="container">
			<div class="row featured_row" style="padding: 20px; display: flex; justify-content: center;">
				<b style="font-size: 35px;">Ministerios</b>
				<div style="position: absolute; margin-top: 220px; z-index: 1; left: -40px;">
					<button onclick="siguiente();">Siguiente</button>
				</div>
				<div style="position: absolute; margin-top: 220px; z-index: 1; right: 0px;">
					<button onclick="anterior();">Anterior</button>
				</div>
			</div>
			<div id="content_ministerios" class="barra_min" style="margin-top: 30px; width: 100vw; height: 400px; margin-left: -200px; overflow-x: scroll;">
				
				<div style="width: 3200px;">
					<div class="col-lg-3 featured_col fade-in_ninos" style="margin-right: 0px; margin-left: 0px; float: left; width: 400px;">
						<div class="featured_content" style="padding-top: 20px; height: 250px; background-image: url(images/ministerios/ninos.png); background-repeat: no-repeat; background-size: cover;">
							<div class="featured_title" style="height: 100px;"><h3><a style="font-size: 20px; color: #ccc; text-shadow: 5px 5px 10px black;" href="#">Culto de Adoración</a></h3></div>
							<div class="featured_text">
								<b style="color: #ccc; text-shadow: 5px 5px 10px black;">Domingo 12:00 hrs.</b>
							</div>
						</div>
						<div style="width: 100%; padding: 20px 0px; font-size: 20px; text-align: center;">
							<b>Niños</b>
						</div>
						<div style="width: 100%; margin-top: -10px; margin-bottom: 20px; text-align: center;">
							<button style="background-color: rgba(0,0,0,0); color: #000; border: #000 1px solid; border-radius: 10px; padding: 10px 20px;">Ver más</button>
						</div>
					</div>
					<div class="col-lg-3 featured_col fade-in_jov" style="margin-right: 0px; margin-left: 0px; float: left; width: 400px;">
						<div class="featured_content" style="padding-top: 20px; height: 250px; background-image: url(images/ministerios/jovenes.png); background-repeat: no-repeat; background-size: cover;">
							<div class="featured_title" style="height: 100px;"><h3><a style="font-size: 20px; color: #ccc; text-shadow: 5px 5px 10px black;" href="#">Culto Dominical Vespertino</a></h3></div>
							<div class="featured_text">
								<b style="color: #ccc; text-shadow: 5px 5px 10px black;">Domingo 18:00 hrs.</b>
							</div>
						</div>
						<div style="width: 100%; padding: 20px 0px; font-size: 20px; text-align: center;">
							<b>Jóvenes</b>
						</div>
						<div style="width: 100%; margin-top: -10px; margin-bottom: 20px; text-align: center;">
							<button style="background-color: rgba(0,0,0,0); color: #000; border: #000 1px solid; border-radius: 10px; padding: 10px 20px;">Ver más</button>
						</div>
					</div>
					<div class="col-lg-3 featured_col fade-in_adul" style="margin-right: 0px; margin-left: 0px; float: left; width: 400px;">
						<div class="featured_content" style="padding-top: 20px; height: 250px; background-image: url(images/ministerios/adultos.png); background-repeat: no-repeat; background-size: cover;">
							<div class="featured_title" style="height: 100px;"><h3><a style="font-size: 20px; color: #ccc; text-shadow: 5px 5px 10px black;" href="#">Culto de Oración</a></h3></div>
							<div class="featured_text">
								<b style="color: #ccc; text-shadow: 5px 5px 10px black;">Miercoles 19:00 hrs.</b>
							</div>
						</div>
						<div style="width: 100%; padding: 20px 0px; font-size: 20px; text-align: center;">
							<b>Adultos</b>
						</div>
						<div style="width: 100%; margin-top: -10px; margin-bottom: 20px; text-align: center;">
							<button style="background-color: rgba(0,0,0,0); color: #000; border: #000 1px solid; border-radius: 10px; padding: 10px 20px;">Ver más</button>
						</div>
					</div>
					<div class="col-lg-3 featured_col fade-in_anc" style="margin-right: 0px; margin-left: 0px; float: left; width: 400px;">
						<div class="featured_content" style="padding-top: 20px; height: 250px; background-image: url(images/ministerios/ancianitos.png); background-repeat: no-repeat; background-size: cover;">
							<div class="featured_title" style="height: 100px;"><h3><a style="font-size: 20px; color: #ccc; text-shadow: 5px 5px 10px black;" href="#">Culto de Estudio Biblico</a></h3></div>
							<div class="featured_text">
								<b style="color: #ccc; text-shadow: 5px 5px 10px black;">Viernes 19:00 hrs.</b>
							</div>
						</div>
						<div style="width: 100%; padding: 20px 0px; font-size: 20px; text-align: center;">
							<b>Tercera edad</b>
						</div>
						<div style="width: 100%; margin-top: -10px; margin-bottom: 20px; text-align: center;">
							<button style="background-color: rgba(0,0,0,0); color: #000; border: #000 1px solid; border-radius: 10px; padding: 10px 20px;">Ver más</button>
						</div>
					</div>
					<div class="col-lg-3 featured_col fade-in_mis" style="margin-right: 0px; margin-left: 0px; float: left; width: 400px;">
						<div class="featured_content" style="padding-top: 20px; height: 250px; background-image: url(images/ministerios/misiones.png); background-repeat: no-repeat; background-size: cover;">
							<div class="featured_title" style="height: 100px;"><h3><a style="font-size: 20px; color: #ccc; text-shadow: 5px 5px 10px black;" href="#">Culto de Estudio Biblico</a></h3></div>
							<div class="featured_text">
								<b style="color: #ccc; text-shadow: 5px 5px 10px black;">Viernes 19:00 hrs.</b>
							</div>
						</div>
						<div style="width: 100%; padding: 20px 0px; font-size: 20px; text-align: center;">
							<b>Misiones</b>
						</div>
						<div style="width: 100%; margin-top: -10px; margin-bottom: 20px; text-align: center;">
							<button style="background-color: rgba(0,0,0,0); color: #000; border: #000 1px solid; border-radius: 10px; padding: 10px 20px;">Ver más</button>
						</div>
					</div>
					<div class="col-lg-3 featured_col fade-in_core" style="margin-right: 0px; margin-left: 0px; float: left; width: 400px;">
						<div class="featured_content" style="padding-top: 20px; height: 250px; background-image: url(images/ministerios/core.png); background-repeat: no-repeat; background-size: cover;">
							<div class="featured_title" style="height: 100px;"><h3><a style="font-size: 20px; color: #ccc; text-shadow: 5px 5px 10px black;" href="#">Culto de Estudio Biblico</a></h3></div>
							<div class="featured_text">
								<b style="color: #ccc; text-shadow: 5px 5px 10px black;">Viernes 19:00 hrs.</b>
							</div>
						</div>
						<div style="width: 100%; padding: 20px 0px; font-size: 20px; text-align: center;">
							<b>Academia Coré</b>
						</div>
						<div style="width: 100%; margin-top: -10px; margin-bottom: 20px; text-align: center;">
							<button style="background-color: rgba(0,0,0,0); color: #000; border: #000 1px solid; border-radius: 10px; padding: 10px 20px;">Ver más</button>
						</div>
					</div>
					<div class="col-lg-3 featured_col fade-in_coros" style="margin-right: 0px; margin-left: 0px; float: left; width: 400px;">
						<div class="featured_content" style="padding-top: 20px; height: 250px; background-image: url(images/ministerios/coros.png); background-repeat: no-repeat; background-size: cover;">
							<div class="featured_title" style="height: 100px;"><h3><a style="font-size: 20px; color: #ccc; text-shadow: 5px 5px 10px black;" href="#">Culto de Estudio Biblico</a></h3></div>
							<div class="featured_text">
								<b style="color: #ccc; text-shadow: 5px 5px 10px black;">Viernes 19:00 hrs.</b>
							</div>
						</div>
						<div style="width: 100%; padding: 20px 0px; font-size: 20px; text-align: center;">
							<b>Coros</b>
						</div>
						<div style="width: 100%; margin-top: -10px; margin-bottom: 20px; text-align: center;">
							<button style="background-color: rgba(0,0,0,0); color: #000; border: #000 1px solid; border-radius: 10px; padding: 10px 20px;">Ver más</button>
						</div>
					</div>
					<div class="col-lg-3 featured_col fade-in_esc" style="margin-right: 0px; margin-left: 0px; float: left; width: 400px;">
						<div class="featured_content" style="padding-top: 20px; height: 250px; background-image: url(images/ministerios/esc.png); background-repeat: no-repeat; background-size: cover;">
							<div class="featured_title" style="height: 100px;"><h3><a style="font-size: 20px; color: #ccc; text-shadow: 5px 5px 10px black;" href="#">Culto de Estudio Biblico</a></h3></div>
							<div class="featured_text">
								<b style="color: #ccc; text-shadow: 5px 5px 10px black;">Viernes 19:00 hrs.</b>
							</div>
						</div>
						<div style="width: 100%; padding: 20px 0px; font-size: 20px; text-align: center;">
							<b>Escuela Dominical</b>
						</div>
						<div style="width: 100%; margin-top: -10px; margin-bottom: 20px; text-align: center;">
							<button style="background-color: rgba(0,0,0,0); color: #000; border: #000 1px solid; border-radius: 10px; padding: 10px 20px;">Ver más</button>
						</div>
					</div>
				</div>
				
				
			</div>
		</div>
	</div> -->

	<div class="courses" style="display: none;">
		<div class="container">
			 <div class="row">
				<div class="col-lg-10 offset-lg-1">
					<div class="section_title text-center"><h2>Grupos de Estudio Bíblico</h2></div>
					<div class="section_subtitle" style="font-size: 20px; margin-top: 30px;">Encamíname en tu verdad, y enséñame, Porque tú eres el Dios de mi salvación; En ti he esperado todo el día.</div>
					<div style="text-align: center;">
						<b style="font-size: 25px;">Salmos 25:5</b>
					</div>
					<div style="width: 100%; margin-top: 50px;">
						<div style="float: left; width: 50%; text-align: center;">
							<div class="news_post_body" style="background-color: #F1F3F5; padding-top: 30px; padding-bottom: 30px; border: #fff 10px solid; padding-left: 10px; padding-right: 10px;">
								<div class="news_post_date" style="font-size: 18px;">Miercoles</div>
								<div class="news_post_title"><a href="#">Estudio biblico en linea</a></div>
								<div class="news_post_author">21:00 hrs.</div>
							</div>
						</div>
						<div style="float: left; width: 50%; text-align: center;">
							<div class="news_post_body" style="background-color: #F1F3F5; padding-top: 30px; padding-bottom: 30px; border: #fff 10px solid; padding-left: 10px; padding-right: 10px;">
								<div class="news_post_date" style="font-size: 18px;">Viernes</div>
								<div class="news_post_title"><a href="#">Estudio biblico en casa</a></div>
								<div class="news_post_author">21:00 hrs.</div>
							</div>
						</div>
					</div>
					
				</div>
			</div>
			<div class="row">
				<div class="col">
					<div class="course_search" style="text-align: center;">
					<a id="a_enviar_mensaje_est" href="" target="_blank" onclick="enviar_mensaje();"><button  class="course_button"><span>Quiero reunirme</span><span class="button_arrow"><i class="fa fa-angle-right" aria-hidden="true"></i></span></button></a>
					
						<!-- <form action="#" class="course_search_form d-flex flex-md-row flex-column align-items-start justify-content-between" style="text-align: center !important;"> -->
							
							<!-- <div><input type="text" class="course_input" placeholder="Course" required="required"></div>
							<div><input type="text" class="course_input" placeholder="Level" required="required"></div> -->
							
						<!-- </form> -->
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col">

					<!-- Courses Slider -->
					<div class="courses_slider_container">
						<div class="owl-carousel owl-theme courses_slider">

							<!-- Slider Item -->
							<div class="owl-item">
								<div class="course">
									<div class="course_image"><img src="images/destacados/2.png" alt=""></div>
									<div class="course_body" style="height: 200px; padding-top: 15px;">

										<div class="course_title"><h3><a href="#">Escuela Bíblica Dominical</a></h3></div>
										<div class="course_text" style="line-height : 15px; margin-top: 40px;">
											<label for="">Todos los domingos</label><br>
											<label for="">Hora: 10:30 am</label>
										</div>
										
										<div class="course_header d-flex flex-row align-items-center justify-content-start" style="margin-top: 50px;">
											
										</div>
									</div>
								</div>
							</div>

							<div class="owl-item">
								<div class="course">
									<div class="course_image"><img src="images/destacados/1.png" alt=""></div>
									<div class="course_body" style="height: 200px; padding-top: 15px;">

										<div class="course_title"><h3><a href="#" id="nom_activ_esp_1">Unión de Jóvenes</a></h3></div>
										<div class="course_text" style="line-height : 15px; margin-top: 40px;">
											<label for="" id="detalle_activ_esp1">Todos los domingos</label><br>
											<label for="">Hora: 05:00 pm</label>
										</div>
										
										<div class="course_header d-flex flex-row align-items-center justify-content-start" style="margin-top: 50px;">
											
										</div>
									</div>
								</div>
							</div>

							<div class="owl-item">
								<div class="course">
									<div class="course_image"><img src="images/destacados/3.png" alt=""></div>
									<div class="course_body" style="height: 200px; padding-top: 15px;">

										<div class="course_title"><h3><a href="#">Unión Femenil</a></h3></div>
										<div class="course_text" style="line-height : 15px; margin-top: 40px;">
											<label for="">Todos los viernes</label><br>
											<!-- <label for="">Hora: 19:00 hrs.</label> -->
										</div>
										
										<div class="course_header d-flex flex-row align-items-center justify-content-start" style="margin-top: 50px;">
											
										</div>
									</div>
								</div>
							</div>

						</div>

						<!-- Courses Slider Nav -->
						<div class="courses_slider_nav courses_slider_prev trans_200"><i class="fa fa-angle-left" aria-hidden="true"></i></div>
						<div class="courses_slider_nav courses_slider_next trans_200"><i class="fa fa-angle-right" aria-hidden="true"></i></div>

					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- <div class="courses">
		<div class="container">
			 <div class="row">
				<div class="col-lg-10 offset-lg-1">
					<div class="section_title text-center"><h2>Actividades Destacadas</h2></div>
					
				</div>
			</div>
			
			<div class="row">
				<div class="col">

					<div class="courses_slider_container">
						<div class="owl-carousel owl-theme courses_slider" id="box_activ_des">

						</div>

						
					</div>
				</div>
			</div>
		</div>
	</div> -->

	<!-- Milestones -->

	<!-- <div class="video" style="padding-top: 50px;">
		<div class="container">
			<div class="row" style="padding-left: 20px;">
				<b style="font-size: 30px;">Ultima Transmisión</b>
			</div>
			<div class="row" style="padding-left: 20px;">
				<label for="">Click sobre la imagen para reproducir</label>
			</div>
			<div class="row">
				
				<div class="col">
					<div class="video_container_outer">
						<img src="images/iconos/boton-de-play.png" alt="" style="width: 60px; position: absolute; z-index: 9999; margin-left: 43%; margin-top: 25%;">
						<div class="video_container">
							
							
							<video id="vid1" class="video-js vjs-default-skin" controls data-setup='{ "poster": "images/fondos/transmision.png", "techOrder": ["youtube"], "sources": [{ "type": "video/youtube", "src": "https://www.youtube.com/watch?v=WCceYU_yNBA"}], "youtube": { "iv_load_policy": 1 } }'>
							</video>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div> -->

	




	

	

	<!-- Sections -->

	<div class="grouped_sections">
		<div class="container">
			<div class="row">

				<!-- Why Choose Us -->

				<!-- <div class="col-lg-4 grouped_col">
					<div class="grouped_title">Why Choose Us?</div>
					<div class="accordions">

						<div class="accordion_container">
							<div class="accordion d-flex flex-row align-items-center active"><div>Mauris vehicula nisi congue?</div></div>
							<div class="accordion_panel">
								<div>
									<p>Suspendisse tincidunt magna eget massa hendrerit efficitur. Ut euismod pellentesque imperdiet. Cras laoreet gravida lectus, at viverra lorem venenatis in. Aenean id varius quam.</p>
								</div>
							</div>
						</div>

						<div class="accordion_container">
							<div class="accordion d-flex flex-row align-items-center"><div>Vehicula nisi congue, blandit?</div></div>
							<div class="accordion_panel">
								<div>
									<p>Suspendisse tincidunt magna eget massa hendrerit efficitur. Ut euismod pellentesque imperdiet. Cras laoreet gravida lectus, at viverra lorem venenatis in. Aenean id varius quam.</p>
								</div>
							</div>
						</div>

						<div class="accordion_container">
							<div class="accordion d-flex flex-row align-items-center"><div>Mauris vehicula nisi congue?</div></div>
							<div class="accordion_panel">
								<div>
									<p>Suspendisse tincidunt magna eget massa hendrerit efficitur. Ut euismod pellentesque imperdiet. Cras laoreet gravida lectus, at viverra lorem venenatis in. Aenean id varius quam.</p>
								</div>
							</div>
						</div>

						<div class="accordion_container">
							<div class="accordion d-flex flex-row align-items-center"><div>Nisi congue, blandit purus sed?</div></div>
							<div class="accordion_panel">
								<div>
									<p>Suspendisse tincidunt magna eget massa hendrerit efficitur. Ut euismod pellentesque imperdiet. Cras laoreet gravida lectus, at viverra lorem venenatis in. Aenean id varius quam.</p>
								</div>
							</div>
						</div>

					</div>

				</div> -->

				<!-- Events -->

				<div class="col-lg-6 grouped_col">
					<div class="grouped_title">Calendario 2024</div>
					<div class="events" id="box_calendario">

						

					</div>
				</div>

				<!-- News -->

				<div class="col-lg-6 grouped_col">
					<div class="grouped_title">Predicaciones</div>
					<div class="news">

						<!-- Mostrar las últimas dos predicacines -->
						<?php
							//info del servidor
							require('config/global.php');
							$connection = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

							//verificamos la conexion
							if(!$connection) 
							{
								echo "<p>Error al conectarse a la base de datos. Código de error: " .  mysqli_connect_errno() . " " .  mysqli_connect_error() . "</p>";
							}
							else
							{
								//consulta
								$consulta = "SELECT * FROM sermones ORDER BY idsermones DESC LIMIT 2;";
								$result = mysqli_query($connection,$consulta);
								$error_message = mysqli_error($connection);
								if(!$result) 
								{
									echo "<p>No se ha podido realizar la consulta</p>" . " " . $error_message;
								}else{
									while ($colum = mysqli_fetch_array($result))
									{
										echo "<div class='news_post d-flex flex-row align-items-start justify-content-start'>";
										echo "<div><div class='news_post_image'><img src='" . $colum['imagen'] . "' alt=''></div></div>";
										echo "<div class='news_post_body'>";
										echo "<div class='news_post_date'>" . $colum['fecha_eti'] . "</div>";
										echo "<div class='news_post_title'><a href='blog.php?id=" . $colum['idsermones'] . "'>" . $colum['nom_sermon'] . "</a></div>";
										echo "<div class='news_post_author'>" . $colum['predicador'] . "</div>";
										echo "</div></div>";
									}
								}

								//cerrar la conexion con la base de datos
								mysqli_close( $connection );
							}
						?>

						<!-- News Post -->
						<!-- <div class="news_post d-flex flex-row align-items-start justify-content-start">
							<div><div class="news_post_image"><img src="images/predicaciones/1.png" alt=""></div></div>
							<div class="news_post_body">
								<div class="news_post_date">Enero 06, 2022</div>
								<div class="news_post_title"><a href="blog.php">Meditación del Salmo 108</a></div>
								<div class="news_post_author">Pastor: Isaac Sotomayor Gutiérrez</div>
							</div>
						</div> -->

						<!-- News Post -->
						<!-- <div class="news_post d-flex flex-row align-items-start justify-content-start">
							<div><div class="news_post_image"><img src="images/predicaciones/2.png" alt=""></div></div>
							<div class="news_post_body">
								<div class="news_post_date">Diciembre 29, 2021</div>
								<div class="news_post_title"><a href="blog.php">Meditación del Salmos 107</a></div>
								<div class="news_post_author">Pastor: Isaac Sotomayor Gutiérrez</div>
							</div>
						</div> -->


						<!-- <div class="news_post d-flex flex-row align-items-start justify-content-start">
							<div><div class="news_post_image"><img src="images/news_3.jpg" alt="https://unsplash.com/@rawpixel"></div></div>
							<div class="news_post_body">
								<div class="news_post_date">April 02, 2018</div>
								<div class="news_post_title"><a href="news.html">Why Choose online education?</a></div>
								<div class="news_post_author">By <a href="#">William Smith</a></div>
							</div>
						</div>


						<div class="news_post d-flex flex-row align-items-start justify-content-start">
							<div><div class="news_post_image"><img src="images/news_4.jpg" alt="https://unsplash.com/@jtylernix"></div></div>
							<div class="news_post_body">
								<div class="news_post_date">April 02, 2018</div>
								<div class="news_post_title"><a href="news.html">Books, Kindle or tablet?</a></div>
								<div class="news_post_author">By <a href="#">William Smith</a></div>
							</div>
						</div> -->

					</div>
				</div>
			</div>
		</div>
	</div>

	

	<!-- Join -->
<!-- 
	<div class="join" style="padding-bottom: 20px;" id="notif">
		<div class="container">
			<div class="row">
				<div class="col-lg-10 offset-lg-1">
					<div class="section_title text-center"><h2>¿Desea recibir notificaciones?</h2></div>
					<div class="section_subtitle" style="font-size: 18px;">Registrate para recibir noticias sobre nuevas publicaciones.</div>
				</div>
			</div>
		</div>
		<br><br>
		<div class="button join_button"><a href="#">Registrarse<div class="button_arrow"><i class="fa fa-angle-right" aria-hidden="true"></i></div></a></div>
		<a href="#notif"><button  class="course_button"><span>Registrarme</span><span class="button_arrow"><i class="fa fa-angle-right" aria-hidden="true"></i></span></button></a>
	</div>
	
		<div class="container" >

			<div class="row">
				<div class="col">
					<div class="course_search">
						
						<form class="course_search_form d-flex flex-md-row flex-column align-items-start justify-content-between" style="margin-bottom: 50px;">
									<div><input type="text" class="course_input" placeholder="Nombre" id="nombre" required="required"></div>
									<div><input type="text" class="course_input" placeholder="Teléfono" id="telefono" required="required"></div>
									<b onclick="enviar_datos_nuevo_contacto();" style="cursor: pointer; width: 150px; text-align: center; background-color: #044BA1; color: #fff; font-size: 15px; padding: 10px; border-radius: 10px; box-shadow: 5px 5px 10px rgba(0,0,0,0.1);"> Enviar</b>

									<button  class="course_button"><span>Enviar</span><span class="button_arrow"><i class="fa fa-angle-right" aria-hidden="true"></i></span></button>
						</form>
					</div>
				</div>
			</div>

		</div>

	
		 -->
		
	

<!-- Footer -->
<!-- <button id="boton_prueba_notif" onclick="prueba_notif()">Prueba notif</button> -->
<?php
	require('footer.php');
?>

</div>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script type="text/javascript" src="scripts/index.js?v=<?php echo(rand()); ?>"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.26.0/moment.min.js"></script>
<script src="js/jquery-3.2.1.min.js"></script>
<script src="styles/bootstrap4/popper.js"></script>
<script src="styles/bootstrap4/bootstrap.min.js"></script>
<script src="plugins/greensock/TweenMax.min.js"></script>
<script src="plugins/greensock/TimelineMax.min.js"></script>
<script src="plugins/scrollmagic/ScrollMagic.min.js"></script>
<script src="plugins/greensock/animation.gsap.min.js"></script>
<script src="plugins/greensock/ScrollToPlugin.min.js"></script>
<script src="plugins/OwlCarousel2-2.2.1/owl.carousel.js"></script>
<script src="plugins/easing/easing.js"></script>
<script src="plugins/video-js/video.min.js"></script>
<script src="plugins/video-js/Youtube.min.js"></script>
<script src="plugins/parallax-js-master/parallax.min.js"></script>
<script src="js/custom.js"></script>
<script src="js/bootbox.js"></script>
</body>
</html>