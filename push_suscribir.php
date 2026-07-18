<?php
ob_start();
require_once "config/Conexion.php";
ob_end_clean();

header('Content-Type: application/json; charset=utf-8');

global $conexion;
$op = $_POST['op'] ?? '';
$user_agent = $conexion->real_escape_string(substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255));

if ($op === 'guardar_webpush') {
    $endpoint = $conexion->real_escape_string($_POST['endpoint'] ?? '');
    $p256dh   = $conexion->real_escape_string($_POST['p256dh']   ?? '');
    $auth     = $conexion->real_escape_string($_POST['auth']     ?? '');

    if ($endpoint === '' || $p256dh === '' || $auth === '') {
        echo json_encode(['ok' => false, 'msg' => 'Datos de suscripción incompletos.']);
        exit;
    }

    $sql = "INSERT INTO push_suscripciones (tipo, endpoint, p256dh, auth, user_agent, activo)
            VALUES ('webpush', '$endpoint', '$p256dh', '$auth', '$user_agent', 1)
            ON DUPLICATE KEY UPDATE p256dh='$p256dh', auth='$auth', user_agent='$user_agent', activo=1";
    ejecutarConsulta($sql);
    echo json_encode(['ok' => true]);
    exit;
}

if ($op === 'guardar_fcm') {
    $token = $conexion->real_escape_string($_POST['token'] ?? '');

    if ($token === '') {
        echo json_encode(['ok' => false, 'msg' => 'Token vacío.']);
        exit;
    }

    $sql = "INSERT INTO push_suscripciones (tipo, fcm_token, user_agent, activo)
            VALUES ('fcm', '$token', '$user_agent', 1)
            ON DUPLICATE KEY UPDATE user_agent='$user_agent', activo=1";
    ejecutarConsulta($sql);
    echo json_encode(['ok' => true]);
    exit;
}

echo json_encode(['ok' => false, 'msg' => 'Operación no reconocida.']);
