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

        <div class="row">
            <!-- Formulario nueva/editar serie -->
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
                            <h4 class="card-title mb-0" id="titulo_form_serie">Nueva Serie Especial</h4>
                            <a href="../predicaciones.php" target="_blank" style="padding:8px 16px;background-color:#28a745;color:#fff;border-radius:8px;font-size:13px;text-decoration:none;">Ver sitio</a>
                        </div>
                        <input type="hidden" id="idserie_edit">

                        <div class="row">
                            <div class="col-lg-8">
                                <div class="form-group">
                                    <label>Nombre de la serie <span style="color:red;">*</span></label>
                                    <input type="text" class="form-control" id="nombre_serie" placeholder="Ej. Semana de Conferencias sobre Santidad 2025">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Estatus</label>
                                    <select class="form-control" id="estatus_serie">
                                        <option value="1">Activa (visible en el sitio)</option>
                                        <option value="0">Inactiva</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Descripción</label>
                            <textarea class="form-control" id="descripcion_serie" rows="3" placeholder="Breve descripción del tema, propósito o contexto de la serie..."></textarea>
                        </div>

                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label>Fecha inicio</label>
                                    <input type="date" class="form-control" id="fecha_inicio_serie">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label>Fecha fin</label>
                                    <input type="date" class="form-control" id="fecha_fin_serie">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Imagen de portada</label>
                                    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                                        <label for="input_imagen_serie" style="margin:0;padding:9px 18px;background-color:#042C49;color:#fff;border-radius:8px;cursor:pointer;font-size:13px;">
                                            <i class="typcn typcn-upload" style="font-size:15px;vertical-align:middle;margin-right:5px;"></i>Seleccionar
                                        </label>
                                        <input type="file" id="input_imagen_serie" accept="image/jpeg,image/png,image/gif,image/webp" onchange="subir_imagen_serie();" style="display:none;">
                                        <span id="upload_estado_serie" style="font-size:12px;color:#888;">Sin imagen seleccionada</span>
                                    </div>
                                    <input type="hidden" id="ruta_imagen_serie">
                                    <img id="img_preview_serie" src="" alt="" style="display:none;margin-top:8px;width:80px;height:80px;object-fit:cover;border-radius:6px;border:2px solid #042C49;">
                                </div>
                            </div>
                        </div>

                        <div style="text-align:right;margin-top:12px;">
                            <b id="btn_cancelar_serie" onclick="limpiar_form_serie();" style="display:none;padding:10px 22px;background-color:#888;color:#fff;cursor:pointer;border-radius:10px;margin-right:10px;">Cancelar</b>
                            <b id="btn_guardar_serie" onclick="guardar_serie();" style="padding:10px 22px;background-color:#042C49;color:#fff;cursor:pointer;border-radius:10px;">Guardar serie</b>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de series -->
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Series registradas</h4>
                        <div class="table-responsive">
                            <table class="table table-striped" style="font-size:13px;">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Imagen</th>
                                        <th>Nombre</th>
                                        <th>Fechas</th>
                                        <th>Sermones</th>
                                        <th>Estatus</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="tabla_series"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Modal: Sermones de la serie -->
    <div class="modal fade" id="modal_sermones_serie" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal_serie_titulo">Sermones de la serie</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <p style="color:#888;font-size:13px;margin-bottom:10px;">Los sermones se muestran por orden de serie. Para cambiar el orden, edita el sermón desde la sección de Predicaciones.</p>
                    <div class="table-responsive">
                        <table class="table table-striped" style="font-size:13px;">
                            <thead>
                                <tr><th>Orden</th><th>Título</th><th>Predicador</th><th>Fecha</th><th>Actividad</th><th></th></tr>
                            </thead>
                            <tbody id="tabla_sermones_serie"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="scripts/series_pred.js?v=<?php echo rand(); ?>"></script>
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
