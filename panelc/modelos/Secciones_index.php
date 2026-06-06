<?php
require "../config/Conexion.php";

class Secciones_index
{
    /* ── VISIBILIDAD SECCIONES NATIVAS ─────────────────────── */

    public function listar_visibilidad()
    {
        return ejecutarConsulta("SELECT * FROM secciones_visibilidad ORDER BY clave");
    }

    public function toggle_visibilidad($clave, $activo)
    {
        global $conexion;
        $clave  = mysqli_real_escape_string($conexion, $clave);
        $activo = (int)$activo ? 1 : 0;
        return ejecutarConsulta(
            "UPDATE secciones_visibilidad SET activo=$activo WHERE clave='$clave'"
        );
    }

    /* ── SECCIONES PERSONALIZADAS ───────────────────────────── */

    public function listar()
    {
        return ejecutarConsulta(
            "SELECT * FROM secciones_index ORDER BY orden ASC, id ASC"
        );
    }

    public function get_uno($id)
    {
        $id = (int)$id;
        return ejecutarConsultaSimpleFila(
            "SELECT * FROM secciones_index WHERE id=$id LIMIT 1"
        );
    }

    public function crear($d)
    {
        global $conexion;
        $nombre       = limpiarCadena($d['nombre']);
        $estilo       = limpiarCadena($d['estilo']);
        $eyebrow      = limpiarCadena($d['eyebrow']);
        $titulo       = limpiarCadena($d['titulo']);
        $texto        = mysqli_real_escape_string($conexion, trim($d['texto']));
        $imagen_url   = limpiarCadena($d['imagen_url']);
        $fondo_oscuro = (int)($d['fondo_oscuro'] ?? 1);
        $btn_texto    = limpiarCadena($d['btn_texto']);
        $btn_url      = limpiarCadena($d['btn_url']);
        $orden        = (int)($d['orden'] ?? 0);

        $sql = "INSERT INTO secciones_index
                    (nombre,estilo,eyebrow,titulo,texto,imagen_url,fondo_oscuro,btn_texto,btn_url,orden,activo)
                VALUES
                    ('$nombre','$estilo','$eyebrow','$titulo','$texto','$imagen_url',
                     $fondo_oscuro,'$btn_texto','$btn_url',$orden,0)";
        return ejecutarConsulta_retornarID($sql);
    }

    public function actualizar($id, $d)
    {
        global $conexion;
        $id           = (int)$id;
        $nombre       = limpiarCadena($d['nombre']);
        $estilo       = limpiarCadena($d['estilo']);
        $eyebrow      = limpiarCadena($d['eyebrow']);
        $titulo       = limpiarCadena($d['titulo']);
        $texto        = mysqli_real_escape_string($conexion, trim($d['texto']));
        $imagen_url   = limpiarCadena($d['imagen_url']);
        $fondo_oscuro = (int)($d['fondo_oscuro'] ?? 1);
        $btn_texto    = limpiarCadena($d['btn_texto']);
        $btn_url      = limpiarCadena($d['btn_url']);
        $orden        = (int)($d['orden'] ?? 0);

        return ejecutarConsulta(
            "UPDATE secciones_index SET
                nombre='$nombre', estilo='$estilo', eyebrow='$eyebrow',
                titulo='$titulo', texto='$texto', imagen_url='$imagen_url',
                fondo_oscuro=$fondo_oscuro, btn_texto='$btn_texto',
                btn_url='$btn_url', orden=$orden
             WHERE id=$id"
        );
    }

    public function toggle_activo($id, $activo)
    {
        $id     = (int)$id;
        $activo = (int)$activo ? 1 : 0;
        return ejecutarConsulta(
            "UPDATE secciones_index SET activo=$activo WHERE id=$id"
        );
    }

    public function eliminar($id)
    {
        $id = (int)$id;
        return ejecutarConsulta("DELETE FROM secciones_index WHERE id=$id");
    }
}
