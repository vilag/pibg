<?php
session_start(); 
require_once "../modelos/Index.php";

$index=new Index();


switch ($_GET["op"]){
	

		case 'contar_act_dia':

			$fecha_hora = $_POST['fecha_hora'];

			$rspta=$index->contar_act_dia($fecha_hora);
			echo json_encode($rspta);
	 		//echo $rspta ? "Anulada" : "No se puede anular";
		break;

		case 'listar_calendario':

			$fecha = $_GET['fecha'];

			$rspta = $index->listar_calendario($fecha);
				
			while ($reg = $rspta->fetch_object())
					{
						if ($reg->mes==1) {
							$mes = "Enero";
						}
						if ($reg->mes==2) {
							$mes = "Febrero";
						}
						if ($reg->mes==3) {
							$mes = "Marzo";
						}
						if ($reg->mes==4) {
							$mes = "Abril";
						}
						if ($reg->mes==5) {
							$mes = "Mayo";
						}
						if ($reg->mes==6) {
							$mes = "Junio";
						}
						if ($reg->mes==7) {
							$mes = "Julio";
						}
						if ($reg->mes==8) {
							$mes = "Agosto";
						}
						if ($reg->mes==9) {
							$mes = "Septiembre";
						}
						if ($reg->mes==10) {
							$mes = "Octubre";
						}
						if ($reg->mes==11) {
							$mes = "Noviembre";
						}
						if ($reg->mes==12) {
							$mes = "Diciembre";
						}

						echo '

							<div class="event d-flex flex-row align-items-start justify-content-start">
								<div>
									<div class="event_date d-flex flex-column align-items-center justify-content-center">
										<div class="event_day">'.$reg->dia.'</div>
										<div class="event_month">'.$mes.'</div>
									</div>
								</div>
								<div class="event_body">
									<div class="event_title"><a href="#">'.$reg->nom_activ.'</a></div>
									<div class="event_subtitle">Hora: '.$reg->hora.' hrs.</div>
								</div>
							</div>
						';	
					}
		break;

		case 'buscar_activ_sem':

			$dia = $_POST['dia'];
			$hora = $_POST['hora'];

			$rspta=$index->buscar_activ_sem($dia,$hora);
			echo json_encode($rspta);
	 		//echo $rspta ? "Anulada" : "No se puede anular";
		break;

		case 'buscar_primer_dia':

			$rspta=$index->buscar_primer_dia();
			echo json_encode($rspta);
	 		//echo $rspta ? "Anulada" : "No se puede anular";
		break;
	
}
?>