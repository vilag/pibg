<?php
session_start(); 
require_once "../modelos/Lectura_diaria.php";

$lectura_diaria=new Lectura_diaria();


switch ($_GET["op"]){

		case 'listar_lecturas':
		

			$rspta = $lectura_diaria->listar_lecturas();
			while ($reg = $rspta->fetch_object())
					{
						
						
						echo '
                               
                            <tr>
                                <td class="py-1">
                                    '.$reg->fecha.'
                                </td>
                                <td>
                                    '.$reg->cita.'
                                </td>
                                <td>
                                    <a href="'.$reg->link_cita.'" target="_blank"><button>Ver</button></a>
                                    
                                </td>
                                
                               
                            </tr>

						';
						
					}

		break;

        case 'guardar_lectura':
			
			$fecha = $_POST['fecha'];
			$cita_biblica = $_POST['cita_biblica'];
			$link = $_POST['link'];
										
			$rspta=$lectura_diaria->guardar_lectura($fecha,$cita_biblica,$link);
			echo json_encode($rspta);
	 		//echo $rspta ? "Anulada" : "No se puede anular";
		break;

	
}
?>