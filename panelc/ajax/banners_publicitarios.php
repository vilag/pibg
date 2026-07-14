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

    case 'generar_imagen_ia':
        generar_imagen_pollinations();
        break;

    case 'auto_generar_ia':
        auto_generar_ia();
        break;

    case 'traducir_termino':
        traducir_termino_busqueda();
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
    return ['centro_apilado', 'franja_inferior', 'panel_lateral', 'tarjeta_flotante', 'minimal_esquinas', 'cartel_evento'];
}

function bp_paletas_validas()
{
    return ['institucional', 'calido_festivo', 'elegante_oscuro', 'vibrante_jovenes', 'natural_esperanza'];
}

function bp_iconos_validos()
{
    return ['account-group', 'calendar-star', 'gift', 'music-note', 'microphone-variant', 'hands-pray', 'cross', 'book-open-variant', 'heart', 'candle', 'party-popper', 'star-four-points', 'tent', 'pine-tree', 'weather-sunny', 'trophy', 'ticket-confirmation'];
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
   GENERACIÓN DE IMAGEN DE FONDO CON IA (Pollinations.ai — sin
   API key, modelo flux gratuito/ilimitado en el tier anónimo)
============================================================ */
function generar_imagen_pollinations()
{
    $prompt = trim($_POST['prompt'] ?? '');
    $width  = (int)($_POST['width']  ?? 1024);
    $height = (int)($_POST['height'] ?? 1024);
    $seed   = isset($_POST['seed']) ? (int)$_POST['seed'] : mt_rand(1, 999999);

    if (!$prompt) {
        echo json_encode(['ok' => false, 'msg' => 'Falta la descripción de la imagen a generar.']);
        exit;
    }

    // Cap de resolución: suficiente calidad para un banner, sin pedir imágenes
    // enormes que tarden demasiado o pesen de más al guardarse en la BD.
    $max_lado = 1200;
    if ($width > $height && $width > $max_lado) {
        $height = (int)round($height * ($max_lado / $width));
        $width  = $max_lado;
    } elseif ($height >= $width && $height > $max_lado) {
        $width  = (int)round($width * ($max_lado / $height));
        $height = $max_lado;
    }

    $url = 'https://image.pollinations.ai/prompt/' . rawurlencode($prompt) . '?' . http_build_query([
        'width'  => $width,
        'height' => $height,
        'seed'   => $seed,
        'model'  => 'flux',
        'nologo' => 'true',
    ]);

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 90,
    ]);
    $response  = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $mime      = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    $curl_err  = curl_error($ch);
    curl_close($ch);

    if ($curl_err) {
        echo json_encode(['ok' => false, 'msg' => 'Error de conexión con el generador de imágenes: ' . $curl_err]);
        exit;
    }

    if ($http_code === 429) {
        echo json_encode(['ok' => false, 'msg' => 'El generador de imágenes está ocupado (límite de una imagen cada 15 segundos). Espera un momento e intenta de nuevo.']);
        exit;
    }

    if ($http_code !== 200 || !$response || strpos($mime, 'image/') !== 0) {
        echo json_encode(['ok' => false, 'msg' => 'No se pudo generar la imagen (código ' . $http_code . ').']);
        exit;
    }

    $data_uri = 'data:' . $mime . ';base64,' . base64_encode($response);
    echo json_encode(['ok' => true, 'data' => $data_uri, 'seed' => $seed]);
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

    $puntos_in = json_decode($_POST['puntos'] ?? '[]', true);
    if (!is_array($puntos_in)) $puntos_in = [];
    $puntos_in = array_values(array_filter(array_map('trim', $puntos_in)));
    $puntos_in = array_slice($puntos_in, 0, 5);

    if (!$titulo && !$mensaje) {
        echo json_encode(['ok' => false, 'msg' => 'Escribe al menos un título o un mensaje.']);
        exit;
    }

    $plantillas = bp_plantillas_validas();
    $paletas    = bp_paletas_validas();
    $iconos     = bp_iconos_validos();

    // La proporción del lienzo es una regla geométrica dura: si es muy horizontal
    // o muy vertical, se decide la plantilla ANTES de llamar a la IA (para que
    // redacte el prompt de imagen sabiendo dónde va a quedar el texto encima).
    // Solo en el rango "cuadrado" se le confía la elección a la IA (y si el
    // usuario capturó 2+ puntos destacados, se prefiere la plantilla más
    // elaborada "cartel_evento", pensada justo para ese contenido).
    $plantilla_forzada = null;
    $zona_texto = 'inferior';
    if ($ratio >= 1.4) {
        $plantilla_forzada = 'franja_inferior';
        $zona_texto = 'tercio inferior (franja horizontal)';
    } elseif ($ratio <= 0.75) {
        $plantilla_forzada = 'panel_lateral';
        $zona_texto = 'la mitad inferior (panel sólido, la imagen solo ocupa la mitad superior)';
    } elseif (count($puntos_in) >= 2) {
        $plantilla_forzada = 'cartel_evento';
        $zona_texto = 'varias franjas repartidas en todo el cartel (título arriba, insignias a media altura, barra de datos y franja inferior)';
    }

    $descripcion_plantillas = "centro_apilado (texto centrado con overlay degradado inferior), " .
        "tarjeta_flotante (tarjeta clara centrada sobre la imagen), " .
        "minimal_esquinas (texto abajo con sombra, sin overlay fuerte)";

    $system_prompt = "Eres un director de arte que arma banners publicitarios a partir de plantillas y paletas ya predefinidas (no inventas posiciones ni colores libres).\n" .
        "Debes responder ÚNICAMENTE con un JSON (sin markdown, sin explicaciones) con esta forma exacta:\n" .
        '{"template": "<una de: ' . implode(', ', $plantillas) . '>", "palette": "<una de: ' . implode(', ', $paletas) . '>", "icon": "<una de: ' . implode(', ', $iconos) . ', o null>", "titulo": "<texto final del título>", "mensaje": "<texto final del mensaje>", "imagen_prompt": "<prompt en inglés para generar la imagen de fondo>", "puntos": [{"texto": "<punto tal cual o mejorado>", "icono": "<una de la misma lista de íconos>"}]}' . "\n" .
        ($plantilla_forzada
            ? "La plantilla YA fue decidida: '$plantilla_forzada'. Devuélvela tal cual en \"template\".\n"
            : "Elige la plantilla entre: $descripcion_plantillas. Si hay logo (" . ($tiene_logo ? 'sí' : 'no') . "), 'centro_apilado' facilita ponerlo arriba. Si el mensaje es largo (>140 caracteres), prefiere 'tarjeta_flotante'.\n") .
        "Elige la paleta según el tema/tono del evento (festivo, formal, juvenil, natural, o institucional/genérico por defecto).\n" .
        "Elige un ícono decorativo relacionado al tema, o null si ninguno aplica bien.\n" .
        ($mejorar
            ? "El usuario pidió que mejores/pulas el título y mensaje (gramática, brevedad, impacto), conservando el idioma español y el significado original.\n"
            : "Devuelve el título y mensaje EXACTAMENTE igual a como te los dieron, sin modificarlos.\n") .
        (count($puntos_in)
            ? "El usuario capturó estos puntos destacados: " . implode(' | ', $puntos_in) . ". Devuélvelos en \"puntos\", en el mismo orden, uno por uno, asignando a cada uno el ícono de la lista que mejor le quede" . ($mejorar ? " y puliendo su redacción si hace falta (muy breve, 2-4 palabras)" : " (sin cambiar su texto)") . ". Si no hay puntos, responde \"puntos\": [].\n"
            : "No hay puntos destacados capturados, responde \"puntos\": [].\n") .
        "Para \"imagen_prompt\": redacta, en INGLÉS, la descripción de una fotografía o ilustración profesional para el fondo de este banner, acorde al tema y a la paleta elegida (luz e iluminación coherentes con el tono festivo/formal/juvenil/natural/institucional). " .
        "El texto del banner se sobrepondrá en la zona: $zona_texto — pide explícitamente que esa zona quede simple/despejada (cielo, pared lisa, desenfoque, etc.) para que el texto se lea bien. " .
        "Nunca incluyas texto, letras, logotipos ni marcas de agua en la imagen. Contenido apto para todo público (el banner es de una iglesia). Responde con 1-2 oraciones, estilo directo de prompt de generación de imágenes, sin comillas.";

    $user_prompt = "Tema/palabras clave: " . ($tema ?: '(sin tema específico)') .
        "\nTítulo actual: " . $titulo .
        "\nMensaje actual: " . $mensaje;

    $resultado = llamar_groq_chat($system_prompt, $user_prompt, 0.5);

    if (!$resultado['ok']) {
        echo json_encode($resultado);
        exit;
    }

    $sugerencia = json_decode($resultado['texto'], true);
    if (!is_array($sugerencia)) {
        $sugerencia = [];
    }

    // Validar contra listas cerradas — cualquier valor fuera de rango cae a un valor seguro por defecto.
    $template = $plantilla_forzada ?: (in_array($sugerencia['template'] ?? '', $plantillas, true) ? $sugerencia['template'] : null);
    $palette  = in_array($paleta_forzada, $paletas, true)
        ? $paleta_forzada
        : (in_array($sugerencia['palette'] ?? '', $paletas, true) ? $sugerencia['palette'] : 'institucional');
    $icon     = in_array($sugerencia['icon'] ?? '', $iconos, true) ? $sugerencia['icon'] : null;

    $titulo_final  = $mejorar && !empty($sugerencia['titulo'])  ? trim($sugerencia['titulo'])  : $titulo;
    $mensaje_final = $mejorar && !empty($sugerencia['mensaje']) ? trim($sugerencia['mensaje']) : $mensaje;

    // Los puntos: se valida cada ícono contra la lista cerrada; si la IA no
    // devolvió algo utilizable, se respalda con el texto original sin ícono.
    $puntos_sugeridos = is_array($sugerencia['puntos'] ?? null) ? $sugerencia['puntos'] : [];
    $puntos_final = [];
    foreach ($puntos_in as $i => $texto_punto) {
        $sugerido = $puntos_sugeridos[$i] ?? [];
        $texto_final = ($mejorar && !empty($sugerido['texto'])) ? trim($sugerido['texto']) : $texto_punto;
        $icono_final = in_array($sugerido['icono'] ?? '', $iconos, true) ? $sugerido['icono'] : 'star-four-points';
        $puntos_final[] = ['texto' => $texto_final, 'icono' => $icono_final];
    }

    $imagen_prompt = trim($sugerencia['imagen_prompt'] ?? '');
    if (!$imagen_prompt) {
        // Respaldo simple si la IA no devolvió un prompt utilizable.
        $imagen_prompt = 'Professional advertising banner background related to: ' . ($tema ?: $titulo) .
            '. Clean simple composition, soft lighting, no text, no logos, no watermarks, family friendly.';
    }

    echo json_encode([
        'ok' => true,
        'template' => $template, // null = usar el fallback determinístico en el navegador
        'palette'  => $palette,
        'icon'     => $icon,
        'titulo'   => $titulo_final,
        'mensaje'  => $mensaje_final,
        'imagen_prompt' => $imagen_prompt,
        'puntos'   => $puntos_final,
    ], JSON_UNESCAPED_UNICODE);
}

/* ============================================================
   TRADUCCIÓN DE TÉRMINO DE BÚSQUEDA (Groq)
   La API de búsqueda de íconos (Iconify) solo entiende inglés;
   esto traduce lo que escriba el usuario para que la búsqueda
   funcione igual de bien en español.
============================================================ */
function traducir_termino_busqueda()
{
    $termino = trim($_POST['termino'] ?? '');
    if (!$termino) {
        echo json_encode(['ok' => false, 'msg' => 'Escribe qué ícono buscas.']);
        exit;
    }

    $system_prompt = 'Traduce al inglés la palabra o frase corta que te den, para usarla como término de búsqueda de íconos. ' .
        'Responde ÚNICAMENTE con la traducción en minúsculas, 1-3 palabras, sin comillas, sin explicaciones, sin punto final. ' .
        'Si ya está en inglés, devuélvela tal cual (en minúsculas).';

    $resultado = llamar_groq_chat($system_prompt, $termino, 0.1);

    if (!$resultado['ok']) {
        // Si Groq falla, no bloqueamos la búsqueda: se intenta con el término original.
        echo json_encode(['ok' => true, 'termino_en' => $termino]);
        exit;
    }

    $traduccion = strtolower(trim($resultado['texto'], " \t\n\r\0\x0B\"'."));
    echo json_encode(['ok' => true, 'termino_en' => $traduccion ?: $termino]);
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
