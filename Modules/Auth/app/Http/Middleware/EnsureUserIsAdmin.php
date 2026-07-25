<?php

namespace Modules\Auth\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check()) {
            return redirect()->route(config('auth.redirects.guest'));
        }

        if (! auth()->user()->isAdmin()) {
            abort(403, __('Access denied. Admin privileges required.'));
        }

        return $next($request);
    }
}
