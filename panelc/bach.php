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
              <!-- <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-wrap justify-content-between">
                            <h4 class="card-title mb-3">Obras</h4>
                        </div>
                        <form class="forms-sample" style="padding-top: 20px;">
                            
                            <div class="col-lg-6" style="float: left; height: 100px;">
                              <div class="form-group">
                                <label>Fecha</label>
                                <input type="date" class="form-control" id="fecha">
                              </div>
                            </div>
                            <div class="col-lg-6" style="float: left; height: 100px;">
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
                            </div>
                            
                           
                            
                            <div class="col-lg-12" style="float: left;">
                                <div class="form-group" style="margin-top: 50px; text-align: right;">
                                    
                                    <b style="padding: 20px; background-color: #000; color: #fff; cursor: pointer; border-radius: 10px;" onclick="guardar_lectura();">Guardar</b>
                                </div>
                            </div>
                            
                        </form>
                    </div>
                </div>
              </div> -->
              <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                    <button id="btn_back_obras" style="margin-bottom: 20px; display: none;" onclick="regresar_a_obras();">Regresar</button>
                    <h4 >Registro de obras y voces</h4><br>
                    <!-- <p class="card-description">
                        Add class <code>.table-striped</code>
                    </p> -->
                    <div>
                    <ul>
                        <li id="li_obra" style="list-style:none;"><a style="padding: 10px; background-color: #000; color: #fff; margin-left: -15px; border-radius: 5px;" href="#modal1" onclick="abrir_modal_nuevo();">Nueva obra</a></li>
                        <li id="li_voz" style="display: none; list-style:none;"><a style="padding: 10px; background-color: #000; color: #fff; margin-left: -15px; border-radius: 5px;" href="#modal2" onclick="abrir_modal_nuevo2();">Nueva voz</a></li>
                    </ul>
                    </div>

                    <div>

                    </div>
                    
                    
                    <div class="table-responsive" style="height: 400px; overflow: scroll;">
                        <div id="tbl_obras">
                            <div id="div_obras">

                            </div>

                        </div>

                        <div id="tbl_voces">
                            <div id="div_voces">

                            </div>

                        </div>
                        <!-- <table id="tbl_obras" class="table table-striped">
                        <thead>
                            <tr>
                                <th>
                                    Nombre
                                </th>
                                <th>
                                    Autor
                                </th>
                                
                            </tr>
                        </thead>
                        <tbody id="div_obras">
                            
                            
                        </tbody>
                        </table> -->

                        <!-- <table id="tbl_voces" class="table table-striped" style="display: none;">
                        <thead>
                            <tr>
                                <th>
                                    Voz
                                </th>
                                <th>
                                    Audio
                                </th>
                                <th>
                                    Opciones
                                </th>
                            </tr>
                        </thead>
                        <tbody id="div_voces">
                            
                            
                        </tbody>
                        </table> -->
                    </div>
                    </div>
                </div>
              </div>

              
            </div>
            
          </div>
          <!-- content-wrapper ends -->



          
<div id="modal1" class="modalmask">
    <div class="modalbox movedown" style="height: 350px;">
        <a href="#close" title="Close" class="close">X</a>
        <form id="form_obras" class="forms-sample" style="padding-top: 50px;">
                    
                            <div class="col-lg-12" style="float: left; height: 100px;">
                              <div class="form-group">
                                <label>Nombre</label>
                                <input type="text" class="form-control" id="nombre">
                              </div>
                            </div>
                            <div class="col-lg-12" style="float: left; height: 100px;">
                                <div class="form-group">
                                    <label>Autor</label>
                                    <input type="text" class="form-control" id="autor">
                                    <input type="hidden" class="form-control" id="idobra_update" value="0">
                                </div>
                            </div>
                        
                            <div class="col-lg-12" style="float: left;">
                                <div class="form-group" style="margin-top: 20px;">
                                    <button id="btn_guardar_obra" style="margin-right: 5px; float: left; display: block; padding: 20px; background-color: #000; color: #fff; cursor: pointer; border-radius: 10px;" onclick="guardar_obra();"><b>Guardar</b></button>
                                    <button id="btn_actualizar_obra" style="margin-right: 5px; float: left; display: none; padding: 20px; background-color: #000; color: #fff; cursor: pointer; border-radius: 10px;" onclick="actualizar_obra();"><b>Actualizar</b></button>
                                    <a style="margin-right: 5px; float: left; padding: 21px; background-color: #000; color: #fff; cursor: pointer; border-radius: 10px;" href="#"><b>Cerrar</b></a>
                                </div>
                            </div>
                            
                    </form>
    </div>
</div>

<div id="modal2" class="modalmask">
    <div class="modalbox movedown" style="height: 380px;">
        <a href="#close" title="Close" class="close">X</a>
                    <form id="form_voces" class="forms-sample" style="padding-top: 20px;">
                            <div class="col-lg-12" style="float: left; height: 50px;">
                              <b id="obra_select"></b>
                              <input type="hidden" id="idobra">
                              <input type="hidden" id="idvoz">
                            </div>
                            <div class="col-lg-12" style="float: left;">
                              <div class="form-group">
                                <label>Voz</label>
                                <input type="text" class="form-control" id="voz">
                              </div>
                            </div>
                            <div class="col-lg-12" style="float: left;">
                                <div class="form-group">
                                    <label>Archivo de audio</label>
                                    <button  type="button" id="btn-foto">Seleccionar</button>
                                    <input type="text" class="form-control" id="archivo_audio" disabled>
                                   
                                </div>
                            </div>
                        
                            <div class="col-lg-12" style="float: left;">
                                <div class="form-group" style=" text-align: right;">
                                    <button id="btn_guardar_voz" style="margin-right: 5px; float: left; display: block; padding: 20px; background-color: #000; color: #fff; cursor: pointer; border-radius: 10px;" onclick="guardar_voz();"><b>Guardar</b></button>
                                    <button id="btn_actualizar_voz" style="margin-right: 5px; float: left; display: none; padding: 20px; background-color: #000; color: #fff; cursor: pointer; border-radius: 10px;" onclick="actualizar_voz();"><b>Actualizar</b></button>
                                    <a style="margin-right: 5px; float: left; padding: 21px; background-color: #000; color: #fff; cursor: pointer; border-radius: 10px;" href="#"><b>Cerrar</b></a>
                                </div>
                            </div>
                            
                    </form>
    </div>
</div>
<!-- <div id="modal2" class="modalmask">
    <div class="modalbox rotate">
        <a href="#close" title="Close" class="close">X</a>
        <h2>ROTAR</h2>
        <p>Usando la propiedad transform de CSS3, podemos hacer que las ventanas aparezcan rotando.</p>
        <p>No hay nada de Javascript, solo unas pocas lineas de CSS.</p>
    </div>
</div>
<div id="modal3" class="modalmask">
    <div class="modalbox resize">
        <a href="#close" title="Close" class="close">X</a>
        <h2>REDIMENSIONAR</h2>
        <p>También puedes redimensionar la ventana hasta hacerla desaparecer.</p>
        <p>Las posibilidades que ofrece CSS3 son múltiples, tan solo hace falta un poco de imaginación para crear efectos realmente llamativos.</p>
    </div>
</div> -->

          
          
          <script src="https://widget.cloudinary.com/v2.0/global/all.js" type="text/javascript"></script>
           <script type="text/javascript" src="scripts/bach.js?v=<?php echo(rand()); ?>"></script>
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