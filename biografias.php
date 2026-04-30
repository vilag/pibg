<!DOCTYPE html>
<html lang="es">
<head>
<title>Biografías - Primera Iglesia Bautista de Guadalajara</title>
<link href="images/iconos/icono.png" rel="icon">
<link rel="stylesheet" type="text/css" href="styles/bootstrap4/bootstrap.min.css">
<link href="plugins/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="styles/about.css">
<link rel="stylesheet" type="text/css" href="styles/about_responsive.css">
<style>
    .bio_grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 30px;
        padding: 40px 0;
    }
    .bio_card {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        overflow: hidden;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .bio_card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 30px rgba(0,0,0,0.14);
    }
    .bio_card_img {
        width: 100%;
        height: 220px;
        object-fit: cover;
        background-color: #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .bio_card_img img {
        width: 100%;
        height: 220px;
        object-fit: cover;
    }
    .bio_card_img .bio_no_img {
        width: 100%;
        height: 220px;
        background: linear-gradient(135deg, #042C49, #24344B);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .bio_card_img .bio_no_img i {
        font-size: 80px;
        color: rgba(255,255,255,0.4);
    }
    .bio_card_body {
        padding: 20px;
    }
    .bio_card_nombre {
        font-size: 18px;
        font-weight: 700;
        color: #24344B;
        margin-bottom: 4px;
    }
    .bio_card_cargo {
        font-size: 13px;
        color: #F85E0C;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 12px;
    }
    .bio_card_texto {
        font-size: 14px;
        color: #666;
        line-height: 1.6;
        margin-bottom: 16px;
    }
    .bio_card_link {
        display: inline-block;
        padding: 7px 18px;
        background-color: #042C49;
        color: #fff;
        border-radius: 5px;
        font-size: 13px;
        text-decoration: none;
        transition: background-color 0.2s;
    }
    .bio_card_link:hover {
        background-color: #F85E0C;
        color: #fff;
        text-decoration: none;
    }
    .bio_section_title {
        text-align: center;
        padding: 50px 0 10px;
    }
    .bio_section_title h2 {
        font-size: 36px;
        color: #24344B;
        font-weight: 700;
    }
    .bio_section_title p {
        color: #888;
        font-size: 16px;
        max-width: 560px;
        margin: 10px auto 0;
    }
    .bio_empty {
        text-align: center;
        padding: 60px 20px;
        color: #aaa;
        font-size: 18px;
    }

    /* Modal de detalle */
    .modal-bio-img {
        width: 130px;
        height: 130px;
        object-fit: cover;
        border-radius: 50%;
        border: 4px solid #042C49;
        margin-bottom: 16px;
    }
    .modal-bio-no-img {
        width: 130px;
        height: 130px;
        border-radius: 50%;
        background: linear-gradient(135deg, #042C49, #24344B);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
    }
    .modal-bio-no-img i {
        font-size: 60px;
        color: rgba(255,255,255,0.5);
    }
    .modal-bio-cargo {
        color: #F85E0C;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 13px;
        letter-spacing: 0.5px;
        margin-bottom: 16px;
    }
    .modal-bio-texto {
        font-size: 15px;
        color: #555;
        line-height: 1.8;
        text-align: left;
        white-space: pre-line;
    }
</style>
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
                            <div class="home_title" style="text-shadow:5px 5px 10px rgba(0,0,0,0.5);">Biografías</div>
                            <div class="breadcrumbs">
                                <ul>
                                    <li><a href="./" style="text-shadow:5px 5px 10px rgba(0,0,0,0.5);">Inicio</a></li>
                                    <li style="text-shadow:5px 5px 10px rgba(0,0,0,0.5);">Biografías</li>
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
<script src="js/about.js"></script>
