<?php
require "../config/Conexion.php";

Class Push_suscripciones
{
    public function __construct() {}

    public function guardar_webpush($endpoint, $p256dh, $auth, $user_agent)
    {
        global $conexion;
        $endpoint   = $conexion->real_escape_string($endpoint);
        $p256dh     = $conexion->real_escape_string($p256dh);
        $auth       = $conexion->real_escape_string($auth);
        $user_agent = $conexion->real_escape_string(substr($user_agent, 0, 255));

        $sql = "INSERT INTO push_suscripciones (tipo, endpoint, p256dh, auth, user_agent, activo)
                VALUES ('webpush', '$endpoint', '$p256dh', '$auth', '$user_agent', 1)
                ON DUPLICATE KEY UPDATE p256dh='$p256dh', auth='$auth', user_agent='$user_agent', activo=1";
        return ejecutarConsulta($sql);
    }

    public function guardar_fcm($token, $user_agent)
    {
        global $conexion;
        $token      = $conexion->real_escape_string($token);
        $user_agent = $conexion->real_escape_string(substr($user_agent, 0, 255));

        $sql = "INSERT INTO push_suscripciones (tipo, fcm_token, user_agent, activo)
                VALUES ('fcm', '$token', '$user_agent', 1)
                ON DUPLICATE KEY UPDATE user_agent='$user_agent', activo=1";
        return ejecutarConsulta($sql);
    }

    public function listar_activas($tipo = null)
    {
        $where = $tipo ? "WHERE activo=1 AND tipo='" . $tipo . "'" : "WHERE activo=1";
        $sql = "SELECT * FROM push_suscripciones $where";
        $res = ejecutarConsulta($sql);
        $filas = [];
        while ($row = $res->fetch_assoc()) { $filas[] = $row; }
        return $filas;
    }

    public function contar_activas()
    {
        $sql = "SELECT tipo, COUNT(*) AS total FROM push_suscripciones WHERE activo=1 GROUP BY tipo";
        $res = ejecutarConsulta($sql);
        $out = ['fcm' => 0, 'webpush' => 0];
        while ($row = $res->fetch_assoc()) { $out[$row['tipo']] = (int)$row['total']; }
        return $out;
    }

    public function desactivar($id)
    {
        $id = intval($id);
        return ejecutarConsulta("UPDATE push_suscripciones SET activo=0 WHERE id=$id");
    }

    public function registrar_envio($titulo, $mensaje, $url, $total_destinatarios, $total_exitosos)
    {
        global $conexion;
        $titulo  = $conexion->real_escape_string($titulo);
        $mensaje = $conexion->real_escape_string($mensaje);
        $url_sql = $url ? "'" . $conexion->real_escape_string($url) . "'" : 'NULL';
        $total_destinatarios = intval($total_destinatarios);
        $total_exitosos      = intval($total_exitosos);

        $sql = "INSERT INTO push_notificaciones_enviadas (titulo, mensaje, url, total_destinatarios, total_exitosos)
                VALUES ('$titulo', '$mensaje', $url_sql, $total_destinatarios, $total_exitosos)";
        return ejecutarConsulta_retornarID($sql);
    }

    public function listar_envios($limite = 20)
    {
        $limite = intval($limite);
        $sql = "SELECT * FROM push_notificaciones_enviadas ORDER BY fecha_envio DESC LIMIT $limite";
        $res = ejecutarConsulta($sql);
        $filas = [];
        while ($row = $res->fetch_assoc()) { $filas[] = $row; }
        return $filas;
    }
}
