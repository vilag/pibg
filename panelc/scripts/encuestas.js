'use strict';

/* ══════════════════════════════════════════════════════
   Estado global
══════════════════════════════════════════════════════ */
var qb_idx       = 0;
var chart_instances = [];

var TIPO_LABELS = {
    libre:          'Respuesta libre',
    opcion_multiple:'Opción múltiple',
    casillas:       'Casillas (múltiple)',
    verdadero_falso:'Verdadero / Falso',
    si_no:          'Sí / No',
    calificacion:   'Calificación (1-5)',
};

/* ══════════════════════════════════════════════════════
   Inicialización
══════════════════════════════════════════════════════ */
document.addEventListener('DOMContentLoaded', function () {
    listar_encuestas();

    // ── Imagen de encabezado ──
    $('#enc_img_file').on('change', function (e) {
        var file = e.target.files[0];
        if (!file) return;
        var reader = new FileReader();
        reader.onloadend = function () {
            $('#enc_img_preview').attr('src', reader.result).show();
            $('#enc_img_file').data('base64', reader.result);
            $('#enc_imagen_base64').val(reader.result);
            $('#enc_img_clear').show();
        };
        reader.readAsDataURL(file);
    });

    $('#enc_img_clear').on('click', function () {
        limpiar_imagen_encabezado();
    });

    $('#enc_img2_file').on('change', function (e) {
        var file = e.target.files[0];
        if (!file) return;
        var reader = new FileReader();
        reader.onloadend = function () {
            $('#enc_img2_preview').attr('src', reader.result).show();
            $('#enc_imagen_secundaria_base64').val(reader.result);
            $('#enc_img2_clear').show();
        };
        reader.readAsDataURL(file);
    });

    $('#enc_img2_clear').on('click', function () {
        limpiar_imagen_secundaria();
    });

    $('#modalMetricas').on('hidden.bs.modal', function () {
        chart_instances.forEach(function (c) { c.destroy(); });
        chart_instances = [];
    });

    // ── Toggle público/privado ──
    $('#comp_toggle').on('change', function () {
        var activar = this.checked ? 1 : 0;
        var id      = $('#comp_id').val();
        $.post('ajax/encuestas.php?op=toggle_publica', { id: id, activar: activar }, function (data) {
            data = JSON.parse(data);
            if (data.ok && data.url) {
                mostrar_link(data.url);
            } else {
                ocultar_link();
            }
            listar_encuestas();
        });
    });
});

/* ══════════════════════════════════════════════════════
   Lista de encuestas
══════════════════════════════════════════════════════ */
function listar_encuestas() {
    $.post('ajax/encuestas.php?op=listar_encuestas', function (r) {
        $('#tabla_encuestas').html(r);
    });
}

/* ══════════════════════════════════════════════════════
   BUILDER — abrir modal
══════════════════════════════════════════════════════ */
function nueva_encuesta() {
    $('#enc_id').val('');
    $('#enc_titulo').val('');
    $('#enc_descripcion').val('');
    $('#enc_fecha_inicio').val('');
    $('#enc_fecha_fin').val('');
    limpiar_imagen_encabezado();
    limpiar_imagen_secundaria();
    $('#qb_lista').empty();
    qb_idx = 0;
    $('#modalEncuestaTitulo').text('Nueva encuesta');
    actualizar_vacio();
    $('#modalEncuesta').modal('show');
}

function limpiar_imagen_encabezado() {
    $('#enc_img_file').val('').data('base64', null);
    $('#enc_img_preview').hide().attr('src', '');
    $('#enc_img_clear').hide();
    $('#enc_imagen_base64').val('');
}

function limpiar_imagen_secundaria() {
    $('#enc_img2_file').val('');
    $('#enc_img2_preview').hide().attr('src', '');
    $('#enc_img2_clear').hide();
    $('#enc_imagen_secundaria_base64').val('');
}

function editar_encuesta(id) {
    $.post('ajax/encuestas.php?op=obtener_encuesta', { id: id }, function (data) {
        data = JSON.parse(data);
        var e = data.encuesta;
        $('#enc_id').val(e.id);
        $('#enc_titulo').val(e.titulo);
        $('#enc_descripcion').val(e.descripcion || '');
        $('#enc_fecha_inicio').val(e.fecha_inicio || '');
        $('#enc_fecha_fin').val(e.fecha_fin || '');
        limpiar_imagen_encabezado();
        limpiar_imagen_secundaria();
        if (e.imagen_base64 && e.imagen_base64.length > 10) {
            $('#enc_img_preview').attr('src', e.imagen_base64).show();
            $('#enc_img_file').data('base64', e.imagen_base64);
            $('#enc_imagen_base64').val(e.imagen_base64);
            $('#enc_img_clear').show();
        }
        if (e.imagen_secundaria_base64 && e.imagen_secundaria_base64.length > 10) {
            $('#enc_img2_preview').attr('src', e.imagen_secundaria_base64).show();
            $('#enc_imagen_secundaria_base64').val(e.imagen_secundaria_base64);
            $('#enc_img2_clear').show();
        }
        $('#qb_lista').empty();
        qb_idx = 0;
        $('#modalEncuestaTitulo').text('Editar encuesta');
        data.preguntas.forEach(function (p) {
            agregar_pregunta(p);
        });
        actualizar_vacio();
        $('#modalEncuesta').modal('show');
    });
}

/* ══════════════════════════════════════════════════════
   BUILDER — agregar pregunta
══════════════════════════════════════════════════════ */
function agregar_pregunta(datos) {
    var idx  = qb_idx++;
    var tipo = (datos && datos.tipo) ? datos.tipo : 'libre';

    var opciones_html = '';
    if (datos && datos.opciones && datos.opciones.length) {
        datos.opciones.forEach(function (op) {
            opciones_html += opcion_row_html(op);
        });
    }

    var $q = $('\
<div class="qb-pregunta" data-idx="' + idx + '">\
  <div class="card-header">\
    <div class="d-flex align-items-center">\
      <span class="qb-num font-weight-bold mr-2" style="color:#042C49;min-width:28px;">P' + (idx + 1) + '</span>\
      <select class="form-control form-control-sm qb-tipo">\
        <option value="libre">Respuesta libre</option>\
        <option value="opcion_multiple">Opción múltiple</option>\
        <option value="casillas">Casillas (múltiple)</option>\
        <option value="verdadero_falso">Verdadero / Falso</option>\
        <option value="si_no">Sí / No</option>\
        <option value="calificacion">Calificación (1-5)</option>\
      </select>\
    </div>\
    <div class="d-flex align-items-center">\
      <label class="qb-requerida-lbl mr-3 mb-0">\
        <input type="checkbox" class="qb-requerida mr-1"> Requerida\
      </label>\
      <button class="btn btn-sm btn-danger qb-eliminar">✕</button>\
    </div>\
  </div>\
  <div class="card-body">\
    <input type="text" class="form-control mb-2 qb-texto" placeholder="Escribe tu pregunta aquí...">\
    <div class="qb-opciones-area" style="display:none;">\
      <div class="qb-opciones-list">' + opciones_html + '</div>\
      <button type="button" class="btn btn-sm btn-outline-secondary qb-add-opcion mt-1">+ Agregar opción</button>\
    </div>\
    <div class="qb-preview-hint"></div>\
  </div>\
</div>');

    $q.find('.qb-tipo').val(tipo);
    if (datos && datos.pregunta)   $q.find('.qb-texto').val(datos.pregunta);
    if (datos && datos.requerida)  $q.find('.qb-requerida').prop('checked', true);

    actualizar_tipo_ui($q, tipo);
    if (!opciones_html && (tipo === 'opcion_multiple' || tipo === 'casillas')) {
        agregar_opcion_a($q);
        agregar_opcion_a($q);
    }

    // ── Eventos ──
    $q.find('.qb-tipo').on('change', function () {
        var t = $(this).val();
        actualizar_tipo_ui($q, t);
    });

    $q.find('.qb-eliminar').on('click', function () {
        $q.remove();
        renumerar_preguntas();
        actualizar_vacio();
    });

    $q.find('.qb-add-opcion').on('click', function () {
        agregar_opcion_a($q);
    });

    $('#qb_lista').append($q);
    actualizar_vacio();
}

function opcion_row_html(valor) {
    return '<div class="qb-opcion-row">' +
        '<input type="text" class="qb-opcion-input form-control form-control-sm" value="' + escHtml(valor) + '" placeholder="Opción...">' +
        '<button type="button" class="qb-remove-opcion">✕</button>' +
        '</div>';
}

function agregar_opcion_a($q) {
    var $row = $(opcion_row_html(''));
    $row.find('.qb-remove-opcion').on('click', function () { $row.remove(); });
    $q.find('.qb-opciones-list').append($row);
}

function actualizar_tipo_ui($q, tipo) {
    var $hint = $q.find('.qb-preview-hint');
    var $opc  = $q.find('.qb-opciones-area');
    $hint.text('');
    $opc.hide();

    if (tipo === 'opcion_multiple' || tipo === 'casillas') {
        $opc.show();
        $hint.text(tipo === 'opcion_multiple' ? '(Una sola respuesta)' : '(Múltiples respuestas)');
    } else if (tipo === 'verdadero_falso') {
        $hint.text('Opciones fijas: Verdadero · Falso');
    } else if (tipo === 'si_no') {
        $hint.text('Opciones fijas: Sí · No');
    } else if (tipo === 'calificacion') {
        $hint.text('Escala de 1 (muy malo) a 5 (excelente)');
    } else {
        $hint.text('El participante escribirá su respuesta libremente');
    }
}

function renumerar_preguntas() {
    $('#qb_lista .qb-pregunta').each(function (i) {
        $(this).find('.qb-num').text('P' + (i + 1));
    });
}

function actualizar_vacio() {
    var hay = $('#qb_lista .qb-pregunta').length > 0;
    $('#qb_vacio').toggle(!hay);
}

/* ══════════════════════════════════════════════════════
   Serializar preguntas desde DOM
══════════════════════════════════════════════════════ */
function serializar_preguntas() {
    var preguntas = [];
    $('#qb_lista .qb-pregunta').each(function () {
        var $q   = $(this);
        var tipo = $q.find('.qb-tipo').val();
        var p = {
            tipo:     tipo,
            pregunta: $q.find('.qb-texto').val().trim(),
            requerida: $q.find('.qb-requerida').is(':checked') ? 1 : 0,
            opciones: [],
        };
        if (tipo === 'opcion_multiple' || tipo === 'casillas') {
            $q.find('.qb-opcion-input').each(function () {
                var v = $(this).val().trim();
                if (v) p.opciones.push(v);
            });
        }
        preguntas.push(p);
    });
    return preguntas;
}

/* ══════════════════════════════════════════════════════
   Guardar encuesta
══════════════════════════════════════════════════════ */
function guardar_encuesta() {
    var titulo = $('#enc_titulo').val().trim();
    if (!titulo) { bootbox.alert('El título es obligatorio.'); return; }

    var preguntas = serializar_preguntas();
    if (!preguntas.length) { bootbox.alert('Agrega al menos una pregunta.'); return; }

    var ok = true;
    preguntas.forEach(function (p, i) {
        if (!p.pregunta) { bootbox.alert('La pregunta ' + (i + 1) + ' no tiene texto.'); ok = false; }
        if ((p.tipo === 'opcion_multiple' || p.tipo === 'casillas') && p.opciones.length < 2) {
            bootbox.alert('La pregunta ' + (i + 1) + ' necesita al menos 2 opciones.'); ok = false;
        }
    });
    if (!ok) return;

    var id  = $('#enc_id').val();
    var op  = id ? 'editar_encuesta' : 'crear_encuesta';
    var datos = {
        titulo:                   titulo,
        descripcion:              $('#enc_descripcion').val().trim(),
        fecha_inicio:             $('#enc_fecha_inicio').val(),
        fecha_fin:                $('#enc_fecha_fin').val(),
        imagen_base64:            $('#enc_imagen_base64').val(),
        imagen_secundaria_base64: $('#enc_imagen_secundaria_base64').val(),
        preguntas:                JSON.stringify(preguntas),
    };
    if (id) datos.id = id;

    $.post('ajax/encuestas.php?op=' + op, datos, function (data) {
        var r;
        try { r = JSON.parse(data); } catch (e) {
            console.error('Respuesta del servidor:', data);
            bootbox.alert('Error de servidor. Revisa la consola (F12) y verifica que las tablas SQL existan.');
            return;
        }
        if (r.ok) {
            $('#modalEncuesta').modal('hide');
            listar_encuestas();
            bootbox.alert(id ? '✓ Encuesta actualizada.' : '✓ Encuesta creada exitosamente.');
        } else {
            bootbox.alert('Error al guardar' + (r.msg ? ': ' + r.msg : '.'));
        }
    });
}

/* ══════════════════════════════════════════════════════
   Borrar encuesta
══════════════════════════════════════════════════════ */
function borrar_encuesta(id) {
    bootbox.confirm({
        message: '¿Eliminar esta encuesta y todas sus respuestas?',
        buttons: {
            confirm: { label: 'Sí, eliminar', className: 'btn-danger' },
            cancel:  { label: 'Cancelar',     className: 'btn-secondary' },
        },
        callback: function (r) {
            if (r) {
                $.post('ajax/encuestas.php?op=borrar_encuesta', { id: id }, function () {
                    listar_encuestas();
                    bootbox.alert('Encuesta eliminada.');
                });
            }
        },
    });
}

/* ══════════════════════════════════════════════════════
   Compartir
══════════════════════════════════════════════════════ */
function compartir_encuesta(id) {
    $.post('ajax/encuestas.php?op=obtener_encuesta', { id: id }, function (data) {
        data = JSON.parse(data);
        var e = data.encuesta;
        $('#comp_id').val(e.id);
        $('#comp_toggle').prop('checked', e.es_publica == 1);
        if (e.es_publica && e.token_publico) {
            mostrar_link(generar_url(e.token_publico));
        } else {
            ocultar_link();
        }
        $('#comp_copiado').hide();
        $('#modalCompartir').modal('show');
    });
}

function generar_url(token) {
    var base = window.location.origin;
    var path = window.location.pathname.replace(/\/[^/]+$/, '');
    return base + path + '/encuesta_publica.php?t=' + token;
}

function mostrar_link(url) {
    $('#comp_link').val(url);
    $('#comp_link_area').show();
    $('#comp_privado_msg').hide();
}

function ocultar_link() {
    $('#comp_link_area').hide();
    $('#comp_privado_msg').show();
}

function copiar_link() {
    var el = document.getElementById('comp_link');
    el.select();
    document.execCommand('copy');
    $('#comp_copiado').show();
}

/* ══════════════════════════════════════════════════════
   Métricas
══════════════════════════════════════════════════════ */
function ver_metricas(id) {
    $('#metricas_contenido').html('<div class="text-center py-4"><span class="text-muted">Cargando...</span></div>');
    $('#modalMetricas').modal('show');

    $.post('ajax/encuestas.php?op=obtener_metricas', { id: id }, function (data) {
        data = JSON.parse(data);
        renderizar_metricas(data);
    });
}

function renderizar_metricas(data) {
    var $cont = $('#metricas_contenido');
    $cont.empty();

    // ── Resumen general ──
    var $resumen = $('<div class="card enc-card mb-3"><div class="card-body"><div class="row text-center"></div></div></div>');
    var $row = $resumen.find('.row');
    $row.append('<div class="col-4"><div class="met-stat-big">' + data.total_respuestas + '</div><div style="font-size:12px;color:#666;">respuestas totales</div></div>');
    $row.append('<div class="col-4"><div class="met-stat-big">' + data.preguntas.length + '</div><div style="font-size:12px;color:#666;">preguntas</div></div>');
    var tasa = data.total_respuestas > 0 ? '✓' : '—';
    $row.append('<div class="col-4"><div class="met-stat-big" style="color:#28a745;">' + tasa + '</div><div style="font-size:12px;color:#666;">estado</div></div>');
    $cont.append($resumen);

    if (data.total_respuestas === 0) {
        $cont.append('<div class="text-center text-muted py-3" style="font-size:14px;">Aún no hay respuestas para esta encuesta.</div>');
        return;
    }

    // ── Por pregunta ──
    data.preguntas.forEach(function (p, i) {
        var $card = $('<div class="met-card"></div>');
        $card.append('<div class="met-titulo">P' + (i + 1) + '. ' + escHtml(p.pregunta) + ' <span style="font-size:11px;color:#aaa;font-weight:400;">(' + TIPO_LABELS[p.tipo] + ')</span></div>');

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
        } else if (p.tipo === 'calificacion') {
            var labels = [], values = [], total_c = 0;
            var dist = { '1': 0, '2': 0, '3': 0, '4': 0, '5': 0 };
            p.respuestas.forEach(function (r) { if (dist[r.valor] !== undefined) { dist[r.valor] = r.cnt; total_c += r.cnt; } });
            for (var n = 1; n <= 5; n++) { labels.push('⭐'.repeat(n)); values.push(dist[String(n)]); }
            var promedio = p.promedio || 0;
            $card.append('<div style="font-size:22px;font-weight:700;color:#f5a623;margin-bottom:8px;">' +
                '★ ' + promedio + ' / 5</div>');
            var canvas_id = 'chart_' + i;
            $card.append('<canvas id="' + canvas_id + '" height="80"></canvas>');
            $cont.append($card);
            var ctx = document.getElementById(canvas_id).getContext('2d');
            chart_instances.push(new Chart(ctx, {
                type: 'bar',
                data: { labels: labels, datasets: [{ label: 'Respuestas', data: values,
                    backgroundColor: ['#e74c3c','#e67e22','#f1c40f','#2ecc71','#27ae60'], borderRadius: 6 }] },
                options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } },
            }));
            return;
        } else {
            var labels2 = [], values2 = [], bg = [];
            var palette = ['#042C49','#1D6F42','#f5a623','#e74c3c','#8e44ad','#2980b9','#16a085','#c0392b','#2c3e50','#d35400'];
            p.respuestas.forEach(function (r, ri) {
                labels2.push(escHtml(r.valor) || '(sin respuesta)');
                values2.push(r.cnt);
                bg.push(palette[ri % palette.length]);
            });

            var usePie = (p.tipo === 'verdadero_falso' || p.tipo === 'si_no');
            var canvas_id2 = 'chart_' + i;
            $card.append('<canvas id="' + canvas_id2 + '" height="' + (usePie ? '140' : '80') + '"></canvas>');
            $cont.append($card);
            var ctx2 = document.getElementById(canvas_id2).getContext('2d');
            chart_instances.push(new Chart(ctx2, {
                type: usePie ? 'pie' : 'bar',
                data: { labels: labels2, datasets: [{ label: 'Respuestas', data: values2, backgroundColor: bg, borderRadius: usePie ? 0 : 6 }] },
                options: {
                    indexAxis: usePie ? undefined : 'y',
                    plugins: { legend: { display: usePie, position: 'right' } },
                    scales: usePie ? {} : { x: { beginAtZero: true, ticks: { stepSize: 1 } } },
                },
            }));
            return;
        }
        $cont.append($card);
    });
}

/* ══════════════════════════════════════════════════════
   Exportar a Excel (SheetJS)
══════════════════════════════════════════════════════ */
function exportar_excel(id, titulo) {
    $.post('ajax/encuestas.php?op=exportar_respuestas', { id: id }, function (data) {
        data = JSON.parse(data);
        var preguntas  = data.preguntas;
        var respuestas = data.respuestas;

        var pids = Object.keys(preguntas);
        var headers = ['#', 'Fecha', 'IP'].concat(pids.map(function (pid) { return preguntas[pid]; }));
        var rows = [headers];

        respuestas.forEach(function (r, i) {
            var fila = [i + 1, r.fecha, r.ip];
            pids.forEach(function (pid) { fila.push(r['p_' + pid] || ''); });
            rows.push(fila);
        });

        var ws = XLSX.utils.aoa_to_sheet(rows);
        var wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, 'Respuestas');

        // Anchos automáticos
        var col_widths = headers.map(function (h) { return { wch: Math.max(h.length, 12) }; });
        ws['!cols'] = col_widths;

        XLSX.writeFile(wb, titulo + '_respuestas.xlsx');
    });
}

/* ══════════════════════════════════════════════════════
   Util
══════════════════════════════════════════════════════ */
function escHtml(s) {
    if (!s) return '';
    return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}
