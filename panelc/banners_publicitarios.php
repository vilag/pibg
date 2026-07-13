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

    <link rel="stylesheet" href="css/banners_publicitarios.css">

    <div class="bp-wrap">

      <!-- Selector de modo -->
      <div class="bp-card" style="margin-bottom:24px;">
        <div class="bp-card-title-row">
          <div class="bp-card-title">¿Cómo quieres crear tu banner?</div>
        </div>
        <div class="bp-modo-row">
          <div class="bp-modo-opcion bp-modo-activa" id="bp_modo_manual_card" onclick="cambiar_modo('manual')">
            <div class="bp-modo-icono">&#9998;</div>
            <div class="bp-modo-titulo">Diseñar manualmente</div>
            <div class="bp-modo-desc">Arma el banner tú mismo: sube tus imágenes, agrega textos y ajusta todo a tu gusto.</div>
          </div>
          <div class="bp-modo-opcion" id="bp_modo_auto_card" onclick="cambiar_modo('auto')">
            <div class="bp-modo-icono">&#10024;</div>
            <div class="bp-modo-titulo">Crear automáticamente con IA</div>
            <div class="bp-modo-desc">Dale el título, mensaje, contacto y tema — la IA busca una imagen, elige un diseño profesional y arma el banner por ti.</div>
          </div>
        </div>
      </div>

      <!-- ═══════════ MODO AUTOMÁTICO ═══════════ -->
      <div class="bp-card" id="bp_seccion_auto" style="display:none;margin-bottom:24px;">
        <div class="bp-card-title-row">
          <div class="bp-card-title">Creación automática con IA</div>
        </div>

        <div class="bp-row">
          <div class="bp-field" style="flex:2;">
            <label>Nombre del banner <span style="color:red;">*</span></label>
            <input type="text" class="form-control" id="bpa_nombre" placeholder="Ej. Promoción Conferencia Jóvenes 2026">
          </div>
          <div class="bp-field" style="flex:2;">
            <label>Tamaño preestablecido</label>
            <select class="form-control" id="bpa_preset" onchange="bpa_aplicar_preset_tamano()">
              <option value="">Personalizado</option>
              <option value="1080|1080|px">Publicación cuadrada (1080 x 1080 px)</option>
              <option value="1080|1920|px">Historia / Story (1080 x 1920 px)</option>
              <option value="1200|628|px">Publicación horizontal (1200 x 628 px)</option>
              <option value="21.6|27.9|cm">Hoja carta / Impresión (21.6 x 27.9 cm)</option>
              <option value="10|15|cm">Postal (10 x 15 cm)</option>
            </select>
          </div>
          <div class="bp-field bp-field-sm">
            <label>Ancho</label>
            <input type="number" class="form-control" id="bpa_ancho" min="1" value="1080">
          </div>
          <div class="bp-field bp-field-sm">
            <label>Alto</label>
            <input type="number" class="form-control" id="bpa_alto" min="1" value="1080">
          </div>
          <div class="bp-field bp-field-sm">
            <label>Unidad</label>
            <select class="form-control" id="bpa_unidad">
              <option value="px">Píxeles</option>
              <option value="cm">Centímetros</option>
            </select>
          </div>
        </div>

        <hr>

        <div class="bp-row">
          <div class="bp-field" style="flex:1;">
            <label>Título</label>
            <input type="text" class="form-control" id="bpa_titulo" placeholder="Ej. Conferencia de Jóvenes 2026">
          </div>
          <div class="bp-field" style="flex:1;">
            <label>Tema / palabras clave <span style="color:red;">*</span></label>
            <input type="text" class="form-control" id="bpa_tema" placeholder="Ej. conferencia de jóvenes, alabanza, retiro navideño">
          </div>
        </div>

        <div class="bp-row">
          <div class="bp-field" style="flex:1;">
            <label>Mensaje</label>
            <textarea class="form-control" id="bpa_mensaje" rows="3" placeholder="Texto con la información que quieras destacar del evento."></textarea>
          </div>
        </div>

        <div class="bp-row">
          <div class="bp-field">
            <label>Teléfono</label>
            <input type="text" class="form-control" id="bpa_telefono" placeholder="(33) 0000-0000">
          </div>
          <div class="bp-field">
            <label>Dirección</label>
            <input type="text" class="form-control" id="bpa_direccion" placeholder="Calle Ejemplo #123, Col. Centro">
          </div>
          <div class="bp-field">
            <label>Correo</label>
            <input type="text" class="form-control" id="bpa_correo" placeholder="contacto@iglesia.org">
          </div>
        </div>

        <div class="bp-row">
          <div class="bp-field">
            <label>Logo (opcional)</label>
            <input type="file" id="bpa_input_logo" accept="image/*">
          </div>
          <div class="bp-field">
            <label>Paleta de color</label>
            <select class="form-control" id="bpa_paleta">
              <option value="">Automática (según el tema)</option>
              <option value="institucional">Institucional (azul)</option>
              <option value="calido_festivo">Cálida / Festiva (rojo y dorado)</option>
              <option value="elegante_oscuro">Elegante oscura (negro y dorado)</option>
              <option value="vibrante_jovenes">Vibrante / Juvenil (morado y naranja)</option>
              <option value="natural_esperanza">Natural (verde y tierra)</option>
            </select>
          </div>
          <div class="bp-field" style="align-self:flex-end;">
            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
              <input type="checkbox" id="bpa_mejorar_textos" style="width:auto;">
              Mejorar textos con IA
            </label>
          </div>
        </div>

        <hr>

        <div class="bp-row" style="align-items:center;">
          <button class="bp-btn bp-btn-primary" onclick="bpa_buscar_imagenes()">Buscar imágenes</button>
          <p class="bp-hint" id="bpa_buscar_status" style="margin:0;"></p>
        </div>

        <div class="bp-picker-grid" id="bpa_picker_grid"></div>

        <div class="bp-row" style="align-items:center;margin-top:14px;">
          <button class="bp-btn bp-btn-primary" id="bpa_btn_generar" onclick="bpa_generar_banner()" disabled>Generar banner</button>
          <p class="bp-hint" id="bpa_generar_status" style="margin:0;"></p>
        </div>
      </div>

      <!-- ═══════════ MODO MANUAL ═══════════ -->
      <div id="bp_seccion_manual">
      <div class="bp-card">
        <div class="bp-card-title-row">
          <div class="bp-card-title" id="bp_form_titulo">Nuevo Banner</div>
          <div style="display:flex;gap:8px;">
            <button id="btn_cancelar_bp" onclick="cancelar_bp();" style="display:none;" class="bp-btn bp-btn-sec">Cancelar / Nuevo</button>
          </div>
        </div>

        <input type="hidden" id="bp_id" value="0">

        <!-- Datos básicos y tamaño -->
        <div class="bp-row">
          <div class="bp-field" style="flex:2;">
            <label>Nombre del banner <span style="color:red;">*</span></label>
            <input type="text" class="form-control" id="bp_nombre" placeholder="Ej. Promoción Conferencia Jóvenes 2026">
          </div>
          <div class="bp-field" style="flex:2;">
            <label>Tamaño preestablecido</label>
            <select class="form-control" id="bp_preset" onchange="aplicar_preset_tamano()">
              <option value="">Personalizado</option>
              <option value="1080|1080|px">Publicación cuadrada (1080 x 1080 px)</option>
              <option value="1080|1920|px">Historia / Story (1080 x 1920 px)</option>
              <option value="1200|628|px">Publicación horizontal (1200 x 628 px)</option>
              <option value="21.6|27.9|cm">Hoja carta / Impresión (21.6 x 27.9 cm)</option>
              <option value="10|15|cm">Postal (10 x 15 cm)</option>
            </select>
          </div>
          <div class="bp-field bp-field-sm">
            <label>Ancho</label>
            <input type="number" class="form-control" id="bp_ancho" min="1" value="1080">
          </div>
          <div class="bp-field bp-field-sm">
            <label>Alto</label>
            <input type="number" class="form-control" id="bp_alto" min="1" value="1080">
          </div>
          <div class="bp-field bp-field-sm">
            <label>Unidad</label>
            <select class="form-control" id="bp_unidad" onchange="cambio_unidad()">
              <option value="px">Píxeles</option>
              <option value="cm">Centímetros</option>
            </select>
          </div>
          <div class="bp-field bp-field-sm" style="align-self:flex-end;">
            <button class="bp-btn bp-btn-primary" onclick="crear_lienzo()">Crear / Redimensionar lienzo</button>
          </div>
        </div>
        <p class="bp-hint" id="bp_hint_cm" style="display:none;">
          La conversión de centímetros a píxeles usa 96 DPI (pantalla). Si el banner es para imprenta, confirma el tamaño final en píxeles con tu proveedor antes de imprimir.
        </p>

        <hr>

        <div class="bp-editor-layout">

          <!-- Toolbox -->
          <div class="bp-toolbox">

            <div class="bp-tool-group">
              <label>Imagen de fondo</label>
              <input type="file" id="bp_input_fondo" accept="image/*" onchange="subir_imagen_fondo(this)">
            </div>

            <div class="bp-tool-group">
              <label>Imagen secundaria (logo, sello, foto, etc.)</label>
              <input type="file" id="bp_input_secundaria" accept="image/*" onchange="subir_imagen_secundaria(this)">
            </div>

            <div class="bp-tool-group">
              <label>Agregar contenido</label>
              <div class="bp-obj-row">
                <button class="bp-btn bp-btn-sec" onclick="agregar_texto('titulo')">+ Título</button>
                <button class="bp-btn bp-btn-sec" onclick="agregar_texto('parrafo')">+ Párrafo</button>
              </div>
              <div class="bp-obj-row">
                <button class="bp-btn bp-btn-sec" onclick="agregar_texto('contacto')">+ Contacto / Dirección</button>
              </div>
            </div>

            <hr>

            <div class="bp-tool-group" id="bp_panel_objeto" style="display:none;">
              <label>Objeto seleccionado</label>
              <div class="bp-obj-row">
                <input type="color" id="bp_obj_color" onchange="cambiar_color_obj(this.value)" title="Color de texto">
                <input type="number" class="form-control" id="bp_obj_fontsize" style="width:75px;" min="6" max="300" onchange="cambiar_fontsize_obj(this.value)" title="Tamaño de fuente">
              </div>
              <div class="bp-obj-row">
                <button class="bp-btn-sec" onclick="obj_negrita()" title="Negrita"><b>N</b></button>
                <button class="bp-btn-sec" onclick="obj_cursiva()" title="Cursiva"><i>I</i></button>
                <button class="bp-btn-sec" onclick="traer_al_frente()" title="Traer al frente">&#8593;</button>
                <button class="bp-btn-sec" onclick="enviar_atras()" title="Enviar atrás">&#8595;</button>
                <button class="bp-btn-sec bp-btn-danger" onclick="eliminar_obj_seleccionado()" title="Eliminar">&times;</button>
              </div>
            </div>

            <hr>

            <div class="bp-tool-group">
              <label>Ajuste con IA</label>
              <textarea class="form-control" id="bp_instruccion_ia" rows="3" placeholder="Ej. Agranda el título, ponlo en rojo y muévelo arriba al centro"></textarea>
              <button class="bp-btn bp-btn-primary" style="width:100%;margin-top:6px;" onclick="aplicar_ajuste_ia()">Aplicar ajuste con IA</button>
              <p class="bp-hint" id="bp_ia_status"></p>
            </div>

            <hr>

            <div class="bp-tool-group">
              <button class="bp-btn bp-btn-sec" style="width:100%;" onclick="descargar_png()">Descargar PNG</button>
              <button class="bp-btn bp-btn-primary" style="width:100%;margin-top:6px;" onclick="guardar_banner()">Guardar banner</button>
            </div>

          </div>

          <!-- Lienzo -->
          <div class="bp-canvas-wrap">
            <canvas id="bp_canvas"></canvas>
          </div>

        </div>
      </div>
      </div><!-- /bp_seccion_manual -->

      <!-- Galería -->
      <div class="bp-card" style="margin-top:24px;">
        <div class="bp-card-title-row">
          <div class="bp-card-title">Banners creados</div>
          <button class="bp-btn-sec" onclick="cargar_galeria()">&#8635; Actualizar</button>
        </div>
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th>Miniatura</th>
                <th>Nombre</th>
                <th>Tamaño</th>
                <th>Creado</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody id="bp_galeria_tbody">
              <tr><td colspan="5" style="text-align:center;color:#aaa;padding:16px;">Cargando&hellip;</td></tr>
            </tbody>
          </table>
        </div>
      </div>

    </div>

  </div><!-- content-wrapper -->

<?php require "footer.php"; ?>

<script src="https://cdn.jsdelivr.net/npm/fabric@5.3.0/dist/fabric.min.js"></script>
<script type="text/javascript" src="scripts/banners_publicitarios.js?v=<?php echo rand(); ?>"></script>

<?php
else:
    require 'noacceso.php';
endif;

ob_end_flush();
