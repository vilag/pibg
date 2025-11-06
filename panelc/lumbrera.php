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
            <link rel="stylesheet" href="css/estilo_bach.css">
            
            
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
                        <div style="width: 100%; text-align: center;">
                            <a href="https://primeraiglesiabautistagdl.org/voces_lumbrera.php" target="_blank">Ver Página</a>
                        </div>
                    <button id="btn_back_obras" style="margin-bottom: 20px; display: none;" onclick="regresar_a_obras();">Regresar</button>
                    
                    <!-- <p class="card-description">
                        Add class <code>.table-striped</code>
                    </p> -->
                    <div style="width: 100%;">
                        <div id="li_obra" style="width: 100%; float: left; display: flex; justify-content: right;">
                            <a style="padding: 10px; background-color: #000; color: #fff; margin-left: -15px; border-radius: 5px;" href="#modal1" onclick="abrir_modal_nuevo();">Nueva obra</a>
                        </div>
                        <div id="li_voz" style="width: 100%; float: left; display: none; justify-content: right;">
                            <a style="padding: 10px; background-color: #000; color: #fff; margin-left: -15px; border-radius: 5px;" href="#modal2" onclick="abrir_modal_nuevo2();">Nueva voz</a>
                        </div>
                        <!-- <ul>
                            <li  style="list-style:none;"></li>
                            <li  style="display: none; list-style:none;"></li>
                        </ul> -->
                    </div>

                    
                    
                    
                    <div class="table-responsive">
                        <div id="tbl_obras">
                            <div style="padding: 10px;">
                                <b style="font-size: 20px;">LISTA DE OBRAS - LUMBRERA</b>
                            </div>
                            <div id="div_obras" style="height: 400px; overflow: scroll;">

                            </div>

                        </div>

                        <div id="tbl_voces">
                            <div style="padding: 10px;">
                                <b style="font-size: 20px;" id="nom_obra_voces"></b>
                                <input type="hidden" id="idobra_refresh">
                            </div>
                            <div id="div_voces" style="height: 400px; overflow: scroll;">

                            </div> 

                        </div>
                        
                    </div>
                    </div>
                </div>
              </div>

              
            </div>
            
          </div>
          <!-- content-wrapper ends -->



          
<div id="modal1" class="modalmask">
    <div class="modalbox movedown" style="height: 450px;">
        <a href="#close" title="Close" class="close">X</a>
        <form id="form_obras" class="forms-sample" style="padding-top: 50px;">
            <div style="width: 100%; height: 50px; text-align: center;">
                <span>Registrar nueva obra</span>
            </div>
                            <div class="col-lg-12" style="float: left; height: 100px;">
                              <div class="form-group">
                                <label>Nombre</label>
                                <input type="text" class="form-control" id="nombre" style="background-color: #dde4f7ff;">
                              </div>
                            </div>
                            <div class="col-lg-12" style="float: left; height: 100px;">
                                <div class="form-group">
                                    <label>Autor</label>
                                    <input type="text" class="form-control" id="autor" style="background-color: #dde4f7ff;">
                                    <input type="hidden" class="form-control" id="idobra_update" value="0">
                                </div>
                            </div>
                        
                            <div class="col-lg-12" style="float: left;">
                                <div class="form-group" style="margin-top: 20px; display: flex; justify-content: center;">
                                    <button id="btn_guardar_obra" style="margin-right: 5px; float: left; display: block; padding: 20px; background-color: #000; color: #fff; cursor: pointer; border-radius: 10px;" onclick="guardar_obra();"><b>Guardar</b></button>
                                    <button id="btn_actualizar_obra" style="margin-right: 5px; float: left; display: none; padding: 20px; background-color: #000; color: #fff; cursor: pointer; border-radius: 10px;" onclick="actualizar_obra();"><b>Actualizar</b></button>
                                    <a style="margin-right: 5px; float: left; padding: 21px; background-color: #000; color: #fff; cursor: pointer; border-radius: 10px;" href="#"><b>Cerrar</b></a>
                                </div>
                            </div>
                            
                    </form>
    </div>
</div>

<style>
  .barra_partituras::-webkit-scrollbar {
    width: 5px;
    height: 5px;
  }

  .barra_partituras::-webkit-scrollbar-track {
    background: rgba(155, 168, 175, 0.5);
  }

  .barra_partituras::-webkit-scrollbar-thumb {
    background-color: rgb(40, 57, 87);
    border-radius: 20px;
    border: 1px solid rgba(155, 168, 175, 0.5);
    ;
    box-shadow: 5px 5px 5px rgba(0, 0, 0, 0.1);
  }
</style>

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
                                <input type="text" class="form-control" id="voz" style="background-color: #dde4f7ff;">
                              </div>
                            </div>
                            <div class="col-lg-12" style="float: left;">
                                <div class="form-group">
                                    <label>Archivo de audio</label>
                                    <button  type="button" id="btn-foto_lumbrera">Seleccionar</button>
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

<div id="modal_archivo_voz" class="modalmask">
    <div class="modalbox movedown" style="height: 380px;">
        <a href="#close" title="Close" class="close">X</a>
        <div style="width: 100%; padding: 20px;">
            <form name="formulario_partituras" id="formulario_partituras" enctype="multipart/form-data" method="post">
 
                <div class="form-group col-md-12 col-sm-12">
                    <div class="form-group col-md-12 col-sm-12">
                        <span onclick="back_list_part();" id="span_back" style="cursor: pointer; padding: 5px; background-color: rgb(7, 47, 123); color: #fff; border-radius: 5px; display: none;">Regresar</span>
                    </div>
                    <div class="form-group col-md-12 col-sm-12" style="text-align: center;">
                        <b style="text-transform: uppercase;" id="nombre_obre_modal_partituras"></b><br>
                        <span id="nombre_voz_modal_part"></span>
                    </div>
                    <div id="content_list_partituras" class="form-group col-md-12 col-sm-12" style="display: block; padding: 10px;">
                        <div class="form-group col-md-12 col-sm-12" id="lista_partituras" style="height: 150px; overflow: scroll;">
                        
                        </div>
                        <div class="form-group col-md-12 col-sm-12" align="center" style="margin-top: 10px;">
                            <button type="button" class="btn btn-dark"  onclick="subir_doc_voz_content();" id="" style="border-radius: 10px;">Nuevo</button>
                        </div>
                    </div>                               
                    <div id="content_reg_part" class="form-group col-md-12 col-sm-12" style="display: none;">
                        <label>CARGAR NUEVA PARTITURA</label><br>
                        <input type="file" name="archivo_part" id="archivo_part">
                        <input type="hidden" name="idvoz_upload" id="idvoz_upload">
                        <input type="hidden" name="nom_voz_upload" id="nom_voz_upload">
                        <input type="hidden" name="fecha_reg_part" id="fecha_reg_part">
                        <div class="form-group col-md-12 col-sm-12" align="center" style="margin-top: 20px;">
                            <button type="button" class="btn btn-dark"  onclick="subir_doc_voz();" id="" style="border-radius: 10px;">Cargar archivo</button>
                        </div>
                    </div>
                                                  
                </div>
 
                
              
                <div class="form-group col-md-7 col-sm-7" id="box_documentos_cargados_lic">

                </div>
                                                                                        
            </form>
        </div>
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
           <script type="text/javascript" src="scripts/lumbrera.js?v=<?php echo(rand()); ?>"></script>
           <script type="text/javascript" src="scripts/servicio_cloudinary.js?v=<?php echo(rand()); ?>"></script>

           
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