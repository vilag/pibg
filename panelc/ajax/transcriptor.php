<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['nombre']) || $_SESSION['administrador'] != 1) {
    echo json_encode(['ok' => false, 'msg' => 'Sin acceso.']);
    exit;
}

require_once '../config/global.php';
require_once '../config/Conexion.php';

$op = $_GET['op'] ?? '';

switch ($op) {
    case 'transcribir':
        transcribir();
        break;
    case 'guardar':
        guardar_transcripcion_db();
        break;
    case 'listar':
        listar_transcripciones_db();
        break;
    case 'ver':
        ver_transcripcion_db();
        break;
    case 'eliminar':
        eliminar_transcripcion_db();
        break;
    default:
        echo json_encode(['ok' => false, 'msg' => 'Operación no válida.']);
}

/* ============================================================
   TRANSCRIBIR
   Recibe el archivo subido y lo envía a Groq Whisper (gratis)
============================================================ */
function transcribir() {

    // Validar API key
    if (!defined('GROQ_API_KEY') || strpos(GROQ_API_KEY, 'gsk_XXXX') !== false) {
        echo json_encode(['ok' => false, 'msg' => 'Configura tu API key de Groq en panelc/config/global.php — Obtén una gratis en https://console.groq.com/keys']);
        exit;
    }

    // Validar que llegó el archivo
    if (!isset($_FILES['archivo']) || $_FILES['archivo']['error'] !== UPLOAD_ERR_OK) {
        $errores = [
            UPLOAD_ERR_INI_SIZE   => 'El archivo supera el límite del servidor (upload_max_filesize).',
            UPLOAD_ERR_FORM_SIZE  => 'El archivo supera el límite del formulario.',
            UPLOAD_ERR_PARTIAL    => 'El archivo se subió parcialmente. Intenta de nuevo.',
            UPLOAD_ERR_NO_FILE    => 'No se seleccionó ningún archivo.',
            UPLOAD_ERR_NO_TMP_DIR => 'Error interno: no hay directorio temporal.',
            UPLOAD_ERR_CANT_WRITE => 'Error interno: no se pudo escribir en disco.',
        ];
        $code = $_FILES['archivo']['error'] ?? UPLOAD_ERR_NO_FILE;
        $msg  = $errores[$code] ?? 'Error al recibir el archivo.';
        echo json_encode(['ok' => false, 'msg' => $msg]);
        exit;
    }

    $tmp      = $_FILES['archivo']['tmp_name'];
    $nombre   = $_FILES['archivo']['name'];
    $tamano   = $_FILES['archivo']['size'];
    $mime     = mime_content_type($tmp);

    // Extensión del archivo original
    $ext = strtolower(pathinfo($nombre, PATHINFO_EXTENSION));

    // Formatos aceptados por Whisper
    $formatos_ok = ['flac', 'm4a', 'mp3', 'mp4', 'mpeg', 'mpga', 'ogg', 'opus', 'wav', 'webm', 'mov', 'avi', 'mkv'];

    if (!in_array($ext, $formatos_ok)) {
        echo json_encode(['ok' => false, 'msg' => 'Formato no soportado. Usa: ' . implode(', ', $formatos_ok)]);
        exit;
    }

    // Límite de 25 MB (la compresión se hace en el navegador antes de subir)
    if ($tamano > 25 * 1024 * 1024) {
        echo json_encode(['ok' => false, 'msg' => 'El archivo supera 25 MB. Algo falló en la compresión del navegador. Intenta con un archivo de audio más corto.']);
        exit;
    }

    $lang      = trim($_POST['lang'] ?? 'es');
    $timestamps = ($_POST['timestamps'] ?? '0') === '1';

    // Llamar a Whisper
    $resultado = llamar_whisper($tmp, $nombre, $lang, $timestamps);
    echo json_encode($resultado);
}

/* ============================================================
   Llamada a la API de Groq Whisper (gratuita)
   Endpoint compatible con OpenAI — mismo formato de respuesta
============================================================ */
function llamar_whisper($tmp_path, $nombre_original, $lang, $timestamps) {
    $api_key = GROQ_API_KEY;

    $response_format = $timestamps ? 'verbose_json' : 'json';

    $post = [
        'file'            => new CURLFile($tmp_path, mime_content_type($tmp_path), $nombre_original),
        'model'           => 'whisper-large-v3-turbo',
        'response_format' => $response_format,
    ];

    if (!empty($lang)) {
        $post['language'] = $lang;
    }

    $ch = curl_init('https://api.groq.com/openai/v1/audio/transcriptions');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $post,
        CURLOPT_HTTPHEADER     => [
            'Authorization: Bearer ' . $api_key,
        ],
        CURLOPT_TIMEOUT        => 300,
    ]);

    $response  = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_err  = curl_error($ch);
    curl_close($ch);

    if ($curl_err) {
        return ['ok' => false, 'msg' => 'Error de conexión con Groq: ' . $curl_err];
    }

    $data = json_decode($response, true);

    if ($http_code !== 200) {
        $msg = $data['error']['message'] ?? $response;
        return ['ok' => false, 'msg' => 'Error de Groq (' . $http_code . '): ' . $msg];
    }

    $resultado = [
        'ok'    => true,
        'texto' => $data['text'] ?? '',
    ];

    if ($timestamps && isset($data['segments'])) {
        $resultado['segmentos'] = array_map(function ($s) {
            return [
                'start' => round($s['start'], 2),
                'end'   => round($s['end'], 2),
                'text'  => trim($s['text']),
            ];
        }, $data['segments']);
        $resultado['idioma'] = $data['language'] ?? $lang;
    }

    return $resultado;
}

/* ============================================================
   GUARDAR TRANSCRIPCIÓN EN BASE DE DATOS
============================================================ */
function guardar_transcripcion_db() {
    global $conexion;

    $nombre = trim($_POST['nombre_archivo'] ?? '');
    $idioma = trim($_POST['idioma'] ?? 'es');
    $texto  = trim($_POST['texto'] ?? '');

    if (!$nombre || !$texto) {
        echo json_encode(['ok' => false, 'msg' => 'Datos incompletos.']);
        exit;
    }

    // Contar palabras (compatible con UTF-8 / español)
    $num_palabras = preg_match_all('/\S+/u', strip_tags($texto), $m);
    if ($num_palabras === false) $num_palabras = 0;

    $stmt = $conexion->prepare(
        'INSERT INTO transcripcion (nombre_archivo, idioma, texto, num_palabras) VALUES (?, ?, ?, ?)'
    );
    $stmt->bind_param('sssi', $nombre, $idioma, $texto, $num_palabras);

    if ($stmt->execute()) {
        echo json_encode(['ok' => true, 'id' => $conexion->insert_id]);
    } else {
        echo json_encode(['ok' => false, 'msg' => 'Error al guardar: ' . $stmt->error]);
    }
    $stmt->close();
}

/* ============================================================
   LISTAR TRANSCRIPCIONES
============================================================ */
function listar_transcripciones_db() {
    global $conexion;

    $rows   = [];
    $result = $conexion->query(
        'SELECT id_transcripcion, nombre_archivo, idioma, num_palabras, fecha_creacion
         FROM transcripcion ORDER BY fecha_creacion DESC LIMIT 100'
    );

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
    }

    echo json_encode(['ok' => true, 'datos' => $rows]);
}

/* ============================================================
   VER TEXTO COMPLETO DE UNA TRANSCRIPCIÓN
============================================================ */
function ver_transcripcion_db() {
    global $conexion;

    $id = intval($_GET['id'] ?? 0);
    if (!$id) {
        echo json_encode(['ok' => false, 'msg' => 'ID inválido.']);
        exit;
    }

    $stmt = $conexion->prepare('SELECT texto, nombre_archivo FROM transcripcion WHERE id_transcripcion = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->bind_result($texto, $nombre);

    if ($stmt->fetch()) {
        echo json_encode(['ok' => true, 'texto' => $texto, 'nombre' => $nombre]);
    } else {
        echo json_encode(['ok' => false, 'msg' => 'Transcripción no encontrada.']);
    }
    $stmt->close();
}

/* ============================================================
   ELIMINAR TRANSCRIPCIÓN
============================================================ */
function eliminar_transcripcion_db() {
    global $conexion;

    $id = intval($_POST['id'] ?? 0);
    if (!$id) {
        echo json_encode(['ok' => false, 'msg' => 'ID inválido.']);
        exit;
    }

    $stmt = $conexion->prepare('DELETE FROM transcripcion WHERE id_transcripcion = ?');
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        echo json_encode(['ok' => true]);
    } else {
        echo json_encode(['ok' => false, 'msg' => 'Error: ' . $stmt->error]);
    }
    $stmt->close();
}
