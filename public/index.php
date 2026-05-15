<?php
session_start();

require_once __DIR__ . '/../config/config.php';
require_once APP_PATH . '/Core/Helpers.php';

spl_autoload_register(function ($class) {
    $folders = [
        APP_PATH . '/Core/',
        APP_PATH . '/Controllers/',
        APP_PATH . '/Models/'
    ];

    foreach ($folders as $folder) {
        $file = $folder . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

$app = new App();
$app->run();
