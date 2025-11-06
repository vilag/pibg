<?php
require('config/global.php');

$table_name = 'semana_juventud';

$mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
if ($mysqli->connect_errno) {
    http_response_code(500);
    die("Error al conectar con la base de datos.");
}

$sql = "DELETE FROM `$table_name`";
if ($mysqli->query($sql)) {
    echo "<script>alert('Todas las preguntas han sido eliminadas correctamente.'); window.location.href='preguntas.php';</script>";
} else {
    echo "<script>alert('Error al eliminar: " . addslashes($mysqli->error) . "'); window.location.href='preguntas.php';</script>";
}

$mysqli->close();
?>
