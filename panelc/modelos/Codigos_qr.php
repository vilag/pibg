<?php
require "../config/Conexion.php";

Class Codigos_qr
{
    public function __construct() {}

    public function guardar_qr($nombre, $contenido, $color_frente, $color_fondo, $estilo_puntos, $nivel_correccion, $imagen_base64)
    {
        global $conexion;
        $imagen_base64 = $conexion->real_escape_string($imagen_base64);
        $sql = "INSERT INTO qr_codes (nombre, contenido, color_frente, color_fondo, estilo_puntos, nivel_correccion, imagen_base64)
                VALUES ('$nombre','$contenido','$color_frente','$color_fondo','$estilo_puntos','$nivel_correccion','$imagen_base64')";
        return ejecutarConsulta_retornarID($sql);
    }

    public function listar_qr()
    {
        $sql = "SELECT id, nombre, contenido, color_frente, color_fondo, estilo_puntos, nivel_correccion, imagen_base64, fecha_creacion
                FROM qr_codes ORDER BY fecha_creacion DESC";
        return ejecutarConsulta($sql);
    }

    public function obtener_qr($id)
    {
        $sql = "SELECT * FROM qr_codes WHERE id='$id'";
        return ejecutarConsulta($sql);
    }

    public function borrar_qr($id)
    {
        $sql = "DELETE FROM qr_codes WHERE id='$id'";
        return ejecutarConsulta($sql);
    }
}
?>
