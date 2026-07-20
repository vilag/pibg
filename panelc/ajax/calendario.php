<?php
session_start();
require_once "../modelos/Calendario.php";

$calendario=new Calendario();


switch ($_GET["op"]){

		case 'analizar_pdf':

			header('Content-Type: application/json; charset=utf-8');
			if (!isset($_SESSION['nombre']) || $_SESSION['administrador'] != 1) {
				echo json_encode(['ok' => false, 'msg' => 'Sin acceso.']);
				break;
			}
			if (!isset($_FILES['pdf']) || $_FILES['pdf']['error'] !== UPLOAD_ERR_OK) {
				echo json_encode(['ok' => false, 'msg' => 'No se recibió el archivo PDF.']);
				break;
			}
			require_once "../config/calendario_pdf_parser.php";
			try {
				$res = calendario_extraer_filas_pdf($_FILES['pdf']['tmp_name']);
				$eventos = [];
				foreach ($res['filas'] as $fila) {
					foreach (calendario_expandir_fila($res['anio'], $fila) as $ev) {
						$eventos[] = $ev;
					}
				}
				echo json_encode(['ok' => true, 'anio' => $res['anio'], 'eventos' => $eventos]);
			} catch (\Throwable $e) {
				echo json_encode(['ok' => false, 'msg' => 'No se pudo leer el PDF: ' . $e->getMessage()]);
			}
		break;

		case 'guardar_multiples':

			header('Content-Type: application/json; charset=utf-8');
			if (!isset($_SESSION['nombre']) || $_SESSION['administrador'] != 1) {
				echo json_encode(['ok' => false, 'msg' => 'Sin acceso.']);
				break;
			}
			$eventos = json_decode($_POST['eventos'] ?? '[]', true);
			if (!is_array($eventos) || empty($eventos)) {
				echo json_encode(['ok' => false, 'msg' => 'No se recibieron actividades para guardar.']);
				break;
			}
			$guardados = 0;
			foreach ($eventos as $ev) {
				$fecha = trim($ev['fecha'] ?? '');
				$hora = trim($ev['hora'] ?? '00:00:00');
				$nombre = trim($ev['nom_activ'] ?? '');
				$tema = trim($ev['tema'] ?? '');
				$tipo = intval($ev['tipo'] ?? 0);
				$diaNom = trim($ev['dia_nom'] ?? '');
				if ($fecha === '' || $nombre === '') continue;
				$fechaHora = $fecha . ' ' . ($hora !== '' ? $hora : '00:00:00');
				$calendario->insertar_seguro($fechaHora, $diaNom, $nombre, $tema, $tipo);
				$guardados++;
			}
			echo json_encode(['ok' => true, 'guardados' => $guardados]);
		break;

		case 'listar_dias':
		

			$rspta = $calendario->listar_dias();
			while ($reg = $rspta->fetch_object())
					{
						if ($reg->tipo==1) {
							$tipo = "Si";
						}
						if ($reg->tipo==0) {
							$tipo = "No";
						}
						
						echo '
                               
                            <tr>
                                <td class="py-1">
                                    '.$reg->fecha.'
                                </td>
                                <td>
                                    '.$reg->hora.' hrs.
                                </td>
                                <td>
                                    '.$reg->dia_nom.'
                                </td>
								<td>
                                    '.$reg->tema.'
                                </td>
                                <td>
                                    '.$reg->nom_activ.'
                                </td>
								<td>
                                    '.$tipo.'
                                </td>
								<td>
									<button style="background-color:rgb(129, 2, 2); padding: 10px; border-radius: 5px;">
										<img onclick="borrar_dia('.$reg->idcal.');" src="images/iconos/basura.png" style="width: 20px; height: 20px">
									</button>
                                    
                                </td>
                               
                            </tr>

						';
						
					}

		break;

        case 'listar_horas':
		

			$rspta = $calendario->listar_horas();
			while ($reg = $rspta->fetch_object())
					{
						
						echo '
                               
                           <div onclick="set_hora('.$reg->idcal.',\''.$reg->hora.'\');" style="width: 100%; height: 50px; border-bottom: rgba(0,0,0,0.2) 1px solid !important; display: flex; align-items: center; justify-content: center;">
                             <p style="cursor: pointer;">'.$reg->hora.'</p>
                           </div>

						';
						
					}

		break;

        case 'listar_nombres':
		

			$rspta = $calendario->listar_nombres();
			while ($reg = $rspta->fetch_object())
					{
						
						echo '
                               
                           <div onclick="set_nombre('.$reg->idcal.',\''.$reg->nom_activ.'\');" style="width: 100%; height: 50px; border-bottom: rgba(0,0,0,0.2) 1px solid !important; display: flex; align-items: center; justify-content: center;">
                             <p style="cursor: pointer;">'.$reg->nom_activ.'</p>
                           </div>

						';
						
					}

		break;

        case 'listar_activ_sem':
		

			$rspta = $calendario->listar_activ_sem();
			while ($reg = $rspta->fetch_object())
					{
						
						echo '
                            <b style="margin: 10px; padding: 10px; background-color: #042C49; color: #fff; border-radius: 5px; cursor: pointer;" onclick="set_dia_sem('.$reg->idactiv.',\''.$reg->nombre.'\',\''.$reg->hora.'\');">'.$reg->nombre.'</b>  
						';
						
					}

		break;

		case 'guardar_dia_calendario':
			
			$fecha_hora = $_POST['fecha_hora'];
			$dia = $_POST['dia'];
			$nom_actividad = $_POST['nom_actividad'];
			$tema_actividad = $_POST['tema_actividad'];
			$tipo_act = $_POST['tipo_act'];
										
			$rspta=$calendario->guardar_dia_calendario($fecha_hora,$dia,$nom_actividad,$tema_actividad,$tipo_act);
			echo json_encode($rspta);
	 		//echo $rspta ? "Anulada" : "No se puede anular";
		break;

		case 'borrar_dia':
			
			$idcal = $_POST['idcal'];
										
			$rspta=$calendario->borrar_dia($idcal);
			echo json_encode($rspta);
	 		//echo $rspta ? "Anulada" : "No se puede anular";
		break;
	
}
?>