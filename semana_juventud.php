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
	<input id="input_vista" type="hidden" value="0">
		<div class="home_background parallax_background parallax-window" data-parallax="scroll" data-image-src="images/jovenes/lumbrera2.jpg" data-speed="0.8"></div>
		<div class="home_container">
			<div class="container">
				<div class="row">
					<div class="col">
						<div class="home_content text-center">
							<div class="home_title" style="text-shadow: 5px 5px 10px rgba(0,0,0,0.5);">Semana de la juventud</div>
							<div class="breadcrumbs">
								<ul>
									<li><a href="./" style="text-shadow: 5px 5px 10px rgba(0,0,0,0.5);">Inicio</a></li>
									<li style="text-shadow: 5px 5px 10px rgba(0,0,0,0.5);">Semana de la juventud</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

    <?php
    // Nombre de la tabla y columna donde insertaremos el campo
	require('config/global.php');
    $table_name = 'semana_juventud';
    $column_name = 'pregunta';


    // Conexión con mysqli
    $mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if ($mysqli->connect_errno) {
    http_response_code(500);
    echo "Error al conectar con la base de datos: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    exit;
    }


    // Establecer charset
    $mysqli->set_charset('utf8mb4');


    // Manejo del formulario
    $message = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener y limpiar entrada
    $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';


    // Validaciones básicas
    if ($nombre === '') {
    $message = 'Por favor ingresa la pregunta.';
    } elseif (mb_strlen($nombre) > 100) {
    $message = 'La pregunta es demasiado larga (máx. 100 caracteres).';
    } else {
    // Preparar statement para evitar inyecciones SQL
    $sql = "INSERT INTO `$table_name` (`$column_name`) VALUES (?);";
    if ($stmt = $mysqli->prepare($sql)) {
    $stmt->bind_param('s', $nombre);
    if ($stmt->execute()) {
    $message = 'Registro guardado correctamente.';
    } else {
    $message = 'Error al guardar: ' . $stmt->error;
    }
    $stmt->close();
    } else {
    $message = 'Error en la preparación de la consulta: ' . $mysqli->error;
    }
    }
    }

    // Cerrar conexión al final del script (opcional)
    $mysqli->close();
?>

<div class="news">
		<div class="container">
<h1>Dudas de la conferencia</h1>


<?php if ($message !== ''): ?>
<div class="message <?php echo (strpos($message, 'correcto') !== false || strpos($message, 'guardado') !== false) ? 'success' : 'error'; ?>">
<?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
</div>
<?php endif; ?>


<form method="post" action="">
<p for="nombre">Introduce tu pregunta y presiona "Guardar".</p>
<input id="nombre" name="nombre" type="text" maxlength="100" required>
<button type="submit" style="padding: 5px 10px 5px 10px; background-color: #F85E0C; color: #fff; border-radius: 5px;">Guardar</button>
</form>

</div>
</div>

</div>