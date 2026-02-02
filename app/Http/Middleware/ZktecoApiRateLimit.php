<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class ZktecoApiRateLimit
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): SymfonyResponse
    {
        // Different rate limits for different endpoints
        $rateLimits = $this->getRateLimits($request);
        
        $key = $this->resolveRequestSignature($request);
        
        foreach ($rateLimits as $limit) {
            $maxAttempts = $limit['max_attempts'];
            $decayMinutes = $limit['decay_minutes'];
            $limitKey = $key . ':' . $limit['name'];
            
            if (RateLimiter::tooManyAttempts($limitKey, $maxAttempts)) {
                $retryAfter = RateLimiter::availableIn($limitKey);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Too many requests. Please try again later.',
                    'retry_after' => $retryAfter,
                    'limit_type' => $limit['name']
                ], 429)->header('Retry-After', $retryAfter);
            }
            
            RateLimiter::hit($limitKey, $decayMinutes * 60);
        }
        
        $response = $next($request);
        
        // Add rate limit headers
        $primaryLimit = $rateLimits[0];
        $primaryKey = $key . ':' . $primaryLimit['name'];
        
        $response->headers->set('X-RateLimit-Limit', $primaryLimit['max_attempts']);
        $response->headers->set('X-RateLimit-Remaining', 
            max(0, $primaryLimit['max_attempts'] - RateLimiter::attempts($primaryKey))
        );
        
        return $response;
    }
    
    /**
     * Get rate limits based on the request
     */
    protected function getRateLimits(Request $request): array
    {
        $path = $request->path();
        
        // High-frequency endpoints (attendance data submission)
        if (str_contains($path, 'attendance/records') && $request->isMethod('POST')) {
            return [
                [
                    'name' => 'attendance_submission',
                    'max_attempts' => 60, // 60 requests per minute (1 per second) - more conservative
                    'decay_minutes' => 1
                ],
                [
                    'name' => 'attendance_hourly',
                    'max_attempts' => 1800, // 1800 requests per hour
                    'decay_minutes' => 60
                ],
                [
                    'name' => 'attendance_daily',
                    'max_attempts' => 10000, // 10k requests per day
                    'decay_minutes' => 1440
                ]
            ];
        }
        
        // Device heartbeat endpoints
        if (str_contains($path, 'heartbeat')) {
            return [
                [
                    'name' => 'heartbeat',
                    'max_attempts' => 60, // 60 requests per minute (1 per second)
                    'decay_minutes' => 1
                ]
            ];
        }
        
        // Device management endpoints
        if (str_contains($path, 'devices') && $request->isMethod('POST')) {
            return [
                [
                    'name' => 'device_registration',
                    'max_attempts' => 10, // 10 device registrations per hour
                    'decay_minutes' => 60
                ]
            ];
        }
        
        // Student sync endpoints
        if (str_contains($path, 'students/sync') || str_contains($path, 'rfid-mappings')) {
            return [
                [
                    'name' => 'student_sync',
                    'max_attempts' => 30, // 30 requests per minute
                    'decay_minutes' => 1
                ]
            ];
        }
        
        // Report endpoints
        if (str_contains($path, 'reports')) {
            return [
                [
                    'name' => 'reports',
                    'max_attempts' => 20, // 20 reports per minute
                    'decay_minutes' => 1
                ]
            ];
        }
        
        // Default rate limit for other ZKTeco API endpoints
        return [
            [
                'name' => 'zkteco_api',
                'max_attempts' => 60, // 60 requests per minute
                'decay_minutes' => 1
            ]
        ];
    }
    
    /**
     * Resolve the request signature for rate limiting
     */
    protected function resolveRequestSignature(Request $request): string
    {
        // Use device_id from request if available (for device-specific limits)
        if ($request->has('device_id')) {
            return 'zkteco_device:' . $request->input('device_id');
        }
        
        // Use authenticated user if available
        if ($request->user()) {
            return 'zkteco_user:' . $request->user()->id;
        }
        
        // Fall back to IP address
        return 'zkteco_ip:' . $request->ip();
    }
}