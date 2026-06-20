<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RequireRole
{
    /**
     * Usage in routes: ->middleware('role:super_admin,admin')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = Auth::user();

        if (! $user) {
            abort(401);
        }

        if (! $user->hasRole(...$roles)) {
            abort(403, 'You do not have permission to access this page.');
        }

        return $next($request);
    }
}
