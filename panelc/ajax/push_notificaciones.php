<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['nombre']) || $_SESSION['administrador'] != 1) {
    echo json_encode(['ok' => false, 'msg' => 'Sin acceso.']);
    exit;
}

require_once '../config/global.php';
require_once '../modelos/Push_suscripciones.php';
require_once '../modelos/Push_eventos.php';
require_once '../modelos/Usuario.php';
require_once '../config/push_helpers.php';

$modelo = new Push_suscripciones();
$modelo_eventos = new Push_eventos();
$modelo_usuario = new Usuario();
$op = $_GET['op'] ?? '';

switch ($op) {

    case 'eventos_listar':
        echo json_encode(['ok' => true, 'eventos' => $modelo_eventos->listar()]);
        break;

    case 'usuarios_listar':
        echo json_encode(['ok' => true, 'usuarios' => $modelo_usuario->listar_activos()]);
        break;

    case 'eventos_guardar':
        $clave             = $_POST['clave']             ?? '';
        $activo            = $_POST['activo']             ?? 0;
        $destino           = $_POST['destino']            ?? 'todos';
        $idusuario_destino = $_POST['idusuario_destino']  ?? null;
        $titulo            = trim($_POST['titulo']  ?? '');
        $mensaje           = trim($_POST['mensaje'] ?? '');

        if ($clave === '' || $titulo === '' || $mensaje === '') {
            echo json_encode(['ok' => false, 'msg' => 'Faltan datos del evento.']);
            break;
        }
        if (!$modelo_eventos->obtener($clave)) {
            echo json_encode(['ok' => false, 'msg' => 'Evento no reconocido.']);
            break;
        }
        if ($destino === 'usuario' && !$idusuario_destino) {
            echo json_encode(['ok' => false, 'msg' => 'Elige a qué usuario se le enviará.']);
            break;
        }

        $modelo_eventos->guardar($clave, $activo, $destino, $idusuario_destino, $titulo, $mensaje);
        echo json_encode(['ok' => true]);
        break;

    case 'contar':
        echo json_encode(['ok' => true, 'conteos' => $modelo->contar_activas()]);
        break;

    case 'historial':
        echo json_encode(['ok' => true, 'envios' => $modelo->listar_envios(20)]);
        break;

    case 'suscripciones':
        echo json_encode(['ok' => true, 'suscripciones' => $modelo->listar_todas(200)]);
        break;

    case 'enviar':
        $titulo  = trim($_POST['titulo']  ?? '');
        $mensaje = trim($_POST['mensaje'] ?? '');
        $url     = trim($_POST['url']     ?? '');

        if ($titulo === '' || $mensaje === '') {
            echo json_encode(['ok' => false, 'msg' => 'Título y mensaje son obligatorios.']);
            break;
        }

        $resultado = push_notificar_suscriptores($titulo, $mensaje, $url);

        $avisos = [];
        if (!(defined('FCM_PROJECT_ID') && FCM_PROJECT_ID !== '')) {
            $avisos[] = 'Firebase (Android) aún no está configurado — solo se envió a suscriptores de iOS/navegador.';
        }

        echo json_encode(['ok' => true, 'total' => $resultado['total'], 'exitosos' => $resultado['exitosos'], 'avisos' => $avisos]);
        break;

    default:
        echo json_encode(['ok' => false, 'msg' => 'Operación no reconocida.']);
}
