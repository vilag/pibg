<?php
require "../config/Conexion.php";

class Predicaciones
{
    public function listar_sermones()
    {
        $sql = "SELECT * FROM sermones ORDER BY idsermones DESC";
        return ejecutarConsulta($sql);
    }

    public function get_sermon($id)
    {
        $id  = (int)$id;
        $sql = "SELECT * FROM sermones WHERE idsermones = $id LIMIT 1";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function guardar_sermon($nom_sermon, $fecha_eti, $predicador, $actividad, $categoria, $serie_id, $orden_serie, $imagen, $predicacion)
    {
        global $conexion;
        $nom_sermon  = limpiarCadena($nom_sermon);
        $fecha_eti   = limpiarCadena($fecha_eti);
        $predicador  = limpiarCadena($predicador);
        $actividad   = limpiarCadena($actividad);
        $categoria   = (int)$categoria;
        $serie_val   = ($serie_id > 0) ? (int)$serie_id : 'NULL';
        $orden_serie = (int)$orden_serie;
        $imagen      = limpiarCadena($imagen);
        $predicacion = mysqli_real_escape_string($conexion, $predicacion);
        $sql = "INSERT INTO sermones(nom_sermon,fecha_eti,predicador,actividad,categoria,serie_id,orden_serie,imagen,predicacion)
                VALUES('$nom_sermon','$fecha_eti','$predicador','$actividad',$categoria,$serie_val,$orden_serie,'$imagen','$predicacion')";
        return ejecutarConsulta_retornarID($sql);
    }

    public function actualizar_sermon($idsermones, $nom_sermon, $fecha_eti, $predicador, $actividad, $categoria, $serie_id, $orden_serie, $imagen, $predicacion)
    {
        global $conexion;
        $idsermones  = (int)$idsermones;
        $nom_sermon  = limpiarCadena($nom_sermon);
        $fecha_eti   = limpiarCadena($fecha_eti);
        $predicador  = limpiarCadena($predicador);
        $actividad   = limpiarCadena($actividad);
        $categoria   = (int)$categoria;
        $serie_val   = ($serie_id > 0) ? (int)$serie_id : 'NULL';
        $orden_serie = (int)$orden_serie;
        $imagen      = limpiarCadena($imagen);
        $predicacion = mysqli_real_escape_string($conexion, $predicacion);
        $sql = "UPDATE sermones SET nom_sermon='$nom_sermon',fecha_eti='$fecha_eti',predicador='$predicador',actividad='$actividad',
                categoria=$categoria,serie_id=$serie_val,orden_serie=$orden_serie,imagen='$imagen',predicacion='$predicacion'
                WHERE idsermones=$idsermones";
        return ejecutarConsulta($sql);
    }

    public function borrar_sermon($idsermones)
    {
        $idsermones = (int)$idsermones;
        $sql = "DELETE FROM sermones WHERE idsermones = $idsermones";
        return ejecutarConsulta($sql);
    }

    public function listar_categorias()
    {
        $sql = "SELECT * FROM cat_sermones ORDER BY 1 ASC";
        return ejecutarConsulta($sql);
    }

    public function guardar_categoria($nombre)
    {
        $nombre = limpiarCadena($nombre);
        $sql    = "INSERT INTO cat_sermones(nombre) VALUES('$nombre')";
        return ejecutarConsulta_retornarID($sql);
    }

    public function borrar_categoria($idcat)
    {
        $idcat = (int)$idcat;
        $sql   = "DELETE FROM cat_sermones WHERE idcat_sermones = $idcat";
        return ejecutarConsulta($sql);
    }

    public function listar_series_activas()
    {
        $sql = "SELECT idserie, nombre FROM series_especiales WHERE estatus = 1 ORDER BY nombre ASC";
        return ejecutarConsulta($sql);
    }
}
?>
