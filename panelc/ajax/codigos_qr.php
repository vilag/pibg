<?php
session_start();
require_once "../modelos/Codigos_qr.php";

$codigos_qr = new Codigos_qr();

switch ($_GET['op'] ?? '') {

    case 'guardar_qr':
        $nombre          = $_POST['nombre']          ?? '';
        $contenido       = $_POST['contenido']       ?? '';
        $color_frente    = $_POST['color_frente']    ?? '#000000';
        $color_fondo     = $_POST['color_fondo']     ?? '#ffffff';
        $estilo_puntos   = $_POST['estilo_puntos']   ?? 'square';
        $nivel_correccion = $_POST['nivel_correccion'] ?? 'M';
        $imagen_base64   = $_POST['imagen_base64']   ?? '';
        $idactiv_relacionada = isset($_POST['idactiv_relacionada']) && $_POST['idactiv_relacionada'] !== ''
            ? intval($_POST['idactiv_relacionada']) : null;

        $id = $codigos_qr->guardar_qr($nombre, $contenido, $color_frente, $color_fondo, $estilo_puntos, $nivel_correccion, $imagen_base64, $idactiv_relacionada);
        echo json_encode(['ok' => true, 'id' => $id]);
        break;

    case 'editar_qr':
        $id              = intval($_POST['id'] ?? 0);
        $nombre          = $_POST['nombre']          ?? '';
        $contenido       = $_POST['contenido']       ?? '';
        $color_frente    = $_POST['color_frente']    ?? '#000000';
        $color_fondo     = $_POST['color_fondo']     ?? '#ffffff';
        $estilo_puntos   = $_POST['estilo_puntos']   ?? 'square';
        $nivel_correccion = $_POST['nivel_correccion'] ?? 'M';
        $imagen_base64   = $_POST['imagen_base64']   ?? '';

        $codigos_qr->editar_qr($id, $nombre, $contenido, $color_frente, $color_fondo, $estilo_puntos, $nivel_correccion, $imagen_base64);
        echo json_encode(['ok' => true, 'id' => $id]);
        break;

    case 'obtener_por_actividad':
        $idactiv = intval($_POST['idactiv'] ?? 0);
        $reg = $codigos_qr->obtener_qr_por_actividad($idactiv)->fetch_object();
        echo json_encode(['ok' => true, 'qr' => $reg ?: null]);
        break;

    case 'listar_qr':
        $rspta = $codigos_qr->listar_qr();
        while ($reg = $rspta->fetch_object()) {
            $fecha = date('d/m/Y H:i', strtotime($reg->fecha_creacion));
            $cont  = htmlspecialchars($reg->contenido, ENT_QUOTES);
            $nom   = htmlspecialchars($reg->nombre, ENT_QUOTES);
            echo '
            <tr>
                <td style="vertical-align:middle;">
                    <img src="' . $reg->imagen_base64 . '" style="width:56px;height:56px;object-fit:contain;border-radius:6px;border:1px solid #ddd;background:#fff;">
                </td>
                <td style="vertical-align:middle;font-weight:600;">' . $nom . '</td>
                <td style="vertical-align:middle;max-width:220px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="' . $cont . '">' . $cont . '</td>
                <td style="vertical-align:middle;white-space:nowrap;">' . $fecha . '</td>
                <td style="vertical-align:middle;white-space:nowrap;">
                    <button onclick="descargar_qr_guardado(' . $reg->id . ');" style="background:#042C49;padding:8px 12px;border-radius:6px;border:none;cursor:pointer;margin-right:4px;" title="Descargar">
                        <span style="color:#fff;font-size:13px;">&#11015; Bajar</span>
                    </button>
                    <button onclick="borrar_qr(' . $reg->id . ');" style="background:rgb(129,2,2);padding:8px;border-radius:6px;border:none;cursor:pointer;" title="Eliminar">
                        <img src="images/iconos/basura.png" style="width:18px;height:18px;" onerror="this.style.display=\'none\';this.parentNode.appendChild(document.createTextNode(\'🗑\'))">
                    </button>
                </td>
            </tr>';
        }
        break;

    case 'obtener_qr':
        $id    = intval($_POST['id'] ?? 0);
        $rspta = $codigos_qr->obtener_qr($id);
        $reg   = $rspta->fetch_object();
        echo json_encode($reg);
        break;

    case 'borrar_qr':
        $id    = intval($_POST['id'] ?? 0);
        $rspta = $codigos_qr->borrar_qr($id);
        echo json_encode(['ok' => true]);
        break;
}
?>
