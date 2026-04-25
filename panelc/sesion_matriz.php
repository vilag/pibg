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

    <!-- ====== OVERLAY DE ACCESO ====== -->
    <div id="sm_overlay" style="
        position:fixed; inset:0; z-index:9999;
        background:rgba(15,23,42,.72);
        display:flex; align-items:center; justify-content:center;">
      <div style="
          background:#fff; border-radius:16px;
          padding:40px 36px; width:100%; max-width:360px;
          box-shadow:0 20px 60px rgba(0,0,0,.35); text-align:center;">
        <div style="font-size:2.4rem; margin-bottom:12px;">🔒</div>
        <div style="font-size:1.05rem; font-weight:700; color:#1a2744; margin-bottom:6px;">
          Vista protegida
        </div>
        <div style="font-size:.83rem; color:#64748b; margin-bottom:24px;">
          Ingresa la contraseña para continuar.
        </div>
        <input id="sm_pass_input" type="password" placeholder="Contraseña"
          style="width:100%; height:44px; border:1.5px solid #d4dbe8; border-radius:10px;
                 padding:0 14px; font-size:.95rem; outline:none; text-align:center;
                 letter-spacing:3px; margin-bottom:14px;"
          onkeydown="if(event.key==='Enter') sm_verificar();">
        <div id="sm_pass_error" style="
            display:none; color:#b91c1c; font-size:.82rem; margin-bottom:10px;"></div>
        <button onclick="sm_verificar()" style="
            width:100%; height:44px; background:#1D4268; color:#fff;
            border:none; border-radius:10px; font-size:.92rem; font-weight:700;
            cursor:pointer; margin-bottom:10px;">Acceder</button>
        <button onclick="window.location.href='index.php'" style="
            width:100%; height:40px; background:#fff; color:#64748b;
            border:1.5px solid #d4dbe8; border-radius:10px; font-size:.88rem; font-weight:600;
            cursor:pointer;">Cancelar</button>
      </div>
    </div>
    <script>
    function sm_verificar() {
        var clave = document.getElementById('sm_pass_input').value;
        document.getElementById('sm_pass_error').style.display = 'none';
        $.ajax({
            url: 'ajax/sesion_matriz.php?op=verificar_acceso',
            method: 'POST',
            data: { clave: clave },
            dataType: 'json',
            success: function(r) {
                if (r.ok) {
                    $('#sm_overlay').fadeOut(250);
                } else {
                    var el = document.getElementById('sm_pass_error');
                    el.textContent = r.msg || 'Contraseña incorrecta.';
                    el.style.display = 'block';
                    document.getElementById('sm_pass_input').value = '';
                    document.getElementById('sm_pass_input').focus();
                }
            },
            error: function() {
                var el = document.getElementById('sm_pass_error');
                el.textContent = 'Error al verificar. Intenta de nuevo.';
                el.style.display = 'block';
            }
        });
    }
    </script>

    <style>
      /* El panel ocupa todo el ancho restante después del sidebar */
      .main-panel {
        flex: 1 1 0 !important;
        width: auto !important;
        min-width: 0 !important;
        margin-left: 0 !important;
      }

      /* Modo edición activo: resaltar tabla */
      #tbl_matriz_wrap.modo-edicion-activo {
        outline: 2px solid #f0ad4e;
        border-radius: 6px;
      }
      #tbl_matriz_wrap.modo-edicion-activo .celda-check {
        cursor: pointer;
      }
      #tbl_matriz_wrap:not(.modo-edicion-activo) .celda-check {
        cursor: default;
      }

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
                <h4 class="card-title mb-0">Registro de sobres</h4>
                <a href="#modal_nueva_sesion" class="btn btn-dark btn-sm" onclick="abrir_modal_nueva_sesion();">
                  + Nueva Lista
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
                <span id="desc_matriz" style="color:#888; font-size:13px;"></span>                <button class="btn btn-sm btn-success" style="margin-left:auto;" onclick="exportar_matriz();">&#8595; Exportar Excel</button>              </div>

              <!-- Cargar / reemplazar listado -->
              <div style="display:flex; align-items:center; gap:10px; margin-bottom: 8px; flex-wrap:wrap;">
                <label style="margin:0; font-size:13px; font-weight:600;">Cargar listado (Excel):</label>
                <input type="file" id="input_excel" accept=".xlsx,.xls,.csv"
                       style="font-size:13px;" onchange="leer_excel(this)">
              </div>

              <!-- Crear tabla por número de filas -->
              <div style="display:flex; align-items:center; gap:10px; margin-bottom:14px; flex-wrap:wrap;">
                  <label style="margin:0; font-size:13px; font-weight:600;">O crear tabla con:</label>
                  <input type="number" id="input_num_filas" min="1" max="999" placeholder="Filas" style="width:70px; padding:5px 8px; border:1px solid #ccc; border-radius:6px; font-size:13px;" disabled>
                  <label style="margin:0; font-size:13px;">filas</label>
                  <input type="number" id="input_digitos_fila" min="1" max="6" value="3" placeholder="Dígitos fila" style="width:60px; padding:5px 8px; border:1px solid #ccc; border-radius:6px; font-size:13px;" disabled>
                  <label style="margin:0; font-size:13px;">dígitos fila</label>
                  <input type="number" id="input_num_columnas" min="1" max="52" placeholder="Columnas" style="width:70px; padding:5px 8px; border:1px solid #ccc; border-radius:6px; font-size:13px;" disabled>
                  <label style="margin:0; font-size:13px;">columnas</label>
                  <input type="number" id="input_digitos_col" min="1" max="6" value="2" placeholder="Dígitos col" style="width:60px; padding:5px 8px; border:1px solid #ccc; border-radius:6px; font-size:13px;" disabled>
                  <label style="margin:0; font-size:13px;">dígitos col</label>
                  <input type="number" id="input_valor_base" min="0" max="9999" value="0" placeholder="Valor base" style="width:80px; padding:5px 8px; border:1px solid #ccc; border-radius:6px; font-size:13px;" disabled>
                  <label style="margin:0; font-size:13px;">valor base</label>
                  <select id="input_orden_concat" style="padding:5px 8px; border:1px solid #ccc; border-radius:6px; font-size:13px;" disabled>
                    <option value="col-fila-base">Columna + Fila + Base</option>
                    <option value="fila-col-base">Fila + Columna + Base</option>
                    <option value="base-col-fila">Base + Columna + Fila</option>
                    <option value="base-fila-col">Base + Fila + Columna</option>
                    <option value="col-base-fila">Columna + Base + Fila</option>
                    <option value="fila-base-col">Fila + Base + Columna</option>
                  </select>
                  <label style="margin:0; font-size:13px;">orden código</label>
                  <button class="btn btn-dark btn-sm" onclick="crear_tabla_manual();" id="btn_crear_tabla" disabled>Crear tabla</button>
                  <button class="btn btn-outline-primary btn-sm" type="button" id="btn_toggle_inputs" onclick="toggleInputsManual();">Activar edición</button>
                  <span id="aviso_manual" style="font-size:13px; display:none;"></span>
                  <script>
                  function toggleInputsManual() {
                    var $filas = document.getElementById('input_num_filas');
                    var $cols = document.getElementById('input_num_columnas');
                    var $digFila = document.getElementById('input_digitos_fila');
                    var $digCol = document.getElementById('input_digitos_col');
                    var $valorBase = document.getElementById('input_valor_base');
                    var $ordenConcat = document.getElementById('input_orden_concat');
                    var $crear = document.getElementById('btn_crear_tabla');
                    var $btn = document.getElementById('btn_toggle_inputs');
                    var disabled = $filas.disabled;
                    $filas.disabled = $cols.disabled = $digFila.disabled = $digCol.disabled = $valorBase.disabled = $ordenConcat.disabled = $crear.disabled = !disabled;
                    $btn.textContent = disabled ? 'Desactivar edición' : 'Activar edición';
                    if (!disabled) {
                      $filas.value = '';
                      $cols.value = '';
                      $valorBase.value = '0';
                      $ordenConcat.selectedIndex = 0;
                    }
                  }
                  </script>
              </div>

              <div id="aviso_excel" style="color:#c00; font-size:13px; margin-bottom:8px; display:none;"></div>

              <!-- Escáner / búsqueda de código -->
              <div style="display:flex; align-items:center; gap:10px; margin-bottom:14px; flex-wrap:wrap;">
                <label style="margin:0; font-size:13px; font-weight:600;">Código escaneado:</label>
                <input type="text" id="input_scanner" placeholder=""
                       style="font-size:14px; padding:6px 10px; border:1px solid #ccc; border-radius:6px; width:160px; letter-spacing:2px;"
                       onkeydown="if(event.key==='Enter'){buscar_celda();}">
                <button class="btn btn-dark btn-sm" onclick="buscar_celda();">Seleccionar</button>
                <button id="btn_modo_edicion" class="btn btn-secondary btn-sm" onclick="toggle_modo_edicion();">Activar edición</button>
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

              <div style="margin-top:10px; font-size:12px; color:#888;" id="hint_matriz">
                Activa el modo edición para marcar / desmarcar celdas con clic.
              </div>

              <!-- ===== GRÁFICAS ===== -->
              <div id="seccion_graficas" style="margin-top:28px;">

                <!-- Avance general -->
                <div style="background:#f8fafd; border:1px solid #e2e8f4; border-radius:12px; padding:20px 22px; margin-bottom:20px;">
                  <div style="font-size:.72rem; font-weight:700; letter-spacing:1.6px; text-transform:uppercase; color:#94a3b8; margin-bottom:14px;">Avance general</div>
                  <div style="display:flex; align-items:center; gap:24px; flex-wrap:wrap;">
                    <div style="width:180px; height:180px; flex-shrink:0;">
                      <canvas id="grafica_general"></canvas>
                    </div>
                    <div id="stats_general" style="font-size:.88rem; color:#334155; line-height:2;"></div>
                  </div>
                </div>

                <!-- Avance por persona -->
                <div style="background:#f8fafd; border:1px solid #e2e8f4; border-radius:12px; padding:20px 22px;">
                  <div style="font-size:.72rem; font-weight:700; letter-spacing:1.6px; text-transform:uppercase; color:#94a3b8; margin-bottom:14px;">Avance por persona</div>
                  <div style="overflow-x:auto;">
                    <canvas id="grafica_personas" style="min-width:400px;"></canvas>
                  </div>
                </div>

              </div><!-- /seccion_graficas -->
            </div>

          </div>
        </div>
      </div>
    </div>

  </div><!-- content-wrapper -->


<!-- ============================================================
     MODAL: Nueva / Editar sesión
============================================================ -->
<div id="modal_nueva_sesion" class="modalmask">
  <div class="modalbox movedown" style="height: 360px;">
    <a href="#close" title="Cerrar" class="close">X</a>
    <form id="form_sesion" class="forms-sample" style="padding-top: 40px;">
      <div style="text-align:center; margin-bottom:14px;">
        <b id="titulo_modal_sesion">Nueva Lista</b>
      </div>
      <input type="hidden" id="idsesion_edit" value="0">

      <div class="col-lg-12" style="float:left;">
        <div class="form-group">
          <label>Nombre de lista</label>
          <input type="text" class="form-control" id="nombre_sesion"
                 style="background-color:#dde4f7ff;" placeholder="Ej: Sobres 2026">
        </div>
      </div>

      <div class="col-lg-12" style="float:left;">
        <div class="form-group">
          <label>Descripción <small>(opcional)</small></label>
          <input type="text" class="form-control" id="desc_sesion"
                 style="background-color:#dde4f7ff;" placeholder="">
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
      <p style="font-size:15px;">¿Eliminar esta lista y todos sus registros?</p>
      <input type="hidden" id="idsesion_eliminar" value="0">
      <button type="button"
              style="padding:12px 22px; background:#c0392b; color:#fff; border-radius:8px; margin-right:10px;"
              onclick="confirmar_eliminar_sesion();"><b>Eliminar</b></button>
      <a href="#close"
         style="padding:13px 22px; background:#555; color:#fff; border-radius:8px;"><b>Cancelar</b></a>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/xlsx-js-style@1.2.0/dist/xlsx.bundle.js"></script>
<script src="https://cdn.jsdelivr.net/npm/exceljs@4.4.0/dist/exceljs.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
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
