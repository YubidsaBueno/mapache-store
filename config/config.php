<?php
// Configuración general del proyecto Mapache Store
// Edita estos datos si tu MySQL usa otro usuario o contraseña.

define('APP_NAME', 'Mapache Store');
define('APP_SLOGAN', 'Tecnología en tus manos');

define('DB_HOST', 'localhost');
define('DB_NAME', 'mapache_store');
define('DB_USER', 'root');
define('DB_PASS', '');

define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
define('PUBLIC_PATH', ROOT_PATH . '/public');

// BASE_URL se arma automáticamente según la carpeta donde pongas el proyecto.
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
$scriptDir = rtrim($scriptDir, '/');
define('BASE_URL', ($scriptDir === '' ? '' : $scriptDir) . '/');

define('UPLOAD_PRODUCTOS_PATH', PUBLIC_PATH . '/assets/uploads/productos/');
define('UPLOAD_VIDEOS_PATH', PUBLIC_PATH . '/assets/uploads/videos/');

define('UPLOAD_PRODUCTOS_URL', 'assets/uploads/productos/');
define('UPLOAD_VIDEOS_URL', 'assets/uploads/videos/');
