<?php
require_once __DIR__ . '/../config/Conexion.php';

Class Push_eventos
{
    public function __construct() {}

    public function obtener($clave)
    {
        global $conexion;
        $clave = $conexion->real_escape_string($clave);
        $sql = "SELECT * FROM push_eventos_config WHERE clave='$clave' LIMIT 1";
        $res = ejecutarConsulta($sql);
        return ($res && $res->num_rows > 0) ? $res->fetch_assoc() : null;
    }

    public function listar()
    {
        $sql = "SELECT * FROM push_eventos_config ORDER BY clave ASC";
        $res = ejecutarConsulta($sql);
        $filas = [];
        while ($row = $res->fetch_assoc()) { $filas[] = $row; }
        return $filas;
    }

    // Solo actualiza — el conjunto de claves es fijo, atado a los puntos del
    // codigo que llaman a push_disparar_evento(); el panel no crea eventos.
    public function guardar($clave, $activo, $destino, $titulo, $mensaje)
    {
        global $conexion;
        $clave   = $conexion->real_escape_string($clave);
        $activo  = intval($activo) ? 1 : 0;
        $destino = $destino === 'admin' ? 'admin' : 'todos';
        $titulo  = $conexion->real_escape_string(trim($titulo));
        $mensaje = $conexion->real_escape_string(trim($mensaje));

        $sql = "UPDATE push_eventos_config
                SET activo=$activo, destino='$destino', titulo='$titulo', mensaje='$mensaje'
                WHERE clave='$clave'";
        return ejecutarConsulta($sql);
    }
}
