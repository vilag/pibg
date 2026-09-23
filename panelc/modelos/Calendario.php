<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

if (!function_exists('calendario_normalizar_nombre')) {
	// Misma normalizacion (minusculas, sin acentos, sin espacios en los
	// extremos) que usa scripts/calendario.js en el navegador, para que un
	// nombre marcado como "sin transmision" se reconozca despues aunque
	// cambien mayusculas/acentos.
	function calendario_normalizar_nombre($s)
	{
		$s = trim(mb_strtolower((string) $s, 'UTF-8'));
		// Misma tecnica que el NFD + quitar marcas combinantes del JS
		// (normalize('NFD').replace(/[̀-ͯ]/g, '')), para que
		// cualquier acento se quite igual en ambos lados, no solo los de
		// este mapa. Si el servidor no tiene la extension intl, se usa el
		// mapa como respaldo (cubre los casos comunes en español).
		if (class_exists('Normalizer')) {
			$descompuesto = Normalizer::normalize($s, Normalizer::FORM_D);
			if ($descompuesto !== false) {
				return preg_replace('/[\x{0300}-\x{036f}]/u', '', $descompuesto);
			}
		}
		$mapa = [
			'á' => 'a', 'à' => 'a', 'ä' => 'a', 'â' => 'a',
			'é' => 'e', 'è' => 'e', 'ë' => 'e', 'ê' => 'e',
			'í' => 'i', 'ì' => 'i', 'ï' => 'i', 'î' => 'i',
			'ó' => 'o', 'ò' => 'o', 'ö' => 'o', 'ô' => 'o',
			'ú' => 'u', 'ù' => 'u', 'ü' => 'u', 'û' => 'u',
			'ñ' => 'n',
		];
		return strtr($s, $mapa);
	}
}

Class Calendario
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	public function listar_dias($mes = null, $anio = null, $pagina = null, $por_pagina = null)
    {
    	$where = "1=1";
    	if ($mes)  { $where .= " AND MONTH(fecha_hora) = " . intval($mes); }
    	if ($anio) { $where .= " AND YEAR(fecha_hora) = " . intval($anio); }
    	$sql="SELECT idcal, DATE(fecha_hora) as fecha, TIME(fecha_hora) as hora, dia_nom, nom_activ, tipo, tema FROM calendario WHERE $where ORDER BY fecha_hora ASC";
    	if ($pagina && $por_pagina) {
    		$offset = (max(1, intval($pagina)) - 1) * intval($por_pagina);
    		$sql .= " LIMIT " . intval($por_pagina) . " OFFSET " . $offset;
    	}
    	return ejecutarConsulta($sql);
    }

	// Total de registros que coinciden con el filtro, para calcular cuantas
	// paginas hay (usado por el paginado de la tabla de registros guardados).
	public function contar_dias($mes = null, $anio = null)
    {
    	$where = "1=1";
    	if ($mes)  { $where .= " AND MONTH(fecha_hora) = " . intval($mes); }
    	if ($anio) { $where .= " AND YEAR(fecha_hora) = " . intval($anio); }
    	$sql = "SELECT COUNT(*) as total FROM calendario WHERE $where";
    	$row = ejecutarConsultaSimpleFila($sql);
    	return (int) $row['total'];
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

	// Recuerda que una actividad (por nombre) no se transmite en vivo, para
	// que al analizar un PDF futuro se marque "No" automaticamente si
	// vuelve a aparecer.
	public function marcar_no_transmite($nom_activ)
    {
    	global $conexion;
    	$norm = calendario_normalizar_nombre($nom_activ);
    	if ($norm === '') return false;
    	$stmt = $conexion->prepare("INSERT INTO calendario_no_transmite (nom_activ, nom_activ_norm, fecha_hora) VALUES (?, ?, NOW()) ON DUPLICATE KEY UPDATE nom_activ = VALUES(nom_activ)");
    	$stmt->bind_param('ss', $nom_activ, $norm);
    	return $stmt->execute();
    }

	public function desmarcar_no_transmite($nom_activ)
    {
    	global $conexion;
    	$norm = calendario_normalizar_nombre($nom_activ);
    	if ($norm === '') return false;
    	$stmt = $conexion->prepare("DELETE FROM calendario_no_transmite WHERE nom_activ_norm = ?");
    	$stmt->bind_param('s', $norm);
    	return $stmt->execute();
    }

	public function listar_no_transmite()
    {
    	global $conexion;
    	return $conexion->query("SELECT nom_activ_norm FROM calendario_no_transmite");
    }

}

?>