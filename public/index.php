<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// ---------------------------------------------------------------------------
// Shared-hosting URL normalization. Production (LiteSpeed) serves this app
// from a subfolder and rewrites /new_contacts/* into public/*, but PHP still
// reports SCRIPT_NAME as .../public/index.php, so Symfony can't derive the
// base URL for the clean (no /public/) form. The .htaccess 301 for the bare
// /public/ URL also never fires on LiteSpeed, so both are handled here.
// ---------------------------------------------------------------------------
$reqPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
$script  = $_SERVER['SCRIPT_NAME'] ?? '';

// Bare .../new_contacts/public[/] → permanent redirect to the clean app root.
if ($reqPath === '/new_contacts/public' || $reqPath === '/new_contacts/public/') {
    $qs = ($_SERVER['QUERY_STRING'] ?? '') !== '' ? '?'.$_SERVER['QUERY_STRING'] : '';
    header('Location: /new_contacts/'.$qs, true, 301);
    exit;
}

// The hosting stack mismatches GET against the cached root route (GET on "/"
// 405s while all other methods match), so send the app root to /dashboard
// before the router — auth middleware bounces guests to the login page.
if ($reqPath === '/new_contacts/' && ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'GET' && ! isset($_GET['__probe2'])) {
    header('Location: /new_contacts/dashboard', true, 302);
    exit;
}

// Clean-form request (no /public/ in the URL): report the front controller
// at the app prefix so Symfony computes baseUrl=/new_contacts.
if (str_ends_with($script, '/public/index.php')) {
    $publicPrefix = substr($script, 0, -strlen('/index.php')); // .../public
    if (! str_starts_with($reqPath, $publicPrefix)) {
        $_SERVER['SCRIPT_NAME'] = substr($publicPrefix, 0, -strlen('/public')).'/index.php';
        $_SERVER['PHP_SELF']    = $_SERVER['SCRIPT_NAME'];
    }
}

// Temporary routing probe: boots the app and reports what the router sees.
if (isset($_GET['__probe2'])) {
    require __DIR__.'/../vendor/autoload.php';
    $app = require_once __DIR__.'/../bootstrap/app.php';
    $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
    $req = Request::capture();
    $out = [
        'method'   => $req->getMethod(),
        'pathInfo' => $req->getPathInfo(),
        'baseUrl'  => $req->getBaseUrl(),
        'path'     => $req->path(),
    ];
    try {
        $route = app('router')->getRoutes()->match($req);
        $out['matched'] = implode('|', $route->methods()).' '.$route->uri();
    } catch (Throwable $e) {
        $out['exception'] = get_class($e).': '.$e->getMessage();
    }
    $out['routes_cached'] = app()->routesAreCached();
    $out['slash_routes'] = [];
    foreach (app('router')->getRoutes()->getRoutes() as $rt) {
        if ($rt->uri() === '/') {
            $out['slash_routes'][] = implode('|', $rt->methods()).' -> '.$rt->getActionName();
        }
    }
    header('Content-Type: application/json');
    echo json_encode($out);
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
