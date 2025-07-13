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

                            <div class="estilo_lista_obras">
                                <div onclick="listar_voces('.$reg->idobra.',\''.$reg->nombre.'\');" style="width: 80%; float: left; cursor: pointer;">
									<span>Obra: </span><b>'.$reg->nombre.'</b><br>
                                	<label>Autor: '.$reg->autor.'</label>
								</div>
                                <div style="width: 20%; float: left; display: flex; justify-content: right;">
                                    <!--<button style="padding: 5px; border-radius: 5px; background-color: #1F4168; border: #ccc 1px solid; color: #fff;" ><img src="images/iconos/voz.png" alt="" style="width: 20px;"></button>-->
                                    
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
			$nombre_obra = $_GET['nombre'];

			$rspta = $bach->listar_voces($idobra);
				
			while ($reg = $rspta->fetch_object())
					{
					

						echo '

						<div style="padding: 5px 20px; width: 100%; margin-top: 15px; background-color: #F1F3F4; color: #000; line-height: 25px; box-shadow: 5px 5px 10px rgba(0,0,0,0.2); height: 130px;">
							<div style="width: 90%; float: left;">
								
								<div style="width: 100%;">
									
									<div style="width: 40%; float: left; padding: 5px 15px;">
										<div style="width: 100%;">
											<b style="font-size: 20px;">'.$reg->voz.'</b>
										</div>
										<audio controls="controls" style="margin-top: 10px; width: 100%;">
											<source src="'.$reg->enlace.'" type="audio/mpeg" />
											Your browser does not support the audio element.
										</audio>
									</div>
									
									<div class="barra_partituras" style="margin-top: 20px; width: 40%; float: left; overflow-x: scroll; padding: 5px 10px;">
										<div style="width: 5000px;">
						';	

								$idvoz=$reg->idvoz;

								$rspta2 = $bach->lista_partituras($idvoz);
								while ($reg2 = $rspta2->fetch_object()){

									echo '

										<div style=" padding-top: 10px; border-radius: 10px; width: 250px; height: 70px; margin-left: 5px; float: left; border-right: rgba(0,0,0,0.2) 1px solid; border-left: rgba(0,0,0,0.2) 1px solid;">
											
												
												<div style="width: 100%; float: left; line-height: 15px; text-align: center;">
													<b style="font-size: 11px;">'.$reg2->nombre.'</b>
													<hr style="margin: 7px !important;">
													<a href="files/bach/'.$nombre_obra.'/'.$reg2->nombre.'" target="_blank" class="estilo_btn_plan_anual" style="color: #000000 !important; margin: 5px 10px; font-size: 12px; color: #fff;"><img src="images/iconos/share2.png" alt="" style="width: 20px;"></a>
		 											<a href="files/bach/'.$nombre_obra.'/'.$reg2->nombre.'" download="'.$reg2->nombre.'" class="estilo_btn_plan_anual" style="color: #000000 !important; margin: 5px 10px; font-size: 12px; color: #fff;"><img src="images/iconos/download2.png" alt="" style="width: 20px;"></a>
													<button style="background-color: transparent; border: none; color: #000000;" onclick="eliminar_partitura('.$reg2->idpartitura.',\''.$nombre_obra.'\',\''.$reg2->nombre.'\');"><img src="images/iconos/trash.png" alt="" style="width: 20px;"></button>
												</div>
												
											
											
										</div>

									';

								}


						echo '
										</div>
									</div>
									<div style="margin-top: 30px; width: 20%; float: left; padding: 5px 10px; border-right: #cccccc 1px solid; height: 60px; padding-top: 20px; text-align: center;">
										<a style="font-size: 12px; border: #ccc 1px solid; border-radius: 10px; box-shadow: 5px 5px 10px rgba(0,0,0,0.2); padding: 9px; width: 25%; border-radius: 5px; background-color: #1F4168; color: #fff;" href="#modal_archivo_voz" onclick="subir_doc_voz_modal('.$reg->idvoz.',\''.$reg->voz.'\')">Nueva partitura</a>		
									</div>
								</div>
							</div>
							
							<div style="width: 10%; float: left; padding-top: 35px;">

								<div style="width: 100%; float: left; height: 50px; display: flex; justify-content:  center; align-items: center;">
									<button style="background-color: transparent; border: none;"><a style="padding: 9px; width: 25%; border-radius: 5px;" href="#modal2" onclick="editar_voz('.$reg->idvoz.',\''.$reg->voz.'\',\''.$reg->enlace.'\')"><img src="images/iconos/editar2.png" alt="" style="width: 20px;"></a></button>
									<button style="background-color: transparent; border: none; color: #fff;" onclick="eliminar_voz('.$reg->idvoz.');"><img src="images/iconos/trash.png" alt="" style="width: 20px;"></button>
								</div>
								
								
								
                            </div>
						</div>
						';
					}
		break;

		// <div style="width: 20%; float: left;">
		// 											<img src="images/iconos/score.png" alt="" style="width: 40px;">
													
		// 										</div>

		// <div style="width: 100%; float: left; display: flex; justify-content: center; padding: 5px;">
		// 										<a href="files/bach/'.$nombre_obra.'/'.$reg2->nombre.'" target="_blank" class="estilo_btn_plan_anual" style="margin: 5px 10px;"><img src="images/iconos/share.png" alt="" style="width: 20px;"></a>
		// 										<a href="files/bach/'.$nombre_obra.'/'.$reg2->nombre.'" download="'.$reg2->nombre.'" class="estilo_btn_plan_anual" style="margin: 5px 10px;"><img src="images/iconos/download.png" alt="" style="width: 20px;"></a>
												
		// 									</div>

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

		case 'subir_doc_voz':

			$idvoz_upload = $_POST['idvoz_upload'];
			$nom_voz_upload = $_POST['nom_voz_upload'];
			$fecha_reg_part = $_POST['fecha_reg_part'];

			$ar_coment = $_FILES["archivo_part"];			
			$nom=$_FILES['archivo_part']['name'];
			$ruta_anterior=$_FILES['archivo_part']['tmp_name'];
			$ruta_idvoz="../files/bach/".$nom_voz_upload;
			if (!file_exists($ruta_idvoz)) {
			    mkdir($ruta_idvoz, 0755, true);
			}
			$ruta="../files/bach/".$nom_voz_upload."/".$nom;
			move_uploaded_file($ruta_anterior, $ruta);

			date_default_timezone_set("America/Mexico_City");

			$rspta=$bach->subir_doc_voz($nom,$idvoz_upload,$fecha_reg_part);
	 		echo json_encode($rspta);
		break;

		case 'lista_partituras':

			$idvoz = $_GET['idvoz'];
			$nombre_obra = $_GET['nombre_obra'];

			$rspta = $bach->lista_partituras($idvoz);
				
			while ($reg = $rspta->fetch_object())
					{
					

						echo '

						<div style="padding: 15px; width: 100%; margin-top: 5px; box-shadow: 5px 5px 10px rgba(4, 47, 128, 0.1); border-radius: 10px;">
							<b>'.$reg->nombre.'</b><br><br>
							<a style="margin: 2px 10px;" href="files/bach/'.$nombre_obra.'/'.$reg->nombre.'" download="'.$reg->nombre.'" class="estilo_btn_plan_anual">Descargar</a>
							<a style="margin: 2px 10px;" href="files/bach/'.$nombre_obra.'/'.$reg->nombre.'" target="_blank" class="estilo_btn_plan_anual">Ver</a>
							
						</div>

						';	
					}
		break;

		case 'eliminar_partitura':
			
            $idpartitura = $_POST['idpartitura'];
			$nombre_obra = $_POST['nombre_obra'];
			$nombre_archivo = $_POST['nombre_archivo'];

			$ruta = "../files/bach/".$nombre_obra."/".$nombre_archivo."";
			unlink($ruta);
										
			$rspta=$bach->eliminar_partitura($idpartitura);
			echo json_encode($rspta);
	 		//echo $rspta ? "Anulada" : "No se puede anular";
		break;
}
?>