<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Lumbrera
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	public function listar_obras()
	{
		$sql="SELECT * FROM lumbrera_obras ORDER BY nombre ASC";
		//return ejecutarConsultaSimpleFila($sql);
		return ejecutarConsulta($sql);			
	}
    public function guardar_obra($nombre,$autor)
	{
		$sql="INSERT INTO lumbrera_obras (nombre, autor) VALUES ('$nombre', '$autor')";
		//return ejecutarConsultaSimpleFila($sql);
		return ejecutarConsulta($sql);			
	}
	public function actualizar_obra($nombre,$autor,$idobra)
	{
		$sql="UPDATE lumbrera_obras SET nombre='$nombre', autor='$autor' WHERE idobra = '$idobra'";
		//return ejecutarConsultaSimpleFila($sql);
		return ejecutarConsulta($sql);			
	}

    public function listar_voces($idobra)
	{
		$sql="SELECT * FROM lumbrera_voces WHERE idobra='$idobra'";
		//return ejecutarConsultaSimpleFila($sql);
		return ejecutarConsulta($sql);			
	}

    public function guardar_voz($idobra,$voz,$archivo_audio)
	{
		$sql="INSERT INTO lumbrera_voces (idobra, nombre, enlace) VALUES ('$idobra', '$voz', '$archivo_audio')";
		//return ejecutarConsultaSimpleFila($sql);
		return ejecutarConsulta($sql);			
	}

	public function eliminar_obra($idobra)
	{
		$sql_1="DELETE FROM lumbrera_obras WHERE idobra='$idobra'";
		//return ejecutarConsultaSimpleFila($sql);
		ejecutarConsulta($sql_1);
		
		$sql="DELETE FROM lumbrera_voces WHERE idobra='$idobra'";
		//return ejecutarConsultaSimpleFila($sql);
		return ejecutarConsulta($sql);
	}

	public function actualizar_voz($idvoz,$voz,$archivo_audio)
	{
		$sql="UPDATE lumbrera_voces SET nombre='$voz', enlace='$archivo_audio' WHERE idvoz = '$idvoz'";
		//return ejecutarConsultaSimpleFila($sql);
		return ejecutarConsulta($sql);			
	}

	public function eliminar_voz($idvoz)
	{
		$sql_1="DELETE FROM lumbrera_voces WHERE idvoz='$idvoz'";
		//return ejecutarConsultaSimpleFila($sql);
		return ejecutarConsulta($sql_1);

	}

	public function subir_doc_voz($nom,$idvoz_upload,$fecha_reg_part)
	{
		$sql_1="INSERT INTO partituras_lumbrera (idvoz,nombre,fecha) VALUES('$idvoz_upload','$nom','$fecha_reg_part')";
		//return ejecutarConsultaSimpleFila($sql);
		return ejecutarConsulta($sql_1);

	}

	public function lista_partituras($idvoz)
	{
		$sql_1="SELECT * FROM partituras_lumbrera WHERE idvoz='$idvoz' ORDER BY nombre DESC";
		//return ejecutarConsultaSimpleFila($sql);
		return ejecutarConsulta($sql_1);

	}

	public function eliminar_partitura($idpartitura)
	{
		$sql_1="DELETE FROM partituras_lumbrera WHERE idpartitura='$idpartitura'";
		//return ejecutarConsultaSimpleFila($sql);
		return ejecutarConsulta($sql_1);

	}

}

?>