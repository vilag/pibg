<!DOCTYPE html>
<html lang="es">
<head>
<title>Juventud PIBG | Primera Iglesia Bautista de Guadalajara</title>
<link href="images/iconos/icono.png" rel="icon">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="Juventud PIBG - Primera Iglesia Bautista de Guadalajara. Jóvenes que crecen en la fe, la Palabra y la comunidad.">
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
<style>
/* ── Variables ── */
:root {
  --azul:    #24344B;
  --naranja: #F85E0C;
  --gris-claro: #f5f7fa;
  --gris-texto: #555;
}

/* ── Hero ── */
.juventud-hero {
  position: relative;
  min-height: 520px;
  display: flex;
  align-items: center;
  overflow: hidden;
  background: var(--azul);
}
.juventud-hero__bg {
  position: absolute;
  inset: 0;
  background-image: url('images/jovenes/lumbrera2.jpg');
  background-size: cover;
  background-position: center 30%;
  filter: brightness(0.38);
}
.juventud-hero__content {
  position: relative;
  z-index: 2;
  text-align: center;
  padding: 120px 20px 60px;
  width: 100%;
}
.juventud-hero__tag {
  display: inline-block;
  background: var(--naranja);
  color: #fff;
  font-size: 11px;
  font-weight: 700;
  letter-spacing: 2px;
  text-transform: uppercase;
  padding: 5px 16px;
  border-radius: 20px;
  margin-bottom: 18px;
}
.juventud-hero__title {
  font-family: 'Libre Baskerville', serif;
  font-size: clamp(38px, 7vw, 72px);
  color: #fff;
  font-weight: 700;
  line-height: 1.1;
  margin-bottom: 16px;
  text-shadow: 0 4px 20px rgba(0,0,0,0.4);
}
.juventud-hero__subtitle {
  font-size: clamp(15px, 2.5vw, 20px);
  color: rgba(255,255,255,0.82);
  max-width: 560px;
  margin: 0 auto 30px;
  line-height: 1.6;
}
.juventud-hero__breadcrumb {
  list-style: none;
  padding: 0;
  margin: 0;
  display: flex;
  justify-content: center;
  gap: 8px;
  font-size: 13px;
  color: rgba(255,255,255,0.6);
}
.juventud-hero__breadcrumb li + li::before { content: '/'; margin-right: 8px; }
.juventud-hero__breadcrumb a { color: rgba(255,255,255,0.7); text-decoration: none; }
.juventud-hero__breadcrumb a:hover { color: var(--naranja); }

/* ── Sección genérica ── */
.sec { padding: 80px 0; }
.sec--gray { background: var(--gris-claro); }
.sec__tag {
  font-size: 11px;
  font-weight: 700;
  letter-spacing: 2px;
  text-transform: uppercase;
  color: var(--naranja);
  margin-bottom: 10px;
}
.sec__title {
  font-family: 'Libre Baskerville', serif;
  font-size: clamp(24px, 4vw, 36px);
  color: var(--azul);
  font-weight: 700;
  line-height: 1.2;
  margin-bottom: 16px;
}
.sec__text {
  font-size: 15px;
  color: var(--gris-texto);
  line-height: 1.8;
  margin-bottom: 16px;
}

/* ── Quiénes somos ── */
.quienes-img {
  width: 100%;
  border-radius: 12px;
  object-fit: cover;
  max-height: 420px;
  box-shadow: 0 20px 60px rgba(0,0,0,0.15);
}

/* ── Cards de actividades ── */
.act-card {
  background: #fff;
  border-radius: 14px;
  padding: 36px 28px;
  text-align: center;
  box-shadow: 0 4px 24px rgba(0,0,0,0.07);
  transition: transform 220ms ease, box-shadow 220ms ease;
  height: 100%;
}
.act-card:hover {
  transform: translateY(-6px);
  box-shadow: 0 12px 40px rgba(0,0,0,0.13);
}
.act-card__icon {
  width: 64px;
  height: 64px;
  border-radius: 50%;
  background: linear-gradient(135deg, var(--azul), #3a5070);
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 20px;
}
.act-card__icon i { font-size: 24px; color: #fff; }
.act-card__title {
  font-family: 'Libre Baskerville', serif;
  font-size: 18px;
  color: var(--azul);
  font-weight: 700;
  margin-bottom: 10px;
}
.act-card__text {
  font-size: 14px;
  color: var(--gris-texto);
  line-height: 1.7;
  margin: 0;
}
.act-card__badge {
  display: inline-block;
  margin-top: 14px;
  background: #FFF1EB;
  color: var(--naranja);
  font-size: 11px;
  font-weight: 700;
  padding: 4px 12px;
  border-radius: 20px;
  letter-spacing: 0.5px;
}

/* ── Versículo ── */
.versiculo-sec {
  background: linear-gradient(135deg, var(--azul) 0%, #1a2840 100%);
  padding: 80px 0;
  text-align: center;
  position: relative;
  overflow: hidden;
}
.versiculo-sec::before {
  content: '\201C';
  position: absolute;
  top: -30px;
  left: 50%;
  transform: translateX(-50%);
  font-size: 260px;
  color: rgba(255,255,255,0.04);
  font-family: Georgia, serif;
  line-height: 1;
  pointer-events: none;
}
.versiculo-sec__text {
  font-family: 'Libre Baskerville', serif;
  font-size: clamp(20px, 3.5vw, 30px);
  color: #fff;
  line-height: 1.6;
  max-width: 720px;
  margin: 0 auto 20px;
  font-style: italic;
}
.versiculo-sec__ref {
  font-size: 14px;
  font-weight: 700;
  color: var(--naranja);
  letter-spacing: 1px;
  text-transform: uppercase;
}

/* ── Horario ── */
.horario-card {
  background: #fff;
  border-radius: 14px;
  padding: 30px 28px;
  box-shadow: 0 4px 24px rgba(0,0,0,0.07);
  margin-bottom: 20px;
  display: flex;
  align-items: flex-start;
  gap: 20px;
}
.horario-card__dot {
  width: 48px;
  height: 48px;
  border-radius: 50%;
  background: var(--naranja);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}
.horario-card__dot i { font-size: 18px; color: #fff; }
.horario-card__day {
  font-size: 11px;
  font-weight: 700;
  color: var(--naranja);
  letter-spacing: 1.5px;
  text-transform: uppercase;
  margin-bottom: 2px;
}
.horario-card__name {
  font-family: 'Libre Baskerville', serif;
  font-size: 17px;
  color: var(--azul);
  font-weight: 700;
  margin-bottom: 4px;
}
.horario-card__desc {
  font-size: 13px;
  color: var(--gris-texto);
  margin: 0;
  line-height: 1.5;
}

/* ── Instagram CTA ── */
.insta-sec {
  background: linear-gradient(135deg, #833ab4 0%, #fd1d1d 50%, #fcb045 100%);
  padding: 70px 0;
  text-align: center;
  color: #fff;
}
.insta-sec__icon { font-size: 52px; color: #fff; margin-bottom: 18px; }
.insta-sec__title {
  font-family: 'Libre Baskerville', serif;
  font-size: clamp(22px, 4vw, 34px);
  font-weight: 700;
  margin-bottom: 10px;
}
.insta-sec__handle {
  font-size: 18px;
  opacity: 0.88;
  margin-bottom: 24px;
}
.insta-sec__btn {
  display: inline-block;
  padding: 13px 36px;
  background: #fff;
  color: #833ab4;
  font-size: 14px;
  font-weight: 700;
  border-radius: 30px;
  text-decoration: none;
  letter-spacing: 0.5px;
  transition: transform 200ms, box-shadow 200ms;
}
.insta-sec__btn:hover {
  transform: translateY(-3px);
  box-shadow: 0 8px 24px rgba(0,0,0,0.2);
  text-decoration: none;
  color: #833ab4;
}

/* ── CTA final ── */
.cta-sec {
  background: var(--gris-claro);
  padding: 80px 0;
  text-align: center;
}
.cta-sec__title {
  font-family: 'Libre Baskerville', serif;
  font-size: clamp(22px, 4vw, 34px);
  color: var(--azul);
  font-weight: 700;
  margin-bottom: 12px;
}
.cta-sec__text {
  font-size: 15px;
  color: var(--gris-texto);
  max-width: 540px;
  margin: 0 auto 28px;
  line-height: 1.7;
}
.btn-pibg {
  display: inline-block;
  padding: 13px 36px;
  background: var(--naranja);
  color: #fff;
  font-size: 14px;
  font-weight: 700;
  border-radius: 30px;
  text-decoration: none;
  letter-spacing: 0.5px;
  transition: background 200ms, transform 200ms;
  margin: 6px;
}
.btn-pibg:hover { background: #d94e00; color: #fff; text-decoration: none; transform: translateY(-2px); }
.btn-pibg--outline {
  background: transparent;
  border: 2px solid var(--naranja);
  color: var(--naranja);
}
.btn-pibg--outline:hover { background: var(--naranja); color: #fff; }

/* ── Responsive ── */
@media (max-width: 991px) {
  .sec { padding: 60px 0; }
  .quienes-img { margin-top: 36px; max-height: 320px; }
}
@media (max-width: 767px) {
  .juventud-hero { min-height: 380px; }
  .juventud-hero__content { padding: 100px 16px 50px; }
  .sec { padding: 50px 0; }
  .act-card { padding: 28px 20px; }
  .horario-card { flex-direction: column; gap: 12px; }
  .versiculo-sec { padding: 60px 0; }
  .insta-sec { padding: 54px 0; }
}
@media (max-width: 480px) {
  .juventud-hero__title { font-size: 32px; }
  .act-card__icon { width: 52px; height: 52px; }
  .act-card__icon i { font-size: 20px; }
}
</style>
</head>
<body>

<div class="super_container">

  <?php require 'header.php'; ?>

  <!-- Hero -->
  <div class="juventud-hero">
    <div class="juventud-hero__bg"></div>
    <div class="juventud-hero__content">
      <span class="juventud-hero__tag">PIBG &bull; Jóvenes</span>
      <div class="juventud-hero__title">Juventud PIBG</div>
      <p class="juventud-hero__subtitle">
        Jóvenes que crecen juntos en la fe, la Palabra de Dios y la comunidad.
      </p>
      <ul class="juventud-hero__breadcrumb">
        <li><a href="./">Inicio</a></li>
        <li>Juventud</li>
      </ul>
    </div>
  </div>

  <!-- Quiénes somos -->
  <div class="sec">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-6">
          <div class="sec__tag">Quiénes somos</div>
          <h2 class="sec__title">Un grupo de jóvenes que ama a Cristo</h2>
          <p class="sec__text">
            La <strong>Juventud PIBG</strong> es el ministerio de jóvenes de la Primera Iglesia Bautista de Guadalajara. Somos un grupo de jóvenes comprometidos con conocer y vivir la Palabra de Dios, con crecer en comunidad y con compartir el evangelio en nuestra ciudad.
          </p>
          <p class="sec__text">
            Creemos que la juventud tiene un papel fundamental en la iglesia. Aquí encontrarás un lugar donde puedes preguntar, crecer, ser aceptado y, sobre todo, encontrarte con Jesucristo.
          </p>
          <p class="sec__text">
            Nos reunimos cada semana para estudiar la Biblia, adorar juntos y apoyarnos como comunidad. También organizamos retiros, eventos especiales y actividades a lo largo del año.
          </p>
          <a href="https://www.instagram.com/pibg.joven/" target="_blank" class="btn-pibg" style="margin-left:0;">
            <i class="fa fa-instagram" style="margin-right:8px;"></i>Síguenos en Instagram
          </a>
        </div>
        <div class="col-lg-6">
          <img src="images/jovenes/lumbrera2.jpg" alt="Juventud PIBG" class="quienes-img">
        </div>
      </div>
    </div>
  </div>

  <!-- Actividades -->
  <div class="sec sec--gray">
    <div class="container">
      <div class="row">
        <div class="col-12 text-center mb-5">
          <div class="sec__tag">Lo que hacemos</div>
          <h2 class="sec__title" style="margin-bottom:0;">Nuestras actividades</h2>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-4 col-md-6 mb-4">
          <div class="act-card">
            <div class="act-card__icon">
              <i class="fa fa-book"></i>
            </div>
            <div class="act-card__title">Estudio Bíblico</div>
            <p class="act-card__text">Cada semana nos reunimos para estudiar las Escrituras con profundidad, aplicando la Palabra a nuestra vida cotidiana.</p>
            <span class="act-card__badge"><i class="fa fa-calendar" style="margin-right:5px;"></i>Cada semana</span>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 mb-4">
          <div class="act-card">
            <div class="act-card__icon">
              <i class="fa fa-music"></i>
            </div>
            <div class="act-card__title">Alabanza</div>
            <p class="act-card__text">Adoramos a Dios a través de la música y el canto. La alabanza es parte central de nuestra reunión semanal y nuestros eventos especiales.</p>
            <span class="act-card__badge"><i class="fa fa-calendar" style="margin-right:5px;"></i>Cada semana</span>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 mb-4">
          <div class="act-card">
            <div class="act-card__icon">
              <i class="fa fa-users"></i>
            </div>
            <div class="act-card__title">Compañerismo</div>
            <p class="act-card__text">Construimos amistades genuinas basadas en el amor de Cristo. Convivencias, juegos y momentos compartidos que fortalecen la comunidad.</p>
            <span class="act-card__badge"><i class="fa fa-calendar" style="margin-right:5px;"></i>Frecuente</span>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 mb-4">
          <div class="act-card">
            <div class="act-card__icon">
              <i class="fa fa-map-marker"></i>
            </div>
            <div class="act-card__title">Retiros</div>
            <p class="act-card__text">Organizamos retiros fuera de la ciudad donde podemos desconectarnos, reflexionar y profundizar nuestra relación con Dios y entre nosotros.</p>
            <span class="act-card__badge"><i class="fa fa-calendar" style="margin-right:5px;"></i>Anual</span>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 mb-4">
          <div class="act-card">
            <div class="act-card__icon">
              <i class="fa fa-bullhorn"></i>
            </div>
            <div class="act-card__title">Evangelismo</div>
            <p class="act-card__text">Salimos a compartir el evangelio en nuestra ciudad. Creemos que cada joven puede ser un instrumento de Dios para alcanzar a otros.</p>
            <span class="act-card__badge"><i class="fa fa-calendar" style="margin-right:5px;"></i>Periódico</span>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 mb-4">
          <div class="act-card">
            <div class="act-card__icon">
              <i class="fa fa-star"></i>
            </div>
            <div class="act-card__title">Semana de la Juventud</div>
            <p class="act-card__text">Una semana especial de predicaciones, actividades y celebración dedicada completamente a los jóvenes de la iglesia. ¡Un evento que no te puedes perder!</p>
            <span class="act-card__badge"><i class="fa fa-calendar" style="margin-right:5px;"></i>Anual</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Versículo -->
  <div class="versiculo-sec">
    <div class="container">
      <p class="versiculo-sec__text">
        "Nadie tenga en poco tu juventud, sino sé ejemplo de los creyentes en palabra, conducta, amor, espíritu, fe y pureza."
      </p>
      <div class="versiculo-sec__ref">1 Timoteo 4:12</div>
    </div>
  </div>

  <!-- Horarios -->
  <div class="sec">
    <div class="container">
      <div class="row">
        <div class="col-lg-5 mb-5 mb-lg-0">
          <div class="sec__tag">Encuéntranos</div>
          <h2 class="sec__title">Horarios de reunión</h2>
          <p class="sec__text">
            Todos son bienvenidos. Si eres joven o conoces a algún joven que quiera conocer a Cristo y crecer en comunidad, estas son nuestras reuniones semanales.
          </p>
          <img src="images/jovenes/lumbrera3.jpg" alt="Retiro Juventud PIBG" class="quienes-img" style="margin-top:28px;">
        </div>
        <div class="col-lg-7">
          <div class="horario-card">
            <div class="horario-card__dot"><i class="fa fa-book"></i></div>
            <div>
              <div class="horario-card__day">Domingo</div>
              <div class="horario-card__name">Escuela Dominical</div>
              <p class="horario-card__desc">Estudio bíblico para jóvenes antes del culto principal. Un espacio de enseñanza y reflexión sobre la Palabra.</p>
            </div>
          </div>
          <div class="horario-card">
            <div class="horario-card__dot"><i class="fa fa-users"></i></div>
            <div>
              <div class="horario-card__day">Domingo</div>
              <div class="horario-card__name">Culto General</div>
              <p class="horario-card__desc">Participamos juntos en el culto de adoración de toda la iglesia. La comunidad intergeneracional es parte importante de nuestra fe.</p>
            </div>
          </div>
          <div class="horario-card">
            <div class="horario-card__dot"><i class="fa fa-music"></i></div>
            <div>
              <div class="horario-card__day">Entre semana</div>
              <div class="horario-card__name">Reunión de Jóvenes</div>
              <p class="horario-card__desc">Nuestra reunión semanal especial para jóvenes: alabanza, estudio bíblico y compañerismo. Síguenos en Instagram para conocer el horario actualizado.</p>
            </div>
          </div>
          <p style="font-size:13px; color:var(--gris-texto); margin-top:10px;">
            <i class="fa fa-map-marker" style="color:var(--naranja); margin-right:6px;"></i>
            C. Independencia 657, Zona Centro, Guadalajara, Jal.
          </p>
        </div>
      </div>
    </div>
  </div>

  <!-- Instagram -->
  <div class="insta-sec">
    <div class="container">
      <div class="insta-sec__icon"><i class="fa fa-instagram"></i></div>
      <div class="insta-sec__title">Síguenos en Instagram</div>
      <div class="insta-sec__handle">@pibg.joven</div>
      <p style="color:rgba(255,255,255,0.8); max-width:480px; margin:0 auto 28px; font-size:15px; line-height:1.6;">
        Mantente al día con nuestras actividades, eventos y devocionales diarios. ¡Únete a la comunidad!
      </p>
      <a href="https://www.instagram.com/pibg.joven/" target="_blank" class="insta-sec__btn">
        <i class="fa fa-instagram" style="margin-right:8px;"></i>Ver perfil
      </a>
    </div>
  </div>

  <!-- CTA final -->
  <div class="cta-sec">
    <div class="container">
      <div class="sec__tag" style="text-align:center;">¿Listo para unirte?</div>
      <div class="cta-sec__title">Ven y conoce a la Juventud PIBG</div>
      <p class="cta-sec__text">
        No importa dónde estés en tu fe. Aquí encontrarás un lugar de bienvenida, amistades genuinas y la Palabra de Dios que transforma vidas.
      </p>
      <a href="./" class="btn-pibg">Ir al inicio</a>
      <a href="quien_es_jesus.php" class="btn-pibg btn-pibg--outline">¿Quién es Jesús?</a>
    </div>
  </div>

  <?php require 'footer.php'; ?>

</div><!-- /super_container -->

</body>
</html>
