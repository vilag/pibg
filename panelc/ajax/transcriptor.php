<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['nombre']) || $_SESSION['administrador'] != 1) {
    echo json_encode(['ok' => false, 'msg' => 'Sin acceso.']);
    exit;
}

$op = $_GET['op'] ?? '';

switch ($op) {
    case 'transcribir':
        transcribir();
        break;
    default:
        echo json_encode(['ok' => false, 'msg' => 'Operación no válida.']);
}

/* ============================================================
   TRANSCRIBIR
   1. Valida entradas
   2. Descarga la página del video de YouTube
   3. Extrae las pistas de subtítulos del ytInitialPlayerResponse
   4. Descarga la pista seleccionada (JSON3)
   5. Filtra por rango de tiempo y devuelve el texto
============================================================ */
function transcribir() {
    $url    = trim($_POST['url']    ?? '');
    $inicio = trim($_POST['inicio'] ?? '0');
    $fin    = trim($_POST['fin']    ?? '');
    $lang   = trim($_POST['lang']   ?? 'es');

    if (empty($url)) {
        echo json_encode(['ok' => false, 'msg' => 'La URL de YouTube es obligatoria.']);
        exit;
    }

    // Solo aceptar URLs de YouTube
    if (!preg_match('/^https?:\/\/(www\.)?(youtube\.com|youtu\.be)\//i', $url)) {
        echo json_encode(['ok' => false, 'msg' => 'La URL debe ser de YouTube.']);
        exit;
    }

    $video_id = extraer_video_id($url);
    if (!$video_id) {
        echo json_encode(['ok' => false, 'msg' => 'No se pudo extraer el ID del video. Verifica la URL.']);
        exit;
    }

    $seg_inicio = tiempo_a_segundos($inicio);
    $seg_fin    = ($fin !== '' && $fin !== null) ? tiempo_a_segundos($fin) : null;

    if ($seg_fin !== null && $seg_fin <= $seg_inicio) {
        echo json_encode(['ok' => false, 'msg' => 'El tiempo de fin debe ser mayor que el de inicio.']);
        exit;
    }

    // Obtener pistas de subtítulos
    $resultado = obtener_caption_tracks($video_id);

    if (!$resultado['ok']) {
        echo json_encode(['ok' => false, 'msg' => $resultado['msg']]);
        exit;
    }

    $tracks = $resultado['tracks'];

    if (empty($tracks)) {
        echo json_encode([
            'ok'  => false,
            'msg' => 'Este video no tiene subtítulos disponibles. YouTube debe tener activados los subtítulos automáticos o manuales en el video.'
        ]);
        exit;
    }

    // Escoger la mejor pista
    $track_url = seleccionar_track($tracks, $lang);

    if (!$track_url) {
        $disponibles = [];
        foreach ($tracks as $t) {
            $nombre = $t['name']['simpleText'] ?? $t['languageCode'];
            $tipo   = ($t['kind'] ?? '') === 'asr' ? ' (auto)' : '';
            $disponibles[] = $nombre . $tipo;
        }
        echo json_encode([
            'ok'  => false,
            'msg' => 'No hay subtítulos en el idioma seleccionado. Idiomas disponibles: ' . implode(', ', $disponibles)
        ]);
        exit;
    }

    // Obtener y parsear segmentos
    $segmentos = obtener_segmentos($track_url);

    if (empty($segmentos)) {
        echo json_encode(['ok' => false, 'msg' => 'No se pudo leer la transcripción del video.']);
        exit;
    }

    // Filtrar por rango de tiempo
    $filtrados = filtrar_segmentos($segmentos, $seg_inicio, $seg_fin);

    if (empty($filtrados)) {
        echo json_encode(['ok' => false, 'msg' => 'No hay texto en el rango de tiempo indicado.']);
        exit;
    }

    // Texto completo
    $texto = implode(' ', array_column($filtrados, 'text'));
    $texto = trim(preg_replace('/\s+/', ' ', $texto));

    // Lista de idiomas disponibles para el selector
    $idiomas = [];
    foreach ($tracks as $t) {
        $idiomas[] = [
            'code' => $t['languageCode'],
            'name' => $t['name']['simpleText'] ?? $t['languageCode'],
            'auto' => ($t['kind'] ?? '') === 'asr',
        ];
    }

    echo json_encode([
        'ok'        => true,
        'texto'     => $texto,
        'segmentos' => $filtrados,
        'idiomas'   => $idiomas,
    ]);
}

/* ============================================================
   Extraer ID del video de YouTube
============================================================ */
function extraer_video_id($url) {
    if (preg_match('/[?&]v=([A-Za-z0-9_\-]{11})/', $url, $m)) return $m[1];
    if (preg_match('/youtu\.be\/([A-Za-z0-9_\-]{11})/', $url, $m)) return $m[1];
    if (preg_match('/youtube\.com\/embed\/([A-Za-z0-9_\-]{11})/', $url, $m)) return $m[1];
    return null;
}

/* ============================================================
   Descargar página de YouTube y extraer pistas de subtítulos
   del objeto ytInitialPlayerResponse
============================================================ */
function obtener_caption_tracks($video_id) {
    $page_url = 'https://www.youtube.com/watch?v=' . urlencode($video_id);

    $ch = curl_init($page_url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_HTTPHEADER     => [
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36',
            'Accept-Language: es-MX,es;q=0.9,en;q=0.8',
            'Cookie: CONSENT=YES+cb; SOCS=CAE=',
        ],
    ]);
    $html = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);

    if (!$html) {
        return ['ok' => false, 'msg' => 'No se pudo conectar con YouTube. ' . $error];
    }

    $needle = 'ytInitialPlayerResponse=';
    $pos = strpos($html, $needle);
    if ($pos === false) {
        return ['ok' => false, 'msg' => 'No se encontró la información del video. El video puede ser privado o no estar disponible.'];
    }

    $json_str = extraer_json_objeto($html, $pos + strlen($needle));
    if (!$json_str) {
        return ['ok' => false, 'msg' => 'No se pudo parsear la respuesta de YouTube.'];
    }

    $data = json_decode($json_str, true);
    if (!$data) {
        return ['ok' => false, 'msg' => 'Error al decodificar datos de YouTube.'];
    }

    $tracks = $data['captions']['playerCaptionsTracklistRenderer']['captionTracks'] ?? [];
    return ['ok' => true, 'tracks' => $tracks];
}

/* ============================================================
   Extraer un objeto JSON completo a partir de una posición
   usando conteo de llaves (más robusto que regex)
============================================================ */
function extraer_json_objeto($str, $start) {
    $depth     = 0;
    $in_string = false;
    $escape    = false;
    $i         = $start;
    $len       = strlen($str);

    while ($i < $len) {
        $c = $str[$i];

        if ($escape) {
            $escape = false;
            $i++;
            continue;
        }
        if ($c === '\\' && $in_string) {
            $escape = true;
            $i++;
            continue;
        }
        if ($c === '"') {
            $in_string = !$in_string;
            $i++;
            continue;
        }
        if (!$in_string) {
            if ($c === '{') {
                $depth++;
            } elseif ($c === '}') {
                $depth--;
                if ($depth === 0) {
                    $i++;
                    break;
                }
            }
        }
        $i++;
    }

    if ($depth !== 0) return null;
    return substr($str, $start, $i - $start);
}

/* ============================================================
   Seleccionar la mejor pista disponible
============================================================ */
function seleccionar_track($tracks, $lang) {
    // 1. Idioma exacto, manual
    foreach ($tracks as $t) {
        if ($t['languageCode'] === $lang && ($t['kind'] ?? '') !== 'asr') {
            return $t['baseUrl'] . '&fmt=json3';
        }
    }
    // 2. Idioma exacto, auto-generado
    foreach ($tracks as $t) {
        if ($t['languageCode'] === $lang) {
            return $t['baseUrl'] . '&fmt=json3';
        }
    }
    // 3. Mismo prefijo de idioma (ej: es-MX para lang=es)
    foreach ($tracks as $t) {
        if (str_starts_with($t['languageCode'], $lang)) {
            return $t['baseUrl'] . '&fmt=json3';
        }
    }
    // 4. Primer disponible como fallback
    if (!empty($tracks)) {
        return $tracks[0]['baseUrl'] . '&fmt=json3';
    }
    return null;
}

/* ============================================================
   Descargar y parsear pista de subtítulos (formato JSON3)
============================================================ */
function obtener_segmentos($track_url) {
    $ch = curl_init($track_url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 20,
        CURLOPT_HTTPHEADER     => [
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
        ],
    ]);
    $json = curl_exec($ch);
    curl_close($ch);

    if (!$json) return [];

    $data = json_decode($json, true);
    if (!$data || !isset($data['events'])) return [];

    $segmentos = [];
    foreach ($data['events'] as $event) {
        if (!isset($event['segs'])) continue;

        $start = ($event['tStartMs'] ?? 0) / 1000;
        $dur   = ($event['dDurationMs'] ?? 0) / 1000;

        $text = '';
        foreach ($event['segs'] as $seg) {
            $text .= $seg['utf8'] ?? '';
        }

        $text = trim(str_replace(["\n", "\r"], ' ', $text));
        if ($text === '' || $text === "\xc2\xa0") continue; // ignorar solo-espacio

        $segmentos[] = [
            'start' => round($start, 2),
            'dur'   => round($dur, 2),
            'text'  => html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8'),
        ];
    }

    return $segmentos;
}

/* ============================================================
   Filtrar segmentos por rango de tiempo
============================================================ */
function filtrar_segmentos($segmentos, $inicio, $fin) {
    return array_values(array_filter($segmentos, function ($s) use ($inicio, $fin) {
        $s_end = $s['start'] + $s['dur'];
        if ($s_end < $inicio) return false;
        if ($fin !== null && $s['start'] > $fin) return false;
        return true;
    }));
}

/* ============================================================
   Convertir h:mm:ss / mm:ss / segundos a float segundos
============================================================ */
function tiempo_a_segundos($tiempo) {
    $tiempo = trim((string)$tiempo);
    if ($tiempo === '') return 0.0;
    if (is_numeric($tiempo)) return (float)$tiempo;

    $partes = array_reverse(explode(':', $tiempo));
    $seg = 0.0;
    foreach ($partes as $i => $p) {
        $seg += (float)$p * pow(60, $i);
    }
    return $seg;
}
