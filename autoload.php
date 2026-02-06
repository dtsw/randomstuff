<?php

// Define the base directory
define('BASE_PATH', dirname(__DIR__));

// Autoloader function
spl_autoload_register(function ($class) {
    // Convert namespace separators to directory separators
    $classPath = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    
    // Define possible locations for classes
    $possiblePaths = [
        BASE_PATH . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . $classPath . '.php',
        BASE_PATH . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . $classPath . '.php',
        BASE_PATH . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . $classPath . '.php'
    ];
    
    foreach ($possiblePaths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});