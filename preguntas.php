<?php
require('config/global.php');

$table_name = 'semana_juventud';
$column_name = 'pregunta';

$mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
if ($mysqli->connect_errno) {
    http_response_code(500);
    die("Error al conectar con la base de datos: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
}
$mysqli->set_charset('utf8mb4');

$sql = "SELECT `$column_name` FROM `$table_name`;";
$result = $mysqli->query($sql);

$preguntas = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $preguntas[] = $row[$column_name];
    }
}
$mysqli->close();
?>

<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Lista de preguntas</title>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="styles/preguntas.css">
</head>
<body>
  <main class="card">
    <h1>Preguntas registradas</h1>

    <?php if (empty($preguntas)): ?>
      <p class="empty">No hay preguntas registradas aún.</p>
    <?php else: ?>
      <ol>
        <?php foreach ($preguntas as $p): ?>
          <li><?= htmlspecialchars($p, ENT_QUOTES, 'UTF-8') ?></li>
        <?php endforeach; ?>
      </ol>
      <form id="formEliminarTodo" method="post" action="eliminar_todo.php" onsubmit="return confirmarEliminar();">
        <button type="submit">🗑️ Eliminar todas las preguntas</button>
      </form>
    <?php endif; ?>
  </main>

  <script>
    function confirmarEliminar() {
      return confirm('¿Seguro que deseas eliminar TODAS las preguntas? Esta acción no se puede deshacer.');
    }
  </script>
</body>
</html>
