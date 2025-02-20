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
                                    '.$reg->detalle.'
                                </td>
                               
                            </tr>

						';
						
					}

		break;
	
}
?>