<?php
session_start();
if (!isset($_SESSION["nombre"])) { http_response_code(403); exit; }
require_once "../modelos/Predicaciones.php";

$pred = new Predicaciones();

/* ── Extracción de texto ─────────────────────────────────────── */
function extraer_texto_docx($ruta) {
    if (!class_exists('ZipArchive')) return '';
    $zip = new ZipArchive();
    if ($zip->open($ruta) !== true) return '';
    $xml = $zip->getFromName('word/document.xml');
    $zip->close();
    if (!$xml) return '';
    $dom = new DOMDocument();
    libxml_use_internal_errors(true);
    $dom->loadXML($xml);
    libxml_clear_errors();
    $xpath = new DOMXPath($dom);
    $xpath->registerNamespace('w', 'http://schemas.openxmlformats.org/wordprocessingml/2006/main');
    $parrafos = $xpath->query('//w:p');
    $partes = [];
    foreach ($parrafos as $p) {
        $textos = $xpath->query('.//w:t', $p);
        $linea = '';
        foreach ($textos as $t) { $linea .= $t->nodeValue; }
        $linea = trim($linea);
        if ($linea !== '') $partes[] = '<p>' . htmlspecialchars($linea) . '</p>';
    }
    return implode("\n", $partes);
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
        $cats = [];
        $rc = $pred->listar_categorias();
        while ($c = $rc->fetch_array()) { $cats[$c[0]] = $c['nombre']; }

        $series = [];
        $rs = $pred->listar_series_activas();
        while ($s = $rs->fetch_object()) { $series[$s->idserie] = $s->nombre; }

        $result = $pred->listar_sermones();
        while ($reg = $result->fetch_object()) {
            $cat_nombre   = $cats[$reg->categoria] ?? '—';
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
        echo json_encode($reg, JSON_UNESCAPED_UNICODE);
        break;

    case 'guardar':
        $id = $pred->guardar_sermon(
            $_POST['nom_sermon'],
            $_POST['fecha_eti'],
            $_POST['predicador'],
            $_POST['actividad'],
            (int)$_POST['categoria'],
            (int)($_POST['serie_id'] ?? 0),
            (int)($_POST['orden_serie'] ?? 0),
            $_POST['imagen'] ?? '',
            $_POST['predicacion'] ?? '',
            $_POST['archivo_pred'] ?? ''
        );
        echo json_encode(['ok' => $id > 0, 'id' => $id]);
        break;

    case 'actualizar':
        $ok = $pred->actualizar_sermon(
            (int)$_POST['idsermones'],
            $_POST['nom_sermon'],
            $_POST['fecha_eti'],
            $_POST['predicador'],
            $_POST['actividad'],
            (int)$_POST['categoria'],
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
