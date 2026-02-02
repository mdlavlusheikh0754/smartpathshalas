<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ZktecoApiValidation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Log API request for monitoring
        $this->logApiRequest($request);
        
        // Validate request format
        if (!$this->validateRequestFormat($request)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid request format',
                'errors' => ['Invalid Content-Type or malformed request']
            ], 400);
        }
        
        // Validate device authentication for device-specific endpoints
        if ($this->requiresDeviceValidation($request)) {
            $validation = $this->validateDeviceRequest($request);
            if (!$validation['valid']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Device validation failed',
                    'errors' => $validation['errors']
                ], 401);
            }
        }
        
        $response = $next($request);
        
        // Log API response for monitoring
        $this->logApiResponse($request, $response);
        
        return $response;
    }
    
    /**
     * Validate request format
     */
    protected function validateRequestFormat(Request $request): bool
    {
        // For POST/PUT requests, ensure JSON content type
        if (in_array($request->method(), ['POST', 'PUT', 'PATCH'])) {
            $contentType = $request->header('Content-Type');
            
            if (!str_contains($contentType, 'application/json')) {
                return false;
            }
            
            // Validate JSON payload
            if ($request->getContent() && !json_decode($request->getContent())) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Check if request requires device validation
     */
    protected function requiresDeviceValidation(Request $request): bool
    {
        $path = $request->path();
        
        // Device-specific endpoints that require device validation
        $deviceEndpoints = [
            'attendance/records',
            'devices/',
            'heartbeat'
        ];
        
        foreach ($deviceEndpoints as $endpoint) {
            if (str_contains($path, $endpoint)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Validate device-specific request
     */
    protected function validateDeviceRequest(Request $request): array
    {
        $errors = [];
        
        // Check for device_id in request
        if (!$request->has('device_id') && !$request->route('deviceId')) {
            $errors[] = 'Device ID is required for this endpoint';
        }
        
        // Validate device_id format
        $deviceId = $request->input('device_id') ?? $request->route('deviceId');
        if ($deviceId && !preg_match('/^[A-Z0-9_-]+$/i', $deviceId)) {
            $errors[] = 'Invalid device ID format';
        }
        
        // Check if device exists (for non-registration endpoints)
        if ($deviceId && !str_contains($request->path(), 'register')) {
            $device = \App\Models\ZktecoDevice::where('device_id', $deviceId)->first();
            if (!$device) {
                $errors[] = 'Device not found or not registered';
            } elseif (!$device->is_active) {
                $errors[] = 'Device is not active';
            }
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }
    
    /**
     * Log API request
     */
    protected function logApiRequest(Request $request): void
    {
        $logData = [
            'method' => $request->method(),
            'path' => $request->path(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'device_id' => $request->input('device_id') ?? $request->route('deviceId'),
            'user_id' => $request->user()?->id,
            'timestamp' => now()->toISOString(),
        ];
        
        // Log request payload for POST requests (excluding sensitive data)
        if ($request->isMethod('POST') && $request->has('records')) {
            $logData['records_count'] = count($request->input('records', []));
        }
        
        Log::channel('daily')->info('ZKTeco API Request', $logData);
    }
    
    /**
     * Log API response
     */
    protected function logApiResponse(Request $request, Response $response): void
    {
        $logData = [
            'method' => $request->method(),
            'path' => $request->path(),
            'status_code' => $response->getStatusCode(),
            'device_id' => $request->input('device_id') ?? $request->route('deviceId'),
            'user_id' => $request->user()?->id,
            'response_time_ms' => round((microtime(true) - LARAVEL_START) * 1000, 2),
            'timestamp' => now()->toISOString(),
        ];
        
        // Log error responses with more detail
        if ($response->getStatusCode() >= 400) {
            $content = json_decode($response->getContent(), true);
            $logData['error_message'] = $content['message'] ?? 'Unknown error';
            
            Log::channel('daily')->warning('ZKTeco API Error Response', $logData);
        } else {
            Log::channel('daily')->info('ZKTeco API Response', $logData);
        }
    }
}