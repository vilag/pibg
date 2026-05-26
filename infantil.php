<!DOCTYPE html>
<html lang="es">
<head>
<title>Departamento Infantil | Primera Iglesia Bautista de Guadalajara</title>
<link href="images/iconos/icono.png" rel="icon">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="Departamento Infantil PIBG - Primera Iglesia Bautista de Guadalajara. Un ministerio dedicado a que los niños conozcan y amen a Jesús.">
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
  --azul:       #24344B;
  --naranja:    #F85E0C;
  --verde:      #2E9B65;
  --verde-dark: #1E7A4D;
  --amarillo:   #F5C242;
  --gris-claro: #f5f7fa;
  --gris-texto: #555;
}

/* ════════════════════════════════
   HERO
════════════════════════════════ */
.inf-hero {
  position: relative;
  min-height: 520px;
  display: flex;
  align-items: center;
  overflow: hidden;
  background: linear-gradient(145deg, #1B7A4F 0%, #2E9B65 40%, #4DBF82 70%, #7DD9A8 100%);
}
/* Círculos decorativos */
.inf-hero::before,
.inf-hero::after {
  content: '';
  position: absolute;
  border-radius: 50%;
  pointer-events: none;
}
.inf-hero::before {
  width: 520px;
  height: 520px;
  background: rgba(255,255,255,0.07);
  top: -160px;
  right: -120px;
}
.inf-hero::after {
  width: 320px;
  height: 320px;
  background: rgba(255,255,255,0.06);
  bottom: -100px;
  left: -80px;
}
.inf-hero__bg {
  display: none;
}
.inf-hero__content {
  position: relative;
  z-index: 2;
  text-align: center;
  padding: 130px 20px 70px;
  width: 100%;
}
.inf-hero__tag {
  display: inline-block;
  background: rgba(255,255,255,0.22);
  border: 1px solid rgba(255,255,255,0.45);
  color: #fff;
  font-size: 11px;
  font-weight: 700;
  letter-spacing: 2px;
  text-transform: uppercase;
  padding: 5px 16px;
  border-radius: 20px;
  margin-bottom: 18px;
}
.inf-hero__title {
  font-family: 'Libre Baskerville', serif !important;
  font-size: clamp(36px, 7vw, 68px);
  color: #fff;
  font-weight: 700;
  line-height: 1.1;
  margin-bottom: 16px;
  text-shadow: 0 2px 12px rgba(0,0,0,0.18);
}
.inf-hero__subtitle {
  font-size: clamp(15px, 2.5vw, 19px);
  color: rgba(255,255,255,0.84);
  max-width: 560px;
  margin: 0 auto 30px;
  line-height: 1.6;
}
.inf-hero__breadcrumb {
  list-style: none;
  padding: 0; margin: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  font-size: 13px;
}
.inf-hero__breadcrumb li { color: rgba(255,255,255,0.65); }
.inf-hero__breadcrumb li a { color: rgba(255,255,255,0.9); text-decoration: none; }
.inf-hero__breadcrumb li + li::before {
  content: '›';
  margin-right: 10px;
  opacity: 0.6;
}

/* ════════════════════════════════
   SECCIONES GENERALES
════════════════════════════════ */
.inf-sec {
  padding: 80px 0;
}
.inf-sec--gray { background: var(--gris-claro); }
.inf-sec--azul { background: var(--azul); }
.inf-sec--verde { background: var(--verde); }

.inf-sec__tag {
  display: inline-block;
  font-size: 11px;
  font-weight: 700;
  letter-spacing: 2px;
  text-transform: uppercase;
  color: var(--verde);
  margin-bottom: 10px;
}
.inf-sec__title {
  font-family: 'Libre Baskerville', serif !important;
  font-size: clamp(24px, 4vw, 36px);
  color: var(--azul);
  font-weight: 700;
  line-height: 1.2;
  margin-bottom: 16px;
}
.inf-sec__title--white {
  color: #fff;
}
.inf-sec__text {
  font-size: 15px;
  color: var(--gris-texto);
  line-height: 1.8;
  margin-bottom: 16px;
}
.inf-sec__img {
  width: 100%;
  border-radius: 16px;
  object-fit: cover;
  max-height: 420px;
  box-shadow: 0 20px 60px rgba(0,0,0,0.14);
}

/* ════════════════════════════════
   LOGO / ESCUDO MINISTERIO
════════════════════════════════ */
.inf-logo-wrap {
  display: flex;
  justify-content: center;
  align-items: center;
  margin-bottom: 32px;
}
.inf-logo-wrap img {
  width: 160px;
  height: 160px;
  object-fit: contain;
  filter: drop-shadow(0 8px 24px rgba(0,0,0,0.15));
}

/* ════════════════════════════════
   CARDS DE ACTIVIDADES
════════════════════════════════ */
.inf-card {
  background: #fff;
  border-radius: 16px;
  padding: 36px 28px;
  text-align: center;
  box-shadow: 0 4px 24px rgba(0,0,0,0.07);
  transition: transform 220ms ease, box-shadow 220ms ease;
  height: 100%;
  border-top: 4px solid var(--verde);
}
.inf-card:hover {
  transform: translateY(-6px);
  box-shadow: 0 12px 40px rgba(0,0,0,0.13);
}
.inf-card__icon {
  width: 68px;
  height: 68px;
  border-radius: 50%;
  background: linear-gradient(135deg, var(--verde), var(--verde-dark));
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 20px;
}
.inf-card__icon i { font-size: 26px; color: #fff; }
.inf-card__title {
  font-family: 'Libre Baskerville', serif !important;
  font-size: 17px;
  color: var(--azul);
  font-weight: 700;
  margin-bottom: 10px;
}
.inf-card__text {
  font-size: 13.5px;
  color: var(--gris-texto);
  line-height: 1.7;
  margin-bottom: 14px;
}
.inf-card__badge {
  display: inline-block;
  font-size: 11px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 1px;
  color: var(--verde);
  background: rgba(46,155,101,0.1);
  padding: 4px 12px;
  border-radius: 20px;
}

/* ════════════════════════════════
   VERSÍCULO
════════════════════════════════ */
.versiculo-inf {
  background: var(--azul);
  padding: 80px 0;
  text-align: center;
  position: relative;
  overflow: hidden;
}
.versiculo-inf::before {
  content: '"';
  position: absolute;
  top: -40px;
  left: 50%;
  transform: translateX(-50%);
  font-size: 260px;
  font-family: Georgia, serif;
  color: rgba(255,255,255,0.04);
  line-height: 1;
  pointer-events: none;
}
.versiculo-inf__text {
  font-family: 'Libre Baskerville', serif !important;
  font-size: clamp(18px, 3vw, 28px);
  color: #fff;
  line-height: 1.65;
  max-width: 740px;
  margin: 0 auto 20px;
  font-style: italic;
}
.versiculo-inf__ref {
  font-size: 13px;
  font-weight: 700;
  color: var(--verde);
  letter-spacing: 1.5px;
  text-transform: uppercase;
}

/* ════════════════════════════════
   HORARIOS
════════════════════════════════ */
.horario-inf {
  display: flex;
  gap: 20px;
  align-items: flex-start;
  padding: 24px 28px;
  border-radius: 14px;
  background: #fff;
  box-shadow: 0 4px 20px rgba(0,0,0,0.06);
  margin-bottom: 16px;
  border-left: 5px solid var(--verde);
  transition: box-shadow 200ms ease;
}
.horario-inf:hover { box-shadow: 0 8px 32px rgba(0,0,0,0.11); }
.horario-inf__dot {
  width: 44px;
  height: 44px;
  flex-shrink: 0;
  border-radius: 50%;
  background: linear-gradient(135deg, var(--verde), var(--verde-dark));
  display: flex;
  align-items: center;
  justify-content: center;
  color: #fff;
  font-size: 17px;
}
.horario-inf__day {
  font-size: 11px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 1.5px;
  color: var(--verde);
  margin-bottom: 2px;
}
.horario-inf__name {
  font-family: 'Libre Baskerville', serif !important;
  font-size: 16px;
  color: var(--azul);
  font-weight: 700;
  margin-bottom: 4px;
}
.horario-inf__desc {
  font-size: 13.5px;
  color: var(--gris-texto);
  line-height: 1.6;
  margin: 0;
}

/* ════════════════════════════════
   FACEBOOK CTA
════════════════════════════════ */
.fb-inf-sec {
  background: #1877F2;
  padding: 80px 0;
  text-align: center;
}
.fb-inf-sec__icon { font-size: 52px; color: #fff; margin-bottom: 18px; }
.fb-inf-sec__title {
  font-family: 'Libre Baskerville', serif !important;
  font-size: clamp(22px, 4vw, 34px);
  font-weight: 700;
  color: #fff;
  margin-bottom: 10px;
}
.fb-inf-sec__sub {
  font-size: 16px;
  color: rgba(255,255,255,0.88);
  margin-bottom: 28px;
  max-width: 480px;
  margin-left: auto;
  margin-right: auto;
  line-height: 1.6;
}
.fb-inf-sec__btns {
  display: flex;
  gap: 16px;
  justify-content: center;
  flex-wrap: wrap;
}
.fb-btn {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 13px 30px;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 700;
  text-decoration: none !important;
  transition: opacity 200ms, transform 180ms;
}
.fb-btn:hover { opacity: 0.88; transform: translateY(-2px); text-decoration: none; }
.fb-btn--white { background: #fff; color: #1877F2 !important; }
.fb-btn--outline { background: transparent; color: #fff !important; border: 2px solid rgba(255,255,255,0.7); }

/* ════════════════════════════════
   CTA FINAL
════════════════════════════════ */
.cta-inf {
  background: var(--gris-claro);
  padding: 80px 0;
  text-align: center;
}
.cta-inf__title {
  font-family: 'Libre Baskerville', serif !important;
  font-size: clamp(22px, 4vw, 34px);
  color: var(--azul);
  font-weight: 700;
  margin-bottom: 12px;
}
.cta-inf__text {
  font-size: 15px;
  color: var(--gris-texto);
  max-width: 540px;
  margin: 0 auto 28px;
  line-height: 1.7;
}
.btn-inf {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 13px 32px;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 700;
  text-decoration: none !important;
  transition: background 200ms, transform 180ms, color 200ms;
  margin: 6px;
}
.btn-inf:hover { transform: translateY(-2px); text-decoration: none; }
.btn-inf--solid { background: var(--verde); color: #fff !important; }
.btn-inf--solid:hover { background: var(--verde-dark); color: #fff !important; }
.btn-inf--outline { background: transparent; color: var(--azul) !important; border: 2px solid var(--azul); }
.btn-inf--outline:hover { background: var(--azul); color: #fff !important; }

/* ════════════════════════════════
   DESTACADO / NÚMEROS
════════════════════════════════ */
.inf-stat {
  text-align: center;
  padding: 32px 20px;
}
.inf-stat__num {
  font-family: 'Libre Baskerville', serif !important;
  font-size: 48px;
  font-weight: 700;
  color: var(--verde);
  line-height: 1;
  margin-bottom: 8px;
}
.inf-stat__label {
  font-size: 14px;
  color: rgba(255,255,255,0.8);
  text-transform: uppercase;
  letter-spacing: 1px;
}

/* ════════════════════════════════
   RESPONSIVE
════════════════════════════════ */
@media (max-width: 991px) {
  .inf-hero__title { font-size: 44px; }
  .inf-sec { padding: 60px 0; }
  .versiculo-inf { padding: 60px 0; }
  .fb-inf-sec { padding: 60px 0; }
  .cta-inf { padding: 60px 0; }
}
@media (max-width: 767px) {
  .inf-hero__title { font-size: 32px; }
  .inf-hero__content { padding: 110px 16px 56px; }
  .inf-sec__img { max-height: 280px; margin-top: 32px; }
  .horario-inf { padding: 18px 18px; }
  .fb-inf-sec__btns { flex-direction: column; align-items: center; }
}
@media (max-width: 480px) {
  .inf-hero__title { font-size: 26px; }
  .inf-card { padding: 28px 18px; }
  .inf-logo-wrap img { width: 120px; height: 120px; }
}
</style>
</head>
<body>

<div class="super_container">

  <?php require 'header.php'; ?>

  <!-- Hero -->
  <div class="inf-hero">
    <div class="inf-hero__bg"></div>
    <div class="inf-hero__content">
      <span class="inf-hero__tag">PIBG &bull; Niños</span>
      <div class="inf-hero__title">Departamento Infantil</div>
      <p class="inf-hero__subtitle">
        Un lugar seguro y lleno de amor donde los niños conocen, aman y crecen en Jesús.
      </p>
      <ul class="inf-hero__breadcrumb">
        <li><a href="./">Inicio</a></li>
        <li>Departamento Infantil</li>
      </ul>
    </div>
  </div>

  <!-- Quiénes somos -->
  <div class="inf-sec">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-6 mb-4 mb-lg-0">
          <div class="inf-logo-wrap">
            <img src="images/ministerios/ninos.png" alt="Departamento Infantil PIBG">
          </div>
          <div class="inf-sec__tag text-center">Quiénes somos</div>
          <h2 class="inf-sec__title text-center">Sembrando la Palabra<br>en corazones pequeños</h2>
          <p class="inf-sec__text">
            El <strong>Departamento Infantil de la PIBG</strong> es el ministerio dedicado a los niños de nuestra iglesia. Creemos firmemente que la fe se forma desde la niñez y que cada niño tiene un lugar especial en el corazón de Dios y en nuestra iglesia.
          </p>
          <p class="inf-sec__text">
            Contamos con maestros comprometidos que semana a semana enseñan la Biblia de manera dinámica, creativa y accesible para cada edad. Aquí los niños aprenden quién es Jesús, estudian la Palabra y crecen en fe junto a otros niños.
          </p>
          <div class="text-center">
            <a href="https://www.facebook.com/gdlpib" target="_blank" class="btn-inf btn-inf--solid">
              <i class="fa fa-facebook"></i>Síguenos en Facebook
            </a>
          </div>
        </div>
        <div class="col-lg-6">
          <img src="images/infantil/quienes.jpg" onerror="this.src='images/infantil/niños_clase2.jpg'" alt="Niños PIBG" class="inf-sec__img">
        </div>
      </div>
    </div>
  </div>

  <!-- Actividades -->
  <div class="inf-sec inf-sec--gray">
    <div class="container">
      <div class="row">
        <div class="col-12 text-center mb-5">
          <div class="inf-sec__tag">Lo que hacemos</div>
          <h2 class="inf-sec__title" style="margin-bottom:0;">Nuestras actividades</h2>
        </div>
      </div>
      <div class="row">

        <div class="col-lg-4 col-md-6 mb-4">
          <div class="inf-card">
            <div class="inf-card__icon"><i class="fa fa-book"></i></div>
            <div class="inf-card__title">Clases Bíblicas</div>
            <p class="inf-card__text">Cada domingo enseñamos la Biblia adaptada a la edad de cada niño, con historias, dinámicas y aplicaciones prácticas para su vida diaria.</p>
            <span class="inf-card__badge"><i class="fa fa-calendar" style="margin-right:5px;"></i>Cada domingo</span>
          </div>
        </div>

        <div class="col-lg-4 col-md-6 mb-4">
          <div class="inf-card">
            <div class="inf-card__icon"><i class="fa fa-music"></i></div>
            <div class="inf-card__title">Música y Coros</div>
            <p class="inf-card__text">Los niños aprenden himnos y cantos que les ayudan a adorar a Dios y a grabar verdades bíblicas en su corazón desde pequeños.</p>
            <span class="inf-card__badge"><i class="fa fa-calendar" style="margin-right:5px;"></i>Cada semana</span>
          </div>
        </div>

        <div class="col-lg-4 col-md-6 mb-4">
          <div class="inf-card">
            <div class="inf-card__icon"><i class="fa fa-paint-brush"></i></div>
            <div class="inf-card__title">Manualidades</div>
            <p class="inf-card__text">A través de la creatividad los niños refuerzan las enseñanzas bíblicas de una manera divertida y memorable.</p>
            <span class="inf-card__badge"><i class="fa fa-calendar" style="margin-right:5px;"></i>Frecuente</span>
          </div>
        </div>

        <div class="col-lg-4 col-md-6 mb-4">
          <div class="inf-card">
            <div class="inf-card__icon"><i class="fa fa-sun-o"></i></div>
            <div class="inf-card__title">Escuela Bíblica de Vacaciones</div>
            <p class="inf-card__text">Una semana intensiva de actividades, juegos, manualidades y enseñanza bíblica durante las vacaciones de verano. ¡Un evento que los niños esperan todo el año!</p>
            <span class="inf-card__badge"><i class="fa fa-calendar" style="margin-right:5px;"></i>Anual · Verano</span>
          </div>
        </div>

        <div class="col-lg-4 col-md-6 mb-4">
          <div class="inf-card">
            <div class="inf-card__icon"><i class="fa fa-tree"></i></div>
            <div class="inf-card__title">Eventos Especiales</div>
            <p class="inf-card__text">Navidad, Día del Niño, aniversarios y celebraciones especiales donde toda la familia puede ver a los niños presentar lo que han aprendido.</p>
            <span class="inf-card__badge"><i class="fa fa-calendar" style="margin-right:5px;"></i>Fechas especiales</span>
          </div>
        </div>

        <div class="col-lg-4 col-md-6 mb-4">
          <div class="inf-card">
            <div class="inf-card__icon"><i class="fa fa-users"></i></div>
            <div class="inf-card__title">Compañerismo</div>
            <p class="inf-card__text">Los niños forman amistades dentro de la iglesia, aprendiendo a compartir, respetar y amarse los unos a los otros tal como Dios nos enseña.</p>
            <span class="inf-card__badge"><i class="fa fa-calendar" style="margin-right:5px;"></i>Cada semana</span>
          </div>
        </div>

      </div>
    </div>
  </div>

  <!-- Versículo -->
  <div class="versiculo-inf">
    <div class="container">
      <p class="versiculo-inf__text">
        "Instruye al niño en su camino, y aun cuando fuere viejo no se apartará de él."
      </p>
      <div class="versiculo-inf__ref">Proverbios 22:6</div>
    </div>
  </div>

  <!-- Horarios -->
  <div class="inf-sec">
    <div class="container">
      <div class="row align-items-start">
        <div class="col-lg-5 mb-5 mb-lg-0">
          <div class="inf-sec__tag">Encuéntranos</div>
          <h2 class="inf-sec__title">Horarios del Departamento</h2>
          <p class="inf-sec__text">
            Los niños son siempre bienvenidos. Contamos con clases organizadas por edades para que cada niño reciba enseñanza apropiada a su etapa de desarrollo.
          </p>
          <p class="inf-sec__text">
            Los maestros y maestras del Departamento Infantil están capacitados y comprometidos con el cuidado y la enseñanza de cada niño.
          </p>
          <img src="images/infantil/grupo.jpg" onerror="this.src='images/infantil/niños_clase.jpg'" alt="Departamento Infantil PIBG" class="inf-sec__img" style="margin-top:20px;">
        </div>
        <div class="col-lg-7">

          <div class="horario-inf">
            <div class="horario-inf__dot"><i class="fa fa-book"></i></div>
            <div>
              <div class="horario-inf__day">Domingo · Mañana</div>
              <div class="horario-inf__name">Escuela Dominical Infantil</div>
              <p class="horario-inf__desc">Clases bíblicas divididas por grupos de edad. Los niños estudian la Palabra, hacen manualidades y adoran a Dios en un espacio diseñado para ellos.</p>
            </div>
          </div>

          <div class="horario-inf">
            <div class="horario-inf__dot"><i class="fa fa-users"></i></div>
            <div>
              <div class="horario-inf__day">Domingo · Culto</div>
              <div class="horario-inf__name">Participación en Culto General</div>
              <p class="horario-inf__desc">Los niños participan en el culto de adoración junto a toda la iglesia. En ocasiones especiales presentan cantos, versículos y programas preparados durante el año.</p>
            </div>
          </div>

          <div class="horario-inf">
            <div class="horario-inf__dot"><i class="fa fa-star"></i></div>
            <div>
              <div class="horario-inf__day">Eventos especiales</div>
              <div class="horario-inf__name">Actividades adicionales</div>
              <p class="horario-inf__desc">A lo largo del año se realizan actividades especiales, EBV y eventos de convivencia. Síguenos en Facebook para conocer las próximas fechas.</p>
            </div>
          </div>

          <p style="font-size:13px; color:var(--gris-texto); margin-top:10px;">
            <i class="fa fa-map-marker" style="color:var(--verde); margin-right:6px;"></i>
            C. Independencia 657, Zona Centro, Guadalajara, Jal.
          </p>
        </div>
      </div>
    </div>
  </div>

  <!-- Facebook CTA -->
  <div class="fb-inf-sec">
    <div class="container">
      <div class="fb-inf-sec__icon"><i class="fa fa-facebook"></i></div>
      <div class="fb-inf-sec__title">Síguenos en Facebook</div>
      <p class="fb-inf-sec__sub">
        Mantente al día con las actividades, eventos y noticias del Departamento Infantil en nuestras páginas de Facebook.
      </p>
      <div class="fb-inf-sec__btns">
        <a href="https://www.facebook.com/gdlpib" target="_blank" class="fb-btn fb-btn--white">
          <i class="fa fa-facebook"></i>PIBG Guadalajara
        </a>
        <a href="https://www.facebook.com/primeraiglesiabautist.guadalajara.7" target="_blank" class="fb-btn fb-btn--outline">
          <i class="fa fa-facebook"></i>Primera Iglesia Bautista GDL
        </a>
      </div>
    </div>
  </div>

  <!-- CTA final -->
  <div class="cta-inf">
    <div class="container">
      <div class="inf-sec__tag" style="text-align:center; display:block;">¿Listo para traer a tus hijos?</div>
      <div class="cta-inf__title">Trae a tus niños al Departamento Infantil</div>
      <p class="cta-inf__text">
        No importa si es la primera vez que visitas la iglesia. Tus hijos encontrarán un lugar seguro, lleno de amor y de la Palabra de Dios. ¡Toda la familia es bienvenida!
      </p>
      <a href="./" class="btn-inf btn-inf--solid">Ir al inicio</a>
      <a href="quien_es_jesus.php" class="btn-inf btn-inf--outline">¿Quién es Jesús?</a>
    </div>
  </div>

  <?php require 'footer.php'; ?>

</div><!-- /super_container -->

</body>
</html>
