<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();

if (!isset($_SESSION["nombre"]))
{
  header("Location: login.php");
}
else
{
require 'header.php';
if ($_SESSION['administrador']==1)
{
?>

<!-- partial -->
<div class="main-panel">
          <div class="content-wrapper">
            
            
            
            <div class="row">
              <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-wrap justify-content-between">
                            <h4 class="card-title mb-3">Registrar tema semanal</h4>
                        </div>
                        <form class="forms-sample" style="padding-top: 20px;">
                            
                            <div class="col-lg-6" style="float: left; height: 100px;">
                              <div class="form-group">
                                <label>Fecha 1 *</label>
                                <input type="date" class="form-control" id="fecha_actividad1" >
                              </div>
                            </div>
                            <div class="col-lg-6" style="float: left; height: 100px;">
                              <div class="form-group">
                                <label>Fecha 2 *</label>
                                <input type="date" class="form-control" id="fecha_actividad2">
                              </div>
                            </div>
                            
                            <div class="col-lg-12" style="float: left; height: 100px;">
                              <div class="form-group">
                                <label>Nombre de Actividad *</label>
                                <input type="text" class="form-control" id="nom_actividad_sem">
                                <!-- <div id="nombre_act_capt" style="display: none; width: 50%; height: 250px; background-color: #fff; position: absolute; z-index: 9999; overflow-y: scroll; text-align: center; border: #ccc 1px solid;">

                                </div> -->
                              </div>
                            </div>
                            <div class="col-lg-12" style="float: left; height: 100px;">
                              <div class="form-group">
                                <label>Nombre corto *</label>
                                <input type="text" class="form-control" id="nom_actividad_corto_sem" maxlength="40">
                              </div>
                            </div>
                            <div class="col-lg-12" style="float: left; height: 100px;">
                              <div class="form-group">
                                <label>Detalle</label>
                                <input type="text" class="form-control" id="detalle_actividad" >
                                
                              </div>
                            </div>
                            <div class="col-lg-12" style="float: left; height: 130px;">
                              <div class="form-group">
                                <label>Texto en portada <small style="color:#888;">(reemplaza "Próxima Transmisión" mientras el banner esté activo)</small></label>
                                <textarea class="form-control" id="texto_banner" rows="2" placeholder="Ej: Esta semana celebramos el culto de adoración..."></textarea>
                              </div>
                            </div>
                            <div class="col-lg-12" style="float: left; height: 100px;">
                              <div class="form-group">
                                <label>URL del video <small style="color:#888;">(YouTube — aparece como botón "Ver video")</small></label>
                                <input type="text" class="form-control" id="video_url" placeholder="https://youtu.be/...">
                              </div>
                            </div>
                            <div class="col-lg-12" style="float: left;">
                                <div class="form-group">
                                    <label>Imagen *</label>
                                    <button  type="button" id="btn-foto">Seleccionar</button>
                                    <input type="text" class="form-control" id="archivo_audio" disabled>
                                   
                                </div>
                            </div>
                            
                            <div class="col-lg-12" style="float: left;">
                                <div class="form-group" style="margin-top: 50px; text-align: right;">
                                    <!-- <button class="btn btn-primary mr-2" >Guardar</button> -->
                                    <b style="padding: 20px; background-color: #000; color: #fff; cursor: pointer; border-radius: 10px;" onclick="guardar_activ_sem();">Guardar</b>
                                </div>
                            </div>
                            
                        </form>
                    </div>
                </div>
              </div>
              <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                    <h4 class="card-title">Temas semanales</h4>
                    <!-- <p class="card-description">
                        Add class <code>.table-striped</code>
                    </p> -->
                    <div class="table-responsive" style="height: 400px; overflow: scroll;">
                        <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>
                                    Fecha inicio
                                </th>
                                <th>
                                    Hora termino
                                </th>
                                <th>
                                    Nombre
                                </th>
                                <th>
                                    Nombre corto
                                </th>
                                <th>
                                    Detalle
                                </th>
                                <th>
                                    Imagen
                                </th>
                                <th>
                                    Acciones
                                </th>

                            </tr>
                        </thead>
                        <tbody id="temas_sem">
                            
                            
                        </tbody>
                        </table>
                    </div>
                    </div>
                </div>
              </div>
  
              
            </div>
            
          </div>
          <!-- content-wrapper ends -->

          <!-- Modal editar banner -->
          <div class="modal fade" id="modalEditarActiv" tabindex="-1" role="dialog" aria-labelledby="modalEditarActivLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="modalEditarActivLabel">Editar banner</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <input type="hidden" id="edit_idactiv">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Fecha 1 *</label>
                        <input type="date" class="form-control" id="edit_fecha1">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Fecha 2 *</label>
                        <input type="date" class="form-control" id="edit_fecha2">
                      </div>
                    </div>
                    <div class="col-md-12">
                      <div class="form-group">
                        <label>Nombre de Actividad *</label>
                        <input type="text" class="form-control" id="edit_nombre">
                      </div>
                    </div>
                    <div class="col-md-12">
                      <div class="form-group">
                        <label>Nombre corto *</label>
                        <input type="text" class="form-control" id="edit_nombre_corto" maxlength="40">
                      </div>
                    </div>
                    <div class="col-md-12">
                      <div class="form-group">
                        <label>Detalle</label>
                        <input type="text" class="form-control" id="edit_detalle">
                      </div>
                    </div>
                    <div class="col-md-12">
                      <div class="form-group">
                        <label>Texto en portada <small style="color:#888;">(reemplaza "Próxima Transmisión")</small></label>
                        <textarea class="form-control" id="edit_texto_banner" rows="2" placeholder="Ej: Esta semana celebramos..."></textarea>
                      </div>
                    </div>
                    <div class="col-md-12">
                      <div class="form-group">
                        <label>URL del video <small style="color:#888;">(YouTube — aparece como botón "Ver video")</small></label>
                        <input type="text" class="form-control" id="edit_video_url" placeholder="https://youtu.be/...">
                      </div>
                    </div>
                    <div class="col-md-12">
                      <div class="form-group">
                        <label>Imagen *</label>
                        <div class="d-flex align-items-center" style="gap:10px;">
                          <img id="edit_preview_img" src="" alt="preview" style="width:60px;height:60px;object-fit:cover;border-radius:6px;border:1px solid #ccc;">
                          <button type="button" id="btn-foto-edit" class="btn btn-secondary btn-sm">Cambiar imagen</button>
                        </div>
                        <input type="text" class="form-control mt-2" id="edit_imagen" disabled>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                  <button type="button" class="btn btn-primary" onclick="guardar_edicion_activ()">Guardar cambios</button>
                </div>
              </div>
            </div>
          </div>

          <!-- Modal ver detalle -->
          <div class="modal fade" id="modalVerActiv" tabindex="-1" role="dialog" aria-labelledby="modalVerActivLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="modalVerActivLabel">Detalle de la semana especial</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <input type="hidden" id="ver_idactiv">
                  <input type="hidden" id="ver_nombre_corto">
                  <input type="hidden" id="ver_fecha1">
                  <input type="hidden" id="ver_fecha2">
                  <div class="row">
                    <div class="col-md-4 text-center">
                      <img id="ver_imagen" src="" alt="" style="width:100%;max-width:220px;border-radius:8px;border:1px solid #ccc;">
                    </div>
                    <div class="col-md-8">
                      <h4 id="ver_nombre" style="margin-bottom:4px;"></h4>
                      <p style="color:#888;margin-bottom:6px;" id="ver_fechas"></p>
                      <p id="ver_detalle"></p>
                    </div>
                  </div>

                  <hr>

                  <h5>Formulario de registro</h5>
                  <div id="ver_seccion_formulario">
                    <p style="color:#aaa;">Cargando…</p>
                  </div>

                  <hr>

                  <h5>Código QR</h5>
                  <div id="ver_seccion_qr">
                    <p style="color:#aaa;">Cargando…</p>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
              </div>
            </div>
          </div>

           <script src="https://widget.cloudinary.com/v2.0/global/all.js" type="text/javascript"></script>
           <script src="https://cdn.jsdelivr.net/npm/qr-code-styling@1.6.0-rc.1/lib/qr-code-styling.js"></script>
           <script type="text/javascript" src="scripts/semanas_esp.js?v=<?php echo(rand()); ?>"></script>
           <script type="text/javascript" src="scripts/servicio-imagen.js?v=<?php echo(rand()); ?>"></script>
<?php
  require "footer.php";
?>
<?php
}
else
{
  require 'noacceso.php';
}

?>

<?php
}
ob_end_flush();
?>