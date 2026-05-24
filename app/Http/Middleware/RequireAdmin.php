<?php

namespace App\Http\Middleware;

use App\Support\AdminAccess;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (AdminAccess::allows()) {
            return $next($request);
        }

        if (! AdminAccess::isConfigured()) {
            abort(500, 'Admin email allowlist is not configured.');
        }

        return redirect()->guest(route('login.google'));
    }
}
