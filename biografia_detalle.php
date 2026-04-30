<!DOCTYPE html>
<html lang="es">

<head>
    <title>Biografía</title>
    <link href="images/iconos/icono.png" rel="icon">
    <link rel="stylesheet" type="text/css" href="styles/bootstrap4/bootstrap.min.css">
    <link href="plugins/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="styles/news.css">
    <link rel="stylesheet" type="text/css" href="styles/news_responsive.css">
    <style>
        .bio_foto_autor {
            width: 110px;
            height: 110px;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid #24344B;
            margin-right: 20px;
            flex-shrink: 0;
        }

        .bio_foto_placeholder {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            background: linear-gradient(135deg, #042C49, #24344B);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 20px;
            flex-shrink: 0;
        }

        .bio_foto_placeholder i {
            font-size: 50px;
            color: rgba(255, 255, 255, 0.5);
        }

        .bio_cargo_badge {
            display: inline-block;
            background-color: #F85E0C;
            color: #fff;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 4px 12px;
            border-radius: 20px;
            margin-bottom: 16px;
        }

        .bio_texto_completo {
            font-size: 16px;
            line-height: 1.9;
            color: #444;
            white-space: pre-line;
        }

        .bio_divider {
            border: none;
            border-top: 2px solid #f0f0f0;
            margin: 30px 0;
        }

        .bio_header_wrap {
            display: flex;
            align-items: center;
            margin-bottom: 24px;
        }

        .bio_header_info h2 {
            margin: 0 0 4px;
            font-size: 26px;
            color: #24344B;
            font-weight: 700;
        }

        .bio_header_info .fecha {
            font-size: 13px;
            color: #aaa;
        }

        /* Sidebar - otras biografías */
        .latest_post_image img {
            border-radius: 50%;
            object-fit: cover;
        }

        .bio_sidebar_foto {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, #042C49, #24344B);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .bio_sidebar_foto i {
            font-size: 22px;
            color: rgba(255, 255, 255, 0.5);
        }

        .back_link {
            display: inline-flex;
            align-items: center;
            color: #042C49;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 24px;
            text-decoration: none;
            gap: 6px;
            transition: color 0.2s;
        }

        .back_link:hover {
            color: #F85E0C;
            text-decoration: none;
        }
    </style>
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
                                <div class="home_title" style=" color: #3e5992 !important;">
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
    <script src="js/news.js"></script>
</body>

</html>