<?php

return [
    /*
    |--------------------------------------------------------------------------
    | ZKTeco Device Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for ZKTeco biometric devices
    |
    */

    'device_ip' => env('ZKTECO_DEVICE_IP', '192.168.1.201'),
    'device_port' => env('ZKTECO_DEVICE_PORT', 4370),
    
    /*
    |--------------------------------------------------------------------------
    | Connection Settings
    |--------------------------------------------------------------------------
    */
    
    'connection_timeout' => env('ZKTECO_CONNECTION_TIMEOUT', 5),
    'max_retry_attempts' => env('ZKTECO_MAX_RETRY_ATTEMPTS', 3),
    
    /*
    |--------------------------------------------------------------------------
    | Sync Settings
    |--------------------------------------------------------------------------
    */
    
    'auto_sync_enabled' => env('ZKTECO_AUTO_SYNC_ENABLED', false),
    'sync_interval_minutes' => env('ZKTECO_SYNC_INTERVAL_MINUTES', 30),
    'clear_device_after_sync' => env('ZKTECO_CLEAR_DEVICE_AFTER_SYNC', false),
    
    /*
    |--------------------------------------------------------------------------
    | User Management
    |--------------------------------------------------------------------------
    */
    
    'user_privilege_level' => env('ZKTECO_USER_PRIVILEGE_LEVEL', 0), // 0=User, 14=Admin
    'use_student_roll_as_user_id' => env('ZKTECO_USE_STUDENT_ROLL_AS_USER_ID', true),
    
    /*
    |--------------------------------------------------------------------------
    | Logging
    |--------------------------------------------------------------------------
    */
    
    'enable_logging' => env('ZKTECO_ENABLE_LOGGING', true),
    'log_level' => env('ZKTECO_LOG_LEVEL', 'info'), // debug, info, warning, error
];