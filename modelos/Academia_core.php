<?php
require_once "../config/Conexion.php";

class Academia_core
{
    /**
     * Guarda la solicitud, aplicando el límite de una por IP cada
     * $segundos_limite mediante un INSERT...SELECT...WHERE NOT EXISTS: al ser
     * una sola consulta atómica evita la condición de carrera de comprobar y
     * luego insertar por separado (dos solicitudes casi simultáneas de la
     * misma IP).
     *
     * Devuelve ['id' => int, 'limitado' => bool]. Si $id es 0 y $limitado es
     * false, la inserción falló (se registra el error con error_log()).
     */
    public function guardar_solicitud($nombre, $correo, $telefono, $instrumentos, $ip, $segundos_limite = 60)
    {
        global $conexion;
        $nombre          = $conexion->real_escape_string(trim($nombre));
        $correo          = $conexion->real_escape_string(trim($correo));
        $telefono        = $conexion->real_escape_string(trim($telefono));
        $instrumentos    = $conexion->real_escape_string(trim($instrumentos));
        $ip              = $conexion->real_escape_string(trim($ip));
        $segundos_limite = (int)$segundos_limite;

        $sql = "INSERT INTO academia_solicitudes (nombre, correo, telefono, instrumentos, ip)
                SELECT '$nombre', '$correo', '$telefono', '$instrumentos', '$ip'
                FROM DUAL
                WHERE NOT EXISTS (
                    SELECT 1 FROM academia_solicitudes
                    WHERE ip = '$ip' AND fecha_hora >= (NOW() - INTERVAL $segundos_limite SECOND)
                )";
        $ok = ejecutarConsulta($sql);

        if ($ok === false) {
            error_log('Academia_core::guardar_solicitud: ' . $conexion->error);
            return ['id' => 0, 'limitado' => false];
        }
        if ($conexion->affected_rows === 0) {
            return ['id' => 0, 'limitado' => true];
        }
        return ['id' => $conexion->insert_id, 'limitado' => false];
    }

    // Nota: este método existe también, verbatim, en
    // panelc/modelos/Academia_solicitudes.php. Se mantiene duplicado (no se
    // comparte vía require) porque raíz y panelc/ son árboles independientes
    // en todo este proyecto (ver también config/Conexion.php vs
    // panelc/config/Conexion.php); si se actualiza aquí, actualizar también
    // la copia del panel admin.
    public function obtener_correos_notificacion()
    {
        // Antes de correr panelc/db/academia_config.sql la tabla no existe:
        // no se debe tronar la solicitud pública por eso, solo usar el
        // correo por defecto.
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
            error_log('Academia_core::obtener_correos_notificacion: ' . $e->getMessage());
        }
        return 'pibgdlar@gmail.com';
    }
}
