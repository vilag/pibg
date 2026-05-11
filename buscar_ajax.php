<?php
ob_start();
require_once "config/Conexion.php";
ob_end_clean();

header('Content-Type: application/json; charset=utf-8');

global $conexion;
$q_raw = isset($_GET['q']) ? trim($_GET['q']) : '';
$resultados = [];

if (mb_strlen($q_raw, 'UTF-8') >= 2) {
    $q = mysqli_real_escape_string($conexion, $q_raw);

    // Sermones / Predicaciones
    $sql = "SELECT idsermones AS id, nom_sermon AS titulo, predicador AS sub, fecha_eti AS extra
            FROM sermones
            WHERE nom_sermon LIKE '%$q%' OR predicador LIKE '%$q%' OR predicacion LIKE '%$q%'
            ORDER BY idsermones DESC LIMIT 5";
    $r = $conexion->query($sql);
    if ($r) {
        while ($row = $r->fetch_assoc()) {
            $sub = $row['sub'];
            if ($row['extra']) $sub .= ' · ' . $row['extra'];
            $sub = trim($sub, ' · ');
            $resultados[] = [
                'tipo'  => 'Predicación',
                'titulo'=> $row['titulo'],
                'sub'   => $sub,
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
    $r = $conexion->query($sql);
    if ($r) {
        while ($row = $r->fetch_assoc()) {
            $resultados[] = [
                'tipo'  => 'Biografía',
                'titulo'=> $row['titulo'],
                'sub'   => $row['sub'] ?? '',
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
    $r = $conexion->query($sql);
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

echo json_encode($resultados, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
?>
