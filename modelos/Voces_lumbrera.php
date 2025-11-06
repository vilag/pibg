<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Voces_lumbrera
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}


    public function listar_obras($nombre)
	{
		$sql="SELECT * FROM lumbrera_obras WHERE nombre LIKE '%".$nombre."%'";
		//return ejecutarConsultaSimpleFila($sql);
		return ejecutarConsulta($sql);			
	}

    public function listar_voces($idobra)
	{
		$sql="SELECT * FROM lumbrera_voces WHERE idobra='$idobra'";
		//return ejecutarConsultaSimpleFila($sql);
		return ejecutarConsulta($sql);			
	}

    public function listar_obras_1()
	{
		$sql="SELECT * FROM lumbrera_obras ORDER BY nombre ASC";
		//return ejecutarConsultaSimpleFila($sql);
		return ejecutarConsulta($sql);			
	}
   
}

?>