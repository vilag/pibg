<?php
ob_start();
session_start();

if (!isset($_SESSION["nombre"])) {
    header("Location: login.php");
} else {
    require 'header.php';
?>

<style>
  .push-card { border-radius: 14px; box-shadow: 0 2px 16px rgba(0,0,0,.08); border: none; padding: 26px 28px; background: #fff; max-width: 560px; }
  .push-btn-enviar { background: #042C49; color: #fff; border: none; border-radius: 10px; padding: 12px 30px; font-weight: 600; cursor: pointer; }
  .push-btn-enviar:hover { opacity: .85; }
  .push-aviso { background: #fff3cd; border: 1px solid #ffe08a; border-radius: 8px; padding: 10px 14px; font-size: 13px; margin-top: 14px; }
</style>

<div class="content-wrapper">
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">

          <h4 style="margin-bottom: 20px;">Mis notificaciones</h4>

          <div class="push-card">
            <p class="text-muted" style="font-size:13px;">
              Actívalas en este dispositivo para recibir avisos que alguien del equipo
              te haya asignado específicamente a ti desde "Notificaciones Push" — por
              ejemplo, nuevas solicitudes de un formulario del sitio.
            </p>
            <button type="button" class="push-btn-enviar" id="push_admin_btn" onclick="push_admin_activar()">Activar en este dispositivo</button>
            <div id="push_admin_resultado" style="margin-top: 14px;"></div>
          </div>

        </div>
      </div>
    </div>
  </div><!-- content-wrapper ends -->

<script src="../js/push_cliente.js"></script>
<script src="scripts/push_activador.js?v=<?php echo rand(); ?>"></script>

<?php
    require "footer.php";
}
ob_end_flush();
?>
