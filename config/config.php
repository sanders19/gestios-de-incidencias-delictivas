<?php
require_once __DIR__ . '/constants.php';

// Configuración general
date_default_timezone_set(DEFAULT_TIMEZONE);

// Rutas base (sistema de archivos)
define('BASE_PATH', dirname(__DIR__));
define('PUBLIC_PATH', BASE_PATH . '/public');
define('UPLOAD_PATH', PUBLIC_PATH . '/uploads');

// URL base (ajustada para subcarpetas anidadas)
// URL base simple para desarrollo
if (!defined('BASE_URL')) {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    define('BASE_URL', $protocol . '://' . $host);
}

// Entorno
define('APP_ENV', 'development'); // o 'production'

// Mostrar errores (solo en desarrollo)
if (APP_ENV === 'development') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
}
?>