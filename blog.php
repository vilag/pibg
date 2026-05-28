<!DOCTYPE html>
<html lang="es">
<head>
<title>Predicaciones</title>
<link href="images/iconos/icono.png" rel="icon">
<link rel="stylesheet" type="text/css" href="styles/bootstrap4/bootstrap.min.css">
<link href="plugins/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link href="plugins/video-js/video-js.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="styles/news.css">
<link rel="stylesheet" type="text/css" href="styles/news_responsive.css">
<style>
.serie_nav_block { background: #f0f4f8; border-left: 4px solid #042C49; padding: 14px 18px; border-radius: 0 8px 8px 0; margin-bottom: 28px; }
.serie_nav_block .serie_label { font-size: 12px; color: #888; margin-bottom: 4px; }
.serie_nav_block .serie_nombre_link { font-size: 16px; font-weight: 700; color: #042C49; text-decoration: none; }
.serie_nav_block .serie_nombre_link:hover { text-decoration: underline; }
.serie_nav_prev_next { display: flex; justify-content: space-between; margin-top: 30px; margin-bottom: 10px; flex-wrap: wrap; gap: 10px; }
.nav_btn { padding: 10px 18px; border-radius: 8px; background: #042C49; color: #fff; text-decoration: none; font-size: 13px; display: inline-block; }
.nav_btn:hover { background: #063d6b; color: #fff; text-decoration: none; }
.nav_btn.disabled { background: #ccc; pointer-events: none; }
.sermon_meta_footer { margin-top: 40px; padding: 16px 20px; background: #f8f9fa; border-radius: 8px; border-left: 4px solid #042C49; }
.sermon_meta_footer b { color: #042C49; }
</style>
</head>
<body>

<div class="super_container">

    <?php require 'header.php'; ?>

    <?php
        $id        = isset($_GET['id'])  ? (int)$_GET['id']  : 1;
        $categoria = isset($_GET['cat']) ? (int)$_GET['cat'] : 1;

        require('config/global.php');
        $connection = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
        mysqli_set_charset($connection, 'utf8');

        $fecha = $nom_sermon = $predicador = $actividad = $predicacion = '';
        $imagen  = 'images/predicaciones/portadas/fondo1.png';
        $serie_id = 0; $orden_serie = 0;

        if ($connection) {
            $r = mysqli_query($connection, "SELECT * FROM sermones WHERE idsermones = $id LIMIT 1");
            if ($r && $row = mysqli_fetch_assoc($r)) {
                $fecha       = $row['fecha_eti'];
                $nom_sermon  = $row['nom_sermon'];
                $predicador  = $row['predicador'];
                $actividad   = $row['actividad'];
                $predicacion = $row['predicacion'];
                $imagen      = $row['imagen'] ?: $imagen;
                $serie_id    = isset($row['serie_id'])    ? (int)$row['serie_id']    : 0;
                $orden_serie = isset($row['orden_serie']) ? (int)$row['orden_serie'] : 0;
            }
        }

        // Datos de la serie (si aplica)
        $serie_nombre = '';
        $sermon_prev  = null;
        $sermon_next  = null;
        $total_serie  = 0;

        if ($connection && $serie_id > 0) {
            $rs = mysqli_query($connection, "SELECT nombre FROM series_especiales WHERE idserie = $serie_id LIMIT 1");
            if ($rs && $sr = mysqli_fetch_assoc($rs)) { $serie_nombre = $sr['nombre']; }

            $rt = mysqli_query($connection, "SELECT COUNT(*) AS t FROM sermones WHERE serie_id = $serie_id");
            if ($rt && $rt_row = mysqli_fetch_assoc($rt)) { $total_serie = (int)$rt_row['t']; }

            $rp = mysqli_query($connection, "SELECT idsermones, nom_sermon FROM sermones WHERE serie_id = $serie_id AND orden_serie < $orden_serie ORDER BY orden_serie DESC LIMIT 1");
            if ($rp && $rp_row = mysqli_fetch_assoc($rp)) { $sermon_prev = $rp_row; }

            $rn = mysqli_query($connection, "SELECT idsermones, nom_sermon FROM sermones WHERE serie_id = $serie_id AND orden_serie > $orden_serie ORDER BY orden_serie ASC LIMIT 1");
            if ($rn && $rn_row = mysqli_fetch_assoc($rn)) { $sermon_next = $rn_row; }
        }
    ?>

    <!-- Hero -->
    <div class="home">
        <input id="input_vista" type="hidden" value="0">
        <div class="home_background parallax_background parallax-window" data-parallax="scroll" data-image-src="images/predicaciones/caratula.png" data-speed="0.8"></div>
        <div class="home_container">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="home_content text-center">
                            <div class="home_title" style="text-shadow:5px 5px 10px rgba(0,0,0,.5);">Predicaciones</div>
                            <div class="breadcrumbs">
                                <ul>
                                    <li><a href="./" style="text-shadow:5px 5px 10px rgba(0,0,0,.5);">Inicio</a></li>
                                    <li><a href="predicaciones.php" style="text-shadow:5px 5px 10px rgba(0,0,0,.5);">Predicaciones</a></li>
                                    <li style="text-shadow:5px 5px 10px rgba(0,0,0,.5);"><?php echo htmlspecialchars($nom_sermon); ?></li>
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
            <div class="row">

                <!-- Sermón -->
                <div class="col-lg-8">

                    <!-- Banner de serie -->
                    <?php if ($serie_id > 0 && $serie_nombre): ?>
                    <div class="serie_nav_block">
                        <div class="serie_label">Esta predicación es parte de la serie:</div>
                        <a href="serie.php?id=<?php echo $serie_id; ?>" class="serie_nombre_link">
                            <i class="fa fa-star" style="color:#e09000;margin-right:6px;"></i><?php echo htmlspecialchars($serie_nombre); ?>
                        </a>
                        <div style="font-size:12px;color:#888;margin-top:4px;">Parte <?php echo $orden_serie; ?> de <?php echo $total_serie; ?> &nbsp;·&nbsp; <a href="serie.php?id=<?php echo $serie_id; ?>" style="color:#042C49;">Ver serie completa</a></div>
                    </div>
                    <?php endif; ?>

                    <div class="news_posts">
                        <div class="news_post">
                            <div class="news_post_image">
                                <img src="<?php echo htmlspecialchars($imagen); ?>" alt="" onerror="this.src='images/predicaciones/portadas/fondo1.png'">
                            </div>
                            <div class="news_post_body">
                                <div class="news_post_date"><a href="#"><?php echo htmlspecialchars($fecha); ?></a></div>
                                <div class="news_post_title"><a href="#"><?php echo htmlspecialchars($nom_sermon); ?></a></div>
                                <div class="news_post_meta d-flex flex-row align-items-start justify-content-start">
                                    <div class="news_post_author"><a href="#"><?php echo htmlspecialchars($predicador); ?></a></div>
                                    <div class="news_post_tags">
                                        <ul><li><a href="#"><?php echo htmlspecialchars($actividad); ?></a></li></ul>
                                    </div>
                                </div>
                                <div class="news_post_text"><?php echo $predicacion; ?></div>

                                <!-- Datos del sermón -->
                                <div class="sermon_meta_footer">
                                    <b><?php echo htmlspecialchars($predicador); ?></b><br>
                                    <label><?php echo htmlspecialchars($actividad); ?>, <?php echo htmlspecialchars($fecha); ?></label>
                                </div>

                                <!-- Navegación entre sermones de la serie -->
                                <?php if ($serie_id > 0): ?>
                                <div class="serie_nav_prev_next">
                                    <?php if ($sermon_prev): ?>
                                        <a class="nav_btn" href="blog.php?id=<?php echo $sermon_prev['idsermones']; ?>&cat=<?php echo $categoria; ?>">
                                            ← <?php echo htmlspecialchars(mb_substr($sermon_prev['nom_sermon'], 0, 35)); ?>...
                                        </a>
                                    <?php else: ?>
                                        <span class="nav_btn disabled">← Primera de la serie</span>
                                    <?php endif; ?>

                                    <?php if ($sermon_next): ?>
                                        <a class="nav_btn" href="blog.php?id=<?php echo $sermon_next['idsermones']; ?>&cat=<?php echo $categoria; ?>">
                                            <?php echo htmlspecialchars(mb_substr($sermon_next['nom_sermon'], 0, 35)); ?>... →
                                        </a>
                                    <?php else: ?>
                                        <span class="nav_btn disabled">Última de la serie →</span>
                                    <?php endif; ?>
                                </div>
                                <div style="text-align:center;margin-top:6px;">
                                    <a href="serie.php?id=<?php echo $serie_id; ?>" style="color:#042C49;font-size:13px;">Ver todos los sermones de esta serie</a>
                                </div>
                                <?php endif; ?>

                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <div class="sidebar">
                        <div class="sidebar_categories">
                            <div class="sidebar_title">Categorías</div>
                            <div class="sidebar_links">
                                <ul>
                                    <?php
                                    if ($connection) {
                                        $rc = mysqli_query($connection, "SELECT * FROM cat_sermones");
                                        $cnt = 1;
                                        while ($c = mysqli_fetch_assoc($rc)) {
                                            echo "<li><a href='blog.php?id=$id&cat={$cnt}'>" . htmlspecialchars($c['nombre']) . "</a></li>";
                                            $cnt++;
                                        }
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>

                        <div class="sidebar_latest_posts">
                            <div class="sidebar_title">Últimas publicaciones</div>
                            <div class="latest_posts">
                                <?php
                                if ($connection) {
                                    $ql = $categoria > 0
                                        ? "SELECT * FROM sermones WHERE categoria = $categoria ORDER BY idsermones DESC LIMIT 3"
                                        : "SELECT * FROM sermones ORDER BY idsermones DESC LIMIT 3";
                                    $rl = mysqli_query($connection, $ql);
                                    while ($colum = mysqli_fetch_assoc($rl)) {
                                        echo "<div class='latest_post d-flex flex-row align-items-start justify-content-start'>";
                                        echo "<div><div class='latest_post_image'><img src='" . htmlspecialchars($colum['imagen']) . "' alt='' onerror=\"this.src='images/predicaciones/portadas/fondo1.png'\"></div></div>";
                                        echo "<div class='latest_post_body'>";
                                        echo "<div class='latest_post_date'>" . htmlspecialchars($colum['fecha_eti']) . "</div>";
                                        echo "<div class='latest_post_title'><a href='blog.php?id=" . $colum['idsermones'] . "&cat=$categoria'>" . htmlspecialchars($colum['nom_sermon']) . "</a></div>";
                                        echo "<div class='latest_post_author'>" . htmlspecialchars($colum['predicador']) . "</div>";
                                        echo "</div></div>";
                                    }
                                    mysqli_close($connection);
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <?php require 'footer.php'; ?>
</div>

<script type="text/javascript" src="scripts/blog.js?v=<?php echo rand(); ?>"></script>
<script src="js/jquery-3.2.1.min.js"></script>
<script src="styles/bootstrap4/popper.js"></script>
<script src="styles/bootstrap4/bootstrap.min.js"></script>
<script src="plugins/easing/easing.js"></script>
<script src="plugins/parallax-js-master/parallax.min.js"></script>
</body>
</html>
