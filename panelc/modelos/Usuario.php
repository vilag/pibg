<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Usuario
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	

	//Función para verificar el acceso al sistema
	public function verificar($login,$clave)
    {
    	$sql="SELECT idusuario,nombre,apellido_p,apellido_m,puesto,imagen,login,lugar FROM usuario WHERE login='$login' AND clave='$clave' AND estatus='1'"; 
    	return ejecutarConsulta($sql);  
    }

    public function listarmarcados($idusuario)
	{
		$sql="SELECT * FROM usuario_permiso WHERE idusuario='$idusuario'";
		return ejecutarConsulta($sql);
	}

	// Usuarios activos del panel, para elegir a quien dirigir un aviso push
	// especifico (push_eventos_config.idusuario_destino). Cualquier usuario
	// del panel puede ser destinatario, no solo los administradores.
	public function listar_activos()
	{
		$sql = "SELECT idusuario, nombre, apellido_p, apellido_m, puesto FROM usuario WHERE estatus='1' ORDER BY nombre ASC";
		$res = ejecutarConsulta($sql);
		$filas = [];
		while ($row = $res->fetch_assoc()) { $filas[] = $row; }
		return $filas;
	}
}

?>