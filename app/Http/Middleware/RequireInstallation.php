<?php

namespace App\Http\Middleware;

use App\Support\Installer;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireInstallation
{
    public function handle(Request $request, Closure $next): Response
    {
        $installed = Installer::isInstalled();

        // If we're on an install route, only allow if NOT installed.
        if ($request->is('install', 'install/*')) {
            if ($installed) {
                return redirect('/');
            }
            return $next($request);
        }

        // Allow asset URLs and health probes through always.
        if ($request->is('build/*', 'storage/*', 'up', 'favicon.ico')) {
            return $next($request);
        }

        // Otherwise, if not installed, force the wizard.
        if (! $installed) {
            return redirect()->route('install.welcome');
        }

        return $next($request);
    }
}
