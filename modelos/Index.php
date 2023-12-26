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
        $sql="SELECT idcal, DAY(fecha_hora) as dia, MONTH(fecha_hora) as mes, TIME(fecha_hora) as hora, dia_nom, nom_activ, tema, img, tipo FROM calendario WHERE fecha_hora>='$fecha_hora' AND nom_activ<>'' ORDER BY fecha_hora ASC LIMIT 1";
        return ejecutarConsultaSimpleFila($sql);  
    }

    public function listar_calendario($fecha)
	{
		$sql="SELECT DAY(fecha_hora) as dia, MONTH(fecha_hora) as mes, TIME(fecha_hora) as hora, nom_activ  FROM calendario WHERE DATE(fecha_hora)>='$fecha' LIMIT 5";
		//return ejecutarConsultaSimpleFila($sql);
		return ejecutarConsulta($sql);			
	}
	

   
}

?>