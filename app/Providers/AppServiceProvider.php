<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Bengali number conversion helper
        if (!function_exists('toBengaliNumber')) {
            function toBengaliNumber($number) {
                $bengaliNumbers = [
                    '0' => '০', '1' => '১', '2' => '২', '3' => '৩', '4' => '৪',
                    '5' => '৫', '6' => '৬', '7' => '৭', '8' => '৮', '9' => '৯'
                ];
                return strtr((string)$number, $bengaliNumbers);
            }
        }
        
        // Bengali date formatting helper
        if (!function_exists('toBengaliDate')) {
            function toBengaliDate($date, $format = 'd/m/Y') {
                if ($date instanceof \Carbon\Carbon) {
                    $formattedDate = $date->format($format);
                } else {
                    $formattedDate = date($format, strtotime($date));
                }
                return toBengaliNumber($formattedDate);
            }
        }
    }
}
