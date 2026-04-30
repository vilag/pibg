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
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-3" id="titulo_form">Nueva Biografía</h4>
                        <input type="hidden" id="idbiografia_edit">

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Nombre <span style="color:red;">*</span></label>
                                    <input type="text" class="form-control" id="nombre" placeholder="Nombre completo">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Cargo / Ministerio</label>
                                    <input type="text" class="form-control" id="cargo" placeholder="Ej. Pastor, Diácono, Maestra...">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-8">
                                <div class="form-group">
                                    <label>Fotografía</label>
                                    <div style="display:flex; align-items:center; gap:14px; flex-wrap:wrap;">
                                        <label for="input_imagen" style="margin:0; padding:10px 20px; background-color:#042C49; color:#fff; border-radius:8px; cursor:pointer; font-size:14px; font-weight:normal; white-space:nowrap;">
                                            <i class="typcn typcn-upload" style="font-size:16px; vertical-align:middle; margin-right:6px;"></i>Seleccionar imagen
                                        </label>
                                        <input type="file" id="input_imagen" accept="image/jpeg,image/png,image/gif,image/webp" onchange="subir_imagen();" style="display:none;">
                                        <span id="upload_estado" style="font-size:13px; color:#888;">Sin imagen seleccionada</span>
                                    </div>
                                    <input type="hidden" id="ruta_imagen">
                                </div>
                            </div>
                            <div class="col-lg-4 d-flex align-items-center justify-content-center" style="padding-bottom:16px;">
                                <img id="img_preview" src="" alt="preview" style="display:none; width:100px; height:100px; object-fit:cover; border-radius:50%; border:3px solid #042C49;">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Biografía</label>
                            <textarea class="form-control" id="biografia" rows="8" placeholder="Escribe la biografía completa..."></textarea>
                        </div>

                        <div style="text-align:right; margin-top:10px;">
                            <b id="btn_cancelar_edicion" onclick="limpiar_form();" style="display:none; padding:12px 24px; background-color:#888; color:#fff; cursor:pointer; border-radius:10px; margin-right:10px;">Cancelar</b>
                            <b id="btn_guardar" onclick="guardar_biografia();" style="padding:12px 24px; background-color:#042C49; color:#fff; cursor:pointer; border-radius:10px;">Guardar</b>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Biografías Publicadas</h4>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Foto</th>
                                        <th>Nombre</th>
                                        <th>Cargo</th>
                                        <th>Fecha</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="tabla_biografias">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- content-wrapper ends -->

    <script type="text/javascript" src="scripts/biografias.js?v=<?php echo rand(); ?>"></script>
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
