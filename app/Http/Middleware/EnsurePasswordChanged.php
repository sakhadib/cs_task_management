<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsurePasswordChanged
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip check if user is not authenticated
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();

        // Skip check if already on password change route or logout route
        if ($request->routeIs('password.change') || 
            $request->routeIs('password.update') || 
            $request->routeIs('logout')) {
            return $next($request);
        }

        // Redirect to password change page if password not changed
        if (!$user->is_password_changed) {
            return redirect()->route('password.change')
                ->with('warning', 'You must change your password before accessing the system.');
        }

        return $next($request);
    }
}
