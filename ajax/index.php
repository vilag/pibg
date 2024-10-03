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
							<a href="'.$reg->link_cita.'" target="_blank" style="color: #fff; background-color: #F36905 !important; padding: 10px !important; border-radius: 10px; margin-left: 10px;">'.$reg->cita.'</a>
							
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

		case 'listar_obras':

			$nombre = $_GET['nombre'];

			$rspta = $index->listar_obras($nombre);
				
			while ($reg = $rspta->fetch_object())
					{
					
						echo '							
							<div onclick="listar_voces('.$reg->idobra.',\''.$reg->nombre.'\');" style="line-height: 23px; padding: 15px 10px 10px 10px; border: #ccc 1px solid; background-color: #EBEFF1; margin-top: 5px; box-shadow: 5px 5px 10px rgba(0,0,0,0.2);">							
								<b style="margin-top: -5px; font-size: 20px;">'.$reg->nombre.'</b><br>
								<label>'.$reg->autor.'</label>
							</div>  
						';	
					}
		break;

		case 'listar_voces':

			$idobra = $_GET['idobra'];

			$rspta = $index->listar_voces($idobra);
				
			while ($reg = $rspta->fetch_object())
					{
					

						echo '
							
						<div style="margin-top: 10px; width: 100%; background-color: #EEF2F3; border-radius: 10px; box-shadow: 5px 5px 10px rgba(0,0,0,0.2);">
							<div style="width: 100%; padding: 10px; text-align: center;">
								<b style="font-size: 18px; margin-left: 7px; color: #1D4268;">'.$reg->voz.'</b>   
							</div>
							<div style="width: 100%; padding: 10px; margin-top: -15px; justify-content: center;">
								<button id="btn_play'.$reg->idvoz.'" onclick="PlaySound('.$reg->idvoz.');" style="display: none; background-color: rgba(0,0,0,0); border: none; margin: 7px;"><img src="images/iconos/play.png" style="width: 40px;" alt=""></button>
								<button id="btn_pause'.$reg->idvoz.'" onclick="PlaySound2('.$reg->idvoz.');" style="display: none; background-color: rgba(0,0,0,0); border: none; margin: 7px;"><img src="images/iconos/pausa.png" style="width: 40px;" alt=""></button>
								<button onclick="PlaySound3('.$reg->idvoz.');" style="display: none; background-color: rgba(0,0,0,0); border: none; margin: 7px;"><img src="images/iconos/reiniciar.png" style="width: 40px;" alt=""></button>
								<audio controls id="audio_voz'.$reg->idvoz.'" style="display: none; position: absolute; width; 100%;">
									<source src="'.$reg->enlace.'" type="audio/mp3">
									Your browser does not support the audio element.
								</audio>
								<audio controls="controls">
									<source src="'.$reg->enlace.'" type="audio/mpeg" />
									Your browser does not support the audio element.
								</audio>
							</div>
						</div>
						

						';	
					}
		break;

		case 'listar_obras_1':

			//$nombre = $_GET['nombre'];

			$rspta = $index->listar_obras_1();
				
			while ($reg = $rspta->fetch_object())
					{
					
						echo '							
							<div onclick="listar_voces('.$reg->idobra.',\''.$reg->nombre.'\');" style="line-height: 23px; padding: 15px 10px 10px 10px; border: #ccc 1px solid; background-color: #EBEFF1; margin-top: 5px; box-shadow: 5px 5px 10px rgba(0,0,0,0.2);">							
								<b style="margin-top: -5px; font-size: 20px;">'.$reg->nombre.'</b><br>
								<label>'.$reg->autor.'</label>
							</div>  
						';	
					}
		break;

		case 'guardar_motivo':

			$nombre_peticion = $_POST['nombre_peticion'];
			$telefono_peticion = $_POST['telefono_peticion'];
			$motivo_peticion = $_POST['motivo_peticion'];
			$fecha_hora = $_POST['fecha_hora'];

			$rspta=$index->guardar_motivo($nombre_peticion,$telefono_peticion,$motivo_peticion,$fecha_hora);
			echo json_encode($rspta);
	 		//echo $rspta ? "Anulada" : "No se puede anular";

			 $destinatario = "vilag2407@gmail.com, pibg.isotomayor@gmail.com, pibgdlar@gmail.com";
			 $asunto = "Petición de oración desde pagina web: ".$nombre_peticion;
 
			 $carta = "De: $nombre_peticion \n";
			//  $carta .= "Correo: $email \n";
			 $carta .= "Telefono: $telefono_peticion \n";
			 $carta .= "Mensaje: $motivo_peticion";
 
			 mail($destinatario, $asunto, $carta);

		break;
	
}
?>