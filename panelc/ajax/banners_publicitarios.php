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

    case 'buscar_imagenes':
        buscar_imagenes_pexels();
        break;

    case 'descargar_imagen':
        descargar_imagen_pexels();
        break;

    case 'auto_generar_ia':
        auto_generar_ia();
        break;

    default:
        echo json_encode(['ok' => false, 'msg' => 'Operación no válida.']);
}

/* ============================================================
   LISTAS CERRADAS DEL SISTEMA DE DISEÑO (deben coincidir con
   las mismas listas en scripts/banners_publicitarios.js)
============================================================ */
function bp_plantillas_validas()
{
    return ['centro_apilado', 'franja_inferior', 'panel_lateral', 'tarjeta_flotante', 'minimal_esquinas'];
}

function bp_paletas_validas()
{
    return ['institucional', 'calido_festivo', 'elegante_oscuro', 'vibrante_jovenes', 'natural_esperanza'];
}

function bp_iconos_validos()
{
    return ['account-group', 'calendar-star', 'gift', 'music-note', 'microphone-variant', 'hands-pray', 'cross', 'book-open-variant', 'heart', 'candle', 'party-popper', 'star-four-points'];
}

/* ============================================================
   LLAMADA GENÉRICA A GROQ (chat completions)
============================================================ */
function llamar_groq_chat($system_prompt, $user_prompt, $temperature = 0.2)
{
    if (!defined('GROQ_API_KEY') || strpos(GROQ_API_KEY, 'gsk_XXXX') !== false) {
        return ['ok' => false, 'msg' => 'Configura tu API key de Groq en panelc/config/secrets.php — Obtén una gratis en https://console.groq.com/keys'];
    }

    $payload = [
        'model' => 'llama-3.3-70b-versatile',
        'messages' => [
            ['role' => 'system', 'content' => $system_prompt],
            ['role' => 'user', 'content' => $user_prompt],
        ],
        'temperature' => $temperature,
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
        return ['ok' => false, 'msg' => 'Error de conexión con Groq: ' . $curl_err];
    }

    $data = json_decode($response, true);

    if ($http_code !== 200) {
        $msg = $data['error']['message'] ?? $response;
        return ['ok' => false, 'msg' => 'Error de Groq (' . $http_code . '): ' . $msg];
    }

    $texto = $data['choices'][0]['message']['content'] ?? '';
    $texto = preg_replace('/^```(?:json)?\s*|\s*```$/m', '', trim($texto));

    return ['ok' => true, 'texto' => $texto];
}

/* ============================================================
   BÚSQUEDA DE IMÁGENES (Pexels — banco gratuito, uso comercial
   permitido, sin atribución obligatoria)
============================================================ */
function buscar_imagenes_pexels()
{
    if (!defined('PEXELS_API_KEY') || strpos(PEXELS_API_KEY, 'TU_PEXELS_API_KEY_AQUI') !== false) {
        echo json_encode(['ok' => false, 'msg' => 'Configura tu API key de Pexels en panelc/config/secrets.php — Obtén una gratis en https://www.pexels.com/api/']);
        exit;
    }

    $tema        = trim($_GET['tema'] ?? '');
    $orientacion = $_GET['orientacion'] ?? 'all';

    if (!$tema) {
        echo json_encode(['ok' => false, 'msg' => 'Escribe un tema para buscar imágenes.']);
        exit;
    }

    if (!in_array($orientacion, ['horizontal', 'vertical', 'all'], true)) {
        $orientacion = 'all';
    }

    $params = [
        'query'    => $tema,
        'per_page' => 8,
        'locale'   => 'es-ES',
    ];
    if ($orientacion === 'horizontal') $params['orientation'] = 'landscape';
    if ($orientacion === 'vertical')   $params['orientation'] = 'portrait';

    $url = 'https://api.pexels.com/v1/search?' . http_build_query($params);

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_HTTPHEADER     => ['Authorization: ' . PEXELS_API_KEY],
    ]);
    $response  = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_err  = curl_error($ch);
    curl_close($ch);

    if ($curl_err) {
        echo json_encode(['ok' => false, 'msg' => 'Error de conexión con Pexels: ' . $curl_err]);
        exit;
    }

    $data = json_decode($response, true);

    if ($http_code !== 200) {
        $msg = $data['error'] ?? $data['message'] ?? $response;
        echo json_encode(['ok' => false, 'msg' => 'Error de Pexels (' . $http_code . '): ' . $msg]);
        exit;
    }

    $hits = array_map(function ($foto) {
        return [
            'id'        => $foto['id'],
            'preview'   => $foto['src']['medium'],
            'webformat' => $foto['src']['large2x'],
            'tags'      => $foto['alt'] ?? '',
        ];
    }, $data['photos'] ?? []);

    echo json_encode(['ok' => true, 'datos' => $hits]);
}

/* ============================================================
   DESCARGA DE IMAGEN ELEGIDA (proxy servidor → evita "tainted
   canvas" al exportar el PNG y valida el host contra SSRF)
============================================================ */
function descargar_imagen_pexels()
{
    $url = trim($_POST['url'] ?? '');

    if (!$url || !preg_match('#^https://([a-z0-9-]+\.)*pexels\.com/#i', $url)) {
        echo json_encode(['ok' => false, 'msg' => 'URL de imagen no válida.']);
        exit;
    }

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 30,
    ]);
    $response  = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $mime      = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    $curl_err  = curl_error($ch);
    curl_close($ch);

    if ($curl_err || $http_code !== 200 || !$response) {
        echo json_encode(['ok' => false, 'msg' => 'No se pudo descargar la imagen.']);
        exit;
    }

    if (strpos($mime, 'image/') !== 0) {
        echo json_encode(['ok' => false, 'msg' => 'La URL no devolvió una imagen válida.']);
        exit;
    }

    $data_uri = 'data:' . $mime . ';base64,' . base64_encode($response);
    echo json_encode(['ok' => true, 'data' => $data_uri]);
}

/* ============================================================
   GENERACIÓN AUTOMÁTICA CON IA
   Groq elige plantilla/paleta/ícono de listas cerradas y puede
   pulir los textos. Todo lo que devuelve se valida contra las
   listas permitidas antes de usarse.
============================================================ */
function auto_generar_ia()
{
    $tema         = trim($_POST['tema'] ?? '');
    $titulo       = trim($_POST['titulo'] ?? '');
    $mensaje      = trim($_POST['mensaje'] ?? '');
    $ratio        = (float)($_POST['ratio'] ?? 1);
    $tiene_logo   = ($_POST['tiene_logo'] ?? '0') === '1';
    $mejorar      = ($_POST['mejorar_textos'] ?? '0') === '1';
    $paleta_forzada = trim($_POST['paleta_forzada'] ?? '');

    if (!$titulo && !$mensaje) {
        echo json_encode(['ok' => false, 'msg' => 'Escribe al menos un título o un mensaje.']);
        exit;
    }

    $plantillas = bp_plantillas_validas();
    $paletas    = bp_paletas_validas();
    $iconos     = bp_iconos_validos();

    $system_prompt = "Eres un asistente de diseño gráfico que arma banners publicitarios a partir de plantillas y paletas ya predefinidas (no inventas posiciones ni colores libres).\n" .
        "Debes responder ÚNICAMENTE con un JSON (sin markdown, sin explicaciones) con esta forma exacta:\n" .
        '{"template": "<una de: ' . implode(', ', $plantillas) . '>", "palette": "<una de: ' . implode(', ', $paletas) . '>", "icon": "<una de: ' . implode(', ', $iconos) . ', o null>", "titulo": "<texto final del título>", "mensaje": "<texto final del mensaje>"}' . "\n" .
        "Elige la plantilla según: proporción del lienzo (ratio ancho/alto " . round($ratio, 2) . "; ratio>=1.4 sugiere 'franja_inferior', ratio<=0.75 sugiere 'panel_lateral'), longitud del mensaje (>140 caracteres sugiere 'tarjeta_flotante'), y si hay logo (" . ($tiene_logo ? 'sí' : 'no') . ", si hay logo prefiere 'centro_apilado').\n" .
        "Elige la paleta según el tema/tono del evento (festivo, formal, juvenil, natural, o institucional/genérico por defecto).\n" .
        "Elige un ícono decorativo relacionado al tema, o null si ninguno aplica bien.\n" .
        ($mejorar
            ? "El usuario pidió que mejores/pulas el título y mensaje (gramática, brevedad, impacto), conservando el idioma español y el significado original."
            : "Devuelve el título y mensaje EXACTAMENTE igual a como te los dieron, sin modificarlos.");

    $user_prompt = "Tema/palabras clave: " . ($tema ?: '(sin tema específico)') .
        "\nTítulo actual: " . $titulo .
        "\nMensaje actual: " . $mensaje;

    $resultado = llamar_groq_chat($system_prompt, $user_prompt, 0.4);

    if (!$resultado['ok']) {
        echo json_encode($resultado);
        exit;
    }

    $sugerencia = json_decode($resultado['texto'], true);
    if (!is_array($sugerencia)) {
        $sugerencia = [];
    }

    // Validar contra listas cerradas — cualquier valor fuera de rango cae a un valor seguro por defecto.
    // La proporción del lienzo es una regla geométrica dura: si es muy horizontal o muy vertical,
    // se fuerza la plantilla adecuada sin importar lo que sugiera la IA (solo se le confía la elección
    // dentro del rango "cuadrado", donde varias plantillas encajan igual de bien).
    if ($ratio >= 1.4) {
        $template = 'franja_inferior';
    } elseif ($ratio <= 0.75) {
        $template = 'panel_lateral';
    } else {
        $template = in_array($sugerencia['template'] ?? '', $plantillas, true) ? $sugerencia['template'] : null;
    }
    $palette  = in_array($paleta_forzada, $paletas, true)
        ? $paleta_forzada
        : (in_array($sugerencia['palette'] ?? '', $paletas, true) ? $sugerencia['palette'] : 'institucional');
    $icon     = in_array($sugerencia['icon'] ?? '', $iconos, true) ? $sugerencia['icon'] : null;

    $titulo_final  = $mejorar && !empty($sugerencia['titulo'])  ? trim($sugerencia['titulo'])  : $titulo;
    $mensaje_final = $mejorar && !empty($sugerencia['mensaje']) ? trim($sugerencia['mensaje']) : $mensaje;

    echo json_encode([
        'ok' => true,
        'template' => $template, // null = usar el fallback determinístico en el navegador
        'palette'  => $palette,
        'icon'     => $icon,
        'titulo'   => $titulo_final,
        'mensaje'  => $mensaje_final,
    ], JSON_UNESCAPED_UNICODE);
}

/* ============================================================
   AJUSTE DE DISEÑO CON IA (Groq — mismo API key que Whisper)
   Interpreta una instrucción en lenguaje natural y devuelve
   una lista de operaciones para aplicar a los objetos del canvas.
============================================================ */
function ajustar_con_ia()
{
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

    $resultado = llamar_groq_chat($system_prompt, $user_prompt, 0.2);

    if (!$resultado['ok']) {
        echo json_encode($resultado);
        exit;
    }

    $operaciones = json_decode($resultado['texto'], true);

    if (!is_array($operaciones)) {
        echo json_encode(['ok' => false, 'msg' => 'La IA no devolvió un ajuste válido. Intenta reformular la instrucción.']);
        exit;
    }

    echo json_encode(['ok' => true, 'operaciones' => $operaciones]);
}
?>
