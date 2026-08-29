<?php
session_start();
if (!isset($_SESSION["nombre"])) { http_response_code(403); exit; }
require_once "../modelos/Predicaciones.php";

$pred = new Predicaciones();

/* ── Extracción de texto ─────────────────────────────────────── */

function _docx_format_num($count, $numFmt) {
    switch ($numFmt) {
        case 'lowerLetter': return chr(96 + (($count - 1) % 26) + 1);
        case 'upperLetter': return chr(64 + (($count - 1) % 26) + 1);
        case 'lowerRoman':  return strtolower(_docx_int_to_roman($count));
        case 'upperRoman':  return _docx_int_to_roman($count);
        default:            return (string)$count;
    }
}

function _docx_int_to_roman($n) {
    $map = [1000=>'M',900=>'CM',500=>'D',400=>'CD',100=>'C',90=>'XC',
            50=>'L',40=>'XL',10=>'X',9=>'IX',5=>'V',4=>'IV',1=>'I'];
    $r = '';
    foreach ($map as $v => $s) { while ($n >= $v) { $r .= $s; $n -= $v; } }
    return $r;
}

function extraer_texto_docx($ruta) {
    if (!class_exists('ZipArchive')) return '';
    $zip = new ZipArchive();
    if ($zip->open($ruta) !== true) return '';
    $doc_xml = $zip->getFromName('word/document.xml');
    $num_xml = $zip->getFromName('word/numbering.xml');
    $zip->close();
    if (!$doc_xml) return '';

    // --- Parsear definiciones de numeración ---
    // $abstract_nums[abstractNumId][ilvl] = ['numFmt'=>..., 'lvlText'=>..., 'start'=>...]
    $abstract_nums = [];
    // $num_map[numId] = abstractNumId
    $num_map = [];

    if ($num_xml) {
        $dn = new DOMDocument();
        libxml_use_internal_errors(true);
        $dn->loadXML($num_xml);
        libxml_clear_errors();
        $xn = new DOMXPath($dn);
        $xn->registerNamespace('w', 'http://schemas.openxmlformats.org/wordprocessingml/2006/main');

        foreach ($xn->query('//w:abstractNum') as $an) {
            $anid = (int)$an->getAttribute('w:abstractNumId');
            $abstract_nums[$anid] = [];
            foreach ($xn->query('.//w:lvl', $an) as $lvl) {
                $ilvl = (int)$lvl->getAttribute('w:ilvl');
                $fmt  = $xn->query('.//w:numFmt/@w:val',  $lvl)->item(0);
                $txt  = $xn->query('.//w:lvlText/@w:val', $lvl)->item(0);
                $st   = $xn->query('.//w:start/@w:val',   $lvl)->item(0);
                $abstract_nums[$anid][$ilvl] = [
                    'numFmt'  => $fmt ? $fmt->nodeValue : 'decimal',
                    'lvlText' => $txt ? $txt->nodeValue : '%1.',
                    'start'   => $st  ? (int)$st->nodeValue : 1,
                ];
            }
        }

        foreach ($xn->query('//w:num') as $num) {
            $nid  = (int)$num->getAttribute('w:numId');
            $abst = $xn->query('.//w:abstractNumId/@w:val', $num)->item(0);
            if ($abst) $num_map[$nid] = (int)$abst->nodeValue;
        }
    }

    // --- Parsear cuerpo del documento ---
    $dd = new DOMDocument();
    libxml_use_internal_errors(true);
    $dd->loadXML($doc_xml);
    libxml_clear_errors();
    $xd = new DOMXPath($dd);
    $xd->registerNamespace('w', 'http://schemas.openxmlformats.org/wordprocessingml/2006/main');

    $counters = [];  // ["numId-ilvl"] => contador actual
    $html = '';

    foreach ($xd->query('//w:body/w:p') as $p) {
        // Alineación del párrafo
        $jc_node = $xd->query('.//w:pPr/w:jc/@w:val', $p)->item(0);
        $align   = $jc_node ? $jc_node->nodeValue : '';

        // Información de lista
        $numId_node = $xd->query('.//w:pPr/w:numPr/w:numId/@w:val', $p)->item(0);
        $ilvl_node  = $xd->query('.//w:pPr/w:numPr/w:ilvl/@w:val', $p)->item(0);
        $numId = $numId_node ? (int)$numId_node->nodeValue : 0;
        $ilvl  = $ilvl_node  ? (int)$ilvl_node->nodeValue  : 0;

        // Recolectar texto con formato inline (negrita, cursiva)
        $line_html = '';
        foreach ($xd->query('.//w:r', $p) as $run) {
            $bold   = $xd->query('.//w:rPr/w:b',  $run)->length > 0;
            $italic = $xd->query('.//w:rPr/w:i',  $run)->length > 0;
            $text   = '';
            foreach ($xd->query('.//w:t', $run) as $t) { $text .= $t->nodeValue; }
            if ($text === '') continue;
            $text = htmlspecialchars($text);
            if ($bold && $italic) $text = "<strong><em>$text</em></strong>";
            elseif ($bold)        $text = "<strong>$text</strong>";
            elseif ($italic)      $text = "<em>$text</em>";
            $line_html .= $text;
        }

        if (trim(strip_tags($line_html)) === '') continue;

        // Generar prefijo de lista
        $prefix      = '';
        $margin_left = 0;

        if ($numId > 0 && isset($num_map[$numId])) {
            $abstractId = $num_map[$numId];
            $key        = "$numId-$ilvl";
            $margin_left = ($ilvl + 1) * 20;

            // Inicializar o incrementar contador
            if (!isset($counters[$key])) {
                $counters[$key] = $abstract_nums[$abstractId][$ilvl]['start'] ?? 1;
            } else {
                $counters[$key]++;
            }
            // Resetear niveles más profundos del mismo numId
            for ($d = $ilvl + 1; $d <= 8; $d++) unset($counters["$numId-$d"]);

            $numFmt  = $abstract_nums[$abstractId][$ilvl]['numFmt']  ?? 'decimal';
            $lvlText = $abstract_nums[$abstractId][$ilvl]['lvlText'] ?? '%1.';

            if ($numFmt === 'bullet') {
                // Para viñetas, lvlText ya es el carácter (•, ○, ▪, etc.)
                $prefix = $lvlText;
            } else {
                // Sustituir %N por el valor formateado del nivel N-1
                $prefix = $lvlText;
                for ($l = 0; $l <= $ilvl; $l++) {
                    $pkey = "$numId-$l";
                    $pcnt = $counters[$pkey] ?? ($abstract_nums[$abstractId][$l]['start'] ?? 1);
                    $pFmt = $abstract_nums[$abstractId][$l]['numFmt'] ?? 'decimal';
                    $pVal = _docx_format_num($pcnt, $pFmt);
                    $prefix = str_replace('%' . ($l + 1), $pVal, $prefix);
                }
            }
        }

        // Construir HTML del párrafo
        $style = '';
        if ($align === 'center') $style .= 'text-align:center;';
        if ($margin_left > 0)   $style .= "margin-left:{$margin_left}px;";

        $attr    = $style ? " style=\"$style\"" : '';
        $content = $prefix !== '' ? htmlspecialchars($prefix) . '&nbsp;' . $line_html : $line_html;
        $html   .= "<p{$attr}>{$content}</p>\n";
    }

    return $html;
}

function extraer_texto_pdf($ruta) {
    if (function_exists('shell_exec')) {
        $cmd    = 'pdftotext -enc UTF-8 ' . escapeshellarg($ruta) . ' - 2>/dev/null';
        $salida = @shell_exec($cmd);
        if ($salida && strlen(trim($salida)) > 5) {
            $bloques = preg_split('/\n{2,}/', trim($salida));
            $partes  = [];
            foreach ($bloques as $b) {
                $b = trim(preg_replace('/[ \t]+/', ' ', $b));
                if ($b) $partes[] = '<p>' . htmlspecialchars($b) . '</p>';
            }
            return implode("\n", $partes);
        }
    }
    return '';
}

switch ($_GET["op"] ?? '') {

    case 'listar':
        $series = [];
        $rs = $pred->listar_series_activas();
        if ($rs) { while ($s = $rs->fetch_object()) { $series[$s->idserie] = $s->nombre; } }

        $result = $pred->listar_sermones();
        if (!$result) {
            echo "<tr><td colspan='9' style='color:#c00;padding:12px;'>Error al cargar predicaciones. Revisa la consola del servidor.</td></tr>";
            break;
        }
        while ($reg = $result->fetch_object()) {
            $cat_nombre   = $reg->categorias_nombres ? htmlspecialchars($reg->categorias_nombres) : '—';
            $serie_nombre = ($reg->serie_id && isset($series[$reg->serie_id])) ? htmlspecialchars($series[$reg->serie_id]) : '—';
            $img_html = $reg->imagen
                ? "<img src='../" . htmlspecialchars($reg->imagen) . "' onerror=\"this.style.display='none'\" style='width:50px;height:50px;object-fit:cover;border-radius:4px;'>"
                : "<span style='color:#aaa;font-size:11px;'>Sin imagen</span>";
            echo "
            <tr>
                <td>{$reg->idsermones}</td>
                <td>{$img_html}</td>
                <td>" . htmlspecialchars($reg->nom_sermon) . "</td>
                <td>" . htmlspecialchars($reg->predicador) . "</td>
                <td>" . htmlspecialchars($reg->actividad) . "</td>
                <td>" . htmlspecialchars($reg->fecha_eti) . "</td>
                <td>{$cat_nombre}</td>
                <td>{$serie_nombre}</td>
                <td>
                    <button onclick=\"editar_sermon({$reg->idsermones});\" style=\"background-color:#042C49;padding:5px 9px;border-radius:5px;border:none;color:#fff;cursor:pointer;margin-right:3px;font-size:11px;\">Editar</button>
                    <a href='../blog.php?id={$reg->idsermones}' target='_blank' style=\"background-color:#28a745;padding:5px 9px;border-radius:5px;color:#fff;margin-right:3px;font-size:11px;text-decoration:none;\">Ver</a>
                    <button onclick=\"borrar_sermon({$reg->idsermones});\" style=\"background-color:rgb(129,2,2);padding:5px 9px;border-radius:5px;border:none;color:#fff;cursor:pointer;font-size:11px;\">Eliminar</button>
                </td>
            </tr>";
        }
        break;

    case 'get_one':
        header('Content-Type: application/json; charset=utf-8');
        $reg = $pred->get_sermon((int)$_GET['id']);
        if ($reg) { $reg['categorias'] = $pred->get_categorias_sermon((int)$_GET['id']); }
        echo json_encode($reg, JSON_UNESCAPED_UNICODE);
        break;

    case 'guardar':
        $cats = isset($_POST['categorias']) && is_array($_POST['categorias'])
            ? array_map('intval', $_POST['categorias']) : [];
        $id = $pred->guardar_sermon(
            $_POST['nom_sermon'],
            $_POST['fecha_eti'],
            $_POST['predicador'],
            $_POST['actividad'],
            $cats,
            (int)($_POST['serie_id'] ?? 0),
            (int)($_POST['orden_serie'] ?? 0),
            $_POST['imagen'] ?? '',
            $_POST['predicacion'] ?? '',
            $_POST['archivo_pred'] ?? ''
        );

        echo json_encode(['ok' => $id > 0, 'id' => $id]);

        // Responder antes de notificar: el envío a todos los suscriptores puede
        // tardar (WebPush + FCM), y no debe hacer esperar al admin por eso.
        if (function_exists('fastcgi_finish_request')) {
            fastcgi_finish_request();
        } elseif (ob_get_level() > 0) {
            ob_end_flush();
            flush();
        }

        if ($id > 0 && ($_SESSION['administrador'] ?? 0) == 1) {
            require_once "../config/push_helpers.php";
            try {
                push_notificar_suscriptores(
                    'Nueva predicación: ' . $_POST['nom_sermon'],
                    'Predicador: ' . $_POST['predicador'],
                    '/blog.php?id=' . $id
                );
            } catch (\Throwable $e) {
                error_log('push_notificar_suscriptores (nueva predicación #' . $id . '): ' . $e->getMessage());
            }
        }
        break;

    case 'actualizar':
        $cats = isset($_POST['categorias']) && is_array($_POST['categorias'])
            ? array_map('intval', $_POST['categorias']) : [];
        $ok = $pred->actualizar_sermon(
            (int)$_POST['idsermones'],
            $_POST['nom_sermon'],
            $_POST['fecha_eti'],
            $_POST['predicador'],
            $_POST['actividad'],
            $cats,
            (int)($_POST['serie_id'] ?? 0),
            (int)($_POST['orden_serie'] ?? 0),
            $_POST['imagen'] ?? '',
            $_POST['predicacion'] ?? '',
            $_POST['archivo_pred'] ?? ''
        );
        echo json_encode(['ok' => (bool)$ok]);
        break;

    case 'borrar':
        $ok = $pred->borrar_sermon((int)$_POST['idsermones']);
        echo json_encode(['ok' => (bool)$ok]);
        break;

    case 'listar_categorias':
        header('Content-Type: application/json; charset=utf-8');
        $result = $pred->listar_categorias();
        $cats = [];
        while ($c = $result->fetch_array()) { $cats[] = ['id' => $c[0], 'nombre' => $c['nombre']]; }
        echo json_encode($cats, JSON_UNESCAPED_UNICODE);
        break;

    case 'guardar_categoria':
        $id = $pred->guardar_categoria($_POST['nombre']);
        echo json_encode(['ok' => $id > 0, 'id' => $id, 'nombre' => $_POST['nombre']]);
        break;

    case 'borrar_categoria':
        $ok = $pred->borrar_categoria((int)$_POST['idcat']);
        echo json_encode(['ok' => (bool)$ok]);
        break;

    case 'listar_series':
        header('Content-Type: application/json; charset=utf-8');
        $result = $pred->listar_series_activas();
        $series = [];
        while ($s = $result->fetch_object()) { $series[] = ['id' => $s->idserie, 'nombre' => $s->nombre]; }
        echo json_encode($series, JSON_UNESCAPED_UNICODE);
        break;

    case 'subir_imagen':
        $ext_ok = ['jpg','jpeg','png','gif','webp'];
        if (!isset($_FILES['imagen']) || $_FILES['imagen']['error'] !== UPLOAD_ERR_OK) {
            echo json_encode(['ok' => false, 'msg' => 'No se recibió archivo.']); break;
        }
        $ext = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $ext_ok)) {
            echo json_encode(['ok' => false, 'msg' => 'Formato no permitido.']); break;
        }
        $carpeta = '../../images/predicaciones/portadas/';
        if (!file_exists($carpeta)) mkdir($carpeta, 0755, true);
        $archivo = 'pred_' . uniqid() . '.' . $ext;
        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $carpeta . $archivo)) {
            echo json_encode(['ok' => true, 'ruta' => 'images/predicaciones/portadas/' . $archivo]);
        } else {
            echo json_encode(['ok' => false, 'msg' => 'Error al guardar el archivo.']);
        }
        break;

    case 'subir_archivo':
        $ext_ok = ['pdf', 'docx'];
        $mime_ok = [
            'application/pdf',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/msword',
        ];
        if (!isset($_FILES['archivo']) || $_FILES['archivo']['error'] !== UPLOAD_ERR_OK) {
            echo json_encode(['ok' => false, 'msg' => 'No se recibió el archivo.']); break;
        }
        $ext = strtolower(pathinfo($_FILES['archivo']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $ext_ok)) {
            echo json_encode(['ok' => false, 'msg' => 'Solo se permiten archivos .pdf o .docx.']); break;
        }
        if ($_FILES['archivo']['size'] > 15 * 1024 * 1024) {
            echo json_encode(['ok' => false, 'msg' => 'El archivo supera el límite de 15 MB.']); break;
        }
        $carpeta = '../../uploads/predicaciones/';
        if (!file_exists($carpeta)) mkdir($carpeta, 0755, true);
        $nombre_original = preg_replace('/[^a-zA-Z0-9áéíóúÁÉÍÓÚñÑ _\-\.]/u', '', $_FILES['archivo']['name']);
        $nombre_original = substr(pathinfo($nombre_original, PATHINFO_FILENAME), 0, 80);
        $nombre_archivo  = 'pred_' . uniqid() . '_' . $nombre_original . '.' . $ext;
        $ruta_server = $carpeta . $nombre_archivo;
        if (move_uploaded_file($_FILES['archivo']['tmp_name'], $ruta_server)) {
            $texto = '';
            if ($ext === 'docx') $texto = extraer_texto_docx($ruta_server);
            elseif ($ext === 'pdf') $texto = extraer_texto_pdf($ruta_server);
            echo json_encode([
                'ok'     => true,
                'ruta'   => 'uploads/predicaciones/' . $nombre_archivo,
                'nombre' => $_FILES['archivo']['name'],
                'texto'  => $texto,
            ]);
        } else {
            echo json_encode(['ok' => false, 'msg' => 'Error al guardar el archivo en el servidor.']);
        }
        break;
}
?>
