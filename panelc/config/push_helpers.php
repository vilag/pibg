<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

/**
 * Envia una notificacion Web Push (iOS agregado a inicio / navegadores) a
 * una suscripcion guardada. Devuelve ['exito' => bool, 'expirada' => bool].
 */
function push_enviar_webpush($fila, $titulo, $mensaje, $url)
{
    $webPush = new WebPush([
        'VAPID' => [
            'subject'    => 'mailto:pibgdlar@gmail.com',
            'publicKey'  => VAPID_PUBLIC_KEY,
            'privateKey' => VAPID_PRIVATE_KEY,
        ],
    ]);

    $subscription = Subscription::create([
        'endpoint'        => $fila['endpoint'],
        'keys'            => ['p256dh' => $fila['p256dh'], 'auth' => $fila['auth']],
        'contentEncoding' => 'aes128gcm',
    ]);

    $payload = json_encode(['title' => $titulo, 'body' => $mensaje, 'url' => $url ?: '/']);
    $reporte = $webPush->sendOneNotification($subscription, $payload);

    return ['exito' => $reporte->isSuccess(), 'expirada' => $reporte->isSubscriptionExpired()];
}

/**
 * Obtiene un access token OAuth2 para la API de FCM v1 usando la cuenta
 * de servicio de Firebase. Devuelve null si aun no esta configurada.
 */
function push_fcm_obtener_token()
{
    if (!defined('FCM_SERVICE_ACCOUNT_PATH') || !file_exists(FCM_SERVICE_ACCOUNT_PATH)) {
        return null;
    }
    if (!defined('FCM_PROJECT_ID') || FCM_PROJECT_ID === '') {
        return null;
    }

    $credenciales = new \Google\Auth\Credentials\ServiceAccountCredentials(
        'https://www.googleapis.com/auth/firebase.messaging',
        FCM_SERVICE_ACCOUNT_PATH
    );
    $token = $credenciales->fetchAuthToken();
    return $token['access_token'] ?? null;
}

/**
 * Envia una notificacion via FCM (Android) a un token especifico.
 * Devuelve ['exito' => bool, 'expirada' => bool].
 */
function push_enviar_fcm($accessToken, $token, $titulo, $mensaje, $url)
{
    $body = [
        'message' => [
            'token' => $token,
            'notification' => ['title' => $titulo, 'body' => $mensaje],
            'webpush' => ['fcm_options' => ['link' => $url ?: '/']],
        ],
    ];

    $ch = curl_init('https://fcm.googleapis.com/v1/projects/' . FCM_PROJECT_ID . '/messages:send');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => json_encode($body),
        CURLOPT_HTTPHEADER     => [
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json',
        ],
        CURLOPT_TIMEOUT => 15,
    ]);
    $respuesta = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $expirada = in_array($http_code, [400, 404], true) && stripos((string)$respuesta, 'UNREGISTERED') !== false;
    return ['exito' => $http_code === 200, 'expirada' => $expirada];
}

/**
 * Envia una notificacion push a todos los suscriptores activos (WebPush para
 * iOS/navegador y FCM para Android), desactiva las suscripciones expiradas y
 * registra el envio en el historial. Devuelve ['total' => int, 'exitosos' => int].
 */
function push_notificar_suscriptores($titulo, $mensaje, $url = '')
{
    require_once __DIR__ . '/../modelos/Push_suscripciones.php';
    $modelo = new Push_suscripciones();

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

    return ['total' => $total, 'exitosos' => $exitosos];
}
