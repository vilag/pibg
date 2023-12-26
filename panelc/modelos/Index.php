<?php
 	require "../config/Conexion.php";

 	Class Index
 	{
 		public function __construct()
		{

		}

		

		public function list_iconos()
		{
			$sql="SELECT * FROM tbl_iconos ORDER BY idtbl_icono ASC";
			return ejecutarConsulta($sql);
		}

		public function mostrar_list_act()
		{
			$sql="SELECT DISTINCT nom_activ FROM calendario ORDER BY nom_activ ASC";
			return ejecutarConsulta($sql);
		}



 	}

?>