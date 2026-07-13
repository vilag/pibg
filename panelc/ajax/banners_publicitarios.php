<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['nombre']) || $_SESSION['administrador'] != 1) {
    echo json_encode(['ok' => false, 'msg' => 'Sin acceso.']);
    exit;
}

require_once '../config/global.php';
require_once '../modelos/Banners_publicitarios.php';

$modelo = new Banners_publicitarios();
$op = $_GET['op'] ?? '';

switch ($op) {

    case 'listar':
        $result = $modelo->listar();
        $filas = [];
        if ($result) {
            while ($reg = $result->fetch_assoc()) {
                $filas[] = $reg;
            }
        }
        echo json_encode(['ok' => true, 'datos' => $filas]);
        break;

    case 'get_one':
        $reg = $modelo->get_uno((int)($_GET['id'] ?? 0));
        if ($reg) {
            echo json_encode(['ok' => true, 'datos' => $reg]);
        } else {
            echo json_encode(['ok' => false, 'msg' => 'Banner no encontrado.']);
        }
        break;

    case 'guardar':
        $id                  = (int)($_POST['id'] ?? 0);
        $nombre              = trim($_POST['nombre'] ?? '');
        $ancho_px            = (int)($_POST['ancho_px'] ?? 0);
        $alto_px             = (int)($_POST['alto_px'] ?? 0);
        $unidad_original     = $_POST['unidad_original'] ?? 'px';
        $ancho_original      = $_POST['ancho_original'] ?? null;
        $alto_original       = $_POST['alto_original'] ?? null;
        $diseno_json         = $_POST['diseno_json'] ?? '';
        $imagen_final_base64 = $_POST['imagen_final_base64'] ?? '';

        if (!$nombre || $ancho_px <= 0 || $alto_px <= 0 || !$diseno_json) {
            echo json_encode(['ok' => false, 'msg' => 'Datos incompletos: falta nombre, tamaño o diseño.']);
            exit;
        }

        if (json_decode($diseno_json) === null) {
            echo json_encode(['ok' => false, 'msg' => 'El diseño del banner no es un JSON válido.']);
            exit;
        }

        if ($id > 0) {
            $ok = $modelo->actualizar_uno($id, $nombre, $ancho_px, $alto_px, $unidad_original, $ancho_original, $alto_original, $diseno_json, $imagen_final_base64);
            echo json_encode(['ok' => (bool)$ok, 'id' => $id]);
        } else {
            $new_id = $modelo->crear($nombre, $ancho_px, $alto_px, $unidad_original, $ancho_original, $alto_original, $diseno_json, $imagen_final_base64);
            echo json_encode(['ok' => $new_id > 0, 'id' => $new_id]);
        }
        break;

    case 'borrar':
        echo json_encode(['ok' => (bool)$modelo->borrar((int)($_POST['id'] ?? 0))]);
        break;

    case 'ajustar_ia':
        ajustar_con_ia();
        break;

    default:
        echo json_encode(['ok' => false, 'msg' => 'Operación no válida.']);
}

/* ============================================================
   AJUSTE DE DISEÑO CON IA (Groq — mismo API key que Whisper)
   Interpreta una instrucción en lenguaje natural y devuelve
   una lista de operaciones para aplicar a los objetos del canvas.
============================================================ */
function ajustar_con_ia()
{
    if (!defined('GROQ_API_KEY') || strpos(GROQ_API_KEY, 'gsk_XXXX') !== false) {
        echo json_encode(['ok' => false, 'msg' => 'Configura tu API key de Groq en panelc/config/secrets.php — Obtén una gratis en https://console.groq.com/keys']);
        exit;
    }

    $instruccion = trim($_POST['instruccion'] ?? '');
    $elementos   = $_POST['elementos'] ?? '[]';

    if (!$instruccion) {
        echo json_encode(['ok' => false, 'msg' => 'Escribe qué ajuste necesitas.']);
        exit;
    }

    $elementos_data = json_decode($elementos, true);
    if (!is_array($elementos_data)) {
        $elementos_data = [];
    }

    $system_prompt = <<<EOT
Eres un asistente que ajusta el diseño de un banner publicitario compuesto por objetos de un canvas (Fabric.js).
Recibirás una lista de los elementos actuales del banner (id, tipo, texto y propiedades) y una instrucción en español.
Debes responder ÚNICAMENTE con un JSON array de operaciones, sin explicaciones, sin markdown, sin texto adicional.
Cada operación tiene esta forma exacta:
{"id": "<id del elemento a modificar>", "set": { <propiedades a cambiar> }}
Las únicas propiedades permitidas dentro de "set" son: left, top, width, height, scaleX, scaleY, angle, fontSize, fill, fontWeight, fontStyle, textAlign, underline, text, opacity.
Usa "text" solo para cambiar el contenido de un texto. Usa "fill" para colores (formato hexadecimal, ej. "#ff0000").
Si la instrucción no aplica a ningún elemento de la lista, responde con un array vacío: []
No modifiques ids que no existan en la lista recibida.
EOT;

    $user_prompt = "Elementos actuales del banner (JSON):\n" . json_encode($elementos_data, JSON_UNESCAPED_UNICODE) .
                   "\n\nInstrucción del usuario: " . $instruccion;

    $payload = [
        'model' => 'llama-3.3-70b-versatile',
        'messages' => [
            ['role' => 'system', 'content' => $system_prompt],
            ['role' => 'user', 'content' => $user_prompt],
        ],
        'temperature' => 0.2,
    ];

    $ch = curl_init('https://api.groq.com/openai/v1/chat/completions');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => json_encode($payload),
        CURLOPT_HTTPHEADER     => [
            'Authorization: Bearer ' . GROQ_API_KEY,
            'Content-Type: application/json',
        ],
        CURLOPT_TIMEOUT        => 60,
    ]);

    $response  = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_err  = curl_error($ch);
    curl_close($ch);

    if ($curl_err) {
        echo json_encode(['ok' => false, 'msg' => 'Error de conexión con Groq: ' . $curl_err]);
        exit;
    }

    $data = json_decode($response, true);

    if ($http_code !== 200) {
        $msg = $data['error']['message'] ?? $response;
        echo json_encode(['ok' => false, 'msg' => 'Error de Groq (' . $http_code . '): ' . $msg]);
        exit;
    }

    $texto = $data['choices'][0]['message']['content'] ?? '';

    // Quitar posibles fences ```json ... ```
    $texto = preg_replace('/^```(?:json)?\s*|\s*```$/m', '', trim($texto));

    $operaciones = json_decode($texto, true);

    if (!is_array($operaciones)) {
        echo json_encode(['ok' => false, 'msg' => 'La IA no devolvió un ajuste válido. Intenta reformular la instrucción.']);
        exit;
    }

    echo json_encode(['ok' => true, 'operaciones' => $operaciones]);
}
?>
