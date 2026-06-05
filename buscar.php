<!DOCTYPE html>
<html lang="es">
<head>
<title>Búsqueda - Primera Iglesia Bautista de Guadalajara</title>
<link href="images/iconos/icono.png" rel="icon">
<link rel="stylesheet" type="text/css" href="styles/bootstrap4/bootstrap.min.css">
<link href="plugins/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="styles/news.css">
<link rel="stylesheet" type="text/css" href="styles/news_responsive.css">
<link rel="stylesheet" type="text/css" href="styles/buscar.css">
</head>
<body>

<div class="super_container">

    <?php require 'header.php'; ?>

    <?php
        require_once 'config/Conexion.php';
        global $conexion;

        $q_raw      = isset($_GET['q']) ? trim($_GET['q']) : '';
        $q_display  = htmlspecialchars($q_raw);
        $q          = $q_raw !== '' ? mysqli_real_escape_string($conexion, $q_raw) : '';

        $sermones   = [];
        $biografias = [];
        $actividades= [];
        $paginas    = [];

        if (mb_strlen($q_raw, 'UTF-8') >= 2) {
            // Sermones
            $r = ejecutarConsulta("SELECT idsermones, nom_sermon, predicador, fecha_eti, imagen
                FROM sermones
                WHERE nom_sermon LIKE '%$q%' OR predicador LIKE '%$q%' OR predicacion LIKE '%$q%'
                ORDER BY idsermones DESC LIMIT 10");
            if ($r) while ($row = $r->fetch_assoc()) $sermones[] = $row;

            // Biografías
            $r = ejecutarConsulta("SELECT idbiografia, nombre, cargo, imagen, biografia
                FROM biografias
                WHERE nombre LIKE '%$q%' OR cargo LIKE '%$q%' OR biografia LIKE '%$q%'
                ORDER BY idbiografia DESC LIMIT 10");
            if ($r) while ($row = $r->fetch_assoc()) $biografias[] = $row;

            // Calendario
            $r = ejecutarConsulta("SELECT idcal, nom_activ, tema, DATE(fecha_hora) AS fecha
                FROM calendario
                WHERE (nom_activ LIKE '%$q%' OR tema LIKE '%$q%') AND DATE(fecha_hora) >= CURDATE()
                ORDER BY fecha_hora ASC LIMIT 10");
            if ($r) while ($row = $r->fetch_assoc()) $actividades[] = $row;

            // Páginas estáticas
            $paginas_catalogo = [
                [
                    'titulo'   => 'Conócenos — Historia de la iglesia',
                    'desc'     => 'Historia de la Primera Iglesia Bautista de Guadalajara. Más de 135 años proclamando el evangelio, pastores fundadores e iglesias organizadas.',
                    'url'      => 'about-us.php',
                    'icono'    => 'fa-building',
                    'keywords' => ['conócenos','conocenos','historia','nosotros','iglesia','fundación','fundacion','bautista','guadalajara','pibg','pastor','quiénes somos','quienes somos'],
                ],
                [
                    'titulo'   => '¿Quién es Jesús?',
                    'desc'     => 'Recurso educativo sobre Jesús con PDF descargable, Juan 3:16 y formulario de contacto para resolver tus dudas.',
                    'url'      => 'quien_es_jesus.php',
                    'icono'    => 'fa-question-circle',
                    'keywords' => ['jesús','jesus','quién es','quien es','salvador','evangelio','fe','cristiano','dios'],
                ],
                [
                    'titulo'   => 'Nueva Vida en Cristo',
                    'desc'     => 'Guía interactiva para conocer a Jesús. Visor de PDF y formulario de contacto para acompañamiento espiritual.',
                    'url'      => 'nueva_vida.php',
                    'icono'    => 'fa-heart',
                    'keywords' => ['nueva vida','nueva','salvación','salvacion','convertir','cristo','oración','oracion','creer'],
                ],
                [
                    'titulo'   => 'Predicaciones',
                    'desc'     => 'Archivo completo de sermones y series bíblicas de la Primera Iglesia Bautista de Guadalajara.',
                    'url'      => 'predicaciones.php',
                    'icono'    => 'fa-microphone',
                    'keywords' => ['predicaciones','predicación','predicacion','sermones','sermón','sermon','series','archivo','bíblico','biblico'],
                ],
                [
                    'titulo'   => 'Coro Johann Sebastian Bach',
                    'desc'     => 'Coro coral de la PIBG. Conciertos, presentaciones y videos de los conciertos de aniversario.',
                    'url'      => 'bach.php',
                    'icono'    => 'fa-music',
                    'keywords' => ['bach','coro bach','johann','concierto','coral','música','musica','coro'],
                ],
                [
                    'titulo'   => 'Voces — Coro Bach',
                    'desc'     => 'Catálogo de obras y partituras del Coro Johann Sebastian Bach. Búsqueda por voz: soprano, tenor, bajo, contralto.',
                    'url'      => 'voces.php',
                    'icono'    => 'fa-music',
                    'keywords' => ['voces bach','voces coro','soprano','tenor','bajo','contralto','obra','partitura','coro bach'],
                ],
                [
                    'titulo'   => 'Voces — Coro Lumbrera',
                    'desc'     => 'Catálogo de obras y partituras del Coro Lumbrera de jóvenes.',
                    'url'      => 'voces_lumbrera.php',
                    'icono'    => 'fa-music',
                    'keywords' => ['voces lumbrera','coro lumbrera','lumbrera coro','soprano lumbrera','tenor lumbrera'],
                ],
                [
                    'titulo'   => 'Jóvenes Lumbrera',
                    'desc'     => 'Ministerio de jóvenes de la PIBG. Estudio bíblico, música, compañerismo, retiros y evangelismo. Domingos 4:30 pm.',
                    'url'      => 'lumbrera.php',
                    'icono'    => 'fa-users',
                    'keywords' => ['jóvenes','jovenes','joven','lumbrera','juventud','adolescentes'],
                ],
                [
                    'titulo'   => 'Departamento Infantil',
                    'desc'     => 'Ministerio para niños de la PIBG. Clases bíblicas, manualidades, Escuela Bíblica de Vacaciones y eventos especiales.',
                    'url'      => 'infantil.php',
                    'icono'    => 'fa-child',
                    'keywords' => ['niños','ninos','niño','nino','infantil','niñez','ninez','ebv','escuela dominical','niñas'],
                ],
            ];
            $q_lower = mb_strtolower($q_raw, 'UTF-8');
            foreach ($paginas_catalogo as $pag) {
                foreach ($pag['keywords'] as $kw) {
                    if (mb_strpos($q_lower, $kw) !== false || mb_strpos($kw, $q_lower) !== false) {
                        $paginas[] = $pag;
                        break;
                    }
                }
            }
        }

        $total = count($sermones) + count($biografias) + count($actividades) + count($paginas);
    ?>

    <!-- Banner de búsqueda -->
    <div class="buscar_banner text-center">
        <div class="container">
            <h1>Búsqueda</h1>
            <?php if ($q_display): ?>
                <p><?php echo $total; ?> resultado<?php echo $total != 1 ? 's' : ''; ?> para "<b><?php echo $q_display; ?></b>"</p>
            <?php else: ?>
                <p>Escribe algo para buscar en el sitio</p>
            <?php endif; ?>
            <form method="get" action="buscar.php">
                <div class="buscar_form_wrap">
                    <input type="text" name="q" value="<?php echo $q_display; ?>" placeholder="Predicaciones, biografías, jóvenes, niños..." autofocus>
                    <button type="submit"><i class="fa fa-search"></i></button>
                </div>
            </form>
        </div>
    </div>

    <!-- Resultados -->
    <div style="background: #f8f9fa; min-height: 400px; padding-bottom: 60px;">
        <div class="container">

            <?php if (!$q_display): ?>

                <div class="buscar_vacio">
                    <i class="fa fa-search"></i>
                    <h3>Ingresa un término de búsqueda</h3>
                    <p>Puedes buscar predicaciones, biografías o actividades.</p>
                </div>

            <?php elseif ($total === 0): ?>

                <div class="buscar_vacio">
                    <i class="fa fa-frown-o"></i>
                    <h3>Sin resultados</h3>
                    <p>No encontramos nada relacionado con "<b><?php echo $q_display; ?></b>".<br>Intenta con otras palabras.</p>
                </div>

            <?php else: ?>

                <?php if ($sermones): ?>
                <div class="buscar_seccion">
                    <div class="buscar_seccion_titulo"><i class="fa fa-microphone"></i> &nbsp;Predicaciones</div>
                    <?php foreach ($sermones as $s): ?>
                        <a href="blog.php?id=<?php echo $s['idsermones']; ?>" class="buscar_item">
                            <div class="bi_icon"><i class="fa fa-microphone"></i></div>
                            <div>
                                <div class="bi_titulo"><?php echo htmlspecialchars($s['nom_sermon']); ?></div>
                                <div class="bi_sub"><?php echo htmlspecialchars($s['predicador']); ?> &nbsp;·&nbsp; <?php echo htmlspecialchars($s['fecha_eti']); ?></div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <?php if ($biografias): ?>
                <div class="buscar_seccion">
                    <div class="buscar_seccion_titulo"><i class="fa fa-user"></i> &nbsp;Biografías</div>
                    <?php foreach ($biografias as $b): ?>
                        <a href="biografia_detalle.php?id=<?php echo $b['idbiografia']; ?>" class="buscar_item">
                            <div class="bi_icon">
                                <?php if ($b['imagen']): ?>
                                    <img src="<?php echo htmlspecialchars($b['imagen']); ?>" style="width:48px;height:48px;border-radius:50%;object-fit:cover;">
                                <?php else: ?>
                                    <i class="fa fa-user"></i>
                                <?php endif; ?>
                            </div>
                            <div>
                                <div class="bi_titulo"><?php echo htmlspecialchars($b['nombre']); ?></div>
                                <?php if ($b['cargo']): ?>
                                    <div class="bi_sub"><?php echo htmlspecialchars($b['cargo']); ?></div>
                                <?php endif; ?>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <?php if ($paginas): ?>
                <div class="buscar_seccion">
                    <div class="buscar_seccion_titulo"><i class="fa fa-file-text-o"></i> &nbsp;Páginas</div>
                    <?php foreach ($paginas as $pg): ?>
                        <a href="<?php echo htmlspecialchars($pg['url']); ?>" class="buscar_item">
                            <div class="bi_icon"><i class="fa <?php echo $pg['icono']; ?>"></i></div>
                            <div>
                                <div class="bi_titulo"><?php echo htmlspecialchars($pg['titulo']); ?></div>
                                <div class="bi_sub"><?php echo htmlspecialchars($pg['desc']); ?></div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <?php if ($actividades): ?>
                <div class="buscar_seccion">
                    <div class="buscar_seccion_titulo"><i class="fa fa-calendar"></i> &nbsp;Próximas Actividades</div>
                    <?php foreach ($actividades as $a): ?>
                        <a href="./#calendario" class="buscar_item">
                            <div class="bi_icon"><i class="fa fa-calendar"></i></div>
                            <div>
                                <div class="bi_titulo"><?php echo htmlspecialchars($a['nom_activ']); ?></div>
                                <div class="bi_sub">
                                    <?php if ($a['tema']) echo htmlspecialchars($a['tema']) . ' &nbsp;·&nbsp; '; ?>
                                    <?php echo $a['fecha']; ?>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

            <?php endif; ?>

        </div>
    </div>

    <?php require 'footer.php'; ?>

</div>

<script src="plugins/parallax-js-master/parallax.min.js"></script>
</body>
</html>
