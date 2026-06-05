<!DOCTYPE html>
<html lang="es">
<head>
<title>Serie Especial</title>
<link href="images/iconos/icono.png" rel="icon">
<link rel="stylesheet" type="text/css" href="styles/bootstrap4/bootstrap.min.css">
<link href="plugins/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="styles/news.css">
<link rel="stylesheet" type="text/css" href="styles/news_responsive.css">
<link rel="stylesheet" type="text/css" href="styles/serie.css">
</head>
<body>

<div class="super_container">

    <?php require 'header.php'; ?>

    <?php
        $idserie = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        require('config/global.php');
        $conn = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
        mysqli_set_charset($conn, 'utf8');

        $serie = null;
        $serie_nombre = 'Serie Especial';
        $serie_descripcion = '';
        $serie_imagen = 'images/predicaciones/caratula.png';

        if ($conn && $idserie > 0) {
            $r = mysqli_query($conn, "SELECT * FROM series_especiales WHERE idserie = $idserie AND estatus = 1 LIMIT 1");
            if ($r && mysqli_num_rows($r) > 0) {
                $serie = mysqli_fetch_assoc($r);
                $serie_nombre      = $serie['nombre'];
                $serie_descripcion = $serie['descripcion'];
                if ($serie['imagen']) $serie_imagen = $serie['imagen'];
            }
        }
    ?>

    <!-- Encabezado de la serie -->
    <div class="home">
        <input id="input_vista" type="hidden" value="0">
        <div class="home_background parallax_background parallax-window" data-parallax="scroll" data-image-src="<?php echo htmlspecialchars($serie_imagen); ?>" data-speed="0.8"></div>
        <div class="home_container">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="home_content text-center">
                            <div class="serie_hero_info">
                                <div class="home_title" style="text-shadow: 5px 5px 10px rgba(0,0,0,0.5); font-size: 28px;"><?php echo htmlspecialchars($serie_nombre); ?></div>
                                <?php if ($serie_descripcion): ?>
                                <div class="serie_descripcion"><?php echo htmlspecialchars($serie_descripcion); ?></div>
                                <?php endif; ?>
                                <?php if ($serie && $serie['fecha_inicio']): ?>
                                <div class="serie_fechas">
                                    <i class="fa fa-calendar"></i>
                                    <?php
                                        $meses = ['','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
                                        $fi = $serie['fecha_inicio'] ? explode('-', $serie['fecha_inicio']) : null;
                                        $ff = $serie['fecha_fin']    ? explode('-', $serie['fecha_fin'])    : null;
                                        if ($fi) echo $fi[2] . ' de ' . $meses[(int)$fi[1]] . ' de ' . $fi[0];
                                        if ($ff) echo ' &mdash; ' . $ff[2] . ' de ' . $meses[(int)$ff[1]] . ' de ' . $ff[0];
                                    ?>
                                </div>
                                <?php endif; ?>
                            </div>
                            <div class="breadcrumbs" style="margin-top:12px;">
                                <ul>
                                    <li><a href="./" style="text-shadow:5px 5px 10px rgba(0,0,0,.5);">Inicio</a></li>
                                    <li><a href="predicaciones.php" style="text-shadow:5px 5px 10px rgba(0,0,0,.5);">Predicaciones</a></li>
                                    <li style="text-shadow:5px 5px 10px rgba(0,0,0,.5);"><?php echo htmlspecialchars($serie_nombre); ?></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenido -->
    <div class="news">
        <div class="container">
            <?php if (!$serie): ?>
                <div class="row"><div class="col"><p style="padding:40px 0;text-align:center;color:#888;">Serie no encontrada.</p></div></div>
            <?php else:
                $res_serm = mysqli_query($conn, "SELECT * FROM sermones WHERE serie_id = $idserie ORDER BY orden_serie ASC, idsermones ASC");
                $total = mysqli_num_rows($res_serm);
                $sermones = [];
                while ($s = mysqli_fetch_assoc($res_serm)) { $sermones[] = $s; }
            ?>
            <div class="row">
                <!-- Lista de sermones -->
                <div class="col-lg-8">
                    <div class="serie_nav_bar">
                        <span><i class="fa fa-list"></i> <?php echo $total; ?> predicaci<?php echo $total === 1 ? 'ón' : 'ones'; ?> en esta serie</span>
                        <a href="predicaciones.php"><i class="fa fa-arrow-left"></i> Todas las predicaciones</a>
                    </div>

                    <?php if ($total === 0): ?>
                        <p style="color:#888;padding:20px 0;">Aún no hay predicaciones registradas en esta serie.</p>
                    <?php else: ?>
                    <div class="news_posts">
                        <?php foreach ($sermones as $i => $s):
                            $parte = $i + 1;
                            $pred_corta = mb_substr(strip_tags($s['predicacion']), 0, 350);
                        ?>
                        <div class="news_post">
                            <div class="news_post_image">
                                <img src="<?php echo htmlspecialchars($s['imagen']); ?>" alt="" onerror="this.src='images/predicaciones/portadas/fondo1.png'">
                            </div>
                            <div class="news_post_body">
                                <div class="news_post_date">
                                    <a href="#"><?php echo htmlspecialchars($s['fecha_eti']); ?></a>
                                    <span style="margin-left:10px;background:#042C49;color:#fff;padding:2px 10px;border-radius:10px;font-size:11px;">Parte <?php echo $parte; ?> de <?php echo $total; ?></span>
                                </div>
                                <div class="news_post_title">
                                    <a href="blog.php?id=<?php echo $s['idsermones']; ?>&cat=<?php echo $s['categoria']; ?>"><?php echo htmlspecialchars($s['nom_sermon']); ?></a>
                                </div>
                                <div class="news_post_meta d-flex flex-row align-items-start justify-content-start">
                                    <div class="news_post_author"><a href="#"><?php echo htmlspecialchars($s['predicador']); ?></a></div>
                                    <div class="news_post_comments"><a href="#"><?php echo htmlspecialchars($s['actividad']); ?></a></div>
                                </div>
                                <div class="news_post_text">
                                    <p><?php echo htmlspecialchars($pred_corta); ?>...</p>
                                </div>
                                <div class="news_post_link">
                                    <a href="blog.php?id=<?php echo $s['idsermones']; ?>&cat=<?php echo $s['categoria']; ?>">Seguir leyendo</a>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <div class="sidebar">
                        <div class="sidebar_categories">
                            <div class="sidebar_title">Todas las predicaciones</div>
                            <div class="sidebar_links">
                                <ul>
                                    <?php
                                    $rc = mysqli_query($conn, "SELECT * FROM cat_sermones");
                                    $contador = 1;
                                    while ($c = mysqli_fetch_assoc($rc)) {
                                        echo "<li><a href='predicaciones.php?cat={$contador}'>" . htmlspecialchars($c['nombre']) . "</a></li>";
                                        $contador++;
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>

                        <div class="sidebar_latest_posts">
                            <div class="sidebar_title">Índice de esta serie</div>
                            <div class="latest_posts">
                                <?php foreach ($sermones as $i => $s): $parte = $i + 1; ?>
                                <div class="nav_sermon_card">
                                    <div class="nav_sermon_num">Parte <?php echo $parte; ?></div>
                                    <div class="nav_sermon_titulo">
                                        <a href="blog.php?id=<?php echo $s['idsermones']; ?>&cat=<?php echo $s['categoria']; ?>"><?php echo htmlspecialchars($s['nom_sermon']); ?></a>
                                    </div>
                                    <div class="nav_sermon_meta"><?php echo htmlspecialchars($s['predicador']); ?> · <?php echo htmlspecialchars($s['fecha_eti']); ?></div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <?php require 'footer.php'; mysqli_close($conn); ?>
</div>

<script src="js/jquery-3.2.1.min.js"></script>
<script src="styles/bootstrap4/popper.js"></script>
<script src="styles/bootstrap4/bootstrap.min.js"></script>
<script src="plugins/easing/easing.js"></script>
<script src="plugins/parallax-js-master/parallax.min.js"></script>
</body>
</html>
