<!DOCTYPE html>
<html lang="en">
<head>
<title>Coro J. S. Bach</title>
<link href="images/iconos/icono.png" rel="icon">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="Primera Iglesia Bautista de Guadalajara">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="styles/bootstrap4/bootstrap.min.css">
<link href="plugins/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="plugins/OwlCarousel2-2.2.1/owl.carousel.css">
<link rel="stylesheet" type="text/css" href="plugins/OwlCarousel2-2.2.1/owl.theme.default.css">
<link rel="stylesheet" type="text/css" href="plugins/OwlCarousel2-2.2.1/animate.css">
<link href="plugins/video-js/video-js.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="styles/main_styles.css">
<link rel="stylesheet" type="text/css" href="styles/responsive.css">
<link rel="stylesheet" type="text/css" href="styles/courses.css">
<link rel="stylesheet" type="text/css" href="styles/courses_responsive.css">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.26.0/moment.min.js"></script>
<script src="js/jquery-3.2.1.min.js"></script>
</head>
<body>
    <div style="width: 100%;">
        <div style="width: 100%; padding-left: 10px; padding-right: 10px; margin-top: 50px;">
            <div style="width: 100%; text-align: center;">
                <b style="font-size: 20px;">Coro Johann Sebastian Bach</b><br>
                <label for="">Voces</label>
            </div>
            <div style="width: 100%;">
                <input id="text_nom" type="text" style="width: 100%; height: 50px; padding-left: 10px;" placeholder="Buscar Obra">
            </div>
            <div style="width: 100%; margin-top: 10px;">
                <button style="width: 100%; height: 40px;" onclick="listar_obras();">Buscar</button>
            </div>
            <div id="div_busquedas" style="padding-top: 20px;">
                <div style="text-align: center;">
                    <label style="font-size: 18px;">Obras</label>
                </div>
                <div style="width: 100%; margin-top: 0px; height: auto; max-height: 200px; overflow: scroll;" id="box_obras">             
                </div>
            </div>
            <div id="div_voces" style="display: none; text-align: center; margin-top: 20px;">
                <p style="padding: 10px;"><b id="p_nom_obra" style="font-size: 20px;"></b></p>
                <div style="width: 100%;  height: auto; margin-top: 10px;" id="box_voces"> 
                </div>
            </div>
        </div>
    </div>

</body>
<script type="text/javascript" src="scripts/voces.js?v=<?php echo(rand()); ?>"></script>
</html>