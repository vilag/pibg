<?php
session_start();
if (!isset($_SESSION["nombre"])) { http_response_code(403); exit; }
require_once "../modelos/Secciones_index.php";

$m  = new Secciones_index();
$op = $_REQUEST['op'] ?? '';

header('Content-Type: application/json; charset=utf-8');

switch ($op) {

    /* ── VISIBILIDAD NATIVAS ──────────────────────────────── */

    case 'listar_vis':
        $r   = $m->listar_visibilidad();
        $arr = [];
        while ($row = $r->fetch_assoc()) $arr[] = $row;
        echo json_encode($arr);
        break;

    case 'toggle_vis':
        $clave  = $_POST['clave']  ?? '';
        $activo = (int)($_POST['activo'] ?? 0);
        if (!$clave) { echo json_encode(['ok'=>false,'msg'=>'Clave vacía']); break; }
        $m->toggle_visibilidad($clave, $activo);
        echo json_encode(['ok'=>true]);
        break;

    /* ── SECCIONES PERSONALIZADAS ─────────────────────────── */

    case 'listar':
        $r   = $m->listar();
        $arr = [];
        while ($row = $r->fetch_assoc()) $arr[] = $row;
        echo json_encode($arr);
        break;

    case 'get_uno':
        $id  = (int)($_GET['id'] ?? 0);
        $row = $m->get_uno($id);
        echo json_encode($row ?: null);
        break;

    case 'crear':
        $id = $m->crear($_POST);
        echo json_encode(['ok' => (bool)$id, 'id' => $id]);
        break;

    case 'actualizar':
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) { echo json_encode(['ok'=>false,'msg'=>'ID inválido']); break; }
        $ok = $m->actualizar($id, $_POST);
        echo json_encode(['ok' => (bool)$ok]);
        break;

    case 'toggle_activo':
        $id     = (int)($_POST['id']     ?? 0);
        $activo = (int)($_POST['activo'] ?? 0);
        $ok = $m->toggle_activo($id, $activo);
        echo json_encode(['ok' => (bool)$ok]);
        break;

    case 'eliminar':
        $id = (int)($_POST['id'] ?? 0);
        $ok = $m->eliminar($id);
        echo json_encode(['ok' => (bool)$ok]);
        break;

    /* ── SUBIR IMAGEN ─────────────────────────────────────── */

    case 'subir_imagen':
        if (empty($_FILES['imagen'])) {
            echo json_encode(['ok'=>false,'msg'=>'Sin archivo']); break;
        }
        $file = $_FILES['imagen'];
        $ext  = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allow= ['jpg','jpeg','png','webp','gif'];
        if (!in_array($ext, $allow)) {
            echo json_encode(['ok'=>false,'msg'=>'Tipo de archivo no permitido']); break;
        }
        $dir = '../../images/secciones/';
        if (!is_dir($dir)) mkdir($dir, 0755, true);
        $fname = uniqid('sec_') . '.' . $ext;
        if (move_uploaded_file($file['tmp_name'], $dir . $fname)) {
            echo json_encode(['ok'=>true,'url'=>'images/secciones/'.$fname]);
        } else {
            echo json_encode(['ok'=>false,'msg'=>'Error al guardar']);
        }
        break;

    default:
        echo json_encode(['ok'=>false,'msg'=>'Operación desconocida']);
}
