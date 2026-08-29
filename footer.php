<?php if (empty($ocultar_aviso_notif)): ?>
<!-- ── Aviso: activar notificaciones (suscripción directa, cuando el navegador la soporta sin instalar la app) ── -->
<style>
.app-notif-banner { display: none; background: linear-gradient(100deg, #1D4268 0%, #0b1c30 100%); padding: 34px 0; }
.app-notif-banner.app-notif-banner--visible { display: block !important; }
.app-notif-banner .app-promo-inner { display: flex; align-items: center; gap: 26px; flex-wrap: wrap; }
.app-notif-banner .app-promo-text { flex: 1; min-width: 240px; }
.app-notif-banner .app-promo-title { color: #fff; font-size: 22px; font-weight: 700; margin-bottom: 4px; }
.app-notif-banner .app-promo-desc { color: rgba(255,255,255,.88); font-size: 14px; line-height: 1.6; max-width: 560px; }
.app-notif-icon-wrap { width: 68px; height: 68px; border-radius: 18px; background: rgba(255, 255, 255, .12); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.app-notif-icon-wrap i { font-size: 30px; color: #fff; }
.app-notif-btn { display: inline-flex; align-items: center; gap: 10px; background: #fff; color: #1D4268 !important; font-weight: 700; font-size: 15px; padding: 14px 28px; border: none; border-radius: 10px; cursor: pointer; white-space: nowrap; box-shadow: 0 6px 16px rgba(0, 0, 0, .18); animation: app-notif-pulse 2.2s infinite; transition: transform .15s; }
.app-notif-btn:hover { transform: translateY(-2px); }
.app-notif-btn:disabled { opacity: .6; cursor: default; animation: none; }
@keyframes app-notif-pulse {
    0%   { box-shadow: 0 0 0 0 rgba(255, 255, 255, .55); }
    70%  { box-shadow: 0 0 0 14px rgba(255, 255, 255, 0); }
    100% { box-shadow: 0 0 0 0 rgba(255, 255, 255, 0); }
}
.app-notif-resultado { text-align: center; color: #fff; font-size: 13px; margin-top: 12px; }
.app-notif-browser-banner { padding: 56px 20px; text-align: center; background: linear-gradient(180deg, #1c2e42 0%, #24344B 100%); }
html.pibg-app-instalada .app-notif-browser-banner { display: none !important; }
.app-notif-browser-inner { max-width: 480px; margin: 0 auto; display: flex; flex-direction: column; align-items: center; }
.app-notif-browser-btn { display: inline-flex; align-items: center; gap: 10px; background: #F2125E; color: #fff !important; font-weight: 700; font-size: 15px; padding: 15px 32px; border: none; border-radius: 10px; cursor: pointer; box-shadow: 0 8px 20px rgba(242, 18, 94, .3); transition: transform .15s, opacity .15s; }
.app-notif-browser-btn:hover { transform: translateY(-2px); opacity: .92; }
.app-notif-browser-text { color: #cfd8e3; font-size: 15px; line-height: 1.6; margin: 0 0 22px; }
@media only screen and (max-width: 767px) {
    .app-notif-banner .app-promo-inner { flex-direction: column; text-align: center; }
    .app-notif-btn { width: 100%; justify-content: center; }
}
</style>

<section class="app-notif-banner" id="app_notif_banner">
	<div class="container">
		<div class="app-promo-inner">
			<div class="app-notif-icon-wrap">
				<i class="fa fa-bell" aria-hidden="true"></i>
			</div>
			<div class="app-promo-text">
				<div class="app-promo-title">No te pierdas ningún anuncio</div>
				<div class="app-promo-desc">Activa las notificaciones y entérate al instante de nuevas
					actividades, predicaciones y avisos importantes.</div>
			</div>
			<button type="button" class="app-notif-btn" id="app_notif_btn" onclick="app_notif_activar_click()">
				<i class="fa fa-bell" aria-hidden="true"></i> Activar notificaciones
			</button>
		</div>
		<div id="app_notif_resultado" class="app-notif-resultado"></div>
	</div>
</section>

<!-- ── Aviso: invitar a instalar la app (solo cuando el navegador no puede recibir push sin instalarla, ej. Safari iOS) ── -->
<section class="app-notif-browser-banner" id="app_notif_browser_banner">
	<div class="app-notif-browser-inner">
		<p class="app-notif-browser-text">No te pierdas ningún anuncio, actividad o predicación nueva.</p>
		<button style="margin-top: 30px;" type="button" class="app-notif-browser-btn" onclick="app_notif_browser_click()">
			<i class="fa fa-bell" aria-hidden="true"></i> Activar notificaciones
		</button>
	</div>
</section>
<?php endif; ?>

<!-- Footer -->
<footer class="footer pibg-footer">
	<div class="container">
		<div class="row pibg-footer__top">

			<!-- Col 1: Logo + verso + redes -->
			<div class="col-lg-4 col-md-6 pibg-footer__col">
				<a href="./" class="pibg-footer__logo-link">
					<img src="images/logos/logo_sf.png" alt="Primera Iglesia Bautista de Guadalajara" class="pibg-footer__logo">
				</a>
				<p class="pibg-footer__verse">"Y esta es la vida eterna: que te conozcan a ti, el único Dios verdadero, y a Jesucristo, a quien has enviado."</p>
				<p class="pibg-footer__verse-ref">— Juan 17:3</p>
				<div class="pibg-footer__social">
					<a href="https://www.youtube.com/@pibguadalajara5203" target="_blank" class="pibg-footer__soc-link" aria-label="YouTube"><i class="fa fa-youtube" aria-hidden="true"></i></a>
					<a href="https://www.facebook.com/gdlpib" target="_blank" class="pibg-footer__soc-link" aria-label="Facebook"><i class="fa fa-facebook" aria-hidden="true"></i></a>
					<a href="https://www.instagram.com/pibgdl/" target="_blank" class="pibg-footer__soc-link" aria-label="Instagram"><i class="fa fa-instagram" aria-hidden="true"></i></a>
				</div>
			</div>

			<!-- Col 2: Menú -->
			<div class="col-lg-2 col-md-6 pibg-footer__col">
				<h4 class="pibg-footer__heading">Menú</h4>
				<ul class="pibg-footer__list">
					<li><a href="./">Inicio</a></li>
					<li><a href="predicaciones.php">Predicaciones</a></li>
					<li><a href="biografias.php">Biografías</a></li>
					<li><a href="lumbrera.php">Jóvenes</a></li>
					<li><a href="infantil.php">Niños</a></li>
					<li><a href="bach.php">Coro J. S. Bach</a></li>
					<li class="js-app-install-item"><a href="descarga-app.php"><i class="fa fa-mobile" aria-hidden="true"></i> Instalar la App</a></li>
				</ul>
			</div>

			<!-- Col 3: Contacto -->
			<div class="col-lg-3 col-md-6 pibg-footer__col">
				<h4 class="pibg-footer__heading">Contacto</h4>
				<ul class="pibg-footer__list pibg-footer__list--contact">
					<li><i class="fa fa-phone pibg-footer__list-icon" aria-hidden="true"></i>(33) 36144120</li>
					<li><i class="fa fa-envelope pibg-footer__list-icon" aria-hidden="true"></i>pibgdlar@gmail.com</li>
				</ul>
			</div>

			<!-- Col 4: Dirección -->
			<div class="col-lg-3 col-md-6 pibg-footer__col">
				<h4 class="pibg-footer__heading">Dirección</h4>
				<p class="pibg-footer__address">C. Independencia 657, Zona Centro,<br>44100 Guadalajara, Jal.</p>
				<a href="https://maps.app.goo.gl/azQQqR955ExPy28F8" target="_blank" class="pibg-footer__map-btn">
					<i class="fa fa-map-marker" aria-hidden="true"></i> Ver en mapa
				</a>
			</div>

		</div>

		<div class="pibg-footer__bottom">
			<span>Copyright &copy;<script>document.write(new Date().getFullYear());</script> Primera Iglesia Bautista de Guadalajara. Todos los derechos reservados.</span>
			<span class="pibg-footer__bottom-sep">&middot;</span>
			<a href="privacidad.php" class="pibg-footer__privacy-link">Aviso de Privacidad</a>
		</div>
	</div>
</footer>
</div>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.26.0/moment.min.js"></script>
<script src="js/jquery-3.2.1.min.js"></script>
<script src="styles/bootstrap4/popper.js"></script>
<script src="styles/bootstrap4/bootstrap.min.js"></script>
<script src="plugins/greensock/TweenMax.min.js"></script>
<script src="plugins/greensock/TimelineMax.min.js"></script>
<script src="plugins/scrollmagic/ScrollMagic.min.js"></script>
<script src="plugins/greensock/animation.gsap.min.js"></script>
<script src="plugins/greensock/ScrollToPlugin.min.js"></script>
<script src="plugins/OwlCarousel2-2.2.1/owl.carousel.js"></script>
<script src="plugins/easing/easing.js"></script>
<script src="plugins/video-js/video.min.js"></script>
<script src="plugins/video-js/Youtube.min.js"></script>
<script src="plugins/parallax-js-master/parallax.min.js"></script>
<script src="js/custom.js"></script>

<?php if (empty($ocultar_aviso_notif)): ?>
<script src="js/bootbox.js"></script>
<script src="js/push_cliente.js"></script>
<script>
(function () {
	var directBanner  = document.getElementById('app_notif_banner');
	var browserBanner = document.getElementById('app_notif_browser_banner');
	if (typeof push_soportado !== 'function' || !push_soportado()) { return; }

	// Este navegador puede recibir push sin necesidad de instalar la app
	// (ej. Chrome/Firefox de escritorio o Android): no tiene sentido pedirle
	// que la instale, se le ofrece activar las notificaciones directamente.
	if (browserBanner) { browserBanner.style.display = 'none'; }

	// La app nativa (Capacitor) aún no puede saber de forma confiable si ya
	// está suscrita (push_yaActivo() siempre devuelve "no activo" en ese caso),
	// así que por ahora no se le muestra este aviso para evitar que aparezca
	// en cada pantalla aunque el usuario ya haya activado las notificaciones.
	if (typeof push_esNativo === 'function' && push_esNativo()) { return; }

	if (!directBanner) { return; }
	push_yaActivo(function (activo) {
		if (!activo) { directBanner.classList.add('app-notif-banner--visible'); }
	});
})();

function app_notif_activar_click() {
	var btn = document.getElementById('app_notif_btn');
	var resultado = document.getElementById('app_notif_resultado');
	btn.disabled = true;
	resultado.textContent = 'Activando…';
	push_activar(function () {
		resultado.textContent = '¡Listo! Ya recibirás notificaciones. 🔔';
		setTimeout(function () {
			document.getElementById('app_notif_banner').style.display = 'none';
		}, 2200);
	}, function (msg) {
		btn.disabled = false;
		resultado.textContent = msg;
	});
}

function app_notif_browser_click() {
	bootbox.alert({
		title: '¿Quieres recibir notificaciones?',
		message: 'Para poder enviarte notificaciones, primero necesitas instalar la app (o agregarla a tu pantalla de inicio si usas iPhone). Es rápido y gratis.<br><br><a href="descarga-app.php" class="app-notif-browser-btn" style="text-decoration:none;display:inline-block;margin-top:6px;">Ver cómo instalarla</a>',
		className: 'app-notif-modal'
	});
}
</script>
<?php endif; ?>

</body>
</html>