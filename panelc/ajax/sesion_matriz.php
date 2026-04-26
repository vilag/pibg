<?php
@ini_set('display_errors', 0);
error_reporting(0);
session_start();

ob_start();
session_start();
require_once "../modelos/Sesion_matriz.php";
require_once "../config/global.php";


$sm = new Sesion_matriz();

switch ($_GET['op'] ?? '') {

    case 'guardar_matriz_manual':
        $idsesion = (int)($_POST['idsesion'] ?? 0);
        $nombres = isset($_POST['nombres']) ? json_decode($_POST['nombres'], true) : [];
        $columnas = isset($_POST['columnas']) ? (int)$_POST['columnas'] : 0;
        $digitos_fila = isset($_POST['digitos_fila']) ? (int)$_POST['digitos_fila'] : 3;
        $digitos_col = isset($_POST['digitos_col']) ? (int)$_POST['digitos_col'] : 2;
        if ($idsesion === 0 || !is_array($nombres) || $columnas < 1 || $columnas > 1000) {
            echo json_encode(['ok' => false, 'msg' => 'Datos inválidos']);
            break;
        }
        // Generar estructura JSON: filas, columnas, checks
        $matriz = [
            'filas' => $nombres,
            'columnas' => [],
            'checks' => []
        ];
        for ($i = 1; $i <= $columnas; $i++) {
            $matriz['columnas'][] = str_pad($i, $digitos_col, '0', STR_PAD_LEFT);
        }
        foreach ($nombres as $nombre) {
            $matriz['checks'][] = array_fill(0, $columnas, 0);
        }
        $matriz_json = json_encode($matriz);
        $ok = $sm->guardar_matriz_json($idsesion, $matriz_json);
        echo json_encode(['ok' => (bool)$ok]);
        break;

    case 'obtener_columnas':
        $idsesion = (int)($_GET['idsesion'] ?? 0);
        $row = $sm->obtener_sesion($idsesion);
        $columnas = isset($row['columnas']) ? (int)$row['columnas'] : 52;
        echo json_encode(['columnas' => $columnas]);
        break;

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
        if ($rs === false) {
            die(json_encode(['ok'=>false, 'msg'=>'Error SQL: ' . ($sm->listar_sesiones()->error ?? 'desconocido')]));
        }
        $lista = [];
        while ($row = $rs->fetch_assoc()) {
            $lista[] = $row;
        }
        echo json_encode($lista);
        break;

    case 'crear_sesion':
        $nombre      = $_POST['nombre']      ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        $columnas    = isset($_POST['columnas']) ? (int)$_POST['columnas'] : 52;
        if ($nombre === '') { echo json_encode(['ok' => false, 'msg' => 'Nombre requerido']); break; }
        $id = $sm->crear_sesion($nombre, $descripcion, $columnas);
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

    // Nuevo endpoint para obtener la matriz JSON de una sesión
    case 'obtener_matriz':
        $idsesion = (int)($_GET['idsesion'] ?? 0);
        if ($idsesion === 0) {
            echo json_encode(['ok' => false, 'msg' => 'ID inválido']);
            break;
        }
        $matriz_json = $sm->obtener_matriz_json($idsesion);
        echo $matriz_json ? $matriz_json : json_encode(['ok' => false, 'msg' => 'No hay matriz']);
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
