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
}
