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

    <link rel="stylesheet" href="css/transcriptor.css">

    <div class="tr-wrap">

      <!-- Formulario principal -->
      <div class="tr-card">
        <div class="tr-card-title">Transcripción de Audio / Video</div>

        <form id="form_transcriptor" autocomplete="off">

          <!-- Zona drag & drop -->
          <div id="drop_zona">
            <div class="drop-icono">🎙️</div>
            <div class="drop-titulo">Arrastra tu archivo aquí o haz clic para seleccionar</div>
            <div class="drop-sub">
              <b>Audio:</b> mp3, m4a, wav, ogg, flac, opus &nbsp;|&nbsp;
              <b>Video:</b> mp4, mov, avi, mkv, webm<br>
              Tamaño máximo: <b>100 MB</b>
            </div>
          </div>
          <input type="file" id="input_archivo" accept=".mp3,.m4a,.wav,.ogg,.flac,.opus,.mpga,.mpeg,.mp4,.mov,.avi,.mkv,.webm" style="display:none;">

          <!-- Info del archivo seleccionado -->
          <div id="archivo_info">
            <span id="archivo_icono">🎵</span>
            <div class="archivo-datos">
              <div class="nombre" id="archivo_nombre"></div>
              <div class="tamano" id="archivo_tamano"></div>
            </div>
          </div>

          <!-- Opciones -->
          <div class="tr-row">
            <div>
              <label class="tr-label" for="select_idioma">Idioma del audio</label>
              <select id="select_idioma" class="tr-select">
                <option value="es">Español</option>
                <option value="en">English</option>
                <option value="fr">Français</option>
                <option value="pt">Português</option>
                <option value="de">Deutsch</option>
                <option value="">Detectar automáticamente</option>
              </select>
            </div>
            <div>
              <label class="tr-label">Opciones</label>
              <div class="tr-check-row">
                <input type="checkbox" id="chk_timestamps">
                <label for="chk_timestamps">Incluir marcas de tiempo</label>
              </div>
              <div class="tr-check-row" style="margin-top:6px;">
                <input type="checkbox" id="chk_dividir">
                <label for="chk_dividir">Dividir en partes <small style="font-weight:400; color:#94a3b8;">(audios largos)</small></label>
              </div>
            </div>
          </div>

          <!-- Panel dividir en partes -->
          <div id="panel_dividir" class="tr-split-panel">
            <label class="tr-label" for="select_duracion">Duración de cada parte:</label>
            <select id="select_duracion" class="tr-select">
              <option value="600"> 10 minutos</option>
              <option value="900" selected>15 minutos</option>
              <option value="1200">20 minutos</option>
              <option value="1800">30 minutos</option>
              <option value="3600">60 minutos</option>
            </select>
            <small style="color:#94a3b8; font-size:.79rem;">El audio se divide en el navegador antes de enviarse.</small>
          </div>

          <button type="submit" class="tr-btn-main" id="btn_transcribir" disabled>
            <div class="tr-spinner" id="transcribir_spinner"></div>
            <span id="transcribir_label">Transcribir</span>
          </button>

        </form>

        <!-- Progreso partes -->
        <div id="progreso_partes">
          <div class="tr-partes-header">
            <div class="tr-spin-blue" id="spin_partes"></div>
            <span id="partes_titulo">Procesando partes…</span>
          </div>
          <div class="tr-barra-wrap"><div class="tr-barra-fill" id="barra_fill"></div></div>
          <div class="tr-partes-lista" id="partes_lista"></div>
        </div>

        <!-- Progreso -->
        <div id="progreso_box">
          <div class="tr-spin-blue"></div>
          <span id="progreso_texto">Subiendo archivo…</span>
        </div>

        <!-- Aviso -->
        <div id="aviso_box">
          <span id="aviso_msg"></span>
        </div>

        <!-- Error -->
        <div id="error_box">
          <span id="error_msg"></span>
        </div>

      </div>

      <!-- Resultado -->
      <div class="tr-card" id="resultado_box">
        <div class="tr-card-title">Resultado</div>
        <textarea id="resultado_texto" spellcheck="false"></textarea>
        <div class="tr-actions">
          <button class="tr-btn-sec" id="btn_copiar">Copiar texto</button>
          <button class="tr-btn-sec" id="btn_descargar">Descargar .txt</button>
          <button class="tr-btn-sec tr-btn-danger" id="btn_limpiar">Borrar y transcribir otro</button>
        </div>
      </div>

      <!-- Segmentos con timestamps -->
      <div class="tr-card" id="segmentos_box">
        <div class="tr-card-title">Segmentos con marcas de tiempo</div>
        <div id="segmentos_lista"></div>
      </div>

      <!-- Historial -->
      <div class="tr-card" id="historial_card">
        <div class="tr-card-title-row">
          <div class="tr-card-title">Historial de transcripciones</div>
          <button class="tr-btn-sec" style="height:28px;padding:0 12px;font-size:.78rem;" onclick="cargar_historial()">&#8635; Actualizar</button>
        </div>
        <div id="historial_tabla_wrap">
          <p class="tr-hist-empty">Cargando&hellip;</p>
        </div>
      </div>

    </div><!-- /tr-wrap -->

  </div><!-- content-wrapper -->

<?php require "footer.php"; ?>

<!-- lamejs: codificador MP3 en el navegador (compresión antes de subir) -->
<script src="https://cdn.jsdelivr.net/npm/lamejs@1.2.1/lame.min.js"></script>
<script type="text/javascript" src="scripts/transcriptor.js?v=<?php echo rand(); ?>"></script>

<?php
else:
    require 'noacceso.php';
endif;

ob_end_flush();
?>
