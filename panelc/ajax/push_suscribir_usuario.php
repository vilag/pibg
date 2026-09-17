<?php
// Igual que ../../push_suscribir.php (endpoint público) pero exige sesión de
// un usuario del panel (cualquiera, no solo administradores) y vincula la
// suscripción a esa cuenta (idusuario), marcando es_admin segun su rol real.
// Así se puede dirigir un aviso a "todos", a "cualquier admin" o a un
// usuario específico del panel — un visitante público nunca puede llegar
// aquí sin haber iniciado sesión.
session_start();
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['nombre']) || !isset($_SESSION['idusuario'])) {
    echo json_encode(['ok' => false, 'msg' => 'Sin acceso.']);
    exit;
}

require_once '../modelos/Push_suscripciones.php';

$modelo = new Push_suscripciones();
$op = $_POST['op'] ?? '';
$user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
$idusuario  = $_SESSION['idusuario'];
$es_admin   = ($_SESSION['administrador'] ?? 0) == 1 ? 1 : 0;

if ($op === 'guardar_webpush') {
    $endpoint = $_POST['endpoint'] ?? '';
    $p256dh   = $_POST['p256dh']   ?? '';
    $auth     = $_POST['auth']     ?? '';

    if ($endpoint === '' || $p256dh === '' || $auth === '') {
        echo json_encode(['ok' => false, 'msg' => 'Datos de suscripción incompletos.']);
        exit;
    }

    $modelo->guardar_webpush_usuario($endpoint, $p256dh, $auth, $user_agent, $idusuario, $es_admin);
    echo json_encode(['ok' => true]);
    exit;
}

if ($op === 'guardar_fcm') {
    $token = $_POST['token'] ?? '';

    if ($token === '') {
        echo json_encode(['ok' => false, 'msg' => 'Token vacío.']);
        exit;
    }

    $modelo->guardar_fcm_usuario($token, $user_agent, $idusuario, $es_admin);
    echo json_encode(['ok' => true]);
    exit;
}

echo json_encode(['ok' => false, 'msg' => 'Operación no reconocida.']);
