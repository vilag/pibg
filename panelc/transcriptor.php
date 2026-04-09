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

    <style>
      .main-panel { flex: 1 1 0 !important; width: auto !important; min-width: 0 !important; margin-left: 0 !important; }

      .tr-wrap { max-width: 760px; margin: 0 auto; padding-bottom: 60px; }

      /* ---- Tarjeta ---- */
      .tr-card {
        background: #fff;
        border: 1px solid #e2e8f4;
        border-radius: 14px;
        padding: 26px 28px;
        margin-bottom: 18px;
      }
      .tr-card-title {
        font-size: .69rem;
        font-weight: 700;
        letter-spacing: 1.8px;
        text-transform: uppercase;
        color: #94a3b8;
        margin-bottom: 20px;
      }

      /* ---- Zona drag & drop ---- */
      #drop_zona {
        border: 2px dashed #c8d8ee;
        border-radius: 14px;
        padding: 36px 20px;
        text-align: center;
        cursor: pointer;
        background: #f8fafd;
        transition: border-color .2s, background .2s;
        user-select: none;
        margin-bottom: 20px;
      }
      #drop_zona:hover, #drop_zona.drag-over {
        border-color: #1D4268;
        background: #eef3fb;
      }
      #drop_zona.tiene-archivo {
        border-color: #1D4268;
        border-style: solid;
        background: #f0f6ff;
      }
      .drop-icono { font-size: 2.4rem; margin-bottom: 8px; }
      .drop-titulo {
        font-size: .95rem;
        font-weight: 700;
        color: #334155;
        margin-bottom: 5px;
      }
      .drop-sub {
        font-size: .78rem;
        color: #94a3b8;
        line-height: 1.5;
      }
      .drop-sub b { color: #475569; }

      /* ---- Info archivo seleccionado ---- */
      #archivo_info {
        display: none;
        align-items: center;
        gap: 14px;
        background: #f0f6ff;
        border: 1px solid #c8dbf5;
        border-radius: 10px;
        padding: 12px 16px;
        margin-bottom: 18px;
      }
      #archivo_icono { font-size: 1.6rem; }
      .archivo-datos { flex: 1; }
      .archivo-datos .nombre {
        font-size: .9rem;
        font-weight: 700;
        color: #1D4268;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 420px;
      }
      .archivo-datos .tamano { font-size: .78rem; color: #94a3b8; }

      /* ---- Controles ---- */
      .tr-label {
        display: block;
        font-size: .82rem;
        font-weight: 600;
        color: #334155;
        margin-bottom: 5px;
      }
      .tr-row { display: flex; gap: 14px; align-items: flex-end; }
      .tr-row > div { flex: 1; }

      .tr-select {
        width: 100%;
        height: 44px;
        border: 1.5px solid #d4dbe8;
        border-radius: 10px;
        padding: 0 14px;
        font-size: .9rem;
        background: #f8fafd;
        color: #1a1a2e;
        outline: none;
        appearance: none;
        cursor: pointer;
        transition: border-color .2s;
        margin-bottom: 0;
      }
      .tr-select:focus { border-color: #1D4268; background: #fff; }

      /* Checkbox timestamps */
      .tr-check-row {
        display: flex;
        align-items: center;
        gap: 8px;
        height: 44px;
      }
      .tr-check-row input[type=checkbox] {
        width: 18px; height: 18px;
        accent-color: #1D4268;
        cursor: pointer;
        flex-shrink: 0;
      }
      .tr-check-row label {
        font-size: .87rem;
        font-weight: 600;
        color: #334155;
        cursor: pointer;
        user-select: none;
      }

      /* ---- Botón principal ---- */
      .tr-btn-main {
        width: 100%;
        height: 50px;
        background: #1D4268;
        color: #fff;
        border: none;
        border-radius: 10px;
        font-size: .95rem;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: background .2s;
        margin-top: 18px;
      }
      .tr-btn-main:hover:not(:disabled) { background: #163454; }
      .tr-btn-main:disabled { opacity: .5; cursor: not-allowed; }

      .tr-spinner {
        display: none;
        width: 19px; height: 19px;
        border: 3px solid rgba(255,255,255,.3);
        border-top-color: #fff;
        border-radius: 50%;
        animation: tr-spin .7s linear infinite;
      }
      @keyframes tr-spin { to { transform: rotate(360deg); } }

      /* ---- Progreso ---- */
      #progreso_box {
        display: none;
        margin-top: 14px;
        background: #f0f6ff;
        border: 1px solid #c8dbf5;
        border-radius: 10px;
        padding: 13px 16px;
        color: #1D4268;
        font-size: .87rem;
        font-weight: 600;
        align-items: center;
        gap: 10px;
      }
      .tr-spin-blue {
        width: 17px; height: 17px;
        border: 3px solid rgba(29,66,104,.2);
        border-top-color: #1D4268;
        border-radius: 50%;
        animation: tr-spin .7s linear infinite;
        flex-shrink: 0;
      }

      /* ---- Error ---- */
      #error_box {
        display: none;
        margin-top: 14px;
        background: #fff5f5;
        border: 1px solid #fca5a5;
        border-radius: 10px;
        padding: 13px 16px;
        color: #b91c1c;
        font-size: .87rem;
        line-height: 1.55;
      }

      /* ---- Aviso (advertencia) ---- */
      #aviso_box {
        display: none;
        margin-top: 14px;
        background: #fffbeb;
        border: 1px solid #fcd34d;
        border-radius: 10px;
        padding: 13px 16px;
        color: #92400e;
        font-size: .87rem;
        line-height: 1.55;
      }

      /* ---- Resultado ---- */
      #resultado_box { display: none; }
      #resultado_texto {
        width: 100%;
        min-height: 200px;
        border: 1.5px solid #d4dbe8;
        border-radius: 10px;
        padding: 14px;
        font-size: .9rem;
        line-height: 1.75;
        color: #1a1a2e;
        resize: vertical;
        background: #f8fafd;
        outline: none;
        transition: border-color .2s;
      }
      #resultado_texto:focus { border-color: #1D4268; background: #fff; }

      .tr-actions { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 12px; }
      .tr-btn-sec {
        height: 38px;
        padding: 0 18px;
        border-radius: 8px;
        border: 1.5px solid #d4dbe8;
        background: #fff;
        color: #334155;
        font-size: .84rem;
        font-weight: 600;
        cursor: pointer;
        transition: background .2s, border-color .2s;
      }
      .tr-btn-sec:hover { background: #f0f4fa; border-color: #1D4268; color: #1D4268; }
      .tr-btn-danger { border-color: #fca5a5; color: #b91c1c; }
      .tr-btn-danger:hover { background: #fff5f5 !important; border-color: #b91c1c !important; }

      /* ---- Segmentos ---- */
      #segmentos_box { display: none; }
      .tr-seg {
        display: flex;
        align-items: baseline;
        gap: 10px;
        padding: 8px 0;
        border-bottom: 1px solid #f0f4fa;
        font-size: .88rem;
      }
      .tr-seg:last-child { border-bottom: none; }
      .tr-seg-ts {
        flex-shrink: 0;
        color: #1D4268;
        font-weight: 700;
        font-size: .74rem;
        font-variant-numeric: tabular-nums;
        background: #eef3fb;
        padding: 2px 8px;
        border-radius: 5px;
        min-width: 44px;
        text-align: center;
      }
      .tr-seg-txt { color: #334155; line-height: 1.6; }

      /* ---- Panel dividir ---- */
      .tr-split-panel {
        display: none;
        margin-top: 10px;
        background: #f8fafd;
        border: 1px solid #e2e8f4;
        border-radius: 10px;
        padding: 14px 16px;
        gap: 14px;
        align-items: center;
        flex-wrap: wrap;
      }
      .tr-split-panel label.tr-label { margin-bottom: 0; white-space: nowrap; }
      .tr-split-panel .tr-select { margin-bottom: 0; width: auto; min-width: 160px; }

      /* ---- Barra de progreso por partes ---- */
      #progreso_partes {
        display: none;
        margin-top: 14px;
        background: #f0f6ff;
        border: 1px solid #c8dbf5;
        border-radius: 10px;
        padding: 14px 16px;
      }
      .tr-partes-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 10px;
        color: #1D4268;
        font-size: .87rem;
        font-weight: 600;
      }
      .tr-barra-wrap {
        background: #dbe9f9;
        border-radius: 20px;
        height: 10px;
        overflow: hidden;
      }
      .tr-barra-fill {
        height: 100%;
        background: #1D4268;
        border-radius: 20px;
        transition: width .4s ease;
        width: 0%;
      }
      .tr-partes-lista {
        margin-top: 10px;
        display: flex;
        flex-direction: column;
        gap: 5px;
        max-height: 180px;
        overflow-y: auto;
      }
      .tr-parte-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: .82rem;
        color: #475569;
      }
      .tr-parte-estado {
        flex-shrink: 0;
        width: 18px; height: 18px;
        border-radius: 50%;
        background: #dbe9f9;
        display: flex; align-items: center; justify-content: center;
        font-size: .65rem;
      }
      .tr-parte-estado.ok   { background: #d1fae5; color: #065f46; }
      .tr-parte-estado.err  { background: #fee2e2; color: #991b1b; }
      .tr-parte-estado.proc { background: #fef9c3; color: #854d0e; animation: tr-spin .9s linear infinite; border: 2px solid #fcd34d; }

      code {
        background: #eef3fb;
        color: #1D4268;
        padding: 1px 7px;
        border-radius: 4px;
        font-size: .82rem;
      }

      @media (max-width: 580px) {
        .tr-card  { padding: 18px 14px; }
        .tr-row   { flex-direction: column; gap: 12px; }
        .archivo-datos .nombre { max-width: 200px; }
      }

      /* ---- Historial de transcripciones ---- */
      .tr-hist-table {
        width: 100%;
        border-collapse: collapse;
        font-size: .84rem;
      }
      .tr-hist-table th {
        text-align: left;
        padding: 8px 10px;
        border-bottom: 2px solid #e2e8f4;
        color: #94a3b8;
        font-weight: 700;
        font-size: .72rem;
        letter-spacing: 1px;
        text-transform: uppercase;
        white-space: nowrap;
      }
      .tr-hist-table td {
        padding: 9px 10px;
        border-bottom: 1px solid #f0f4fa;
        color: #334155;
        vertical-align: middle;
      }
      .tr-hist-table tr:last-child td { border-bottom: none; }
      .tr-hist-table tr:hover td { background: #f8fafd; }
      .tr-hist-nombre {
        max-width: 220px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
      }
      .tr-hist-badge {
        background: #eef3fb;
        color: #1D4268;
        padding: 2px 8px;
        border-radius: 5px;
        font-size: .74rem;
        font-weight: 700;
        text-transform: uppercase;
        display: inline-block;
      }
      .tr-hist-actions { white-space: nowrap; }
      .tr-hist-actions .tr-btn-sec { height: 28px; padding: 0 10px; font-size: .78rem; }
      .tr-hist-empty { color: #94a3b8; font-size: .85rem; text-align: center; padding: 24px 0; }
      .tr-card-title-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 14px;
      }
      .tr-card-title-row .tr-card-title { margin-bottom: 0; }
    </style>

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
