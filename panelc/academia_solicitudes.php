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

        <!-- Configuración de correos -->
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-3" style="color:#042C49;">&#9881; Configuración de notificaciones</h4>
                        <p style="color:#888;font-size:13px;">
                            Correos a los que llegará el aviso cada vez que alguien solicite informes de Academia Coré.
                            Puedes escribir varios separados por coma.
                        </p>
                        <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;">
                            <input type="text" id="aca_correos_notif" class="form-control" style="max-width:420px;"
                                placeholder="correo1@ejemplo.com, correo2@ejemplo.com">
                            <button class="btn" style="background:#042C49;color:#fff;" onclick="guardar_correos_academia();">
                                Guardar
                            </button>
                        </div>
                        <div id="aca_config_resultado" style="margin-top:8px;font-size:13px;"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Listado de solicitudes -->
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
                            <h4 class="card-title mb-0" style="color:#042C49;">&#127925; Solicitudes de Informes — Academia Coré</h4>
                            <div style="display:flex;gap:6px;">
                                <button id="btn_filtro_todas" class="btn-filtro-aca"
                                    onclick="set_filtro_academia('todas');"
                                    style="padding:6px 14px;border-radius:20px;border:none;cursor:pointer;font-size:13px;background:#042C49;color:#fff;font-weight:600;">
                                    Todas
                                </button>
                                <button id="btn_filtro_pendientes" class="btn-filtro-aca"
                                    onclick="set_filtro_academia('pendientes');"
                                    style="padding:6px 14px;border-radius:20px;border:none;cursor:pointer;font-size:13px;background:#f0f0f0;color:#333;">
                                    Pendientes
                                </button>
                                <button id="btn_filtro_atendidas" class="btn-filtro-aca"
                                    onclick="set_filtro_academia('atendidas');"
                                    style="padding:6px 14px;border-radius:20px;border:none;cursor:pointer;font-size:13px;background:#f0f0f0;color:#333;">
                                    Atendidas
                                </button>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped" style="font-size:13px;">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Correo</th>
                                        <th>Teléfono</th>
                                        <th>Instrumentos</th>
                                        <th>Fecha</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="tabla_academia_solicitudes">
                                    <tr><td colspan="7" style="text-align:center;padding:24px;color:#aaa;">Cargando...</td></tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="scripts/academia_solicitudes.js?v=<?php echo rand(); ?>"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    listar_academia_solicitudes();
    cargar_correos_academia();
});
</script>

<?php
        require "footer.php";
    } else {
        require 'noacceso.php';
    }
}
ob_end_flush();
?>
