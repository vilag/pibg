<?php
session_start();
require_once 'config/Conexion.php';   // CWD = panelc/ → panelc/config/Conexion.php

$token = trim($_GET['t'] ?? '');

/* ── Buscar encuesta por token ─────────────────────── */
$encuesta = null;
$preguntas = [];
$error     = '';

if (!$token) {
    $error = 'Enlace no válido.';
} else {
    $t_safe   = $conexion->real_escape_string($token);
    $res_enc  = ejecutarConsulta("SELECT * FROM encuestas WHERE token_publico='$t_safe' AND es_publica=1");
    $encuesta = $res_enc ? $res_enc->fetch_object() : null;

    if (!$encuesta) {
        $error = 'Esta encuesta no está disponible o el enlace es incorrecto.';
    } else {
        $hoy = date('Y-m-d');
        if ($encuesta->fecha_fin && $hoy > $encuesta->fecha_fin) {
            $error = 'Esta encuesta ya no está activa (venció el ' . date('d/m/Y', strtotime($encuesta->fecha_fin)) . ').';
        } elseif ($encuesta->fecha_inicio && $hoy < $encuesta->fecha_inicio) {
            $error = 'Esta encuesta aún no está disponible. Inicia el ' . date('d/m/Y', strtotime($encuesta->fecha_inicio)) . '.';
        } else {
            $res_p = ejecutarConsulta("SELECT * FROM encuesta_preguntas WHERE encuesta_id='{$encuesta->id}' ORDER BY orden ASC");
            while ($p = $res_p->fetch_object()) { $preguntas[] = $p; }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?php echo $encuesta ? htmlspecialchars($encuesta->titulo) : 'Encuesta'; ?></title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <style>
    body { background:#f0f4f8; font-family:'Segoe UI',sans-serif; min-height:100vh; }
    .enc-wrap { max-width:700px; margin:40px auto; padding:0 16px 60px; }
    .enc-header { background:#042C49; color:#fff; border-radius:14px 14px 0 0; padding:28px 30px 20px; }
    .enc-header h2 { margin:0; font-size:22px; font-weight:700; }
    .enc-header p  { margin:8px 0 0; opacity:.82; font-size:14px; }
    .enc-body { background:#fff; border-radius:0 0 14px 14px; padding:30px; box-shadow:0 4px 24px rgba(0,0,0,.1); }
    .preg-card { border:1px solid #e9ecef; border-radius:10px; padding:20px; margin-bottom:18px; }
    .preg-num  { font-size:12px; font-weight:700; color:#042C49; text-transform:uppercase; letter-spacing:.5px; }
    .preg-text { font-size:16px; font-weight:600; margin:6px 0 14px; }
    .preg-img  { width:100%; max-height:220px; object-fit:cover; border-radius:8px; margin-bottom:14px; }
    .preg-req  { color:#dc3545; font-size:12px; margin-left:4px; }
    .opt-label { display:flex; align-items:center; gap:10px; padding:10px 14px; border:1px solid #dee2e6; border-radius:8px;
                 margin-bottom:8px; cursor:pointer; font-size:14px; transition:border-color .15s,background .15s; }
    .opt-label:hover { border-color:#042C49; background:#f0f7ff; }
    .opt-label input:checked ~ span { font-weight:600; color:#042C49; }
    .opt-label input { accent-color:#042C49; }
    .star-row { display:flex; gap:8px; }
    .star-btn  { font-size:28px; cursor:pointer; color:#dee2e6; transition:color .15s; line-height:1; background:none; border:none; padding:0; }
    .star-btn.active { color:#f5a623; }
    textarea.form-control { border-radius:8px; font-size:14px; }
    .btn-enviar { background:#042C49; color:#fff; border:none; padding:13px 36px; border-radius:10px; font-size:15px; font-weight:600; cursor:pointer; }
    .btn-enviar:hover { opacity:.88; }
    .enc-success { text-align:center; padding:50px 20px; }
    .enc-success .icon { font-size:64px; margin-bottom:16px; }
    .enc-error   { text-align:center; padding:60px 20px; }
    .enc-error .icon { font-size:60px; margin-bottom:16px; }
  </style>
</head>
<body>
<div class="enc-wrap">

<?php if ($error): ?>
  <div class="enc-header"><h2>Encuesta</h2></div>
  <div class="enc-body">
    <div class="enc-error">
      <div class="icon">⚠️</div>
      <h4 style="color:#dc3545;"><?php echo htmlspecialchars($error); ?></h4>
    </div>
  </div>

<?php elseif (isset($_GET['ok'])): ?>
  <div class="enc-header"><h2><?php echo htmlspecialchars($encuesta->titulo); ?></h2></div>
  <div class="enc-body">
    <div class="enc-success">
      <div class="icon">✅</div>
      <h4>¡Gracias por tu respuesta!</h4>
      <p class="text-muted">Tu respuesta ha sido registrada exitosamente.</p>
    </div>
  </div>

<?php else: ?>
  <div class="enc-header">
    <h2><?php echo htmlspecialchars($encuesta->titulo); ?></h2>
    <?php if ($encuesta->descripcion): ?>
      <p><?php echo htmlspecialchars($encuesta->descripcion); ?></p>
    <?php endif; ?>
    <?php if ($encuesta->fecha_fin): ?>
      <p style="font-size:12px;opacity:.7;margin-top:8px;">Vigencia hasta: <?php echo date('d/m/Y', strtotime($encuesta->fecha_fin)); ?></p>
    <?php endif; ?>
  </div>

  <div class="enc-body">
    <div id="enc_form_area">

      <?php foreach ($preguntas as $i => $p): ?>
      <div class="preg-card">
        <div class="preg-num">Pregunta <?php echo $i + 1; ?><?php if ($p->requerida): ?><span class="preg-req">*</span><?php endif; ?></div>
        <div class="preg-text"><?php echo htmlspecialchars($p->pregunta); ?></div>

        <?php if ($p->imagen_base64): ?>
          <img class="preg-img" src="<?php echo $p->imagen_base64; ?>" alt="">
        <?php endif; ?>

        <?php
          $name = 'resp[' . $p->id . ']';
          switch ($p->tipo):

            case 'libre': ?>
          <textarea class="form-control" name="<?php echo $name; ?>" rows="3"
            placeholder="Escribe tu respuesta..."
            <?php if ($p->requerida) echo 'data-req="1"'; ?>></textarea>

            <?php break;
            case 'opcion_multiple':
              $opts = $p->opciones ? json_decode($p->opciones, true) : [];
              foreach ($opts as $op): ?>
          <label class="opt-label">
            <input type="radio" name="<?php echo $name; ?>" value="<?php echo htmlspecialchars($op, ENT_QUOTES); ?>"
              <?php if ($p->requerida) echo 'data-req="1"'; ?>>
            <span><?php echo htmlspecialchars($op); ?></span>
          </label>
            <?php endforeach; break;

            case 'casillas':
              $opts = $p->opciones ? json_decode($p->opciones, true) : [];
              foreach ($opts as $op): ?>
          <label class="opt-label">
            <input type="checkbox" name="<?php echo 'resp[' . $p->id . '][]'; ?>" value="<?php echo htmlspecialchars($op, ENT_QUOTES); ?>">
            <span><?php echo htmlspecialchars($op); ?></span>
          </label>
            <?php endforeach; break;

            case 'verdadero_falso': ?>
          <?php foreach (['Verdadero','Falso'] as $op): ?>
          <label class="opt-label">
            <input type="radio" name="<?php echo $name; ?>" value="<?php echo $op; ?>"
              <?php if ($p->requerida) echo 'data-req="1"'; ?>>
            <span><?php echo $op; ?></span>
          </label>
          <?php endforeach; break;

            case 'si_no': ?>
          <?php foreach (['Sí','No'] as $op): ?>
          <label class="opt-label">
            <input type="radio" name="<?php echo $name; ?>" value="<?php echo $op; ?>"
              <?php if ($p->requerida) echo 'data-req="1"'; ?>>
            <span><?php echo $op; ?></span>
          </label>
          <?php endforeach; break;

            case 'calificacion': ?>
          <div class="star-row" data-pregunta="<?php echo $p->id; ?>">
            <?php for ($s = 1; $s <= 5; $s++): ?>
            <button type="button" class="star-btn" data-val="<?php echo $s; ?>">★</button>
            <?php endfor; ?>
          </div>
          <input type="hidden" name="<?php echo $name; ?>" class="star-val"
            <?php if ($p->requerida) echo 'data-req="1"'; ?>>
            <?php break;
          endswitch; ?>
      </div>
      <?php endforeach; ?>

      <div id="enc_error_msg" style="display:none;color:#dc3545;margin-bottom:12px;font-size:14px;"></div>
      <button type="button" class="btn-enviar" onclick="enviar_encuesta();">Enviar respuestas</button>

    </div><!-- /enc_form_area -->
  </div><!-- /enc-body -->
<?php endif; ?>

</div><!-- /enc-wrap -->

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
'use strict';

// ── Estrellas ──
$(document).on('click', '.star-btn', function () {
    var val  = $(this).data('val');
    var $row = $(this).closest('.star-row');
    $row.find('.star-btn').each(function () {
        $(this).toggleClass('active', $(this).data('val') <= val);
    });
    $row.siblings('.star-val').val(val);
});

// ── Enviar ──
function enviar_encuesta() {
    var encuesta_id = <?php echo $encuesta ? $encuesta->id : 0; ?>;
    var respuestas  = {};
    var valido      = true;
    var primer_err  = '';

    // Recolectar valores
    <?php foreach ($preguntas as $p): ?>
    (function () {
        var pid = <?php echo $p->id; ?>;
        var tipo = '<?php echo $p->tipo; ?>';
        var req  = <?php echo $p->requerida ? 'true' : 'false'; ?>;
        var val  = null;

        if (tipo === 'casillas') {
            var checked = [];
            $('input[name="resp[' + pid + '][]"]:checked').each(function () { checked.push($(this).val()); });
            val = checked;
            if (req && !checked.length) { valido = false; primer_err = primer_err || 'Completa la pregunta <?php echo $i+1; ?>.'; }
        } else if (tipo === 'libre') {
            val = $('textarea[name="resp[' + pid + ']"]').val().trim();
            if (req && !val) { valido = false; primer_err = primer_err || 'Completa la pregunta <?php echo $p->orden; ?>.'; }
        } else {
            val = $('[name="resp[' + pid + ']"]:checked').val() || $('[name="resp[' + pid + ']"]').val();
            if (req && !val) { valido = false; primer_err = primer_err || 'Completa la pregunta <?php echo $p->orden; ?>.'; }
        }
        if (val !== null) respuestas[pid] = val;
    })();
    <?php endforeach; ?>

    if (!valido) {
        $('#enc_error_msg').text(primer_err || 'Por favor completa todos los campos requeridos.').show();
        return;
    }
    $('#enc_error_msg').hide();

    $.post('ajax/encuestas.php?op=guardar_respuesta', {
        encuesta_id: encuesta_id,
        respuestas:  respuestas,
    }, function (data) {
        data = JSON.parse(data);
        if (data.ok) {
            window.location.href = '?t=<?php echo urlencode($token); ?>&ok=1';
        } else {
            $('#enc_error_msg').text('Error al enviar. Intenta de nuevo.').show();
        }
    });
}
</script>
</body>
</html>
