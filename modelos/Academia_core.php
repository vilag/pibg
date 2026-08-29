<?php
require_once "../config/Conexion.php";

class Academia_core
{
    public function ya_solicito_recientemente($ip, $segundos = 60)
    {
        global $conexion;
        $ip = $conexion->real_escape_string($ip);
        $segundos = (int)$segundos;

        $sql = "SELECT id FROM academia_solicitudes
                WHERE ip = '$ip' AND fecha_hora >= (NOW() - INTERVAL $segundos SECOND)
                LIMIT 1";
        $res = ejecutarConsulta($sql);
        return $res && $res->num_rows > 0;
    }

    public function guardar_solicitud($nombre, $correo, $telefono, $instrumentos, $ip)
    {
        global $conexion;
        $nombre       = $conexion->real_escape_string(trim($nombre));
        $correo       = $conexion->real_escape_string(trim($correo));
        $telefono     = $conexion->real_escape_string(trim($telefono));
        $instrumentos = $conexion->real_escape_string(trim($instrumentos));
        $ip           = $conexion->real_escape_string(trim($ip));

        $sql = "INSERT INTO academia_solicitudes (nombre, correo, telefono, instrumentos, ip)
                VALUES ('$nombre', '$correo', '$telefono', '$instrumentos', '$ip')";
        return ejecutarConsulta_retornarID($sql);
    }
}
