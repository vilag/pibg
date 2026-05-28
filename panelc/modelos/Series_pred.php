<?php
require "../config/Conexion.php";

class Series_pred
{
    public function listar_series()
    {
        $sql = "SELECT se.*,
                       (SELECT COUNT(*) FROM sermones s WHERE s.serie_id = se.idserie) AS total_sermones
                FROM series_especiales se
                ORDER BY se.idserie DESC";
        return ejecutarConsulta($sql);
    }

    public function get_serie($id)
    {
        $id  = (int)$id;
        $sql = "SELECT * FROM series_especiales WHERE idserie = $id LIMIT 1";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function guardar_serie($nombre, $descripcion, $fecha_inicio, $fecha_fin, $imagen)
    {
        $nombre           = limpiarCadena($nombre);
        $descripcion      = limpiarCadena($descripcion);
        $fecha_inicio_val = $fecha_inicio ? "'$fecha_inicio'" : 'NULL';
        $fecha_fin_val    = $fecha_fin    ? "'$fecha_fin'"    : 'NULL';
        $imagen           = limpiarCadena($imagen);
        $sql = "INSERT INTO series_especiales(nombre,descripcion,fecha_inicio,fecha_fin,imagen)
                VALUES('$nombre','$descripcion',$fecha_inicio_val,$fecha_fin_val,'$imagen')";
        return ejecutarConsulta_retornarID($sql);
    }

    public function actualizar_serie($idserie, $nombre, $descripcion, $fecha_inicio, $fecha_fin, $imagen, $estatus)
    {
        $idserie          = (int)$idserie;
        $nombre           = limpiarCadena($nombre);
        $descripcion      = limpiarCadena($descripcion);
        $fecha_inicio_val = $fecha_inicio ? "'$fecha_inicio'" : 'NULL';
        $fecha_fin_val    = $fecha_fin    ? "'$fecha_fin'"    : 'NULL';
        $imagen           = limpiarCadena($imagen);
        $estatus          = (int)$estatus;
        $sql = "UPDATE series_especiales
                SET nombre='$nombre',descripcion='$descripcion',fecha_inicio=$fecha_inicio_val,
                    fecha_fin=$fecha_fin_val,imagen='$imagen',estatus=$estatus
                WHERE idserie=$idserie";
        return ejecutarConsulta($sql);
    }

    public function borrar_serie($idserie)
    {
        $idserie = (int)$idserie;
        ejecutarConsulta("UPDATE sermones SET serie_id=NULL, orden_serie=0 WHERE serie_id=$idserie");
        $sql = "DELETE FROM series_especiales WHERE idserie=$idserie";
        return ejecutarConsulta($sql);
    }

    public function listar_sermones_de_serie($idserie)
    {
        $idserie = (int)$idserie;
        $sql = "SELECT idsermones, nom_sermon, fecha_eti, predicador, actividad, orden_serie, imagen
                FROM sermones WHERE serie_id = $idserie ORDER BY orden_serie ASC, idsermones ASC";
        return ejecutarConsulta($sql);
    }
}
?>
