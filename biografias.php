<!DOCTYPE html>
<html lang="es">
<head>
<title>Biografías - Primera Iglesia Bautista de Guadalajara</title>
<link href="images/iconos/icono.png" rel="icon">
<link rel="stylesheet" type="text/css" href="styles/bootstrap4/bootstrap.min.css">
<link href="plugins/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="styles/about.css">
<link rel="stylesheet" type="text/css" href="styles/about_responsive.css">
<link rel="stylesheet" type="text/css" href="styles/biografias.css">
</head>
<body>

<div class="super_container">

    <?php require 'header.php'; ?>

    <!-- Banner -->
    <div class="home">
        <div class="home_background parallax_background parallax-window"
             data-parallax="scroll"
             data-image-src="images/about/about_background.jpg"
             data-speed="0.8"></div>
        <div class="home_container">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="home_content text-center">
                            <div class="home_title">Biografías</div>
                            <div class="breadcrumbs">
                                <ul>
                                    <li><a href="./">Inicio</a></li>
                                    <li>Biografías</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenido -->
    <div style="background-color:#f8f9fa; min-height:400px;">
        <div class="container">

            <div class="bio_section_title">
                <h2>Personas de nuestra Iglesia</h2>
                <p>Conoce la historia y el testimonio de quienes han servido y sirven en nuestra congregación.</p>
            </div>

            <?php
                require('config/global.php');
                $connection = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
                mysqli_set_charset($connection, DB_ENCODE);

                if (!$connection) {
                    echo "<div class='bio_empty'>No se pudo conectar a la base de datos.</div>";
                } else {
                    $result = mysqli_query($connection, "SELECT * FROM biografias ORDER BY idbiografia DESC");
                    if ($result && mysqli_num_rows($result) > 0) {
                        echo "<div class='bio_grid'>";
                        while ($row = mysqli_fetch_assoc($result)) {
                            $nombre    = htmlspecialchars($row['nombre']);
                            $cargo     = htmlspecialchars($row['cargo']);
                            $imagen    = htmlspecialchars($row['imagen']);
                            $biografia = htmlspecialchars($row['biografia']);
                            $resumen   = mb_substr(strip_tags($row['biografia']), 0, 160);
                            if (mb_strlen(strip_tags($row['biografia'])) > 160) $resumen .= '...';
                            $id = (int)$row['idbiografia'];

                            echo "<div class='bio_card'>";

                            echo "<div class='bio_card_img'>";
                            if ($imagen) {
                                echo "<img src='" . $imagen . "' alt='" . $nombre . "'>";
                            } else {
                                echo "<div class='bio_no_img'><i class='fa fa-user'></i></div>";
                            }
                            echo "</div>";

                            echo "<div class='bio_card_body'>";
                            echo "<div class='bio_card_nombre'>" . $nombre . "</div>";
                            if ($cargo) echo "<div class='bio_card_cargo'>" . $cargo . "</div>";
                            echo "<div class='bio_card_texto'>" . $resumen . "</div>";
                            echo "<a href='biografia_detalle.php?id=$id' class='bio_card_link'>Leer más</a>";
                            echo "</div></div>";
                        }
                        echo "</div>";
                    } else {
                        echo "<div class='bio_empty'><i class='fa fa-users' style='font-size:48px; display:block; margin-bottom:16px;'></i>Aún no hay biografías publicadas.</div>";
                    }
                    mysqli_close($connection);
                }
            ?>

        </div>
    </div>

    <?php require 'footer.php'; ?>

</div>

<script src="plugins/parallax-js-master/parallax.min.js"></script>
