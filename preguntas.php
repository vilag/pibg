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
  <style>
    body {
      font-family: system-ui, -apple-system, 'Segoe UI', Roboto, Arial;
      margin: 2rem;
      background: #f7f7fb;
    }
    .card {
      background: white;
      padding: 1.2rem;
      border-radius: 8px;
      max-width: 600px;
      margin: 0 auto;
      box-shadow: 0 6px 18px rgba(20, 20, 40, 0.06);
    }
    h1 {
      font-size: 1.4rem;
      margin-bottom: 1rem;
    }
    ol {
      padding-left: 1.2rem;
    }
    li {
      margin-bottom: .4rem;
      padding: .4rem .6rem;
      background: #f9fafb;
      border-radius: 6px;
      border: 1px solid #e5e7eb;
    }
    .empty {
      color: #666;
      text-align: center;
      font-style: italic;
      margin-top: 1rem;
    }
    button {
      margin-top: 1rem;
      background: #dc2626;
      color: white;
      border: none;
      padding: .6rem 1rem;
      border-radius: 8px;
      cursor: pointer;
      font-weight: 600;
    }
    button:hover {
      background: #b91c1c;
    }
  </style>
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
