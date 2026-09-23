<?php
session_start();
require_once "../modelos/Calendario.php";

$calendario=new Calendario();

function calendario_es_admin()
{
	return isset($_SESSION['nombre']) && $_SESSION['administrador'] == 1;
}

// Solo para mostrar en la columna Fecha de la tabla; el valor que se manda
// a guardar/editar/borrar sigue siendo el idcal y las fechas ISO de
// siempre, esto no toca esos procesos.
function calendario_fecha_larga($fechaIso)
{
	$meses = ['', 'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
	$ts = strtotime((string) $fechaIso);
	if ($ts === false) return $fechaIso;
	return (int) date('j', $ts) . ' de ' . $meses[(int) date('n', $ts)] . ' de ' . date('Y', $ts);
}


switch ($_GET["op"]){

		case 'listar_anios_disponibles':

			header('Content-Type: application/json; charset=utf-8');
			$anioActual = (int) date('Y');
			$anios = [$anioActual - 1, $anioActual, $anioActual + 1, $anioActual + 2];
			$rspta = $calendario->listar_anios_disponibles();
			while ($reg = $rspta->fetch_object()) {
				if (!in_array((int) $reg->anio, $anios, true)) $anios[] = (int) $reg->anio;
			}
			sort($anios);
			echo json_encode(['ok' => true, 'anios' => array_values($anios), 'anio_actual' => $anioActual, 'mes_actual' => (int) date('n')]);
		break;

		case 'analizar_pdf':

			header('Content-Type: application/json; charset=utf-8');
			if (!calendario_es_admin()) {
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
			if (!calendario_es_admin()) {
				echo json_encode(['ok' => false, 'msg' => 'Sin acceso.']);
				break;
			}
			$eventos = json_decode($_POST['eventos'] ?? '[]', true);
			if (!is_array($eventos) || empty($eventos)) {
				echo json_encode(['ok' => false, 'msg' => 'No se recibieron actividades para guardar.']);
				break;
			}
			// Evita duplicados (misma fecha_hora + nombre de actividad), por
			// ejemplo si la peticion llega repetida por un doble clic, un
			// reintento, o si se vuelve a analizar/registrar un PDF que ya se
			// habia cargado antes. La comparacion del nombre ignora
			// mayusculas/acentos/espacios para que una diferencia minima de
			// captura no deje pasar un duplicado real.
			global $conexion;
			$existentes = [];
			$rExist = $conexion->query("SELECT fecha_hora, nom_activ FROM calendario");
			while ($rExist && ($row = $rExist->fetch_assoc())) {
				$existentes[$row['fecha_hora'] . '|' . calendario_normalizar_nombre($row['nom_activ'])] = true;
			}

			$guardados = 0;
			$omitidos = 0;
			foreach ($eventos as $ev) {
				$fecha = trim($ev['fecha'] ?? '');
				$hora = trim($ev['hora'] ?? '00:00:00');
				$nombre = trim($ev['nom_activ'] ?? '');
				$tema = trim($ev['tema'] ?? '');
				$tipo = intval($ev['tipo'] ?? 0);
				$diaNom = trim($ev['dia_nom'] ?? '');
				if ($fecha === '' || $nombre === '') continue;
				$fechaHora = $fecha . ' ' . ($hora !== '' ? $hora : '00:00:00');

				$clave = $fechaHora . '|' . calendario_normalizar_nombre($nombre);
				if (isset($existentes[$clave])) { $omitidos++; continue; } // ya existe, se omite

				$calendario->insertar_seguro($fechaHora, $diaNom, $nombre, $tema, $tipo);
				$existentes[$clave] = true;
				$guardados++;
			}
			echo json_encode(['ok' => true, 'guardados' => $guardados, 'omitidos' => $omitidos]);
		break;

		case 'listar_dias':

			header('Content-Type: application/json; charset=utf-8');
			$mes = isset($_POST['mes']) && $_POST['mes'] !== '' ? intval($_POST['mes']) : null;
			$anio = isset($_POST['anio']) && $_POST['anio'] !== '' ? intval($_POST['anio']) : null;
			$por_pagina = 50;
			$total = $calendario->contar_dias($mes, $anio);
			$total_paginas = max(1, (int) ceil($total / $por_pagina));
			$pagina = isset($_POST['pagina']) ? intval($_POST['pagina']) : 1;
			if ($pagina < 1) $pagina = 1;
			if ($pagina > $total_paginas) $pagina = $total_paginas;

			$rspta = $calendario->listar_dias($mes, $anio, $pagina, $por_pagina);
			$html = '';
			while ($reg = $rspta->fetch_object())
					{
						if ($reg->tipo==1) {
							$tipo = "Si";
						}
						if ($reg->tipo==0) {
							$tipo = "No";
						}

						$html .= '

                            <tr>
                                <td class="py-1">
                                    '.calendario_fecha_larga($reg->fecha).'
                                </td>
                                <td>
                                    '.$reg->hora.' hrs.
                                </td>
                                <td>
                                    '.htmlspecialchars($reg->dia_nom).'
                                </td>
								<td>
                                    '.htmlspecialchars($reg->nom_activ).'
                                </td>
                                <td>
                                    '.htmlspecialchars($reg->tema).'
                                </td>
								<td>
                                    '.$tipo.'
                                </td>
								<td style="white-space:nowrap;">
									<button style="background-color:#1F4168; padding: 10px; border-radius: 5px; border:none; margin-right:5px;">
										<img onclick="editar_dia_calendario('.$reg->idcal.');" src="images/iconos/editar.png" style="width: 20px; height: 20px; cursor:pointer;">
									</button>
									<button style="background-color:rgb(129, 2, 2); padding: 10px; border-radius: 5px; border:none;">
										<img onclick="borrar_dia('.$reg->idcal.');" src="images/iconos/basura.png" style="width: 20px; height: 20px; cursor:pointer;">
									</button>

                                </td>

                            </tr>

						';

					}

			echo json_encode(['ok' => true, 'html' => $html, 'pagina' => $pagina, 'total_paginas' => $total_paginas, 'total' => $total]);

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

		case 'obtener_dia':

			header('Content-Type: application/json; charset=utf-8');
			if (!calendario_es_admin()) {
				echo json_encode(['ok' => false, 'msg' => 'Sin acceso.']);
				break;
			}
			$idcal = intval($_POST['idcal'] ?? 0);
			$rspta = $calendario->obtener_dia($idcal);
			$reg = $rspta ? $rspta->fetch_assoc() : null;
			echo json_encode(['ok' => (bool) $reg, 'dia' => $reg]);
		break;

		case 'actualizar_dia_calendario':

			header('Content-Type: application/json; charset=utf-8');
			if (!calendario_es_admin()) {
				echo json_encode(['ok' => false, 'msg' => 'Sin acceso.']);
				break;
			}
			$idcal = intval($_POST['idcal'] ?? 0);
			$fecha_hora = $_POST['fecha_hora'] ?? '';
			$dia = $_POST['dia'] ?? '';
			$nom_actividad = $_POST['nom_actividad'] ?? '';
			$tema_actividad = $_POST['tema_actividad'] ?? '';
			$tipo_act = $_POST['tipo_act'] ?? 0;

			$rspta = $calendario->actualizar_dia_calendario($idcal, $fecha_hora, $dia, $nom_actividad, $tema_actividad, $tipo_act);
			echo json_encode(['ok' => (bool) $rspta]);
		break;

		case 'marcar_no_transmite':

			header('Content-Type: application/json; charset=utf-8');
			if (!calendario_es_admin()) {
				echo json_encode(['ok' => false, 'msg' => 'Sin acceso.']);
				break;
			}
			$nom_activ = $_POST['nom_activ'] ?? '';
			$rspta = $calendario->marcar_no_transmite($nom_activ);
			echo json_encode(['ok' => (bool) $rspta]);
		break;

		case 'desmarcar_no_transmite':

			header('Content-Type: application/json; charset=utf-8');
			if (!calendario_es_admin()) {
				echo json_encode(['ok' => false, 'msg' => 'Sin acceso.']);
				break;
			}
			$nom_activ = $_POST['nom_activ'] ?? '';
			$rspta = $calendario->desmarcar_no_transmite($nom_activ);
			echo json_encode(['ok' => (bool) $rspta]);
		break;

		case 'listar_no_transmite':

			header('Content-Type: application/json; charset=utf-8');
			if (!calendario_es_admin()) {
				echo json_encode(['ok' => false, 'msg' => 'Sin acceso.']);
				break;
			}
			$rspta = $calendario->listar_no_transmite();
			$nombres = [];
			while ($reg = $rspta->fetch_assoc()) {
				$nombres[] = $reg['nom_activ_norm'];
			}
			echo json_encode(['ok' => true, 'nombres' => $nombres]);
		break;

}
?>