<?php
session_start();
if (!isset($_SESSION["nombre"])) { http_response_code(403); exit; }
require_once "../modelos/Series_pred.php";

$series = new Series_pred();

switch ($_GET["op"] ?? '') {

    case 'listar':
        $result = $series->listar_series();
        while ($reg = $result->fetch_object()) {
            $badge = $reg->estatus
                ? "<span style='background:#28a745;color:#fff;padding:2px 8px;border-radius:10px;font-size:11px;'>Activa</span>"
                : "<span style='background:#dc3545;color:#fff;padding:2px 8px;border-radius:10px;font-size:11px;'>Inactiva</span>";
            $fi = $reg->fecha_inicio ? date('d/m/Y', strtotime($reg->fecha_inicio)) : '—';
            $ff = $reg->fecha_fin    ? date('d/m/Y', strtotime($reg->fecha_fin))    : '—';
            $img_html = $reg->imagen
                ? "<img src='../" . htmlspecialchars($reg->imagen) . "' onerror=\"this.style.display='none'\" style='width:50px;height:50px;object-fit:cover;border-radius:4px;'>"
                : "<span style='color:#aaa;font-size:11px;'>Sin imagen</span>";
            $nombre_js = addslashes(htmlspecialchars($reg->nombre));
            echo "
            <tr>
                <td>{$reg->idserie}</td>
                <td>{$img_html}</td>
                <td>" . htmlspecialchars($reg->nombre) . "</td>
                <td>{$fi} — {$ff}</td>
                <td><b>{$reg->total_sermones}</b></td>
                <td>{$badge}</td>
                <td>
                    <button onclick=\"editar_serie({$reg->idserie});\" style=\"background-color:#042C49;padding:5px 9px;border-radius:5px;border:none;color:#fff;cursor:pointer;margin-right:3px;font-size:11px;\">Editar</button>
                    <button onclick=\"ver_sermones_serie({$reg->idserie},'{$nombre_js}');\" style=\"background-color:#17a2b8;padding:5px 9px;border-radius:5px;border:none;color:#fff;cursor:pointer;margin-right:3px;font-size:11px;\">Sermones</button>
                    <a href='../serie.php?id={$reg->idserie}' target='_blank' style=\"background-color:#6c757d;padding:5px 9px;border-radius:5px;color:#fff;margin-right:3px;font-size:11px;text-decoration:none;\">Ver</a>
                    <button onclick=\"borrar_serie({$reg->idserie});\" style=\"background-color:rgb(129,2,2);padding:5px 9px;border-radius:5px;border:none;color:#fff;cursor:pointer;font-size:11px;\">Eliminar</button>
                </td>
            </tr>";
        }
        break;

    case 'get_one':
        header('Content-Type: application/json; charset=utf-8');
        $reg = $series->get_serie((int)$_GET['id']);
        echo json_encode($reg, JSON_UNESCAPED_UNICODE);
        break;

    case 'guardar':
        $id = $series->guardar_serie(
            $_POST['nombre'],
            $_POST['descripcion'] ?? '',
            $_POST['fecha_inicio'] ?? '',
            $_POST['fecha_fin'] ?? '',
            $_POST['imagen'] ?? ''
        );
        echo json_encode(['ok' => $id > 0, 'id' => $id]);
        break;

    case 'actualizar':
        $ok = $series->actualizar_serie(
            (int)$_POST['idserie'],
            $_POST['nombre'],
            $_POST['descripcion'] ?? '',
            $_POST['fecha_inicio'] ?? '',
            $_POST['fecha_fin'] ?? '',
            $_POST['imagen'] ?? '',
            (int)($_POST['estatus'] ?? 1)
        );
        echo json_encode(['ok' => (bool)$ok]);
        break;

    case 'borrar':
        $ok = $series->borrar_serie((int)$_POST['idserie']);
        echo json_encode(['ok' => (bool)$ok]);
        break;

    case 'sermones_de_serie':
        header('Content-Type: application/json; charset=utf-8');
        $result   = $series->listar_sermones_de_serie((int)$_GET['id']);
        $sermones = [];
        while ($s = $result->fetch_object()) { $sermones[] = $s; }
        echo json_encode($sermones, JSON_UNESCAPED_UNICODE);
        break;

    case 'subir_imagen':
        $ext_ok = ['jpg','jpeg','png','gif','webp'];
        if (!isset($_FILES['imagen']) || $_FILES['imagen']['error'] !== UPLOAD_ERR_OK) {
            echo json_encode(['ok' => false, 'msg' => 'No se recibió archivo.']); break;
        }
        $ext = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $ext_ok)) {
            echo json_encode(['ok' => false, 'msg' => 'Formato no permitido.']); break;
        }
        $carpeta = '../../images/series/';
        if (!file_exists($carpeta)) mkdir($carpeta, 0755, true);
        $archivo = 'serie_' . uniqid() . '.' . $ext;
        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $carpeta . $archivo)) {
            echo json_encode(['ok' => true, 'ruta' => 'images/series/' . $archivo]);
        } else {
            echo json_encode(['ok' => false, 'msg' => 'Error al guardar el archivo.']);
        }
        break;
}
?>
