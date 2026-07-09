<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Semanas_esp
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	public function guardar_activ_sem($fecha1,$fecha2,$nombre,$nombre_corto,$detalle,$imagen,$texto_banner='',$video_url='')
    {
    	$sql="INSERT INTO actividades_destacadas (fecha1, fecha2, nombre, nombre_corto, detalle, imagen, texto_banner, video_url) VALUES ('$fecha1','$fecha2','$nombre','$nombre_corto','$detalle','$imagen','$texto_banner','$video_url')";
    	return ejecutarConsulta($sql);
    }

    public function listar_activ_sem_esp()
    {
    	$sql="SELECT * FROM actividades_destacadas ORDER BY fecha1 DESC"; 
    	return ejecutarConsulta($sql);  
    }
	
	public function obtener_activ($idactiv)
    {
    	$sql="SELECT * FROM actividades_destacadas WHERE idactiv='$idactiv'";
    	return ejecutarConsulta($sql);
    }

    public function editar_activ_sem($idactiv,$fecha1,$fecha2,$nombre,$nombre_corto,$detalle,$imagen,$texto_banner='',$video_url='')
    {
    	$sql="UPDATE actividades_destacadas SET fecha1='$fecha1', fecha2='$fecha2', nombre='$nombre', nombre_corto='$nombre_corto', detalle='$detalle', imagen='$imagen', texto_banner='$texto_banner', video_url='$video_url' WHERE idactiv='$idactiv'";
    	return ejecutarConsulta($sql);
    }

	public function borrar_activ($idactiv)
    {
    	$sql="DELETE FROM actividades_destacadas WHERE idactiv='$idactiv'";
    	return ejecutarConsulta($sql);
    }

}

?>