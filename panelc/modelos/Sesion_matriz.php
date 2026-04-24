<?php
require "../config/Conexion.php";

class Sesion_matriz
{
    // Guardar el número de columnas para la sesión
    public function actualizar_columnas_sesion($idsesion, $columnas)
    {
        global $conexion;
        $idsesion = (int)$idsesion;
        $columnas = (int)$columnas;
        if ($columnas < 1 || $columnas > 52) return false;
        $sql = "UPDATE sesion_lista SET columnas=$columnas WHERE idsesion=$idsesion";
        return ejecutarConsulta($sql);
    }
    public function __construct() {}

    /* ---- SESIONES ---- */

    public function listar_sesiones()
    {
        $sql = "SELECT s.*, COUNT(r.idregistro) AS total_registros
                FROM sesion_lista s
                LEFT JOIN sesion_registro r ON r.idsesion = s.idsesion
                GROUP BY s.idsesion
                ORDER BY s.fecha_creacion DESC";
        return ejecutarConsulta($sql);
    }

    public function obtener_sesion($idsesion)
    {
        $idsesion = (int)$idsesion;
        $sql = "SELECT * FROM sesion_lista WHERE idsesion = $idsesion";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function crear_sesion($nombre, $descripcion, $columnas = 52)
    {
        global $conexion;
        $nombre      = $conexion->real_escape_string(trim($nombre));
        $descripcion = $conexion->real_escape_string(trim($descripcion));
        $columnas    = (int)$columnas;
        if ($columnas < 1 || $columnas > 52) $columnas = 52;
        $sql = "INSERT INTO sesion_lista (nombre, descripcion, columnas) VALUES ('$nombre', '$descripcion', $columnas)";
        ejecutarConsulta($sql);
        return $conexion->insert_id;
    }

    public function actualizar_sesion($idsesion, $nombre, $descripcion)
    {
        global $conexion;
        $idsesion    = (int)$idsesion;
        $nombre      = $conexion->real_escape_string(trim($nombre));
        $descripcion = $conexion->real_escape_string(trim($descripcion));
        $sql = "UPDATE sesion_lista SET nombre='$nombre', descripcion='$descripcion' WHERE idsesion=$idsesion";
        return ejecutarConsulta($sql);
    }

    public function eliminar_sesion($idsesion)
    {
        $idsesion = (int)$idsesion;
        $sql = "DELETE FROM sesion_lista WHERE idsesion = $idsesion";
        return ejecutarConsulta($sql);
    }

    /* ---- REGISTROS ---- */

    public function insertar_registros($idsesion, $nombres)
    {
        global $conexion;
        $idsesion = (int)$idsesion;
        // Eliminar registros previos de la sesión
        ejecutarConsulta("DELETE FROM sesion_registro WHERE idsesion = $idsesion");
        foreach ($nombres as $nombre) {
            $nombre = trim($nombre);
            if ($nombre === '') continue;
            $nombre_esc = $conexion->real_escape_string($nombre);
            ejecutarConsulta("INSERT INTO sesion_registro (idsesion, nombre) VALUES ($idsesion, '$nombre_esc')");
        }
        return true;
    }

    public function listar_registros($idsesion)
    {
        $idsesion = (int)$idsesion;
        $sql = "SELECT * FROM sesion_registro WHERE idsesion = $idsesion ORDER BY idregistro ASC";
        return ejecutarConsulta($sql);
    }

    /* ---- CELDA ---- */

    public function actualizar_celda($idregistro, $col_num, $valor)
    {
        $idregistro = (int)$idregistro;
        $col_num    = (int)$col_num;
        $valor      = (int)$valor; // 0 ó 1

        if ($col_num < 1 || $col_num > 52) return false;

        $col_name = 'col_' . str_pad($col_num, 2, '0', STR_PAD_LEFT);
        $sql = "UPDATE sesion_registro SET $col_name = $valor WHERE idregistro = $idregistro";
        return ejecutarConsulta($sql);
    }
}
