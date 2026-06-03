<?php
require "../config/Conexion.php";

class Modal_bienvenida
{
    public function obtener()
    {
        return ejecutarConsultaSimpleFila("SELECT * FROM modal_bienvenida LIMIT 1");
    }

    public function actualizar($habilitado, $titulo, $mensaje, $video_espanol, $video_ingles, $video_koreano, $video_frances)
    {
        $habilitado    = (int)$habilitado;
        $titulo        = limpiarCadena($titulo);
        $mensaje       = limpiarCadena($mensaje);
        $video_espanol = limpiarCadena($video_espanol);
        $video_ingles  = limpiarCadena($video_ingles);
        $video_koreano = limpiarCadena($video_koreano);
        $video_frances = limpiarCadena($video_frances);
        $sql = "UPDATE modal_bienvenida SET
                    habilitado = $habilitado,
                    titulo     = '$titulo',
                    mensaje    = '$mensaje',
                    video_espanol = '$video_espanol',
                    video_ingles  = '$video_ingles',
                    video_koreano = '$video_koreano',
                    video_frances = '$video_frances'
                LIMIT 1";
        return ejecutarConsulta($sql);
    }
}
?>
