<?php $academia_instrumentos = require 'config/academia_instrumentos.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
<link rel="manifest" href="manifest.webmanifest">
<meta name="theme-color" content="#F2125E">
<link rel="apple-touch-icon" href="images/icons/apple-touch-icon.png">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="apple-mobile-web-app-title" content="PIBG">
<script>
(function(){
	var esApp = false;
	try { if (window.Capacitor && typeof window.Capacitor.isNativePlatform === 'function' && window.Capacitor.isNativePlatform()) esApp = true; } catch(e){}
	if (!esApp && window.matchMedia && window.matchMedia('(display-mode: standalone)').matches) esApp = true;
	if (!esApp && window.navigator && window.navigator.standalone === true) esApp = true;
	if (esApp) document.documentElement.className += ' pibg-app-instalada';
})();
</script>
<title>Academia Coré | Primera Iglesia Bautista de Guadalajara</title>
<link href="images/iconos/icono.png" rel="icon">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="Academia Coré - Academia de música de la Primera Iglesia Bautista de Guadalajara, abierta a cualquier persona interesada en aprender música.">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="styles/bootstrap4/bootstrap.min.css">
<link href="plugins/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link href="plugins/video-js/video-js.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="styles/news.css">
<link rel="stylesheet" type="text/css" href="styles/news_responsive.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.26.0/moment.min.js"></script>
<script src="js/jquery-3.2.1.min.js"></script>
<link rel="stylesheet" type="text/css" href="styles/academia_core.css">
</head>
<body>

<div class="super_container">

  <?php require 'header.php'; ?>

  <!-- Hero -->
  <div class="aca-hero">
    <div class="aca-hero__bg"></div>
    <div class="aca-hero__content">
      <img src="images/logos/academia_core_logo_blanco.png" alt="Academia Coré - Academia de Música de la Primera Iglesia Bautista de Guadalajara" class="aca-hero__logo">
      <p class="aca-hero__subtitle">
        Academia de música de la Primera Iglesia Bautista de Guadalajara, para cualquier persona que le interese aprender música.
      </p>
      <ul class="aca-hero__breadcrumb">
        <li><a href="./">Inicio</a></li>
        <li>Academia Coré</li>
      </ul>
    </div>
  </div>

  <!-- Quiénes somos -->
  <div class="aca-sec">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-6 mb-4 mb-lg-0">
          <div class="aca-sec__tag">Quiénes somos</div>
          <h2 class="aca-sec__title">Aprende música<br>en un ambiente de fe</h2>
          <p class="aca-sec__text">
            <strong>Academia Coré</strong> es la academia de música de la Primera Iglesia Bautista de Guadalajara, abierta a cualquier persona que le interese aprender música, sin importar su edad o experiencia previa.
          </p>
          <p class="aca-sec__text">
            Contamos con maestros dedicados que enseñan con paciencia y pasión, y con la oportunidad de poner en práctica lo aprendido formando parte de nuestro coro.
          </p>
        </div>
        <div class="col-lg-6">
          <img src="images/ministerios/core.png" alt="Academia Coré" class="aca-sec__img">
        </div>
      </div>
    </div>
  </div>

  <!-- Lo que ofrecemos -->
  <div class="aca-instrumentos-sec">
    <div class="aca-instrumentos-sec__bg"></div>
    <div class="container">
      <p class="aca-sec__tag aca-instrumentos-sec__tag">Lo que ofrecemos</p>
      <h2 class="aca-sec__title aca-instrumentos-sec__title">Instrumentos y clases disponibles</h2>

      <div class="aca-instrumentos-box">
        <div class="aca-instrumentos">
          <?php foreach ($academia_instrumentos as $aca_inst): ?>
            <span class="aca-instrumento"><?php echo htmlspecialchars($aca_inst); ?></span>
          <?php endforeach; ?>
        </div>
      </div>

      <p class="aca-instrumentos-sec__text">
        Además de las clases individuales, los alumnos tienen la oportunidad de formar parte de nuestro <strong>coro</strong>, acompañados siempre por <strong>maestros dedicados</strong> a su proceso de aprendizaje.
      </p>
    </div>
  </div>

  <!-- Versículo -->
  <div class="aca-versiculo">
    <div class="container">
      <p class="aca-versiculo__text">
        "Cantad alegres a Dios, habitantes de toda la tierra. Servid a Jehová con alegría; venid ante su presencia con regocijo."
      </p>
      <div class="aca-versiculo__ref">Salmos 100:1-2</div>
    </div>
  </div>

  <!-- Instagram CTA -->
  <div class="aca-ig-sec">
    <div class="container">
      <div class="aca-ig-sec__icon"><i class="fa fa-instagram"></i></div>
      <div class="aca-ig-sec__title">Síguenos en Instagram</div>
      <p class="aca-ig-sec__sub">
        Entérate de nuestras clases, presentaciones y actividades en la cuenta oficial de Academia Coré.
      </p>
      <a href="https://www.instagram.com/academia_de_musica_core/" target="_blank" class="aca-btn aca-btn--white">
        <i class="fa fa-instagram"></i>@academia_de_musica_core
      </a>
    </div>
  </div>

  <!-- CTA final -->
  <div class="aca-cta">
    <div class="container">
      <img src="images/logos/academia_core_logo.png" alt="Academia Coré" class="aca-cta__logo">
      <div class="aca-sec__tag" style="text-align:center; display:block;">¿Te gustaría aprender música?</div>
      <div class="aca-cta__title">Inscríbete a Academia Coré</div>
      <p class="aca-cta__text">
        Escríbenos y con gusto te compartimos información sobre horarios, costos e inscripciones.
      </p>
      <div class="aca-contacto">
        <a href="https://api.whatsapp.com/send?phone=3330230905"><i class="fa fa-whatsapp"></i> (33) 30230905</a>
        <a href="tel:+523336144120"><i class="fa fa-phone"></i> (33) 36144120</a>
        <a href="mailto:pibgdlar@gmail.com"><i class="fa fa-envelope"></i> pibgdlar@gmail.com</a>
      </div>

      <!-- Formulario: solicitar informes -->
      <div class="aca-form-wrap">
        <h3 class="aca-form__title">Solicita informes</h3>
        <p class="aca-form__subtitle">Déjanos tus datos y el instrumento que te interesa; te contactaremos con gusto.</p>

        <!-- Honeypot anti-spam: campo oculto que un humano nunca llena -->
        <input type="text" id="aca_web" name="aca_web" class="aca-form__honeypot" tabindex="-1" autocomplete="off" aria-hidden="true">

        <div class="aca-form__row">
          <div class="aca-form__group">
            <label for="aca_nombre">Nombre</label>
            <input type="text" id="aca_nombre" class="aca-form__input" placeholder="Tu nombre">
          </div>
          <div class="aca-form__group">
            <label for="aca_correo">Correo</label>
            <input type="email" id="aca_correo" class="aca-form__input" placeholder="tu@correo.com">
          </div>
        </div>
        <div class="aca-form__row">
          <div class="aca-form__group aca-form__group--half">
            <label for="aca_telefono">Teléfono</label>
            <input type="tel" id="aca_telefono" class="aca-form__input" placeholder="10 dígitos">
          </div>
        </div>

        <div class="aca-form__group">
          <label>¿Qué instrumento(s) te interesa aprender?</label>
          <div class="aca-form__checks">
            <?php foreach ($academia_instrumentos as $aca_inst): ?>
              <label class="aca-form__check">
                <input type="checkbox" name="aca_instrumento" value="<?php echo htmlspecialchars($aca_inst); ?>">
                <?php echo htmlspecialchars($aca_inst); ?>
              </label>
            <?php endforeach; ?>
          </div>
        </div>

        <button type="button" class="aca-btn aca-btn--solid" id="aca_form_btn" onclick="academia_enviar_solicitud();">
          <i class="fa fa-paper-plane"></i> Solicitar informes
        </button>
        <div id="aca_form_resultado" class="aca-form__resultado"></div>
      </div>

      <div style="margin-top:24px;">
        <a href="./" class="aca-btn aca-btn--outline">Ir al inicio</a>
      </div>
    </div>
  </div>

  <?php require 'footer.php'; ?>
  <script src="scripts/academia_core.js?v=<?php echo(rand()); ?>"></script>

</div><!-- /super_container -->

</body>
</html>
