<?php
require "../config/Conexion.php";

class Academia_solicitudes
{
    public function listar($filtro = 'todas')
    {
        $where = '';
        if ($filtro === 'pendientes') $where = 'WHERE atendida = 0';
        if ($filtro === 'atendidas')  $where = 'WHERE atendida = 1';
        return ejecutarConsulta("SELECT * FROM academia_solicitudes $where ORDER BY fecha_hora DESC");
    }

    public function contar_pendientes()
    {
        $r = ejecutarConsultaSimpleFila("SELECT COUNT(*) AS n FROM academia_solicitudes WHERE atendida = 0");
        return $r ? (int)$r['n'] : 0;
    }

    public function toggle_atendida($id, $valor)
    {
        $id    = (int)$id;
        $valor = $valor ? 1 : 0;
        // Se comprueba que la fila exista (en vez de fiarse de affected_rows,
        // que da 0 tanto si el id no existe como si ya tenía ese mismo valor)
        // para que un id ya eliminado por otro admin reporte error real.
        if (!ejecutarConsultaSimpleFila("SELECT id FROM academia_solicitudes WHERE id = $id")) {
            return false;
        }
        return ejecutarConsulta("UPDATE academia_solicitudes SET atendida = $valor WHERE id = $id") !== false;
    }

    public function eliminar($id)
    {
        return ejecutarConsulta("DELETE FROM academia_solicitudes WHERE id = " . (int)$id);
    }

    // Nota: este método existe también, verbatim, en
    // modelos/Academia_core.php (usado por el formulario público). Se
    // mantiene duplicado (no se comparte vía require) porque raíz y
    // panelc/ son árboles independientes en todo este proyecto; si se
    // actualiza aquí, actualizar también esa copia.
    public function obtener_correos_notificacion()
    {
        try {
            $r = ejecutarConsultaSimpleFila("SELECT correos_notificacion FROM academia_config WHERE id = 1 LIMIT 1");
            if ($r && $r['correos_notificacion'] !== '') {
                // Se revalida aquí (no solo al guardar desde el panel) por si
                // el valor llegó a la tabla por otra vía (edición manual,
                // restauración de base de datos, etc.) y quedó mal formado.
                $validos = array_filter(
                    array_map('trim', explode(',', $r['correos_notificacion'])),
                    function ($c) { return filter_var($c, FILTER_VALIDATE_EMAIL) !== false; }
                );
                if (!empty($validos)) {
                    return implode(', ', $validos);
                }
            }
        } catch (\Throwable $e) {
            error_log('Academia_solicitudes::obtener_correos_notificacion: ' . $e->getMessage());
        }
        return 'pibgdlar@gmail.com';
    }

    public function guardar_correos_notificacion($correos)
    {
        global $conexion;
        $correos = $conexion->real_escape_string(trim($correos));
        try {
            $ok = ejecutarConsulta(
                "INSERT INTO academia_config (id, correos_notificacion) VALUES (1, '$correos')
                 ON DUPLICATE KEY UPDATE correos_notificacion = '$correos'"
            );
            return $ok !== false;
        } catch (\Throwable $e) {
            error_log('Academia_solicitudes::guardar_correos_notificacion: ' . $e->getMessage());
            return false;
        }
    }
}
