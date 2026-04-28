/* ============================================================
    sesion_matriz.js
    Gestión de sesiones + matriz de asistencia (hasta 1000 columnas)
============================================================ */


var idsesion_actual = 0;
var registros_actuales = [];
var modo_edicion = false;
var columnas_actuales = 52; // Por defecto
var digitos_fila_actual = 3;
var digitos_col_actual = 2;

/* ---- Inicialización ---- */
document.addEventListener("DOMContentLoaded", function () {
    // Valores por defecto para los campos de dígitos
    $('#input_digitos_fila').val(2);
    $('#input_digitos_col').val(2);
    construir_encabezado_matriz();
    listar_sesiones();
});

/* ============================================================
   ENCABEZADO DE LA TABLA (columnas 1–52)
============================================================ */
function construir_encabezado_matriz() {
    var row = $("#thead_row");
    row.empty();
    row.append('<th>Nombre</th>');
    for (var i = 1; i <= columnas_actuales; i++) {
        var colLabel = String(i).padStart(digitos_col_actual, '0');
        row.append('<th style="min-width:32px;">' + colLabel + '</th>');
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
    // Obtener columnas de la sesión antes de cargar la matriz
    $.get("ajax/sesion_matriz.php?op=obtener_columnas&idsesion=" + idsesion, function (r) {
        var res = JSON.parse(r);
        columnas_actuales = res.columnas ? parseInt(res.columnas) : 52;
        // Leer los dígitos usados en los inputs (si existen)
        var digFila = parseInt($('#input_digitos_fila').val(), 10);
        var digCol = parseInt($('#input_digitos_col').val(), 10);
        digitos_fila_actual = (!isNaN(digFila) && digFila >= 1 && digFila <= 6) ? digFila : 3;
        digitos_col_actual = (!isNaN(digCol) && digCol >= 1 && digCol <= 6) ? digCol : 2;
        construir_encabezado_matriz();
        cargar_matriz(idsesion);
    });
}

function regresar_sesiones() {
    idsesion_actual = 0;
    registros_actuales = [];
    // Destruir gráficas para que no interfieran con la próxima sesión
    if (_chart_general)  { _chart_general.destroy();  _chart_general  = null; }
    if (_chart_personas) { _chart_personas.destroy(); _chart_personas = null; }
    $("#vista_matriz").hide();
    $("#vista_sesiones").show();
    $("#input_excel").val("");
    $("#aviso_excel").hide();
}

/* ============================================================
   MATRIZ – carga desde servidor
============================================================ */

// Nueva estructura: matriz_json = { filas: [...], columnas: [...], checks: [[...], ...] }
var matriz_json_actual = null;

function cargar_matriz(idsesion) {
    $("#tbody_matriz").html('<tr><td colspan="' + (columnas_actuales + 1) + '" style="text-align:center; padding:16px;">Cargando...</td></tr>');
    $.get("ajax/sesion_matriz.php?op=obtener_matriz&idsesion=" + idsesion, function (r) {
        try {
            matriz_json_actual = JSON.parse(r);
        } catch (e) {
            matriz_json_actual = null;
        }
        if (!matriz_json_actual || !Array.isArray(matriz_json_actual.filas) || !Array.isArray(matriz_json_actual.columnas) || !Array.isArray(matriz_json_actual.checks)) {
            $("#tbody_matriz").html('<tr><td colspan="' + (columnas_actuales + 1) + '" style="text-align:center; color:#888; padding:20px;">Sin registros. Carga un archivo Excel.</td></tr>');
            $('#info_matriz_config').hide();
            actualizar_graficas();
            return;
        }
        columnas_actuales = matriz_json_actual.columnas.length;
        construir_encabezado_matriz();
        mostrar_configuracion_matriz();
        renderizar_matriz_json();
    });

// Muestra la configuración de la matriz sobre la tabla
function mostrar_configuracion_matriz() {
    if (!matriz_json_actual) { $('#info_matriz_config').hide(); return; }
    var filas = matriz_json_actual.filas ? matriz_json_actual.filas.length : 0;
    var columnas = matriz_json_actual.columnas ? matriz_json_actual.columnas.length : 0;
    var config = matriz_json_actual.config || {};
    var digitosFila = config.digitos_fila || 2;
    var digitosCol = config.digitos_col || 2;
    var ordenCodigo = config.orden_codigo || '';
    var valorBase = config.valor_base !== undefined ? config.valor_base : '';
    var omitirBase = config.omitir_base ? 'Sí' : 'No';
    var ejemplo = (matriz_json_actual.codigos && matriz_json_actual.codigos[0] && matriz_json_actual.codigos[0][0]) ? matriz_json_actual.codigos[0][0] : '';
    var html = '<b>Configuración:</b> ' + filas + ' filas, ' + columnas + ' columnas, dígitos fila: ' + digitosFila + ', dígitos columna: ' + digitosCol + ', orden: ' + ordenCodigo + ', base: ' + valorBase + ', omitir base: ' + omitirBase + (ejemplo ? ', ejemplo: ' + ejemplo : '');
    $('#info_matriz_config').html(html).show();
}
}

function renderizar_matriz_json() {
    var tbody = $("#tbody_matriz");
    tbody.empty();
    if (!matriz_json_actual || !Array.isArray(matriz_json_actual.filas) || !Array.isArray(matriz_json_actual.columnas)) {
        tbody.html('<tr><td colspan="' + (columnas_actuales + 1) + '" style="text-align:center; color:#888; padding:20px;">Sin registros. Carga un archivo Excel.</td></tr>');
        actualizar_graficas();
        return;
    }
    var filas = matriz_json_actual.filas;
    var columnas = matriz_json_actual.columnas;
    var checks = matriz_json_actual.checks;
    var codigos = matriz_json_actual.codigos || null;
    for (var i = 0; i < filas.length; i++) {
        var fila = '<tr>';
        fila += '<td title="' + filas[i] + '">' + filas[i] + '</td>';
        for (var j = 0; j < columnas.length; j++) {
            var val = (checks[i] && checks[i][j]) ? 1 : 0;
            var cls = val === 1 ? 'celda-check activa' : 'celda-check';
            var codigo = codigos && codigos[i] && codigos[i][j] ? codigos[i][j] : '';
            fila += '<td class="' + cls + '" data-fila="' + i + '" data-col="' + j + '" data-val="' + val + '" onclick="toggle_celda_json(this);">' + codigo + '</td>';
        }
        fila += '</tr>';
        tbody.append(fila);
    }
    actualizar_graficas();
}

function toggle_celda_json(celda) {
    if (!modo_edicion) return;
    var $c = $(celda);
    var filaIdx = parseInt($c.data("fila"));
    var colIdx = parseInt($c.data("col"));
    var val_actual = parseInt($c.data("val")) || 0;
    var nuevo_val = val_actual === 1 ? 0 : 1;
    // Actualiza en memoria
    if (matriz_json_actual && matriz_json_actual.checks && matriz_json_actual.checks[filaIdx]) {
        matriz_json_actual.checks[filaIdx][colIdx] = nuevo_val;
    }
    // Actualiza UI (solo clase y valor, NO el texto/código)
    if (nuevo_val === 1) {
        $c.addClass("activa");
    } else {
        $c.removeClass("activa");
    }
    $c.data("val", nuevo_val);
    // Guarda toda la matriz y actualiza gráficas
    guardar_matriz_json();
    actualizar_graficas();
}

function guardar_matriz_json() {
    if (!idsesion_actual || !matriz_json_actual) return;
    $.post("ajax/sesion_matriz.php?op=cargar_registros", {
        idsesion: idsesion_actual,
        matriz: JSON.stringify(matriz_json_actual),
        columnas: matriz_json_actual.columnas.length
    }, function (r) {
        // Opcional: manejar respuesta
    });
}

function renderizar_matriz(registros) {
    var tbody = $("#tbody_matriz");
    tbody.empty();

    if (registros.length === 0) {
        tbody.html('<tr><td colspan="' + (columnas_actuales + 1) + '" style="text-align:center; color:#888; padding:20px;">Sin registros. Carga un archivo Excel.</td></tr>');
        actualizar_graficas();
        return;
    }

    var anio = String(new Date().getFullYear()).slice(-2);
    var omitirBase = $('#chk_omitir_base').is(':checked');
    var valorBase = omitirBase ? '' : (parseInt($('#input_valor_base').val(), 10) || 0);
    var ordenConcat = $('#input_orden_concat').val() || 'col-fila-base';

    function generarCodigo(semana, filaPad, base) {
        switch (ordenConcat) {
            case 'col-fila-base': return semana + filaPad + (omitirBase ? '' : base);
            case 'fila-col-base': return filaPad + semana + (omitirBase ? '' : base);
            case 'base-col-fila': return (omitirBase ? '' : base) + semana + filaPad;
            case 'base-fila-col': return (omitirBase ? '' : base) + filaPad + semana;
            case 'col-base-fila': return semana + (omitirBase ? '' : base) + filaPad;
            case 'fila-base-col': return filaPad + (omitirBase ? '' : base) + semana;
            case 'col-fila': return semana + filaPad;
            case 'fila-col': return filaPad + semana;
            default: return semana + filaPad + (omitirBase ? '' : base);
        }
    }

    registros.forEach(function (reg, idx) {
        var fila = '<tr>';
        fila += '<td title="' + reg.nombre + '">' + reg.nombre + '</td>';

        for (var i = 1; i <= columnas_actuales; i++) {
            var col    = 'col_' + (i < 10 ? '0' + i : '' + i);
            var val    = parseInt(reg[col]) || 0;
            var cls    = val === 1 ? 'celda-check activa' : 'celda-check';
            var semana = String(i).padStart(digitos_col_actual, '0');
            var numfila = idx + 1;
            var filaPad = String(numfila).padStart(digitos_fila_actual, '0');
            var codigo = generarCodigo(semana, filaPad, valorBase);
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
    var omitirBase = $('#chk_omitir_base').is(':checked');
    var semana  = String(col).padStart(digitos_col_actual, '0');
    var numfila = $c.closest('tr').index() + 1;
    var filaPad = String(numfila).padStart(digitos_fila_actual, '0');
    var valorBase = omitirBase ? '' : (parseInt($('#input_valor_base').val(), 10) || 0);
    var ordenConcat = $('#input_orden_concat').val() || 'col-fila-base';
    function generarCodigo(semana, filaPad, base) {
        switch (ordenConcat) {
            case 'col-fila-base': return semana + filaPad + (omitirBase ? '' : base);
            case 'fila-col-base': return filaPad + semana + (omitirBase ? '' : base);
            case 'base-col-fila': return (omitirBase ? '' : base) + semana + filaPad;
            case 'base-fila-col': return (omitirBase ? '' : base) + filaPad + semana;
            case 'col-base-fila': return semana + (omitirBase ? '' : base) + filaPad;
            case 'fila-base-col': return filaPad + (omitirBase ? '' : base) + semana;
            case 'col-fila': return semana + filaPad;
            case 'fila-col': return filaPad + semana;
            default: return semana + filaPad + (omitirBase ? '' : base);
        }
    }
    var codigo  = generarCodigo(semana, filaPad, valorBase);

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

    if (!modo_edicion) {
        $aviso.text('Activa el modo edición antes de seleccionar celdas.').css('color', '#c00').show();
        $("#input_scanner").val('').focus();
        return;
    }

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

    // Si no está activa, activarla visualmente y en memoria, pero NO modificar el texto
    if (!$celda.hasClass('activa')) {
        $celda.addClass('activa');
        $celda.data('val', 1);
        // Actualizar checks en memoria si es modo JSON
        var filaIdx = $celda.data('fila');
        var colIdx = $celda.data('col');
        if (typeof filaIdx !== 'undefined' && typeof colIdx !== 'undefined' && matriz_json_actual && matriz_json_actual.checks && matriz_json_actual.checks[filaIdx]) {
            matriz_json_actual.checks[filaIdx][colIdx] = 1;
            guardar_matriz_json();
        }
        actualizar_graficas();
    }

    // Scroll hacia la celda y resaltado temporal
    var wrap = document.getElementById('tbl_matriz_wrap');
    var cell = $celda[0];
    wrap.scrollTo({ left: cell.offsetLeft - 200, top: cell.offsetParent.offsetTop - 100, behavior: 'smooth' });
    $celda.css('outline', '3px solid #ff9800');
    setTimeout(function () { $celda.css('outline', ''); }, 1500);

    $("#input_scanner").val('').focus();
}


// UTILIDAD: Escapar texto para HTML/atributos
function escapar(str) {
    if (!str) return "";
    return String(str).replace(/\\/g, "\\\\").replace(/'/g, "\\'").replace(/"/g, "&quot;");
}

/* ============================================================
   CREAR TABLA MANUAL (por número de filas)
============================================================ */
function crear_tabla_manual() {
    var n = parseInt($('#input_num_filas').val(), 10);
    var m = parseInt($('#input_num_columnas').val(), 10);
    var digFila = parseInt($('#input_digitos_fila').val(), 10) || 2;
    var digCol = parseInt($('#input_digitos_col').val(), 10) || 2;
    var $aviso = $('#aviso_manual');
    $aviso.hide();

    if (isNaN(n) || n < 1 || n > 1000) {
        $aviso.text('Ingresa un número de filas entre 1 y 1000.').css('color', '#c00').show();
        return;
    }
    if (isNaN(m) || m < 1 || m > 1000) {
        $aviso.text('Ingresa un número de columnas entre 1 y 1000.').css('color', '#c00').show();
        return;
    }
    if (isNaN(digFila) || digFila < 1 || digFila > 6) digFila = 3;
    if (isNaN(digCol) || digCol < 1 || digCol > 6) digCol = 2;


    var nombres = [];
    for (var i = 1; i <= n; i++) {
        nombres.push(String(i).padStart(digFila, '0'));
    }

    var ordenCodigo = $('#input_orden_concat').val() || 'col-fila-base';
    var valorBase = $('#chk_omitir_base').is(':checked') ? '' : ($('#input_valor_base').val() || '0');
    var omitirBase = $('#chk_omitir_base').is(':checked') ? 1 : 0;

    if (!confirm('Se crearán ' + n + ' filas numeradas (' + String(1).padStart(digFila,'0') + '–' + String(n).padStart(digFila,'0') + ') y ' + m + ' columnas. Esto reemplazará el listado actual. ¿Continuar?')) return;

    // Si la sesión aún no existe, crearla primero con el número de columnas correcto
    if (!idsesion_actual || idsesion_actual === 0) {
        var nombre = prompt('Nombre de la nueva lista:');
        if (!nombre) return;
        $.post('ajax/sesion_matriz.php?op=crear_sesion', { nombre: nombre, descripcion: '', columnas: m }, function (r) {
            var res = JSON.parse(r);
            if (res.ok && res.idsesion) {
                idsesion_actual = res.idsesion;
                enviar_registros_manual(nombres, m, digFila, digCol);
            } else {
                $aviso.text('Error al crear la sesión.').css('color', '#c00').show();
            }
        });
    } else {
        enviar_registros_manual(nombres, m, digFila, digCol);
    }
}

function enviar_registros_manual(nombres, columnas, digFila, digCol) {
    var ordenCodigo = $('#input_orden_concat').val() || 'col-fila-base';
    var valorBase = $('#chk_omitir_base').is(':checked') ? '' : ($('#input_valor_base').val() || '0');
    var omitirBase = $('#chk_omitir_base').is(':checked') ? 1 : 0;
    $.post('ajax/sesion_matriz.php?op=guardar_matriz_manual',
        { idsesion: idsesion_actual, nombres: JSON.stringify(nombres), columnas: columnas, digitos_fila: digFila, digitos_col: digCol, orden_codigo: ordenCodigo, valor_base: valorBase, omitir_base: omitirBase },
        function (r) {
            var res = JSON.parse(r);
            if (res.ok) {
                $('#input_num_filas').val('');
                $('#input_num_columnas').val('');
                cargar_matriz(idsesion_actual);
            } else {
                $('#aviso_manual').text('Error: ' + (res.msg || '')).css('color', '#c00').show();
            }
        }
    );
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

    var total_posible = nombres.length * columnas_actuales;
    var pct_general   = total_posible > 0 ? Math.round((total_marcadas / total_posible) * 100) : 0;

    // ---- Gráfica general (doughnut) ----
    // Primera vez en la sesión: reemplazar canvas y crear chart.
    // Llamadas siguientes (toggles de celda): actualizar datos del chart existente.
    if (!_chart_general) {
        var $wrap_g = $('#grafica_general').parent();
        $('#grafica_general').remove();
        $wrap_g.append('<canvas id="grafica_general"></canvas>');
        var ctx_g = document.getElementById('grafica_general');
        if (!ctx_g) return;
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
                    var pct = chart.data.datasets[0].data[0];
                    var tot = chart.data.datasets[0].data[0] + chart.data.datasets[0].data[1];
                    var p   = tot > 0 ? Math.round((pct / tot) * 100) : 0;
                    ctx.save();
                    ctx.font = 'bold 22px sans-serif';
                    ctx.fillStyle = '#1D4268';
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';
                    ctx.fillText(p + '%', cx, cy);
                    ctx.restore();
                }
            }]
        });
    } else {
        _chart_general.data.datasets[0].data = [total_marcadas, total_posible - total_marcadas];
        _chart_general.update();
    }

    $('#stats_general').html(
        '<b>' + total_marcadas + '</b> celdas marcadas<br>' +
        '<b>' + (total_posible - total_marcadas) + '</b> pendientes<br>' +
        '<b>' + total_posible + '</b> total posible (' + nombres.length + ' personas × ' + columnas_actuales + ')<br>' +
        '<b>' + pct_general + '%</b> avance global'
    );

    // ---- Gráfica por persona (barras horizontales) ----
    var datos_ord = nombres.map(function(n, i) { return { nombre: n, avance: avances[i] }; });
    datos_ord.sort(function(a, b) { return b.avance - a.avance; });
    var MAX_PERSONAS = 60;
    if (datos_ord.length > MAX_PERSONAS) datos_ord = datos_ord.slice(0, MAX_PERSONAS);

    var labels_p  = datos_ord.map(function(d) { return d.nombre; });
    var values_p  = datos_ord.map(function(d) { return d.avance; });
    var colores_p = values_p.map(function(v) {
        var pct = columnas_actuales > 0 ? v / columnas_actuales : 0;
        if (pct >= 0.8) return '#28a745';
        if (pct >= 0.4) return '#f0ad4e';
        return '#dc3545';
    });

    var chartH = Math.max(200, Math.min(labels_p.length * 28, 700));
    var stepX   = Math.max(1, Math.floor(columnas_actuales / 10));

    if (!_chart_personas) {
        var $wrap_p = $('#grafica_personas').parent();
        $('#grafica_personas').remove();
        $wrap_p.append('<canvas id="grafica_personas"></canvas>');
        var ctx_p = document.getElementById('grafica_personas');
        if (!ctx_p) return;

        $wrap_p.css('height', chartH + 'px');

        _chart_personas = new Chart(ctx_p, {
            type: 'bar',
            data: {
                labels: labels_p,
                datasets: [{
                    label: 'Columnas marcadas',
                    data: values_p,
                    backgroundColor: colores_p,
                    borderRadius: 4
                }]
            },
            options: {
                indexAxis: 'y',          // Y = filas (personas),  X = columnas seleccionadas
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(c) {
                                var pct = columnas_actuales > 0 ? Math.round((c.raw / columnas_actuales) * 100) : 0;
                                return ' ' + c.raw + ' / ' + columnas_actuales + '  (' + pct + '%)';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        title: { display: true, text: 'Filas', font: { size: 11 }, color: '#94a3b8' },
                        ticks: { font: { size: 10 }, maxRotation: 0 }
                    },
                    x: {
                        title: { display: true, text: 'Columnas seleccionadas', font: { size: 11 }, color: '#94a3b8' },
                        min: 0,
                        max: columnas_actuales,
                        ticks: { stepSize: stepX, font: { size: 10 } }
                    }
                }
            }
        });
    } else {
        _chart_personas.data.labels = labels_p;
        _chart_personas.data.datasets[0].data = values_p;
        _chart_personas.data.datasets[0].backgroundColor = colores_p;
        _chart_personas.options.scales.x.max = columnas_actuales;
        _chart_personas.options.scales.x.ticks.stepSize = stepX;
        $('#wrap_grafica_personas').css('height', chartH + 'px');
        _chart_personas.resize();
        _chart_personas.update();
    }
}

// ===========================================================
// EXPORTAR MATRIZ A EXCEL (async)
// ===========================================================
async function exportar_matriz() {
    // Crear workbook ExcelJS
    if (typeof ExcelJS === 'undefined') {
        alert('ExcelJS no está cargado.');
        return;
    }
    var wb = new ExcelJS.Workbook();
    var ws = wb.addWorksheet('Matriz');

    for (var c = 2; c <= columnas_actuales + 1; c++) ws.getColumn(c).width = 9;

    // Encabezado
    var headers = ['Nombre'];
    $('#thead_row th').each(function (i) { if (i > 0) headers.push($(this).text()); });
    var hRow = ws.addRow(headers);
    hRow.height = 20;
    hRow.eachCell(function (cell) {
        cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF1F4168' } };
        cell.font = { color: { argb: 'FFFFFFFF' }, bold: true, size: 10 };
        cell.alignment = { horizontal: 'center', vertical: 'middle' };
    });

    // Filas de datos
    $('#tbody_matriz tr').each(function () {
        var rowData = [];
        var activas = [];
        $(this).find('td').each(function (i) {
            rowData.push($(this).text());
            activas.push(i > 0 && $(this).hasClass('activa'));
        });
        if (!rowData.length) return;

        var row = ws.addRow(rowData);
        row.eachCell(function (cell, colNumber) {
            cell.alignment = { horizontal: 'center', vertical: 'middle' };
            if (activas[colNumber - 1]) {
                cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF28A745' } };
                cell.font = { color: { argb: 'FFFFFFFF' }, bold: true, size: 10 };
            } else if (colNumber > 1) {
                cell.font = { color: { argb: 'FF94A3B8' }, size: 9 };
            }
        });
    });

    // ---- Hoja 2: Gráficas ----
    var wsG = wb.addWorksheet('Gráficas');
    wsG.getColumn(1).width = 2;   // margen izquierdo

    var canvasGen = document.getElementById('grafica_general');
    var canvasPer = document.getElementById('grafica_personas');

    var rowOffset = 1;   // fila actual en la hoja (base-1)

    if (canvasGen && canvasGen.width > 0) {
        var idGen = wb.addImage({
            base64: canvasGen.toDataURL('image/png').split(',')[1],
            extension: 'png'
        });
        var wGen = 260, hGen = 260;
        wsG.addImage(idGen, {
            tl: { col: 1, row: rowOffset - 1 },
            ext: { width: wGen, height: hGen }
        });
        // ~1 fila ≈ 18px; reservar espacio + separación
        rowOffset += Math.ceil(hGen / 18) + 2;
    }

    if (canvasPer && canvasPer.width > 0) {
        var idPer = wb.addImage({
            base64: canvasPer.toDataURL('image/png').split(',')[1],
            extension: 'png'
        });
        var wPer = Math.min(canvasPer.offsetWidth  || 700, 760);
        var hPer = Math.min(canvasPer.offsetHeight || 400, 1200);
        wsG.addImage(idPer, {
            tl: { col: 1, row: rowOffset - 1 },
            ext: { width: wPer, height: hPer }
        });
    }

    // ---- Descargar ----
    var nombre = ($('#titulo_matriz').text().trim() || 'matriz').replace(/[^a-z0-9_\-]/gi, '_');
    var buffer = await wb.xlsx.writeBuffer();
    var blob   = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
    var a      = document.createElement('a');

    a.href     = URL.createObjectURL(blob);
    a.download = nombre + '.xlsx';
    a.click();
}

