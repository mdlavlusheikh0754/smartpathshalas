<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomPaymentMethod extends Model
{
    protected $fillable = [
        'provider',
        'account_number',
        'qr_code',
        'display_name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
