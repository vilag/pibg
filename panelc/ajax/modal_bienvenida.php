<?php
session_start();
if (!isset($_SESSION["nombre"])) { http_response_code(403); exit; }
require_once "../modelos/Modal_bienvenida.php";

$modal = new Modal_bienvenida();

switch ($_GET["op"] ?? '') {

    case 'listar':
        $result = $modal->listar();
        while ($reg = $result->fetch_object()) {
            $tipo_label = $reg->tiene_selector
                ? "<span style='font-size:11px;color:#555;'>Selector</span>"
                : "<span style='font-size:11px;color:#555;'>" . (
                    $reg->tipo_directo === 'video'  ? 'Video directo' :
                   ($reg->tipo_directo === 'imagen' ? 'Imagen directa' : 'Solo texto')
                  ) . "</span>";
            $badge = $reg->habilitado
                ? "<span style='background:#28a745;color:#fff;padding:3px 10px;border-radius:20px;font-size:11px;'>Activa</span>"
                : "<span style='background:#ccc;color:#555;padding:3px 10px;border-radius:20px;font-size:11px;'>Inactiva</span>";
            $btn_estado = $reg->habilitado
                ? "<button onclick=\"desactivar_mbv({$reg->id});\" style=\"background:#ffc107;padding:5px 10px;border-radius:5px;border:none;color:#333;cursor:pointer;font-size:11px;margin-right:3px;\">Desactivar</button>"
                : "<button onclick=\"activar_mbv({$reg->id});\" style=\"background:#28a745;padding:5px 10px;border-radius:5px;border:none;color:#fff;cursor:pointer;font-size:11px;margin-right:3px;\">Activar</button>";
            echo "
            <tr>
                <td>{$reg->id}</td>
                <td>" . htmlspecialchars($reg->nombre) . "</td>
                <td>" . htmlspecialchars($reg->titulo) . "</td>
                <td>{$tipo_label}</td>
                <td>{$badge}</td>
                <td>
                    <button onclick=\"editar_mbv({$reg->id});\" style=\"background:#042C49;padding:5px 10px;border-radius:5px;border:none;color:#fff;cursor:pointer;font-size:11px;margin-right:3px;\">Editar</button>
                    {$btn_estado}
                    <button onclick=\"borrar_mbv({$reg->id});\" style=\"background:rgb(129,2,2);padding:5px 10px;border-radius:5px;border:none;color:#fff;cursor:pointer;font-size:11px;\">Eliminar</button>
                </td>
            </tr>";
        }
        break;

    case 'get_one':
        header('Content-Type: application/json; charset=utf-8');
        $reg = $modal->get_uno((int)($_GET['id'] ?? 0));
        echo json_encode($reg, JSON_UNESCAPED_UNICODE);
        break;

    case 'guardar':
        $id             = (int)($_POST['id'] ?? 0);
        $nombre         = $_POST['nombre']         ?? '';
        $titulo         = $_POST['titulo']         ?? '';
        $mensaje        = $_POST['mensaje']        ?? '';
        $tiene_selector = (int)($_POST['tiene_selector'] ?? 0);
        $tipo_directo   = $_POST['tipo_directo']   ?? '';
        $url_directo    = $_POST['url_directo']    ?? '';
        $opciones_json  = $_POST['opciones']       ?? '[]';

        // Validar que sea JSON válido
        if (!json_decode($opciones_json)) $opciones_json = '[]';

        $habilitado = (int)($_POST['habilitado'] ?? 0);

        if ($id > 0) {
            $ok = $modal->actualizar_uno($id, $nombre, $titulo, $mensaje, $tiene_selector, $tipo_directo, $url_directo, $opciones_json);
            if ($ok && $habilitado) $modal->activar($id);
            echo json_encode(['ok' => (bool)$ok, 'id' => $id]);
        } else {
            $new_id = $modal->crear($nombre, $titulo, $mensaje, $tiene_selector, $tipo_directo, $url_directo, $opciones_json);
            if ($new_id > 0 && $habilitado) $modal->activar($new_id);
            echo json_encode(['ok' => $new_id > 0, 'id' => $new_id]);
        }
        break;

    case 'activar':
        echo json_encode(['ok' => (bool)$modal->activar((int)($_POST['id'] ?? 0))]);
        break;

    case 'desactivar':
        echo json_encode(['ok' => (bool)$modal->desactivar((int)($_POST['id'] ?? 0))]);
        break;

    case 'borrar':
        echo json_encode(['ok' => (bool)$modal->borrar((int)($_POST['id'] ?? 0))]);
        break;
}
?>
