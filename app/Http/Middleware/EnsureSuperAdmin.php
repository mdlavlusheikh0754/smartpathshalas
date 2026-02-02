<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSuperAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            // Return JSON response for API requests
            if ($request->expectsJson() || $request->is('admin/api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'আপনার এই পেজে প্রবেশের অনুমতি নেই।'
                ], 403);
            }
            
            abort(403, 'আপনার এই পেজে প্রবেশের অনুমতি নেই।');
        }

        return $next($request);
    }
}
