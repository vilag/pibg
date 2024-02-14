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
                                    <button style="padding: 5px; border-radius: 5px; background-color: #1F4168; border: #ccc 1px solid; color: #fff;" onclick="listar_voces('.$reg->idobra.',\''.$reg->nombre.'\');"><img src="images/iconos/voz.png" alt="" style="width: 20px;"></button>
                                    
                                    <a style="padding: 9px; border-radius: 5px; background-color: #1F4168; border: #ccc 1px solid; color: #fff;" href="#modal1" onclick="editar_obra('.$reg->idobra.',\''.$reg->nombre.'\',\''.$reg->autor.'\')"><img src="images/iconos/editar.png" alt="" style="width: 20px;"></a>
                                    <button style="padding: 5px; border-radius: 5px; background-color: #1F4168; border: #ccc 1px solid; color: #fff;" onclick="eliminar_obra('.$reg->idobra.');"><img src="images/iconos/basura.png" alt="" style="width: 20px;"></button>
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
        case 'actualizar_obra':
			
			$nombre = $_POST['nombre'];
			$autor = $_POST['autor'];
            $idobra = $_POST['idobra'];
										
			$rspta=$bach->actualizar_obra($nombre,$autor,$idobra);
			echo json_encode($rspta);
	 		//echo $rspta ? "Anulada" : "No se puede anular";
		break;

        case 'listar_voces':

			$idobra = $_GET['idobra'];

			$rspta = $bach->listar_voces($idobra);
				
			while ($reg = $rspta->fetch_object())
					{
					

						echo '

						<div style="padding: 15px; width: 100%; margin-top: 15px; background-color: #1F4168; color: #fff; line-height: 25px; box-shadow: 5px 5px 10px rgba(0,0,0,0.2);">
							<b>'.$reg->voz.'</b><br>
							<audio controls="controls" style="margin-top: 10px;">
                                        <source src="'.$reg->enlace.'" type="audio/mpeg" />
                                        Your browser does not support the audio element.
                            </audio>
							<div style="width: 100%;">
                                    
                                    
                                    <a style="padding: 9px; width: 25%; border-radius: 5px; background-color: #1F4168; border: #ccc 1px solid; color: #fff;" href="#modal2" onclick="editar_voz('.$reg->idvoz.',\''.$reg->voz.'\',\''.$reg->enlace.'\')"><img src="images/iconos/editar.png" alt="" style="width: 20px;"></a>
                                    <button style="padding: 5px; border-radius: 5px; background-color: #1F4168; border: #ccc 1px solid; color: #fff;" onclick="eliminar_voz('.$reg->idvoz.');"><img src="images/iconos/basura.png" alt="" style="width: 20px;"></button>
                            </div>
						</div>

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

		case 'eliminar_obra':
			
            $idobra = $_POST['idobra'];
										
			$rspta=$bach->eliminar_obra($idobra);
			echo json_encode($rspta);
	 		//echo $rspta ? "Anulada" : "No se puede anular";
		break;

		case 'actualizar_voz':
			
			$idvoz = $_POST['idvoz'];
			$voz = $_POST['voz'];
            $archivo_audio = $_POST['archivo_audio'];
										
			$rspta=$bach->actualizar_voz($idvoz,$voz,$archivo_audio);
			echo json_encode($rspta);
	 		//echo $rspta ? "Anulada" : "No se puede anular";
		break;

		case 'eliminar_voz':
			
            $idvoz = $_POST['idvoz'];
										
			$rspta=$bach->eliminar_voz($idvoz);
			echo json_encode($rspta);
	 		//echo $rspta ? "Anulada" : "No se puede anular";
		break;
}
?>