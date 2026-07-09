<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Temporary server-vars probe for debugging the hosting rewrite chain.
if (isset($_GET['__probe'])) {
    header('Content-Type: application/json');
    echo json_encode([
        'method'      => $_SERVER['REQUEST_METHOD'] ?? null,
        'request_uri' => $_SERVER['REQUEST_URI'] ?? null,
        'script_name' => $_SERVER['SCRIPT_NAME'] ?? null,
        'php_self'    => $_SERVER['PHP_SELF'] ?? null,
        'path_info'   => $_SERVER['PATH_INFO'] ?? null,
        'orig_uri'    => $_SERVER['X-LSCACHE_ORIG_URI'] ?? ($_SERVER['ORIG_PATH_INFO'] ?? null),
    ]);
    exit;
}

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
