<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BangladeshAddress extends Model
{
    // Use central database connection for shared address data
    protected $connection = 'mysql';
    
    protected $fillable = [
        'type',
        'name_bn',
        'name_en',
        'parent_id'
    ];

    // Get parent location
    public function parent()
    {
        return $this->belongsTo(BangladeshAddress::class, 'parent_id');
    }

    // Get child locations
    public function children()
    {
        return $this->hasMany(BangladeshAddress::class, 'parent_id');
    }

    // Scope for divisions
    public function scopeDivisions($query)
    {
        return $query->where('type', 'division')->whereNull('parent_id');
    }

    // Scope for districts
    public function scopeDistricts($query, $divisionId = null)
    {
        $query = $query->where('type', 'district');
        if ($divisionId) {
            $query->where('parent_id', $divisionId);
        }
        return $query;
    }

    // Scope for upazilas
    public function scopeUpazilas($query, $districtId = null)
    {
        $query = $query->where('type', 'upazila');
        if ($districtId) {
            $query->where('parent_id', $districtId);
        }
        return $query;
    }

    // Scope for unions
    public function scopeUnions($query, $upazilaId = null)
    {
        $query = $query->where('type', 'union');
        if ($upazilaId) {
            $query->where('parent_id', $upazilaId);
        }
        return $query;
    }
}
