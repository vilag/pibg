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
                                <label>Fecha 1</label>
                                <input type="date" class="form-control" id="fecha_actividad1" >
                              </div>
                            </div>
                            <div class="col-lg-6" style="float: left; height: 100px;">
                              <div class="form-group">
                                <label>Fecha 2</label>
                                <input type="date" class="form-control" id="fecha_actividad2">
                              </div>
                            </div>
                            
                            <div class="col-lg-12" style="float: left; height: 100px;">
                              <div class="form-group">
                                <label>Nombre de Actividad</label>
                                <input type="text" class="form-control" id="nom_actividad_sem">
                                <!-- <div id="nombre_act_capt" style="display: none; width: 50%; height: 250px; background-color: #fff; position: absolute; z-index: 9999; overflow-y: scroll; text-align: center; border: #ccc 1px solid;">

                                </div> -->
                              </div>
                            </div>
                            <div class="col-lg-12" style="float: left; height: 100px;">
                              <div class="form-group">
                                <label>Detalle</label>
                                <input type="text" class="form-control" id="detalle_actividad" >
                                
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
                                    Detalle
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

           <script type="text/javascript" src="scripts/semanas_esp.js?v=<?php echo(rand()); ?>"></script>
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