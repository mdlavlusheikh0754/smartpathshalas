<?php

use App\Helpers\BengaliHelper;

if (!function_exists('tenant_asset')) {
    /**
     * Generate a tenant-aware asset URL
     */
    function tenant_asset($path)
    {
        return url($path);
    }
}

if (!function_exists('tenant_storage_url')) {
    /**
     * Generate a tenant-aware storage URL
     */
    function tenant_storage_url($path)
    {
        // Remove leading slash if present
        $path = ltrim($path, '/');
        
        // In web context, use the current request URL
        if (app()->runningInConsole()) {
            // For console commands, we can't determine the tenant URL
            return '/storage/' . $path;
        }
        
        // Use the current request's base URL to ensure tenant context
        return request()->getSchemeAndHttpHost() . '/storage/' . $path;
    }
}

if (!function_exists('toBengaliDate')) {
    /**
     * Convert date to Bengali format
     */
    function toBengaliDate($date, $format = 'd M, Y')
    {
        return BengaliHelper::toBengaliDate($date, $format);
    }
}

if (!function_exists('toBengaliNumber')) {
    /**
     * Convert English numbers to Bengali
     */
    function toBengaliNumber($number)
    {
        return BengaliHelper::toBengaliNumber($number);
    }
}

if (!function_exists('toBengaliTime')) {
    /**
     * Convert time to Bengali format
     */
    function toBengaliTime($time, $format = 'h:i A')
    {
        return BengaliHelper::toBengaliTime($time, $format);
    }
}

if (!function_exists('toBengaliDateTime')) {
    /**
     * Convert datetime to Bengali format
     */
    function toBengaliDateTime($datetime, $dateFormat = 'd M, Y', $timeFormat = 'h:i A')
    {
        return BengaliHelper::toBengaliDateTime($datetime, $dateFormat, $timeFormat);
    }
}

if (!function_exists('getBengaliDayName')) {
    /**
     * Get Bengali day name
     */
    function getBengaliDayName($date)
    {
        return BengaliHelper::getBengaliDayName($date);
    }
}

if (!function_exists('formatBengaliNumber')) {
    /**
     * Format number with Bengali digits
     */
    function formatBengaliNumber($number, $decimals = 0)
    {
        return BengaliHelper::formatBengaliNumber($number, $decimals);
    }
}

if (!function_exists('en2bn')) {
    /**
     * Convert English numbers to Bengali (alias for toBengaliNumber)
     */
    function en2bn($number)
    {
        return BengaliHelper::toBengaliNumber($number);
    }
}

if (!function_exists('bn2en')) {
    /**
     * Convert Bengali numbers to English
     */
    function bn2en($number)
    {
        $bengaliToEnglish = [
            '০' => '0', '১' => '1', '২' => '2', '৩' => '3', '৪' => '4',
            '৫' => '5', '৬' => '6', '৭' => '7', '৮' => '8', '৯' => '9'
        ];
        
        return strtr($number, $bengaliToEnglish);
    }
}