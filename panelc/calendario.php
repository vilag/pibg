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
                            <h4 class="card-title mb-3">Registrar en calendario</h4>
                        </div>
                        <form class="forms-sample" style="padding-top: 20px;">
                            <label for="">Seleccion rapida</label>
                            <div class="col-lg-12" style="float: left; height: 100px; margin-top: 20px;" id="box_act_sem">
                              
                            </div>
                            <div class="col-lg-6" style="float: left; height: 100px;">
                              <div class="form-group">
                                <label>Fecha</label>
                                <input type="date" class="form-control" id="fecha_actividad" onchange="mostrar_dia();">
                              </div>
                            </div>
                            <div class="col-lg-6" style="float: left; height: 100px;">
                                <div class="form-group">
                                    <label>Hora (Formato 24 hrs)</label>
                                    <input type="text" class="form-control" id="hora_actividad" value="00:00:00">
                                    <!-- <div id="horas_capt" style="display: none; width: 30%; height: 150px; background-color: #fff; position: absolute; z-index: 9999; overflow-y: scroll; text-align: center; border: #ccc 1px solid;">

                                    </div> -->
                                </div>
                            </div>
                            <div class="col-lg-6" style="float: left; height: 100px;">
                              <div class="form-group">
                                <label>Dia</label>
                                <input type="text" class="form-control" id="dia" disabled>
                              </div>
                            </div>
                            <div class="col-lg-6" style="float: left; height: 100px;">
                              <div class="form-group">
                                <label>Nombre de Actividad</label>
                                <input type="text" class="form-control" id="nom_actividad">
                                <!-- <div id="nombre_act_capt" style="display: none; width: 50%; height: 250px; background-color: #fff; position: absolute; z-index: 9999; overflow-y: scroll; text-align: center; border: #ccc 1px solid;">

                                </div> -->
                              </div>
                            </div>
                            <div class="col-lg-10" style="float: left; height: 100px;">
                              <div class="form-group">
                                <label>Tema</label>
                                <input type="text" class="form-control" id="tema_actividad" >
                                
                              </div>
                            </div>
                            <div class="col-lg-2" style="float: left; height: 100px;">
                              <div class="form-group" style="text-align: center;">
                                <div class="col-lg-12">
                                  <label>Transmisión</label>
                                </div>
                                <div class="col-lg-12" style="padding-top: 20px;">
                                  <input type="radio" id="activ1" name="tipo_actividad" value="Si">
                                  <label style="color: #000; margin-top: -3px; margin-right: 15px;" for="marca1">Si</label>
                                  <input type="radio" id="activ2" name="tipo_actividad" value="No">
                                  <label style="color: #000; margin-top: -3px; margin-right: 5px;" for="marca2">No</label>
                                </div>
                                
                                <!-- <input type="checkbox" class="form-control" id="tipo_actividad" style="margin-top: 20px;"> -->
                                
                                
                              </div>
                            </div>
                            <div class="col-lg-12" style="float: left;">
                                <div class="form-group" style="margin-top: 50px; text-align: right;">
                                    <!-- <button class="btn btn-primary mr-2" >Guardar</button> -->
                                    <b style="padding: 20px; background-color: #000; color: #fff; cursor: pointer; border-radius: 10px;" onclick="guardar_dia_calendario();">Guardar</b>
                                </div>
                            </div>
                            
                        </form>
                    </div>
                </div>
              </div>
              <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-3">Cargar actividades desde PDF</h4>
                        <p class="text-muted" style="font-size: 13px;">Sube el PDF del calendario anual (una página por mes, tabla de Fecha/Actividad/Horario/Encargados). Se analiza y te muestra la lista antes de guardar nada — puedes editar, quitar o agregar actividades y seleccionar solo las que quieras registrar.</p>
                        <div class="form-inline" style="gap: 10px;">
                            <input type="file" id="cal_pdf_input" accept="application/pdf" class="form-control" style="max-width: 320px; display: inline-block;">
                            <button type="button" class="btn btn-primary" onclick="cal_analizar_pdf();" id="cal_pdf_btn_analizar">Analizar PDF</button>
                            <span id="cal_pdf_estado" style="margin-left: 10px; color: #6c757d;"></span>
                        </div>

                        <div id="cal_pdf_revision" style="display: none; margin-top: 24px;">
                            <div class="d-flex flex-wrap align-items-center mb-2" style="gap: 10px;">
                                <label class="mb-0" style="font-size: 13px; color: #6c757d;">Mes:</label>
                                <select id="cal_pdf_filtro_mes" class="form-control form-control-sm" style="width: auto;" onchange="cal_pdf_render_tabla();">
                                    <option value="">Todos</option>
                                    <option value="1">Enero</option>
                                    <option value="2">Febrero</option>
                                    <option value="3">Marzo</option>
                                    <option value="4">Abril</option>
                                    <option value="5">Mayo</option>
                                    <option value="6">Junio</option>
                                    <option value="7">Julio</option>
                                    <option value="8">Agosto</option>
                                    <option value="9">Septiembre</option>
                                    <option value="10">Octubre</option>
                                    <option value="11">Noviembre</option>
                                    <option value="12">Diciembre</option>
                                </select>
                                <label class="mb-0" style="font-size: 13px; color: #6c757d;">Año:</label>
                                <select id="cal_pdf_filtro_anio" class="form-control form-control-sm" style="width: auto;" onchange="cal_pdf_render_tabla();">
                                    <option value="">Todos</option>
                                </select>
                                <span class="text-muted" style="font-size: 12px;">(los botones de marcar aplican solo a lo que se ve con este filtro)</span>
                            </div>
                            <div class="d-flex flex-wrap justify-content-between align-items-center mb-2">
                                <div>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="cal_pdf_agregar_fila();">+ Agregar actividad</button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="cal_pdf_marcar_todas(true);">Marcar todas</button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="cal_pdf_marcar_todas(false);">Desmarcar todas</button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="cal_pdf_marcar_desde_hoy();">Marcar desde hoy</button>
                                </div>
                                <button type="button" class="btn btn-success" id="cal_pdf_btn_registrar" onclick="cal_pdf_registrar_seleccionadas();">Registrar seleccionadas (<span id="cal_pdf_contador">0</span>)</button>
                            </div>
                            <div class="table-responsive" style="max-height: 500px; overflow: auto;">
                                <table class="table table-sm table-striped">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Fecha</th>
                                            <th>Hora</th>
                                            <th>Nombre de actividad</th>
                                            <th>Tema / Encargados</th>
                                            <th>Transmisión</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody id="cal_pdf_tabla"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
              </div>
              <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                    <h4 class="card-title">Calendario</h4>
                    <div class="d-flex flex-wrap align-items-center" style="gap: 10px; margin-bottom: 16px;">
                        <label class="mb-0" style="font-size: 13px; color: #6c757d;">Mes:</label>
                        <select id="cal_filtro_mes" class="form-control form-control-sm" style="width: auto;" onchange="listar_dias();">
                            <option value="">Todos</option>
                            <option value="1">Enero</option>
                            <option value="2">Febrero</option>
                            <option value="3">Marzo</option>
                            <option value="4">Abril</option>
                            <option value="5">Mayo</option>
                            <option value="6">Junio</option>
                            <option value="7">Julio</option>
                            <option value="8">Agosto</option>
                            <option value="9">Septiembre</option>
                            <option value="10">Octubre</option>
                            <option value="11">Noviembre</option>
                            <option value="12">Diciembre</option>
                        </select>
                        <label class="mb-0" style="font-size: 13px; color: #6c757d;">Año:</label>
                        <select id="cal_filtro_anio" class="form-control form-control-sm" style="width: auto;" onchange="listar_dias();"></select>
                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="cal_filtro_limpiar();">Ver todo</button>
                    </div>
                    <div class="table-responsive" style="height: 400px; overflow: scroll;">
                        <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>
                                    Fecha
                                </th>
                                <th>
                                    Hora
                                </th>
                                <th>
                                    Dia
                                </th>
                                <th>
                                    Nombre
                                </th>
                                <th>
                                    Tema
                                </th>
                                <th>
                                    Transmisión
                                </th>
                                <th>
                                    Eliminar
                                </th>
                            
                            </tr>
                        </thead>
                        <tbody id="dias_calendario">
                            
                            
                        </tbody>
                        </table>
                    </div>
                    </div>
                </div>
              </div>

              
            </div>
            
          </div>
          <!-- content-wrapper ends -->

           <script type="text/javascript" src="scripts/calendario.js?v=<?php echo(rand()); ?>"></script>
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