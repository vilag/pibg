<?php
session_start();
if (!isset($_SESSION["nombre"]) || $_SESSION['administrador'] != 1) { http_response_code(403); exit; }
require_once "../modelos/Academia_solicitudes.php";

$modelo = new Academia_solicitudes();

switch ($_GET['op'] ?? '') {

    case 'listar':
        $filtro = in_array($_GET['filtro'] ?? '', ['pendientes', 'atendidas']) ? $_GET['filtro'] : 'todas';
        $result = $modelo->listar($filtro);
        $total  = 0;
        if ($result) {
            while ($r = $result->fetch_object()) {
                $total++;
                $badge = $r->atendida
                    ? '<span style="background:#28a745;color:#fff;padding:2px 10px;border-radius:12px;font-size:11px;">Atendida</span>'
                    : '<span style="background:#ffc107;color:#333;padding:2px 10px;border-radius:12px;font-size:11px;">Pendiente</span>';
                $toggle_lbl = $r->atendida ? 'Marcar pendiente' : 'Marcar atendida';
                $toggle_val = $r->atendida ? 0 : 1;
                $toggle_col = $r->atendida ? '#6c757d' : '#28a745';
                echo "
                <tr>
                    <td style='white-space:nowrap;'>" . htmlspecialchars($r->nombre) . "</td>
                    <td style='white-space:nowrap;'><a href='mailto:" . htmlspecialchars($r->correo) . "'>" . htmlspecialchars($r->correo) . "</a></td>
                    <td style='white-space:nowrap;'>" . htmlspecialchars($r->telefono) . "</td>
                    <td style='font-size:13px;'>" . htmlspecialchars($r->instrumentos) . "</td>
                    <td style='white-space:nowrap;font-size:12px;color:#888;'>" . htmlspecialchars($r->fecha_hora) . "</td>
                    <td>{$badge}</td>
                    <td style='white-space:nowrap;'>
                        <button onclick=\"toggle_atendida_academia({$r->id},{$toggle_val});\" style=\"background:{$toggle_col};padding:4px 8px;border-radius:5px;border:none;color:#fff;cursor:pointer;font-size:11px;margin-right:4px;\">{$toggle_lbl}</button>
                        <button onclick=\"eliminar_solicitud_academia({$r->id});\" style=\"background:rgb(129,2,2);padding:4px 8px;border-radius:5px;border:none;color:#fff;cursor:pointer;font-size:11px;\">Eliminar</button>
                    </td>
                </tr>";
            }
        }
        if ($total === 0) {
            echo "<tr><td colspan='7' style='text-align:center;color:#aaa;padding:24px;'>No hay solicitudes en este filtro.</td></tr>";
        }
        break;

    case 'toggle_atendida':
        $ok = $modelo->toggle_atendida((int)($_POST['id'] ?? 0), (int)($_POST['valor'] ?? 0));
        echo json_encode(['ok' => (bool)$ok]);
        break;

    case 'eliminar':
        $ok = $modelo->eliminar((int)($_POST['id'] ?? 0));
        echo json_encode(['ok' => (bool)$ok]);
        break;

    case 'contar':
        echo json_encode(['n' => $modelo->contar_pendientes()]);
        break;

    case 'obtener_config':
        echo json_encode(['ok' => true, 'correos' => $modelo->obtener_correos_notificacion()]);
        break;

    case 'guardar_config':
        $correos = trim($_POST['correos'] ?? '');
        $lista   = array_filter(array_map('trim', explode(',', $correos)));

        if (empty($lista)) {
            echo json_encode(['ok' => false, 'msg' => 'Ingresa al menos un correo.']);
            break;
        }
        foreach ($lista as $c) {
            if (!filter_var($c, FILTER_VALIDATE_EMAIL)) {
                echo json_encode(['ok' => false, 'msg' => "El correo \"$c\" no es válido."]);
                exit;
            }
        }

        $modelo->guardar_correos_notificacion(implode(', ', $lista));
        echo json_encode(['ok' => true]);
        break;

    default:
        echo json_encode(['ok' => false, 'msg' => 'Operación no reconocida.']);
}
