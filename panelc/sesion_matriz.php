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
          <input id="sm_pass_input" type="password" placeholder="Contraseña" style="width:100%; height:44px; border:1.5px solid #d4dbe8; border-radius:10px;
                 padding:0 14px; font-size:.95rem; outline:none; text-align:center;
                 letter-spacing:3px; margin-bottom:14px;" onkeydown="if(event.key==='Enter') sm_verificar();">
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
            success: function (r) {
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
            error: function () {
              var el = document.getElementById('sm_pass_error');
              el.textContent = 'Error al verificar. Intenta de nuevo.';
              el.style.display = 'block';
            }
          });
        }
      </script>

      <link rel="stylesheet" href="css/sesion_matriz.css">

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
                        <input type="number" id="input_num_filas" min="1" max="1000">
                      </div>
                      <div class="sm-field">
                        <label>Dígitos fila</label>
                        <input type="number" id="input_digitos_fila" min="1" max="6" value="2">
                      </div>
                      <div class="sm-field">
                        <label>Columnas</label>
                        <input type="number" id="input_num_columnas" min="1" max="1000">
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
              <input type="text" class="form-control" id="nombre_sesion" style="background-color:#dde4f7ff;"
                placeholder="Ej: Sobres 2026">
            </div>
          </div>
          <div class="col-lg-12" style="float:left;">
            <div class="form-group">
              <label>Descripción <small>(opcional)</small></label>
              <input type="text" class="form-control" id="desc_sesion" style="background-color:#dde4f7ff;" placeholder="">
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
          <a href="#close" style="padding:13px 22px; background:#555; color:#fff; border-radius:8px;"><b>Cancelar</b></a>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/xlsx-js-style@1.2.0/dist/xlsx.bundle.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/exceljs@4.4.0/dist/exceljs.min.js"></script>
        <script type="text/javascript" src="scripts/sesion_matriz.js?v=<?php echo rand(); ?>"></script>

    <script>
      /* ---- Orden de código: opciones según "omitir base" ---- */
      document.addEventListener('DOMContentLoaded', function () {
        var chkOmitir = document.getElementById('chk_omitir_base');
        var inputBase = document.getElementById('input_valor_base');
        var ordenSelect = document.getElementById('input_orden_concat');

        var opcionesCompleto = [
          { v: 'col-fila-base', t: 'Columna + Fila + Base' },
          { v: 'fila-col-base', t: 'Fila + Columna + Base' },
          { v: 'base-col-fila', t: 'Base + Columna + Fila' },
          { v: 'base-fila-col', t: 'Base + Fila + Columna' },
          { v: 'col-base-fila', t: 'Columna + Base + Fila' },
          { v: 'fila-base-col', t: 'Fila + Base + Columna' }
        ];
        var opcionesSinBase = [
          { v: 'col-fila', t: 'Columna + Fila' },
          { v: 'fila-col', t: 'Fila + Columna' }
        ];

        function setOpcionesOrden(soloFilaCol) {
          if (!ordenSelect) return;
          ordenSelect.innerHTML = '';
          var opts = soloFilaCol ? opcionesSinBase : opcionesCompleto;
          opts.forEach(function (opt) {
            var o = document.createElement('option');
            o.value = opt.v;
            o.textContent = opt.t;
            ordenSelect.appendChild(o);
          });
        }
        if (chkOmitir) {
          chkOmitir.addEventListener('change', function () {
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
      listar_sesiones = function () {
        $("#spinner_carga").show();
        $.get("ajax/sesion_matriz.php?op=listar_sesiones", function (data) {
          $("#spinner_carga").hide();
          var lista;
          try { lista = JSON.parse(data); } catch (e) { lista = []; }
          var html = '';
          if (!lista.length) {
            html = '<div class="sm-empty-state">' +
              '<div class="sm-empty-icon">📋</div>' +
              '<p>No hay listas registradas todavía.</p>' +
              '<small>Crea una nueva lista con el botón de arriba.</small>' +
              '</div>';
          } else {
            lista.forEach(function (s) {
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
      toggle_modo_edicion = function () {
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