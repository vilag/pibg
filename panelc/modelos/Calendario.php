<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Calendario
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	public function listar_dias()
    {
    	$sql="SELECT DATE(fecha_hora) as fecha, TIME(fecha_hora) as hora, dia_nom, nom_activ FROM calendario WHERE DATE(fecha_hora)>=NOW()"; 
    	return ejecutarConsulta($sql);  
    }
	public function listar_horas()
    {
    	$sql="SELECT idcal, TIME(fecha_hora) as hora FROM calendario GROUP BY TIME(fecha_hora) ORDER BY TIME(fecha_hora) asc"; 
    	return ejecutarConsulta($sql);  
    }

	public function listar_nombres()
    {
    	$sql="SELECT idcal, nom_activ FROM calendario GROUP BY nom_activ ORDER BY nom_activ asc"; 
    	return ejecutarConsulta($sql);  
    }

	public function listar_activ_sem()
    {
    	$sql="SELECT * FROM activ_sem"; 
    	return ejecutarConsulta($sql);  
    }

	public function guardar_dia_calendario($fecha_hora,$dia,$nom_actividad)
    {
    	$sql="INSERT INTO calendario(fecha_hora, dia_nom, nom_activ) VALUES('$fecha_hora','$dia','$nom_actividad')"; 
    	return ejecutarConsulta($sql);  
    }

}

?>