<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(403, 'You do not have permission to access this resource.');
        }

        if ($user->hasAnyRole(['admin', 'super_admin'])) {
            return $next($request);
        }

        if (!$user->hasAnyRole($roles)) {
            abort(403, 'You do not have permission to access this resource.');
        }

        return $next($request);
    }
}
