<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Index
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

    public function contar_act_dia($fecha_hora)
    {
        $sql="SELECT idcal, DAY(fecha_hora) as dia, MONTH(fecha_hora) as mes, TIME(fecha_hora) as hora, dia_nom, nom_activ, tema, img, tipo FROM calendario WHERE fecha_hora>='$fecha_hora' AND nom_activ<>'' AND tipo=1 ORDER BY fecha_hora ASC LIMIT 1";
        return ejecutarConsultaSimpleFila($sql);  
    }

    public function listar_calendario($fecha)
	{
		$sql="SELECT DAY(fecha_hora) as dia, MONTH(fecha_hora) as mes, TIME(fecha_hora) as hora, nom_activ, tema  FROM calendario WHERE DATE(fecha_hora)>='$fecha' ORDER BY DATE(fecha_hora) ASC, TIME(fecha_hora) ASC LIMIT 5";
		//return ejecutarConsultaSimpleFila($sql);
		return ejecutarConsulta($sql);			
	}

	public function buscar_activ_sem($dia,$hora)
	{
		$sql="SELECT dia, nombre, hora FROM activ_sem WHERE num_dia>='$dia' AND hora_fin>='$hora' ORDER BY num_dia ASC LIMIT 1";
		return ejecutarConsultaSimpleFila($sql);
		//return ejecutarConsulta($sql);			
	}

	public function buscar_primer_dia()
	{
		$sql="SELECT * FROM activ_sem ORDER BY num_dia ASC LIMIT 1";
		return ejecutarConsultaSimpleFila($sql);
		//return ejecutarConsulta($sql);			
	}

	public function listar_lecturas($fecha)
	{
		$sql="SELECT * FROM lectura_diaria WHERE fecha='$fecha' ORDER BY idlectura ASC";
		//return ejecutarConsultaSimpleFila($sql);
		return ejecutarConsulta($sql);			
	}

	public function activ_esp1($fecha)
	{
		$sql="SELECT * FROM actividades_destacadas WHERE fecha2>='$fecha' ORDER BY idactiv ASC LIMIT 1";
		return ejecutarConsultaSimpleFila($sql);
		//return ejecutarConsulta($sql);			
	}

	public function listar_activ_dest($fecha)
	{
		$sql="SELECT * FROM actividades_destacadas WHERE fecha2>='$fecha' ORDER BY idactiv ASC LIMIT 3";
		//return ejecutarConsultaSimpleFila($sql);
		return ejecutarConsulta($sql);			
	}

	public function enviar_datos_nuevo_contacto($nombre,$telefono,$fecha_hora)
	{
		$sql="INSERT INTO nuevos_contactos (nombre, telefono, fecha_hora) VALUE('$nombre','$telefono','$fecha_hora')";
		//return ejecutarConsultaSimpleFila($sql);
		return ejecutarConsulta($sql);			
	}

   
}

?>