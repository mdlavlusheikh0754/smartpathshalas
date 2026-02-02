<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_number',
        'vehicle_model',
        'year_made',
        'driver_name',
        'driver_license',
        'driver_contact',
        'description'
    ];

    public function allocations()
    {
        return $this->hasMany(TransportAllocation::class);
    }
}
