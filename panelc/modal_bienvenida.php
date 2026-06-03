<?php
ob_start();
session_start();

if (!isset($_SESSION["nombre"])) {
    header("Location: login.php");
} else {
    require 'header.php';
    if ($_SESSION['administrador'] == 1) {
?>

<div class="main-panel">
    <div class="content-wrapper">

        <div class="row">
            <div class="col-lg-8 offset-lg-2 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="card-title mb-0">Modal de Bienvenida</h4>
                            <a href="../index.php" target="_blank" style="padding:8px 16px;background-color:#28a745;color:#fff;border-radius:8px;font-size:13px;text-decoration:none;">
                                <i class="typcn typcn-eye"></i> Ver sitio
                            </a>
                        </div>

                        <!-- Estado habilitado -->
                        <div class="form-group d-flex align-items-center" style="gap:14px;">
                            <label style="margin:0;font-weight:600;">Estado de la modal:</label>
                            <label class="switch-label" style="display:flex;align-items:center;gap:10px;cursor:pointer;margin:0;">
                                <input type="checkbox" id="habilitado" style="width:20px;height:20px;cursor:pointer;">
                                <span id="lbl_habilitado" style="font-size:13px;color:#888;">Deshabilitada</span>
                            </label>
                        </div>

                        <hr>

                        <!-- Título -->
                        <div class="form-group">
                            <label>Título <span style="color:red;">*</span></label>
                            <input type="text" class="form-control" id="titulo" placeholder="Ej. ¡Bienvenido!">
                        </div>

                        <!-- Mensaje -->
                        <div class="form-group">
                            <label>Mensaje de bienvenida <span style="color:red;">*</span></label>
                            <textarea class="form-control" id="mensaje" rows="3" placeholder="Texto que aparece debajo del título, antes de los botones de idioma."></textarea>
                        </div>

                        <hr>
                        <h5 style="margin-bottom:16px;color:#042C49;">URLs de los Videos <small style="font-size:12px;color:#888;font-weight:400;">(URL completa del embed, ej: https://www.youtube.com/embed/VIDEO_ID)</small></h5>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>🇲🇽 Español</label>
                                    <input type="text" class="form-control" id="video_espanol" placeholder="https://www.youtube.com/embed/...">
                                    <div id="prev_espanol" class="mbv_preview" style="display:none;margin-top:8px;border-radius:8px;overflow:hidden;position:relative;padding-top:56.25%;">
                                        <iframe style="position:absolute;top:0;left:0;width:100%;height:100%;border:none;" id="iframe_espanol" allowfullscreen></iframe>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>🇺🇸 Inglés</label>
                                    <input type="text" class="form-control" id="video_ingles" placeholder="https://www.youtube.com/embed/...">
                                    <div id="prev_ingles" class="mbv_preview" style="display:none;margin-top:8px;border-radius:8px;overflow:hidden;position:relative;padding-top:56.25%;">
                                        <iframe style="position:absolute;top:0;left:0;width:100%;height:100%;border:none;" id="iframe_ingles" allowfullscreen></iframe>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>🇰🇷 Coreano</label>
                                    <input type="text" class="form-control" id="video_koreano" placeholder="https://www.youtube.com/embed/...">
                                    <div id="prev_koreano" class="mbv_preview" style="display:none;margin-top:8px;border-radius:8px;overflow:hidden;position:relative;padding-top:56.25%;">
                                        <iframe style="position:absolute;top:0;left:0;width:100%;height:100%;border:none;" id="iframe_koreano" allowfullscreen></iframe>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>🇫🇷 Francés</label>
                                    <input type="text" class="form-control" id="video_frances" placeholder="https://www.youtube.com/embed/...">
                                    <div id="prev_frances" class="mbv_preview" style="display:none;margin-top:8px;border-radius:8px;overflow:hidden;position:relative;padding-top:56.25%;">
                                        <iframe style="position:absolute;top:0;left:0;width:100%;height:100%;border:none;" id="iframe_frances" allowfullscreen></iframe>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div style="text-align:right;margin-top:8px;">
                            <b id="btn_guardar_mbv" onclick="guardar_modal_bv();" style="padding:10px 26px;background-color:#042C49;color:#fff;cursor:pointer;border-radius:10px;display:inline-block;">
                                Guardar cambios
                            </b>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>

    <script type="text/javascript" src="scripts/modal_bienvenida.js?v=<?php echo rand(); ?>"></script>
    <script src="js/dashboard.js"></script>
<?php
    require "footer.php";
?>
<?php
    } else {
        require 'noacceso.php';
    }
}
ob_end_flush();
?>
