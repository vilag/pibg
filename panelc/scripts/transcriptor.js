/* ============================================================
   transcriptor.js — Transcripción de audio/video con Whisper
============================================================ */

var MAX_MB = 100;

$(function () {

    // Drag & drop
    var $zona = $('#drop_zona');

    $zona.on('dragover dragenter', function (e) {
        e.preventDefault();
        $zona.addClass('drag-over');
    });
    $zona.on('dragleave drop', function (e) {
        e.preventDefault();
        $zona.removeClass('drag-over');
    });
    $zona.on('drop', function (e) {
        var archivo = e.originalEvent.dataTransfer.files[0];
        if (archivo) cargar_archivo(archivo);
    });

    // Click en zona = abrir selector
    $zona.on('click', function () {
        $('#input_archivo').trigger('click');
    });

    $('#input_archivo').on('change', function () {
        if (this.files[0]) cargar_archivo(this.files[0]);
    });

    // Submit
    $('#form_transcriptor').on('submit', function (e) {
        e.preventDefault();
        transcribir();
    });

    // Acciones resultado
    $('#btn_copiar').on('click', copiar_texto);
    $('#btn_descargar').on('click', descargar_txt);
    $('#btn_limpiar').on('click', limpiar);

    // Toggle panel dividir
    $('#chk_dividir').on('change', function () {
        if ($(this).is(':checked')) {
            $('#panel_dividir').css('display', 'flex');
        } else {
            $('#panel_dividir').hide();
        }
    });

    // Cargar historial al abrir la pagina
    cargar_historial();
});;

/* ---- Archivo seleccionado ---- */
var archivo_sel = null;

function cargar_archivo(archivo) {
    var ext_ok = /\.(flac|m4a|mp3|mp4|mpeg|mpga|ogg|opus|wav|webm|mov|avi|mkv)$/i.test(archivo.name);
    if (!ext_ok) {
        mostrar_error('Formato no soportado. Usa: mp3, mp4, wav, m4a, ogg, webm, flac, mov…');
        return;
    }
    var mb = archivo.size / (1024 * 1024);
    if (mb > MAX_MB) {
        mostrar_error('El archivo supera el límite de ' + MAX_MB + ' MB (tamaño: ' + mb.toFixed(1) + ' MB).');
        return;
    }
    // Video > 25 MB: no se puede comprimir en el navegador
    if (mb > 25 && es_video(archivo.name)) {
        mostrar_error('Los videos de más de 25 MB no son compatibles directamente. Extrae el audio del video (MP3) con cualquier conversor online y sube el MP3.');
        return;
    }

    archivo_sel = archivo;
    $('#error_box').hide();
    $('#aviso_box').hide();

    // Mostrar info del archivo
    var icono = es_video(archivo.name) ? '🎬' : '🎵';
    $('#archivo_nombre').text(archivo.name);
    $('#archivo_tamano').text(mb.toFixed(2) + ' MB');
    $('#archivo_icono').text(icono);
    $('#archivo_info').show();
    $('#drop_zona').addClass('tiene-archivo');
    $('#btn_transcribir').prop('disabled', false);
}

function es_video(nombre) {
    return /\.(mp4|mov|avi|mkv|webm)$/i.test(nombre);
}

/* ---- Transcribir ---- */
async function transcribir() {
    if (!archivo_sel) { mostrar_error('Selecciona un archivo primero.'); return; }

    estado_cargando(true);
    $('#error_box').hide();
    $('#aviso_box').hide();
    $('#resultado_box').hide();
    $('#segmentos_box').hide();
    $('#progreso_partes').hide();

    var mb       = archivo_sel.size / (1024 * 1024);
    var dividir  = $('#chk_dividir').is(':checked');
    var lang     = $('#select_idioma').val();
    var useTs    = $('#chk_timestamps').is(':checked');
    var durSeg   = parseInt($('#select_duracion').val(), 10) || 900;

    // ---- Modo dividir en partes ----
    if (dividir) {
        try {
            await transcribir_en_partes(archivo_sel, lang, useTs, durSeg);
        } catch (e) {
            estado_cargando(false);
            mostrar_error('Error al procesar: ' + e.message);
        }
        return;
    }

    // ---- Modo normal (un solo archivo) ----
    var archivo_a_subir = archivo_sel;

    // Si el audio supera 25 MB, comprimir en el navegador antes de subir
    if (mb > 25) {
        try {
            var blob_comp = await comprimir_audio_browser(archivo_sel);
            var mb_comp = blob_comp.size / (1024 * 1024);

            if (blob_comp.size > 25 * 1024 * 1024) {
                estado_cargando(false);
                mostrar_error('Incluso comprimido el audio pesa ' + mb_comp.toFixed(1) + ' MB. El audio es demasiado largo. Divídelo en partes de máximo 2 horas.');
                return;
            }

            archivo_a_subir = new File(
                [blob_comp],
                archivo_sel.name.replace(/\.[^.]+$/, '') + '_comp.mp3',
                { type: 'audio/mpeg' }
            );
        } catch (e) {
            estado_cargando(false);
            mostrar_error('No se pudo comprimir el audio: ' + e.message);
            return;
        }
    }

    var fd = new FormData();
    fd.append('archivo',    archivo_a_subir);
    fd.append('lang',       $('#select_idioma').val());
    fd.append('timestamps', $('#chk_timestamps').is(':checked') ? '1' : '0');

    $.ajax({
        url:         'ajax/transcriptor.php?op=transcribir',
        method:      'POST',
        data:        fd,
        processData: false,
        contentType: false,
        timeout:     360000,
        xhr: function () {
            var xhr = new window.XMLHttpRequest();
            xhr.upload.addEventListener('progress', function (e) {
                if (e.lengthComputable) {
                    var pct = Math.round((e.loaded / e.total) * 100);
                    if (pct < 100) {
                        $('#progreso_texto').text('Subiendo archivo… ' + pct + '%');
                    } else {
                        $('#progreso_texto').text('Transcribiendo con Whisper… (puede tomar unos segundos)');
                    }
                }
            }, false);
            return xhr;
        },
        success: function (r) {
            estado_cargando(false);
            if (r.ok) {
                mostrar_resultado(r);
                guardar_transcripcion(r.texto, archivo_sel.name, $('#select_idioma').val());
            } else {
                mostrar_error(r.msg);
            }
        },
        error: function (xhr, status) {
            estado_cargando(false);
            if (status === 'timeout') {
                mostrar_error('La solicitud tardó demasiado. Intenta con un archivo más corto.');
            } else {
                mostrar_error('Error de conexión (' + status + ').');
            }
        }
    });
}

function estado_cargando(on) {
    $('#btn_transcribir').prop('disabled', on);
    $('#transcribir_label').toggle(!on);
    $('#transcribir_spinner').toggle(on);
    if (on) {
        $('#progreso_box').css('display', 'flex');
        $('#progreso_texto').text('Subiendo archivo…');
    } else {
        $('#progreso_box').hide();
        $('#progreso_partes').hide();
    }
}

/* ---- Resultado ---- */
function mostrar_resultado(r) {
    $('#resultado_texto').val(r.texto);
    $('#resultado_box').show();

    if (r.segmentos && r.segmentos.length) {
        var html = '';
        r.segmentos.forEach(function (s) {
            html += '<div class="tr-seg">'
                  + '<span class="tr-seg-ts">' + fmt(s.start) + '</span>'
                  + '<span class="tr-seg-txt">' + $('<div>').text(s.text).html() + '</span>'
                  + '</div>';
        });
        $('#segmentos_lista').html(html);
        $('#segmentos_box').show();
    }
}

/* ---- Acciones ---- */
function copiar_texto() {
    var t = $('#resultado_texto').val();
    if (!t) return;
    navigator.clipboard.writeText(t).then(function () {
        $('#btn_copiar').text('¡Copiado!');
        setTimeout(function () { $('#btn_copiar').text('Copiar texto'); }, 2000);
    });
}

function descargar_txt() {
    var t = $('#resultado_texto').val();
    if (!t) return;
    var nombre = archivo_sel ? archivo_sel.name.replace(/\.[^.]+$/, '') + '.txt' : 'transcripcion.txt';
    var blob = new Blob([t], { type: 'text/plain;charset=utf-8' });
    var a = document.createElement('a');
    a.href = URL.createObjectURL(blob);
    a.download = nombre;
    a.click();
}

function limpiar() {
    archivo_sel = null;
    $('#resultado_texto').val('');
    $('#resultado_box').hide();
    $('#segmentos_box').hide();
    $('#segmentos_lista').html('');
    $('#archivo_info').hide();
    $('#drop_zona').removeClass('tiene-archivo');
    $('#input_archivo').val('');
    $('#btn_transcribir').prop('disabled', true);
    $('#error_box').hide();
    $('#aviso_box').hide();
    $('#progreso_partes').hide();
    $('#partes_lista').html('');
}

function mostrar_error(msg) {
    $('#error_msg').text(msg);
    $('#aviso_box').hide();
    $('#error_box').show();
}

function mostrar_aviso(msg) {
    $('#aviso_msg').text(msg);
    $('#aviso_box').show();
}

/* ============================================================
   GUARDAR EN BD Y GESTIÓN DEL HISTORIAL
============================================================ */
function guardar_transcripcion(texto, nombre, idioma) {
    $.ajax({
        url:    'ajax/transcriptor.php?op=guardar',
        method: 'POST',
        data:   { texto: texto, nombre_archivo: nombre, idioma: idioma || 'es' },
        success: function (r) {
            if (r.ok) cargar_historial();
        }
    });
}

function cargar_historial() {
    $.get('ajax/transcriptor.php?op=listar', function (r) {
        if (!r.ok) return;

        if (!r.datos || !r.datos.length) {
            $('#historial_tabla_wrap').html('<p class="tr-hist-empty">Sin transcripciones guardadas.</p>');
            return;
        }

        var html = '<table class="tr-hist-table">';
        html += '<thead><tr><th>Archivo</th><th>Idioma</th><th>Palabras</th><th>Fecha</th><th></th></tr></thead><tbody>';

        r.datos.forEach(function (d) {
            html += '<tr>'
                  + '<td class="tr-hist-nombre" title="' + $('<div>').text(d.nombre_archivo).html() + '">' + $('<div>').text(d.nombre_archivo).html() + '</td>'
                  + '<td><span class="tr-hist-badge">' + $('<div>').text(d.idioma).html() + '</span></td>'
                  + '<td>' + parseInt(d.num_palabras, 10).toLocaleString() + '</td>'
                  + '<td style="white-space:nowrap;">' + d.fecha_creacion + '</td>'
                  + '<td class="tr-hist-actions">'
                  + '<button class="tr-btn-sec" onclick="ver_transcripcion(' + d.id_transcripcion + ', ' + JSON.stringify(d.nombre_archivo) + ')">Ver</button> '
                  + '<button class="tr-btn-sec tr-btn-danger" onclick="eliminar_transcripcion(' + d.id_transcripcion + ')">Borrar</button>'
                  + '</td>'
                  + '</tr>';
        });

        html += '</tbody></table>';
        $('#historial_tabla_wrap').html(html);
    });
}

function ver_transcripcion(id, nombre) {
    $.get('ajax/transcriptor.php?op=ver&id=' + id, function (r) {
        if (!r.ok) { alert('No se pudo cargar la transcripción.'); return; }
        $('#resultado_texto').val(r.texto);
        $('#resultado_box').show();
        $('#segmentos_box').hide();
        $('html,body').animate({ scrollTop: $('#resultado_box').offset().top - 20 }, 300);
    });
}

function eliminar_transcripcion(id) {
    if (!confirm('¿Eliminar esta transcripción del historial?')) return;
    $.post('ajax/transcriptor.php?op=eliminar', { id: id }, function (r) {
        if (r.ok) {
            cargar_historial();
        } else {
            alert('Error al eliminar: ' + (r.msg || ''));
        }
    });
}

/* ============================================================
   DIVIDIR EN PARTES: decodifica, segmenta, sube parte a parte
============================================================ */
async function transcribir_en_partes(archivo, lang, useTs, durSegundos) {
    $('#progreso_box').hide();
    $('#progreso_partes').show();

    // 1. Decodificar audio completo
    $('#partes_titulo').text('Decodificando audio…');
    await tick();

    var arrayBuffer = await archivo.arrayBuffer();
    var audioCtx    = new (window.AudioContext || window.webkitAudioContext)();
    var audioBuffer = await audioCtx.decodeAudioData(arrayBuffer);

    var sampleRate   = audioBuffer.sampleRate;
    var totalSamples = audioBuffer.length;
    var totalSeg     = totalSamples / sampleRate;           // duración real en segundos
    var samplesXPart = Math.floor(durSegundos * sampleRate);
    var numPartes    = Math.ceil(totalSamples / samplesXPart);

    // 2. Construir lista visual
    var html = '';
    for (var i = 0; i < numPartes; i++) {
        var tIni = fmt(i * durSegundos);
        var tFin = fmt(Math.min((i + 1) * durSegundos, totalSeg));
        html += '<div class="tr-parte-item" id="parte_item_' + i + '">'
              + '<div class="tr-parte-estado" id="parte_est_' + i + '">·</div>'
              + '<span>Parte ' + (i + 1) + ' / ' + numPartes + ' &nbsp;<small style="color:#94a3b8;">(' + tIni + ' – ' + tFin + ')</small></span>'
              + '</div>';
    }
    $('#partes_lista').html(html);
    actualizar_barra(0, numPartes);

    // 3. Procesar partes secuencialmente
    var textos_acumulados   = [];
    var segmentos_acumulados = [];
    var errores              = [];
    var numChannels          = audioBuffer.numberOfChannels;

    for (var p = 0; p < numPartes; p++) {
        var offsetSeg = p * durSegundos;   // offset en segundos para esta parte
        $('#partes_titulo').text('Transcribiendo parte ' + (p + 1) + ' de ' + numPartes + '…');
        $('#parte_est_' + p).removeClass('ok err').addClass('proc').text('');

        try {
            // Extraer muestras de esta parte en mono
            var ini = p * samplesXPart;
            var fin = Math.min(ini + samplesXPart, totalSamples);
            var mono = mezclar_mono(audioBuffer, numChannels, ini, fin);

            // Comprimir a MP3
            $('#partes_titulo').text('Comprimiendo parte ' + (p + 1) + ' de ' + numPartes + '…');
            await tick();
            var mp3Blob = await codificar_mp3(mono, sampleRate);

            // Subir a Whisper
            $('#partes_titulo').text('Transcribiendo parte ' + (p + 1) + ' de ' + numPartes + '…');
            var nombre_parte = archivo.name.replace(/\.[^.]+$/, '') + '_parte' + (p + 1) + '.mp3';
            var r = await subir_a_whisper(mp3Blob, nombre_parte, lang, useTs);

            if (!r.ok) throw new Error(r.msg);

            textos_acumulados.push(r.texto);

            // Si hay timestamps, desplazar el inicio de cada segmento
            if (useTs && r.segmentos && r.segmentos.length) {
                r.segmentos.forEach(function (s) {
                    segmentos_acumulados.push({
                        start: s.start + offsetSeg,
                        end:   s.end   + offsetSeg,
                        text:  s.text
                    });
                });
            }

            $('#parte_est_' + p).removeClass('proc err').addClass('ok').text('✓');
        } catch (e) {
            errores.push('Parte ' + (p + 1) + ': ' + e.message);
            $('#parte_est_' + p).removeClass('proc ok').addClass('err').text('✗');
        }

        actualizar_barra(p + 1, numPartes);

        // Pausa entre partes para no saturar el rate limit de la API
        if (p < numPartes - 1) {
            $('#partes_titulo').text('Esperando antes de la siguiente parte…');
            await new Promise(function (r) { setTimeout(r, 3000); });
        }
    }

    // 4. Mostrar resultados
    estado_cargando(false);

    if (textos_acumulados.length === 0) {
        mostrar_error('No se pudo transcribir ninguna parte.\n' + errores.join('\n'));
        return;
    }

    var texto_final = textos_acumulados.join(' ');
    var r_final     = { ok: true, texto: texto_final };
    if (useTs && segmentos_acumulados.length) r_final.segmentos = segmentos_acumulados;

    mostrar_resultado(r_final);
    guardar_transcripcion(texto_final, archivo.name, lang);

    if (errores.length) {
        mostrar_aviso('Completado con errores en ' + errores.length + ' parte(s):\n' + errores.join('\n'));
    }

    $('#spin_partes').css('animation', 'none');
    $('#partes_titulo').text('Completado — ' + textos_acumulados.length + ' / ' + numPartes + ' partes transcritas.');
}

/* ---- Mezclar a mono (segmento del AudioBuffer) ---- */
function mezclar_mono(audioBuffer, numChannels, ini, fin) {
    var len  = fin - ini;
    var mono = new Float32Array(len);
    for (var c = 0; c < numChannels; c++) {
        var ch = audioBuffer.getChannelData(c);
        for (var i = 0; i < len; i++) {
            mono[i] += ch[ini + i] / numChannels;
        }
    }
    return mono;
}

/* ---- Codificar Float32Array mono a Blob MP3 con lamejs ---- */
async function codificar_mp3(mono, sampleRate) {
    var encoder   = new lamejs.Mp3Encoder(1, sampleRate, 64);
    var mp3Chunks = [];
    var blockSize = 1152;
    var total     = mono.length;

    for (var i = 0; i < total; i += blockSize) {
        var chunk = mono.subarray(i, i + blockSize);
        var int16 = new Int16Array(chunk.length);
        for (var j = 0; j < chunk.length; j++) {
            int16[j] = Math.max(-32768, Math.min(32767, Math.round(chunk[j] * 32767)));
        }
        var buf = encoder.encodeBuffer(int16);
        if (buf.length > 0) mp3Chunks.push(new Uint8Array(buf));
        if (i % (blockSize * 500) === 0) await tick();
    }
    var end = encoder.flush();
    if (end.length > 0) mp3Chunks.push(new Uint8Array(end));
    return new Blob(mp3Chunks, { type: 'audio/mpeg' });
}

/* ---- Subir un Blob de audio a Whisper, retorna promesa con JSON ---- */
function subir_a_whisper(blob, nombre, lang, useTs) {
    return new Promise(function (resolve, reject) {
        var fd = new FormData();
        fd.append('archivo',    new File([blob], nombre, { type: 'audio/mpeg' }));
        fd.append('lang',       lang);
        fd.append('timestamps', useTs ? '1' : '0');

        $.ajax({
            url:         'ajax/transcriptor.php?op=transcribir',
            method:      'POST',
            data:        fd,
            processData: false,
            contentType: false,
            timeout:     180000,
            success:  function (r) { resolve(r); },
            error:    function (x, s) { reject(new Error(s === 'timeout' ? 'Tiempo agotado' : s)); }
        });
    });
}

/* ---- Actualizar barra de progreso ---- */
function actualizar_barra(done, total) {
    var pct = total > 0 ? Math.round((done / total) * 100) : 0;
    $('#barra_fill').css('width', pct + '%');
}

/* ---- Ceder al navegador (para actualizar DOM) ---- */
function tick() { return new Promise(function (r) { setTimeout(r, 0); }); }

/* ============================================================
   Comprimir audio completo en el navegador (para modo normal)
============================================================ */
async function comprimir_audio_browser(archivo) {
    $('#progreso_texto').text('Decodificando audio…');
    // Pequeña pausa para que el DOM actualice
    await new Promise(function (r) { setTimeout(r, 30); });

    var arrayBuffer = await archivo.arrayBuffer();

    var audioCtx = new (window.AudioContext || window.webkitAudioContext)();
    var audioBuffer = await audioCtx.decodeAudioData(arrayBuffer);

    $('#progreso_texto').text('Comprimiendo a MP3 64 kbps…');
    await new Promise(function (r) { setTimeout(r, 30); });

    var numChannels = audioBuffer.numberOfChannels;
    var length      = audioBuffer.length;
    var sampleRate  = audioBuffer.sampleRate;

    // Mezclar todos los canales a mono
    var mono = new Float32Array(length);
    for (var c = 0; c < numChannels; c++) {
        var ch = audioBuffer.getChannelData(c);
        for (var i = 0; i < length; i++) {
            mono[i] += ch[i] / numChannels;
        }
    }

    // Codificar con lamejs (mono, sampleRate original, 64 kbps)
    var encoder   = new lamejs.Mp3Encoder(1, sampleRate, 64);
    var mp3Chunks = [];
    var blockSize = 1152;
    var total     = mono.length;

    for (var i = 0; i < total; i += blockSize) {
        var chunk  = mono.subarray(i, i + blockSize);
        var int16  = new Int16Array(chunk.length);
        for (var j = 0; j < chunk.length; j++) {
            int16[j] = Math.max(-32768, Math.min(32767, Math.round(chunk[j] * 32767)));
        }
        var buf = encoder.encodeBuffer(int16);
        if (buf.length > 0) mp3Chunks.push(new Uint8Array(buf));

        // Ceder al browser cada ~5 segundos de audio procesado
        if (i % (blockSize * Math.round(sampleRate / blockSize * 5)) === 0) {
            var pct = Math.round((i / total) * 100);
            $('#progreso_texto').text('Comprimiendo… ' + pct + '%');
            await new Promise(function (r) { setTimeout(r, 0); });
        }
    }

    var end = encoder.flush();
    if (end.length > 0) mp3Chunks.push(new Uint8Array(end));

    $('#progreso_texto').text('Subiendo archivo comprimido…');
    return new Blob(mp3Chunks, { type: 'audio/mpeg' });
}

/* ---- Helpers ---- */
function fmt(s) {
    s = Math.floor(s);
    var h = Math.floor(s / 3600), m = Math.floor((s % 3600) / 60), sec = s % 60;
    if (h > 0) return h + ':' + p(m) + ':' + p(sec);
    return p(m) + ':' + p(sec);
}
function p(n) { return n < 10 ? '0' + n : '' + n; }
