<?php
// Configuración del sistema

// Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'cine_rewind');

// Configuración general
define('BASE_URL', '/cine-rewind/public/');
define('SITE_NAME', 'RETRO REWIND');
define('UPLOAD_DIR', __DIR__ . '/../public/imagenes/');

// Configuración de sesión
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); // Cambiar a 1 en producción con HTTPS

// Zona horaria
date_default_timezone_set('America/Argentina/Buenos_Aires');

// Reporte de errores (desactivar en producción)
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>