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

						if ($reg->tema<>"") {
							$disp_tema = "display: block;";
							$disp_tema2 = "margin-top: -5px;";
						}else{
							$disp_tema = "display: none;";
							$disp_tema2 = "margin-top: 5px;";
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
									<div style="'.$disp_tema.'"><label>'.$reg->tema.'</label></div>
									<div style="'.$disp_tema2.'"><b>'.$reg->hora.' hrs.</b></div>
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

		case 'listar_lecturas':

			$fecha = $_GET['fecha'];

			$rspta = $index->listar_lecturas($fecha);
				
			while ($reg = $rspta->fetch_object())
					{
					

						echo '
							<a href="'.$reg->link.'" target="_blank" style="color: #fff; background-color: #F36905 !important; padding: 10px !important; border-radius: 10px; margin-left: 10px;">'.$reg->cita.'</a>
							
						';	
					}
		break;

		case 'activ_esp1':

			$fecha = $_POST['fecha'];

			$rspta=$index->activ_esp1($fecha);
			echo json_encode($rspta);
	 		//echo $rspta ? "Anulada" : "No se puede anular";
		break;

		case 'listar_activ_dest':

			$fecha = $_GET['fecha'];

			$rspta = $index->listar_activ_dest($fecha);
				
			while ($reg = $rspta->fetch_object())
					{
					

						echo '
							
						<div class="col-lg-4 col-md-6 col-sm-12" style="float: left;">
							<div class="course">
								<div class="course_image"><img src="'.$reg->imagen.'" alt="" style="width: 100%;"></div>
								<div class="course_body" style="height: 280px;">

									<div class="course_title"><h3><a href="#">'.$reg->nombre.'</a></h3></div>
									<div class="course_text" style="line-height : 15px; margin-top: 40px;">
										<label for="">'.$reg->detalle.'</label><br>
										
									</div>
									
								</div>
							</div>
						</div>
						

						';	
					}
		break;

		case 'enviar_datos_nuevo_contacto':

			$nombre = $_POST['nombre'];
			$telefono = $_POST['telefono'];
			$fecha_hora = $_POST['fecha_hora'];

			$rspta=$index->enviar_datos_nuevo_contacto($nombre,$telefono,$fecha_hora);
			echo json_encode($rspta);
	 		//echo $rspta ? "Anulada" : "No se puede anular";
		break;
	
}
?>