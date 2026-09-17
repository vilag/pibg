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
  .push-card { border-radius: 14px; box-shadow: 0 2px 16px rgba(0,0,0,.08); border: none; padding: 26px 28px; background: #fff; }
  .push-conteos { display: flex; gap: 18px; margin-bottom: 24px; flex-wrap: wrap; }
  .push-conteo-item { flex: 1; min-width: 160px; background: #f8f9fa; border-radius: 12px; padding: 16px 20px; text-align: center; }
  .push-conteo-num { font-size: 28px; font-weight: 700; color: #042C49; }
  .push-conteo-label { font-size: 12px; color: #6c757d; text-transform: uppercase; letter-spacing: .4px; }
  .push-btn-enviar { background: #042C49; color: #fff; border: none; border-radius: 10px; padding: 12px 30px; font-weight: 600; cursor: pointer; }
  .push-btn-enviar:hover { opacity: .85; }
  .push-aviso { background: #fff3cd; border: 1px solid #ffe08a; border-radius: 8px; padding: 10px 14px; font-size: 13px; margin-top: 14px; }
  .push-tabla-hist { width: 100%; margin-top: 10px; }
  .push-tabla-hist th, .push-tabla-hist td { padding: 8px 10px; font-size: 13px; border-bottom: 1px solid #eee; }
  .push-badge { display: inline-block; padding: 2px 10px; border-radius: 10px; font-size: 11px; font-weight: 700; }
  .push-badge-activo { background: #d4edda; color: #155724; }
  .push-badge-inactivo { background: #f1f1f1; color: #888; }
  .push-badge-todos { background: #cfe2ff; color: #084298; }
  .push-badge-admin { background: #d4edda; color: #155724; }
  .push-evento-variables { font-size: 12px; color: #6c757d; margin-top: 4px; }
  .push-evento-guardado { color: #155724; font-size: 13px; }
  .push-evento-error { color: #b02a37; font-size: 13px; }
  .push-fila-evento { cursor: pointer; }
  .push-fila-evento:hover { background: #f8f9fa; }
</style>

<div class="content-wrapper">
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">

          <h4 style="margin-bottom: 20px;">Notificaciones Push</h4>

          <div class="push-card" style="margin-bottom: 24px;">
            <h5>¿Cuándo se envía una notificación?</h5>
            <p class="text-muted" style="font-size:13px;">
              Estos avisos se disparan solos cuando pasa el evento. Abre uno para activarlo o
              desactivarlo, decidir a quién le llega y editar su texto. Lo que redactes y envíes
              con el botón "Enviar notificación" (abajo) siempre es manual y va a todos los
              suscriptores; eso no se configura aquí.
            </p>
            <table class="push-tabla-hist">
              <thead>
                <tr><th>Evento</th><th>Estado</th><th>A quién llega</th><th></th></tr>
              </thead>
              <tbody id="push_tabla_eventos">
                <tr><td colspan="4" class="text-center text-muted">Cargando…</td></tr>
              </tbody>
            </table>
          </div>

          <div class="push-card">
            <div class="push-conteos">
              <div class="push-conteo-item">
                <div class="push-conteo-num" id="push_conteo_ios">—</div>
                <div class="push-conteo-label">Suscriptores iOS / Web</div>
              </div>
              <div class="push-conteo-item">
                <div class="push-conteo-num" id="push_conteo_android">—</div>
                <div class="push-conteo-label">Suscriptores Android</div>
              </div>
            </div>

            <div class="form-group">
              <label>Título</label>
              <input type="text" class="form-control" id="push_titulo" maxlength="150" placeholder="Ej. Nuevo anuncio">
            </div>
            <div class="form-group">
              <label>Mensaje</label>
              <textarea class="form-control" id="push_mensaje" rows="3" maxlength="255" placeholder="Escribe el mensaje que verá la gente en la notificación"></textarea>
            </div>
            <div class="form-group">
              <label>Enlace al abrir (opcional)</label>
              <input type="text" class="form-control" id="push_url" placeholder="Ej. /anuncio.php?id=12">
            </div>

            <button type="button" class="push-btn-enviar" onclick="push_enviar_notificacion()">Enviar notificación</button>
            <div id="push_resultado" style="margin-top: 14px;"></div>
          </div>

          <div class="push-card" style="margin-top: 24px;">
            <h5>Avisos para ti como administrador</h5>
            <p class="text-muted" style="font-size:13px;">
              Actívalas en este dispositivo para recibir un aviso al instante cada vez que alguien
              envíe una petición de oración desde el sitio — solo a ti, no se transmite a los
              demás suscriptores.
            </p>
            <button type="button" class="push-btn-enviar" id="push_admin_btn" onclick="push_admin_activar()">Activar en este dispositivo</button>
            <div id="push_admin_resultado" style="margin-top: 14px;"></div>
          </div>

          <div class="push-card" style="margin-top: 24px;">
            <h5>Historial de envíos</h5>
            <table class="push-tabla-hist">
              <thead>
                <tr><th>Fecha</th><th>Título</th><th>Mensaje</th><th>Destinatarios</th><th>Exitosos</th></tr>
              </thead>
              <tbody id="push_tabla_historial">
                <tr><td colspan="5" class="text-center text-muted">Cargando…</td></tr>
              </tbody>
            </table>
          </div>

          <div class="push-card" style="margin-top: 24px;">
            <h5>Reporte de suscripciones</h5>
            <table class="push-tabla-hist">
              <thead>
                <tr><th>Fecha</th><th>Plataforma</th><th>Dispositivo</th><th>Estado</th><th>Admin</th></tr>
              </thead>
              <tbody id="push_tabla_suscripciones">
                <tr><td colspan="5" class="text-center text-muted">Cargando…</td></tr>
              </tbody>
            </table>
          </div>

        </div>
      </div>
    </div>
  </div><!-- content-wrapper ends -->

  <div class="modal fade" id="modalEventoPush" tabindex="-1" role="dialog" aria-labelledby="modalEventoPushLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalEventoPushLabel">Editar aviso</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="evento_clave">
          <div class="form-group">
            <label style="margin:0;"><input type="checkbox" id="evento_activo"> Activo</label>
          </div>
          <div class="form-group">
            <label>¿A quién llega?</label>
            <select class="form-control" id="evento_destino">
              <option value="todos">Todos los suscriptores</option>
              <option value="admin">Cualquier administrador</option>
              <option value="usuario">Un usuario específico del panel</option>
            </select>
            <div id="evento_advertencia" class="push-aviso" style="display:none;"></div>
          </div>
          <div class="form-group" id="evento_usuario_wrap" style="display:none;">
            <label>¿A qué usuario?</label>
            <select class="form-control" id="evento_usuario">
              <option value="">Elige un usuario…</option>
            </select>
            <div class="push-evento-variables">Ese usuario debe activar sus notificaciones en "Mis notificaciones" desde su propio dispositivo.</div>
          </div>
          <div class="form-group">
            <label>Título</label>
            <input type="text" class="form-control" id="evento_titulo" maxlength="150">
          </div>
          <div class="form-group">
            <label>Mensaje</label>
            <textarea class="form-control" id="evento_mensaje" rows="2" maxlength="255"></textarea>
          </div>
          <div class="push-evento-variables" id="evento_variables"></div>
          <div id="evento_resultado" style="margin-top: 10px;"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-primary" id="evento_guardar_btn" onclick="push_evento_guardar()">Guardar cambios</button>
        </div>
      </div>
    </div>
  </div>

<script src="../js/push_cliente.js"></script>
<script src="scripts/push_activador.js?v=<?php echo rand(); ?>"></script>
<script src="scripts/push_notificaciones.js?v=<?php echo rand(); ?>"></script>

<?php
        require "footer.php";
    }
?>
<?php
}
ob_end_flush();
?>
