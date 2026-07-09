<?php
require "../config/Conexion.php";

class Encuestas
{
    public function __construct() {}

    /* ── Encuestas ─────────────────────────────────── */

    public function crear_encuesta($titulo, $descripcion, $fecha_inicio, $fecha_fin, $imagen_base64 = '')
    {
        global $conexion;
        $titulo        = $conexion->real_escape_string($titulo);
        $descripcion   = $conexion->real_escape_string($descripcion);
        $imagen_base64 = $conexion->real_escape_string($imagen_base64);
        $sql = "INSERT INTO encuestas (titulo, descripcion, fecha_inicio, fecha_fin, imagen_base64)
                VALUES ('$titulo','$descripcion','$fecha_inicio','$fecha_fin','$imagen_base64')";
        return ejecutarConsulta_retornarID($sql);
    }

    public function actualizar_encuesta($id, $titulo, $descripcion, $fecha_inicio, $fecha_fin, $imagen_base64 = '')
    {
        global $conexion;
        $titulo        = $conexion->real_escape_string($titulo);
        $descripcion   = $conexion->real_escape_string($descripcion);
        $imagen_base64 = $conexion->real_escape_string($imagen_base64);
        $id = intval($id);
        ejecutarConsulta("UPDATE encuestas SET titulo='$titulo', descripcion='$descripcion',
            fecha_inicio='$fecha_inicio', fecha_fin='$fecha_fin', imagen_base64='$imagen_base64'
            WHERE id='$id'");
    }

    public function listar_encuestas()
    {
        $sql = "SELECT e.*,
                (SELECT COUNT(*) FROM encuesta_respuestas r WHERE r.encuesta_id = e.id) AS total_respuestas,
                (SELECT COUNT(*) FROM encuesta_preguntas p WHERE p.encuesta_id = e.id) AS total_preguntas
                FROM encuestas e ORDER BY e.fecha_creacion DESC";
        return ejecutarConsulta($sql);
    }

    public function obtener_encuesta($id)
    {
        $id = intval($id);
        return ejecutarConsulta("SELECT * FROM encuestas WHERE id='$id'");
    }

    public function obtener_preguntas($encuesta_id)
    {
        $encuesta_id = intval($encuesta_id);
        return ejecutarConsulta("SELECT * FROM encuesta_preguntas
            WHERE encuesta_id='$encuesta_id' ORDER BY orden ASC");
    }

    public function obtener_encuesta_por_token($token)
    {
        global $conexion;
        $token = $conexion->real_escape_string($token);
        return ejecutarConsulta("SELECT * FROM encuestas
            WHERE token_publico='$token' AND es_publica=1");
    }

    public function borrar_encuesta($id)
    {
        $id = intval($id);
        $resp_ids = ejecutarConsulta("SELECT id FROM encuesta_respuestas WHERE encuesta_id='$id'");
        while ($r = $resp_ids->fetch_object()) {
            ejecutarConsulta("DELETE FROM encuesta_respuesta_detalles WHERE respuesta_id='{$r->id}'");
        }
        ejecutarConsulta("DELETE FROM encuesta_respuestas WHERE encuesta_id='$id'");
        ejecutarConsulta("DELETE FROM encuesta_preguntas WHERE encuesta_id='$id'");
        return ejecutarConsulta("DELETE FROM encuestas WHERE id='$id'");
    }

    public function generar_token($id)
    {
        $id    = intval($id);
        $token = bin2hex(random_bytes(16));
        ejecutarConsulta("UPDATE encuestas SET es_publica=1, token_publico='$token' WHERE id='$id'");
        return $token;
    }

    public function revocar_token($id)
    {
        $id = intval($id);
        ejecutarConsulta("UPDATE encuestas SET es_publica=0, token_publico=NULL WHERE id='$id'");
    }

    /* ── Preguntas ──────────────────────────────────── */

    public function guardar_preguntas($encuesta_id, $preguntas)
    {
        global $conexion;
        $encuesta_id = intval($encuesta_id);
        ejecutarConsulta("DELETE FROM encuesta_preguntas WHERE encuesta_id='$encuesta_id'");
        foreach ($preguntas as $i => $p) {
            $orden    = $i + 1;
            $tipo     = $conexion->real_escape_string($p['tipo']     ?? 'libre');
            $pregunta = $conexion->real_escape_string($p['pregunta'] ?? '');
            $requerida = isset($p['requerida']) && $p['requerida'] ? 1 : 0;
            $opciones = (isset($p['opciones']) && is_array($p['opciones']))
                ? "'" . $conexion->real_escape_string(json_encode($p['opciones'], JSON_UNESCAPED_UNICODE)) . "'"
                : "NULL";
            ejecutarConsulta("INSERT INTO encuesta_preguntas
                (encuesta_id, orden, tipo, pregunta, opciones, requerida)
                VALUES ('$encuesta_id','$orden','$tipo','$pregunta',$opciones,'$requerida')");
        }
    }

    /* ── Respuestas ─────────────────────────────────── */

    public function guardar_respuesta($encuesta_id, $sesion_id, $ip, $respuestas)
    {
        global $conexion;
        $encuesta_id = intval($encuesta_id);
        $sesion_id   = $conexion->real_escape_string($sesion_id);
        $ip          = $conexion->real_escape_string($ip);
        $resp_id = ejecutarConsulta_retornarID(
            "INSERT INTO encuesta_respuestas (encuesta_id, sesion_id, ip)
             VALUES ('$encuesta_id','$sesion_id','$ip')"
        );
        foreach ($respuestas as $pregunta_id => $valor) {
            $pregunta_id = intval($pregunta_id);
            $v = is_array($valor) ? implode(' | ', $valor) : $valor;
            $v = $conexion->real_escape_string($v);
            ejecutarConsulta("INSERT INTO encuesta_respuesta_detalles (respuesta_id, pregunta_id, valor)
                VALUES ('$resp_id','$pregunta_id','$v')");
        }
        return $resp_id;
    }

    /* ── Métricas ───────────────────────────────────── */

    public function obtener_metricas($encuesta_id)
    {
        $encuesta_id = intval($encuesta_id);
        $total = (int) ejecutarConsulta(
            "SELECT COUNT(*) AS n FROM encuesta_respuestas WHERE encuesta_id='$encuesta_id'"
        )->fetch_object()->n;

        $preguntas = $this->obtener_preguntas($encuesta_id);
        $metricas  = [];

        while ($p = $preguntas->fetch_object()) {
            $m = [
                'pregunta_id' => $p->id,
                'pregunta'    => $p->pregunta,
                'tipo'        => $p->tipo,
                'total'       => $total,
                'respuestas'  => [],
            ];

            if ($p->tipo === 'libre') {
                $textos = ejecutarConsulta(
                    "SELECT valor FROM encuesta_respuesta_detalles WHERE pregunta_id='{$p->id}'"
                );
                $m['textos'] = [];
                while ($t = $textos->fetch_object()) {
                    $m['textos'][] = $t->valor;
                }
            } else {
                $agg = ejecutarConsulta(
                    "SELECT valor, COUNT(*) AS cnt
                     FROM encuesta_respuesta_detalles
                     WHERE pregunta_id='{$p->id}'
                     GROUP BY valor ORDER BY cnt DESC"
                );
                while ($d = $agg->fetch_object()) {
                    $m['respuestas'][] = ['valor' => $d->valor, 'cnt' => (int) $d->cnt];
                }
                if ($p->tipo === 'calificacion' && count($m['respuestas'])) {
                    $sum = 0; $cnt = 0;
                    foreach ($m['respuestas'] as $r) {
                        $sum += floatval($r['valor']) * $r['cnt'];
                        $cnt += $r['cnt'];
                    }
                    $m['promedio'] = $cnt > 0 ? round($sum / $cnt, 1) : 0;
                }
            }
            $metricas[] = $m;
        }
        return ['total_respuestas' => $total, 'preguntas' => $metricas];
    }

    /* ── Exportar a Excel ───────────────────────────── */

    public function obtener_respuestas_excel($encuesta_id)
    {
        $encuesta_id = intval($encuesta_id);
        $preguntas   = [];
        $pr = $this->obtener_preguntas($encuesta_id);
        while ($p = $pr->fetch_object()) {
            $preguntas[$p->id] = $p->pregunta;
        }

        $rows = [];
        $resp = ejecutarConsulta(
            "SELECT * FROM encuesta_respuestas WHERE encuesta_id='$encuesta_id' ORDER BY fecha_respuesta ASC"
        );
        while ($r = $resp->fetch_object()) {
            $row = ['id' => $r->id, 'fecha' => $r->fecha_respuesta, 'ip' => $r->ip];
            $det = ejecutarConsulta(
                "SELECT pregunta_id, valor FROM encuesta_respuesta_detalles WHERE respuesta_id='{$r->id}'"
            );
            while ($d = $det->fetch_object()) {
                $row['p_' . $d->pregunta_id] = $d->valor;
            }
            $rows[] = $row;
        }
        return ['preguntas' => $preguntas, 'respuestas' => $rows];
    }
}
?>
