<?php
require "../config/Conexion.php";

class Modal_bienvenida
{
    public function listar()
    {
        return ejecutarConsulta("SELECT * FROM modal_bienvenida ORDER BY id DESC");
    }

    public function get_uno($id)
    {
        $id = (int)$id;
        return ejecutarConsultaSimpleFila("SELECT * FROM modal_bienvenida WHERE id = $id LIMIT 1");
    }

    public function crear($nombre, $titulo, $mensaje, $tiene_selector, $tipo_directo, $url_directo, $opciones_json)
    {
        global $conexion;
        $nombre         = limpiarCadena($nombre);
        $titulo         = limpiarCadena($titulo);
        $mensaje        = limpiarCadena($mensaje);
        $tiene_selector = (int)$tiene_selector;
        $tipo_directo   = limpiarCadena($tipo_directo);
        $url_directo    = limpiarCadena($url_directo);
        $opciones_json  = mysqli_real_escape_string($conexion, $opciones_json);
        $sql = "INSERT INTO modal_bienvenida
                    (nombre, habilitado, titulo, mensaje, tiene_selector, tipo_directo, url_directo, opciones)
                VALUES
                    ('$nombre', 0, '$titulo', '$mensaje', $tiene_selector, '$tipo_directo', '$url_directo', '$opciones_json')";
        return ejecutarConsulta_retornarID($sql);
    }

    public function actualizar_uno($id, $nombre, $titulo, $mensaje, $tiene_selector, $tipo_directo, $url_directo, $opciones_json)
    {
        global $conexion;
        $id             = (int)$id;
        $nombre         = limpiarCadena($nombre);
        $titulo         = limpiarCadena($titulo);
        $mensaje        = limpiarCadena($mensaje);
        $tiene_selector = (int)$tiene_selector;
        $tipo_directo   = limpiarCadena($tipo_directo);
        $url_directo    = limpiarCadena($url_directo);
        $opciones_json  = mysqli_real_escape_string($conexion, $opciones_json);
        $sql = "UPDATE modal_bienvenida SET
                    nombre         = '$nombre',
                    titulo         = '$titulo',
                    mensaje        = '$mensaje',
                    tiene_selector = $tiene_selector,
                    tipo_directo   = '$tipo_directo',
                    url_directo    = '$url_directo',
                    opciones       = '$opciones_json'
                WHERE id = $id";
        return ejecutarConsulta($sql);
    }

    public function activar($id)
    {
        $id  = (int)$id;
        return ejecutarConsulta("UPDATE modal_bienvenida SET habilitado = IF(id = $id, 1, 0)");
    }

    public function desactivar($id)
    {
        $id  = (int)$id;
        return ejecutarConsulta("UPDATE modal_bienvenida SET habilitado = 0 WHERE id = $id");
    }

    public function borrar($id)
    {
        $id  = (int)$id;
        return ejecutarConsulta("DELETE FROM modal_bienvenida WHERE id = $id");
    }
}
?>
