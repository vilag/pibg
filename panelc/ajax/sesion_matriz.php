<?php
@ini_set('display_errors', 0);
error_reporting(0);
ob_start();
session_start();
require_once "../modelos/Sesion_matriz.php";
require_once "../config/global.php";

$sm = new Sesion_matriz();

switch ($_GET['op'] ?? '') {

    /* ============================================================
       ACCESO EXTRA: verificar contraseña de la vista
    ============================================================ */
    case 'verificar_acceso':
        ob_clean();
        header('Content-Type: application/json; charset=utf-8');
        if (!isset($_SESSION['nombre'])) {
            echo json_encode(['ok' => false, 'msg' => 'Sin sesión.']);
            exit;
        }
        $clave = $_POST['clave'] ?? '';
        if (!defined('SESION_MATRIZ_PASS') || $clave === '') {
            echo json_encode(['ok' => false, 'msg' => 'Sin configuración de acceso.']);
            exit;
        }
        if (hash_equals(SESION_MATRIZ_PASS, $clave)) {
            $_SESSION['sesion_matriz_auth'] = true;
            echo json_encode(['ok' => true]);
        } else {
            echo json_encode(['ok' => false, 'msg' => 'Contraseña incorrecta.']);
        }
        exit;

    /* ============================================================
       SESIONES
    ============================================================ */
    case 'listar_sesiones':
        $rs = $sm->listar_sesiones();
        $lista = [];
        while ($row = $rs->fetch_assoc()) {
            $lista[] = $row;
        }
        echo json_encode($lista);
        break;

    case 'crear_sesion':
        $nombre      = $_POST['nombre']      ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        if ($nombre === '') { echo json_encode(['ok' => false, 'msg' => 'Nombre requerido']); break; }
        $id = $sm->crear_sesion($nombre, $descripcion);
        echo json_encode(['ok' => true, 'idsesion' => $id]);
        break;

    case 'actualizar_sesion':
        $idsesion    = $_POST['idsesion']    ?? 0;
        $nombre      = $_POST['nombre']      ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        $r = $sm->actualizar_sesion($idsesion, $nombre, $descripcion);
        echo json_encode(['ok' => (bool)$r]);
        break;

    case 'eliminar_sesion':
        $idsesion = $_POST['idsesion'] ?? 0;
        $r = $sm->eliminar_sesion($idsesion);
        echo json_encode(['ok' => (bool)$r]);
        break;

    /* ============================================================
       REGISTROS (cargar desde Excel vía JS)
    ============================================================ */
    case 'cargar_registros':
        $idsesion = (int)($_POST['idsesion'] ?? 0);
        $nombres_raw = $_POST['nombres'] ?? '';          // JSON array
        $nombres = json_decode($nombres_raw, true);
        if (!is_array($nombres) || $idsesion === 0) {
            echo json_encode(['ok' => false, 'msg' => 'Datos inválidos']);
            break;
        }
        $r = $sm->insertar_registros($idsesion, $nombres);
        echo json_encode(['ok' => (bool)$r]);
        break;

    case 'listar_registros':
        $idsesion = (int)($_GET['idsesion'] ?? 0);
        $rs = $sm->listar_registros($idsesion);
        $lista = [];
        while ($row = $rs->fetch_assoc()) {
            $lista[] = $row;
        }
        echo json_encode($lista);
        break;

    /* ============================================================
       CELDA
    ============================================================ */
    case 'actualizar_celda':
        $idregistro = $_POST['idregistro'] ?? 0;
        $col        = $_POST['col']        ?? 0;
        $valor      = $_POST['valor']      ?? 0;
        $r = $sm->actualizar_celda($idregistro, $col, $valor);
        echo json_encode(['ok' => (bool)$r]);
        break;

    default:
        http_response_code(400);
        echo json_encode(['ok' => false, 'msg' => 'Operación no reconocida']);
        break;
}
