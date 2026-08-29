<?php
require "../config/Conexion.php";

class Predicaciones
{
    public function __construct()
    {
        // Crea la tabla de relación si aún no existe y migra datos previos
        ejecutarConsulta("CREATE TABLE IF NOT EXISTS sermon_categorias (
            idsermones INT NOT NULL,
            idcat      INT NOT NULL,
            PRIMARY KEY (idsermones, idcat),
            KEY idx_sc_idcat (idcat)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        ejecutarConsulta("INSERT IGNORE INTO sermon_categorias (idsermones, idcat)
            SELECT idsermones, categoria FROM sermones
            WHERE categoria IS NOT NULL AND categoria > 0");
    }

    public function listar_sermones()
    {
        // Subconsulta correlacionada: evita GROUP BY y es compatible con cualquier modo SQL
        $sql = "SELECT s.*,
                    (SELECT GROUP_CONCAT(c.nombre ORDER BY c.nombre SEPARATOR ', ')
                     FROM sermon_categorias sc
                     LEFT JOIN cat_sermones c ON sc.idcat = c.id_cat
                     WHERE sc.idsermones = s.idsermones) AS categorias_nombres
                FROM sermones s
                ORDER BY s.idsermones DESC";
        $result = ejecutarConsulta($sql);
        // Si falla (tabla aún no existe), usar consulta simple sin categorías
        if (!$result) {
            $result = ejecutarConsulta("SELECT * FROM sermones ORDER BY idsermones DESC");
        }
        return $result;
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
        return ejecutarConsulta("DELETE FROM cat_sermones WHERE id_cat = $idcat");
    }

    public function listar_series_activas()
    {
        $sql = "SELECT idserie, nombre FROM series_especiales WHERE estatus = 1 ORDER BY nombre ASC";
        return ejecutarConsulta($sql);
    }
}
?>
