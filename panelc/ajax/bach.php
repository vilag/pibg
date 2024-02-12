<?php
session_start(); 
require_once "../modelos/Bach.php";

$bach=new Bach();


switch ($_GET["op"]){

		case 'listar_obras':
		

			$rspta = $bach->listar_obras();
			while ($reg = $rspta->fetch_object())
					{
						
						
						echo '

                            <div style="padding: 15px; width: 100%; margin-top: 15px; background-color: #1F4168; color: #fff; line-height: 25px; box-shadow: 5px 5px 10px rgba(0,0,0,0.2);">
                                <b>'.$reg->nombre.'</b><br>
                                <label>'.$reg->autor.'</label>
                                <div style="width: 100%;">
                                    <button style="padding: 5px; width: 100px; border-radius: 5px; background-color: #1F4168; border: #ccc 1px solid; color: #fff;" onclick="listar_voces('.$reg->idobra.',\''.$reg->nombre.'\');">Ver</button>
                                    <button style="padding: 5px; width: 100px; border-radius: 5px; background-color: #1F4168; border: #ccc 1px solid; color: #fff;" onclick="listar_voces('.$reg->idobra.',\''.$reg->nombre.'\');">Editar</button>
                                </div>
                            </div>
                               
                            

						';
						
					}

		break;

        case 'guardar_obra':
			
			$nombre = $_POST['nombre'];
			$autor = $_POST['autor'];
										
			$rspta=$bach->guardar_obra($nombre,$autor);
			echo json_encode($rspta);
	 		//echo $rspta ? "Anulada" : "No se puede anular";
		break;

        case 'listar_voces':

			$idobra = $_GET['idobra'];

			$rspta = $bach->listar_voces($idobra);
				
			while ($reg = $rspta->fetch_object())
					{
					

						echo '

                            <tr>
                                <td class="py-1">
                                    '.$reg->voz.'
                                </td>
                                <td>
                                    <audio controls="controls">
                                        <source src="'.$reg->enlace.'" type="audio/mpeg" />
                                        Your browser does not support the audio element.
                                    </audio>
                                </td>
                                <td>
                                    <button>Ver</button>
                                    
                                </td>
                                
                               
                            </tr>
							
						
						

						';	
					}
		break;

        case 'guardar_voz':
			
            $idobra = $_POST['idobra'];
			$voz = $_POST['voz'];
			$archivo_audio = $_POST['archivo_audio'];
										
			$rspta=$bach->guardar_voz($idobra,$voz,$archivo_audio);
			echo json_encode($rspta);
	 		//echo $rspta ? "Anulada" : "No se puede anular";
		break;

	
}
?>