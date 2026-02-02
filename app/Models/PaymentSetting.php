<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentSetting extends Model
{
    protected $fillable = [
        'ssl_active', 'ssl_store_id', 'ssl_store_password', 'ssl_mode',
        'shurjopay_active', 'shurjopay_username', 'shurjopay_password', 'shurjopay_prefix', 'shurjopay_mode',
        'bkash_active', 'bkash_app_key', 'bkash_app_secret', 'bkash_username', 'bkash_password', 'bkash_mode',
        'nagad_active', 'nagad_merchant_id', 'nagad_public_key', 'nagad_private_key', 'nagad_mode',
        'amarpay_active', 'amarpay_store_id', 'amarpay_signature_key', 'amarpay_mode'
    ];

    protected $casts = [
        'ssl_active' => 'boolean',
        'shurjopay_active' => 'boolean',
        'bkash_active' => 'boolean',
        'nagad_active' => 'boolean',
        'amarpay_active' => 'boolean',
    ];

    public static function getSettings()
    {
        return static::first() ?? new static();
    }

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
