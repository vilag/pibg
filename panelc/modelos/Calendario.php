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

}

?>