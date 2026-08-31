<?php
ob_start();
session_start();

if (!isset($_SESSION["nombre"])) {
    header("Location: login.php");
} else {
    require 'header.php';
    if ($_SESSION['administrador'] == 1) {
?>

<style>
  .enc-card { border-radius:14px; box-shadow:0 2px 16px rgba(0,0,0,.08); border:none; }
  .enc-btn-new { background:#042C49; color:#fff; border:none; padding:10px 22px; border-radius:10px; font-size:14px; font-weight:600; cursor:pointer; }
  .enc-btn-new:hover { opacity:.85; }

  /* ── Builder ── */
  .qb-pregunta { border:1px solid #dee2e6; border-radius:10px; margin-bottom:14px; background:#fff; }
  .qb-pregunta .card-header { background:#f8f9fa; border-radius:10px 10px 0 0; padding:10px 14px; display:flex; align-items:center; justify-content:space-between; }
  .qb-tipo { border-radius:6px; font-size:13px; padding:4px 8px; }
  .qb-texto { border-radius:8px; font-size:14px; }
  .qb-opcion-row { display:flex; align-items:center; gap:6px; margin-bottom:6px; }
  .qb-opcion-input { flex:1; border-radius:6px; border:1px solid #ced4da; padding:5px 10px; font-size:13px; }
  .qb-remove-opcion { background:#dc3545; color:#fff; border:none; border-radius:5px; padding:3px 8px; cursor:pointer; font-size:12px; }
  .qb-add-opcion { font-size:12px; padding:4px 12px; border-radius:6px; }
  .qb-preview-hint { font-size:12px; color:#6c757d; margin-top:6px; }
  .qb-img-preview { width:54px; height:54px; object-fit:cover; border-radius:6px; border:1px solid #dee2e6; display:none; }
  .qb-requerida-lbl { font-size:12px; color:#495057; margin:0; cursor:pointer; }

  /* ── Métricas ── */
  .met-card { border:1px solid #e9ecef; border-radius:10px; padding:16px; margin-bottom:14px; background:#fff; }
  .met-titulo { font-size:14px; font-weight:700; color:#042C49; margin-bottom:10px; }
  .met-texto-list { max-height:160px; overflow-y:auto; background:#f8f9fa; border-radius:8px; padding:10px; }
  .met-texto-item { padding:4px 0; border-bottom:1px solid #e9ecef; font-size:13px; }
  .met-texto-item:last-child { border-bottom:none; }
  .met-stat-big { font-size:42px; font-weight:800; color:#042C49; line-height:1; }
  .met-stars { color:#f5a623; font-size:20px; }
  .met-historial { background:#fff8e6; border:1px solid #f5d98e; border-radius:8px; padding:10px 12px; margin-bottom:12px; }
  .met-historial-version { margin-bottom:8px; }
  .met-historial-version:last-child { margin-bottom:0; }
  .met-historial-tag { font-size:12px; font-weight:700; color:#8a6d1f; margin-bottom:4px; }
  .met-historial-fila { font-size:13px; color:#555; padding:2px 0 2px 20px; }
</style>

<div class="main-panel">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
        <div class="card enc-card">
          <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-4">
              <h4 class="card-title mb-0" style="color:#042C49;">&#128203; Encuestas</h4>
              <button class="enc-btn-new" onclick="nueva_encuesta();">+ Nueva encuesta</button>
            </div>

            <div class="table-responsive">
              <table class="table table-striped" style="min-width:700px;">
                <thead>
                  <tr>
                    <th>Título</th>
                    <th style="text-align:center;">Preguntas</th>
                    <th style="text-align:center;">Respuestas</th>
                    <th>Vigencia</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                  </tr>
                </thead>
                <tbody id="tabla_encuestas">
                  <tr><td colspan="6" style="text-align:center;color:#aaa;padding:30px;">Cargando...</td></tr>
                </tbody>
              </table>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div><!-- content-wrapper ends -->

<!-- ══════════════════════════════════════════════
     MODAL: Crear / Editar encuesta
══════════════════════════════════════════════ -->
<div class="modal fade" id="modalEncuesta" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background:#042C49;">
        <h5 class="modal-title text-white" id="modalEncuestaTitulo">Nueva encuesta</h5>
        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body" style="background:#f4f6f8;">
        <input type="hidden" id="enc_id">

        <!-- Info general -->
        <div class="card enc-card mb-3">
          <div class="card-body">
            <h6 style="color:#042C49;font-weight:700;margin-bottom:14px;">Información general</h6>
            <div class="row">
              <div class="col-md-8">
                <div class="form-group">
                  <label style="font-size:13px;font-weight:600;">Título <span style="color:red">*</span></label>
                  <input type="text" class="form-control" id="enc_titulo" placeholder="Nombre de la encuesta">
                </div>
                <div class="form-group">
                  <label style="font-size:13px;font-weight:600;">Descripción</label>
                  <textarea class="form-control" id="enc_descripcion" rows="2" placeholder="Descripción opcional..."></textarea>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label style="font-size:13px;font-weight:600;">Fecha de inicio</label>
                  <input type="date" class="form-control" id="enc_fecha_inicio">
                </div>
                <div class="form-group">
                  <label style="font-size:13px;font-weight:600;">Fecha de fin</label>
                  <input type="date" class="form-control" id="enc_fecha_fin">
                </div>
                <div class="form-group">
                  <label style="font-size:13px;font-weight:600;">Imagen de portada</label>
                  <input type="file" class="form-control-file" id="enc_img_file" accept="image/*" style="font-size:13px;">
                  <div class="mt-2 d-flex align-items-center" style="gap:8px;">
                    <img id="enc_img_preview" src="" alt="preview"
                      style="display:none;width:80px;height:56px;object-fit:cover;border-radius:6px;border:1px solid #dee2e6;">
                    <button type="button" id="enc_img_clear"
                      style="display:none;background:#dc3545;color:#fff;border:none;border-radius:6px;padding:3px 10px;font-size:12px;cursor:pointer;">
                      Quitar
                    </button>
                  </div>
                  <input type="hidden" id="enc_imagen_base64">
                </div>
                <div class="form-group">
                  <label style="font-size:13px;font-weight:600;">Imagen secundaria <span style="font-size:11px;font-weight:400;color:#6c757d;">(logo / icono circular)</span></label>
                  <input type="file" class="form-control-file" id="enc_img2_file" accept="image/*" style="font-size:13px;">
                  <div class="mt-2 d-flex align-items-center" style="gap:8px;">
                    <img id="enc_img2_preview" src="" alt="preview"
                      style="display:none;width:48px;height:48px;object-fit:cover;border-radius:50%;border:2px solid #dee2e6;">
                    <button type="button" id="enc_img2_clear"
                      style="display:none;background:#dc3545;color:#fff;border:none;border-radius:6px;padding:3px 10px;font-size:12px;cursor:pointer;">
                      Quitar
                    </button>
                  </div>
                  <input type="hidden" id="enc_imagen_secundaria_base64">
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Builder de preguntas -->
        <div class="d-flex justify-content-between align-items-center mb-2">
          <h6 style="color:#042C49;font-weight:700;margin:0;">Preguntas</h6>
          <button class="btn btn-sm btn-outline-primary" onclick="agregar_pregunta();">+ Agregar pregunta</button>
        </div>
        <div id="qb_lista"></div>
        <div id="qb_vacio" class="text-center text-muted py-4" style="border:2px dashed #dee2e6;border-radius:10px;">
          <p class="mb-1" style="font-size:16px;">&#128203;</p>
          <p style="font-size:13px;">Agrega al menos una pregunta para continuar</p>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" onclick="guardar_encuesta();" style="background:#042C49;border-color:#042C49;">Guardar encuesta</button>
      </div>
    </div>
  </div>
</div>

<!-- ══════════════════════════════════════════════
     MODAL: Métricas
══════════════════════════════════════════════ -->
<div class="modal fade" id="modalMetricas" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background:#042C49;">
        <h5 class="modal-title text-white">📊 Métricas de la encuesta</h5>
        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body" style="background:#f4f6f8;">
        <div id="metricas_contenido">
          <div class="text-center py-4 text-muted">Cargando...</div>
        </div>
        <div id="tabla_respuestas_wrap"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- ══════════════════════════════════════════════
     MODAL: Compartir
══════════════════════════════════════════════ -->
<div class="modal fade" id="modalCompartir" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background:#28a745;">
        <h5 class="modal-title text-white">🔗 Compartir encuesta</h5>
        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="comp_id">
        <p style="font-size:13px;color:#555;">Activa el acceso público para que cualquier persona pueda responder la encuesta con un enlace.</p>

        <div class="d-flex align-items-center mb-3">
          <span style="font-size:14px;font-weight:600;margin-right:12px;">Acceso público</span>
          <label class="switch-toggle mb-0">
            <input type="checkbox" id="comp_toggle">
            <span class="slider-toggle"></span>
          </label>
        </div>

        <div id="comp_link_area" style="display:none;">
          <label style="font-size:13px;font-weight:600;">Enlace para compartir</label>
          <div class="input-group">
            <input type="text" class="form-control" id="comp_link" readonly style="font-size:12px;">
            <div class="input-group-append">
              <button class="btn btn-outline-secondary" onclick="copiar_link();" style="font-size:12px;">Copiar</button>
            </div>
          </div>
          <p id="comp_copiado" style="display:none;color:#28a745;font-size:12px;margin-top:4px;">✓ ¡Enlace copiado!</p>
        </div>
        <div id="comp_privado_msg" style="color:#6c757d;font-size:13px;">
          La encuesta está en modo privado. Solo administradores pueden verla.
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<style>
.switch-toggle { position:relative; display:inline-block; width:46px; height:24px; }
.switch-toggle input { opacity:0; width:0; height:0; }
.slider-toggle { position:absolute; cursor:pointer; top:0; left:0; right:0; bottom:0; background:#ccc; border-radius:24px; transition:.3s; }
.slider-toggle:before { position:absolute; content:""; height:18px; width:18px; left:3px; bottom:3px; background:#fff; border-radius:50%; transition:.3s; }
.switch-toggle input:checked + .slider-toggle { background:#28a745; }
.switch-toggle input:checked + .slider-toggle:before { transform:translateX(22px); }
</style>

<!-- Chart.js + SheetJS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="scripts/encuestas.js?v=<?php echo rand(); ?>"></script>

<?php
        require "footer.php";
    }
?>
<?php
}
ob_end_flush();
?>
