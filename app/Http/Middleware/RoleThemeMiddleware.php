<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleThemeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();
            $theme = match(true) {
                $user->hasRole('SuperAdmin') => 'dark-theme',
                $user->hasRole('Admin') => 'light-theme',
                $user->hasRole('Staff') => 'blue-theme',
                default => 'default-theme',
            };
            session(['theme' => $theme]);
        }
        return $next($request);
    }
}
