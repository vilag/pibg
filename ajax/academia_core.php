<?php
session_start();
require_once "../modelos/Academia_core.php";

header('Content-Type: application/json; charset=utf-8');

$academia = new Academia_core();
$op = $_GET['op'] ?? '';

const ACADEMIA_INSTRUMENTOS_VALIDOS = [
    'Violín', 'Piano', 'Clarinete', 'Trompeta', 'Guitarra',
    'Cello', 'Canto', 'Saxofón', 'Flauta transversal',
];

switch ($op) {

    case 'solicitar_informes':
        // Honeypot: campo oculto que solo un bot llenaría. Se responde "ok"
        // sin guardar nada ni enviar correos, para no delatar el filtro.
        if (trim($_POST['aca_web'] ?? '') !== '') {
            echo json_encode(['ok' => true]);
            break;
        }

        $nombre   = trim($_POST['nombre']   ?? '');
        $correo   = trim($_POST['correo']   ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $instrumentos_arr = isset($_POST['instrumentos']) && is_array($_POST['instrumentos'])
            ? array_values(array_intersect($_POST['instrumentos'], ACADEMIA_INSTRUMENTOS_VALIDOS))
            : [];

        if ($nombre === '' || $correo === '' || $telefono === '' || empty($instrumentos_arr)) {
            echo json_encode(['ok' => false, 'msg' => 'Completa nombre, correo, teléfono y al menos un instrumento.']);
            break;
        }
        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['ok' => false, 'msg' => 'El correo no es válido.']);
            break;
        }

        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        if ($ip !== '' && $academia->ya_solicito_recientemente($ip)) {
            echo json_encode(['ok' => false, 'msg' => 'Ya recibimos tu solicitud. Espera un momento antes de enviar otra.']);
            break;
        }

        $instrumentos_txt = implode(', ', $instrumentos_arr);
        $id = $academia->guardar_solicitud($nombre, $correo, $telefono, $instrumentos_txt, $ip);

        // Responder antes de enviar los correos: mail() puede tardar (o
        // colgarse) si el servidor de correo es lento, y no debe hacer
        // esperar al usuario por eso. Sin PHP-FPM, un flush() normal no
        // basta para que el navegador de por completada la respuesta
        // (sigue esperando el cierre de la conexión); declarar
        // Content-Length explícito sí se lo permite.
        $respuesta = json_encode(['ok' => $id > 0]);
        if (function_exists('fastcgi_finish_request')) {
            echo $respuesta;
            fastcgi_finish_request();
        } else {
            ignore_user_abort(true);
            header('Content-Length: ' . strlen($respuesta));
            header('Connection: close');
            echo $respuesta;
            if (ob_get_level() > 0) { @ob_end_flush(); }
            @flush();
        }
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_write_close();
        }

        if ($id > 0) {
            $remitente = 'Academia Coré <pibgdlar@gmail.com>';

            // Notificación al administrador
            $asunto_admin = 'Nueva solicitud de informes - Academia Coré';
            $cuerpo_admin = "Se recibió una nueva solicitud de informes desde el sitio web:\n\n"
                . "Nombre: $nombre\n"
                . "Correo: $correo\n"
                . "Teléfono: $telefono\n"
                . "Instrumentos de interés: $instrumentos_txt";
            $headers_admin = "From: $remitente\r\nReply-To: $correo\r\n";
            @mail('pibgdlar@gmail.com', $asunto_admin, $cuerpo_admin, $headers_admin);

            // Correo de agradecimiento a quien solicitó informes
            $asunto_user = 'Gracias por tu interés en Academia Coré';
            $cuerpo_user = "Hola $nombre,\n\n"
                . "Gracias por tu interés en Academia Coré, la academia de música de la "
                . "Primera Iglesia Bautista de Guadalajara.\n\n"
                . "Recibimos tu solicitud de informes para: $instrumentos_txt.\n\n"
                . "Pronto nos comunicaremos contigo.\n\n"
                . "Primera Iglesia Bautista de Guadalajara";
            $headers_user = "From: $remitente\r\n";
            @mail($correo, $asunto_user, $cuerpo_user, $headers_user);
        }
        break;

    default:
        echo json_encode(['ok' => false, 'msg' => 'Operación no reconocida.']);
}
