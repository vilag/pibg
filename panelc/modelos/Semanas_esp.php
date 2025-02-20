<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Semanas_esp
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	public function guardar_activ_sem($fecha1,$fecha2,$nombre,$nombre_corto,$detalle,$imagen)
    {
    	$sql="INSERT INTO actividades_destacadas (fecha1, fecha2, nombre, nombre_corto, detalle, imagen) VALUES ('$fecha1','$fecha2','$nombre', '$nombre_corto','$detalle','$imagen')"; 
    	return ejecutarConsulta($sql);  
    }

    public function listar_activ_sem_esp()
    {
    	$sql="SELECT * FROM actividades_destacadas ORDER BY fecha1 DESC"; 
    	return ejecutarConsulta($sql);  
    }
	

}

?>