<?php
/**
 * Configuración Instagram Graph API — @pibg.joven
 *
 * Pasos para obtener el token:
 * 1. Asegúrate de que @pibg.joven sea cuenta Profesional (Negocio o Creador)
 *    y esté conectada a una Página de Facebook.
 * 2. Ve a https://developers.facebook.com → Crear App → Tipo "Business".
 * 3. Agrega el producto "Instagram Graph API".
 * 4. En Graph API Explorer selecciona tu App y genera un User Token con permisos:
 *       instagram_basic   pages_show_list   instagram_manage_insights
 * 5. Convierte a Long-Lived Token (válido 60 días, renovable):
 *       GET https://graph.facebook.com/oauth/access_token
 *           ?grant_type=fb_exchange_token
 *           &client_id={app_id}
 *           &client_secret={app_secret}
 *           &fb_exchange_token={short_token}
 * 6. Obtén el Instagram User ID numérico:
 *       GET https://graph.instagram.com/me?access_token={long_token}
 *       → copia el campo "id"
 * 7. Pega los valores abajo y guarda.
 */

define('IG_TOKEN',   '');   // Long-Lived User Access Token de @pibg.joven
define('IG_USER_ID', '');   // ID numérico, ej: '17841400000000000'
