/* ============================================================
   sesion_matriz.js
   Gestión de sesiones + matriz de asistencia (52 columnas)
============================================================ */

var idsesion_actual = 0;
var registros_actuales = [];

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
    $("#titulo_modal_sesion").text("Nueva Sesión");
    $("#idsesion_edit").val("0");
    $("#nombre_sesion").val("");
    $("#desc_sesion").val("");
}

function editar_sesion(e, idsesion, nombre, descripcion) {
    e.stopPropagation();
    $("#titulo_modal_sesion").text("Editar Sesión");
    $("#idsesion_edit").val(idsesion);
    $("#nombre_sesion").val(nombre);
    $("#desc_sesion").val(descripcion);
    window.location.hash = "modal_nueva_sesion";
}

function guardar_sesion() {
    var nombre = $.trim($("#nombre_sesion").val());
    var desc   = $.trim($("#desc_sesion").val());
    var id     = $("#idsesion_edit").val();

    if (nombre === "") { alert("El nombre de la sesión es obligatorio."); return; }

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
            alert("No se pudo eliminar la sesión.");
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
        return;
    }

    registros.forEach(function (reg) {
        var fila = '<tr>';
        fila += '<td title="' + reg.nombre + '">' + reg.nombre + '</td>';

        for (var i = 1; i <= 52; i++) {
            var col  = 'col_' + (i < 10 ? '0' + i : '' + i);
            var val  = parseInt(reg[col]) || 0;
            var cls  = val === 1 ? 'celda-check activa' : 'celda-check';
            fila += '<td class="' + cls + '" ' +
                    'data-idregistro="' + reg.idregistro + '" ' +
                    'data-col="' + i + '" ' +
                    'data-val="' + val + '" ' +
                    'onclick="toggle_celda(this);"></td>';
        }

        fila += '</tr>';
        tbody.append(fila);
    });
}

/* ============================================================
   TOGGLE CELDA
============================================================ */
function toggle_celda(celda) {
    var $c        = $(celda);
    var idregistro = $c.data("idregistro");
    var col        = $c.data("col");
    var val_actual = parseInt($c.data("val")) || 0;
    var nuevo_val  = val_actual === 1 ? 0 : 1;

    // Actualiza UI inmediatamente
    if (nuevo_val === 1) {
        $c.addClass("activa");
    } else {
        $c.removeClass("activa");
    }
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

            if (rows.length < 2) {
                mostrar_aviso_excel("El archivo no contiene datos suficientes (se necesita al menos un encabezado y una fila de datos).");
                return;
            }

            // Fila 0 = encabezado (se omite). Tomamos primera celda de cada fila
            var nombres = [];
            for (var i = 1; i < rows.length; i++) {
                var val = String(rows[i][0] || "").trim();
                if (val !== "") nombres.push(val);
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
    if (!confirm("Se cargarán " + nombres.length + " registro(s). Esto reemplazará el listado actual de esta sesión. ¿Continuar?")) {
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
