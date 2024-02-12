<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Bach
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	public function listar_obras()
	{
		$sql="SELECT * FROM bach_obras ORDER BY nombre ASC";
		//return ejecutarConsultaSimpleFila($sql);
		return ejecutarConsulta($sql);			
	}
    public function guardar_obra($nombre,$autor)
	{
		$sql="INSERT INTO bach_obras (nombre, autor) VALUES ('$nombre', '$autor')";
		//return ejecutarConsultaSimpleFila($sql);
		return ejecutarConsulta($sql);			
	}

    public function listar_voces($idobra)
	{
		$sql="SELECT * FROM bach_voces WHERE idobra='$idobra'";
		//return ejecutarConsultaSimpleFila($sql);
		return ejecutarConsulta($sql);			
	}

    public function guardar_voz($idobra,$voz,$archivo_audio)
	{
		$sql="INSERT INTO bach_voces (idobra, voz, enlace) VALUES ('$idobra', '$voz', '$archivo_audio')";
		//return ejecutarConsultaSimpleFila($sql);
		return ejecutarConsulta($sql);			
	}

}

?>