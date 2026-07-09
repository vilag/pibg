<?php
session_start();
require_once "../modelos/Encuestas.php";

$enc = new Encuestas();
$op  = $_GET['op'] ?? '';

/* ── Operaciones públicas (sin sesión) ──────────────── */
if ($op === 'guardar_respuesta') {
    $encuesta_id = intval($_POST['encuesta_id'] ?? 0);
    $sesion_id   = session_id();
    $ip          = $_SERVER['REMOTE_ADDR'] ?? '';
    $respuestas  = $_POST['respuestas'] ?? [];
    $id = $enc->guardar_respuesta($encuesta_id, $sesion_id, $ip, $respuestas);
    echo json_encode(['ok' => true, 'id' => $id]);
    exit;
}

/* ── Resto requiere sesión de administrador ─────────── */
if (!isset($_SESSION['nombre'])) {
    echo json_encode(['ok' => false, 'msg' => 'Sin sesión']); exit;
}

switch ($op) {

    case 'crear_encuesta':
        $titulo                    = $_POST['titulo']                    ?? '';
        $descripcion               = $_POST['descripcion']               ?? '';
        $fecha_inicio              = $_POST['fecha_inicio']              ?? '';
        $fecha_fin                 = $_POST['fecha_fin']                 ?? '';
        $imagen_base64             = $_POST['imagen_base64']             ?? '';
        $imagen_secundaria_base64  = $_POST['imagen_secundaria_base64']  ?? '';
        $preguntas                 = json_decode($_POST['preguntas'] ?? '[]', true);

        $id = $enc->crear_encuesta($titulo, $descripcion, $fecha_inicio, $fecha_fin, $imagen_base64, $imagen_secundaria_base64);
        if (!$id) {
            echo json_encode(['ok' => false, 'msg' => 'Error en la base de datos. Verifica que las tablas existan (encuestas.sql).']);
            break;
        }
        if ($preguntas) $enc->guardar_preguntas($id, $preguntas);
        echo json_encode(['ok' => true, 'id' => $id]);
        break;

    case 'editar_encuesta':
        $id                        = intval($_POST['id']                   ?? 0);
        $titulo                    = $_POST['titulo']                    ?? '';
        $descripcion               = $_POST['descripcion']               ?? '';
        $fecha_inicio              = $_POST['fecha_inicio']              ?? '';
        $fecha_fin                 = $_POST['fecha_fin']                 ?? '';
        $imagen_base64             = $_POST['imagen_base64']             ?? '';
        $imagen_secundaria_base64  = $_POST['imagen_secundaria_base64']  ?? '';
        $preguntas                 = json_decode($_POST['preguntas'] ?? '[]', true);

        $enc->actualizar_encuesta($id, $titulo, $descripcion, $fecha_inicio, $fecha_fin, $imagen_base64, $imagen_secundaria_base64);
        $enc->guardar_preguntas($id, $preguntas);
        echo json_encode(['ok' => true]);
        break;

    case 'listar_encuestas':
        $rspta = $enc->listar_encuestas();
        $html  = '';
        while ($e = $rspta->fetch_object()) {
            $vigencia = '';
            if ($e->fecha_inicio && $e->fecha_fin) {
                $vigencia = date('d/m/Y', strtotime($e->fecha_inicio))
                          . ' — ' . date('d/m/Y', strtotime($e->fecha_fin));
            }
            $badge = $e->es_publica
                ? '<span class="badge badge-success">Pública</span>'
                : '<span class="badge badge-secondary">Privada</span>';
            $titulo_esc = htmlspecialchars($e->titulo, ENT_QUOTES);
            $html .= '
            <tr>
              <td style="vertical-align:middle;font-weight:600;">' . $titulo_esc . '</td>
              <td style="vertical-align:middle;text-align:center;">' . $e->total_preguntas . '</td>
              <td style="vertical-align:middle;text-align:center;">
                <span style="font-size:18px;font-weight:700;color:#042C49;">' . $e->total_respuestas . '</span>
              </td>
              <td style="vertical-align:middle;font-size:12px;">' . $vigencia . '</td>
              <td style="vertical-align:middle;">' . $badge . '</td>
              <td style="vertical-align:middle;white-space:nowrap;">
                <button onclick="ver_metricas(' . $e->id . ');" title="Métricas"
                  style="background:#042C49;color:#fff;border:none;padding:6px 10px;border-radius:6px;cursor:pointer;margin-right:3px;font-size:12px;">📊</button>
                <button onclick="editar_encuesta(' . $e->id . ');" title="Editar"
                  style="background:rgb(13,110,180);color:#fff;border:none;padding:6px 10px;border-radius:6px;cursor:pointer;margin-right:3px;font-size:12px;">✏️</button>
                <button onclick="compartir_encuesta(' . $e->id . ');" title="Compartir"
                  style="background:#28a745;color:#fff;border:none;padding:6px 10px;border-radius:6px;cursor:pointer;margin-right:3px;font-size:12px;">🔗</button>
                <button onclick="exportar_excel(' . $e->id . ', \'' . addslashes($titulo_esc) . '\');" title="Exportar Excel"
                  style="background:#1D6F42;color:#fff;border:none;padding:6px 10px;border-radius:6px;cursor:pointer;margin-right:3px;font-size:12px;">📥</button>
                <button onclick="borrar_encuesta(' . $e->id . ');" title="Eliminar"
                  style="background:rgb(129,2,2);color:#fff;border:none;padding:6px 10px;border-radius:6px;cursor:pointer;font-size:12px;">🗑</button>
              </td>
            </tr>';
        }
        if (!$html) $html = '<tr><td colspan="6" style="text-align:center;color:#aaa;padding:30px;">Sin encuestas registradas</td></tr>';
        echo $html;
        break;

    case 'obtener_encuesta':
        $id = intval($_POST['id'] ?? 0);
        $e  = $enc->obtener_encuesta($id)->fetch_object();
        $pr = $enc->obtener_preguntas($id);
        $preguntas = [];
        while ($p = $pr->fetch_object()) {
            $preguntas[] = [
                'id'            => $p->id,
                'tipo'          => $p->tipo,
                'pregunta'      => $p->pregunta,
                'opciones'      => $p->opciones ? json_decode($p->opciones, true) : [],
                'imagen_base64' => $p->imagen_base64,
                'requerida'     => (bool) $p->requerida,
            ];
        }
        echo json_encode(['encuesta' => $e, 'preguntas' => $preguntas]);
        break;

    case 'borrar_encuesta':
        $id = intval($_POST['id'] ?? 0);
        $enc->borrar_encuesta($id);
        echo json_encode(['ok' => true]);
        break;

    case 'toggle_publica':
        $id      = intval($_POST['id']     ?? 0);
        $activar = intval($_POST['activar'] ?? 0);
        if ($activar) {
            $token = $enc->generar_token($id);
            $proto = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
            $host  = $_SERVER['HTTP_HOST'];
            $dir   = rtrim(dirname(str_replace('/ajax', '', $_SERVER['PHP_SELF'])), '/');
            $url   = $proto . '://' . $host . $dir . '/encuesta_publica.php?t=' . $token;
            echo json_encode(['ok' => true, 'token' => $token, 'url' => $url]);
        } else {
            $enc->revocar_token($id);
            echo json_encode(['ok' => true, 'token' => null]);
        }
        break;

    case 'obtener_metricas':
        $id = intval($_POST['id'] ?? 0);
        echo json_encode($enc->obtener_metricas($id));
        break;

    case 'exportar_respuestas':
        $id = intval($_POST['id'] ?? 0);
        echo json_encode($enc->obtener_respuestas_excel($id));
        break;

    default:
        echo json_encode(['ok' => false, 'msg' => 'op no definida']);
}
?>
