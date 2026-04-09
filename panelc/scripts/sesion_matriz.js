/* ============================================================
   sesion_matriz.js
   Gestión de sesiones + matriz de asistencia (52 columnas)
============================================================ */

var idsesion_actual = 0;
var registros_actuales = [];
var modo_edicion = false;

/* ---- Inicialización ---- */
document.addEventListener("DOMContentLoaded", function () {
    construir_encabezado_matriz();
    listar_sesiones();
});

/* ============================================================
   ENCABEZADO DE LA TABLA (columnas 1–52)
============================================================ */
function construir_encabezado_matriz() {
    var row = $("#thead_row");
    for (var i = 1; i <= 52; i++) {
        row.append('<th style="min-width:32px;">' + i + '</th>');
    }
}

/* ============================================================
   SESIONES
============================================================ */
function listar_sesiones() {
    $("#spinner_carga").show();
    $.get("ajax/sesion_matriz.php?op=listar_sesiones", function (data) {
        $("#spinner_carga").hide();
        var lista = JSON.parse(data);
        var html = '';

        if (lista.length === 0) {
            html = '<p style="color:#888; text-align:center; padding:20px;">No hay sesiones registradas. Crea una nueva.</p>';
        } else {
            lista.forEach(function (s) {
                html += '<div class="card-sesion">' +
                    '<div class="info" onclick="abrir_sesion(' + s.idsesion + ',\'' + escapar(s.nombre) + '\',\'' + escapar(s.descripcion) + '\');">' +
                    '<h6>' + s.nombre + '</h6>' +
                    '<small>' + (s.descripcion ? s.descripcion + ' &nbsp;|&nbsp; ' : '') +
                    s.total_registros + ' registro(s) &nbsp;|&nbsp; Creada: ' + s.fecha_creacion + '</small>' +
                    '</div>' +
                    '<div class="acciones">' +
                    '<button class="btn btn-sm btn-outline-secondary" ' +
                    'onclick="editar_sesion(event,' + s.idsesion + ',\'' + escapar(s.nombre) + '\',\'' + escapar(s.descripcion) + '\');">Editar</button>' +
                    '<button class="btn btn-sm btn-outline-danger" ' +
                    'onclick="pedir_eliminar_sesion(event,' + s.idsesion + ');">Eliminar</button>' +
                    '</div>' +
                    '</div>';
            });
        }

        $("#lista_sesiones").html(html);
    });
}

function abrir_modal_nueva_sesion() {
    $("#titulo_modal_sesion").text("Nueva Lista");
    $("#idsesion_edit").val("0");
    $("#nombre_sesion").val("");
    $("#desc_sesion").val("");
}

function editar_sesion(e, idsesion, nombre, descripcion) {
    e.stopPropagation();
    $("#titulo_modal_sesion").text("Editar Lista");
    $("#idsesion_edit").val(idsesion);
    $("#nombre_sesion").val(nombre);
    $("#desc_sesion").val(descripcion);
    window.location.hash = "modal_nueva_sesion";
}

function guardar_sesion() {
    var nombre = $.trim($("#nombre_sesion").val());
    var desc   = $.trim($("#desc_sesion").val());
    var id     = $("#idsesion_edit").val();

    if (nombre === "") { alert("El nombre de la lista es obligatorio."); return; }

    var op   = id == "0" ? "crear_sesion" : "actualizar_sesion";
    var data = { nombre: nombre, descripcion: desc };
    if (id != "0") data.idsesion = id;

    $.post("ajax/sesion_matriz.php?op=" + op, data, function (r) {
        var res = JSON.parse(r);
        if (res.ok || res.idsesion) {
            window.location.hash = "close";
            listar_sesiones();
        } else {
            alert(res.msg || "Error al guardar.");
        }
    });
}

function pedir_eliminar_sesion(e, idsesion) {
    e.stopPropagation();
    $("#idsesion_eliminar").val(idsesion);
    window.location.hash = "modal_confirmar_eliminar";
}

function confirmar_eliminar_sesion() {
    var id = $("#idsesion_eliminar").val();
    $.post("ajax/sesion_matriz.php?op=eliminar_sesion", { idsesion: id }, function (r) {
        var res = JSON.parse(r);
        if (res.ok) {
            window.location.hash = "close";
            listar_sesiones();
        } else {
            alert("No se pudo eliminar la lista.");
        }
    });
}

/* ============================================================
   NAVEGACIÓN  sesiones ↔ matriz
============================================================ */
function abrir_sesion(idsesion, nombre, descripcion) {
    idsesion_actual = idsesion;
    $("#titulo_matriz").text(nombre);
    $("#desc_matriz").text(descripcion || "");
    $("#vista_sesiones").hide();
    $("#vista_matriz").show();
    cargar_matriz(idsesion);
}

function regresar_sesiones() {
    idsesion_actual = 0;
    registros_actuales = [];
    $("#vista_matriz").hide();
    $("#vista_sesiones").show();
    // limpiar input archivo
    $("#input_excel").val("");
    $("#aviso_excel").hide();
}

/* ============================================================
   MATRIZ – carga desde servidor
============================================================ */
function cargar_matriz(idsesion) {
    $("#tbody_matriz").html('<tr><td colspan="53" style="text-align:center; padding:16px;">Cargando...</td></tr>');

    $.get("ajax/sesion_matriz.php?op=listar_registros&idsesion=" + idsesion, function (r) {
        registros_actuales = JSON.parse(r);
        renderizar_matriz(registros_actuales);
    });
}

function renderizar_matriz(registros) {
    var tbody = $("#tbody_matriz");
    tbody.empty();

    if (registros.length === 0) {
        tbody.html('<tr><td colspan="53" style="text-align:center; color:#888; padding:20px;">Sin registros. Carga un archivo Excel.</td></tr>');
        actualizar_graficas();
        return;
    }

    var anio = String(new Date().getFullYear()).slice(-2);

    registros.forEach(function (reg, idx) {
        var fila = '<tr>';
        fila += '<td title="' + reg.nombre + '">' + reg.nombre + '</td>';

        for (var i = 1; i <= 52; i++) {
            var col    = 'col_' + (i < 10 ? '0' + i : '' + i);
            var val    = parseInt(reg[col]) || 0;
            var cls    = val === 1 ? 'celda-check activa' : 'celda-check';
            var semana = (i < 10 ? '0' : '') + i;
            var numfila = idx + 1;
            var filaPad = (numfila < 10 ? '00' : (numfila < 100 ? '0' : '')) + numfila;
            var codigo = anio + semana + filaPad;
            fila += '<td class="' + cls + '" ' +
                    'data-idregistro="' + reg.idregistro + '" ' +
                    'data-col="' + i + '" ' +
                    'data-val="' + val + '" ' +
                    'onclick="toggle_celda(this);">' + codigo + '</td>';
        }

        fila += '</tr>';
        tbody.append(fila);
    });

    actualizar_graficas();
}

/* ============================================================
   MODO EDICIÓN
============================================================ */
function toggle_modo_edicion() {
    modo_edicion = !modo_edicion;
    var $btn = $('#btn_modo_edicion');
    if (modo_edicion) {
        $btn.text('Desactivar edición').removeClass('btn-secondary').addClass('btn-warning');
        $('#tbl_matriz_wrap').addClass('modo-edicion-activo');
    } else {
        $btn.text('Activar edición').removeClass('btn-warning').addClass('btn-secondary');
        $('#tbl_matriz_wrap').removeClass('modo-edicion-activo');
    }
}

/* ============================================================
   TOGGLE CELDA
============================================================ */
function toggle_celda(celda, forzar) {
    if (!modo_edicion && !forzar) return;
    var $c         = $(celda);
    var idregistro = $c.data("idregistro");
    var col        = parseInt($c.data("col"));
    var val_actual = parseInt($c.data("val")) || 0;
    var nuevo_val  = val_actual === 1 ? 0 : 1;

    // Calcular código de la celda
    var anio   = String(new Date().getFullYear()).slice(-2);
    var semana  = (col < 10 ? '0' : '') + col;
    var numfila = $c.closest('tr').index() + 1;
    var filaPad = (numfila < 10 ? '00' : (numfila < 100 ? '0' : '')) + numfila;
    var codigo  = anio + semana + filaPad;

    // Actualiza UI inmediatamente
    if (nuevo_val === 1) {
        $c.addClass("activa");
    } else {
        $c.removeClass("activa");
    }
    $c.text(codigo);
    $c.data("val", nuevo_val);

    // Persiste en servidor
    $.ajax({
        url: "ajax/sesion_matriz.php?op=actualizar_celda",
        type: "POST",
        data: { idregistro: idregistro, col: col, valor: nuevo_val },
        timeout: 15000,
        success: function (r) {
            var res;
            try { res = JSON.parse(r); } catch(e) { return; /* warning de PHP pero query ejecutada */ }
            if (!res.ok) {
                // Revertir si falla
                if (nuevo_val === 1) { $c.removeClass("activa"); } else { $c.addClass("activa"); }
                $c.data("val", val_actual);
                console.warn("Error al guardar celda", res);
            } else {
                actualizar_graficas();
            }
        },
        error: function (xhr, status) {
            // Revertir visualmente ante error de red/timeout
            if (nuevo_val === 1) { $c.removeClass("activa"); } else { $c.addClass("activa"); }
            $c.data("val", val_actual);
            console.warn("Error de red al guardar celda:", status);
        }
    });
}

/* ============================================================
   ESCÁNER – buscar y activar celda por código
============================================================ */
function buscar_celda() {
    var codigo  = $.trim($("#input_scanner").val());
    var $aviso  = $("#aviso_scanner");

    if (codigo === '') {
        $aviso.text('Ingresa un código.').css('color', '#c00').show();
        return;
    }

    // Buscar celda cuyo texto coincida exactamente con el código
    var $celda = $("#tbody_matriz td.celda-check").filter(function () {
        return $.trim($(this).text()) === codigo;
    });

    if ($celda.length === 0) {
        $aviso.text('Código "' + codigo + '" no encontrado.').css('color', '#c00').show();
        return;
    }

    $aviso.hide();

    // Si no está activa, activarla; si ya está activa, no hacer nada
    if (!$celda.hasClass('activa')) {
        toggle_celda($celda[0], true);
    }

    // Scroll hacia la celda y resaltado temporal
    var wrap = document.getElementById('tbl_matriz_wrap');
    var cell = $celda[0];
    wrap.scrollTo({ left: cell.offsetLeft - 200, top: cell.offsetParent.offsetTop - 100, behavior: 'smooth' });
    $celda.css('outline', '3px solid #ff9800');
    setTimeout(function () { $celda.css('outline', ''); }, 1500);

    $("#input_scanner").val('').focus();
}

/* ============================================================
   LEER EXCEL CON SheetJS
============================================================ */
function leer_excel(input) {
    var file = input.files[0];
    if (!file) return;

    var ext = file.name.split('.').pop().toLowerCase();
    if (ext !== 'xlsx' && ext !== 'xls' && ext !== 'csv') {
        mostrar_aviso_excel("Solo se permiten archivos .xlsx, .xls o .csv");
        input.value = "";
        return;
    }

    var reader = new FileReader();
    reader.onload = function (e) {
        try {
            var data    = new Uint8Array(e.target.result);
            var wb      = XLSX.read(data, { type: 'array' });
            var sheet   = wb.Sheets[wb.SheetNames[0]];
            var rows    = XLSX.utils.sheet_to_json(sheet, { header: 1, defval: "" });

            if (rows.length < 1) {
                mostrar_aviso_excel("El archivo no contiene datos.");
                return;
            }

            // Tomamos primera celda de cada fila (sin omitir ninguna)
            // Si el valor es puramente numérico, lo formateamos a 3 dígitos con ceros a la izquierda
            var nombres = [];
            for (var i = 0; i < rows.length; i++) {
                var val = String(rows[i][0] || "").trim();
                if (val !== "") {
                    if (/^\d+$/.test(val)) {
                        val = val.padStart(3, '0');
                    }
                    nombres.push(val);
                }
            }

            if (nombres.length === 0) {
                mostrar_aviso_excel("No se encontraron nombres en la primera columna.");
                return;
            }

            ocultar_aviso_excel();
            enviar_registros(nombres);

        } catch (err) {
            mostrar_aviso_excel("Error al leer el archivo: " + err.message);
        }
    };
    reader.readAsArrayBuffer(file);
}

function enviar_registros(nombres) {
    if (!confirm("Se cargarán " + nombres.length + " registro(s). Esto reemplazará el listado actual de esta lista. ¿Continuar?")) {
        return;
    }

    $.post("ajax/sesion_matriz.php?op=cargar_registros",
        { idsesion: idsesion_actual, nombres: JSON.stringify(nombres) },
        function (r) {
            var res = JSON.parse(r);
            if (res.ok) {
                cargar_matriz(idsesion_actual);
                // Refrescar conteo en lista
            } else {
                alert("Error al cargar registros: " + (res.msg || ""));
            }
        }
    );
}

/* ============================================================
   UTILIDADES
============================================================ */
function escapar(str) {
    if (!str) return "";
    return String(str).replace(/\\/g, "\\\\").replace(/'/g, "\\'").replace(/"/g, "&quot;");
}

function mostrar_aviso_excel(msg) {
    $("#aviso_excel").text(msg).show();
}

function ocultar_aviso_excel() {
    $("#aviso_excel").hide().text("");
}

/* ============================================================
   GRÁFICAS DE AVANCE
============================================================ */
var _chart_general   = null;
var _chart_personas  = null;

function actualizar_graficas() {
    var nombres  = [];
    var avances  = [];   // marcadas por persona
    var total_marcadas = 0;
    var total_celdas   = 0;

    $('#tbody_matriz tr').each(function () {
        var tds = $(this).find('td');
        if (!tds.length) return;
        var nombre   = tds.eq(0).text();
        var marcadas = 0;
        tds.each(function (i) {
            if (i === 0) return;
            if ($(this).hasClass('activa')) marcadas++;
        });
        var total_cols = tds.length - 1;   // sin la columna de nombre
        nombres.push(nombre);
        avances.push(marcadas);
        total_marcadas += marcadas;
        total_celdas   += total_cols;
    });

    if (!nombres.length) return;

    var total_posible = nombres.length * 52;
    var pct_general   = total_posible > 0 ? Math.round((total_marcadas / total_posible) * 100) : 0;

    // ---- Gráfica general (doughnut) ----
    var ctx_g = document.getElementById('grafica_general');
    if (!ctx_g) return;

    if (_chart_general) _chart_general.destroy();
    _chart_general = new Chart(ctx_g, {
        type: 'doughnut',
        data: {
            labels: ['Completado', 'Pendiente'],
            datasets: [{
                data: [total_marcadas, total_posible - total_marcadas],
                backgroundColor: ['#28a745', '#e2e8f4'],
                borderWidth: 0
            }]
        },
        options: {
            cutout: '72%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(c) {
                            return ' ' + c.label + ': ' + c.raw;
                        }
                    }
                }
            }
        },
        plugins: [{
            id: 'texto_centro',
            afterDraw: function(chart) {
                var ctx = chart.ctx;
                var cx  = chart.chartArea.left + (chart.chartArea.right  - chart.chartArea.left) / 2;
                var cy  = chart.chartArea.top  + (chart.chartArea.bottom - chart.chartArea.top)  / 2;
                ctx.save();
                ctx.font = 'bold 22px sans-serif';
                ctx.fillStyle = '#1D4268';
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                ctx.fillText(pct_general + '%', cx, cy);
                ctx.restore();
            }
        }]
    });

    $('#stats_general').html(
        '<b>' + total_marcadas + '</b> celdas marcadas<br>' +
        '<b>' + (total_posible - total_marcadas) + '</b> pendientes<br>' +
        '<b>' + total_posible + '</b> total posible (' + nombres.length + ' personas × 52)<br>' +
        '<b>' + pct_general + '%</b> avance global'
    );

    // ---- Gráfica por persona (barras horizontales) ----
    var ctx_p = document.getElementById('grafica_personas');
    if (!ctx_p) return;

    // Limitar a 60 personas para legibilidad; ordenar de mayor a menor
    var datos_ord = nombres.map(function(n, i) { return { nombre: n, avance: avances[i] }; });
    datos_ord.sort(function(a, b) { return b.avance - a.avance; });
    var MAX_PERSONAS = 60;
    if (datos_ord.length > MAX_PERSONAS) datos_ord = datos_ord.slice(0, MAX_PERSONAS);

    var labels_p  = datos_ord.map(function(d) { return d.nombre; });
    var values_p  = datos_ord.map(function(d) { return d.avance; });
    var colores_p = values_p.map(function(v) {
        var pct = v / 52;
        if (pct >= 0.8) return '#28a745';
        if (pct >= 0.4) return '#f0ad4e';
        return '#dc3545';
    });

    // Alto dinámico: ~22px por barra
    ctx_p.parentElement.style.height = Math.max(200, labels_p.length * 24) + 'px';
    ctx_p.style.height = ctx_p.parentElement.style.height;

    if (_chart_personas) _chart_personas.destroy();
    _chart_personas = new Chart(ctx_p, {
        type: 'bar',
        data: {
            labels: labels_p,
            datasets: [{
                label: 'Semanas marcadas',
                data: values_p,
                backgroundColor: colores_p,
                borderRadius: 4
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(c) {
                            var pct = Math.round((c.raw / 52) * 100);
                            return ' ' + c.raw + ' / 52  (' + pct + '%)';
                        }
                    }
                }
            },
            scales: {
                x: {
                    min: 0,
                    max: 52,
                    ticks: { stepSize: 4, font: { size: 11 } },
                    grid: { color: '#e2e8f4' }
                },
                y: {
                    ticks: { font: { size: 11 } }
                }
            }
        }
    });
}

/* ============================================================
   EXPORTAR TABLA A EXCEL
============================================================ */
function exportar_matriz() {
    var filas = [];

    // Encabezado
    var enc = ['Nombre'];
    $('#thead_row th').each(function (i) {
        if (i > 0) enc.push($(this).text());
    });
    filas.push(enc);

    // Filas de datos
    var data_rows = [];   // solo datos (para aplicar estilos por índice)
    $('#tbody_matriz tr').each(function () {
        var fila        = [];
        var fila_activa = [];  // true/false por columna
        $(this).find('td').each(function (i) {
            if (i === 0) {
                fila.push($(this).text());
                fila_activa.push(false);
            } else {
                var activa = $(this).hasClass('activa');
                // Celda activa → código con estilo verde; inactiva → código sin estilo
                fila.push($(this).text());
                fila_activa.push(activa);
            }
        });
        if (fila.length) {
            filas.push(fila);
            data_rows.push(fila_activa);
        }
    });

    var ws = XLSX.utils.aoa_to_sheet(filas);

    // Aplicar fondo verde a celdas activas (requiere objeto de celda con estilo)
    var col_letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    function col_name(idx) {
        // idx 0-based: 0=A, 25=Z, 26=AA …
        if (idx < 26) return col_letters[idx];
        return col_letters[Math.floor(idx / 26) - 1] + col_letters[idx % 26];
    }

    data_rows.forEach(function (fila_activa, ri) {
        var row_xlsx = ri + 2;  // +1 encabezado, +1 base-1
        fila_activa.forEach(function (activa, ci) {
            if (!activa || ci === 0) return;
            var ref = col_name(ci) + row_xlsx;
            if (!ws[ref]) ws[ref] = { t: 's', v: '' };
            ws[ref].s = {
                fill:  { fgColor: { rgb: '28A745' } },
                font:  { color: { rgb: 'FFFFFF' }, bold: true },
                alignment: { horizontal: 'center' }
            };
        });
    });

    var wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, 'Matriz');

    // Nombre de archivo: titulo de la sesion
    var nombre = ($('#titulo_matriz').text().trim() || 'matriz').replace(/[^a-z0-9_\-]/gi, '_');
    XLSX.writeFile(wb, nombre + '.xlsx');
}
