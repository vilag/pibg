<?php
session_start(); 
require_once "../modelos/Voces_lumbrera.php";

$voces_lumbrera=new Voces_lumbrera();


switch ($_GET["op"]){
	
        case 'listar_obras':

			$nombre = $_GET['nombre'];

			$rspta = $voces_lumbrera->listar_obras($nombre);
				
			while ($reg = $rspta->fetch_object())
					{
					
						echo '							
							<div onclick="listar_voces('.$reg->idobra.',\''.$reg->nombre.'\');" style="line-height: 23px; padding: 15px 10px 10px 10px; border: #ccc 1px solid; background-color: #EBEFF1; margin-top: 5px; box-shadow: 5px 5px 10px rgba(0,0,0,0.2);">							
								<b style="margin-top: -5px; font-size: 20px;">'.$reg->nombre.'</b><br>
								<label>'.$reg->autor.'</label>
							</div>  
						';	
					}
		break;

        case 'listar_voces':

			$idobra = $_GET['idobra'];
			$nombre_obra = $_GET['nombre'];

			$rspta = $voces_lumbrera->listar_voces($idobra);
				
			while ($reg = $rspta->fetch_object())
					{
					

						echo '
							
						<div style="margin-top: 10px; width: 100%; height: 250px; background-color: #EEF2F3; border-radius: 10px; box-shadow: 5px 5px 10px rgba(0,0,0,0.2);">
							
							<div style="width: 100%; padding: 10px;">
								<div style="width: 100%; float: left;">
									<div style="width: 100%; padding: 10px; text-align: left;">
										<b style="font-size: 18px; margin-left: 7px; color: #1D4268;">'.$reg->nombre.'</b>   
									</div>
									<!--<button id="btn_play'.$reg->idvoz.'" onclick="PlaySound('.$reg->idvoz.');" style="display: none; background-color: rgba(0,0,0,0); border: none; margin: 7px;"><img src="images/iconos/play.png" style="width: 40px;" alt=""></button>
									<button id="btn_pause'.$reg->idvoz.'" onclick="PlaySound2('.$reg->idvoz.');" style="display: none; background-color: rgba(0,0,0,0); border: none; margin: 7px;"><img src="images/iconos/pausa.png" style="width: 40px;" alt=""></button>
									<button onclick="PlaySound3('.$reg->idvoz.');" style="display: none; background-color: rgba(0,0,0,0); border: none; margin: 7px;"><img src="images/iconos/reiniciar.png" style="width: 40px;" alt=""></button>
									<audio controls id="audio_voz'.$reg->idvoz.'" style="display: none; position: absolute; width: 100%;">
										<source src="'.$reg->enlace.'" type="audio/mp3">
										Your browser does not support the audio element.
									</audio>
									<audio controls="controls">
										<source src="'.$reg->enlace.'" type="audio/mpeg" />
										Your browser does not support the audio element.
									</audio>-->

									<audio controls="controls" style="margin-top: 10px; width: 100%;">
											<source src="'.$reg->enlace.'" type="audio/mpeg" />
											Your browser does not support the audio element.
									</audio>
								</div>
								<div class="barra_partituras" style=" width: 100%; float: left; overflow-x: scroll; padding: 5px 10px;">
									<div style="width: 2000px;">
						';	

								$idvoz=$reg->idvoz;

								$rspta2 = $index->lista_partituras($idvoz);
								while ($reg2 = $rspta2->fetch_object()){

									echo '

										<div style="margin-bottom: 10px; padding-top: 10px; border-radius: 10px; width: 250px; height: 80px; margin-left: 5px; float: left; border-right: rgba(0,0,0,0.2) 1px solid; border-left: rgba(0,0,0,0.2) 1px solid;">
											
												
												<div style="width: 100%; float: left; line-height: 15px; text-align: center;">
													<b style="font-size: 11px;">'.$reg2->nombre.'</b>
													<hr style="margin: 7px !important;">
													<a href="panelc/files/bach/'.$nombre_obra.'/'.$reg2->nombre.'" target="_blank" class="estilo_btn_plan_anual" style="color: #000000 !important; margin: 5px 10px; font-size: 12px; color: #fff;"><img src="panelc/images/iconos/share2.png" alt="" style="width: 20px;"></a>
		 											<a href="panelc/files/bach/'.$nombre_obra.'/'.$reg2->nombre.'" download="'.$reg2->nombre.'" class="estilo_btn_plan_anual" style="color: #000000 !important; margin: 5px 10px; font-size: 12px; color: #fff;"><img src="panelc/images/iconos/download2.png" alt="" style="width: 20px;"></a>
													
												</div>
												
											
											
										</div>

									';

								}


						echo '
									</div>
								</div>
								
							</div>
						</div>
						';
					}
		break;

        case 'listar_obras_1':

			//$nombre = $_GET['nombre'];

			$rspta = $voces_lumbrera->listar_obras_1();
				
			while ($reg = $rspta->fetch_object())
					{
					
						echo '							
							<div onclick="listar_voces('.$reg->idobra.',\''.$reg->nombre.'\');" style="line-height: 23px; padding: 15px 10px 10px 10px; border: #ccc 1px solid; background-color: #EBEFF1; margin-top: 5px; box-shadow: 5px 5px 10px rgba(0,0,0,0.2);">							
								<b style="margin-top: -5px; font-size: 20px;">'.$reg->nombre.'</b><br>
								<label>'.$reg->autor.'</label>
							</div>  
						';	
					}
		break;
		
	
}
?>