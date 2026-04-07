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

		

		// case 'listar_lecturas':

		// 	$fecha = $_GET['fecha'];

		// 	$rspta = $index->listar_lecturas($fecha);
				
		// 	while ($reg = $rspta->fetch_object())
		// 			{
					

		// 				echo '
		// 					<a href="'.$reg->link_cita.'" target="_blank" style="color: #fff; background-color: #F36905 !important; padding: 10px !important; border-radius: 10px; margin-left: 10px;">'.$reg->cita.'</a>
							
		// 				';	
		// 			}
		// break;

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
						<div class="v-obra-card" onclick="listar_voces('.$reg->idobra.',\''.$reg->nombre.'\');">
							<div>
								<div class="v-obra-name">'.$reg->nombre.'</div>
								<div class="v-obra-author">'.$reg->autor.'</div>
							</div>
							<span class="v-obra-arrow">&#8250;</span>
						</div>
					';
					}
		break;

		case 'listar_voces':

			$idobra = $_GET['idobra'];
			$nombre_obra = $_GET['nombre'];

			$rspta = $index->listar_voces($idobra);
				
			while ($reg = $rspta->fetch_object()) {

				echo '
					<div class="v-voz-card">
						<div class="v-voz-name">'.$reg->nombre.'</div>
						<audio controls>
							<source src="'.$reg->enlace.'" type="audio/mpeg">
						</audio>
						<div class="v-partituras-label">Partituras</div>
						<div class="v-partituras-row">
				';

				$idvoz = $reg->idvoz;
				$rspta2 = $index->lista_partituras($idvoz);
				while ($reg2 = $rspta2->fetch_object()) {
					echo '
						<div class="v-file-item">
							<div class="v-file-name">'.$reg2->nombre.'</div>
							<div class="v-file-actions">
								<a href="panelc/files/bach/'.$nombre_obra.'/'.$reg2->nombre.'" target="_blank" class="v-file-btn">
									<img src="panelc/images/iconos/share2.png" alt="Ver" style="width:16px;">
								</a>
								<a href="panelc/files/bach/'.$nombre_obra.'/'.$reg2->nombre.'" download="'.$reg2->nombre.'" class="v-file-btn">
									<img src="panelc/images/iconos/download2.png" alt="Descargar" style="width:16px;">
								</a>
							</div>
						</div>
					';
				}

				echo '
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
						<div class="v-obra-card" onclick="listar_voces('.$reg->idobra.',\''.$reg->nombre.'\');">
							<div>
								<div class="v-obra-name">'.$reg->nombre.'</div>
								<div class="v-obra-author">'.$reg->autor.'</div>
							</div>
							<span class="v-obra-arrow">&#8250;</span>
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

			// $destinatario = "vilag2407@gmail.com, pibg.isotomayor@gmail.com, pibgdlar@gmail.com";
	
			  $destinatario = "vilag2407@gmail.com, orel.vilchis@gmail.com";
			 $asunto = "Petición de oración desde pagina web: ".$nombre_peticion;
 
			 $carta = "De: $nombre_peticion \n";
			//  $carta .= "Correo: $email \n";
			 $carta .= "Telefono: $telefono_peticion \n";
			 $carta .= "Mensaje: $motivo_peticion";
 
			 mail($destinatario, $asunto, $carta);

		break;

		case 'consul_sem_esp':

			$fecha = $_POST['fecha'];

			$rspta=$index->consul_sem_esp($fecha);
			echo json_encode($rspta);
	 		//echo $rspta ? "Anulada" : "No se puede anular";
		break;

		case 'count_activ_esp':

			$fecha = $_POST['fecha'];

			$rspta=$index->count_activ_esp($fecha);
			$pila = array();
			
			while ($reg = $rspta->fetch_object()){
				array_push($pila, $reg);
			}
			
			echo json_encode($pila);
		break;

		case 'listar_lecturas':

			$fecha = $_POST['fecha'];

			$rspta = $index->listar_lecturas($fecha);
			$pila = array();
				
			while ($reg = $rspta->fetch_object())
			{
				array_push($pila, $reg);
			}
			echo json_encode($pila);
		break;

		case 'listar_actividades_destacadas':

			$fecha = $_GET['fecha'];

			$rspta = $index->listar_actividades_destacadas($fecha);
			$conteo = 1;
			while ($reg = $rspta->fetch_object())
					{
					
						echo '		
							<div>					
												<div id="mini'.$conteo.'" class="estilo_mini_princ1 fade-in_'.$conteo.'" style="
													background-image: url('.$reg->imagen.'); 
													background-repeat: no-repeat;
													background-size: 400px 350px;
													background-position: center; z-index: 1;">
													<div style="padding: 15px; background-image: linear-gradient(rgba(0,0,0,0), rgba(0,0,0,0.8)); height: 100%; width: 100%; border-radius: 10px;">
														<p class="yanone-kaffeesatz">'.$reg->nombre_corto.'</p>
													</div>
													<p id="nom_activ'.$conteo.'" style="display: none;">'.$reg->nombre.'</p>
													<p id="det_activ'.$conteo.'" style="display: none;">'.$reg->detalle.'</p>
												</div>
												<div id="mini'.$conteo.'_2" class="estilo_mini_princ1-in" style="
													background-image: url('.$reg->imagen.');
													background-repeat: no-repeat;
													background-size: cover;
													background-position: center; display:none;">
													<div style="padding: 15px; background-image: linear-gradient(rgba(0,0,0,0), rgba(0,0,0,1)); height: 100%; width: 100%;">
														<p class="yanone-kaffeesatz"></p>
													</div>
													
												</div>  
							</div>
						';	

						$conteo = $conteo + 1;
					}
					
		break;
	
}
?>