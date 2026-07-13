<?php
require "../config/Conexion.php";

class Banners_publicitarios
{
    public function listar()
    {
        return ejecutarConsulta(
            "SELECT id, nombre, ancho_px, alto_px, unidad_original, ancho_original, alto_original, imagen_final_base64, activo, creado_en, actualizado_en
             FROM banners_publicitarios
             WHERE activo = 1
             ORDER BY id DESC"
        );
    }

    public function get_uno($id)
    {
        $id = (int)$id;
        return ejecutarConsultaSimpleFila("SELECT * FROM banners_publicitarios WHERE id = $id LIMIT 1");
    }

    public function crear($nombre, $ancho_px, $alto_px, $unidad_original, $ancho_original, $alto_original, $diseno_json, $imagen_final_base64)
    {
        global $conexion;
        $nombre               = limpiarCadena($nombre);
        $ancho_px             = (int)$ancho_px;
        $alto_px              = (int)$alto_px;
        $unidad_original      = $unidad_original === 'cm' ? 'cm' : 'px';
        $ancho_original       = $ancho_original !== null && $ancho_original !== '' ? (float)$ancho_original : 'NULL';
        $alto_original        = $alto_original  !== null && $alto_original  !== '' ? (float)$alto_original  : 'NULL';
        $diseno_json          = mysqli_real_escape_string($conexion, $diseno_json);
        $imagen_final_base64  = mysqli_real_escape_string($conexion, $imagen_final_base64);

        $sql = "INSERT INTO banners_publicitarios
                    (nombre, ancho_px, alto_px, unidad_original, ancho_original, alto_original, diseno_json, imagen_final_base64)
                VALUES
                    ('$nombre', $ancho_px, $alto_px, '$unidad_original', $ancho_original, $alto_original, '$diseno_json', '$imagen_final_base64')";
        return ejecutarConsulta_retornarID($sql);
    }

    public function actualizar_uno($id, $nombre, $ancho_px, $alto_px, $unidad_original, $ancho_original, $alto_original, $diseno_json, $imagen_final_base64)
    {
        global $conexion;
        $id                   = (int)$id;
        $nombre               = limpiarCadena($nombre);
        $ancho_px             = (int)$ancho_px;
        $alto_px              = (int)$alto_px;
        $unidad_original      = $unidad_original === 'cm' ? 'cm' : 'px';
        $ancho_original       = $ancho_original !== null && $ancho_original !== '' ? (float)$ancho_original : 'NULL';
        $alto_original        = $alto_original  !== null && $alto_original  !== '' ? (float)$alto_original  : 'NULL';
        $diseno_json          = mysqli_real_escape_string($conexion, $diseno_json);
        $imagen_final_base64  = mysqli_real_escape_string($conexion, $imagen_final_base64);

        $sql = "UPDATE banners_publicitarios SET
                    nombre               = '$nombre',
                    ancho_px             = $ancho_px,
                    alto_px              = $alto_px,
                    unidad_original      = '$unidad_original',
                    ancho_original       = $ancho_original,
                    alto_original        = $alto_original,
                    diseno_json          = '$diseno_json',
                    imagen_final_base64  = '$imagen_final_base64'
                WHERE id = $id";
        return ejecutarConsulta($sql);
    }

    public function borrar($id)
    {
        $id = (int)$id;
        return ejecutarConsulta("UPDATE banners_publicitarios SET activo = 0 WHERE id = $id");
    }
}
?>
