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

							<div class="col-lg-12" style="padding: 10px;">
								<p>'.$reg->fecha.'</p>
								<div class="col-lg-12">

						';

						$fecha = $reg->fecha;

						$rspta2 = $lectura_diaria->listar_lecturas_fechas($fecha);
						while ($reg2 = $rspta2->fetch_object())
						{
							if ($reg2->versiculo1>0) {
								$versiculo1 = ":".$reg2->versiculo1;
							}else{
								$versiculo1 = "";
							}
							if ($reg2->versiculo2>0) {
								$versiculo2 = "-".$reg2->versiculo2;
							}else{
								$versiculo2 = "";
							}
							echo '
								<div class="col-lg-12" style="height: 40px; padding: 10px 10px 5px 10px; background-color: #0067ad; border-radius: 10px 10px 0px 0px; box-shadow: 5px 5px 10px rgba(0,0,0,0.1); color: #fff; margin-top: 5px;">
									<div class="col-lg-10" style="float: left; cursor: pointer;" onclick="ver_textos_reg('.$reg2->idlectura.',\''.$reg2->libro.'\',\''.$reg2->capitulo.'\',\''.$reg2->versiculo1.'\',\''.$reg2->versiculo2.'\',\''.$reg2->tipo.'\');">
										<p style="cursor: pointer;"><b>'.$reg2->libro.' '.$reg2->capitulo.''.$versiculo1.''.$versiculo2.'</b></p>
										<input style="cursor: pointer;" id="input_text_regis'.$reg2->idlectura.'" type="hidden" value="0">
									</div>
									<div class="col-lg-2 estilo_btn_delete_text_reg">
										<img src="images/iconos/basura.png" style="width: 20px;">
									</div>
								</div>
								<div id="texto_regis'.$reg2->idlectura.'" class="col-lg-12" style="background-color: #fff; height: 0px; border-radius: 0px 0px 10px 10px; border-color: #ccc; box-shadow: 5px 5px 10px rgba(0,0,0,0.1); overflow-y: scroll;">
								</div>
							';
						}


						echo '
								</div>
							</div>
						';
						
						// echo '

						// 	<div class="col-lg-12">
						// 		<p>'.$reg->fecha.'</p>
						// 	</div>
                               
                        //     <tr>
                        //         <td class="py-1">
                        //             '.$reg->fecha.'
                        //         </td>
                        //         <td>
                        //             '.$reg->cita.'
                        //         </td>
                        //         <td>
                        //             <a href="'.$reg->link_cita.'" target="_blank"><button>Ver</button></a>
                                    
                        //         </td>
                                
                               
                        //     </tr>

						// ';
						
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

		case 'guardar_cita_send':
			
			$fecha = $_POST['fecha'];
			$tipo_cita_biblica = $_POST['tipo_cita_biblica'];
			$libro_cita_biblica = $_POST['libro_cita_biblica'];
			$capitulo_cita_biblica = $_POST['capitulo_cita_biblica'];
			$vers1_cita_biblica = $_POST['vers1_cita_biblica'];
			$vers2_cita_biblica = $_POST['vers2_cita_biblica'];
										
			$rspta=$lectura_diaria->guardar_cita_send($fecha,$tipo_cita_biblica,$libro_cita_biblica,$capitulo_cita_biblica,$vers1_cita_biblica,$vers2_cita_biblica);
			echo json_encode($rspta);
	 		//echo $rspta ? "Anulada" : "No se puede anular";
		break;

	
}
?>