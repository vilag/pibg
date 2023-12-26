<?php
  require "header.php";
?>
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            
            
            
            <div class="row">
              <div class="col-lg-12 d-flex grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <div class="d-flex flex-wrap justify-content-between">
                      <h4 class="card-title mb-3">Registros</h4>
                    </div>
                    <div class="row">
                      <div class="col-md-6 grid-margin stretch-card">
                        <div class="card">
                          <div class="card-body">
                            <h4 class="card-title">Calendario</h4>
                            <p class="card-description">
                              Actividades semanales
                            </p>
                            <form class="forms-sample">
                              <div class="form-group">
                                <label>Fecha de actividad</label>
                                <input type="date" class="form-control" id="fecha_actividad" onchange="mostrar_dia();">
                              </div>
                              <div class="form-group">
                                <label>Dia</label>
                                <input type="text" class="form-control" id="dia" disabled>
                              </div>
                              <div class="form-group">
                                <label>Nombre de Actividad</label>
                                <input type="text" class="form-control" id="nom_actividad" onclick="mostrar_list_act();">
                                <div style="width: 88%; background-color: white; height: 150px; position: absolute; border-style: solid; border-width: 1px; border-radius: 10px; display: none; overflow: scroll; padding-left: 15px;" id="div_list_act">
                                  
                                </div>
                              </div>
                              <div class="form-group">
                                <label>Icono</label>
                                <div class="form-group" id="iconos_list">

                                </div><br>
                              </div>

                              <div class="form-group">
                                <label>Tema</label>
                                <input type="text" class="form-control" id="tema">
                              </div>
                              <div class="form-group">
                                <label>Icono tema</label>
                                <div class="form-group" id="iconos_list2">

                                </div><br>
                              </div>
                              <div class="form-group" style="margin-top: 50px;" align="center">
                                <button class="btn btn-primary mr-2">Guardar</button>
                              </div>



                              
                              
                              
                            </form>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-9">
                        <div class="d-sm-flex justify-content-between">
                          <div class="dropdown">
                            <button class="btn bg-white btn-sm dropdown-toggle btn-icon-text pl-0" type="button" id="dropdownMenuSizeButton4" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Mon,1 Oct 2019 - Tue,2 Oct 2019
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuSizeButton4" data-x-placement="top-start">
                              <h6 class="dropdown-header">Mon,17 Oct 2019 - Tue,25 Oct 2019</h6>
                              <a class="dropdown-item" href="#">Tue,18 Oct 2019 - Wed,26 Oct 2019</a>
                              <a class="dropdown-item" href="#">Wed,19 Oct 2019 - Thu,26 Oct 2019</a>
                            </div>
                          </div>
                          <div>
                            <button type="button" class="btn btn-sm btn-light mr-2">Day</button>
                            <button type="button" class="btn btn-sm btn-light mr-2">Week</button>
                            <button type="button" class="btn btn-sm btn-light">Month</button>
                          </div>
                        </div>
                        <div class="chart-container mt-4">
                          <canvas id="ecommerceAnalytic"></canvas>
                        </div>
                      </div>
                      <div class="col-lg-3">
                        <div>
                          <div class="d-flex justify-content-between mb-3">
                            <div class="text-success font-weight-bold">Inbound</div>
                          </div>
                          <div class="d-flex justify-content-between mb-3">
                            <div class="font-weight-medium">Current</div>
                            <div class="text-muted">38.34M</div>
                          </div>
                          <div class="d-flex justify-content-between mb-3">
                            <div class="font-weight-medium">Average</div>
                            <div class="text-muted">38.34M</div>
                          </div>
                          <div class="d-flex justify-content-between mb-3">
                            <div class="font-weight-medium">Maximum</div>
                            <div class="text-muted">68.14M</div>
                          </div>
                          <div class="d-flex justify-content-between mb-3">
                            <div class="font-weight-medium">60th %</div>
                            <div class="text-muted">168.3GB</div>
                          </div>
                        </div>
                        <hr>
                        <div class="mt-4">
                          <div class="d-flex justify-content-between mb-3">
                            <div class="text-success font-weight-bold">Outbound</div>
                          </div>
                          <div class="d-flex justify-content-between mb-3">
                            <div class="font-weight-medium">Current</div>
                            <div class="text-muted">458.77M</div>
                          </div>
                          <div class="d-flex justify-content-between mb-3">
                            <div class="font-weight-medium">Average</div>
                            <div class="text-muted">1.45K</div>
                          </div>
                          <div class="d-flex justify-content-between mb-3">
                            <div class="font-weight-medium">Maximum</div>
                            <div class="text-muted">15.50K</div>
                          </div>
                          <div class="d-flex justify-content-between">
                            <div class="font-weight-medium">60th %</div>
                            <div class="text-muted">45.5</div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-4 d-flex grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <div class="d-flex flex-wrap justify-content-between">
                      <h4 class="card-title mb-3">Sale Analysis Trend</h4>
                    </div>
                    <div class="mt-2">
                      <div class="d-flex justify-content-between">
                        <small>Order Value</small>
                        <small>155.5%</small>
                      </div>
                      <div class="progress progress-md  mt-2">
                        <div class="progress-bar bg-secondary" role="progressbar" style="width: 80%" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                    </div>
                    <div class="mt-4">
                      <div class="d-flex justify-content-between">
                        <small>Total Products</small>
                        <small>238.2%</small>
                      </div>
                      <div class="progress progress-md  mt-2">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 50%" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                    </div>
                    <div class="mt-4 mb-5">
                      <div class="d-flex justify-content-between">
                        <small>Quantity</small>
                        <small>23.30%</small>
                      </div>
                      <div class="progress progress-md mt-2">
                        <div class="progress-bar bg-warning" role="progressbar" style="width: 70%" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                    </div>
                    <canvas id="salesTopChart"></canvas>
                  </div>
                </div>
              </div>
              <div class="col-lg-8 d-flex grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <div class="d-flex flex-wrap justify-content-between">
                      <h4 class="card-title mb-3">Project status</h4>
                    </div>
                    <div class="table-responsive">
                      <table class="table">
                        <tbody>
                          <tr>
                            <td>
                              <div class="d-flex">
                                <img class="img-sm rounded-circle mb-md-0 mr-2" src="images/faces/face30.png" alt="profile image">
                                <div>
                                  <div> Company</div>
                                  <div class="font-weight-bold mt-1">volkswagen</div>
                                </div>
                              </div>
                            </td>
                            <td>
                              Budget
                              <div class="font-weight-bold  mt-1">$2322 </div>
                            </td>
                            <td>
                              Status
                              <div class="font-weight-bold text-success  mt-1">88% </div>
                            </td>
                            <td>
                              Deadline
                              <div class="font-weight-bold  mt-1">07 Nov 2019</div>
                            </td>
                            <td>
                              <button type="button" class="btn btn-sm btn-secondary">edit actions</button>
                            </td>
                          </tr>
                          <tr>
                            <td>
                              <div class="d-flex">
                                <img class="img-sm rounded-circle mb-md-0 mr-2" src="images/faces/face31.png" alt="profile image">
                                <div>
                                  <div> Company</div>
                                  <div class="font-weight-bold  mt-1">Land Rover</div>
                                </div>
                              </div>
                            </td>
                            <td>
                              Budget
                              <div class="font-weight-bold  mt-1">$12022  </div>
                            </td>
                            <td>
                              Status
                              <div class="font-weight-bold text-success  mt-1">70% </div>
                            </td>
                            <td>
                              Deadline
                              <div class="font-weight-bold  mt-1">08 Nov 2019</div>
                            </td>
                            <td>
                              <button type="button" class="btn btn-sm btn-secondary">edit actions</button>
                            </td>
                          </tr>
                          <tr>
                            <td>
                              <div class="d-flex">
                                <img class="img-sm rounded-circle mb-md-0 mr-2" src="images/faces/face32.png" alt="profile image">
                                <div>
                                  <div> Company</div>
                                  <div class="font-weight-bold  mt-1">Bentley </div>
                                </div>
                              </div>
                            </td>
                            <td>
                              Budget
                              <div class="font-weight-bold  mt-1">$8,725</div>
                            </td>
                            <td>
                              Status
                              <div class="font-weight-bold text-success  mt-1">87% </div>
                            </td>
                            <td>
                              Deadline
                              <div class="font-weight-bold  mt-1">11 Jun 2019</div>
                            </td>
                            <td>
                              <button type="button" class="btn btn-sm btn-secondary">edit actions</button>
                            </td>
                          </tr>
                          <tr>
                            <td>
                              <div class="d-flex">
                                <img class="img-sm rounded-circle mb-md-0 mr-2" src="images/faces/face33.png" alt="profile image">
                                <div>
                                  <div> Company</div>
                                  <div class="font-weight-bold  mt-1">Morgan </div>
                                </div>
                              </div>
                            </td>
                            <td>
                              Budget
                              <div class="font-weight-bold  mt-1">$5,220 </div>
                            </td>
                            <td>
                              Status
                              <div class="font-weight-bold text-success  mt-1">65% </div>
                            </td>
                            <td>
                              Deadline
                              <div class="font-weight-bold  mt-1">26 Oct 2019</div>
                            </td>
                            <td>
                              <button type="button" class="btn btn-sm btn-secondary">edit actions</button>
                            </td>
                          </tr>
                          <tr>
                            <td>
                              <div class="d-flex">
                                <img class="img-sm rounded-circle mb-md-0 mr-2" src="images/faces/face34.png" alt="profile image">
                                <div>
                                  <div> Company</div>
                                  <div class="font-weight-bold  mt-1">volkswagen</div>
                                </div>
                              </div>
                            </td>
                            <td>
                              Budget
                              <div class="font-weight-bold  mt-1">$2322 </div>
                            </td>
                            <td>
                              Status
                              <div class="font-weight-bold text-success mt-1">88% </div>
                            </td>
                            <td>
                              Deadline
                              <div class="font-weight-bold  mt-1">07 Nov 2019</div>
                            </td>
                            <td>
                              <button type="button" class="btn btn-sm btn-secondary">edit actions</button>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- content-wrapper ends -->

           <script type="text/javascript" src="scripts/index.js?v=<?php echo(rand()); ?>"></script>
<?php
  require "footer.php";
?>