<?php
session_start();
require_once "../modelos/Biografias.php";

$biografias = new Biografias();

switch ($_GET["op"]) {

    case 'listar':
        $rspta = $biografias->listar_biografias();
        while ($reg = $rspta->fetch_object()) {
            $imagen_html = $reg->imagen
                ? "<img src='" . htmlspecialchars($reg->imagen) . "' style='width:50px; height:50px; object-fit:cover; border-radius:50%;'>"
                : "<span style='color:#aaa;'>Sin imagen</span>";

            echo '
            <tr>
                <td>' . $reg->idbiografia . '</td>
                <td>' . $imagen_html . '</td>
                <td>' . htmlspecialchars($reg->nombre) . '</td>
                <td>' . htmlspecialchars($reg->cargo) . '</td>
                <td>' . date('d/m/Y', strtotime($reg->fecha_registro)) . '</td>
                <td>
                    <button onclick="editar_biografia(' . $reg->idbiografia . ',\'' . addslashes($reg->nombre) . '\',\'' . addslashes($reg->cargo) . '\',\'' . addslashes($reg->imagen) . '\',\'' . addslashes($reg->biografia) . '\');" style="background-color:#042C49; padding:8px 12px; border-radius:5px; border:none; color:#fff; cursor:pointer; margin-right:5px;">
                        Editar
                    </button>
                    <button onclick="borrar_biografia(' . $reg->idbiografia . ');" style="background-color:rgb(129,2,2); padding:8px 12px; border-radius:5px; border:none; color:#fff; cursor:pointer;">
                        Eliminar
                    </button>
                </td>
            </tr>
            ';
        }
    break;

    case 'get_one':
        $idbiografia = (int)$_GET['id'];
        $reg = $biografias->get_biografia($idbiografia);
        echo json_encode($reg);
    break;

    case 'guardar':
        $nombre    = $_POST['nombre'];
        $cargo     = $_POST['cargo'];
        $biografia = $_POST['biografia'];
        $imagen    = $_POST['imagen'];
        $rspta = $biografias->guardar_biografia($nombre, $cargo, $biografia, $imagen);
        echo json_encode($rspta);
    break;

    case 'actualizar':
        $idbiografia = $_POST['idbiografia'];
        $nombre      = $_POST['nombre'];
        $cargo       = $_POST['cargo'];
        $biografia   = $_POST['biografia'];
        $imagen      = $_POST['imagen'];
        $rspta = $biografias->actualizar_biografia($idbiografia, $nombre, $cargo, $biografia, $imagen);
        echo json_encode($rspta);
    break;

    case 'borrar':
        $idbiografia = $_POST['idbiografia'];
        $rspta = $biografias->borrar_biografia($idbiografia);
        echo json_encode($rspta);
    break;

    case 'subir_imagen':
        $extensiones_permitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (!isset($_FILES['imagen']) || $_FILES['imagen']['error'] !== UPLOAD_ERR_OK) {
            echo json_encode(['ok' => false, 'msg' => 'No se recibió ningún archivo.']);
            break;
        }
        $extension = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, $extensiones_permitidas)) {
            echo json_encode(['ok' => false, 'msg' => 'Formato no permitido. Use JPG, PNG, GIF o WEBP.']);
            break;
        }
        $carpeta = '../../images/biografias/';
        if (!file_exists($carpeta)) {
            mkdir($carpeta, 0755, true);
        }
        $nombre_archivo = 'bio_' . uniqid() . '.' . $extension;
        $ruta_destino   = $carpeta . $nombre_archivo;
        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_destino)) {
            echo json_encode(['ok' => true, 'ruta' => 'images/biografias/' . $nombre_archivo]);
        } else {
            echo json_encode(['ok' => false, 'msg' => 'Error al guardar el archivo.']);
        }
    break;
}
?>
