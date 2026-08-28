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
  .qr-panel-card { border-radius: 14px; box-shadow: 0 2px 16px rgba(0,0,0,.08); border: none; }
  .qr-preview-wrap {
    display: flex; align-items: center; justify-content: center;
    min-height: 340px; background: #f8f9fa; border-radius: 12px;
    border: 2px dashed #dee2e6;
  }
  .qr-preview-inner {
    padding: 18px; background: #fff; border-radius: 14px;
    box-shadow: 0 6px 32px rgba(0,0,0,.13);
    display: inline-block;
  }
  #qr_preview canvas { display: block; }
  .qr-opt-label { font-size: 13px; font-weight: 600; color: #495057; margin-bottom: 4px; }
  .qr-color-row { display: flex; gap: 16px; align-items: center; }
  .qr-color-group { flex: 1; }
  .qr-color-input { width: 100%; height: 42px; border-radius: 8px; border: 1px solid #ced4da; padding: 3px 6px; cursor: pointer; }
  .qr-range { width: 100%; accent-color: #042C49; }
  .qr-badge-dots { display: flex; gap: 8px; flex-wrap: wrap; margin-top: 4px; }
  .qr-dot-btn {
    padding: 6px 12px; border: 2px solid #dee2e6; border-radius: 20px;
    font-size: 12px; cursor: pointer; background: #fff; transition: all .18s;
    white-space: nowrap;
  }
  .qr-dot-btn.active { border-color: #042C49; background: #042C49; color: #fff; }
  .qr-action-btn {
    padding: 11px 26px; border: none; border-radius: 10px; font-size: 14px;
    font-weight: 600; cursor: pointer; transition: opacity .18s;
  }
  .qr-action-btn:hover { opacity: .85; }
  .qr-btn-download { background: #6c757d; color: #fff; }
  .qr-btn-save    { background: #042C49; color: #fff; }
  .qr-section-title { font-size: 13px; font-weight: 700; color: #042C49; text-transform: uppercase; letter-spacing: .5px; margin: 18px 0 8px; }
  .logo-preview { width: 48px; height: 48px; object-fit: contain; border-radius: 8px; border: 1px solid #dee2e6; display: none; vertical-align: middle; }
  .qr-table-img { width: 56px; height: 56px; object-fit: contain; border-radius: 6px; border: 1px solid #ddd; background: #fff; }
  .crop-viewport {
    width: 280px; height: 280px; overflow: hidden; position: relative;
    background: #f1f3f5; border-radius: 10px; border: 2px dashed #042C49;
    cursor: move; touch-action: none;
  }
  .crop-viewport img { position: absolute; top: 0; left: 0; transform-origin: 0 0; user-select: none; pointer-events: none; }
</style>

<div class="main-panel">
  <div class="content-wrapper">

    <!-- ===== Generador ===== -->
    <div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
        <div class="card qr-panel-card">
          <div class="card-body">
            <h4 class="card-title mb-4" style="color:#042C49;">&#9635; Generador de Códigos QR</h4>

            <div class="row">

              <!-- ─── Columna izquierda: formulario ─── -->
              <div class="col-md-6">

                <!-- Nombre -->
                <div class="form-group">
                  <label class="qr-opt-label">Nombre del QR <span style="color:red">*</span></label>
                  <input type="text" class="form-control" id="qr_nombre" placeholder="Ej: Enlace predicación dominical">
                </div>

                <!-- Contenido -->
                <div class="form-group">
                  <label class="qr-opt-label">Contenido / URL <span style="color:red">*</span></label>
                  <textarea class="form-control" id="qr_contenido" rows="3" placeholder="https://..."></textarea>
                </div>

                <!-- Colores -->
                <div class="qr-section-title">Colores</div>
                <div class="qr-color-row">
                  <div class="qr-color-group">
                    <label class="qr-opt-label">Puntos</label>
                    <input type="color" class="qr-color-input" id="qr_color_dots" value="#042C49">
                  </div>
                  <div class="qr-color-group">
                    <label class="qr-opt-label">Fondo</label>
                    <input type="color" class="qr-color-input" id="qr_color_bg" value="#ffffff">
                  </div>
                </div>

                <!-- Estilo puntos -->
                <div class="qr-section-title">Estilo de puntos</div>
                <div class="qr-badge-dots" id="dots_style_group">
                  <button class="qr-dot-btn" data-val="square">Cuadrado</button>
                  <button class="qr-dot-btn active" data-val="rounded">Redondeado</button>
                  <button class="qr-dot-btn" data-val="dots">Círculos</button>
                  <button class="qr-dot-btn" data-val="classy">Elegante</button>
                  <button class="qr-dot-btn" data-val="classy-rounded">Eleg. redondo</button>
                  <button class="qr-dot-btn" data-val="extra-rounded">Extra redondo</button>
                </div>
                <input type="hidden" id="qr_dots_style" value="rounded">

                <!-- Estilo esquinas -->
                <div class="qr-section-title">Estilo de esquinas</div>
                <div class="qr-badge-dots" id="corners_style_group">
                  <button class="qr-dot-btn" data-val="square">Cuadrado</button>
                  <button class="qr-dot-btn active" data-val="dot">Punto</button>
                  <button class="qr-dot-btn" data-val="extra-rounded">Redondeado</button>
                </div>
                <input type="hidden" id="qr_corners_style" value="dot">

                <!-- Corrección de error -->
                <div class="qr-section-title">Nivel de corrección de error</div>
                <div class="form-group">
                  <select class="form-control" id="qr_error_correction">
                    <option value="L">L — Bajo (7%) — QR más simple</option>
                    <option value="M" selected>M — Medio (15%)</option>
                    <option value="Q">Q — Cuartil (25%) — recomendado con logo</option>
                    <option value="H">H — Alto (30%) — mayor tolerancia</option>
                  </select>
                </div>

                <!-- Tamaño -->
                <div class="form-group">
                  <label class="qr-opt-label">Tamaño: <span id="qr_size_val">300</span> px</label>
                  <input type="range" class="qr-range" id="qr_size" min="200" max="600" value="300" step="50">
                </div>

                <!-- Margen -->
                <div class="form-group">
                  <label class="qr-opt-label">Margen: <span id="qr_margin_val">10</span> px</label>
                  <input type="range" class="qr-range" id="qr_margin" min="0" max="60" value="10" step="2">
                </div>

                <!-- Logo -->
                <div class="form-group">
                  <label class="qr-opt-label">Logo central (opcional)</label>
                  <div style="display:flex;align-items:center;gap:10px;">
                    <input type="file" class="form-control-file" id="qr_logo" accept="image/*" style="flex:1;">
                    <img id="qr_logo_preview" class="logo-preview" src="" alt="logo">
                    <button type="button" id="qr_logo_edit" style="display:none;background:#042C49;color:#fff;border:none;border-radius:6px;padding:4px 10px;font-size:12px;cursor:pointer;" title="Ajustar recorte">✎</button>
                    <button type="button" id="qr_logo_clear" style="display:none;background:#dc3545;color:#fff;border:none;border-radius:6px;padding:4px 10px;font-size:12px;cursor:pointer;">✕</button>
                  </div>
                  <p style="font-size:11px;color:#aaa;margin-top:6px;margin-bottom:0;">Al subir la imagen podrás recortar la parte que se mostrará en el centro del QR.</p>
                  <div id="qr_logo_size_wrap" style="display:none;margin-top:12px;">
                    <label class="qr-opt-label">Tamaño del logo: <span id="qr_logo_size_val">30</span>%</label>
                    <input type="range" class="qr-range" id="qr_logo_size" min="10" max="50" value="30" step="5">
                    <p style="font-size:11px;color:#aaa;margin-top:4px;margin-bottom:0;">Límite según el nivel de corrección de error actual: <span id="qr_logo_size_max_label">50</span>%. Sube el nivel a "Q" o "H" para permitir un logo más grande sin afectar el escaneo.</p>
                  </div>
                </div>

                <!-- Botones -->
                <div style="display:flex;gap:12px;margin-top:24px;align-items:center;flex-wrap:wrap;">
                  <button class="qr-action-btn qr-btn-download" onclick="descargar_qr();">&#11015; Descargar</button>
                  <button class="qr-action-btn qr-btn-save" id="qr_btn_guardar" onclick="guardar_qr();">&#128190; Guardar</button>
                  <span id="qr_editando_msg" style="display:none;font-size:13px;color:#042C49;">
                    Editando <b id="qr_editando_nombre"></b> —
                    <a href="#" onclick="cancelar_edicion_qr();return false;">Cancelar</a>
                  </span>
                </div>

              </div><!-- /col izquierda -->

              <!-- ─── Columna derecha: preview ─── -->
              <div class="col-md-6 d-flex flex-column align-items-center" style="padding-top:8px;">
                <p style="font-size:13px;color:#888;margin-bottom:12px;">Vista previa en tiempo real</p>
                <div class="qr-preview-wrap" style="width:100%;">
                  <div class="qr-preview-inner">
                    <div id="qr_preview"></div>
                  </div>
                </div>
                <p style="font-size:11px;color:#aaa;margin-top:10px;">El QR se actualiza mientras editas</p>
              </div>

            </div><!-- /row interno -->
          </div>
        </div>
      </div>
    </div>

    <!-- ===== Historial ===== -->
    <div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
        <div class="card qr-panel-card">
          <div class="card-body">
            <h4 class="card-title" style="color:#042C49;">Códigos QR guardados</h4>
            <div class="table-responsive" style="max-height:420px;overflow:auto;">
              <table class="table table-striped" style="min-width:600px;">
                <thead>
                  <tr>
                    <th style="width:70px;">QR</th>
                    <th>Nombre</th>
                    <th>Contenido</th>
                    <th style="white-space:nowrap;">Fecha</th>
                    <th>Acciones</th>
                  </tr>
                </thead>
                <tbody id="tabla_qr_codes">
                  <tr><td colspan="5" style="text-align:center;color:#aaa;padding:30px;">Cargando...</td></tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ===== Modal recorte de logo ===== -->
    <div class="modal fade" id="modal_crop_logo" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius:14px;">
          <div class="modal-header">
            <h5 class="modal-title" style="color:#042C49;">Ajustar imagen del logo</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          </div>
          <div class="modal-body" style="display:flex;flex-direction:column;align-items:center;">
            <div class="crop-viewport" id="crop_viewport">
              <img id="crop_image" src="" alt="logo">
            </div>
            <div style="width:280px;margin-top:16px;">
              <label class="qr-opt-label">Zoom</label>
              <input type="range" class="qr-range" id="crop_zoom" min="1" max="4" step="0.01" value="1">
            </div>
            <p style="font-size:12px;color:#888;margin-top:10px;text-align:center;">Arrastra la imagen para posicionarla y usa el zoom para acercar la parte que quieres mostrar en el centro del QR.</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="qr-action-btn" style="background:#e9ecef;color:#333;" data-dismiss="modal">Cancelar</button>
            <button type="button" class="qr-action-btn qr-btn-save" id="btn_aplicar_crop">Aplicar recorte</button>
          </div>
        </div>
      </div>
    </div>

  </div><!-- content-wrapper ends -->

<script src="https://cdn.jsdelivr.net/npm/qr-code-styling@1.6.0-rc.1/lib/qr-code-styling.js"></script>
<script src="scripts/codigos_qr.js?v=<?php echo rand(); ?>"></script>

<?php
        require "footer.php";
    }
?>
<?php
}
ob_end_flush();
?>
