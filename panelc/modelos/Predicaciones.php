<?php
require "../config/Conexion.php";

class Predicaciones
{
    public function listar_sermones()
    {
        $sql = "SELECT s.*,
                    MIN(sc.idcat) AS cat_first,
                    GROUP_CONCAT(cat.nombre ORDER BY cat.nombre SEPARATOR ', ') AS categorias_nombres
                FROM sermones s
                LEFT JOIN sermon_categorias sc ON s.idsermones = sc.idsermones
                LEFT JOIN cat_sermones cat ON sc.idcat = cat.idcat_sermones
                GROUP BY s.idsermones
                ORDER BY s.idsermones DESC";
        return ejecutarConsulta($sql);
    }

    public function get_sermon($id)
    {
        $id  = (int)$id;
        $sql = "SELECT * FROM sermones WHERE idsermones = $id LIMIT 1";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function get_categorias_sermon($idsermones)
    {
        $idsermones = (int)$idsermones;
        $sql    = "SELECT idcat FROM sermon_categorias WHERE idsermones = $idsermones ORDER BY idcat";
        $result = ejecutarConsulta($sql);
        $cats   = [];
        while ($row = $result->fetch_assoc()) { $cats[] = (int)$row['idcat']; }
        return $cats;
    }

    public function sync_categorias_sermon($idsermones, $categorias)
    {
        $idsermones = (int)$idsermones;
        ejecutarConsulta("DELETE FROM sermon_categorias WHERE idsermones = $idsermones");
        foreach ($categorias as $idcat) {
            $idcat = (int)$idcat;
            if ($idcat > 0) {
                ejecutarConsulta("INSERT IGNORE INTO sermon_categorias (idsermones, idcat) VALUES ($idsermones, $idcat)");
            }
        }
    }

    public function guardar_sermon($nom_sermon, $fecha_eti, $predicador, $actividad, $categorias, $serie_id, $orden_serie, $imagen, $predicacion, $archivo_pred = '')
    {
        global $conexion;
        $nom_sermon   = limpiarCadena($nom_sermon);
        $fecha_eti    = limpiarCadena($fecha_eti);
        $predicador   = limpiarCadena($predicador);
        $actividad    = limpiarCadena($actividad);
        $categoria    = !empty($categorias) ? (int)$categorias[0] : 0;
        $serie_val    = ($serie_id > 0) ? (int)$serie_id : 'NULL';
        $orden_serie  = (int)$orden_serie;
        $imagen       = limpiarCadena($imagen);
        $predicacion  = mysqli_real_escape_string($conexion, $predicacion);
        $archivo_pred = limpiarCadena($archivo_pred);
        $sql = "INSERT INTO sermones(nom_sermon,fecha_eti,predicador,actividad,categoria,serie_id,orden_serie,imagen,predicacion,archivo_pred)
                VALUES('$nom_sermon','$fecha_eti','$predicador','$actividad',$categoria,$serie_val,$orden_serie,'$imagen','$predicacion','$archivo_pred')";
        $id = ejecutarConsulta_retornarID($sql);
        if ($id > 0) { $this->sync_categorias_sermon($id, $categorias); }
        return $id;
    }

    public function actualizar_sermon($idsermones, $nom_sermon, $fecha_eti, $predicador, $actividad, $categorias, $serie_id, $orden_serie, $imagen, $predicacion, $archivo_pred = '')
    {
        global $conexion;
        $idsermones   = (int)$idsermones;
        $nom_sermon   = limpiarCadena($nom_sermon);
        $fecha_eti    = limpiarCadena($fecha_eti);
        $predicador   = limpiarCadena($predicador);
        $actividad    = limpiarCadena($actividad);
        $categoria    = !empty($categorias) ? (int)$categorias[0] : 0;
        $serie_val    = ($serie_id > 0) ? (int)$serie_id : 'NULL';
        $orden_serie  = (int)$orden_serie;
        $imagen       = limpiarCadena($imagen);
        $predicacion  = mysqli_real_escape_string($conexion, $predicacion);
        $archivo_pred = limpiarCadena($archivo_pred);
        $sql = "UPDATE sermones SET nom_sermon='$nom_sermon',fecha_eti='$fecha_eti',predicador='$predicador',actividad='$actividad',
                categoria=$categoria,serie_id=$serie_val,orden_serie=$orden_serie,imagen='$imagen',predicacion='$predicacion',
                archivo_pred='$archivo_pred'
                WHERE idsermones=$idsermones";
        $ok = ejecutarConsulta($sql);
        $this->sync_categorias_sermon($idsermones, $categorias);
        return $ok;
    }

    public function borrar_sermon($idsermones)
    {
        $idsermones = (int)$idsermones;
        ejecutarConsulta("DELETE FROM sermon_categorias WHERE idsermones = $idsermones");
        return ejecutarConsulta("DELETE FROM sermones WHERE idsermones = $idsermones");
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
        ejecutarConsulta("DELETE FROM sermon_categorias WHERE idcat = $idcat");
        return ejecutarConsulta("DELETE FROM cat_sermones WHERE idcat_sermones = $idcat");
    }

    public function listar_series_activas()
    {
        $sql = "SELECT idserie, nombre FROM series_especiales WHERE estatus = 1 ORDER BY nombre ASC";
        return ejecutarConsulta($sql);
    }
}
?>
