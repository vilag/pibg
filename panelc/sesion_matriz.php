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
        <div style="font-size:1.05rem; font-weight:700; color:#1a2744; margin-bottom:6px;">Vista protegida</div>
        <div style="font-size:.83rem; color:#64748b; margin-bottom:24px;">Ingresa la contraseña para continuar.</div>
        <input id="sm_pass_input" type="password" placeholder="Contraseña"
          style="width:100%; height:44px; border:1.5px solid #d4dbe8; border-radius:10px;
                 padding:0 14px; font-size:.95rem; outline:none; text-align:center;
                 letter-spacing:3px; margin-bottom:14px;"
          onkeydown="if(event.key==='Enter') sm_verificar();">
        <div id="sm_pass_error" style="display:none; color:#b91c1c; font-size:.82rem; margin-bottom:10px;"></div>
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
      .main-panel {
        flex: 1 1 0 !important;
        width: auto !important;
        min-width: 0 !important;
        margin-left: 0 !important;
      }

      /* ===== SESIONES – tarjetas ===== */
      .sm-page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
      }
      .sm-page-header h4 {
        font-size: 1.2rem;
        font-weight: 700;
        color: #1a2744;
        margin: 0;
      }
      .sm-page-header .sm-subtitle {
        font-size: .82rem;
        color: #94a3b8;
        margin-top: 2px;
      }
      .sm-btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #1D4268;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 9px 18px;
        font-size: .88rem;
        font-weight: 700;
        cursor: pointer;
        text-decoration: none;
        transition: background .2s;
      }
      .sm-btn-primary:hover { background: #15325a; color: #fff; text-decoration: none; }

      .sm-session-card {
        border: 1px solid #e2e8f4;
        border-left: 4px solid #1D4268;
        border-radius: 10px;
        padding: 16px 20px;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 16px;
        cursor: pointer;
        transition: box-shadow .2s, transform .15s;
        background: #fff;
      }
      .sm-session-card:hover {
        box-shadow: 0 4px 18px rgba(29,66,104,.13);
        transform: translateY(-1px);
      }
      .sm-sc-icon {
        width: 44px; height: 44px;
        border-radius: 10px;
        background: #eef3ff;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.3rem;
        flex-shrink: 0;
      }
      .sm-sc-info { flex: 1; min-width: 0; }
      .sm-sc-info h6 {
        margin: 0 0 5px;
        font-size: .97rem;
        font-weight: 700;
        color: #1a2744;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
      }
      .sm-sc-meta {
        display: flex;
        gap: 14px;
        flex-wrap: wrap;
      }
      .sm-sc-badge {
        font-size: .78rem;
        color: #64748b;
        display: flex;
        align-items: center;
        gap: 4px;
      }
      .sm-sc-badge b { color: #1D4268; }
      .sm-sc-actions {
        display: flex;
        gap: 8px;
        flex-shrink: 0;
      }
      .sm-btn-outline {
        border: 1.5px solid #cbd5e1;
        background: #fff;
        color: #475569;
        border-radius: 7px;
        padding: 6px 14px;
        font-size: .8rem;
        font-weight: 600;
        cursor: pointer;
        transition: all .15s;
      }
      .sm-btn-outline:hover { border-color: #1D4268; color: #1D4268; }
      .sm-btn-danger {
        border: 1.5px solid #fca5a5;
        background: #fff;
        color: #dc2626;
        border-radius: 7px;
        padding: 6px 14px;
        font-size: .8rem;
        font-weight: 600;
        cursor: pointer;
        transition: all .15s;
      }
      .sm-btn-danger:hover { background: #fee2e2; }

      .sm-empty-state {
        text-align: center;
        padding: 56px 20px;
        color: #94a3b8;
      }
      .sm-empty-state .sm-empty-icon { font-size: 3rem; margin-bottom: 14px; }
      .sm-empty-state p { margin: 0 0 6px; font-size: .95rem; color: #64748b; }
      .sm-empty-state small { font-size: .82rem; }

      /* ===== VISTA MATRIZ ===== */

      /* Barra superior de navegación */
      .sm-topbar {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
        flex-wrap: wrap;
      }
      .sm-btn-back {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        border: 1.5px solid #cbd5e1;
        background: #fff;
        color: #475569;
        border-radius: 8px;
        padding: 7px 14px;
        font-size: .82rem;
        font-weight: 600;
        cursor: pointer;
        transition: all .15s;
        white-space: nowrap;
      }
      .sm-btn-back:hover { border-color: #1D4268; color: #1D4268; }
      .sm-topbar-sep { color: #cbd5e1; font-size: 1.1rem; }
      .sm-topbar-info { flex: 1; min-width: 0; }
      .sm-topbar-info h5 {
        margin: 0;
        font-size: 1rem;
        font-weight: 700;
        color: #1a2744;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
      }
      .sm-topbar-info small { color: #94a3b8; font-size: .8rem; }
      .sm-btn-export {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #15803d;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 8px 16px;
        font-size: .82rem;
        font-weight: 700;
        cursor: pointer;
        transition: background .2s;
        white-space: nowrap;
        flex-shrink: 0;
      }
      .sm-btn-export:hover { background: #166534; }

      /* Panel colapsable de configuración */
      .sm-config-panel {
        background: #f8fafd;
        border: 1px solid #e2e8f4;
        border-radius: 12px;
        margin-bottom: 14px;
        overflow: hidden;
      }
      .sm-cp-header {
        padding: 13px 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        user-select: none;
        border-bottom: 1px solid transparent;
        transition: border-color .2s;
      }
      .sm-config-panel.sm-open .sm-cp-header {
        border-bottom-color: #e2e8f4;
      }
      .sm-cp-label {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: .88rem;
        font-weight: 700;
        color: #1a2744;
      }
      .sm-cp-label .sm-cp-tag {
        font-size: .72rem;
        background: #eef3ff;
        color: #1D4268;
        border-radius: 5px;
        padding: 2px 8px;
        font-weight: 600;
        letter-spacing: .2px;
      }
      .sm-cp-chevron {
        font-size: .75rem;
        color: #94a3b8;
        transition: transform .25s;
      }
      .sm-config-panel.sm-open .sm-cp-chevron { transform: rotate(180deg); }

      .sm-cp-body {
        display: none;
        padding: 18px 20px 20px;
      }
      .sm-config-panel.sm-open .sm-cp-body { display: block; }

      .sm-fields-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
        gap: 12px 16px;
        margin-bottom: 14px;
      }
      .sm-field {
        display: flex;
        flex-direction: column;
        gap: 5px;
      }
      .sm-field label {
        font-size: .72rem;
        font-weight: 700;
        color: #64748b;
        letter-spacing: .4px;
        text-transform: uppercase;
      }
      .sm-field input[type=number],
      .sm-field select {
        border: 1.5px solid #d1d5db;
        border-radius: 7px;
        padding: 8px 10px;
        font-size: .88rem;
        outline: none;
        background: #fff;
        width: 100%;
        box-sizing: border-box;
        transition: border-color .15s;
      }
      .sm-field input[type=number]:focus,
      .sm-field select:focus { border-color: #1D4268; }
      .sm-field-wide { grid-column: span 2; }

      .sm-field-check {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: .85rem;
        color: #475569;
        padding-top: 6px;
        cursor: pointer;
      }
      .sm-field-check input[type=checkbox] { width: 16px; height: 16px; cursor: pointer; }

      .sm-cp-actions {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        border-top: 1px solid #e2e8f4;
        padding-top: 14px;
        margin-top: 4px;
      }
      .sm-btn-crear {
        background: #1D4268;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 9px 22px;
        font-size: .9rem;
        font-weight: 700;
        cursor: pointer;
        transition: background .2s;
      }
      .sm-btn-crear:hover { background: #15325a; }
      #aviso_manual {
        font-size: .82rem;
      }

      /* Sección de escáner + modo edición */
      .sm-controls-bar {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        background: #fff;
        border: 1.5px solid #e2e8f4;
        border-radius: 12px;
        padding: 14px 18px;
        margin-bottom: 14px;
      }
      .sm-scanner-group {
        display: flex;
        align-items: center;
        gap: 8px;
        flex: 1;
        min-width: 220px;
      }
      .sm-scanner-label {
        font-size: .78rem;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: .4px;
        white-space: nowrap;
      }
      #input_scanner {
        flex: 1;
        min-width: 120px;
        max-width: 200px;
        border: 2px solid #1D4268;
        border-radius: 8px;
        padding: 8px 12px;
        font-size: .95rem;
        font-weight: 600;
        letter-spacing: 2px;
        outline: none;
        transition: box-shadow .15s;
      }
      #input_scanner:focus { box-shadow: 0 0 0 3px rgba(29,66,104,.15); }
      .sm-btn-scan {
        background: #1D4268;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 8px 18px;
        font-size: .88rem;
        font-weight: 700;
        cursor: pointer;
        transition: background .2s;
        white-space: nowrap;
      }
      .sm-btn-scan:hover { background: #15325a; }
      #aviso_scanner { font-size: .8rem; }

      .sm-divider {
        width: 1px;
        height: 32px;
        background: #e2e8f4;
        flex-shrink: 0;
      }

      /* Botón de modo edición (se reutiliza el ID original) */
      #btn_modo_edicion {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: 1.5px solid #cbd5e1;
        background: #f8fafd;
        color: #475569;
        border-radius: 8px;
        padding: 8px 16px;
        font-size: .85rem;
        font-weight: 600;
        cursor: pointer;
        transition: all .2s;
        white-space: nowrap;
      }
      #btn_modo_edicion .sm-dot {
        width: 8px; height: 8px;
        border-radius: 50%;
        background: #94a3b8;
        transition: background .2s;
        flex-shrink: 0;
      }
      #btn_modo_edicion.btn-warning {
        background: #fffbeb;
        border-color: #f59e0b;
        color: #92400e;
      }
      #btn_modo_edicion.btn-warning .sm-dot { background: #f59e0b; }

      /* Tabla */
      #tbl_matriz_wrap {
        overflow-x: auto;
        overflow-y: auto;
        max-height: 520px;
        border-radius: 10px;
        border: 1px solid #e2e8f4;
      }
      #tbl_matriz_wrap.modo-edicion-activo {
        outline: 2px solid #f59e0b;
        border-radius: 10px;
      }
      #tbl_matriz_wrap.modo-edicion-activo .celda-check { cursor: pointer; }
      #tbl_matriz_wrap:not(.modo-edicion-activo) .celda-check { cursor: default; }

      #tbl_matriz {
        border-collapse: collapse;
        min-width: 100%;
        font-size: 12px;
      }
      #tbl_matriz th,
      #tbl_matriz td {
        border: 1px solid #dde4f0;
        text-align: center;
        padding: 4px 6px;
        white-space: nowrap;
      }
      #tbl_matriz thead tr {
        background-color: #1D4268;
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
        background-color: #1D4268;
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

      .celda-check {
        user-select: none;
        min-width: 32px;
      }
      .celda-check.activa {
        background-color: #22c55e !important;
        color: #fff;
      }
      .celda-check.activa::after { content: "✓"; font-weight: bold; }
      .celda-check:not(.activa)::after { content: ""; }

      /* Info config */
      #info_matriz_config {
        font-size: .78rem;
        color: #64748b;
        background: #f1f5f9;
        border-radius: 7px;
        padding: 6px 12px;
        margin-bottom: 10px;
      }

      /* Hint */
      #hint_matriz {
        font-size: .78rem;
        color: #94a3b8;
        margin-top: 8px;
        text-align: center;
      }

      /* Gráficas */
      .sm-charts-grid {
        display: grid;
        grid-template-columns: 220px 1fr;
        gap: 16px;
        margin-top: 24px;
        align-items: start;
      }
      @media (max-width: 700px) {
        .sm-charts-grid { grid-template-columns: 1fr; }
      }
      .sm-chart-card {
        background: #f8fafd;
        border: 1px solid #e2e8f4;
        border-radius: 12px;
        padding: 18px 20px;
      }
      .sm-chart-title {
        font-size: .7rem;
        font-weight: 700;
        letter-spacing: 1.4px;
        text-transform: uppercase;
        color: #94a3b8;
        margin-bottom: 14px;
      }

      /* Spinner */
      #spinner_carga { display: none; }
    </style>

    <div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">

            <!-- ================================================
                 VISTA: LISTA DE SESIONES
            ================================================ -->
            <div id="vista_sesiones">

              <div class="sm-page-header">
                <div>
                  <h4>Registro de sobres</h4>
                  <div class="sm-subtitle">Selecciona una lista para ver o editar su matriz</div>
                </div>
                <a href="#modal_nueva_sesion" class="sm-btn-primary" onclick="abrir_modal_nueva_sesion();">
                  + Nueva lista
                </a>
              </div>

              <div id="spinner_carga" style="text-align:center; padding:24px; color:#94a3b8;">
                Cargando listas...
              </div>

              <div id="lista_sesiones"></div>
            </div>

            <!-- ================================================
                 VISTA: MATRIZ
            ================================================ -->
            <div id="vista_matriz" style="display:none;">

              <!-- Barra de navegación superior -->
              <div class="sm-topbar">
                <button class="sm-btn-back" onclick="regresar_sesiones();">&#8592; Regresar</button>
                <span class="sm-topbar-sep">/</span>
                <div class="sm-topbar-info">
                  <h5 id="titulo_matriz"></h5>
                  <small id="desc_matriz"></small>
                </div>
                <button class="sm-btn-export" onclick="exportar_matriz();">&#8595; Exportar Excel</button>
              </div>

              <!-- Info de configuración actual (se muestra tras crear tabla) -->
              <div id="info_matriz_config" style="display:none;"></div>

              <!-- Panel colapsable: Configuración de tabla -->
              <div class="sm-config-panel" id="sm_config_panel">
                <div class="sm-cp-header" onclick="sm_toggle_config();">
                  <div class="sm-cp-label">
                    &#9881; Configurar tabla
                    <span class="sm-cp-tag">Nueva / Reemplazar</span>
                  </div>
                  <span class="sm-cp-chevron">&#9660;</span>
                </div>
                <div class="sm-cp-body">
                  <div class="sm-fields-grid">
                    <div class="sm-field">
                      <label>Filas</label>
                      <input type="number" id="input_num_filas" min="1" max="1000" placeholder="Ej: 100">
                    </div>
                    <div class="sm-field">
                      <label>Dígitos fila</label>
                      <input type="number" id="input_digitos_fila" min="1" max="6" value="2">
                    </div>
                    <div class="sm-field">
                      <label>Columnas</label>
                      <input type="number" id="input_num_columnas" min="1" max="1000" placeholder="Ej: 52">
                    </div>
                    <div class="sm-field">
                      <label>Dígitos col</label>
                      <input type="number" id="input_digitos_col" min="1" max="6" value="2">
                    </div>
                    <div class="sm-field">
                      <label>Valor base</label>
                      <input type="number" id="input_valor_base" min="0" max="9999" value="0">
                    </div>
                    <div class="sm-field" style="justify-content:flex-end;">
                      <label>&nbsp;</label>
                      <label class="sm-field-check">
                        <input type="checkbox" id="chk_omitir_base"> Omitir base
                      </label>
                    </div>
                    <div class="sm-field sm-field-wide">
                      <label>Orden del código</label>
                      <select id="input_orden_concat">
                        <option value="col-fila-base">Columna + Fila + Base</option>
                        <option value="fila-col-base">Fila + Columna + Base</option>
                        <option value="base-col-fila">Base + Columna + Fila</option>
                        <option value="base-fila-col">Base + Fila + Columna</option>
                        <option value="col-base-fila">Columna + Base + Fila</option>
                        <option value="fila-base-col">Fila + Base + Columna</option>
                      </select>
                    </div>
                  </div>
                  <div class="sm-cp-actions">
                    <button class="sm-btn-crear" type="button" onclick="crear_tabla_manual();">Crear tabla</button>
                    <span id="aviso_manual" style="display:none;"></span>
                  </div>
                </div>
              </div>

              <!-- Cargar desde Excel (oculto, mantenido para compatibilidad) -->
              <div style="display:none;">
                <input type="file" id="input_excel" accept=".xlsx,.xls,.csv" onchange="leer_excel(this)">
              </div>
              <div id="aviso_excel" style="color:#c00; font-size:13px; margin-bottom:8px; display:none;"></div>

              <!-- Barra de escáner + modo edición -->
              <div class="sm-controls-bar">
                <span class="sm-scanner-label">&#128269; Código</span>
                <div class="sm-scanner-group">
                  <input type="text" id="input_scanner" placeholder="Escanear..."
                         onkeydown="if(event.key==='Enter'){buscar_celda();}">
                  <button class="sm-btn-scan" onclick="buscar_celda();">Seleccionar</button>
                </div>
                <span id="aviso_scanner" style="font-size:.8rem; display:none;"></span>

                <div class="sm-divider"></div>

                <button id="btn_modo_edicion" onclick="toggle_modo_edicion();">
                  <span class="sm-dot"></span>
                  <span id="lbl_modo_edicion">Activar edición</span>
                </button>
              </div>

              <!-- Tabla matriz -->
              <div id="tbl_matriz_wrap">
                <table id="tbl_matriz">
                  <thead>
                    <tr id="thead_row">
                      <th>Nombre</th>
                    </tr>
                  </thead>
                  <tbody id="tbody_matriz"></tbody>
                </table>
              </div>

              <div id="hint_matriz">
                Activa el modo edición para marcar / desmarcar celdas con clic.
              </div>

              <!-- Gráficas -->
              <div id="seccion_graficas" class="sm-charts-grid" style="margin-top:24px;">

                <div class="sm-chart-card">
                  <div class="sm-chart-title">Avance general</div>
                  <div style="width:100%; max-width:180px; margin:0 auto;">
                    <canvas id="grafica_general"></canvas>
                  </div>
                  <div id="stats_general" style="font-size:.82rem; color:#334155; line-height:1.9; margin-top:12px;"></div>
                </div>

                <div class="sm-chart-card">
                  <div class="sm-chart-title">Avance por persona</div>
                  <div style="overflow-x:auto;">
                    <canvas id="grafica_personas" style="min-width:400px;"></canvas>
                  </div>
                </div>

              </div>

            </div><!-- /vista_matriz -->

          </div>
        </div>
      </div>
    </div>

  </div><!-- /content-wrapper -->


<!-- ============================================================
     MODAL: Nueva / Editar sesión
============================================================ -->
<div id="modal_nueva_sesion" class="modalmask">
  <div class="modalbox movedown" style="height:360px;">
    <a href="#close" title="Cerrar" class="close">X</a>
    <form id="form_sesion" class="forms-sample" style="padding-top:40px;">
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
  <div class="modalbox movedown" style="height:220px;">
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

<script>
/* ---- Orden de código: opciones según "omitir base" ---- */
document.addEventListener('DOMContentLoaded', function() {
  var chkOmitir  = document.getElementById('chk_omitir_base');
  var inputBase  = document.getElementById('input_valor_base');
  var ordenSelect = document.getElementById('input_orden_concat');

  var opcionesCompleto = [
    {v:'col-fila-base', t:'Columna + Fila + Base'},
    {v:'fila-col-base', t:'Fila + Columna + Base'},
    {v:'base-col-fila', t:'Base + Columna + Fila'},
    {v:'base-fila-col', t:'Base + Fila + Columna'},
    {v:'col-base-fila', t:'Columna + Base + Fila'},
    {v:'fila-base-col', t:'Fila + Base + Columna'}
  ];
  var opcionesSinBase = [
    {v:'col-fila', t:'Columna + Fila'},
    {v:'fila-col', t:'Fila + Columna'}
  ];

  function setOpcionesOrden(soloFilaCol) {
    if (!ordenSelect) return;
    ordenSelect.innerHTML = '';
    var opts = soloFilaCol ? opcionesSinBase : opcionesCompleto;
    opts.forEach(function(opt) {
      var o = document.createElement('option');
      o.value = opt.v;
      o.textContent = opt.t;
      ordenSelect.appendChild(o);
    });
  }
  if (chkOmitir) {
    chkOmitir.addEventListener('change', function() {
      if (inputBase) inputBase.disabled = chkOmitir.checked;
      setOpcionesOrden(chkOmitir.checked);
    });
    setOpcionesOrden(chkOmitir.checked);
  }
});

/* ---- Toggle del panel de configuración ---- */
function sm_toggle_config() {
  var panel = document.getElementById('sm_config_panel');
  panel.classList.toggle('sm-open');
}

/* ---- Render tarjetas de sesión (sobreescribe la función inline del JS) ---- */
/* Se inyecta HTML con clases nuevas desde listar_sesiones() — ver JS abajo ---- */
</script>

<script>
/* Parche: sobreescribir listar_sesiones para usar las nuevas tarjetas */
var _orig_listar = listar_sesiones;
listar_sesiones = function() {
  $("#spinner_carga").show();
  $.get("ajax/sesion_matriz.php?op=listar_sesiones", function(data) {
    $("#spinner_carga").hide();
    var lista;
    try { lista = JSON.parse(data); } catch(e) { lista = []; }
    var html = '';
    if (!lista.length) {
      html = '<div class="sm-empty-state">' +
        '<div class="sm-empty-icon">📋</div>' +
        '<p>No hay listas registradas todavía.</p>' +
        '<small>Crea una nueva lista con el botón de arriba.</small>' +
        '</div>';
    } else {
      lista.forEach(function(s) {
        var desc = s.descripcion ? '<span class="sm-sc-badge">📝 ' + s.descripcion + '</span>' : '';
        html += '<div class="sm-session-card" onclick="abrir_sesion(' + s.idsesion + ',\'' + escapar(s.nombre) + '\',\'' + escapar(s.descripcion) + '\');">' +
          '<div class="sm-sc-icon">📦</div>' +
          '<div class="sm-sc-info">' +
            '<h6>' + s.nombre + '</h6>' +
            '<div class="sm-sc-meta">' +
              '<span class="sm-sc-badge">&#9634; <b>' + s.total_registros + '</b> registros</span>' +
              '<span class="sm-sc-badge">&#128197; ' + s.fecha_creacion + '</span>' +
              desc +
            '</div>' +
          '</div>' +
          '<div class="sm-sc-actions">' +
            '<button class="sm-btn-outline" onclick="editar_sesion(event,' + s.idsesion + ',\'' + escapar(s.nombre) + '\',\'' + escapar(s.descripcion) + '\');">Editar</button>' +
            '<button class="sm-btn-danger" onclick="pedir_eliminar_sesion(event,' + s.idsesion + ');">Eliminar</button>' +
          '</div>' +
          '</div>';
      });
    }
    $("#lista_sesiones").html(html);
  });
};

/* Parche: sobreescribir toggle_modo_edicion para actualizar el label del botón nuevo */
var _orig_toggle = toggle_modo_edicion;
toggle_modo_edicion = function() {
  modo_edicion = !modo_edicion;
  var $btn = $('#btn_modo_edicion');
  var $lbl = $('#lbl_modo_edicion');
  if (modo_edicion) {
    $lbl.text('Desactivar edición');
    $btn.addClass('btn-warning').removeClass('btn-secondary');
    $('#tbl_matriz_wrap').addClass('modo-edicion-activo');
  } else {
    $lbl.text('Activar edición');
    $btn.removeClass('btn-warning').addClass('btn-secondary');
    $('#tbl_matriz_wrap').removeClass('modo-edicion-activo');
  }
};
</script>

<?php
  require "footer.php";
?>
<?php
else:
  require 'noacceso.php';
endif;

ob_end_flush();
?>
