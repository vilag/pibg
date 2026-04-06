<?php
ob_start();
session_start();

if (!isset($_SESSION["nombre"])) {
    header("Location: login.php");
    exit;
}

require 'header.php';

if ($_SESSION['administrador'] == 1):
?>

<div class="main-panel">
  <div class="content-wrapper">
    <link rel="stylesheet" href="css/estilo_bach.css">

    <style>
      /* ---- Tabla matriz ---- */
      #tbl_matriz_wrap {
        overflow-x: auto;
        overflow-y: auto;
        max-height: 520px;
      }
      #tbl_matriz {
        border-collapse: collapse;
        min-width: 100%;
        font-size: 12px;
      }
      #tbl_matriz th,
      #tbl_matriz td {
        border: 1px solid #ccc;
        text-align: center;
        padding: 4px 6px;
        white-space: nowrap;
      }
      #tbl_matriz thead tr {
        background-color: #1f4168;
        color: #fff;
        position: sticky;
        top: 0;
        z-index: 2;
      }
      #tbl_matriz thead th:first-child,
      #tbl_matriz tbody td:first-child {
        position: sticky;
        left: 0;
        z-index: 1;
        min-width: 180px;
        max-width: 220px;
        text-align: left;
        padding-left: 10px;
      }
      #tbl_matriz thead th:first-child {
        background-color: #1f4168;
        z-index: 3;
      }
      #tbl_matriz tbody td:first-child {
        background-color: #f4f7ff;
        font-weight: 600;
        overflow: hidden;
        text-overflow: ellipsis;
      }
      #tbl_matriz tbody tr:hover td { background-color: #eaf0ff; }
      #tbl_matriz tbody tr:hover td:first-child { background-color: #d6e4ff; }

      /* Celda marcada */
      .celda-check {
        cursor: pointer;
        user-select: none;
        min-width: 32px;
      }
      .celda-check.activa {
        background-color: #28a745 !important;
        color: #fff;
      }
      .celda-check.activa::after { content: "✓"; font-weight: bold; }
      .celda-check:not(.activa)::after { content: ""; }

      /* Tarjetas de sesión */
      .card-sesion {
        border: 1px solid #dde4f7;
        border-radius: 8px;
        padding: 14px 18px;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        transition: box-shadow .2s;
      }
      .card-sesion:hover { box-shadow: 0 2px 10px rgba(0,0,0,.12); }
      .card-sesion .info h6 { margin: 0; font-size: 15px; font-weight: 700; }
      .card-sesion .info small { color: #888; }
      .card-sesion .acciones button { margin-left: 6px; }

      /* Spinner carga */
      #spinner_carga { display: none; }
    </style>

    <div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">

            <!-- === VISTA LISTA DE SESIONES === -->
            <div id="vista_sesiones">
              <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 16px;">
                <h4 class="card-title mb-0">Sesiones – Matriz de Asistencia</h4>
                <a href="#modal_nueva_sesion" class="btn btn-dark btn-sm" onclick="abrir_modal_nueva_sesion();">
                  + Nueva Sesión
                </a>
              </div>

              <div id="spinner_carga" style="text-align:center; padding:20px;">
                <span>Cargando...</span>
              </div>

              <div id="lista_sesiones"></div>
            </div>

            <!-- === VISTA MATRIZ === -->
            <div id="vista_matriz" style="display:none;">
              <div style="display:flex; align-items:center; margin-bottom: 16px; gap: 10px; flex-wrap:wrap;">
                <button class="btn btn-sm btn-secondary" onclick="regresar_sesiones();">← Regresar</button>
                <h5 id="titulo_matriz" class="mb-0" style="font-weight:700;"></h5>
                <span id="desc_matriz" style="color:#888; font-size:13px;"></span>
              </div>

              <!-- Cargar / reemplazar listado -->
              <div style="display:flex; align-items:center; gap:10px; margin-bottom: 14px; flex-wrap:wrap;">
                <label style="margin:0; font-size:13px; font-weight:600;">Cargar listado (Excel):</label>
                <input type="file" id="input_excel" accept=".xlsx,.xls,.csv"
                       style="font-size:13px;" onchange="leer_excel(this)">
                <small style="color:#888;">Primera columna = nombres. Fila 1 = encabezado (se omite).</small>
              </div>

              <div id="aviso_excel" style="color:#c00; font-size:13px; margin-bottom:8px; display:none;"></div>

              <!-- Escáner / búsqueda de código -->
              <div style="display:flex; align-items:center; gap:10px; margin-bottom:14px; flex-wrap:wrap;">
                <label style="margin:0; font-size:13px; font-weight:600;">Código escaneado:</label>
                <input type="text" id="input_scanner" placeholder="Ej: 260101"
                       style="font-size:14px; padding:6px 10px; border:1px solid #ccc; border-radius:6px; width:160px; letter-spacing:2px;"
                       onkeydown="if(event.key==='Enter'){buscar_celda();}">
                <button class="btn btn-dark btn-sm" onclick="buscar_celda();">Seleccionar</button>
                <span id="aviso_scanner" style="font-size:13px; display:none;"></span>
              </div>

              <div id="tbl_matriz_wrap">
                <table id="tbl_matriz">
                  <thead>
                    <tr id="thead_row">
                      <th>Nombre</th>
                      <!-- columnas 1-52 generadas desde JS -->
                    </tr>
                  </thead>
                  <tbody id="tbody_matriz"></tbody>
                </table>
              </div>

              <div style="margin-top:10px; font-size:12px; color:#888;">
                Haz clic en una celda para marcar / desmarcar. Los cambios se guardan automáticamente.
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>

  </div><!-- content-wrapper -->
</div><!-- main-panel -->


<!-- ============================================================
     MODAL: Nueva / Editar sesión
============================================================ -->
<div id="modal_nueva_sesion" class="modalmask">
  <div class="modalbox movedown" style="height: 360px;">
    <a href="#close" title="Cerrar" class="close">X</a>
    <form id="form_sesion" class="forms-sample" style="padding-top: 40px;">
      <div style="text-align:center; margin-bottom:14px;">
        <b id="titulo_modal_sesion">Nueva Sesión</b>
      </div>
      <input type="hidden" id="idsesion_edit" value="0">

      <div class="col-lg-12" style="float:left;">
        <div class="form-group">
          <label>Nombre de la Sesión</label>
          <input type="text" class="form-control" id="nombre_sesion"
                 style="background-color:#dde4f7ff;" placeholder="Ej: Asistencia 2026">
        </div>
      </div>

      <div class="col-lg-12" style="float:left;">
        <div class="form-group">
          <label>Descripción <small>(opcional)</small></label>
          <input type="text" class="form-control" id="desc_sesion"
                 style="background-color:#dde4f7ff;" placeholder="Ej: Grupo de jóvenes">
        </div>
      </div>

      <div class="col-lg-12" style="float:left;">
        <div class="form-group" style="display:flex; justify-content:center; gap:10px; margin-top:10px;">
          <button type="button" id="btn_guardar_sesion"
                  style="padding:14px 24px; background:#000; color:#fff; border-radius:10px;"
                  onclick="guardar_sesion();"><b>Guardar</b></button>
          <a href="#close"
             style="padding:15px 24px; background:#000; color:#fff; border-radius:10px;"><b>Cerrar</b></a>
        </div>
      </div>
    </form>
  </div>
</div>


<!-- ============================================================
     MODAL: Confirmar eliminación
============================================================ -->
<div id="modal_confirmar_eliminar" class="modalmask">
  <div class="modalbox movedown" style="height: 220px;">
    <a href="#close" title="Cerrar" class="close">X</a>
    <div style="padding:40px; text-align:center;">
      <p style="font-size:15px;">¿Eliminar esta sesión y todos sus registros?</p>
      <input type="hidden" id="idsesion_eliminar" value="0">
      <button type="button"
              style="padding:12px 22px; background:#c0392b; color:#fff; border-radius:8px; margin-right:10px;"
              onclick="confirmar_eliminar_sesion();"><b>Eliminar</b></button>
      <a href="#close"
         style="padding:13px 22px; background:#555; color:#fff; border-radius:8px;"><b>Cancelar</b></a>
    </div>
  </div>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script type="text/javascript" src="scripts/sesion_matriz.js?v=<?php echo rand(); ?>"></script>

<?php
  require "footer.php";
?>
<?php
else:
  require 'noacceso.php';
endif;

ob_end_flush();
?>
