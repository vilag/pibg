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

        <!-- Formulario Nuevo/Editar Sermón -->
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
                            <h4 class="card-title mb-0" id="titulo_form">Nueva Predicación</h4>
                            <div>
                                <b id="btn_categorias" onclick="abrir_modal_categorias();" style="padding:8px 16px;background-color:#6c757d;color:#fff;cursor:pointer;border-radius:8px;font-size:13px;margin-right:8px;">Gestionar Categorías</b>
                                <a href="../predicaciones.php" target="_blank" style="padding:8px 16px;background-color:#28a745;color:#fff;border-radius:8px;font-size:13px;text-decoration:none;">Ver sitio</a>
                            </div>
                        </div>

                        <input type="hidden" id="idsermones_edit">

                        <div class="row">
                            <div class="col-lg-8">
                                <div class="form-group">
                                    <label>Título del sermón <span style="color:red;">*</span></label>
                                    <input type="text" class="form-control" id="nom_sermon" placeholder="Ej. La Fe que Mueve Montañas">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Fecha <span style="color:red;">*</span></label>
                                    <input type="text" class="form-control" id="fecha_eti" placeholder="Ej. Domingo, 25 de Mayo de 2025">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Predicador <span style="color:red;">*</span></label>
                                    <input type="text" class="form-control" id="predicador" placeholder="Nombre del pastor o predicador">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Tipo de Actividad <span style="color:red;">*</span></label>
                                    <select class="form-control" id="actividad">
                                        <option value="">— Seleccionar —</option>
                                        <option value="Culto Dominical Matutino">Culto Dominical Matutino</option>
                                        <option value="Culto Dominical Vespertino">Culto Dominical Vespertino</option>
                                        <option value="Culto de Oración">Culto de Oración (Miércoles)</option>
                                        <option value="Conferencia">Conferencia</option>
                                        <option value="Retiro">Retiro</option>
                                        <option value="Evangelismo">Evangelismo</option>
                                        <option value="Otra">Otra</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Categoría <span style="color:red;">*</span></label>
                                    <select class="form-control" id="categoria">
                                        <option value="">— Seleccionar —</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Serie especial <small style="color:#888;">(opcional)</small></label>
                                    <select class="form-control" id="serie_id" onchange="toggle_orden_serie();">
                                        <option value="0">— Sin serie —</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2" id="bloque_orden_serie" style="display:none;">
                                <div class="form-group">
                                    <label>Orden en la serie</label>
                                    <input type="number" class="form-control" id="orden_serie" min="1" value="1">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Imagen de portada</label>
                                    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                                        <label for="input_imagen_pred" style="margin:0;padding:9px 18px;background-color:#042C49;color:#fff;border-radius:8px;cursor:pointer;font-size:13px;">
                                            <i class="typcn typcn-upload" style="font-size:15px;vertical-align:middle;margin-right:5px;"></i>Seleccionar
                                        </label>
                                        <input type="file" id="input_imagen_pred" accept="image/jpeg,image/png,image/gif,image/webp" onchange="subir_imagen_pred();" style="display:none;">
                                        <span id="upload_estado_pred" style="font-size:12px;color:#888;">Sin imagen seleccionada</span>
                                    </div>
                                    <input type="hidden" id="ruta_imagen_pred">
                                    <img id="img_preview_pred" src="" alt="" style="display:none;margin-top:8px;width:80px;height:80px;object-fit:cover;border-radius:6px;border:2px solid #042C49;">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Transcripción <small style="color:#888;">(HTML permitido)</small></label>
                            <textarea class="form-control" id="predicacion" rows="14" placeholder="Pega aquí el contenido de la predicación. Puedes incluir etiquetas HTML como &lt;p&gt;, &lt;b&gt;, &lt;i&gt;, etc."></textarea>
                        </div>

                        <div style="text-align:right;margin-top:12px;">
                            <b id="btn_cancelar_pred" onclick="limpiar_form_pred();" style="display:none;padding:10px 22px;background-color:#888;color:#fff;cursor:pointer;border-radius:10px;margin-right:10px;">Cancelar</b>
                            <b id="btn_guardar_pred" onclick="guardar_sermon();" style="padding:10px 22px;background-color:#042C49;color:#fff;cursor:pointer;border-radius:10px;">Guardar predicación</b>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de predicaciones -->
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Predicaciones publicadas</h4>
                        <div class="table-responsive">
                            <table class="table table-striped" style="font-size:13px;">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Imagen</th>
                                        <th>Título</th>
                                        <th>Predicador</th>
                                        <th>Actividad</th>
                                        <th>Fecha</th>
                                        <th>Categoría</th>
                                        <th>Serie</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="tabla_sermones"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Modal: Gestionar Categorías -->
    <div class="modal fade" id="modal_categorias" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Gestionar Categorías</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div style="display:flex;gap:8px;margin-bottom:16px;">
                        <input type="text" class="form-control" id="nueva_categoria" placeholder="Nueva categoría...">
                        <b onclick="guardar_categoria();" style="padding:8px 18px;background-color:#042C49;color:#fff;cursor:pointer;border-radius:8px;white-space:nowrap;">Agregar</b>
                    </div>
                    <ul id="lista_categorias" style="list-style:none;padding:0;margin:0;"></ul>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="scripts/predicaciones.js?v=<?php echo rand(); ?>"></script>
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
