<?php
session_start();
if (!isset($_SESSION["nombre"])) { http_response_code(403); exit; }
require_once "../modelos/Peticiones.php";
require_once "../config/secrets.php";

$pet = new Peticiones();

switch ($_GET["op"] ?? '') {

    case 'verificar_clave':
        $clave  = $_POST['clave'] ?? '';
        $pass   = defined('PETICIONES_PASS') ? PETICIONES_PASS : 'oracion2026';
        if ($clave === $pass) {
            $_SESSION['pet_auth'] = true;
            echo json_encode(['ok' => true]);
        } else {
            echo json_encode(['ok' => false, 'msg' => 'Contraseña incorrecta.']);
        }
        break;

    case 'listar':
        if (empty($_SESSION['pet_auth'])) { http_response_code(403); exit; }
        $filtro  = in_array($_GET['filtro'] ?? '', ['pendientes','atendidas']) ? $_GET['filtro'] : 'todas';
        $result  = $pet->listar($filtro);
        $total   = 0;
        if ($result) {
            while ($r = $result->fetch_object()) {
                $total++;
                $badge   = $r->atendida
                    ? '<span style="background:#28a745;color:#fff;padding:2px 10px;border-radius:12px;font-size:11px;">Atendida</span>'
                    : '<span style="background:#ffc107;color:#333;padding:2px 10px;border-radius:12px;font-size:11px;">Pendiente</span>';
                $toggle_lbl  = $r->atendida ? 'Marcar pendiente' : 'Marcar atendida';
                $toggle_val  = $r->atendida ? 0 : 1;
                $toggle_col  = $r->atendida ? '#6c757d' : '#28a745';
                $motivo_full = htmlspecialchars($r->motivo);
                $motivo_prev = mb_strlen($r->motivo) > 120
                    ? '<span class="motivo-prev">' . htmlspecialchars(mb_substr($r->motivo, 0, 120)) . '… <a href="#" onclick="toggleMotivo(this);return false;" style="font-size:11px;">ver más</a></span>'
                      . '<span class="motivo-full" style="display:none;">' . $motivo_full . ' <a href="#" onclick="toggleMotivo(this);return false;" style="font-size:11px;">ver menos</a></span>'
                    : $motivo_full;
                echo "
                <tr id='pet_row_{$r->id}'>
                    <td style='white-space:nowrap;'>" . htmlspecialchars($r->nombre) . "</td>
                    <td style='white-space:nowrap;'>" . htmlspecialchars($r->telefono ?: '—') . "</td>
                    <td style='font-size:13px;'>{$motivo_prev}</td>
                    <td style='white-space:nowrap;font-size:12px;color:#888;'>" . htmlspecialchars($r->fecha_hora) . "</td>
                    <td>{$badge}</td>
                    <td style='white-space:nowrap;'>
                        <button onclick=\"toggle_atendida({$r->id},{$toggle_val});\" style=\"background:{$toggle_col};padding:4px 8px;border-radius:5px;border:none;color:#fff;cursor:pointer;font-size:11px;margin-right:4px;\">{$toggle_lbl}</button>
                        <button onclick=\"eliminar_peticion({$r->id});\" style=\"background:rgb(129,2,2);padding:4px 8px;border-radius:5px;border:none;color:#fff;cursor:pointer;font-size:11px;\">Eliminar</button>
                    </td>
                </tr>";
            }
        }
        if ($total === 0) {
            echo "<tr><td colspan='6' style='text-align:center;color:#aaa;padding:24px;'>No hay peticiones en este filtro.</td></tr>";
        }
        break;

    case 'toggle_atendida':
        if (empty($_SESSION['pet_auth'])) { http_response_code(403); exit; }
        $ok = $pet->toggle_atendida((int)$_POST['id'], (int)$_POST['valor']);
        echo json_encode(['ok' => (bool)$ok]);
        break;

    case 'eliminar':
        if (empty($_SESSION['pet_auth'])) { http_response_code(403); exit; }
        $ok = $pet->eliminar((int)$_POST['id']);
        echo json_encode(['ok' => (bool)$ok]);
        break;

    case 'contar':
        echo json_encode(['n' => $pet->contar_pendientes()]);
        break;
}
?>
