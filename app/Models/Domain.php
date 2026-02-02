<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Models\Domain as BaseDomain;

class Domain extends BaseDomain
{
    protected $table = 'domains';

    protected $fillable = [
        'domain',
        'tenant_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the tenant that owns the domain.
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
