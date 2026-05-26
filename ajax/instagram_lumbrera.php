<?php
header('Content-Type: application/json; charset=utf-8');

$config_file = __DIR__ . '/../config/instagram_config.php';
if (file_exists($config_file)) require_once $config_file;

$ig_token   = defined('IG_TOKEN')   ? IG_TOKEN   : '';
$ig_user_id = defined('IG_USER_ID') ? IG_USER_ID : '';

$cache_dir  = __DIR__ . '/../cache';
$cache_file = $cache_dir . '/ig_lumbrera.json';
$cache_ttl  = 3600; // 1 hora

// Devolver caché si está vigente
if (file_exists($cache_file) && (time() - filemtime($cache_file)) < $cache_ttl) {
    echo file_get_contents($cache_file);
    exit;
}

// Sin credenciales → array vacío (el frontend usará fotos locales)
if (!$ig_token || !$ig_user_id) {
    echo json_encode([]);
    exit;
}

// Llamar a Instagram Graph API
$api_url = "https://graph.instagram.com/v21.0/{$ig_user_id}/media"
         . "?fields=id,media_type,media_url,thumbnail_url,caption,permalink,timestamp"
         . "&limit=50"
         . "&access_token=" . urlencode($ig_token);

$ctx      = stream_context_create(['http' => ['timeout' => 10, 'ignore_errors' => true]]);
$response = @file_get_contents($api_url, false, $ctx);

if (!$response) {
    // En error de red devolver caché aunque esté vencida
    echo file_exists($cache_file) ? file_get_contents($cache_file) : json_encode([]);
    exit;
}

$data = json_decode($response, true);
if (empty($data['data'])) {
    echo json_encode([]);
    exit;
}

$photos = [];
foreach ($data['data'] as $item) {
    // Solo imágenes (no reels/videos sin thumbnail)
    if (!in_array($item['media_type'], ['IMAGE', 'CAROUSEL_ALBUM'])) continue;
    $img = $item['media_url'] ?? ($item['thumbnail_url'] ?? null);
    if (!$img) continue;

    $photos[] = [
        'id'      => $item['id'],
        'url'     => $img,
        'link'    => $item['permalink'] ?? 'https://www.instagram.com/pibg.joven/',
        'caption' => mb_substr($item['caption'] ?? '', 0, 140, 'UTF-8'),
        'date'    => substr($item['timestamp'] ?? '', 0, 10),
    ];
}

$json = json_encode($photos, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

// Guardar caché
if (!is_dir($cache_dir)) mkdir($cache_dir, 0755, true);
file_put_contents($cache_file, $json);

echo $json;
