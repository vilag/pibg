<?php
ob_start();
session_start();

if (!isset($_SESSION["nombre"])) {
    header("Location: login.php");
} else {
    require 'header.php';
    if ($_SESSION['administrador'] == 1):
?>

<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">

                        <!-- Pantalla de contraseña -->
                        <div id="panel_clave_pet" <?php echo !empty($_SESSION['pet_auth']) ? 'style="display:none;"' : ''; ?>>
                            <div style="max-width:380px;margin:40px auto;text-align:center;">
                                <div style="font-size:3rem;margin-bottom:12px;">🙏</div>
                                <h4 style="color:#042C49;margin-bottom:6px;">Peticiones de Oración</h4>
                                <p style="color:#888;font-size:13px;margin-bottom:24px;">
                                    Esta sección contiene información sensible.<br>
                                    Ingresa la contraseña para continuar.
                                </p>
                                <div style="display:flex;gap:8px;justify-content:center;">
                                    <input type="password" id="input_clave_pet" placeholder="Contraseña"
                                        style="padding:9px 14px;border:1px solid #ced4da;border-radius:8px;font-size:14px;width:200px;">
                                    <button id="btn_acceder_pet" onclick="verificar_clave();"
                                        style="padding:9px 20px;background:#042C49;color:#fff;border:none;border-radius:8px;cursor:pointer;font-size:14px;font-weight:600;">
                                        Acceder
                                    </button>
                                </div>
                                <div id="error_clave_pet" style="display:none;color:#c00;margin-top:10px;font-size:13px;"></div>
                            </div>
                        </div>

                        <!-- Panel principal (visible tras autenticación) -->
                        <div id="panel_peticiones" <?php echo empty($_SESSION['pet_auth']) ? 'style="display:none;"' : ''; ?>>

                            <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
                                <h4 class="card-title mb-0">🙏 Peticiones de Oración</h4>
                                <div style="display:flex;gap:6px;">
                                    <button id="btn_filtro_todas" class="btn-filtro-pet"
                                        onclick="set_filtro('todas');"
                                        style="padding:6px 14px;border-radius:20px;border:none;cursor:pointer;font-size:13px;background:#042C49;color:#fff;font-weight:600;">
                                        Todas
                                    </button>
                                    <button id="btn_filtro_pendientes" class="btn-filtro-pet"
                                        onclick="set_filtro('pendientes');"
                                        style="padding:6px 14px;border-radius:20px;border:none;cursor:pointer;font-size:13px;background:#f0f0f0;color:#333;">
                                        Pendientes
                                    </button>
                                    <button id="btn_filtro_atendidas" class="btn-filtro-pet"
                                        onclick="set_filtro('atendidas');"
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
                                            <th>Teléfono</th>
                                            <th>Motivo</th>
                                            <th>Fecha / Hora</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tabla_peticiones">
                                        <tr><td colspan="6" style="text-align:center;padding:24px;color:#aaa;">Cargando...</td></tr>
                                    </tbody>
                                </table>
                            </div>

                        </div><!-- /panel_peticiones -->

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="scripts/peticiones.js?v=<?php echo rand(); ?>"></script>
    <script>
        <?php if (!empty($_SESSION['pet_auth'])): ?>
        document.addEventListener("DOMContentLoaded", function () { listar_peticiones(); });
        <?php endif; ?>
    </script>
    <script src="js/dashboard.js"></script>
<?php
    require "footer.php";
?>
<?php
    else:
        require 'noacceso.php';
    endif;
}
ob_end_flush();
?>
