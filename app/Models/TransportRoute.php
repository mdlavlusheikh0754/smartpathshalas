<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransportRoute extends Model
{
    use HasFactory;

    protected $fillable = ['route_title', 'start_point', 'end_point', 'description'];

    public function allocations()
    {
        return $this->hasMany(TransportAllocation::class, 'route_id');
    }
}
