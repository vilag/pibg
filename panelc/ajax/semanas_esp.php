<?php
session_start(); 
require_once "../modelos/Semanas_esp.php";

$semanas_esp=new Semanas_esp();


switch ($_GET["op"]){


		case 'guardar_activ_sem':
			
			$fecha1 = $_POST['fecha1'];
			$fecha2 = $_POST['fecha2'];
			$nombre = $_POST['nombre'];
			$nombre_corto = $_POST['nombre_corto'];
			$detalle = $_POST['detalle'];
			$imagen = $_POST['imagen'];
										
			$rspta=$semanas_esp->guardar_activ_sem($fecha1,$fecha2,$nombre,$nombre_corto,$detalle,$imagen);
			echo json_encode($rspta);
	 		//echo $rspta ? "Anulada" : "No se puede anular";
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
									<button style="background-color:rgb(129, 2, 2); padding: 10px; border-radius: 5px;">
										<img onclick="borrar_activ('.$reg->idactiv .');" src="images/iconos/basura.png" style="width: 20px; height: 20px">
									</button>
                                    
                                </td>
                               
                            </tr>

						';
						
					}

		break;

		case 'borrar_activ':
			
			$idactiv = $_POST['idactiv'];
										
			$rspta=$semanas_esp->borrar_activ($idactiv);
			echo json_encode($rspta);
	 		//echo $rspta ? "Anulada" : "No se puede anular";
		break;
	
}
?>