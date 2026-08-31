<?php
require "../config/Conexion.php";

class Encuestas
{
    public function __construct() {}

    /* ── Encuestas ─────────────────────────────────── */

    public function crear_encuesta($titulo, $descripcion, $fecha_inicio, $fecha_fin, $imagen_base64 = '', $imagen_secundaria_base64 = '', $idactiv_relacionada = null)
    {
        global $conexion;
        $titulo      = $conexion->real_escape_string($titulo);
        $descripcion = $conexion->real_escape_string($descripcion);
        $idactiv_sql = $idactiv_relacionada !== null ? intval($idactiv_relacionada) : 'NULL';
        $id = ejecutarConsulta_retornarID(
            "INSERT INTO encuestas (titulo, descripcion, fecha_inicio, fecha_fin, idactiv_relacionada)
             VALUES ('$titulo','$descripcion','$fecha_inicio','$fecha_fin',$idactiv_sql)"
        );
        if ($id && $imagen_base64) {
            $img = $conexion->real_escape_string($imagen_base64);
            $conexion->query("UPDATE encuestas SET imagen_base64='$img' WHERE id='$id'");
        }
        if ($id && $imagen_secundaria_base64) {
            $img2 = $conexion->real_escape_string($imagen_secundaria_base64);
            $conexion->query("UPDATE encuestas SET imagen_secundaria_base64='$img2' WHERE id='$id'");
        }
        return $id;
    }

    public function actualizar_encuesta($id, $titulo, $descripcion, $fecha_inicio, $fecha_fin, $imagen_base64 = '', $imagen_secundaria_base64 = '')
    {
        global $conexion;
        $titulo      = $conexion->real_escape_string($titulo);
        $descripcion = $conexion->real_escape_string($descripcion);
        $id = intval($id);
        ejecutarConsulta("UPDATE encuestas SET titulo='$titulo', descripcion='$descripcion',
            fecha_inicio='$fecha_inicio', fecha_fin='$fecha_fin' WHERE id='$id'");
        $img = $conexion->real_escape_string($imagen_base64);
        $conexion->query("UPDATE encuestas SET imagen_base64='$img' WHERE id='$id'");
        $img2 = $conexion->real_escape_string($imagen_secundaria_base64);
        $conexion->query("UPDATE encuestas SET imagen_secundaria_base64='$img2' WHERE id='$id'");
    }

    public function listar_encuestas()
    {
        $sql = "SELECT e.*,
                (SELECT COUNT(*) FROM encuesta_respuestas r WHERE r.encuesta_id = e.id) AS total_respuestas,
                (SELECT COUNT(*) FROM encuesta_preguntas p WHERE p.encuesta_id = e.id AND p.activo=1) AS total_preguntas
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
            WHERE encuesta_id='$encuesta_id' AND activo=1 ORDER BY orden ASC");
    }

    // Versiones anteriores (activo=0) de una pregunta que fue corregida,
    // más antigua primero, siguiendo la cadena reemplaza_a hacia atrás.
    private function obtener_historial_pregunta($pregunta_id)
    {
        $historial = [];
        $actual = intval($pregunta_id);
        // Tope de 20 saltos para no entrar en bucle infinito ante datos corruptos.
        for ($i = 0; $i < 20 && $actual; $i++) {
            $fila = ejecutarConsultaSimpleFila(
                "SELECT id, pregunta, reemplaza_a FROM encuesta_preguntas WHERE id='$actual'"
            );
            if (!$fila || !$fila['reemplaza_a']) break;
            $anterior = ejecutarConsultaSimpleFila(
                "SELECT id, pregunta, tipo FROM encuesta_preguntas WHERE id='" . intval($fila['reemplaza_a']) . "'"
            );
            if (!$anterior) break;
            array_unshift($historial, $anterior);
            $actual = $anterior['id'];
        }
        return $historial;
    }

    public function obtener_encuesta_por_actividad($idactiv)
    {
        $idactiv = intval($idactiv);
        return ejecutarConsulta("SELECT * FROM encuestas WHERE idactiv_relacionada='$idactiv' LIMIT 1");
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

    /**
     * Guarda las preguntas del constructor, preservando el id de las que no
     * cambiaron para no desconectar las respuestas ya capturadas.
     *
     * Antes esto borraba TODAS las preguntas de la encuesta y las volvía a
     * insertar en cada guardado (incluso si solo se corregía el texto de
     * una), generando ids nuevos por el AUTO_INCREMENT — las respuestas ya
     * guardadas en encuesta_respuesta_detalles seguían apuntando al id
     * viejo (ya borrado) y desaparecían de los resultados.
     *
     * Cada elemento de $preguntas puede traer un 'id' (pregunta existente
     * que se está editando) o no traerlo (pregunta nueva):
     *   - Si el texto no cambió: se actualiza en el mismo lugar (mismo id).
     *   - Si el texto cambió: se conserva la fila anterior (activo=0, ya no
     *     se cuenta en resultados "actuales" ni se manda a responder), y se
     *     inserta una fila nueva con reemplaza_a apuntando a la anterior,
     *     para poder mostrar ambos periodos por separado.
     *   - Preguntas nuevas: se insertan tal cual.
     *   - Preguntas existentes que ya no vienen en la lista (se borraron en
     *     el constructor): se marcan activo=0 en vez de borrarse, para no
     *     perder sus respuestas históricas.
     */
    public function guardar_preguntas($encuesta_id, $preguntas)
    {
        global $conexion;
        $encuesta_id = intval($encuesta_id);

        $actuales = [];
        $res = ejecutarConsulta("SELECT id, pregunta, tipo FROM encuesta_preguntas WHERE encuesta_id='$encuesta_id' AND activo=1");
        while ($fila = $res->fetch_assoc()) {
            $actuales[(int)$fila['id']] = ['pregunta' => $fila['pregunta'], 'tipo' => $fila['tipo']];
        }

        $ids_usados = [];

        foreach ($preguntas as $i => $p) {
            $orden     = $i + 1;
            $tipo      = $conexion->real_escape_string($p['tipo']     ?? 'libre');
            $pregunta  = $p['pregunta'] ?? '';
            $requerida = isset($p['requerida']) && $p['requerida'] ? 1 : 0;
            $opciones  = (isset($p['opciones']) && is_array($p['opciones']))
                ? "'" . $conexion->real_escape_string(json_encode($p['opciones'], JSON_UNESCAPED_UNICODE)) . "'"
                : "NULL";
            $pregunta_sql = $conexion->real_escape_string($pregunta);
            $id_existente = isset($p['id']) ? intval($p['id']) : 0;

            if ($id_existente && array_key_exists($id_existente, $actuales)) {
                $ids_usados[] = $id_existente;
                $sin_cambios = $actuales[$id_existente]['pregunta'] === $pregunta
                    && $actuales[$id_existente]['tipo'] === ($p['tipo'] ?? 'libre');
                if ($sin_cambios) {
                    // Ni el texto ni el tipo cambiaron: solo se actualizan opciones/orden/requerida en el mismo lugar.
                    ejecutarConsulta("UPDATE encuesta_preguntas SET
                        orden='$orden', tipo='$tipo', opciones=$opciones, requerida='$requerida'
                        WHERE id='$id_existente'");
                } else {
                    // El texto y/o el tipo de respuesta cambiaron: se conserva la
                    // pregunta anterior (con sus respuestas, bajo su propio tipo
                    // original) y se crea una nueva versión enlazada. Si solo
                    // cambiara el tipo (ej. de "calificación" a "libre") sin tocar
                    // el texto, versionar evita reinterpretar respuestas viejas
                    // (números de calificación, etc.) bajo el tipo nuevo.
                    ejecutarConsulta("UPDATE encuesta_preguntas SET activo=0 WHERE id='$id_existente'");
                    ejecutarConsulta("INSERT INTO encuesta_preguntas
                        (encuesta_id, orden, tipo, pregunta, opciones, requerida, activo, reemplaza_a)
                        VALUES ('$encuesta_id','$orden','$tipo','$pregunta_sql',$opciones,'$requerida',1,'$id_existente')");
                }
            } else {
                // Pregunta nueva (no traía id, o traía uno que ya no es una pregunta activa de esta encuesta).
                ejecutarConsulta("INSERT INTO encuesta_preguntas
                    (encuesta_id, orden, tipo, pregunta, opciones, requerida, activo)
                    VALUES ('$encuesta_id','$orden','$tipo','$pregunta_sql',$opciones,'$requerida',1)");
            }
        }

        // Preguntas que existían y ya no vienen en la lista: se retiran sin borrar el historial.
        $sobrantes = array_diff(array_keys($actuales), $ids_usados);
        foreach ($sobrantes as $id_sobrante) {
            ejecutarConsulta("UPDATE encuesta_preguntas SET activo=0 WHERE id='" . intval($id_sobrante) . "'");
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

    public function borrar_respuesta($id)
    {
        $id = intval($id);
        ejecutarConsulta("DELETE FROM encuesta_respuesta_detalles WHERE respuesta_id='$id'");
        return ejecutarConsulta("DELETE FROM encuesta_respuestas WHERE id='$id'");
    }

    /* ── Métricas ───────────────────────────────────── */

    // Calcula la métrica de una sola pregunta (por su id), sin importar si
    // está activa o es una versión histórica retirada.
    private function calcular_metrica_pregunta($pregunta_id, $tipo)
    {
        $m = ['respuestas' => []];

        if ($tipo === 'libre') {
            $textos = ejecutarConsulta(
                "SELECT valor FROM encuesta_respuesta_detalles WHERE pregunta_id='$pregunta_id'"
            );
            $m['textos'] = [];
            while ($t = $textos->fetch_object()) {
                $m['textos'][] = $t->valor;
            }
        } else {
            $agg = ejecutarConsulta(
                "SELECT valor, COUNT(*) AS cnt
                 FROM encuesta_respuesta_detalles
                 WHERE pregunta_id='$pregunta_id'
                 GROUP BY valor ORDER BY cnt DESC"
            );
            while ($d = $agg->fetch_object()) {
                $m['respuestas'][] = ['valor' => $d->valor, 'cnt' => (int) $d->cnt];
            }
            if ($tipo === 'calificacion' && count($m['respuestas'])) {
                $sum = 0; $cnt = 0;
                foreach ($m['respuestas'] as $r) {
                    $sum += floatval($r['valor']) * $r['cnt'];
                    $cnt += $r['cnt'];
                }
                $m['promedio'] = $cnt > 0 ? round($sum / $cnt, 1) : 0;
            }
        }
        return $m;
    }

    public function obtener_metricas($encuesta_id)
    {
        $encuesta_id = intval($encuesta_id);
        $total = (int) ejecutarConsulta(
            "SELECT COUNT(*) AS n FROM encuesta_respuestas WHERE encuesta_id='$encuesta_id'"
        )->fetch_object()->n;

        $preguntas = $this->obtener_preguntas($encuesta_id);
        $metricas  = [];

        while ($p = $preguntas->fetch_object()) {
            $m = array_merge(
                [
                    'pregunta_id' => $p->id,
                    'pregunta'    => $p->pregunta,
                    'tipo'        => $p->tipo,
                    'total'       => $total,
                ],
                $this->calcular_metrica_pregunta($p->id, $p->tipo)
            );

            // Versiones anteriores de esta misma pregunta (texto corregido en
            // algún momento): se muestran aparte, marcadas como histórico,
            // en vez de perderse o mezclarse con las respuestas actuales.
            $m['historial'] = [];
            foreach ($this->obtener_historial_pregunta($p->id) as $version) {
                // Se usa el tipo propio de esa versión (pudo cambiar junto
                // con el texto, ej. de "calificación" a "libre"), no el tipo
                // de la pregunta activa actual.
                $m['historial'][] = array_merge(
                    ['pregunta_id' => $version['id'], 'pregunta' => $version['pregunta']],
                    $this->calcular_metrica_pregunta($version['id'], $version['tipo'])
                );
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
        // Incluye también las versiones históricas (activo=0) de preguntas
        // corregidas, para no perder esas columnas en la exportación; se
        // etiquetan para distinguirlas de la versión vigente.
        $pr = ejecutarConsulta("SELECT * FROM encuesta_preguntas WHERE encuesta_id='$encuesta_id' ORDER BY orden ASC, id ASC");
        while ($p = $pr->fetch_object()) {
            $preguntas[$p->id] = $p->activo ? $p->pregunta : $p->pregunta . ' (versión anterior)';
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
