<?php
require "../config/Conexion.php";

Class Biografias
{
	public function __construct()
	{
	}

	public function listar_biografias()
    {
    	$sql = "SELECT * FROM biografias ORDER BY idbiografia DESC";
    	return ejecutarConsulta($sql);
    }

	public function get_biografia($idbiografia)
    {
    	$idbiografia = (int)$idbiografia;
    	$sql = "SELECT * FROM biografias WHERE idbiografia = $idbiografia LIMIT 1";
    	return ejecutarConsultaSimpleFila($sql);
    }

	public function guardar_biografia($nombre, $cargo, $biografia, $imagen)
    {
    	$nombre    = limpiarCadena($nombre);
    	$cargo     = limpiarCadena($cargo);
    	$biografia = limpiarCadena($biografia);
    	$imagen    = limpiarCadena($imagen);
    	$sql = "INSERT INTO biografias(nombre, cargo, biografia, imagen) VALUES('$nombre','$cargo','$biografia','$imagen')";
    	return ejecutarConsulta($sql);
    }

	public function actualizar_biografia($idbiografia, $nombre, $cargo, $biografia, $imagen)
    {
    	$idbiografia = (int)$idbiografia;
    	$nombre      = limpiarCadena($nombre);
    	$cargo       = limpiarCadena($cargo);
    	$biografia   = limpiarCadena($biografia);
    	$imagen      = limpiarCadena($imagen);
    	$sql = "UPDATE biografias SET nombre='$nombre', cargo='$cargo', biografia='$biografia', imagen='$imagen' WHERE idbiografia=$idbiografia";
    	return ejecutarConsulta($sql);
    }

	public function borrar_biografia($idbiografia)
    {
    	$idbiografia = (int)$idbiografia;
    	$sql = "DELETE FROM biografias WHERE idbiografia=$idbiografia";
    	return ejecutarConsulta($sql);
    }
}
?>
