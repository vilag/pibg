<!DOCTYPE html>
<html lang="es">

<head>
    <title>Biografía</title>
    <link href="images/iconos/icono.png" rel="icon">
    <link rel="stylesheet" type="text/css" href="styles/bootstrap4/bootstrap.min.css">
    <link href="plugins/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="styles/news.css">
    <link rel="stylesheet" type="text/css" href="styles/news_responsive.css">
    <link rel="stylesheet" type="text/css" href="styles/biografia_detalle.css">
</head>

<body>

    <div class="super_container">

        <?php require 'header.php'; ?>

        <?php
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

        require('config/global.php');
        $connection = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
        mysqli_set_charset($connection, DB_ENCODE);

        $nombre = '';
        $cargo = '';
        $biografia = '';
        $imagen = '';
        $fecha = '';

        if ($connection && $id > 0) {
            $result = mysqli_query($connection, "SELECT * FROM biografias WHERE idbiografia = $id LIMIT 1");
            if ($result && mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $nombre = htmlspecialchars($row['nombre']);
                $cargo = htmlspecialchars($row['cargo']);
                $biografia = htmlspecialchars($row['biografia']);
                $imagen = htmlspecialchars($row['imagen']);
                $fecha = date('d \d\e F \d\e Y', strtotime($row['fecha_registro']));
            }
        }
        ?>

        <!-- Banner -->
        <div class="home">
            <div class="home_background parallax_background parallax-window" data-parallax="scroll"
                data-image-src="images/about/about_background.jpg" data-speed="0.8"></div>
            <div class="home_container">
                <div class="container">
                    <div class="row">
                        <div class="col">
                            <div class="home_content text-center">
                                <div class="home_title" style=" color: #24344B !important;">
                                    <?php echo $nombre ?: 'Biografía'; ?>
                                </div>
                                <div class="breadcrumbs" style="color: #3e5992 !important;">
                                    <ul style="color: #3e5992 !important;">
                                        <li><a href="./">Inicio</a>
                                        </li>
                                        <li><a href="biografias.php">Biografías</a>
                                        </li>
                                        <li><?php echo $nombre; ?>
                                        </li>
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

                    <!-- Artículo principal -->
                    <div class="col-lg-8">
                        <?php if (!$nombre): ?>
                            <div style="padding:60px 0; text-align:center; color:#aaa;">
                                <i class="fa fa-user" style="font-size:48px; display:block; margin-bottom:16px;"></i>
                                Biografía no encontrada.
                                <br><br>
                                <a href="biografias.php" class="back_link"><i class="fa fa-arrow-left"></i> Volver a
                                    Biografías</a>
                            </div>
                        <?php else: ?>
                            <div class="news_posts">
                                <div class="news_post">

                                    <?php if ($imagen): ?>
                                        <div class="news_post_image">
                                            <img src="<?php echo $imagen; ?>" alt="<?php echo $nombre; ?>">
                                        </div>
                                    <?php endif; ?>

                                    <div class="news_post_body">

                                        <a href="biografias.php" class="back_link">
                                            <i class="fa fa-arrow-left"></i> Volver a Biografías
                                        </a>

                                        <?php if ($cargo): ?>
                                            <div><span class="bio_cargo_badge"><?php echo $cargo; ?></span></div>
                                        <?php endif; ?>

                                        <div class="news_post_title">
                                            <a href="#"><?php echo $nombre; ?></a>
                                        </div>

                                        <div class="news_post_meta d-flex flex-row align-items-start justify-content-start">
                                            <div class="news_post_date">
                                                <a href="#"><i class="fa fa-calendar"
                                                        style="margin-right:5px;"></i><?php echo $fecha; ?></a>
                                            </div>
                                        </div>

                                        <hr class="bio_divider">

                                        <div class="news_post_text bio_texto_completo">
                                            <?php echo $biografia; ?>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Sidebar -->
                    <div class="col-lg-4">
                        <div class="sidebar">
                            <div class="sidebar_latest_posts">
                                <div class="sidebar_title">Otras Biografías</div>
                                <div class="latest_posts">
                                    <?php
                                    if ($connection) {
                                        $sql_otros = "SELECT idbiografia, nombre, cargo, imagen FROM biografias WHERE idbiografia != $id ORDER BY idbiografia DESC LIMIT 5";
                                        $res_otros = mysqli_query($connection, $sql_otros);
                                        if ($res_otros && mysqli_num_rows($res_otros) > 0) {
                                            while ($otro = mysqli_fetch_assoc($res_otros)) {
                                                $o_nombre = htmlspecialchars($otro['nombre']);
                                                $o_cargo = htmlspecialchars($otro['cargo']);
                                                $o_img = htmlspecialchars($otro['imagen']);
                                                $o_id = (int) $otro['idbiografia'];
                                                echo "<div class='latest_post d-flex flex-row align-items-start justify-content-start'>";
                                                echo "<div>";
                                                if ($o_img) {
                                                    echo "<div class='latest_post_image'><img src='" . $o_img . "' alt='" . $o_nombre . "' style='width:50px;height:50px;border-radius:50%;object-fit:cover;'></div>";
                                                } else {
                                                    echo "<div class='bio_sidebar_foto'><i class='fa fa-user'></i></div>";
                                                }
                                                echo "</div>";
                                                echo "<div class='latest_post_body'>";
                                                echo "<div class='latest_post_title'><a href='biografia_detalle.php?id=$o_id'>" . $o_nombre . "</a></div>";
                                                if ($o_cargo)
                                                    echo "<div class='latest_post_author'>" . $o_cargo . "</div>";
                                                echo "</div></div>";
                                            }
                                        } else {
                                            echo "<p style='color:#aaa; font-size:14px;'>No hay otras biografías.</p>";
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

    <script src="plugins/parallax-js-master/parallax.min.js"></script>
</body>

</html>