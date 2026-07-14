<?php
session_start(); 
require_once "../modelos/Semanas_esp.php";

$semanas_esp=new Semanas_esp();


switch ($_GET["op"]){


		case 'guardar_activ_sem':

			$fecha1       = $_POST['fecha1'];
			$fecha2       = $_POST['fecha2'];
			$nombre       = $_POST['nombre'];
			$nombre_corto = $_POST['nombre_corto'];
			$detalle      = $_POST['detalle'];
			$imagen       = $_POST['imagen'];
			$texto_banner = $_POST['texto_banner'] ?? '';
			$video_url    = $_POST['video_url']    ?? '';

			$idactiv=$semanas_esp->guardar_activ_sem($fecha1,$fecha2,$nombre,$nombre_corto,$detalle,$imagen,$texto_banner,$video_url);
			echo json_encode(['ok' => $idactiv > 0, 'idactiv' => $idactiv]);
		break;

        case 'listar_activ_sem_esp':
		
			$rspta = $semanas_esp->listar_activ_sem_esp();
			while ($reg = $rspta->fetch_object())
					{
						
						
						echo '
                               
                            <tr>
                                <td class="py-1">
                                    '.$reg->fecha1.'
                                </td>
                                <td>
                                    '.$reg->fecha2.'
                                </td>
                                <td>
                                    '.$reg->nombre.'
                                </td>
								<td>
                                    '.$reg->nombre_corto.'
                                </td>
                                <td>
                                    '.$reg->detalle.'
                                </td>
								<td>
                                    <img src="'.$reg->imagen.'" alt="" style="width: 50px; height: 50px; border-radius: 0px !important;">
                                </td>
								<td>
									<button onclick="ver_activ_detalle('.$reg->idactiv .');" title="Ver detalle" style="background-color:#455a64; padding: 10px; border-radius: 5px; margin-right: 6px; cursor:pointer; color:#fff; border:none;">👁</button>
									<button onclick="editar_activ('.$reg->idactiv .');" style="background-color:rgb(13, 110, 180); padding: 10px; border-radius: 5px; margin-right: 6px; cursor:pointer;">
										<img src="images/iconos/edit.png" style="width: 20px; height: 20px" onerror="this.style.display=\'none\'; this.parentNode.appendChild(document.createTextNode(\'✏️\'))">
									</button>
									<button style="background-color:rgb(129, 2, 2); padding: 10px; border-radius: 5px;">
										<img onclick="borrar_activ('.$reg->idactiv .');" src="images/iconos/basura.png" style="width: 20px; height: 20px">
									</button>
                                </td>
                               
                            </tr>

						';
						
					}

		break;

		case 'obtener_activ':

			$idactiv = $_POST['idactiv'];
			$rspta = $semanas_esp->obtener_activ($idactiv);
			$reg = $rspta->fetch_object();
			echo json_encode($reg);
		break;

		case 'editar_activ_sem':

			$idactiv      = $_POST['idactiv'];
			$fecha1       = $_POST['fecha1'];
			$fecha2       = $_POST['fecha2'];
			$nombre       = $_POST['nombre'];
			$nombre_corto = $_POST['nombre_corto'];
			$detalle      = $_POST['detalle'];
			$imagen       = $_POST['imagen'];
			$texto_banner = $_POST['texto_banner'] ?? '';
			$video_url    = $_POST['video_url']    ?? '';

			$rspta = $semanas_esp->editar_activ_sem($idactiv,$fecha1,$fecha2,$nombre,$nombre_corto,$detalle,$imagen,$texto_banner,$video_url);
			echo json_encode($rspta);
		break;

		case 'borrar_activ':

			$idactiv = $_POST['idactiv'];

			$rspta=$semanas_esp->borrar_activ($idactiv);
			echo json_encode($rspta);
		break;

}
?>