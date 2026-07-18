<!DOCTYPE html>
<html lang="es">
<head>
<title>Descargar App Android - Primera Iglesia Bautista de Guadalajara</title>
<link href="images/iconos/icono.png" rel="icon">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="Descarga la app oficial de Android de Primera Iglesia Bautista de Guadalajara">
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
							<div class="home_title" style="font-size: 35px;">App para Android</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="container" style="padding: 50px 15px; line-height: 1.8; max-width: 820px;">

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
			<li>Abre este enlace <strong>desde tu celular Android</strong> (no funciona en iPhone) y toca "Descargar APK".</li>
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

<?php
	require('footer.php');
?>

</div>
<script src="styles/bootstrap4/popper.js"></script>
<script src="styles/bootstrap4/bootstrap.min.js"></script>
</body>
</html>
