<?php
ob_start();
session_start();
if (!isset($_SESSION["nombre"])) { header("Location: login.php"); exit; }
require 'header.php';
if ($_SESSION['administrador'] != 1) { echo '<p>Acceso denegado</p>'; exit; }
?>

<style>
/* ── Estilos del módulo ── */
.si-tabs { display:flex; gap:0; border-bottom:2px solid #e0e0e0; margin-bottom:28px; }
.si-tab  { padding:10px 24px; font-size:13px; font-weight:600; cursor:pointer;
           border-bottom:3px solid transparent; margin-bottom:-2px; color:#888;
           transition:color .2s, border-color .2s; }
.si-tab.active { color:#042C49; border-bottom-color:#042C49; }

.si-panel { display:none; }
.si-panel.active { display:block; }

/* Tarjetas de visibilidad */
.vis-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(260px,1fr)); gap:16px; }
.vis-card { background:#fff; border:1px solid #e0e0e0; border-radius:10px;
            padding:20px; display:flex; align-items:center; gap:16px;
            box-shadow:0 2px 8px rgba(0,0,0,.06); }
.vis-card__ico { width:44px; height:44px; border-radius:8px; background:#f0f4f8;
                 display:flex; align-items:center; justify-content:center;
                 font-size:20px; flex-shrink:0; color:#042C49; }
.vis-card__name { font-size:14px; font-weight:600; color:#333; margin-bottom:2px; }
.vis-card__sub  { font-size:11px; color:#999; }

/* Toggle switch */
.si-toggle { position:relative; display:inline-flex; width:46px; height:26px; flex-shrink:0; margin-left:auto; }
.si-toggle input { opacity:0; width:0; height:0; }
.si-toggle-slider {
    position:absolute; inset:0; border-radius:26px; background:#ccc;
    cursor:pointer; transition:background .2s;
}
.si-toggle-slider::before {
    content:''; position:absolute; width:20px; height:20px; border-radius:50%;
    background:#fff; left:3px; top:3px; transition:transform .2s;
    box-shadow:0 1px 3px rgba(0,0,0,.3);
}
.si-toggle input:checked + .si-toggle-slider { background:#28a745; }
.si-toggle input:checked + .si-toggle-slider::before { transform:translateX(20px); }

/* Lista de secciones custom */
.sec-list { display:flex; flex-direction:column; gap:12px; }
.sec-item {
    background:#fff; border:1px solid #e0e0e0; border-radius:10px;
    padding:16px 20px; display:flex; align-items:center; gap:14px;
    box-shadow:0 1px 4px rgba(0,0,0,.05);
}
.sec-item__drag { color:#bbb; cursor:grab; font-size:18px; flex-shrink:0; }
.sec-item__preview {
    width:56px; height:42px; border-radius:6px; object-fit:cover;
    background:#f0f0f0; flex-shrink:0;
    display:flex; align-items:center; justify-content:center; overflow:hidden;
}
.sec-item__preview img { width:100%; height:100%; object-fit:cover; }
.sec-item__preview-ph { font-size:20px; color:#ccc; }
.sec-item__name  { font-size:14px; font-weight:600; color:#333; }
.sec-item__meta  { font-size:11px; color:#888; margin-top:2px; }
.sec-item__badge {
    font-size:10px; font-weight:700; padding:2px 9px; border-radius:20px;
    flex-shrink:0;
}
.badge-on  { background:#d4edda; color:#155724; }
.badge-off { background:#f0f0f0; color:#888; }
.sec-item__actions { margin-left:auto; display:flex; gap:6px; flex-shrink:0; }
.btn-xs {
    font-size:11px; font-weight:600; padding:5px 12px; border:none;
    border-radius:6px; cursor:pointer; transition:opacity .15s;
}
.btn-xs:hover { opacity:.82; }
.btn-edit    { background:#042C49; color:#fff; }
.btn-toggle-on  { background:#ffc107; color:#333; }
.btn-toggle-off { background:#28a745; color:#fff; }
.btn-del     { background:#dc3545; color:#fff; }

/* Formulario */
.si-form-card { background:#fff; border:1px solid #e0e0e0; border-radius:12px; padding:28px; }
.si-form-title { font-size:16px; font-weight:700; color:#042C49; margin-bottom:20px;
                 padding-bottom:12px; border-bottom:1px solid #eee; }
.si-form-row { display:grid; gap:16px; margin-bottom:16px; }
.si-form-row--2 { grid-template-columns:1fr 1fr; }
.si-form-row--3 { grid-template-columns:1fr 1fr 1fr; }
.si-label { font-size:12px; font-weight:600; color:#555; margin-bottom:5px; display:block; }
.si-input, .si-textarea, .si-select {
    width:100%; border:1px solid #d0d0d0; border-radius:7px; padding:9px 12px;
    font-size:13px; color:#333; outline:none; transition:border-color .2s;
    font-family:inherit;
}
.si-input:focus, .si-textarea:focus, .si-select:focus { border-color:#042C49; }
.si-textarea { resize:vertical; min-height:90px; }

/* Preview de imagen */
.img-preview-wrap { position:relative; border:1px dashed #ccc; border-radius:8px;
                    overflow:hidden; background:#f8f8f8; text-align:center; min-height:110px;
                    display:flex; align-items:center; justify-content:center; }
.img-preview-wrap img { max-height:150px; max-width:100%; display:block; }
.img-preview-ph { color:#bbb; font-size:13px; padding:20px; }

/* Estilos visuales para los estilos de sección */
.estilo-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:12px; margin-bottom:4px; }
.estilo-opt  { border:2px solid #e0e0e0; border-radius:8px; padding:12px 8px; text-align:center;
               cursor:pointer; transition:border-color .2s, background .2s; font-size:11px;
               font-weight:600; color:#555; }
.estilo-opt:hover { border-color:#042C49; }
.estilo-opt.selected { border-color:#042C49; background:#e8f0f8; color:#042C49; }
.estilo-opt .estilo-icon { font-size:22px; margin-bottom:6px; }

/* Fondo color radio */
.fondo-opts { display:flex; gap:16px; }
.fondo-opt  { display:flex; align-items:center; gap:7px; cursor:pointer;
              font-size:13px; font-weight:500; }
.fondo-swatch { width:22px; height:22px; border-radius:4px; border:1px solid rgba(0,0,0,.15); }

/* Subida imagen */
.upload-zone {
    border:2px dashed #d0d0d0; border-radius:8px; padding:18px; text-align:center;
    cursor:pointer; transition:border-color .2s, background .2s; background:#fafafa;
}
.upload-zone:hover { border-color:#042C49; background:#f0f5fb; }
.upload-zone input[type=file] { display:none; }
.upload-zone p { font-size:12px; color:#888; margin:6px 0 0; }
</style>

<div class="main-panel">
  <div class="content-wrapper">

    <!-- Encabezado -->
    <div class="d-flex align-items-center justify-content-between mb-4">
      <div>
        <h4 style="margin:0;color:#042C49;">Secciones de la Página Principal</h4>
        <p style="margin:0;font-size:12px;color:#888;">Controla qué secciones aparecen y crea nuevas secciones personalizadas.</p>
      </div>
      <a href="../index.php" target="_blank" style="padding:8px 16px;background:#28a745;color:#fff;border-radius:8px;font-size:12px;text-decoration:none;">
        <i class="typcn typcn-eye"></i> Ver sitio
      </a>
    </div>

    <!-- Tabs -->
    <div class="si-tabs">
      <div class="si-tab active" onclick="siTab('fijas')">Secciones fijas</div>
      <div class="si-tab" onclick="siTab('custom')">Secciones personalizadas</div>
    </div>

    <!-- ══════════════════════════════════════════════
         TAB 1: SECCIONES FIJAS
    ══════════════════════════════════════════════ -->
    <div class="si-panel active" id="panel-fijas">
      <p style="font-size:13px;color:#666;margin-bottom:20px;">
        Activa o desactiva las secciones que aparecen en la página de inicio. Los cambios se aplican de inmediato.
      </p>
      <div class="vis-grid" id="vis-grid">
        <!-- Se carga con JS -->
        <div style="padding:20px;color:#888;font-size:13px;">Cargando...</div>
      </div>
    </div>

    <!-- ══════════════════════════════════════════════
         TAB 2: SECCIONES PERSONALIZADAS
    ══════════════════════════════════════════════ -->
    <div class="si-panel" id="panel-custom">

      <!-- Formulario -->
      <div class="si-form-card mb-4" id="si-form-wrap">
        <div class="si-form-title" id="si-form-title">Nueva sección personalizada</div>

        <input type="hidden" id="sec-id" value="0">

        <!-- Nombre interno -->
        <div class="si-form-row">
          <div>
            <span class="si-label">Nombre interno <small style="color:#aaa;">(solo visible en el panel)</small></span>
            <input id="sec-nombre" class="si-input" >
          </div>
        </div>

        <!-- Estilo de layout -->
        <div class="si-form-row" style="margin-bottom:20px;">
          <div>
            <span class="si-label">Diseño de la sección</span>
            <div class="estilo-grid">
              <div class="estilo-opt selected" data-v="centrado" onclick="selEstilo(this)">
                <div class="estilo-icon">⬜</div>
                Centrado
              </div>
              <div class="estilo-opt" data-v="split-izq" onclick="selEstilo(this)">
                <div class="estilo-icon">◧</div>
                Imagen izquierda
              </div>
              <div class="estilo-opt" data-v="split-der" onclick="selEstilo(this)">
                <div class="estilo-icon">◨</div>
                Imagen derecha
              </div>
            </div>
            <input type="hidden" id="sec-estilo" value="centrado">
          </div>
        </div>

        <!-- Fondo -->
        <div class="si-form-row" style="margin-bottom:20px;">
          <div>
            <span class="si-label">Color de fondo</span>
            <div class="fondo-opts">
              <label class="fondo-opt">
                <input type="radio" name="sec_fondo" value="1" id="fondo_osc" checked>
                <span class="fondo-swatch" style="background:#0d1b2a;"></span>
                Oscuro (navy)
              </label>
              <label class="fondo-opt">
                <input type="radio" name="sec_fondo" value="0" id="fondo_cla">
                <span class="fondo-swatch" style="background:#f8f7f4;border:1px solid #ddd;"></span>
                Claro (gris cálido)
              </label>
            </div>
          </div>
        </div>

        <!-- Contenido de texto -->
        <div class="si-form-row si-form-row--2">
          <div>
            <span class="si-label">Etiqueta / eyebrow <small style="color:#aaa;">(pequeño texto naranja)</small></span>
            <input id="sec-eyebrow" class="si-input" placeholder="Ej. Ministerio de Música">
          </div>
          <div>
            <span class="si-label">Título</span>
            <input id="sec-titulo" class="si-input" placeholder="Ej. Academia de Música Coré">
          </div>
        </div>

        <div class="si-form-row">
          <div>
            <span class="si-label">Texto / descripción</span>
            <textarea id="sec-texto" class="si-textarea" placeholder="Descripción de la sección…"></textarea>
          </div>
        </div>

        <!-- Botón -->
        <div class="si-form-row si-form-row--2">
          <div>
            <span class="si-label">Texto del botón <small style="color:#aaa;">(deja vacío para omitir)</small></span>
            <input id="sec-btn-texto" class="si-input" placeholder="Ej. Ver más, Únete ahora…">
          </div>
          <div>
            <span class="si-label">URL del botón</span>
            <input id="sec-btn-url" class="si-input" placeholder="Ej. lumbrera.php o https://…">
          </div>
        </div>

        <!-- Imagen -->
        <div class="si-form-row si-form-row--2" id="si-img-row">
          <div>
            <span class="si-label">Imagen — URL directa</span>
            <input id="sec-img-url" class="si-input" placeholder="https://res.cloudinary.com/…"
                   oninput="previewImgUrl(this.value)">
            <p style="font-size:11px;color:#aaa;margin-top:5px;">
              Puedes pegar una URL de Cloudinary, o subir una imagen local abajo.
            </p>
          </div>
          <div>
            <span class="si-label">— o — Subir imagen desde tu equipo</span>
            <div class="upload-zone" onclick="document.getElementById('sec-img-file').click()">
              <input type="file" id="sec-img-file" accept="image/*" onchange="subirImagen(this)">
              <i class="typcn typcn-upload" style="font-size:24px;color:#bbb;"></i>
              <p>Haz clic para seleccionar una imagen<br><small>JPG, PNG, WEBP — máx. 5 MB</small></p>
            </div>
          </div>
        </div>

        <!-- Preview imagen -->
        <div class="si-form-row" id="img-preview-row" style="display:none;">
          <div>
            <span class="si-label">Vista previa de imagen</span>
            <div class="img-preview-wrap">
              <img id="img-preview" src="" alt="preview">
            </div>
            <button type="button" class="btn-xs btn-del" style="margin-top:8px;" onclick="quitarImagen()">
              Quitar imagen
            </button>
          </div>
        </div>

        <!-- Orden -->
        <div class="si-form-row si-form-row--3">
          <div>
            <span class="si-label">Orden de aparición</span>
            <input id="sec-orden" class="si-input" type="number" value="0" min="0">
          </div>
        </div>

        <!-- Acciones del formulario -->
        <div style="display:flex;gap:10px;margin-top:8px;">
          <button class="btn btn-primary btn-sm" onclick="guardarSeccion()">
            <i class="typcn typcn-tick"></i> Guardar sección
          </button>
          <button class="btn btn-secondary btn-sm" onclick="cancelarForm()">
            Cancelar
          </button>
        </div>
      </div>

      <!-- Lista de secciones -->
      <div class="card">
        <div class="card-body">
          <div class="d-flex align-items-center justify-content-between mb-3">
            <h5 class="card-title mb-0">Secciones creadas</h5>
            <span style="font-size:12px;color:#888;">Las secciones activas aparecen en la página de inicio.</span>
          </div>
          <div class="sec-list" id="sec-list">
            <div style="color:#888;font-size:13px;padding:20px;text-align:center;">Cargando…</div>
          </div>
        </div>
      </div>

    </div><!-- /panel-custom -->

  </div><!-- /content-wrapper -->
</div><!-- /main-panel -->

<script>
/* ════════════════════════════════════════════════════════════
   TABS
════════════════════════════════════════════════════════════ */
function siTab(id) {
    document.querySelectorAll('.si-tab').forEach(function(t,i){
        t.classList.toggle('active', (i===0 && id==='fijas')||(i===1 && id==='custom'));
    });
    document.querySelectorAll('.si-panel').forEach(function(p){
        p.classList.toggle('active', p.id==='panel-'+id);
    });
    if (id==='fijas')  cargarVisibilidad();
    if (id==='custom') cargarSecciones();
}

/* ════════════════════════════════════════════════════════════
   TAB 1 — SECCIONES FIJAS
════════════════════════════════════════════════════════════ */
var VIS_ICONS = {
    actividades:      '📅',
    jovenes_lumbrera: '🎵',
    estudio_biblico:  '📖',
    lecturas:         '🌅',
    calendario:       '🗓️',
    oracion:          '🙏'
};

function cargarVisibilidad() {
    $.post('ajax/secciones_index.php?op=listar_vis', {}, function(data){
        var arr;
        try { arr = typeof data==='string' ? JSON.parse(data) : data; } catch(e) { arr=[]; }
        var html = '';
        arr.forEach(function(v) {
            var checked = v.activo == 1 ? 'checked' : '';
            html += '<div class="vis-card">' +
                '<div class="vis-card__ico">' + (VIS_ICONS[v.clave]||'🔲') + '</div>' +
                '<div>' +
                  '<div class="vis-card__name">' + esc(v.nombre) + '</div>' +
                  '<div class="vis-card__sub">Clave: ' + esc(v.clave) + '</div>' +
                '</div>' +
                '<label class="si-toggle" title="Activar / desactivar">' +
                  '<input type="checkbox" ' + checked + ' onchange="toggleVis(\'' + v.clave + '\',this.checked)">' +
                  '<span class="si-toggle-slider"></span>' +
                '</label>' +
            '</div>';
        });
        document.getElementById('vis-grid').innerHTML = html || '<p style="color:#888;padding:16px;">No se encontraron secciones.</p>';
    });
}

function toggleVis(clave, activo) {
    $.post('ajax/secciones_index.php?op=toggle_vis', {clave:clave, activo: activo?1:0}, function(data){
        var r = typeof data==='string' ? JSON.parse(data) : data;
        if (!r.ok) alert('Error al actualizar');
    });
}

/* ════════════════════════════════════════════════════════════
   TAB 2 — SECCIONES PERSONALIZADAS
════════════════════════════════════════════════════════════ */
var ESTILO_LABELS = { centrado:'Centrado', 'split-izq':'Img izquierda', 'split-der':'Img derecha' };

function cargarSecciones() {
    $.post('ajax/secciones_index.php?op=listar', {}, function(data){
        var arr;
        try { arr = typeof data==='string' ? JSON.parse(data) : data; } catch(e) { arr=[]; }
        var html = '';
        arr.forEach(function(s) {
            var imgHtml = s.imagen_url
                ? '<img src="../' + esc(s.imagen_url) + '" alt="">'
                : '<span class="sec-item__preview-ph">🖼️</span>';
            var badge = s.activo==1
                ? '<span class="sec-item__badge badge-on">Visible</span>'
                : '<span class="sec-item__badge badge-off">Oculta</span>';
            var btnToggle = s.activo==1
                ? '<button class="btn-xs btn-toggle-on" onclick="toggleSeccion(' + s.id + ',0)">Ocultar</button>'
                : '<button class="btn-xs btn-toggle-off" onclick="toggleSeccion(' + s.id + ',1)">Mostrar</button>';
            html += '<div class="sec-item" id="secitem-' + s.id + '">' +
                '<span class="sec-item__drag typcn typcn-th-menu"></span>' +
                '<div class="sec-item__preview">' + imgHtml + '</div>' +
                '<div>' +
                  '<div class="sec-item__name">' + esc(s.nombre) + '</div>' +
                  '<div class="sec-item__meta">' + esc(ESTILO_LABELS[s.estilo]||s.estilo) +
                    (s.titulo ? ' · ' + esc(s.titulo) : '') +
                    ' · Orden: ' + s.orden + '</div>' +
                '</div>' +
                badge +
                '<div class="sec-item__actions">' +
                  '<button class="btn-xs btn-edit" onclick="editarSeccion(' + s.id + ')">Editar</button>' +
                  btnToggle +
                  '<button class="btn-xs btn-del" onclick="eliminarSeccion(' + s.id + ')">Eliminar</button>' +
                '</div>' +
            '</div>';
        });
        document.getElementById('sec-list').innerHTML = html ||
            '<p style="text-align:center;color:#aaa;padding:30px;">No hay secciones creadas todavía.</p>';
    });
}

/* ── Estilo selector ── */
function selEstilo(el) {
    document.querySelectorAll('.estilo-opt').forEach(function(e){ e.classList.remove('selected'); });
    el.classList.add('selected');
    document.getElementById('sec-estilo').value = el.getAttribute('data-v');
    // Mostrar/ocultar imagen si es centrado sin imagen útil
}

/* ── Preview imagen por URL ── */
function previewImgUrl(url) {
    if (!url) { document.getElementById('img-preview-row').style.display='none'; return; }
    document.getElementById('img-preview').src = url;
    document.getElementById('img-preview-row').style.display='block';
}

/* ── Subir imagen ── */
function subirImagen(input) {
    if (!input.files || !input.files[0]) return;
    var fd = new FormData();
    fd.append('imagen', input.files[0]);
    fetch('ajax/secciones_index.php?op=subir_imagen', { method:'POST', body:fd })
        .then(function(r){ return r.json(); })
        .then(function(data){
            if (data.ok) {
                document.getElementById('sec-img-url').value = data.url;
                previewImgUrl('../' + data.url);
            } else {
                alert('Error: ' + (data.msg || 'no se pudo subir'));
            }
        });
}

function quitarImagen() {
    document.getElementById('sec-img-url').value = '';
    document.getElementById('img-preview-row').style.display = 'none';
}

/* ── Guardar (crear o actualizar) ── */
function guardarSeccion() {
    var nombre = $('#sec-nombre').val().trim();
    if (!nombre) { alert('El nombre interno es obligatorio.'); return; }

    var datos = {
        id:           $('#sec-id').val(),
        nombre:       nombre,
        estilo:       $('#sec-estilo').val(),
        eyebrow:      $('#sec-eyebrow').val().trim(),
        titulo:       $('#sec-titulo').val().trim(),
        texto:        $('#sec-texto').val().trim(),
        imagen_url:   $('#sec-img-url').val().trim(),
        fondo_oscuro: $('input[name=sec_fondo]:checked').val(),
        btn_texto:    $('#sec-btn-texto').val().trim(),
        btn_url:      $('#sec-btn-url').val().trim(),
        orden:        $('#sec-orden').val()
    };

    var opStr = datos.id > 0 ? 'actualizar' : 'crear';
    $.post('ajax/secciones_index.php?op=' + opStr, datos, function(data){
        var r = typeof data==='string' ? JSON.parse(data) : data;
        if (r.ok) {
            resetForm();
            cargarSecciones();
        } else {
            alert('Error al guardar: ' + (r.msg || ''));
        }
    });
}

/* ── Editar ── */
function editarSeccion(id) {
    $.get('ajax/secciones_index.php?op=get_uno&id=' + id, function(data){
        var s = typeof data==='string' ? JSON.parse(data) : data;
        if (!s) { alert('No se encontró la sección'); return; }

        $('#sec-id').val(s.id);
        $('#sec-nombre').val(s.nombre);
        $('#sec-eyebrow').val(s.eyebrow||'');
        $('#sec-titulo').val(s.titulo||'');
        $('#sec-texto').val(s.texto||'');
        $('#sec-img-url').val(s.imagen_url||'');
        $('#sec-btn-texto').val(s.btn_texto||'');
        $('#sec-btn-url').val(s.btn_url||'');
        $('#sec-orden').val(s.orden||0);
        $('input[name=sec_fondo][value=' + (s.fondo_oscuro||1) + ']').prop('checked', true);

        // Estilo
        document.querySelectorAll('.estilo-opt').forEach(function(e){
            e.classList.toggle('selected', e.getAttribute('data-v')===s.estilo);
        });
        $('#sec-estilo').val(s.estilo);

        // Preview imagen
        if (s.imagen_url) {
            document.getElementById('img-preview').src = '../' + s.imagen_url;
            document.getElementById('img-preview-row').style.display = 'block';
        } else {
            document.getElementById('img-preview-row').style.display = 'none';
        }

        document.getElementById('si-form-title').textContent = 'Editando: ' + s.nombre;
        document.querySelector('.si-form-card').scrollIntoView({ behavior:'smooth' });
    });
}

/* ── Toggle ── */
function toggleSeccion(id, activo) {
    $.post('ajax/secciones_index.php?op=toggle_activo', {id:id, activo:activo}, function(data){
        var r = typeof data==='string' ? JSON.parse(data) : data;
        if (r.ok) cargarSecciones();
        else alert('Error');
    });
}

/* ── Eliminar ── */
function eliminarSeccion(id) {
    if (!confirm('¿Eliminar esta sección permanentemente?')) return;
    $.post('ajax/secciones_index.php?op=eliminar', {id:id}, function(data){
        var r = typeof data==='string' ? JSON.parse(data) : data;
        if (r.ok) {
            document.getElementById('secitem-' + id).remove();
        } else alert('Error al eliminar');
    });
}

/* ── Reset formulario ── */
function resetForm() {
    $('#sec-id').val('0');
    $('#sec-nombre, #sec-eyebrow, #sec-titulo, #sec-texto, #sec-img-url, #sec-btn-texto, #sec-btn-url').val('');
    $('#sec-orden').val('0');
    $('#fondo_osc').prop('checked', true);
    document.querySelectorAll('.estilo-opt').forEach(function(e,i){ e.classList.toggle('selected', i===0); });
    $('#sec-estilo').val('centrado');
    document.getElementById('img-preview-row').style.display = 'none';
    document.getElementById('si-form-title').textContent = 'Nueva sección personalizada';
}

function cancelarForm() { resetForm(); }

/* ── Util ── */
function esc(s){ return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); }

/* ── Init ── */
window.onload = function() {
    cargarVisibilidad();
};
</script>

<?php
require 'footer.php';
?>
