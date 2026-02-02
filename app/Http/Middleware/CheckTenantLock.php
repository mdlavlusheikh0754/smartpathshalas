<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTenantLock
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if tenant is locked
        if (tenant() && tenant()->is_locked) {
            \Illuminate\Support\Facades\Log::info('Tenant is locked', ['tenant' => tenant('id'), 'user' => auth()->id()]);
            // If trying to access login page, allow it (for admin override)
            if ($request->routeIs('login')) {
                return $next($request);
            }
            
            // If user is logged in and is admin, allow access
            if (auth()->check() && auth()->user()->role === 'admin') {
                return $next($request);
            }
            
            // If user is logged in but tenant is locked, logout and redirect
            if (auth()->check()) {
                auth()->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            }
            
            // Show lock message for all other requests
            return response()->view('errors.locked', [], 403);
        }
        
        return $next($request);
    }
}
