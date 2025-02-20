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
                    <h4 class="card-title">Calendario</h4>
                    <!-- <p class="card-description">
                        Add class <code>.table-striped</code>
                    </p> -->
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
                                    Transmisión
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