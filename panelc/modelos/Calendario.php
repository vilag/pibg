<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Calendario
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	public function listar_dias($mes = null, $anio = null)
    {
    	$where = "1=1";
    	if ($mes)  { $where .= " AND MONTH(fecha_hora) = " . intval($mes); }
    	if ($anio) { $where .= " AND YEAR(fecha_hora) = " . intval($anio); }
    	$sql="SELECT idcal, DATE(fecha_hora) as fecha, TIME(fecha_hora) as hora, dia_nom, nom_activ, tipo, tema FROM calendario WHERE $where ORDER BY fecha_hora ASC";
    	return ejecutarConsulta($sql);
    }

	public function listar_anios_disponibles()
    {
    	$sql = "SELECT DISTINCT YEAR(fecha_hora) as anio FROM calendario ORDER BY anio DESC";
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

	public function obtener_dia($idcal)
    {
    	global $conexion;
    	$stmt = $conexion->prepare("SELECT idcal, DATE(fecha_hora) as fecha, TIME(fecha_hora) as hora, dia_nom, nom_activ, tema, tipo FROM calendario WHERE idcal=?");
    	$idcalInt = intval($idcal);
    	$stmt->bind_param('i', $idcalInt);
    	$stmt->execute();
    	return $stmt->get_result();
    }

	public function actualizar_dia_calendario($idcal, $fecha_hora, $dia_nom, $nom_activ, $tema, $tipo)
    {
    	global $conexion;
    	$idcalInt = intval($idcal);

    	// Se comprueba que la fila exista antes de actualizar: fiarse de
    	// affected_rows() no sirve, porque tambien da 0 cuando los valores
    	// nuevos son iguales a los que ya tenia (no solo cuando el id no
    	// existe), y en ese caso el guardado si fue exitoso.
    	$check = $conexion->prepare("SELECT idcal FROM calendario WHERE idcal=?");
    	$check->bind_param('i', $idcalInt);
    	$check->execute();
    	if (!$check->get_result()->fetch_assoc()) {
    		return false;
    	}

    	$stmt = $conexion->prepare("UPDATE calendario SET fecha_hora=?, dia_nom=?, nom_activ=?, tema=?, tipo=? WHERE idcal=?");
    	$tipoInt = intval($tipo);
    	$stmt->bind_param('ssssii', $fecha_hora, $dia_nom, $nom_activ, $tema, $tipoInt, $idcalInt);
    	return $stmt->execute();
    }

	// Insercion segura (prepared statement) usada por la carga masiva desde PDF.
	public function insertar_seguro($fecha_hora, $dia_nom, $nom_activ, $tema, $tipo)
    {
    	global $conexion;
    	$stmt = $conexion->prepare("INSERT INTO calendario (fecha_hora, dia_nom, nom_activ, tema, tipo) VALUES (?, ?, ?, ?, ?)");
    	$tipoInt = intval($tipo);
    	$stmt->bind_param('ssssi', $fecha_hora, $dia_nom, $nom_activ, $tema, $tipoInt);
    	$stmt->execute();
    	return $stmt->insert_id;
    }

}

?>