<?php
ob_start();
session_start();

if (!isset($_SESSION["nombre"])) {
    header("Location: login.php");
} else {
    require 'header.php';
    if ($_SESSION['administrador'] == 1) {
?>

<div class="main-panel">
  <div class="content-wrapper">

    <!-- ── Formulario ── -->
    <div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <h4 class="card-title mb-0" id="mbv_form_titulo">Nuevo Anuncio</h4>
              <div style="display:flex;gap:8px;">
                <b id="btn_cancelar_mbv" onclick="cancelar_mbv();" style="display:none;padding:8px 18px;background:#888;color:#fff;cursor:pointer;border-radius:8px;font-size:13px;">Cancelar</b>
                <a href="../index.php" target="_blank" style="padding:8px 16px;background:#28a745;color:#fff;border-radius:8px;font-size:13px;text-decoration:none;"><i class="typcn typcn-eye"></i> Ver sitio</a>
              </div>
            </div>

            <input type="hidden" id="mbv_id" value="0">

            <!-- Datos básicos -->
            <div class="row">
              <div class="col-lg-4">
                <div class="form-group">
                  <label>Nombre del anuncio <span style="color:red;">*</span></label>
                  <input type="text" class="form-control" id="nombre" placeholder="Ej. Navidad 2025, Evangelismo…">
                </div>
              </div>
              <div class="col-lg-4">
                <div class="form-group">
                  <label>Título <span style="color:red;">*</span></label>
                  <input type="text" class="form-control" id="titulo" placeholder="Ej. ¡Bienvenido!">
                </div>
              </div>
              <div class="col-lg-4">
                <div class="form-group">
                  <label>Mensaje <span style="color:red;">*</span></label>
                  <textarea class="form-control" id="mensaje" rows="2" placeholder="Texto que aparece debajo del título."></textarea>
                </div>
              </div>
            </div>

            <hr>

            <!-- Tipo de modal -->
            <div class="form-group">
              <label><b>Tipo de modal</b></label>
              <div style="display:flex;gap:24px;margin-top:8px;">
                <label style="cursor:pointer;display:flex;align-items:center;gap:8px;margin:0;font-size:14px;">
                  <input type="radio" name="tipo_modal" value="0" id="radio_directo" checked onchange="toggle_tipo_modal();">
                  <span>Contenido directo <small style="color:#888;">(sin selector)</small></span>
                </label>
                <label style="cursor:pointer;display:flex;align-items:center;gap:8px;margin:0;font-size:14px;">
                  <input type="radio" name="tipo_modal" value="1" id="radio_selector" onchange="toggle_tipo_modal();">
                  <span>Con selector de opciones <small style="color:#888;">(ej. idiomas)</small></span>
                </label>
              </div>
            </div>

            <!-- ── Sección A: Contenido directo ── -->
            <div id="seccion_directo">
              <div class="row align-items-end">
                <div class="col-lg-3">
                  <div class="form-group mb-0">
                    <label>Tipo de contenido</label>
                    <select class="form-control" id="tipo_directo" onchange="toggle_url_directo();">
                      <option value="">Solo texto / mensaje</option>
                      <option value="video">📹 Video</option>
                      <option value="imagen">🖼️ Imagen</option>
                    </select>
                  </div>
                </div>
                <div class="col-lg-9" id="url_directo_wrap" style="display:none;">
                  <div class="form-group mb-0">
                    <label>URL del contenido</label>
                    <input type="text" class="form-control" id="url_directo" placeholder="https://youtube.com/watch?v=… o URL de imagen…" onblur="mostrar_preview_directo();">
                  </div>
                </div>
              </div>
              <div id="prev_directo" style="display:none;margin-top:10px;border-radius:8px;overflow:hidden;position:relative;width:260px;height:146px;background:#000;"></div>
            </div>

            <!-- ── Sección B: Selector de opciones ── -->
            <div id="seccion_selector" style="display:none;">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <label style="margin:0;font-weight:600;">Opciones del selector</label>
                <b onclick="agregar_opcion();" style="cursor:pointer;padding:7px 16px;background:#042C49;color:#fff;border-radius:8px;font-size:13px;">+ Agregar opción</b>
              </div>
              <div id="opciones_container"></div>
              <p id="msg_sin_opciones" style="color:#aaa;font-size:13px;text-align:center;padding:16px 0;">Agrega al menos una opción con el botón de arriba.</p>
            </div>

            <hr style="margin-top:20px;">
            <div class="d-flex justify-content-between align-items-center flex-wrap" style="gap:12px;">
              <label style="display:flex;align-items:center;gap:10px;cursor:pointer;margin:0;">
                <input type="checkbox" id="habilitado" style="width:18px;height:18px;cursor:pointer;">
                <span style="font-size:14px;">Activar al guardar</span>
                <span id="lbl_habilitado" style="padding:3px 12px;border-radius:20px;font-size:12px;background:#ccc;color:#555;font-weight:600;">Inactivo</span>
                <small style="color:#aaa;">(desactiva el anuncio actual)</small>
              </label>
              <b id="btn_guardar_mbv" onclick="guardar_mbv();" style="padding:10px 26px;background:#042C49;color:#fff;cursor:pointer;border-radius:10px;display:inline-block;">Guardar anuncio</b>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ── Tabla histórico ── -->
    <div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Histórico de anuncios</h4>
            <p style="font-size:12px;color:#888;margin-bottom:12px;">Solo un anuncio puede estar <b>Activo</b> a la vez. El anuncio activo es el que aparece en el sitio.</p>
            <div class="table-responsive">
              <table class="table table-striped" style="font-size:13px;">
                <thead>
                  <tr><th>#</th><th>Nombre</th><th>Título</th><th>Contenido</th><th>Estado</th><th>Acciones</th></tr>
                </thead>
                <tbody id="tabla_mbv"></tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>

  <!-- Template de opción (oculto, clonado por JS) -->
  <template id="tpl_opcion">
    <div class="opt_block card mb-2" style="border:1px solid #dde6f5;">
      <div class="card-body py-2">
        <div class="d-flex align-items-center" style="gap:8px;flex-wrap:wrap;">
          <input type="text" class="form-control opt_emoji" style="width:58px;text-align:center;" placeholder="🌐" title="Emoji (opcional)">
          <input type="text" class="form-control opt_label" style="flex:1;min-width:120px;" placeholder="Etiqueta (ej. Español)">
          <select class="form-control opt_tipo" style="width:140px;" onchange="toggle_opt_url(this);">
            <option value="video">📹 Video</option>
            <option value="imagen">🖼️ Imagen</option>
            <option value="texto">📝 Solo texto</option>
          </select>
          <button onclick="eliminar_opcion(this);" style="background:rgb(129,2,2);border:none;color:#fff;width:30px;height:30px;border-radius:6px;cursor:pointer;flex-shrink:0;" title="Eliminar opción">✕</button>
        </div>
        <div class="opt_url_wrap" style="margin-top:8px;">
          <input type="text" class="form-control opt_url" placeholder="URL del video o imagen…" onblur="mostrar_preview_opt(this);">
          <div class="opt_prev" style="display:none;margin-top:8px;border-radius:8px;overflow:hidden;position:relative;width:220px;height:124px;background:#000;"></div>
        </div>
      </div>
    </div>
  </template>

  <script type="text/javascript" src="scripts/modal_bienvenida.js?v=<?php echo rand(); ?>"></script>
  <script src="js/dashboard.js"></script>
<?php
    require "footer.php";
?>
<?php
    } else {
        require 'noacceso.php';
    }
}
ob_end_flush();
?>
