<!DOCTYPE html>
<html lang="es">
<head>
<link rel="manifest" href="manifest.webmanifest">
<meta name="theme-color" content="#F2125E">
<link rel="apple-touch-icon" href="images/icons/apple-touch-icon.png">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="apple-mobile-web-app-title" content="PIBG">
<script>
(function(){
	var esApp = false;
	try { if (window.Capacitor && typeof window.Capacitor.isNativePlatform === 'function' && window.Capacitor.isNativePlatform()) esApp = true; } catch(e){}
	if (!esApp && window.matchMedia && window.matchMedia('(display-mode: standalone)').matches) esApp = true;
	if (!esApp && window.navigator && window.navigator.standalone === true) esApp = true;
	if (esApp) document.documentElement.className += ' pibg-app-instalada';
})();
</script>
<title>Coro J. S. Bach – Voces</title>
<link href="images/iconos/icono.png" rel="icon">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="Primera Iglesia Bautista de Guadalajara – Coro Bach">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<script src="js/jquery-3.2.1.min.js"></script>
<link rel="stylesheet" type="text/css" href="styles/voces.css">
</head>
<body>

<header class="v-header">
  <h1>Coro Johann Sebastian Bach</h1>
  <p>Primera Iglesia Bautista de Guadalajara</p>
</header>

<div class="v-container">

  <!-- Búsqueda -->
  <div class="v-search-row">
    <input type="text" id="text_nom" placeholder="Buscar obra…"
           onkeydown="if(event.key==='Enter') listar_obras();">
    <button class="v-btn-primary" onclick="listar_obras();">Buscar</button>
  </div>
  <button class="v-btn-ghost" onclick="listar_obras_1();">Ver todas las obras</button>

  <!-- Vista: listado de obras -->
  <div id="div_busquedas">
    <div class="v-section-label">Obras</div>
    <div id="v-spinner-obras" class="v-spinner">Cargando…</div>
    <div id="box_obras"></div>
  </div>

  <!-- Vista: voces de una obra -->
  <div id="div_voces" style="display:none;">
    <button class="v-back-btn" onclick="regresar_obras();">&#8592; Regresar</button>
    <div class="v-obra-heading" id="p_nom_obra"></div>
    <div id="v-spinner-voces" class="v-spinner">Cargando…</div>
    <div id="box_voces"></div>
  </div>

</div>

<script type="text/javascript" src="scripts/voces.js?v=<?php echo(rand()); ?>"></script>
</body>
</html>