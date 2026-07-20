<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['nombre']) || $_SESSION['administrador'] != 1) {
    echo json_encode(['ok' => false, 'msg' => 'Sin acceso.']);
    exit;
}

require_once '../config/global.php';
require_once '../modelos/Push_suscripciones.php';
require_once '../config/push_helpers.php';

$modelo = new Push_suscripciones();
$op = $_GET['op'] ?? '';

switch ($op) {

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

        $suscripciones = array_merge(
            $modelo->listar_activas('webpush'),
            $modelo->listar_activas('fcm')
        );

        $total = count($suscripciones);
        $exitosos = 0;
        $accessTokenFcm = null;
        $fcm_disponible = defined('FCM_PROJECT_ID') && FCM_PROJECT_ID !== '';
        if ($fcm_disponible) {
            $accessTokenFcm = push_fcm_obtener_token();
        }

        foreach ($suscripciones as $fila) {
            if ($fila['tipo'] === 'webpush') {
                $resultado = push_enviar_webpush($fila, $titulo, $mensaje, $url);
            } else {
                if (!$accessTokenFcm) { continue; }
                $resultado = push_enviar_fcm($accessTokenFcm, $fila['fcm_token'], $titulo, $mensaje, $url);
            }

            if ($resultado['exito']) {
                $exitosos++;
            } elseif ($resultado['expirada']) {
                $modelo->desactivar($fila['id']);
            }
        }

        $modelo->registrar_envio($titulo, $mensaje, $url ?: null, $total, $exitosos);

        $avisos = [];
        if (!$fcm_disponible) {
            $avisos[] = 'Firebase (Android) aún no está configurado — solo se envió a suscriptores de iOS/navegador.';
        }

        echo json_encode(['ok' => true, 'total' => $total, 'exitosos' => $exitosos, 'avisos' => $avisos]);
        break;

    default:
        echo json_encode(['ok' => false, 'msg' => 'Operación no reconocida.']);
}
