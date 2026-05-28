<?php
session_start();
if (!isset($_SESSION["nombre"])) { http_response_code(403); exit; }
require_once "../modelos/Predicaciones.php";

$pred = new Predicaciones();

switch ($_GET["op"] ?? '') {

    case 'listar':
        $cats = [];
        $rc = $pred->listar_categorias();
        while ($c = $rc->fetch_array()) { $cats[$c[0]] = $c['nombre']; }

        $series = [];
        $rs = $pred->listar_series_activas();
        while ($s = $rs->fetch_object()) { $series[$s->idserie] = $s->nombre; }

        $result = $pred->listar_sermones();
        while ($reg = $result->fetch_object()) {
            $cat_nombre   = $cats[$reg->categoria] ?? '—';
            $serie_nombre = ($reg->serie_id && isset($series[$reg->serie_id])) ? htmlspecialchars($series[$reg->serie_id]) : '—';
            $img_html = $reg->imagen
                ? "<img src='../" . htmlspecialchars($reg->imagen) . "' onerror=\"this.style.display='none'\" style='width:50px;height:50px;object-fit:cover;border-radius:4px;'>"
                : "<span style='color:#aaa;font-size:11px;'>Sin imagen</span>";
            echo "
            <tr>
                <td>{$reg->idsermones}</td>
                <td>{$img_html}</td>
                <td>" . htmlspecialchars($reg->nom_sermon) . "</td>
                <td>" . htmlspecialchars($reg->predicador) . "</td>
                <td>" . htmlspecialchars($reg->actividad) . "</td>
                <td>" . htmlspecialchars($reg->fecha_eti) . "</td>
                <td>{$cat_nombre}</td>
                <td>{$serie_nombre}</td>
                <td>
                    <button onclick=\"editar_sermon({$reg->idsermones});\" style=\"background-color:#042C49;padding:5px 9px;border-radius:5px;border:none;color:#fff;cursor:pointer;margin-right:3px;font-size:11px;\">Editar</button>
                    <a href='../blog.php?id={$reg->idsermones}' target='_blank' style=\"background-color:#28a745;padding:5px 9px;border-radius:5px;color:#fff;margin-right:3px;font-size:11px;text-decoration:none;\">Ver</a>
                    <button onclick=\"borrar_sermon({$reg->idsermones});\" style=\"background-color:rgb(129,2,2);padding:5px 9px;border-radius:5px;border:none;color:#fff;cursor:pointer;font-size:11px;\">Eliminar</button>
                </td>
            </tr>";
        }
        break;

    case 'get_one':
        header('Content-Type: application/json; charset=utf-8');
        $reg = $pred->get_sermon((int)$_GET['id']);
        echo json_encode($reg, JSON_UNESCAPED_UNICODE);
        break;

    case 'guardar':
        $id = $pred->guardar_sermon(
            $_POST['nom_sermon'],
            $_POST['fecha_eti'],
            $_POST['predicador'],
            $_POST['actividad'],
            (int)$_POST['categoria'],
            (int)($_POST['serie_id'] ?? 0),
            (int)($_POST['orden_serie'] ?? 0),
            $_POST['imagen'] ?? '',
            $_POST['predicacion']
        );
        echo json_encode(['ok' => $id > 0, 'id' => $id]);
        break;

    case 'actualizar':
        $ok = $pred->actualizar_sermon(
            (int)$_POST['idsermones'],
            $_POST['nom_sermon'],
            $_POST['fecha_eti'],
            $_POST['predicador'],
            $_POST['actividad'],
            (int)$_POST['categoria'],
            (int)($_POST['serie_id'] ?? 0),
            (int)($_POST['orden_serie'] ?? 0),
            $_POST['imagen'] ?? '',
            $_POST['predicacion']
        );
        echo json_encode(['ok' => (bool)$ok]);
        break;

    case 'borrar':
        $ok = $pred->borrar_sermon((int)$_POST['idsermones']);
        echo json_encode(['ok' => (bool)$ok]);
        break;

    case 'listar_categorias':
        header('Content-Type: application/json; charset=utf-8');
        $result = $pred->listar_categorias();
        $cats = [];
        while ($c = $result->fetch_array()) { $cats[] = ['id' => $c[0], 'nombre' => $c['nombre']]; }
        echo json_encode($cats, JSON_UNESCAPED_UNICODE);
        break;

    case 'guardar_categoria':
        $id = $pred->guardar_categoria($_POST['nombre']);
        echo json_encode(['ok' => $id > 0, 'id' => $id, 'nombre' => $_POST['nombre']]);
        break;

    case 'borrar_categoria':
        $ok = $pred->borrar_categoria((int)$_POST['idcat']);
        echo json_encode(['ok' => (bool)$ok]);
        break;

    case 'listar_series':
        header('Content-Type: application/json; charset=utf-8');
        $result = $pred->listar_series_activas();
        $series = [];
        while ($s = $result->fetch_object()) { $series[] = ['id' => $s->idserie, 'nombre' => $s->nombre]; }
        echo json_encode($series, JSON_UNESCAPED_UNICODE);
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
        $carpeta = '../../images/predicaciones/portadas/';
        if (!file_exists($carpeta)) mkdir($carpeta, 0755, true);
        $archivo = 'pred_' . uniqid() . '.' . $ext;
        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $carpeta . $archivo)) {
            echo json_encode(['ok' => true, 'ruta' => 'images/predicaciones/portadas/' . $archivo]);
        } else {
            echo json_encode(['ok' => false, 'msg' => 'Error al guardar el archivo.']);
        }
        break;
}
?>
