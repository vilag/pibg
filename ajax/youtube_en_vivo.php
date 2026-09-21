<?php
header('Content-Type: application/json; charset=utf-8');

$config_file = __DIR__ . '/../config/youtube_config.php';
if (file_exists($config_file)) require_once $config_file;

$channel_id = defined('YOUTUBE_CHANNEL_ID') ? YOUTUBE_CHANNEL_ID : '';

$cache_dir  = __DIR__ . '/../cache';
$cache_file = $cache_dir . '/yt_en_vivo.json';
$cache_ttl  = 90; // segundos — barato de sobra para no golpear a YouTube en cada visita

// Devolver caché si está vigente
if (file_exists($cache_file) && (time() - filemtime($cache_file)) < $cache_ttl) {
    echo file_get_contents($cache_file);
    exit;
}

if (!$channel_id) {
    echo json_encode(['en_vivo' => false]);
    exit;
}

// Truco sin API key ni cuota: si el canal está transmitiendo en vivo, la URL
// "/channel/{id}/live" redirige al video en curso y el oEmbed responde 200;
// si no hay transmisión, responde 404. Evita usar la YouTube Data API
// (search.list cuesta 100 unidades por consulta — inviable para sondear
// cada visita del sitio).
$oembed_url = 'https://www.youtube.com/oembed?url='
            . urlencode("https://www.youtube.com/channel/{$channel_id}/live")
            . '&format=json';

$ctx = stream_context_create(['http' => ['timeout' => 6, 'ignore_errors' => true]]);
$response = @file_get_contents($oembed_url, false, $ctx);

if ($response === false) {
    // Fallo de red: devolver caché vieja si existe, o "no en vivo" por defecto
    echo file_exists($cache_file) ? file_get_contents($cache_file) : json_encode(['en_vivo' => false]);
    exit;
}

$en_vivo = false;
if (isset($http_response_header)) {
    foreach ($http_response_header as $header) {
        if (preg_match('#^HTTP/\S+\s+(\d+)#', $header, $m)) {
            $en_vivo = ((int)$m[1] === 200);
            break;
        }
    }
}

$json = json_encode(['en_vivo' => $en_vivo, 'channel_id' => $channel_id]);

if (!is_dir($cache_dir)) mkdir($cache_dir, 0755, true);
file_put_contents($cache_file, $json);

echo $json;
