<?php
session_start(); 
require_once "../modelos/Calendario.php";

$calendario=new Calendario();


switch ($_GET["op"]){

		case 'listar_dias':
		

			$rspta = $calendario->listar_dias();
			while ($reg = $rspta->fetch_object())
					{
						
						
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
                                    '.$reg->nom_activ.'
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
                               
                           <div style="width: 100%; height: 50px;">
                             <p onclick="set_hora('.$reg->idcal.',\''.$reg->hora.'\');" style="cursor: pointer;">'.$reg->hora.'</p>
                           </div>

						';
						
					}

		break;

		
	
}
?>