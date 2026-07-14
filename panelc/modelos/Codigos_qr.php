<?php
require "../config/Conexion.php";

Class Codigos_qr
{
    public function __construct() {}

    public function guardar_qr($nombre, $contenido, $color_frente, $color_fondo, $estilo_puntos, $nivel_correccion, $imagen_base64, $idactiv_relacionada = null)
    {
        global $conexion;
        $imagen_base64 = $conexion->real_escape_string($imagen_base64);
        $idactiv_sql = $idactiv_relacionada !== null ? intval($idactiv_relacionada) : 'NULL';
        $sql = "INSERT INTO qr_codes (nombre, contenido, color_frente, color_fondo, estilo_puntos, nivel_correccion, imagen_base64, idactiv_relacionada)
                VALUES ('$nombre','$contenido','$color_frente','$color_fondo','$estilo_puntos','$nivel_correccion','$imagen_base64',$idactiv_sql)";
        return ejecutarConsulta_retornarID($sql);
    }

    public function editar_qr($id, $nombre, $contenido, $color_frente, $color_fondo, $estilo_puntos, $nivel_correccion, $imagen_base64)
    {
        global $conexion;
        $id            = intval($id);
        $imagen_base64 = $conexion->real_escape_string($imagen_base64);
        $sql = "UPDATE qr_codes SET
                    nombre='$nombre', contenido='$contenido', color_frente='$color_frente',
                    color_fondo='$color_fondo', estilo_puntos='$estilo_puntos',
                    nivel_correccion='$nivel_correccion', imagen_base64='$imagen_base64'
                WHERE id='$id'";
        return ejecutarConsulta($sql);
    }

    public function obtener_qr_por_actividad($idactiv)
    {
        $idactiv = intval($idactiv);
        return ejecutarConsulta("SELECT * FROM qr_codes WHERE idactiv_relacionada='$idactiv' LIMIT 1");
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
