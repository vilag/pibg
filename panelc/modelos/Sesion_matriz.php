<?php
require "../config/Conexion.php";

class Sesion_matriz {
        // Devuelve todas las sesiones de la tabla sesion_lista
        public function listar_sesiones()
        {
            $sql = "SELECT * FROM sesion_lista ORDER BY idsesion DESC";
            return ejecutarConsulta($sql);
        }
    // Guarda la matriz como JSON en la columna matriz_json
    public function guardar_matriz_json($idsesion, $matriz_json)
    {
        global $conexion;
        $idsesion = (int)$idsesion;
        $matriz_json_esc = $conexion->real_escape_string($matriz_json);
        $sql = "UPDATE sesion_lista SET matriz_json='$matriz_json_esc' WHERE idsesion=$idsesion";
        return ejecutarConsulta($sql);
    }

    // Obtiene la matriz JSON de la columna matriz_json
    public function obtener_matriz_json($idsesion)
    {
        $idsesion = (int)$idsesion;
        $sql = "SELECT matriz_json FROM sesion_lista WHERE idsesion = $idsesion";
        $row = ejecutarConsultaSimpleFila($sql);
        return $row ? $row['matriz_json'] : null;
    }

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

        // ---- CELDA ----
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
