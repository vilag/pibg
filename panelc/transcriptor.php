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

      /* ---- Contenedor ---- */
      .tr-wrap { max-width: 820px; margin: 0 auto; padding-bottom: 60px; }

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
        margin-bottom: 18px;
      }

      /* ---- Instrucciones ---- */
      .tr-step {
        display: flex;
        gap: 14px;
        align-items: flex-start;
        padding: 9px 0;
        border-bottom: 1px solid #f0f4fa;
        font-size: .87rem;
        color: #475569;
        line-height: 1.5;
      }
      .tr-step:last-child { border-bottom: none; }
      .tr-step-num {
        flex-shrink: 0;
        width: 26px; height: 26px;
        background: #1D4268;
        color: #fff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .76rem;
        font-weight: 700;
        margin-top: 1px;
      }
      code {
        background: #eef3fb;
        padding: 1px 7px;
        border-radius: 4px;
        font-size: .82rem;
        color: #1D4268;
      }

      /* ---- Preview ---- */
      #preview_wrapper { display: none; margin-bottom: 4px; }
      #yt_preview {
        width: 100%;
        aspect-ratio: 16/9;
        border-radius: 10px;
        border: none;
        background: #000;
      }

      /* ---- Formulario ---- */
      .tr-label {
        display: block;
        font-size: .82rem;
        font-weight: 600;
        color: #334155;
        margin-bottom: 5px;
      }
      .tr-label small { font-weight: 400; color: #94a3b8; }

      .tr-input, .tr-select {
        width: 100%;
        height: 44px;
        border: 1.5px solid #d4dbe8;
        border-radius: 10px;
        padding: 0 14px;
        font-size: .9rem;
        background: #f8fafd;
        color: #1a1a2e;
        outline: none;
        transition: border-color .2s, background .2s;
        margin-bottom: 14px;
        appearance: none;
      }
      .tr-input:focus, .tr-select:focus { border-color: #1D4268; background: #fff; }
      .tr-input::placeholder { color: #b0bec5; }

      .tr-row { display: flex; gap: 14px; }
      .tr-row > div { flex: 1; }

      /* ---- Botón principal ---- */
      .tr-btn-main {
        width: 100%;
        height: 48px;
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
        margin-top: 4px;
      }
      .tr-btn-main:hover:not(:disabled) { background: #163454; }
      .tr-btn-main:disabled { opacity: .55; cursor: not-allowed; }

      .tr-spinner {
        display: none;
        width: 18px; height: 18px;
        border: 3px solid rgba(255,255,255,.35);
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
      .tr-spinner-blue {
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

      /* ---- Idiomas disponibles ---- */
      #idiomas_disponibles {
        display: none;
        margin-top: 10px;
        font-size: .85rem;
        color: #475569;
      }
      .tr-tag {
        display: inline-block;
        background: #eef3fb;
        color: #1D4268;
        border-radius: 6px;
        padding: 2px 9px;
        font-size: .78rem;
        font-weight: 600;
        margin: 2px 3px 2px 0;
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
        line-height: 1.7;
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
      .tr-btn-danger { border-color: #fca5a5 !important; color: #b91c1c !important; }
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
        font-size: .75rem;
        font-variant-numeric: tabular-nums;
        background: #eef3fb;
        padding: 2px 7px;
        border-radius: 5px;
        min-width: 44px;
        text-align: center;
      }
      .tr-seg-txt { color: #334155; line-height: 1.55; }

      /* ---- Responsive ---- */
      @media (max-width: 600px) {
        .tr-card  { padding: 18px 14px; }
        .tr-row   { flex-direction: column; gap: 0; }
      }
    </style>

    <div class="tr-wrap">

      <!-- Instrucciones -->
      <div class="tr-card">
        <div class="tr-card-title">Cómo funciona</div>
        <div class="tr-step">
          <div class="tr-step-num">1</div>
          <div>Pega la URL del video de YouTube. Funciona con videos que tengan <b>subtítulos activados</b> (automáticos o manuales).</div>
        </div>
        <div class="tr-step">
          <div class="tr-step-num">2</div>
          <div>Indica el tiempo de inicio y fin en formato <code>mm:ss</code> o <code>h:mm:ss</code>. Deja el fin en blanco para transcribir hasta el final del video.</div>
        </div>
        <div class="tr-step">
          <div class="tr-step-num">3</div>
          <div>Selecciona el idioma del video (o déjalo en <b>Español</b>). Si el video tiene subtítulos en otros idiomas, se mostrarán los disponibles.</div>
        </div>
        <div class="tr-step">
          <div class="tr-step-num">4</div>
          <div>Presiona <b>Obtener transcripción</b>. El resultado será editable y podrás copiarlo o descargarlo como <code>.txt</code>.</div>
        </div>
      </div>

      <!-- Formulario -->
      <div class="tr-card">
        <div class="tr-card-title">Nueva Transcripción</div>

        <div id="preview_wrapper">
          <iframe id="yt_preview" allowfullscreen></iframe>
          <div style="height:12px;"></div>
        </div>

        <form id="form_transcriptor" autocomplete="off">

          <label class="tr-label" for="url_youtube">URL del video de YouTube</label>
          <input type="url" id="url_youtube" class="tr-input"
                 placeholder="https://www.youtube.com/watch?v=…">

          <div class="tr-row">
            <div>
              <label class="tr-label" for="tiempo_inicio">
                Inicio <small>(mm:ss o h:mm:ss)</small>
              </label>
              <input type="text" id="tiempo_inicio" class="tr-input" placeholder="0:00">
            </div>
            <div>
              <label class="tr-label" for="tiempo_fin">
                Fin <small>(vacío = hasta el final)</small>
              </label>
              <input type="text" id="tiempo_fin" class="tr-input" placeholder="5:00">
            </div>
            <div>
              <label class="tr-label" for="select_idioma">Idioma del video</label>
              <select id="select_idioma" class="tr-select">
                <option value="es">Español</option>
                <option value="en">English</option>
                <option value="fr">Français</option>
                <option value="pt">Português</option>
                <option value="de">Deutsch</option>
              </select>
            </div>
          </div>

          <button type="submit" class="tr-btn-main" id="btn_transcribir">
            <div class="tr-spinner" id="transcribir_spinner"></div>
            <span id="transcribir_label">Obtener transcripción</span>
          </button>

        </form>

        <!-- Progreso -->
        <div id="progreso_box" style="display:flex;">
          <div class="tr-spinner-blue"></div>
          <span>Obteniendo subtítulos de YouTube…</span>
        </div>

        <!-- Error -->
        <div id="error_box">
          <span id="error_msg"></span>
          <div id="idiomas_disponibles"><div id="idiomas_txt"></div></div>
        </div>

      </div>

      <!-- Resultado -->
      <div class="tr-card" id="resultado_box">
        <div class="tr-card-title">Transcripción</div>
        <textarea id="resultado_texto" spellcheck="false"></textarea>
        <div id="idiomas_disponibles" style="margin-top:10px;display:none;">
          <div id="idiomas_txt"></div>
        </div>
        <div class="tr-actions">
          <button class="tr-btn-sec" id="btn_copiar">Copiar texto</button>
          <button class="tr-btn-sec" id="btn_descargar">Descargar .txt</button>
          <button class="tr-btn-sec tr-btn-danger" id="btn_limpiar">Borrar</button>
        </div>
      </div>

      <!-- Segmentos con timestamps -->
      <div class="tr-card" id="segmentos_box">
        <div class="tr-card-title">Segmentos con marcas de tiempo</div>
        <div id="segmentos_lista"></div>
      </div>

    </div><!-- /tr-wrap -->

  </div><!-- content-wrapper -->

<?php require "footer.php"; ?>

<script type="text/javascript" src="scripts/transcriptor.js?v=<?php echo rand(); ?>"></script>

<?php
else:
    require 'noacceso.php';
endif;

ob_end_flush();
?>
