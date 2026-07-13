/* ══════════════════════════════════════════════
   BANNERS PUBLICITARIOS — editor con Fabric.js
══════════════════════════════════════════════ */

var bp_canvas = null;
var bp_contador_ids = 0;

var BP_DPI_PANTALLA = 96; // 1 cm = 37.8 px aprox. (vista en pantalla)

document.addEventListener('DOMContentLoaded', function () {
    crear_lienzo();
    cargar_galeria();
});

/* ══════════════════════════════════════════════
   TAMAÑO DEL LIENZO
══════════════════════════════════════════════ */
function cambio_unidad() {
    var unidad = $('#bp_unidad').val();
    $('#bp_hint_cm').toggle(unidad === 'cm');
}

function aplicar_preset_tamano() {
    var val = $('#bp_preset').val();
    if (!val) return;
    var partes = val.split('|');
    $('#bp_ancho').val(partes[0]);
    $('#bp_alto').val(partes[1]);
    $('#bp_unidad').val(partes[2]);
    cambio_unidad();
    crear_lienzo();
}

function bp_valor_a_px(valor, unidad) {
    valor = parseFloat(valor) || 0;
    if (unidad === 'cm') {
        return Math.round((valor / 2.54) * BP_DPI_PANTALLA);
    }
    return Math.round(valor);
}

function crear_lienzo(ancho_px_forzado, alto_px_forzado) {
    var unidad = $('#bp_unidad').val();
    var ancho_px = ancho_px_forzado || bp_valor_a_px($('#bp_ancho').val(), unidad);
    var alto_px  = alto_px_forzado  || bp_valor_a_px($('#bp_alto').val(), unidad);

    if (!ancho_px || !alto_px) {
        bootbox.alert('Indica un ancho y alto válidos.');
        return;
    }

    // Escala de vista: el lienzo real puede ser más grande que el espacio disponible en pantalla
    var max_vista = 700;
    var escala = Math.min(1, max_vista / Math.max(ancho_px, alto_px));

    if (bp_canvas) {
        bp_canvas.dispose();
    }

    bp_canvas = new fabric.Canvas('bp_canvas', {
        width: ancho_px,
        height: alto_px,
        backgroundColor: '#ffffff',
        preserveObjectStacking: true
    });

    bp_canvas.setZoom(escala);
    bp_canvas.setWidth(ancho_px * escala);
    bp_canvas.setHeight(alto_px * escala);

    bp_canvas.on('selection:created', bp_mostrar_panel_objeto);
    bp_canvas.on('selection:updated', bp_mostrar_panel_objeto);
    bp_canvas.on('selection:cleared', function () {
        $('#bp_panel_objeto').hide();
    });
}

/* ══════════════════════════════════════════════
   IMÁGENES (fondo / secundarias)
   Se redimensionan en el navegador antes de insertarlas
   para no disparar el tamaño del banner guardado.
══════════════════════════════════════════════ */
function bp_procesar_imagen(file, maxDim, formato, callback) {
    var reader = new FileReader();
    reader.onload = function (e) {
        var img = new Image();
        img.onload = function () {
            var w = img.width, h = img.height;
            if (w > maxDim || h > maxDim) {
                var factor = maxDim / Math.max(w, h);
                w = Math.round(w * factor);
                h = Math.round(h * factor);
            }
            var tmp = document.createElement('canvas');
            tmp.width = w;
            tmp.height = h;
            var ctx = tmp.getContext('2d');
            ctx.drawImage(img, 0, 0, w, h);
            callback(tmp.toDataURL(formato, 0.85));
        };
        img.src = e.target.result;
    };
    reader.readAsDataURL(file);
}

function subir_imagen_fondo(input) {
    var file = input.files && input.files[0];
    if (!file || !bp_canvas) return;
    bp_procesar_imagen(file, 1600, 'image/jpeg', function (dataUrl) {
        fabric.Image.fromURL(dataUrl, function (img) {
            var cw = bp_canvas.getWidth() / bp_canvas.getZoom();
            var ch = bp_canvas.getHeight() / bp_canvas.getZoom();
            var escala = Math.max(cw / img.width, ch / img.height);
            img.set({
                left: 0,
                top: 0,
                scaleX: escala,
                scaleY: escala,
                selectable: false,
                evented: false,
                id: 'fondo',
                tipo: 'fondo'
            });
            bp_canvas.setBackgroundImage(img, bp_canvas.renderAll.bind(bp_canvas));
        });
    });
    input.value = '';
}

function subir_imagen_secundaria(input) {
    var file = input.files && input.files[0];
    if (!file || !bp_canvas) return;
    bp_procesar_imagen(file, 800, 'image/png', function (dataUrl) {
        fabric.Image.fromURL(dataUrl, function (img) {
            var cw = bp_canvas.getWidth() / bp_canvas.getZoom();
            var maxW = cw * 0.35;
            if (img.width > maxW) {
                var factor = maxW / img.width;
                img.scale(factor);
            }
            img.set({
                left: 20,
                top: 20,
                id: 'img_' + (++bp_contador_ids),
                tipo: 'imagen_secundaria'
            });
            bp_canvas.add(img);
            bp_canvas.setActiveObject(img);
        });
    });
    input.value = '';
}

/* ══════════════════════════════════════════════
   TEXTOS (título / párrafo / contacto-dirección)
══════════════════════════════════════════════ */
function agregar_texto(tipo) {
    if (!bp_canvas) return;

    var config = {
        titulo:   { texto: 'Título del banner',            fontSize: 48, fontWeight: 'bold', width: 400 },
        parrafo:  { texto: 'Escribe aquí el texto del banner con la información que quieras destacar.', fontSize: 22, fontWeight: 'normal', width: 400 },
        contacto: { texto: 'Tel. (33) 0000-0000\nCalle Ejemplo #123, Col. Centro\nGuadalajara, Jal.', fontSize: 18, fontWeight: 'normal', width: 320 }
    };

    var c = config[tipo] || config.parrafo;

    var textbox = new fabric.Textbox(c.texto, {
        left: 30,
        top: 30,
        width: c.width,
        fontSize: c.fontSize,
        fontWeight: c.fontWeight,
        fill: '#000000',
        fontFamily: 'Arial',
        id: tipo + '_' + (++bp_contador_ids),
        tipo: tipo
    });

    bp_canvas.add(textbox);
    bp_canvas.setActiveObject(textbox);
}

/* ══════════════════════════════════════════════
   PANEL DEL OBJETO SELECCIONADO
══════════════════════════════════════════════ */
function bp_mostrar_panel_objeto(e) {
    var obj = bp_canvas.getActiveObject();
    if (!obj) { $('#bp_panel_objeto').hide(); return; }
    $('#bp_panel_objeto').show();
    $('#bp_obj_color').val(bp_color_a_hex(obj.fill) || '#000000');
    $('#bp_obj_fontsize').val(obj.fontSize || '');
}

function bp_color_a_hex(color) {
    if (!color) return null;
    if (color.charAt(0) === '#') return color;
    return null;
}

function cambiar_color_obj(color) {
    var obj = bp_canvas && bp_canvas.getActiveObject();
    if (!obj) return;
    obj.set('fill', color);
    bp_canvas.renderAll();
}

function cambiar_fontsize_obj(valor) {
    var obj = bp_canvas && bp_canvas.getActiveObject();
    if (!obj || !valor) return;
    obj.set('fontSize', parseInt(valor, 10));
    bp_canvas.renderAll();
}

function obj_negrita() {
    var obj = bp_canvas && bp_canvas.getActiveObject();
    if (!obj) return;
    obj.set('fontWeight', obj.fontWeight === 'bold' ? 'normal' : 'bold');
    bp_canvas.renderAll();
}

function obj_cursiva() {
    var obj = bp_canvas && bp_canvas.getActiveObject();
    if (!obj) return;
    obj.set('fontStyle', obj.fontStyle === 'italic' ? 'normal' : 'italic');
    bp_canvas.renderAll();
}

function traer_al_frente() {
    var obj = bp_canvas && bp_canvas.getActiveObject();
    if (!obj) return;
    bp_canvas.bringToFront(obj);
}

function enviar_atras() {
    var obj = bp_canvas && bp_canvas.getActiveObject();
    if (!obj) return;
    bp_canvas.sendToBack(obj);
}

function eliminar_obj_seleccionado() {
    var obj = bp_canvas && bp_canvas.getActiveObject();
    if (!obj) return;
    bp_canvas.remove(obj);
    $('#bp_panel_objeto').hide();
}

/* ══════════════════════════════════════════════
   AJUSTE CON IA
══════════════════════════════════════════════ */
function bp_serializar_elementos() {
    return bp_canvas.getObjects().map(function (obj) {
        return {
            id: obj.id || null,
            tipo: obj.tipo || obj.type,
            text: obj.text || undefined,
            left: Math.round(obj.left),
            top: Math.round(obj.top),
            width: Math.round(obj.width * (obj.scaleX || 1)),
            height: Math.round(obj.height * (obj.scaleY || 1)),
            fontSize: obj.fontSize || undefined,
            fill: obj.fill || undefined,
            angle: obj.angle || 0
        };
    }).filter(function (el) { return !!el.id; });
}

function aplicar_ajuste_ia() {
    var instruccion = $('#bp_instruccion_ia').val().trim();
    if (!instruccion) {
        bootbox.alert('Escribe qué ajuste necesitas.');
        return;
    }
    if (!bp_canvas || bp_canvas.getObjects().length === 0) {
        bootbox.alert('Agrega al menos un elemento al banner antes de pedir un ajuste.');
        return;
    }

    $('#bp_ia_status').text('Aplicando ajuste…');

    $.post('ajax/banners_publicitarios.php?op=ajustar_ia', {
        instruccion: instruccion,
        elementos: JSON.stringify(bp_serializar_elementos())
    }, function (res) {
        if (!res || !res.ok) {
            $('#bp_ia_status').text('');
            bootbox.alert((res && res.msg) || 'No se pudo aplicar el ajuste.');
            return;
        }
        (res.operaciones || []).forEach(function (op) {
            if (!op || !op.id || !op.set) return;
            var obj = bp_canvas.getObjects().find(function (o) { return o.id === op.id; });
            if (!obj) return;
            obj.set(op.set);
        });
        bp_canvas.renderAll();
        $('#bp_ia_status').text('Ajuste aplicado.');
        setTimeout(function () { $('#bp_ia_status').text(''); }, 3000);
    }, 'json').fail(function () {
        $('#bp_ia_status').text('');
        bootbox.alert('Error de conexión al aplicar el ajuste con IA.');
    });
}

/* ══════════════════════════════════════════════
   DESCARGAR / GUARDAR
══════════════════════════════════════════════ */
function bp_descargar_dataurl(dataUrl, nombre) {
    var a = document.createElement('a');
    a.href = dataUrl;
    a.download = (nombre || 'banner') + '.png';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
}

function descargar_png() {
    if (!bp_canvas) return;
    var dataUrl = bp_canvas.toDataURL({ format: 'png', multiplier: 1 / bp_canvas.getZoom() });
    var nombre = $('#bp_nombre').val().trim() || 'banner';
    bp_descargar_dataurl(dataUrl, nombre);
}

function guardar_banner() {
    var nombre = $('#bp_nombre').val().trim();
    if (!nombre) {
        bootbox.alert('Escribe un nombre para el banner.');
        return;
    }
    if (!bp_canvas) return;

    var unidad = $('#bp_unidad').val();
    var ancho_original = $('#bp_ancho').val();
    var alto_original  = $('#bp_alto').val();
    var ancho_px = Math.round(bp_canvas.getWidth() / bp_canvas.getZoom());
    var alto_px  = Math.round(bp_canvas.getHeight() / bp_canvas.getZoom());

    var payload = {
        id: $('#bp_id').val() || 0,
        nombre: nombre,
        ancho_px: ancho_px,
        alto_px: alto_px,
        unidad_original: unidad,
        ancho_original: ancho_original,
        alto_original: alto_original,
        diseno_json: JSON.stringify(bp_canvas.toJSON(['id', 'tipo'])),
        imagen_final_base64: bp_canvas.toDataURL({ format: 'png', multiplier: 1 / bp_canvas.getZoom() })
    };

    $.post('ajax/banners_publicitarios.php?op=guardar', payload, function (res) {
        if (res && res.ok) {
            $('#bp_id').val(res.id);
            $('#bp_form_titulo').text('Editando: ' + nombre);
            $('#btn_cancelar_bp').show();
            cargar_galeria();
            bootbox.alert('Banner guardado correctamente.');
        } else {
            bootbox.alert((res && res.msg) || 'Error al guardar el banner.');
        }
    }, 'json').fail(function () {
        bootbox.alert('Error de conexión al guardar el banner.');
    });
}

function cancelar_bp() {
    $('#bp_id').val(0);
    $('#bp_nombre').val('');
    $('#bp_preset').val('');
    $('#bp_ancho').val(1080);
    $('#bp_alto').val(1080);
    $('#bp_unidad').val('px');
    cambio_unidad();
    $('#bp_form_titulo').text('Nuevo Banner');
    $('#btn_cancelar_bp').hide();
    crear_lienzo();
}

/* ══════════════════════════════════════════════
   GALERÍA
══════════════════════════════════════════════ */
function cargar_galeria() {
    $.get('ajax/banners_publicitarios.php?op=listar', function (res) {
        var $tbody = $('#bp_galeria_tbody');
        if (!res || !res.ok || !res.datos || res.datos.length === 0) {
            $tbody.html('<tr><td colspan="5" style="text-align:center;color:#aaa;padding:16px;">Sin banners creados.</td></tr>');
            return;
        }
        var filas = res.datos.map(function (b) {
            var miniatura = b.imagen_final_base64
                ? '<img src="' + b.imagen_final_base64 + '" style="width:70px;height:auto;border:1px solid #ddd;border-radius:4px;">'
                : '<span style="color:#aaa;">—</span>';
            var tamano = b.ancho_px + ' x ' + b.alto_px + ' px' + (b.unidad_original === 'cm' ? ' (' + b.ancho_original + ' x ' + b.alto_original + ' cm)' : '');
            return '<tr>' +
                '<td>' + miniatura + '</td>' +
                '<td>' + $('<div>').text(b.nombre).html() + '</td>' +
                '<td style="font-size:12px;">' + tamano + '</td>' +
                '<td style="font-size:12px;">' + (b.creado_en || '') + '</td>' +
                '<td>' +
                '<button class="bp-btn-sec" onclick="editar_bp(' + b.id + ')">Editar</button> ' +
                '<button class="bp-btn-sec" onclick="descargar_bp(' + b.id + ', \'' + $('<div>').text(b.nombre).html().replace(/'/g, "") + '\')">Descargar</button> ' +
                '<button class="bp-btn-sec bp-btn-danger" onclick="borrar_bp(' + b.id + ')">Eliminar</button>' +
                '</td>' +
                '</tr>';
        }).join('');
        $tbody.html(filas);
    }, 'json');
}

function editar_bp(id) {
    $.get('ajax/banners_publicitarios.php?op=get_one&id=' + id, function (res) {
        if (!res || !res.ok) {
            bootbox.alert('No se pudo cargar el banner.');
            return;
        }
        var b = res.datos;
        $('#bp_id').val(b.id);
        $('#bp_nombre').val(b.nombre);
        $('#bp_preset').val('');
        $('#bp_unidad').val(b.unidad_original || 'px');
        $('#bp_ancho').val(b.unidad_original === 'cm' ? b.ancho_original : b.ancho_px);
        $('#bp_alto').val(b.unidad_original === 'cm' ? b.alto_original : b.alto_px);
        cambio_unidad();

        crear_lienzo(parseInt(b.ancho_px, 10), parseInt(b.alto_px, 10));

        bp_canvas.loadFromJSON(b.diseno_json, function () {
            bp_canvas.renderAll();
        });

        $('#bp_form_titulo').text('Editando: ' + b.nombre);
        $('#btn_cancelar_bp').show();
        $('html, body').animate({ scrollTop: 0 }, 300);
    }, 'json');
}

function descargar_bp(id, nombre) {
    $.get('ajax/banners_publicitarios.php?op=get_one&id=' + id, function (res) {
        if (!res || !res.ok || !res.datos.imagen_final_base64) {
            bootbox.alert('No se pudo descargar el banner.');
            return;
        }
        bp_descargar_dataurl(res.datos.imagen_final_base64, nombre || 'banner');
    }, 'json');
}

function borrar_bp(id) {
    bootbox.confirm('¿Eliminar este banner? Esta acción no se puede deshacer.', function (ok) {
        if (!ok) return;
        $.post('ajax/banners_publicitarios.php?op=borrar', { id: id }, function (res) {
            if (res && res.ok) {
                cargar_galeria();
            } else {
                bootbox.alert('Error al eliminar.');
            }
        }, 'json');
    });
}
