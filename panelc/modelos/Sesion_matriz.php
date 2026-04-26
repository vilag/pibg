<?php
require "../config/Conexion.php";

class Sesion_matriz {

    // Inserta registros desde Excel: nombres y checks (hasta 1000 columnas)
    public function insertar_matriz_excel($idsesion, $matriz, $numCols = 52)
    {
        global $conexion;
        $idsesion = (int)$idsesion;
        $numCols = (int)$numCols;
        if ($numCols < 1 || $numCols > 1000) $numCols = 52;
        ejecutarConsulta("DELETE FROM sesion_registro WHERE idsesion = $idsesion");
        foreach ($matriz as $fila) {
            $nombre = isset($fila['nombre']) ? trim($fila['nombre']) : '';
            if ($nombre === '') continue;
            $nombre_esc = $conexion->real_escape_string($nombre);
            $cols = [];
            for ($i = 1; $i <= $numCols; $i++) {
                $val = isset($fila['checks'][$i-1]) ? (int)$fila['checks'][$i-1] : 0;
                $colName = 'col_' . str_pad($i, 3, '0', STR_PAD_LEFT);
                $cols[] = "$colName=$val";
            }
            $cols_sql = count($cols) ? ', ' . implode(', ', $cols) : '';
            $sql = "INSERT INTO sesion_registro (idsesion, nombre$cols_sql) VALUES ($idsesion, '$nombre_esc'";
            // Agregar valores para las columnas
            for ($i = 1; $i <= $numCols; $i++) {
                $val = isset($fila['checks'][$i-1]) ? (int)$fila['checks'][$i-1] : 0;
                $sql .= ", $val";
            }
            $sql .= ")";
            // Reemplazar el insert para que funcione con columnas dinámicas
            $campos = "idsesion, nombre";
            $valores = "$idsesion, '$nombre_esc'";
            for ($i = 1; $i <= $numCols; $i++) {
                $colName = 'col_' . str_pad($i, 3, '0', STR_PAD_LEFT);
                $campos .= ", $colName";
                $val = isset($fila['checks'][$i-1]) ? (int)$fila['checks'][$i-1] : 0;
                $valores .= ", $val";
            }
            $sql = "INSERT INTO sesion_registro ($campos) VALUES ($valores)";
            ejecutarConsulta($sql);
        }
        return true;
    }
{
    // Guardar el número de columnas para la sesión
    public function actualizar_columnas_sesion($idsesion, $columnas)
    {
        global $conexion;
        $idsesion = (int)$idsesion;
        $columnas = (int)$columnas;
        if ($columnas < 1 || $columnas > 1000) return false;
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
        if ($columnas < 1 || $columnas > 1000) $columnas = 52;
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

        if ($col_num < 1 || $col_num > 1000) return false;

        $col_name = 'col_' . str_pad($col_num, 2, '0', STR_PAD_LEFT);
        $sql = "UPDATE sesion_registro SET $col_name = $valor WHERE idregistro = $idregistro";
        return ejecutarConsulta($sql);
    }
}
