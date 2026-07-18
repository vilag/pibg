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
<title>Instalar la App - Primera Iglesia Bautista de Guadalajara</title>
<link href="images/iconos/icono.png" rel="icon">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="Instala la app oficial de Primera Iglesia Bautista de Guadalajara en Android o iPhone">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="styles/bootstrap4/bootstrap.min.css">
<link href="plugins/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="styles/main_styles.css">
<link rel="stylesheet" type="text/css" href="styles/responsive.css">
<link rel="stylesheet" type="text/css" href="styles/personal.css">
<script src="js/jquery-3.2.1.min.js"></script>
<style>
	.app-download-card{background:#fff;border:1px solid #e2e8f4;border-radius:14px;padding:32px;margin-bottom:30px;box-shadow:0 4px 18px rgba(0,0,0,.05);}
	.app-download-btn{display:inline-flex;align-items:center;gap:10px;background:#F2125E;color:#fff !important;font-weight:700;font-size:16px;padding:14px 28px;border-radius:10px;text-decoration:none !important;transition:opacity .15s;}
	.app-download-btn:hover{opacity:.85;}
	.app-meta{font-size:13px;color:#888;margin-top:12px;}
	.app-checksum{background:#f8fafd;border:1px solid #e2e8f4;border-radius:8px;padding:10px 14px;font-family:monospace;font-size:12px;word-break:break-all;margin-top:8px;}
	.app-steps{counter-reset:step;list-style:none;padding-left:0;}
	.app-steps li{counter-increment:step;position:relative;padding-left:42px;margin-bottom:18px;}
	.app-steps li::before{content:counter(step);position:absolute;left:0;top:0;width:28px;height:28px;background:#1D4268;color:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:13px;}
	.app-security-box{background:#fff7f0;border:1px solid #ffd9b3;border-radius:12px;padding:20px 24px;}
	.app-security-box h4{color:#b4530a;}
	.app-platform-picker{background:#fff;border:1px solid #e2e8f4;border-radius:14px;padding:40px 32px;margin-bottom:30px;}
	.app-platform-btns{display:flex;gap:18px;justify-content:center;flex-wrap:wrap;margin-top:22px;}
	.app-platform-btn{display:flex;flex-direction:column;align-items:center;gap:10px;background:#f8fafd;border:2px solid #e2e8f4;border-radius:12px;padding:22px 34px;min-width:160px;cursor:pointer;font-weight:700;font-size:15px;color:#1D4268;transition:border-color .15s,background .15s;}
	.app-platform-btn i{font-size:32px;}
	.app-platform-btn:hover{border-color:#F2125E;background:#fff;}
	.app-volver{display:inline-flex;align-items:center;gap:6px;font-size:13px;color:#888;text-decoration:none !important;cursor:pointer;margin-bottom:22px;background:none;border:none;padding:0;}
	.app-volver:hover{color:#F2125E;}
</style>
</head>
<body>

<div class="super_container">

	<?php
		require('header.php');
	?>

	<div class="home" style="height: 220px;">
		<div class="home_container">
			<div class="container" style="margin-top: 50px;">
				<div class="row">
					<div class="col">
						<div class="home_content text-center">
							<div class="home_title" style="font-size: 35px;">Instalar la App</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="container" style="padding: 50px 15px; line-height: 1.8; max-width: 820px;">

		<div class="app-platform-picker text-center" id="app_platform_picker">
			<h4 style="margin-bottom:0;">¿En qué celular vas a instalar la app?</h4>
			<div class="app-platform-btns">
				<button type="button" class="app-platform-btn" onclick="mostrarPlataformaApp('android')">
					<i class="fa fa-android" aria-hidden="true"></i> Android
				</button>
				<button type="button" class="app-platform-btn" onclick="mostrarPlataformaApp('ios')">
					<i class="fa fa-apple" aria-hidden="true"></i> iPhone / iPad
				</button>
			</div>
		</div>

		<div id="app_seccion_android" style="display:none;">
			<button type="button" class="app-volver" onclick="mostrarPlataformaApp(null)"><i class="fa fa-chevron-left" aria-hidden="true"></i> Elegir otro dispositivo</button>

			<div class="app-download-card text-center">
				<p>Descarga la app oficial de la Primera Iglesia Bautista de Guadalajara para Android. Muestra el mismo contenido de este sitio web, directo en tu celular.</p>
				<a href="descargas/pibg-v1.0.apk" class="app-download-btn" download>
					<i class="fa fa-android" aria-hidden="true"></i> Descargar APK (v1.0)
				</a>
				<div class="app-meta">
					Versión 1.0 &middot; 3.1 MB &middot; Requiere Android 7.0 o superior<br>
					Publicado: 18 de julio de 2026
				</div>
				<div class="app-meta" style="margin-top:18px;">
					Huella digital SHA-256 del archivo (para verificar que no fue alterado):
					<div class="app-checksum">11f2b5000d08b0c6eb4bc2e26e90a12194e50794ffd8f350b4cfc07fa2b46977</div>
				</div>
			</div>

			<h4>Cómo instalarla</h4>
			<ol class="app-steps">
				<li>Abre este enlace <strong>desde tu celular Android</strong> y toca "Descargar APK".</li>
				<li>Cuando termine de descargar, ábrelo desde el panel de notificaciones o desde tu carpeta de "Descargas".</li>
				<li>Android pedirá permiso para instalar apps de este origen (por ejemplo, Chrome). Actívalo <strong>solo para esta descarga</strong> — es un permiso normal para instalar apps fuera de Google Play.</li>
				<li>Google Play Protect puede mostrar una revisión automática antes de instalar; espera a que termine y confirma "Instalar".</li>
				<li>Abre la app "PIBG" desde tu pantalla de inicio. Verás el mismo contenido que en primeraiglesiabautistagdl.org.</li>
			</ol>

			<div class="app-security-box" style="margin-top:30px;">
				<h4><i class="fa fa-shield" aria-hidden="true"></i> Antes de instalar, verifica esto</h4>
				<ul>
					<li><strong>Descarga solo desde este dominio</strong>: <code>primeraiglesiabautistagdl.org</code>. Desconfía de este mismo archivo si lo recibes por WhatsApp, correo o cualquier otro sitio.</li>
					<li>El archivo está firmado digitalmente con el certificado oficial de la Iglesia; Android rechazará instalarlo si alguien lo modifica.</li>
					<li>Si quieres verificar la huella digital SHA-256 tú mismo, puedes compararla con la mostrada arriba usando cualquier app "de suma de verificación" (checksum) para Android.</li>
					<li>Después de instalar, puedes volver a desactivar el permiso de "orígenes desconocidos" en Ajustes &gt; Apps &gt; Chrome (o el navegador que hayas usado).</li>
					<li>La app solo puede mostrar contenido de este sitio web (conexión cifrada HTTPS); no pide permisos de cámara, contactos, ubicación ni almacenamiento.</li>
				</ul>
				<p style="margin-bottom:0;">Consulta también nuestro <a href="privacidad.php">Aviso de Privacidad</a>.</p>
			</div>

			<p class="app-meta" style="margin-top:24px;">Próximamente esta app también estará disponible en Google Play. Cuando esté publicada, actualizaremos esta página con el enlace directo a la tienda.</p>
		</div>

		<div id="app_seccion_ios" style="display:none;">
			<button type="button" class="app-volver" onclick="mostrarPlataformaApp(null)"><i class="fa fa-chevron-left" aria-hidden="true"></i> Elegir otro dispositivo</button>

			<div class="app-download-card text-center">
				<p>En iPhone / iPad no se descarga ningún archivo: agregas el sitio a tu pantalla de inicio desde Safari y queda como una app, con ícono propio y pantalla completa.</p>
				<div class="app-meta">Funciona en iOS 12 o superior &middot; Requiere el navegador Safari</div>
			</div>

			<h4>Cómo instalarla</h4>
			<ol class="app-steps">
				<li>Abre <strong>primeraiglesiabautistagdl.org</strong> en <strong>Safari</strong> (debe ser Safari; en Chrome para iPhone no aparece esta opción).</li>
				<li>Toca el ícono de compartir <i class="fa fa-share-square-o" aria-hidden="true"></i> en la barra inferior (o superior, según tu modelo).</li>
				<li>Desliza hacia abajo en el menú y toca <strong>"Agregar a inicio"</strong> (Add to Home Screen).</li>
				<li>Confirma el nombre "PIBG" y toca <strong>"Agregar"</strong>, arriba a la derecha.</li>
				<li>Abre el ícono "PIBG" que aparece en tu pantalla de inicio. Se abrirá en pantalla completa, como una app.</li>
			</ol>

			<div class="app-security-box" style="margin-top:30px;">
				<h4><i class="fa fa-shield" aria-hidden="true"></i> Sobre la seguridad de esta opción</h4>
				<ul>
					<li>No se descarga ni se instala ningún archivo ejecutable; solo es un acceso directo al sitio web oficial.</li>
					<li>Toda la conexión va cifrada (HTTPS), igual que cuando visitas el sitio desde el navegador.</li>
					<li>No se piden permisos adicionales de cámara, contactos, ubicación ni almacenamiento.</li>
					<li><strong>Verifica siempre que el enlace diga</strong> <code>primeraiglesiabautistagdl.org</code> antes de agregarlo a tu pantalla de inicio.</li>
				</ul>
				<p style="margin-bottom:0;">Consulta también nuestro <a href="privacidad.php">Aviso de Privacidad</a>.</p>
			</div>
		</div>
	</div>

	<script>
		function mostrarPlataformaApp(plataforma) {
			document.getElementById('app_platform_picker').style.display = plataforma ? 'none' : 'block';
			document.getElementById('app_seccion_android').style.display = (plataforma === 'android') ? 'block' : 'none';
			document.getElementById('app_seccion_ios').style.display = (plataforma === 'ios') ? 'block' : 'none';
			if (plataforma) {
				document.getElementById('app_seccion_' + plataforma).scrollIntoView({ behavior: 'smooth', block: 'start' });
			}
		}
	</script>

<?php
	require('footer.php');
?>

</div>
<script src="styles/bootstrap4/popper.js"></script>
<script src="styles/bootstrap4/bootstrap.min.js"></script>
</body>
</html>
