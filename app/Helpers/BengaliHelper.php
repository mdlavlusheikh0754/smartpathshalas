<?php

namespace App\Helpers;

use Carbon\Carbon;

class BengaliHelper
{
    /**
     * English to Bengali number mapping
     */
    private static $englishToBengali = [
        '0' => '০', '1' => '১', '2' => '২', '3' => '৩', '4' => '৪',
        '5' => '৫', '6' => '৬', '7' => '৭', '8' => '৮', '9' => '৯'
    ];

    /**
     * English to Bengali month mapping
     */
    private static $englishToBengaliMonths = [
        'January' => 'জানুয়ারি', 'February' => 'ফেব্রুয়ারি', 'March' => 'মার্চ',
        'April' => 'এপ্রিল', 'May' => 'মে', 'June' => 'জুন',
        'July' => 'জুলাই', 'August' => 'আগস্ট', 'September' => 'সেপ্টেম্বর',
        'October' => 'অক্টোবর', 'November' => 'নভেম্বর', 'December' => 'ডিসেম্বর'
    ];

    /**
     * Short month names
     */
    private static $shortMonths = [
        'Jan' => 'জান', 'Feb' => 'ফেব', 'Mar' => 'মার্চ',
        'Apr' => 'এপ্রিল', 'May' => 'মে', 'Jun' => 'জুন',
        'Jul' => 'জুলাই', 'Aug' => 'আগস্ট', 'Sep' => 'সেপ্টেম্বর',
        'Oct' => 'অক্টোবর', 'Nov' => 'নভেম্বর', 'Dec' => 'ডিসেম্বর'
    ];

    /**
     * Convert English numbers to Bengali
     */
    public static function toBengaliNumber($number)
    {
        return strtr($number, self::$englishToBengali);
    }

    /**
     * Convert date to Bengali format
     */
    public static function toBengaliDate($date, $format = 'd M, Y')
    {
        if (!$date) {
            return '';
        }

        // Convert to Carbon instance if it's not already
        if (!$date instanceof Carbon) {
            $date = Carbon::parse($date);
        }

        // Format the date
        $formattedDate = $date->format($format);

        // Convert numbers to Bengali
        $formattedDate = self::toBengaliNumber($formattedDate);

        // Convert month names to Bengali
        foreach (self::$shortMonths as $english => $bengali) {
            $formattedDate = str_replace($english, $bengali, $formattedDate);
        }

        foreach (self::$englishToBengaliMonths as $english => $bengali) {
            $formattedDate = str_replace($english, $bengali, $formattedDate);
        }

        return $formattedDate;
    }

    /**
     * Convert time to Bengali format
     */
    public static function toBengaliTime($time, $format = 'h:i A')
    {
        if (!$time) {
            return '';
        }

        // Convert to Carbon instance if it's not already
        if (!$time instanceof Carbon) {
            $time = Carbon::parse($time);
        }

        // Format the time
        $formattedTime = $time->format($format);

        // Convert numbers to Bengali
        $formattedTime = self::toBengaliNumber($formattedTime);

        // Convert AM/PM to Bengali
        $formattedTime = str_replace(['AM', 'PM'], ['পূর্বাহ্ন', 'অপরাহ্ন'], $formattedTime);

        return $formattedTime;
    }

    /**
     * Convert datetime to Bengali format
     */
    public static function toBengaliDateTime($datetime, $dateFormat = 'd M, Y', $timeFormat = 'h:i A')
    {
        if (!$datetime) {
            return '';
        }

        $date = self::toBengaliDate($datetime, $dateFormat);
        $time = self::toBengaliTime($datetime, $timeFormat);

        return $date . ' ' . $time;
    }

    /**
     * Get Bengali day name
     */
    public static function getBengaliDayName($date)
    {
        if (!$date) {
            return '';
        }

        if (!$date instanceof Carbon) {
            $date = Carbon::parse($date);
        }

        $dayNames = [
            'Sunday' => 'রবিবার',
            'Monday' => 'সোমবার',
            'Tuesday' => 'মঙ্গলবার',
            'Wednesday' => 'বুধবার',
            'Thursday' => 'বৃহস্পতিবার',
            'Friday' => 'শুক্রবার',
            'Saturday' => 'শনিবার'
        ];

        return $dayNames[$date->format('l')] ?? '';
    }

    /**
     * Format number with Bengali digits
     */
    public static function formatBengaliNumber($number, $decimals = 0)
    {
        $formatted = number_format($number, $decimals);
        return self::toBengaliNumber($formatted);
    }

    /**
     * Convert English text to Bengali numbers only
     */
    public static function convertNumbersOnly($text)
    {
        return self::toBengaliNumber($text);
    }
}