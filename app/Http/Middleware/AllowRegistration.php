<?php

namespace App\Http\Middleware;

use App\Settings\GeneralSettings;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AllowRegistration
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! app(GeneralSettings::class)->allow_registration) {
            abort(404);
        }

        return $next($request);
    }
}
