<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Primera Iglesia Bautista de Guadalajara</title>
    <link rel="stylesheet" href="./styles/anuncio.css">
    
</head>
<body >
    <div style="display: flex; width: 100%; justify-content: center;">
        <!-- <div class="estilo_div"> -->
            <img id="img_anuncio1" src="images/anuncios/1.jpg" alt="" class="estilo_img1">
            <img id="img_anuncio2" src="images/anuncios/2.jpg" alt="" class="estilo_img2">
            <img id="img_anuncio3" src="images/anuncios/3.jpg" alt="" class="estilo_img3">
            <img id="img_anuncio4" src="images/anuncios/4.jpg" alt="" class="estilo_img4">
            <button class="estilo_btn_next slide-in-blurred-left" onclick="siguienteimg();"><img src="images/iconos/right_b.png" alt="" style="width: 30px; opacity: 0.5;"></button>
            <button class="estilo_btn_next2 slide-in-blurred-left" onclick="siguienteimg();"><img src="images/iconos/right.png" alt="" style="width: 30px; opacity: 0.5;"></button>
            <a href="./" style="position: absolute; bottom: 10px; z-index: 15; padding:5px; background-color: #054d7e; color: #fff; border-radius: 5px;">Ver pagina principal</a>
            
        <!-- </div> -->
    </div>
    
    
</body>
<script type="text/javascript" src="scripts/anuncio.js?v=<?php echo(rand()); ?>"></script>
<script src="js/jquery-3.2.1.min.js"></script>
</html>