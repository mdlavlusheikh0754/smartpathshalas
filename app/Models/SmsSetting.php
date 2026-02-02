<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsSetting extends Model
{
    protected $fillable = [
        'sms_provider',
        'api_url',
        'api_key',
        'api_secret',
        'sender_id',
        'template_admission',
        'template_fee_payment',
        'template_absent',
    ];

    /**
     * Get the SMS settings for the current tenant
     */
    public static function getSettings()
    {
        return static::first() ?? new static([
            'sms_provider' => 'bulksms_bangladesh',
            'template_admission' => 'প্রিয় {name}, আপনার সন্তান {student_name} কে {class} শ্রেণীতে ভর্তি করা হয়েছে। রোল: {roll}',
            'template_fee_payment' => 'প্রিয় {name}, {student_name} এর {month} মাসের ফি ৳{amount} পরিশোধ করা হয়েছে। ধন্যবাদ।',
            'template_absent' => 'প্রিয় {name}, আপনার সন্তান {student_name} আজ {date} তারিখে স্কুলে অনুপস্থিত ছিল।',
        ]);
    }

    /**
     * Update or create SMS settings
     */
    public static function updateSettings(array $data)
    {
        $settings = static::first();
        
        if ($settings) {
            $settings->update($data);
        } else {
            $settings = static::create($data);
        }
        
        return $settings;
    }
}
