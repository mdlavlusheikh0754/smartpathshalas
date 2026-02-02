<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationSetting extends Model
{
    protected $fillable = [
        'email_admission',
        'email_fee',
        'email_exam',
        'email_attendance',
        'sms_admission',
        'sms_fee',
        'sms_exam',
        'sms_attendance',
        'sms_notice',
        'push_admission',
        'push_fee',
        'push_notice',
        'push_exam',
        'push_event',
    ];

    protected $casts = [
        'email_admission' => 'boolean',
        'email_fee' => 'boolean',
        'email_exam' => 'boolean',
        'email_attendance' => 'boolean',
        'sms_admission' => 'boolean',
        'sms_fee' => 'boolean',
        'sms_exam' => 'boolean',
        'sms_attendance' => 'boolean',
        'sms_notice' => 'boolean',
        'push_admission' => 'boolean',
        'push_fee' => 'boolean',
        'push_notice' => 'boolean',
        'push_exam' => 'boolean',
        'push_event' => 'boolean',
    ];

    /**
     * Get the notification settings for the current tenant
     */
    public static function getSettings()
    {
        return static::first() ?? new static();
    }

    /**
     * Update or create notification settings
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
