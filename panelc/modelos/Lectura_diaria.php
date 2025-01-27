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
    	$sql="SELECT DISTINCT fecha  FROM lectura_diaria ORDER BY fecha DESC"; 
    	return ejecutarConsulta($sql);  
    }

	public function listar_lecturas_fechas($fecha)
    {
    	$sql="SELECT * FROM lectura_diaria WHERE fecha='$fecha'"; 
    	return ejecutarConsulta($sql);  
    }

    public function guardar_lectura($fecha,$cita_biblica,$link)
    {
    	$sql="INSERT INTO lectura_diaria (fecha,cita,link_cita) VALUES ('$fecha','$cita_biblica','$link')"; 
    	return ejecutarConsulta($sql);  
    }

	public function guardar_cita_send($fecha,$tipo_cita_biblica,$libro_cita_biblica,$capitulo_cita_biblica,$vers1_cita_biblica,$vers2_cita_biblica)
    {
    	$sql="INSERT INTO lectura_diaria (fecha,tipo,libro,capitulo,versiculo1,versiculo2) VALUES ('$fecha','$tipo_cita_biblica','$libro_cita_biblica','$capitulo_cita_biblica','$vers1_cita_biblica','$vers2_cita_biblica')"; 
    	return ejecutarConsulta($sql);  
    }

	public function borrar_lectura($idlectura)
    {
    	$sql="DELETE FROM lectura_diaria WHERE idlectura='$idlectura'"; 
    	return ejecutarConsulta($sql);  
    }
	

}

?>