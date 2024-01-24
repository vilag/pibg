<!DOCTYPE html>
<html lang="en">
<head>
<title>Predicaciones</title>
<link href="images/iconos/icono.png" rel="icon">
<link rel="stylesheet" type="text/css" href="styles/bootstrap4/bootstrap.min.css">
<link href="plugins/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link href="plugins/video-js/video-js.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="styles/news.css">
<link rel="stylesheet" type="text/css" href="styles/news_responsive.css">
</head>
<body>

<div class="super_container">

	<?php
		require 'header.php';
	?>
	
	<!-- Home -->
	<div class="home">
		<div class="home_background parallax_background parallax-window" data-parallax="scroll" data-image-src="images/predicaciones/caratula.png" data-speed="0.8"></div>
		<div class="home_container">
			<div class="container">
				<div class="row">
					<div class="col">
						<div class="home_content text-center">
							<div class="home_title" style="text-shadow: 5px 5px 10px rgba(0,0,0,0.5);">Predicaciones</div>
							<div class="breadcrumbs">
								<ul>
									<li><a href="./" style="text-shadow: 5px 5px 10px rgba(0,0,0,0.5);">Inicio</a></li>
									<li style="text-shadow: 5px 5px 10px rgba(0,0,0,0.5);">Predicaciones</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- News -->
	<div class="news">
		<div class="container">
			<div class="row">

				<!-- News Posts -->
				<div class="col-lg-8">
					<div class="news_posts">
						
						<!-- News Post -->
						<?php
							// Obtener los parámetros desde la URL (toma 1 por defecto)
							$categoria = isset($_GET['cat']) ? $_GET['cat'] : 1;

							//info del servidor
							require('config/global.php');
							$connection = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

							//verificamos la conexion
							if(!$connection) 
							{
								echo "<p>Error al conectarse a la base de datos. Código de error: " .  mysqli_connect_errno() . " " .  mysqli_connect_error() . "</p>";
								echo "<p>No se ha podido realizar la consulta</p>" . " " . $error_message;
								$fecha = 0;
								$nom_sermon = 0;
								$predicador = 0;
								$actividad = 0;
								$predicacion = 0;
								$imagen = "images/predicaciones/portadas/fondo1.png";
							}
							else
							{
								//consulta
								$consulta = "SELECT * FROM sermones WHERE idsermones LIMIT 3;";
								$result = mysqli_query($connection,$consulta);
								$error_message = mysqli_error($connection);
								if($result) 
								{
									while ($colum = mysqli_fetch_array($result))
									{
										//recortar predicación
										$predicacion = $colum['predicacion'];
										$predicacion_recortada = substr($predicacion, 0, 500);

										//mostrar las últimas 3
										echo "<div class='news_post'>";
										echo "<div class='news_post_image'><img src='" . $colum['imagen'] . "' alt=''></div>";
										echo "<div class='news_post_body'>";
										echo "<div class='news_post_date'><a href='#'>" . $colum['fecha_eti'] . "</a></div>";
										echo "<div class='news_post_title'><a href='#'>" . $colum['nom_sermon'] . "</a></div>";
										echo "<div class='news_post_meta d-flex flex-row align-items-start justify-content-start'>";
										echo "<div class='news_post_author'><a href='#'>" . $colum['predicador'] . "</a></div>";
										echo "<div class='news_post_comments'><a href='#'>" . $colum['actividad'] . "</a></div>";
										echo "</div>";
										echo "<div class='news_post_text'>";
										echo "<p>" . $predicacion_recortada . "...</p>";
										echo "</div>";
										echo "<div class='news_post_link'><a href='blog.php?id=" . $colum['idsermones'] ."&cat=" . $colum['categoria'] . "'>Seguir leyendo</a></div>";
										echo "</div></div>";
									}
								}
							}
						?>
						<!--
						<div class="news_post">
							<div class="news_post_image"><img src="images/news_5.jpg" alt=""></div>
							<div class="news_post_body">
								<div class="news_post_date"><a href="#">April 02, 2018</a></div>
								<div class="news_post_title"><a href="#">Books, Kindle or Tablet?</a></div>
								<div class="news_post_meta d-flex flex-row align-items-start justify-content-start">
									<div class="news_post_author">By <a href="#">William Smith</a></div>
									<div class="news_post_comments"><a href="#">3 Comments</a></div>
									<div class="news_post_tags">
										<span>in </span>
										<ul>
											<li><a href="#">Social Media</a></li>
										</ul>
									</div>
								</div>
								<div class="news_post_text">
									<p>Suspendisse tincidunt magna eget massa hendrerit efficitur. Ut euismod pellentesque imperdiet. Cras laoreet gravida lectus, at viverra lorem venenatis in. Aenean id varius quam. Nullam bibendum interdum dui, ac tempor lorem convallis ut. Maecenas rutrum viverra sapien sed fermentum. Morbi tempor odio eget lacus tempus pulvinar. Praesent vel nisl fermentum, gravida augue.</p>
								</div>
								<div class="news_post_link"><a href="#">Read More</a></div>
							</div>
						</div>-->
					</div>
				</div>

				<!-- Sidebar -->
				<div class="col-lg-4">
					<div class="sidebar">
						<div class="sidebar_categories">
							<div class="sidebar_title">Categorias</div>

							<!--Categorias-->
							<div class="sidebar_links">
								<ul>
									<!--Mostrar todas las categorías de sermones-->
									<?php
									if(!$connection) 
									{
										echo "<p>Error al conectarse a la base de datos. Código de error: " .  mysqli_connect_errno() . " " .  mysqli_connect_error() . "</p>";
									}
									else
									{
										$consulta = "SELECT * FROM cat_sermones;";
										$result = mysqli_query($connection,$consulta);
										$error_message = mysqli_error($connection);
										if(!$result) 
										{
											echo "<p>No se ha podido realizar la consulta</p>" . " " . $error_message;
										}else{
											$contador = 1;
											while ($colum = mysqli_fetch_array($result))
											{
												echo "<li><a href='predicaciones.php?cat=" . $contador . "'>" . $colum['nombre'] . "</a></li>";
												$contador++;
											}
										}
									}
									?>
								</ul>
							</div>
						</div>

						<!--Ultimas 2 publicaciones-->
						<div class="sidebar_latest_posts">
							<div class="sidebar_title">Ultimas publicaciones</div>
							<div class="latest_posts">
								<?php
									if(!$connection) 
									{
										echo "<p>Error al conectarse a la base de datos. Código de error: " .  mysqli_connect_errno() . " " .  mysqli_connect_error() . "</p>";
									}
									else
									{
										$consulta = "SELECT * FROM sermones WHERE categoria = $categoria;";
										$result = mysqli_query($connection,$consulta);
										$error_message = mysqli_error($connection);
										if(!$result) 
										{
											echo "<p>No se ha podido realizar la consulta</p>" . " " . $error_message;
										}else{
											while ($colum = mysqli_fetch_array($result))
											{
												echo "<div class='latest_post d-flex flex-row align-items-start justify-content-start'>";
												echo "<div><div class='latest_post_image'><img src='" . $colum['imagen'] . "' alt=''></div></div>";
												echo "<div class='latest_post_body'>";
												echo "<div class='latest_post_date'>" . $colum['fecha_eti'] . "</div>";
												echo "<div class='latest_post_title'><a href='blog.php?id=" . $colum['idsermones'] ."&cat=" . $categoria . "'>" . $colum['nom_sermon'] . "</a></div>";
												echo "<div class='latest_post_author'>" . $colum['predicador'] . "</div>";
												echo "</div></div>";
											}
										}
										//cerrar la conexion con la base de datos
										mysqli_close( $connection );
									}
								?>

							</div>
						</div>
					</div>
				</div>

			<!-- Pagination
			<div class="row">
				<div class="col">
					<div class="news_pagination">
						<ul>
							<li><a href="#">01</a></li>
							<li class="active"><a href="#">02</a></li>
							<li><a href="#">03</a></li>
							<li><a href="#">04</a></li>
							<li><a href="#">05</a></li>
						</ul>
					</div>
				</div>
			</div>-->

		</div>
	</div>

	<!-- Footer -->
	<?php
		require 'footer.php';
	?>
</div>

<script src="js/jquery-3.2.1.min.js"></script>
<script src="styles/bootstrap4/popper.js"></script>
<script src="styles/bootstrap4/bootstrap.min.js"></script>
<script src="plugins/easing/easing.js"></script>
<script src="plugins/parallax-js-master/parallax.min.js"></script>
<script src="js/news.js"></script>
</body>
</html>