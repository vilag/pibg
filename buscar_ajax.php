<?php
require_once "config/Conexion.php";

$q = isset($_GET['q']) ? limpiarCadena($_GET['q']) : '';
$resultados = [];

if (mb_strlen($q) >= 2) {

    // Sermones / Predicaciones
    $sql = "SELECT idsermones AS id, nom_sermon AS titulo, predicador AS sub, fecha_eti AS extra
            FROM sermones
            WHERE nom_sermon LIKE '%$q%' OR predicador LIKE '%$q%' OR predicacion LIKE '%$q%'
            ORDER BY idsermones DESC LIMIT 5";
    $r = ejecutarConsulta($sql);
    if ($r) {
        while ($row = $r->fetch_assoc()) {
            $resultados[] = [
                'tipo'  => 'Predicación',
                'titulo'=> $row['titulo'],
                'sub'   => trim($row['sub'] . ($row['extra'] ? ' · ' . $row['extra'] : ''), ' · '),
                'url'   => 'blog.php?id=' . (int)$row['id'],
                'icono' => 'fa-microphone'
            ];
        }
    }

    // Biografías
    $sql = "SELECT idbiografia AS id, nombre AS titulo, cargo AS sub
            FROM biografias
            WHERE nombre LIKE '%$q%' OR cargo LIKE '%$q%' OR biografia LIKE '%$q%'
            ORDER BY idbiografia DESC LIMIT 5";
    $r = ejecutarConsulta($sql);
    if ($r) {
        while ($row = $r->fetch_assoc()) {
            $resultados[] = [
                'tipo'  => 'Biografía',
                'titulo'=> $row['titulo'],
                'sub'   => $row['sub'],
                'url'   => 'biografia_detalle.php?id=' . (int)$row['id'],
                'icono' => 'fa-user'
            ];
        }
    }

    // Próximas actividades del calendario
    $sql = "SELECT idcal AS id, nom_activ AS titulo, tema AS sub, DATE(fecha_hora) AS fecha
            FROM calendario
            WHERE (nom_activ LIKE '%$q%' OR tema LIKE '%$q%') AND DATE(fecha_hora) >= CURDATE()
            ORDER BY fecha_hora ASC LIMIT 3";
    $r = ejecutarConsulta($sql);
    if ($r) {
        while ($row = $r->fetch_assoc()) {
            $sub = trim(($row['sub'] ?? '') . ($row['fecha'] ? ' · ' . $row['fecha'] : ''), ' · ');
            $resultados[] = [
                'tipo'  => 'Actividad',
                'titulo'=> $row['titulo'],
                'sub'   => $sub,
                'url'   => './#calendario',
                'icono' => 'fa-calendar'
            ];
        }
    }
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode($resultados, JSON_UNESCAPED_UNICODE);
?>
