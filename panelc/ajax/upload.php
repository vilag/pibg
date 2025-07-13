   <?php
   if ($_FILES['archivo']['error'] === UPLOAD_ERR_OK) {
       $nombre_original = $_FILES['archivo']['name'];
       $nombre_temporal = $_FILES['archivo']['tmp_name'];
       $extension = pathinfo($nombre_original, PATHINFO_EXTENSION);
       $carpeta_destino = '../files/bach/';
       $nombre_archivo = uniqid() . '.' . $extension;
       $ruta_destino = $carpeta_destino . $nombre_archivo;

       $extensiones_permitidas = array('jpg', 'jpeg', 'png', 'gif', 'pdf');

       if (in_array(strtolower($extension), $extensiones_permitidas)) {
           if (move_uploaded_file($nombre_temporal, $ruta_destino)) {
               echo "Archivo subido exitosamente a " . $ruta_destino;

                $servername = 'srv467.hstgr.io';
				$username = 'u690371019_pibg';
				$password = "1t;Ut]qW&";
				$dbname = "u690371019_pibg";
				$conn = new mysqli($servername, $username, $password, $dbname);







           } else {
               echo "Error al subir el archivo.";
           }
       } else {
           echo "Extensión de archivo no permitida.";
       }
   } else {
       echo "Error al subir el archivo: " . $_FILES['archivo']['error'];
   }
   ?>