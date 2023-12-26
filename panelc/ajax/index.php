<?php
session_start(); 
require_once "../modelos/Index.php";

$index=new Index();


switch ($_GET["op"]){

		case 'list_iconos':
		

			$rspta = $index->list_iconos();
			while ($reg = $rspta->fetch_object())
					{
						
						
						echo '

							<div style="float: left; padding: 8px; border-style: solid; border-width: 1px; border-radius: 5px; margin: 3px;">
								<img src="'.$reg->direccion.'" style="width: 30px;">
							</div>
                               
                            

						';
						
					}

		break;

		case 'mostrar_list_act':
		

			$rspta = $index->mostrar_list_act();
			while ($reg = $rspta->fetch_object())
					{
						
						
						echo '

							<div style="padding: 6px;">
								<b>'.$reg->nom_activ.'</b>
							</div>
                               
                            

						';
						
					}

		break;

		
	
}
?>