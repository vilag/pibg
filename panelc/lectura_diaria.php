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
            
            <style>
              .estilo_btn_delete_text_reg{
                float: left; text-align: center;
                transition: all 0.3s;
                padding: 2px;
                cursor: pointer;
              }
              .estilo_btn_delete_text_reg:hover{
                float: left; text-align: center;
                background-color:rgb(50, 123, 231);
                border-radius: 5px;
                height: 25px;
                padding: 2px;
                cursor: pointer;
                transition: all 0.3s;
              }
            </style>
            
            <div class="row">
              <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-wrap justify-content-between">
                            <h4 class="card-title mb-3">Registrar lecturas</h4>
                        </div>
                        <form class="forms-sample" style="padding-top: 20px;">

                        <div class="col-lg-6" style="float: left;">
                            <div class="col-lg-6" style="float: left; height: 100px;">
                              <div class="form-group">
                                <label>Fecha</label>
                                <input type="date" class="form-control" id="fecha">
                              </div>
                            </div>
                            <div class="col-lg-6" style="float: left; height: 100px;">
                              <div class="form-group">
                                <label>Tipo</label>
                                <select style="height: 48px;" class="form-control" name="" id="tipo_cita_biblica" onchange="seleccionar_tipo();">
                                  <option value="">Seleccionar</option>
                                  <option value="1">Capitulo completo</option>
                                  <option value="2">Capitulo y versículo</option>
                                  <option value="3">Capitulo y rango de versiculos</option>
                                </select>
                              </div>
                            </div>

                            <div class="col-sm-12 col-md-12 col-lg-12" style="float: left; height: 100px;">
                                <div class="form-group">
                                    <label>Libro (Ejemplo: Salmos, 1-Corintios)</label>
                                    <input type="text" class="form-control" id="libro_cita_biblica">
                                   
                                </div>
                            </div>
                            <div class="col-lg-4" style="float: left; height: 100px;">
                                <div class="form-group">
                                    <label>Capitulo</label>
                                    <input type="number" class="form-control" id="capitulo_cita_biblica">
                                   
                                </div>
                            </div>
                            <div class="col-lg-4" style="float: left; height: 100px;">
                                <div class="form-group">
                                    <label>Versiculo 1</label>
                                    <input type="number" class="form-control" id="vers1_cita_biblica">    
                                </div>
                            </div>
                            <div class="col-lg-4" style="float: left; height: 100px;">
                                <div class="form-group">
                                    <label>Versiculo 2</label>
                                    <input type="number" class="form-control" id="vers2_cita_biblica">    
                                </div>
                            </div>


                            <!-- <div class="col-lg-6" style="float: left; height: 100px;">
                                <div class="form-group">
                                    <label>Cita Biblica</label>
                                    <input type="text" class="form-control" id="cita_biblica">
                                   
                                </div>
                            </div>
                            <div class="col-lg-12" style="float: left; height: 100px;">
                              <div class="form-group">
                                <label>Link</label>
                                <input type="text" class="form-control" id="link">
                              </div>
                            </div> -->
                            <div class="col-lg-12"  style="float: left;">
                              <div class="col-lg-12">
                                <p>Texto encontrado</p>
                              </div>
                              <div class="col-lg-12" id="text_confirm_div" style="height: 200px; overflow-y: scroll; background-color:rgb(237, 239, 245); padding: 15px;">

                              </div>
                            </div>
                           
                            
                            <div class="col-lg-12" style="float: left;">
                                <div class="form-group" style="margin-top: 50px; text-align: center;">
                                    <!-- <button class="btn btn-primary mr-2" >Guardar</button> -->
                                    <b id="btn_save_cita" style="float: left; margin-right: 5px; width: 100px; display: block; padding: 20px; background-color: #000; color: #fff; cursor: pointer; border-radius: 10px;" onclick="buscar_cita();">Buscar</b>
                                    <b id="btn_confirm_cita" style="float: left; margin-right: 5px; width: 150px; display: none; padding: 20px; background-color: #015cb8; color: #fff; cursor: pointer; border-radius: 10px;" onclick="guardar_cita();">Guardar</b>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6" style="float: left;">
                          <div class="col-lg-12">
                            <b>Textos guardados (Click en la cita bíblica para ver)</b>
                          </div>
                          <div id="div_textos_guardados" class="col-lg-12" style="background-color:rgb(237, 239, 245); padding: 15px; margin-top: 15px; height: 500px; overflow-y: scroll;">
                          </div>

                        </div>
                            
                            
                            
                        </form>
                    </div>
                </div>
              </div>
              <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                    <h4 class="card-title">Lecturas diarias</h4>
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
                                    Cita
                                </th>
                                <th>
                                    Link
                                </th>
                            </tr>
                        </thead>
                        <tbody id="dias_lectura">
                            
                            
                        </tbody>
                        </table>
                    </div>
                    </div>
                </div>
              </div>

              
            </div>
            
          </div>
          <!-- content-wrapper ends -->
          
           <script type="text/javascript" src="scripts/lectura_diaria.js?v=<?php echo(rand()); ?>"></script>
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