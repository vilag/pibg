<?php
// Igual que ../../push_suscribir.php (endpoint público) pero exige sesión de
// administrador y marca la suscripción como es_admin=1. Así el dispositivo
// del admin puede recibir avisos internos (ej. nuevas peticiones de oración)
// sin que un visitante público pueda auto-asignarse ese rol.
session_start();
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['nombre']) || $_SESSION['administrador'] != 1) {
    echo json_encode(['ok' => false, 'msg' => 'Sin acceso.']);
    exit;
}

require_once '../modelos/Push_suscripciones.php';

$modelo = new Push_suscripciones();
$op = $_POST['op'] ?? '';
$user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';

if ($op === 'guardar_webpush') {
    $endpoint = $_POST['endpoint'] ?? '';
    $p256dh   = $_POST['p256dh']   ?? '';
    $auth     = $_POST['auth']     ?? '';

    if ($endpoint === '' || $p256dh === '' || $auth === '') {
        echo json_encode(['ok' => false, 'msg' => 'Datos de suscripción incompletos.']);
        exit;
    }

    $modelo->guardar_webpush_admin($endpoint, $p256dh, $auth, $user_agent);
    echo json_encode(['ok' => true]);
    exit;
}

if ($op === 'guardar_fcm') {
    $token = $_POST['token'] ?? '';

    if ($token === '') {
        echo json_encode(['ok' => false, 'msg' => 'Token vacío.']);
        exit;
    }

    $modelo->guardar_fcm_admin($token, $user_agent);
    echo json_encode(['ok' => true]);
    exit;
}

echo json_encode(['ok' => false, 'msg' => 'Operación no reconocida.']);
