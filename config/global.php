<?php
// Detectar entorno: local (XAMPP/localhost) o producción
$_is_local = (
    (isset($_SERVER['HTTP_HOST'])   && in_array($_SERVER['HTTP_HOST'],   ['localhost','127.0.0.1','::1'])) ||
    (isset($_SERVER['SERVER_NAME']) && in_array($_SERVER['SERVER_NAME'], ['localhost','127.0.0.1','::1'])) ||
    php_sapi_name() === 'cli'
);

if ($_is_local) {
    // ── Entorno LOCAL ─────────────────────────────────────────
    // Sin base de datos local: el sitio carga sin consultas DB
    define("DB_HOST",     "srv467.hstgr.io");
    define("DB_NAME",     "u690371019_pibg");
    define("DB_USERNAME", "u690371019_pibg");
    define("DB_PASSWORD", "1t;Ut]qW&");
    define("DB_AVAILABLE", true);   // <- señal para omitir conexiones
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
?>