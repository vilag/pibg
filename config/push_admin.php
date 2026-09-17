<?php
// Puente hacia panelc/config/push_helpers.php para avisarle solo al admin
// (push_notificar_admin(), filtra por es_admin=1) desde código de la raíz del
// sitio, como ajax/index.php al guardar una petición de oración. Se resuelve
// todo por ruta absoluta (__DIR__) para no depender del directorio del script
// de entrada — la raíz y panelc/ son árboles independientes en este proyecto.
//
// Si panelc/config/secrets.php no existe en este entorno (no está en git, se
// crea manualmente en cada servidor), esta función simplemente no hace nada:
// nunca debe interrumpir el flujo que la llama.
function push_avisar_admin($titulo, $mensaje, $url = '')
{
    $secrets = __DIR__ . '/../panelc/config/secrets.php';
    $helpers = __DIR__ . '/../panelc/config/push_helpers.php';

    if (!file_exists($secrets) || !file_exists($helpers)) {
        return;
    }

    // @: la raíz y panelc/ ya definieron por su cuenta las mismas constantes
    // de conexión (DB_HOST, etc.) con los mismos valores; sin esto, PHP emite
    // un warning cosmético de "constante ya definida" al recargarlas aquí.
    @require_once $secrets;
    @require_once $helpers;
    push_notificar_admin($titulo, $mensaje, $url);
}
