<?php
session_start();
require_once 'config/Conexion.php';   // CWD = panelc/ → panelc/config/Conexion.php

$token = trim($_GET['t'] ?? '');

/* ── Buscar encuesta por token de resultados ───────── */
$encuesta = null;
$error    = '';

if (!$token) {
    $error = 'Enlace no válido.';
} else {
    $t_safe  = $conexion->real_escape_string($token);
    $res_enc = ejecutarConsulta("SELECT * FROM encuestas WHERE token_resultados='$t_safe' AND resultados_publicos=1");
    $encuesta = $res_enc ? $res_enc->fetch_object() : null;
    if (!$encuesta) {
        $error = 'Este enlace de resultados no está disponible o es incorrecto.';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?php echo $encuesta ? htmlspecialchars($encuesta->titulo) . ' — Resultados' : 'Resultados de encuesta'; ?></title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <style>
    body { background:#f0f4f8; font-family:'Segoe UI',sans-serif; min-height:100vh; }
    .res-wrap { max-width:760px; margin:40px auto; padding:0 16px 60px; }
    .res-header { background:#042C49; color:#fff; border-radius:14px 14px 0 0; padding:28px 30px 20px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px; }
    .res-header h2 { margin:0; font-size:22px; font-weight:700; }
    .res-live { font-size:12px; opacity:.85; display:flex; align-items:center; gap:6px; }
    .res-live .dot { width:8px; height:8px; border-radius:50%; background:#2ecc71; animation:pulse 1.6s infinite; }
    @keyframes pulse { 0%{opacity:1;} 50%{opacity:.3;} 100%{opacity:1;} }
    .res-body { background:#fff; border-radius:0 0 14px 14px; padding:30px; box-shadow:0 4px 24px rgba(0,0,0,.1); }
    .res-error { text-align:center; padding:60px 20px; }
    .res-error .icon { font-size:60px; margin-bottom:16px; }
    .met-card { border:1px solid #e9ecef; border-radius:10px; padding:16px; margin-bottom:14px; background:#fff; }
    .met-titulo { font-size:14px; font-weight:700; color:#042C49; margin-bottom:10px; }
    .met-texto-list { max-height:160px; overflow-y:auto; background:#f8f9fa; border-radius:8px; padding:10px; }
    .met-texto-item { padding:4px 0; border-bottom:1px solid #e9ecef; font-size:13px; }
    .met-texto-item:last-child { border-bottom:none; }
    .met-stat-big { font-size:42px; font-weight:800; color:#042C49; line-height:1; }
    .met-historial { background:#fff8e6; border:1px solid #f5d98e; border-radius:8px; padding:10px 12px; margin-bottom:12px; }
    .met-historial-version { margin-bottom:8px; }
    .met-historial-version:last-child { margin-bottom:0; }
    .met-historial-tag { font-size:12px; font-weight:700; color:#8a6d1f; margin-bottom:4px; }
    .met-historial-fila { font-size:13px; color:#555; padding:2px 0 2px 20px; }
  </style>
</head>
<body>
<div class="res-wrap">

<?php if ($error): ?>
  <div class="res-header"><h2>Resultados</h2></div>
  <div class="res-body">
    <div class="res-error">
      <div class="icon">⚠️</div>
      <h4 style="color:#dc3545;"><?php echo htmlspecialchars($error); ?></h4>
    </div>
  </div>

<?php else: ?>
  <div class="res-header">
    <h2><?php echo htmlspecialchars($encuesta->titulo); ?></h2>
    <div class="res-live"><span class="dot"></span> En vivo</div>
  </div>
  <div class="res-body">
    <div id="res_contenido">
      <div class="text-center py-4 text-muted">Cargando...</div>
    </div>
  </div>
<?php endif; ?>

</div><!-- /res-wrap -->

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
'use strict';

<?php if (!$error): ?>
var RES_TOKEN = <?php echo json_encode($token); ?>;
var TIPO_LABELS = {
    libre:          'Respuesta libre',
    opcion_multiple:'Opción múltiple',
    casillas:       'Casillas (múltiple)',
    verdadero_falso:'Verdadero / Falso',
    si_no:          'Sí / No',
    calificacion:   'Calificación (1-5)',
};
var chart_instances = [];

/* ── Gráficas — estilo compartido (mismo criterio que el panel de
   administración: un solo color institucional, nunca uno distinto por
   barra; el valor se lee en la etiqueta, no adivinando por el color) ── */
var ENC_NAVY = '#042C49';
var ENC_INK  = '#495057';
var ENC_AXIS = '#898781';
var ENC_GRID = '#e9ecef';

function enc_navy_shade(t) {
    var r = Math.round(205 + (4  - 205) * t);
    var g = Math.round(219 + (44 - 219) * t);
    var b = Math.round(229 + (73 - 229) * t);
    return 'rgb(' + r + ',' + g + ',' + b + ')';
}

var encBarValueLabels = {
    id: 'encBarValueLabels',
    afterDatasetsDraw: function (chart) {
        var ctx = chart.ctx;
        chart.data.datasets.forEach(function (dataset, dsIndex) {
            var meta = chart.getDatasetMeta(dsIndex);
            if (meta.hidden) return;
            meta.data.forEach(function (el, idx) {
                var value = dataset.data[idx];
                if (value === null || value === undefined) return;
                ctx.save();
                ctx.font = '600 12px "Segoe UI",sans-serif';
                ctx.fillStyle = ENC_INK;
                ctx.textAlign = 'left';
                ctx.textBaseline = 'middle';
                ctx.fillText(value, el.x + 8, el.y);
                ctx.restore();
            });
        });
    },
};
Chart.register(encBarValueLabels);

function escHtml(s) {
    if (!s) return '';
    return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

function render_historial_html(historial) {
    var $box = $('<div class="met-historial"></div>');
    var con_respuestas = 0;
    historial.forEach(function (v) {
        var total_v = (v.textos ? v.textos.length : 0) ||
            v.respuestas.reduce(function (acc, r) { return acc + r.cnt; }, 0);
        if (!total_v) return;
        con_respuestas++;
        var $v = $('<div class="met-historial-version"></div>');
        $v.append('<div class="met-historial-tag">🕓 Antes de la corrección — "' + escHtml(v.pregunta) + '" (' + total_v + ' respuesta(s))</div>');
        if (v.textos) {
            v.textos.forEach(function (t) {
                $v.append('<div class="met-texto-item">💬 ' + escHtml(t) + '</div>');
            });
        } else {
            v.respuestas.forEach(function (r) {
                $v.append('<div class="met-historial-fila">' + escHtml(r.valor || '(sin respuesta)') + ': <b>' + r.cnt + '</b></div>');
            });
        }
        $box.append($v);
    });
    return con_respuestas ? $box : null;
}

function renderizar_metricas(data) {
    var $cont = $('#res_contenido');
    $cont.empty();
    chart_instances.forEach(function (c) { c.destroy(); });
    chart_instances = [];

    var $resumen = $('<div class="card met-card mb-3"><div class="row text-center"></div></div>');
    var $row = $resumen.find('.row');
    $row.append('<div class="col-6"><div class="met-stat-big">' + data.total_respuestas + '</div><div style="font-size:12px;color:#666;">respuestas totales</div></div>');
    $row.append('<div class="col-6"><div class="met-stat-big">' + data.preguntas.length + '</div><div style="font-size:12px;color:#666;">preguntas</div></div>');
    $cont.append($resumen);

    if (data.total_respuestas === 0) {
        $cont.append('<div class="text-center text-muted py-3" style="font-size:14px;">Aún no hay respuestas para esta encuesta.</div>');
        return;
    }

    data.preguntas.forEach(function (p, i) {
        var $card = $('<div class="met-card"></div>');
        $card.append('<div class="met-titulo">P' + (i + 1) + '. ' + escHtml(p.pregunta) + ' <span style="font-size:11px;color:#aaa;font-weight:400;">(' + TIPO_LABELS[p.tipo] + ')</span></div>');

        if (p.historial && p.historial.length) {
            var $historial = render_historial_html(p.historial);
            if ($historial) $card.append($historial);
        }

        if (p.tipo === 'libre') {
            var textos = p.textos || [];
            if (!textos.length) {
                $card.append('<div class="text-muted" style="font-size:13px;">Sin respuestas de texto.</div>');
            } else {
                var $list = $('<div class="met-texto-list"></div>');
                textos.forEach(function (t) {
                    $list.append('<div class="met-texto-item">💬 ' + escHtml(t) + '</div>');
                });
                $card.append($list);
                $card.append('<div style="font-size:11px;color:#aaa;margin-top:6px;">' + textos.length + ' respuesta(s)</div>');
            }
            $cont.append($card);
        } else if (p.tipo === 'calificacion') {
            var labels = [], values = [], colors = [], total_c = 0;
            var dist = { '1': 0, '2': 0, '3': 0, '4': 0, '5': 0 };
            p.respuestas.forEach(function (r) { if (dist[r.valor] !== undefined) { dist[r.valor] = r.cnt; total_c += r.cnt; } });
            for (var n = 1; n <= 5; n++) {
                labels.push(n + ' estrella' + (n === 1 ? '' : 's'));
                values.push(dist[String(n)]);
                colors.push(enc_navy_shade(n / 5));
            }
            var promedio = p.promedio || 0;
            $card.append('<div style="font-size:22px;font-weight:700;color:#042C49;margin-bottom:10px;">★ ' +
                promedio + ' <span style="font-size:13px;font-weight:400;color:#6c757d;">/ 5 · ' + total_c + ' respuesta(s)</span></div>');
            var canvas_id = 'chart_' + i;
            $card.append('<canvas id="' + canvas_id + '" height="150"></canvas>');
            $cont.append($card);
            var max_v = Math.max.apply(null, values.concat([1]));
            var ctx = document.getElementById(canvas_id).getContext('2d');
            chart_instances.push(new Chart(ctx, {
                type: 'bar',
                data: { labels: labels, datasets: [{ label: 'Respuestas', data: values, backgroundColor: colors,
                    borderRadius: { topRight: 4, bottomRight: 4 }, barPercentage: 0.6, maxBarThickness: 24 }] },
                options: {
                    indexAxis: 'y',
                    layout: { padding: { right: 26 } },
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { beginAtZero: true, suggestedMax: max_v * 1.2,
                             ticks: { precision: 0, color: ENC_AXIS },
                             grid: { color: ENC_GRID }, border: { display: false } },
                        y: { grid: { display: false }, border: { display: false },
                             ticks: { color: ENC_INK, font: { size: 12 } } },
                    },
                },
            }));
        } else {
            var labels2 = [], values2 = [];
            p.respuestas.forEach(function (r) {
                labels2.push(escHtml(r.valor) || '(sin respuesta)');
                values2.push(r.cnt);
            });

            var canvas_id2 = 'chart_' + i;
            var alto2 = Math.max(90, labels2.length * 36 + 24);
            $card.append('<canvas id="' + canvas_id2 + '" height="' + alto2 + '"></canvas>');
            $cont.append($card);
            var max_v2 = Math.max.apply(null, values2.concat([1]));
            var ctx2 = document.getElementById(canvas_id2).getContext('2d');
            chart_instances.push(new Chart(ctx2, {
                type: 'bar',
                data: { labels: labels2, datasets: [{ label: 'Respuestas', data: values2, backgroundColor: ENC_NAVY,
                    borderRadius: { topRight: 4, bottomRight: 4 }, barPercentage: 0.6, maxBarThickness: 24 }] },
                options: {
                    indexAxis: 'y',
                    layout: { padding: { right: 30 } },
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { beginAtZero: true, suggestedMax: max_v2 * 1.18,
                             ticks: { precision: 0, color: ENC_AXIS },
                             grid: { color: ENC_GRID }, border: { display: false } },
                        y: { grid: { display: false }, border: { display: false },
                             ticks: { color: ENC_INK, font: { size: 12 } } },
                    },
                },
            }));
        }
    });
}

function cargar_resultados() {
    $.post('ajax/encuestas.php?op=obtener_metricas_publica', { token: RES_TOKEN }, function (data) {
        var d;
        try { d = JSON.parse(data); } catch (e) { return; }
        if (!d.ok) {
            $('#res_contenido').html('<div class="text-center text-danger py-3">' + escHtml(d.msg || 'Enlace no válido.') + '</div>');
            return;
        }
        renderizar_metricas(d);
    });
}

$(document).ready(function () {
    cargar_resultados();
    setInterval(cargar_resultados, 8000);
});
<?php endif; ?>
</script>
</body>
</html>
