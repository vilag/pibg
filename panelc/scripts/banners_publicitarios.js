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
   SELECTOR DE MODO (manual / automático con IA)
══════════════════════════════════════════════ */
function cambiar_modo(modo) {
    var esAuto = modo === 'auto';
    $('#bp_modo_manual_card').toggleClass('bp-modo-activa', !esAuto);
    $('#bp_modo_auto_card').toggleClass('bp-modo-activa', esAuto);
    $('#bp_seccion_auto').toggle(esAuto);
    $('#bp_seccion_manual').toggle(!esAuto);
}

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

function bp_set_fondo_desde_dataurl(dataUrl, callback) {
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
        bp_canvas.setBackgroundImage(img, function () {
            bp_canvas.renderAll();
            if (callback) callback();
        });
    });
}

function subir_imagen_fondo(input) {
    var file = input.files && input.files[0];
    if (!file || !bp_canvas) return;
    bp_procesar_imagen(file, 1600, 'image/jpeg', function (dataUrl) {
        bp_set_fondo_desde_dataurl(dataUrl);
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

/* ══════════════════════════════════════════════════════════════
   MODO AUTOMÁTICO CON IA
   Sistema de diseño: plantillas + paletas curadas (no coordenadas
   libres de la IA). Groq solo elige de listas cerradas y pule
   textos; la composición la resuelve este archivo.
══════════════════════════════════════════════════════════════ */

var bpa_imagen_elegida = null;

var BP_PALETAS = {
    institucional:     { overlay: '#0b1f33', overlayOpacidad: 0.55, texto: '#ffffff', textoSecundario: '#dbe7f3', acento: '#8fb4d9', panel: '#ffffff', textoPanel: '#1D4268' },
    calido_festivo:    { overlay: '#3a0d0d', overlayOpacidad: 0.55, texto: '#ffffff', textoSecundario: '#ffe6bf', acento: '#e8b84b', panel: '#fff7e6', textoPanel: '#5c1a1a' },
    elegante_oscuro:   { overlay: '#0d0d0d', overlayOpacidad: 0.60, texto: '#ffffff', textoSecundario: '#e6d9b8', acento: '#cfa93c', panel: '#1a1a1a', textoPanel: '#ffffff' },
    vibrante_jovenes:  { overlay: '#2a0e3d', overlayOpacidad: 0.55, texto: '#ffffff', textoSecundario: '#ffd9c2', acento: '#ff8c42', panel: '#ffffff', textoPanel: '#2a0e3d' },
    natural_esperanza: { overlay: '#10281d', overlayOpacidad: 0.50, texto: '#ffffff', textoSecundario: '#dff0da', acento: '#8bc48a', panel: '#f2f7f0', textoPanel: '#14361f' }
};

var BP_ICONOS_CONTACTO = {
    telefono:  ['phone',       '☎'],
    direccion: ['map-marker',  '⚑'],
    correo:    ['email',       '✉']
};

/* ── Formulario ── */
function bpa_aplicar_preset_tamano() {
    var val = $('#bpa_preset').val();
    if (!val) return;
    var partes = val.split('|');
    $('#bpa_ancho').val(partes[0]);
    $('#bpa_alto').val(partes[1]);
    $('#bpa_unidad').val(partes[2]);
}

function bpa_input_logo_file() {
    var input = document.getElementById('bpa_input_logo');
    return input && input.files && input.files[0] ? input.files[0] : null;
}

/* ── Búsqueda y selección de imagen (Pexels) ── */
function bpa_buscar_imagenes() {
    var tema = $('#bpa_tema').val().trim();
    if (!tema) {
        bootbox.alert('Escribe un tema o palabras clave primero.');
        return;
    }

    var unidad = $('#bpa_unidad').val();
    var ancho = bp_valor_a_px($('#bpa_ancho').val(), unidad);
    var alto  = bp_valor_a_px($('#bpa_alto').val(), unidad);
    var ratio = ancho / alto;
    var orientacion = ratio >= 1.15 ? 'horizontal' : (ratio <= 0.85 ? 'vertical' : 'all');

    bpa_imagen_elegida = null;
    $('#bpa_btn_generar').prop('disabled', true);
    $('#bpa_buscar_status').text('Buscando imágenes…');
    $('#bpa_picker_grid').html('');

    $.get('ajax/banners_publicitarios.php?op=buscar_imagenes', { tema: tema, orientacion: orientacion }, function (res) {
        $('#bpa_buscar_status').text('');
        if (!res || !res.ok || !res.datos || !res.datos.length) {
            $('#bpa_picker_grid').html('<p class="bp-hint">' + ((res && res.msg) || 'No se encontraron imágenes para ese tema. Intenta con otras palabras.') + '</p>');
            return;
        }
        var html = res.datos.map(function (hit, idx) {
            return '<div class="bp-picker-item" data-idx="' + idx + '" onclick="bpa_elegir_imagen(this, ' + idx + ')">' +
                '<img src="' + hit.preview + '" alt="">' +
                '</div>';
        }).join('');
        $('#bpa_picker_grid').html(html);
        $('#bpa_picker_grid').data('hits', res.datos);
    }, 'json').fail(function () {
        $('#bpa_buscar_status').text('');
        bootbox.alert('Error de conexión al buscar imágenes.');
    });
}

function bpa_elegir_imagen(el, idx) {
    var hits = $('#bpa_picker_grid').data('hits') || [];
    var hit = hits[idx];
    if (!hit) return;
    $('#bpa_picker_grid .bp-picker-item').removeClass('bp-picker-item-activa');
    $(el).addClass('bp-picker-item-activa');
    bpa_imagen_elegida = hit;
    $('#bpa_btn_generar').prop('disabled', false);
}

/* ── Íconos (Iconify — JSON, sin API key, se renderiza localmente) ──
   El parámetro "color" de la API solo aplica al endpoint .svg individual;
   el endpoint .json (usado aquí para evitar cargar imágenes cross-origin
   dentro del canvas) siempre devuelve el body con fill="currentColor",
   así que el color se reemplaza aquí mismo antes de construir el SVG. */
function bp_obtener_icono_svg(nombre, colorHex, callback) {
    var url = 'https://api.iconify.design/mdi.json?icons=' + encodeURIComponent(nombre);
    fetch(url).then(function (r) { return r.json(); }).then(function (data) {
        var info = data && data.icons && data.icons[nombre];
        if (!info) { callback(null); return; }
        var w = info.width || data.width || 24;
        var h = info.height || data.height || 24;
        var body = info.body.replace(/currentColor/g, colorHex);
        var svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 ' + w + ' ' + h + '" width="' + w + '" height="' + h + '">' + body + '</svg>';
        fabric.loadSVGFromString(svg, function (objects, options) {
            callback(fabric.util.groupSVGElements(objects, options));
        });
    }).catch(function () { callback(null); });
}

function bpa_colocar_icono_svg(nombreIcono, glifoFallback, colorHex, x, y, tam) {
    bp_obtener_icono_svg(nombreIcono, colorHex, function (obj) {
        if (obj) {
            obj.set({ left: x, top: y, selectable: true });
            obj.scaleToWidth(tam);
            bp_canvas.add(obj);
        } else {
            bpa_texto(glifoFallback, 'icono', { left: x, top: y, width: tam * 1.5, fontSize: tam, fill: colorHex });
        }
        bp_canvas.renderAll();
    });
}

function bpa_colocar_icono_decorativo(nombreIcono, colorHex, x, y, tam) {
    if (!nombreIcono) return;
    bp_obtener_icono_svg(nombreIcono, colorHex, function (obj) {
        if (!obj) return;
        obj.set({ left: x, top: y, selectable: true });
        obj.scaleToWidth(tam);
        bp_canvas.add(obj);
        bp_canvas.renderAll();
    });
}

function bpa_colocar_logo(dataUrl, x, y, tam) {
    if (!dataUrl) return;
    fabric.Image.fromURL(dataUrl, function (img) {
        var escala = tam / img.width;
        img.set({ left: x, top: y, scaleX: escala, scaleY: escala, id: 'logo_' + (++bp_contador_ids), tipo: 'imagen_secundaria' });
        bp_canvas.add(img);
        bp_canvas.renderAll();
    });
}

/* ── Helpers de composición ── */
function bpa_hex_a_rgb(hex) {
    hex = hex.replace('#', '');
    var r = parseInt(hex.substring(0, 2), 16), g = parseInt(hex.substring(2, 4), 16), b = parseInt(hex.substring(4, 6), 16);
    return r + ',' + g + ',' + b;
}

function bpa_agregar_overlay_degradado(cw, y, h, colorHex, opacidadMax) {
    var rgb = bpa_hex_a_rgb(colorHex);
    var rect = new fabric.Rect({
        left: 0, top: y, width: cw, height: h, selectable: false, evented: false,
        fill: new fabric.Gradient({
            type: 'linear',
            coords: { x1: 0, y1: 0, x2: 0, y2: h },
            colorStops: [
                { offset: 0, color: 'rgba(' + rgb + ',0)' },
                { offset: 1, color: 'rgba(' + rgb + ',' + opacidadMax + ')' }
            ]
        })
    });
    bp_canvas.add(rect);
}

function bpa_agregar_overlay_solido_completo(cw, ch, colorHex, opacidad) {
    var rect = new fabric.Rect({ left: 0, top: 0, width: cw, height: ch, fill: colorHex, opacity: opacidad, selectable: false, evented: false });
    bp_canvas.add(rect);
}

function bpa_rect_solido(x, y, w, h, colorHex, opacidad) {
    var rect = new fabric.Rect({ left: x, top: y, width: w, height: h, fill: colorHex, opacity: opacidad, rx: 12, ry: 12 });
    bp_canvas.add(rect);
    return rect;
}

function bpa_texto(texto, tipo, opts) {
    if (!texto) return null;
    var base = { left: 0, top: 0, width: 200, fontSize: 20, fill: '#000000', fontFamily: 'Arial', id: tipo + '_' + (++bp_contador_ids), tipo: tipo };
    var config = Object.assign(base, opts || {});
    var tb = new fabric.Textbox(texto, config);
    bp_canvas.add(tb);
    return tb;
}

function bpa_bloque_contacto(c, colorTexto, colorAcento, x, y, maxWidth, fontSize) {
    var lineas = [];
    if (c.telefono)  lineas.push(['telefono', c.telefono]);
    if (c.direccion) lineas.push(['direccion', c.direccion]);
    if (c.correo)    lineas.push(['correo', c.correo]);
    if (!lineas.length) return;

    var indent = Math.round(fontSize * 1.5);
    var lineHeight = Math.round(fontSize * 1.6);
    var curY = y;

    lineas.forEach(function (linea) {
        var iconoInfo = BP_ICONOS_CONTACTO[linea[0]];
        bpa_texto(linea[1], 'contacto', {
            left: x + indent, top: curY, width: maxWidth - indent,
            fontSize: fontSize, fill: colorTexto
        });
        bpa_colocar_icono_svg(iconoInfo[0], iconoInfo[1], colorAcento, x, curY - fontSize * 0.15, fontSize * 1.25);
        curY += lineHeight;
    });
}

/* ── Selección determinística de plantilla (fallback si Groq no aplica) ── */
function bpa_seleccionar_plantilla(ratio, longitudMensaje, tieneLogo) {
    if (ratio >= 1.4) return 'franja_inferior';
    if (ratio <= 0.75) return 'panel_lateral';
    if (longitudMensaje > 140) return 'tarjeta_flotante';
    if (tieneLogo) return 'centro_apilado';
    return 'minimal_esquinas';
}

/* ── Plantillas (composición con criterio de diseño gráfico) ── */
function bpa_plantilla_centro_apilado(cw, ch, pal, c, iconoNombre, logoDataUrl) {
    var margen = Math.round(cw * 0.07);
    bpa_agregar_overlay_degradado(cw, ch * 0.4, ch * 0.6, pal.overlay, pal.overlayOpacidad);

    var tituloSize = Math.round(ch * 0.065);
    var y = ch * 0.12;
    if (logoDataUrl) {
        bpa_colocar_logo(logoDataUrl, cw / 2 - cw * 0.09, ch * 0.04, cw * 0.18);
        y = ch * 0.24;
    }
    if (c.titulo) {
        bpa_texto(c.titulo, 'titulo', { left: margen, top: y, width: cw - margen * 2, fontSize: tituloSize, fontWeight: 'bold', fill: pal.texto, textAlign: 'center' });
        y += tituloSize * 1.4;
    }
    if (c.mensaje) {
        bpa_texto(c.mensaje, 'parrafo', { left: margen, top: y, width: cw - margen * 2, fontSize: Math.round(ch * 0.03), fill: pal.textoSecundario, textAlign: 'center' });
    }
    bpa_bloque_contacto(c, pal.texto, pal.acento, margen, ch - margen * 2.4, cw - margen * 2, Math.round(ch * 0.022));
    bpa_colocar_icono_decorativo(iconoNombre, pal.acento, cw / 2 - ch * 0.03, ch * 0.06, ch * 0.06);
}

function bpa_plantilla_franja_inferior(cw, ch, pal, c, iconoNombre, logoDataUrl) {
    var margen = Math.round(cw * 0.04);
    var bandaAlto = ch * 0.32;
    bpa_rect_solido(0, ch - bandaAlto, cw, bandaAlto, pal.overlay, pal.overlayOpacidad);

    var tituloSize = Math.round(ch * 0.075);
    var anchoTexto = cw * 0.6;
    var y = ch - bandaAlto + bandaAlto * 0.12;
    if (c.titulo) {
        bpa_texto(c.titulo, 'titulo', { left: margen, top: y, width: anchoTexto, fontSize: tituloSize, fontWeight: 'bold', fill: pal.texto });
        y += tituloSize * 1.3;
    }
    if (c.mensaje) {
        bpa_texto(c.mensaje, 'parrafo', { left: margen, top: y, width: anchoTexto, fontSize: Math.round(ch * 0.032), fill: pal.textoSecundario });
    }
    bpa_bloque_contacto(c, pal.texto, pal.acento, cw * 0.66, ch - bandaAlto + bandaAlto * 0.15, cw * 0.3, Math.round(ch * 0.026));
    bpa_colocar_logo(logoDataUrl, cw - cw * 0.16, margen, cw * 0.11);
    bpa_colocar_icono_decorativo(iconoNombre, pal.acento, margen, ch - bandaAlto - ch * 0.09, ch * 0.07);
}

function bpa_plantilla_panel_lateral(cw, ch, pal, c, iconoNombre, logoDataUrl) {
    var margen = Math.round(cw * 0.08);
    var panelAlto = ch * 0.42;
    var panelY = ch - panelAlto;
    bpa_rect_solido(0, panelY, cw, panelAlto, pal.panel, 0.97);

    bpa_colocar_logo(logoDataUrl, cw / 2 - cw * 0.1, panelY * 0.08, cw * 0.2);

    var tituloSize = Math.round(ch * 0.05);
    var y = panelY + panelAlto * 0.12;
    if (c.titulo) {
        bpa_texto(c.titulo, 'titulo', { left: margen, top: y, width: cw - margen * 2, fontSize: tituloSize, fontWeight: 'bold', fill: pal.textoPanel, textAlign: 'center' });
        y += tituloSize * 1.4;
    }
    if (c.mensaje) {
        bpa_texto(c.mensaje, 'parrafo', { left: margen, top: y, width: cw - margen * 2, fontSize: Math.round(ch * 0.026), fill: pal.textoPanel, textAlign: 'center' });
    }
    bpa_bloque_contacto(c, pal.textoPanel, pal.acento, margen, panelY + panelAlto * 0.78, cw - margen * 2, Math.round(ch * 0.02));
    bpa_colocar_icono_decorativo(iconoNombre, pal.acento, cw / 2 - ch * 0.025, panelY - ch * 0.07, ch * 0.055);
}

function bpa_plantilla_tarjeta_flotante(cw, ch, pal, c, iconoNombre, logoDataUrl) {
    bpa_agregar_overlay_solido_completo(cw, ch, pal.overlay, pal.overlayOpacidad * 0.6);

    var margen = cw * 0.08;
    var tarjetaX = margen, tarjetaY = ch * 0.18, tarjetaW = cw - margen * 2, tarjetaH = ch * 0.64;
    bpa_rect_solido(tarjetaX, tarjetaY, tarjetaW, tarjetaH, pal.panel, 0.95);

    var padding = tarjetaW * 0.08;
    var y = tarjetaY + tarjetaH * 0.08;
    if (logoDataUrl) {
        bpa_colocar_logo(logoDataUrl, tarjetaX + tarjetaW / 2 - cw * 0.08, y, cw * 0.16);
        y += cw * 0.16 + 10;
    }
    var tituloSize = Math.round(ch * 0.045);
    if (c.titulo) {
        bpa_texto(c.titulo, 'titulo', { left: tarjetaX + padding, top: y, width: tarjetaW - padding * 2, fontSize: tituloSize, fontWeight: 'bold', fill: pal.textoPanel, textAlign: 'center' });
        y += tituloSize * 1.4;
    }
    if (c.mensaje) {
        bpa_texto(c.mensaje, 'parrafo', { left: tarjetaX + padding, top: y, width: tarjetaW - padding * 2, fontSize: Math.round(ch * 0.024), fill: pal.textoPanel, textAlign: 'center' });
    }
    bpa_bloque_contacto(c, pal.textoPanel, pal.acento, tarjetaX + padding, tarjetaY + tarjetaH - tarjetaH * 0.22, tarjetaW - padding * 2, Math.round(ch * 0.02));
    bpa_colocar_icono_decorativo(iconoNombre, pal.acento, cw / 2 - ch * 0.03, tarjetaY - ch * 0.08, ch * 0.06);
}

function bpa_plantilla_minimal_esquinas(cw, ch, pal, c, iconoNombre, logoDataUrl) {
    bpa_agregar_overlay_degradado(cw, ch * 0.55, ch * 0.45, pal.overlay, pal.overlayOpacidad * 0.85);

    var margen = cw * 0.06;
    var tituloSize = Math.round(ch * 0.06);
    var y = ch * 0.68;
    var sombra = new fabric.Shadow({ color: 'rgba(0,0,0,0.6)', blur: 8, offsetX: 0, offsetY: 2 });

    if (c.titulo) {
        var t = bpa_texto(c.titulo, 'titulo', { left: margen, top: y, width: cw - margen * 2, fontSize: tituloSize, fontWeight: 'bold', fill: pal.texto });
        if (t) t.set('shadow', sombra);
        y += tituloSize * 1.3;
    }
    if (c.mensaje) {
        var m = bpa_texto(c.mensaje, 'parrafo', { left: margen, top: y, width: cw - margen * 2, fontSize: Math.round(ch * 0.03), fill: pal.textoSecundario });
        if (m) m.set('shadow', sombra);
    }
    bpa_bloque_contacto(c, pal.texto, pal.acento, margen, ch - margen * 1.6, cw - margen * 2, Math.round(ch * 0.02));
    bpa_colocar_logo(logoDataUrl, margen, margen, cw * 0.14);
    bpa_colocar_icono_decorativo(iconoNombre, pal.acento, cw - margen - ch * 0.05, margen, ch * 0.05);
}

function bpa_construir_plantilla(nombre, cw, ch, pal, c, iconoNombre, logoDataUrl) {
    switch (nombre) {
        case 'franja_inferior':  bpa_plantilla_franja_inferior(cw, ch, pal, c, iconoNombre, logoDataUrl); break;
        case 'panel_lateral':    bpa_plantilla_panel_lateral(cw, ch, pal, c, iconoNombre, logoDataUrl); break;
        case 'tarjeta_flotante': bpa_plantilla_tarjeta_flotante(cw, ch, pal, c, iconoNombre, logoDataUrl); break;
        case 'minimal_esquinas': bpa_plantilla_minimal_esquinas(cw, ch, pal, c, iconoNombre, logoDataUrl); break;
        default:                 bpa_plantilla_centro_apilado(cw, ch, pal, c, iconoNombre, logoDataUrl); break;
    }
}

/* ── Orquestación principal ── */
function bpa_generar_banner() {
    var nombre  = $('#bpa_nombre').val().trim();
    var tema    = $('#bpa_tema').val().trim();
    var titulo  = $('#bpa_titulo').val().trim();
    var mensaje = $('#bpa_mensaje').val().trim();

    if (!nombre) { bootbox.alert('Escribe un nombre para el banner.'); return; }
    if (!tema) { bootbox.alert('Escribe un tema o palabras clave.'); return; }
    if (!titulo && !mensaje) { bootbox.alert('Escribe al menos un título o un mensaje.'); return; }
    if (!bpa_imagen_elegida) { bootbox.alert('Busca y elige una imagen de fondo primero.'); return; }

    $('#bpa_generar_status').text('Descargando imagen…');
    $('#bpa_btn_generar').prop('disabled', true);

    $.post('ajax/banners_publicitarios.php?op=descargar_imagen', { url: bpa_imagen_elegida.webformat }, function (resImg) {
        if (!resImg || !resImg.ok) {
            $('#bpa_generar_status').text('');
            $('#bpa_btn_generar').prop('disabled', false);
            bootbox.alert((resImg && resImg.msg) || 'No se pudo descargar la imagen elegida.');
            return;
        }

        var unidad         = $('#bpa_unidad').val();
        var ancho_original  = $('#bpa_ancho').val();
        var alto_original   = $('#bpa_alto').val();
        var ancho_px = bp_valor_a_px(ancho_original, unidad);
        var alto_px  = bp_valor_a_px(alto_original, unidad);
        var ratio = ancho_px / alto_px;
        var archivoLogo = bpa_input_logo_file();

        $('#bpa_generar_status').text('Diseñando banner…');

        $.post('ajax/banners_publicitarios.php?op=auto_generar_ia', {
            tema: tema,
            titulo: titulo,
            mensaje: mensaje,
            ratio: ratio,
            tiene_logo: archivoLogo ? '1' : '0',
            mejorar_textos: $('#bpa_mejorar_textos').is(':checked') ? '1' : '0',
            paleta_forzada: $('#bpa_paleta').val()
        }, function (resIA) {
            $('#bpa_btn_generar').prop('disabled', false);
            if (!resIA || !resIA.ok) {
                $('#bpa_generar_status').text('');
                bootbox.alert((resIA && resIA.msg) || 'No se pudo generar el diseño.');
                return;
            }

            var plantilla = resIA.template || bpa_seleccionar_plantilla(ratio, (resIA.mensaje || '').length, !!archivoLogo);
            var paleta = BP_PALETAS[resIA.palette] || BP_PALETAS.institucional;
            var contenido = {
                titulo: resIA.titulo || titulo,
                mensaje: resIA.mensaje || mensaje,
                telefono: $('#bpa_telefono').val().trim(),
                direccion: $('#bpa_direccion').val().trim(),
                correo: $('#bpa_correo').val().trim()
            };

            $('#bp_nombre').val(nombre);
            $('#bp_ancho').val(ancho_original);
            $('#bp_alto').val(alto_original);
            $('#bp_unidad').val(unidad);
            $('#bp_preset').val('');
            cambio_unidad();
            crear_lienzo(ancho_px, alto_px);

            var terminarGeneracion = function (logoDataUrl) {
                bp_set_fondo_desde_dataurl(resImg.data, function () {
                    bpa_construir_plantilla(plantilla, ancho_px, alto_px, paleta, contenido, resIA.icon, logoDataUrl);
                    bp_canvas.renderAll();
                    $('#bpa_generar_status').text('');
                    cambiar_modo('manual');
                    $('#bp_form_titulo').text('Editando: ' + nombre);
                    $('html, body').animate({ scrollTop: 0 }, 300);
                });
            };

            if (archivoLogo) {
                bp_procesar_imagen(archivoLogo, 800, 'image/png', terminarGeneracion);
            } else {
                terminarGeneracion(null);
            }
        }, 'json').fail(function () {
            $('#bpa_btn_generar').prop('disabled', false);
            $('#bpa_generar_status').text('');
            bootbox.alert('Error de conexión al generar el diseño.');
        });
    }, 'json').fail(function () {
        $('#bpa_btn_generar').prop('disabled', false);
        $('#bpa_generar_status').text('');
        bootbox.alert('Error de conexión al descargar la imagen.');
    });
}
