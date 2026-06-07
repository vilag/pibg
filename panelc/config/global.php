<?php
// Detectar entorno: local (XAMPP/localhost) o producción
$_is_local = (
    (isset($_SERVER['HTTP_HOST'])   && in_array($_SERVER['HTTP_HOST'],   ['localhost','127.0.0.1','::1'])) ||
    (isset($_SERVER['SERVER_NAME']) && in_array($_SERVER['SERVER_NAME'], ['localhost','127.0.0.1','::1'])) ||
    php_sapi_name() === 'cli'
);

if ($_is_local) {
    // ── Entorno LOCAL ─────────────────────────────────────────
    define("DB_HOST",     "srv467.hstgr.io");
    define("DB_NAME",     "u690371019_pibg");
    define("DB_USERNAME", "u690371019_pibg");
    define("DB_PASSWORD", "1t;Ut]qW&");
    define("DB_AVAILABLE", true); 
} else {
    // ── Producción (Hostinger) ────────────────────────────────
    define("DB_HOST",     "localhost");
    define("DB_NAME",     "u690371019_pibg");
    define("DB_USERNAME", "u690371019_pibg");
    define("DB_PASSWORD", "1t;Ut]qW&");
    define("DB_AVAILABLE", true);
}

define("DB_ENCODE",  "utf8");
define("PRO_NOMBRE", "Pibg");

// Groq API key — cargada desde secrets.php (no versionado)
$_secrets_file = __DIR__ . '/secrets.php';
if (file_exists($_secrets_file)) {
    require_once $_secrets_file;
}
?>