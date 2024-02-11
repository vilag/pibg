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




            <b style="font-size: 30px;">En la tierra paz</b>
            <div style="width: 100%;  height: auto; margin-top: 20px;">
                <div style="margin-top: 10px; width: 100%; background-color: #1A456D; border-radius: 10px; box-shadow: 5px 5px 10px rgba(0,0,0,0.2);">
                    <div style="width: 100%; padding: 10px;">
                        <b style="font-size: 18px; margin-left: 7px; color: #E0E4E7;">SOPRANO</b>   
                    </div>
                    <div style="width: 100%; padding: 10px;">
                        <button onclick="PlaySound();" style="background-color: rgba(0,0,0,0); border: none; margin: 7px;"><img src="images/iconos/play.png" style="width: 50px;" alt=""></button>
                        <button onclick="PlaySound2();" style="background-color: rgba(0,0,0,0); border: none; margin: 7px;"><img src="images/iconos/pausa.png" style="width: 50px;" alt=""></button>
                        <button onclick="PlaySound3();" style="background-color: rgba(0,0,0,0); border: none; margin: 7px;"><img src="images/iconos/reiniciar.png" style="width: 50px;" alt=""></button>
                        <audio controls id="audio_prueba" style="opacity: 0; position: absolute;">
                            <source src="audio/en_la_tierra_paz/soprano.mp3" type="audio/mp3">
                            Your browser does not support the audio element.
                        </audio>
                    </div>
                </div>
                
            </div>
        </div>
    </div>

</body>
<script type="text/javascript" src="scripts/index.js?v=<?php echo(rand()); ?>"></script>
</html>