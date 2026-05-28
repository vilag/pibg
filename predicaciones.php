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
.series_section { margin-bottom: 40px; }
.series_section_title { font-size: 20px; font-weight: 700; color: #042C49; border-bottom: 3px solid #042C49; padding-bottom: 8px; margin-bottom: 20px; }
.serie_card { border-radius: 10px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,.1); transition: transform .2s, box-shadow .2s; margin-bottom: 20px; background: #fff; }
.serie_card:hover { transform: translateY(-3px); box-shadow: 0 6px 20px rgba(0,0,0,.15); }
.serie_card img { width: 100%; height: 160px; object-fit: cover; }
.serie_card_body { padding: 14px 16px; }
.serie_card_nombre { font-size: 15px; font-weight: 700; color: #042C49; margin-bottom: 5px; }
.serie_card_desc { font-size: 12px; color: #666; margin-bottom: 8px; line-height: 1.4; }
.serie_card_meta { font-size: 11px; color: #aaa; }
.serie_card_btn { display: block; background: #042C49; color: #fff; text-align: center; padding: 8px; border-radius: 0 0 10px 10px; font-size: 13px; text-decoration: none; }
.serie_card_btn:hover { background: #063d6b; color: #fff; text-decoration: none; }
</style>
</head>
<body>

<div class="super_container">

    <?php require 'header.php'; ?>

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
                                    <li style="text-shadow:5px 5px 10px rgba(0,0,0,.5);">Predicaciones</li>
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

            <?php
                $categoria = isset($_GET['cat']) ? (int)$_GET['cat'] : 0;
                require('config/global.php');
                $connection = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
                mysqli_set_charset($connection, 'utf8');
            ?>

            <!-- Sección: Series Especiales -->
            <?php
            if ($connection) {
                $res_series = mysqli_query($connection,
                    "SELECT * FROM series_especiales WHERE estatus = 1 ORDER BY idserie DESC LIMIT 6");
                if ($res_series && mysqli_num_rows($res_series) > 0):
            ?>
            <div class="series_section">
                <div class="series_section_title"><i class="fa fa-star" style="color:#e09000;margin-right:8px;"></i>Series Especiales</div>
                <div class="row">
                    <?php
                    $meses = ['','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
                    while ($serie = mysqli_fetch_assoc($res_series)):
                        $img = $serie['imagen'] ?: 'images/predicaciones/caratula.png';
                        $desc = mb_substr($serie['descripcion'], 0, 80);
                        $total_s = mysqli_fetch_assoc(mysqli_query($connection,
                            "SELECT COUNT(*) AS t FROM sermones WHERE serie_id = " . (int)$serie['idserie']));
                        $total_n = $total_s['t'];
                        $fi = $serie['fecha_inicio'] ? explode('-', $serie['fecha_inicio']) : null;
                    ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="serie_card">
                            <img src="<?php echo htmlspecialchars($img); ?>" alt="<?php echo htmlspecialchars($serie['nombre']); ?>" onerror="this.src='images/predicaciones/caratula.png'">
                            <div class="serie_card_body">
                                <div class="serie_card_nombre"><?php echo htmlspecialchars($serie['nombre']); ?></div>
                                <?php if ($desc): ?><div class="serie_card_desc"><?php echo htmlspecialchars($desc); ?>...</div><?php endif; ?>
                                <div class="serie_card_meta">
                                    <i class="fa fa-microphone"></i> <?php echo $total_n; ?> predicaci<?php echo $total_n == 1 ? 'ón' : 'ones'; ?>
                                    <?php if ($fi): ?> &nbsp;·&nbsp; <i class="fa fa-calendar"></i> <?php echo $fi[2] . ' ' . $meses[(int)$fi[1]] . ' ' . $fi[0]; ?><?php endif; ?>
                                </div>
                            </div>
                            <a class="serie_card_btn" href="serie.php?id=<?php echo $serie['idserie']; ?>">Ver serie completa →</a>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>
            <?php endif; } ?>

            <div class="row">

                <!-- Predicaciones -->
                <div class="col-lg-8">
                    <div class="news_posts">
                        <?php
                        if ($connection) {
                            if ($categoria > 0) {
                                $consulta = "SELECT * FROM sermones WHERE categoria = $categoria ORDER BY idsermones DESC LIMIT 12";
                            } else {
                                $consulta = "SELECT * FROM sermones ORDER BY idsermones DESC LIMIT 12";
                            }
                            $result = mysqli_query($connection, $consulta);
                            if ($result && mysqli_num_rows($result) > 0) {
                                while ($colum = mysqli_fetch_assoc($result)) {
                                    $pred_corta = mb_substr(strip_tags($colum['predicacion']), 0, 400);
                                    echo "<div class='news_post'>";
                                    echo "<div class='news_post_image'><img src='" . htmlspecialchars($colum['imagen']) . "' alt='' onerror=\"this.src='images/predicaciones/portadas/fondo1.png'\"></div>";
                                    echo "<div class='news_post_body'>";
                                    echo "<div class='news_post_date'><a href='#'>" . htmlspecialchars($colum['fecha_eti']) . "</a></div>";
                                    echo "<div class='news_post_title'><a href='blog.php?id=" . $colum['idsermones'] . "&cat=" . $colum['categoria'] . "'>" . htmlspecialchars($colum['nom_sermon']) . "</a></div>";
                                    echo "<div class='news_post_meta d-flex flex-row align-items-start justify-content-start'>";
                                    echo "<div class='news_post_author'><a href='#'>" . htmlspecialchars($colum['predicador']) . "</a></div>";
                                    echo "<div class='news_post_comments'><a href='#'>" . htmlspecialchars($colum['actividad']) . "</a></div>";
                                    echo "</div>";
                                    echo "<div class='news_post_text'><p>" . htmlspecialchars($pred_corta) . "...</p></div>";
                                    echo "<div class='news_post_link'><a href='blog.php?id=" . $colum['idsermones'] . "&cat=" . $colum['categoria'] . "'>Seguir leyendo</a></div>";
                                    echo "</div></div>";
                                }
                            } else {
                                echo "<p style='color:#888;padding:20px 0;'>No hay predicaciones en esta categoría.</p>";
                            }
                        }
                        ?>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <div class="sidebar">
                        <div class="sidebar_categories">
                            <div class="sidebar_title">Categorías</div>
                            <div class="sidebar_links">
                                <ul>
                                    <li><a href="predicaciones.php">Todas</a></li>
                                    <?php
                                    if ($connection) {
                                        $rc = mysqli_query($connection, "SELECT * FROM cat_sermones");
                                        $cnt = 1;
                                        while ($c = mysqli_fetch_assoc($rc)) {
                                            $active = ($categoria == $cnt) ? " style='font-weight:bold;'" : '';
                                            echo "<li><a href='predicaciones.php?cat={$cnt}'{$active}>" . htmlspecialchars($c['nombre']) . "</a></li>";
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
                                    $q = $categoria > 0
                                        ? "SELECT * FROM sermones WHERE categoria = $categoria ORDER BY idsermones DESC LIMIT 5"
                                        : "SELECT * FROM sermones ORDER BY idsermones DESC LIMIT 5";
                                    $rl = mysqli_query($connection, $q);
                                    while ($colum = mysqli_fetch_assoc($rl)) {
                                        echo "<div class='latest_post d-flex flex-row align-items-start justify-content-start'>";
                                        echo "<div><div class='latest_post_image'><img src='" . htmlspecialchars($colum['imagen']) . "' alt='' onerror=\"this.src='images/predicaciones/portadas/fondo1.png'\"></div></div>";
                                        echo "<div class='latest_post_body'>";
                                        echo "<div class='latest_post_date'>" . htmlspecialchars($colum['fecha_eti']) . "</div>";
                                        echo "<div class='latest_post_title'><a href='blog.php?id=" . $colum['idsermones'] . "&cat=" . $colum['categoria'] . "'>" . htmlspecialchars($colum['nom_sermon']) . "</a></div>";
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

<script type="text/javascript" src="scripts/predic.js?v=<?php echo rand(); ?>"></script>
<script src="js/jquery-3.2.1.min.js"></script>
<script src="styles/bootstrap4/popper.js"></script>
<script src="styles/bootstrap4/bootstrap.min.js"></script>
<script src="plugins/easing/easing.js"></script>
<script src="plugins/parallax-js-master/parallax.min.js"></script>
</body>
</html>
