<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Lectura_diaria
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	public function listar_lecturas()
    {
    	$sql="SELECT * FROM lectura_diaria ORDER BY fecha DESC"; 
    	return ejecutarConsulta($sql);  
    }

    public function guardar_lectura($fecha,$cita_biblica,$link)
    {
    	$sql="INSERT INTO lectura_diaria (fecha,cita,link_cita) VALUES ('$fecha','$cita_biblica','$link')"; 
    	return ejecutarConsulta($sql);  
    }
	

}

?>