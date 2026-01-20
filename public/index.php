<?php

// Require the class loader to enable automatic loading of classes
require __DIR__ . '/../Framework/ClassLoader.php';

// Simple .env loader
if (file_exists(__DIR__ . '/../.env')) {
    $lines = file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        list($name, $value) = explode('=', $line, 2);
        putenv(trim($name) . '=' . trim($value));
    }
}

use Framework\Core\App;

try {
    // Create an instance of the App class
    $app = new App();

    // Run the application
    $app->run();
} catch (Exception $e) {
    // Handle any exceptions that occur during the application run
    die('An error occurred: ' . $e->getMessage());
}
