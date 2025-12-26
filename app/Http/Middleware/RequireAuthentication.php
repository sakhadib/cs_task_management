<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequireAuthentication
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Allow login routes for guests
        $routeName = optional($request->route())->getName();

        if (Auth::check()) {
            return $next($request);
        }

        if (in_array($routeName, ['login', 'login.post'])) {
            return $next($request);
        }

        // Allow the login page path as well
        if ($request->is('/')) {
            return $next($request);
        }

        // otherwise redirect to login
        return redirect()->route('login');
    }
}
