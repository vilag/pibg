<?php
session_start();
if (!isset($_SESSION["nombre"])) { http_response_code(403); exit; }
require_once "../modelos/Modal_bienvenida.php";

$modal = new Modal_bienvenida();

switch ($_GET["op"] ?? '') {

    case 'obtener':
        header('Content-Type: application/json; charset=utf-8');
        $reg = $modal->obtener();
        echo json_encode($reg, JSON_UNESCAPED_UNICODE);
        break;

    case 'guardar':
        $habilitado    = isset($_POST['habilitado']) ? 1 : 0;
        $titulo        = $_POST['titulo']        ?? '';
        $mensaje       = $_POST['mensaje']       ?? '';
        $video_espanol = $_POST['video_espanol'] ?? '';
        $video_ingles  = $_POST['video_ingles']  ?? '';
        $video_koreano = $_POST['video_koreano'] ?? '';
        $video_frances = $_POST['video_frances'] ?? '';
        $ok = $modal->actualizar($habilitado, $titulo, $mensaje, $video_espanol, $video_ingles, $video_koreano, $video_frances);
        echo json_encode(['ok' => (bool)$ok]);
        break;
}
?>
