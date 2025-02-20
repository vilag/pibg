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
    	$sql="SELECT idcal, DATE(fecha_hora) as fecha, TIME(fecha_hora) as hora, dia_nom, nom_activ, tipo, tema FROM calendario WHERE DATE(fecha_hora)>=NOW() ORDER BY fecha_hora DESC"; 
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

	public function guardar_dia_calendario($fecha_hora,$dia,$nom_actividad,$tema_actividad,$tipo_act)
    {
    	$sql="INSERT INTO calendario(fecha_hora, dia_nom, nom_activ, tema, tipo) VALUES('$fecha_hora','$dia','$nom_actividad', '$tema_actividad', '$tipo_act')"; 
    	return ejecutarConsulta($sql);  
    }

	public function borrar_dia($idcal)
    {
    	$sql="DELETE FROM calendario WHERE idcal='$idcal'"; 
    	return ejecutarConsulta($sql);  
    }

}

?>