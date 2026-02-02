<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    protected $fillable = [
        'name_bn',
        'name_en',
        'type',
        'parent_id',
        'hierarchy_path',
        'level',
        'latitude',
        'longitude',
        'postal_code',
        'code',
        'division_name',
        'district_name',
        'upazila_name',
        'description',
        'metadata',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_active' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8'
    ];

    // Relationships
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Location::class, 'parent_id')->where('is_active', true)->orderBy('sort_order')->orderBy('name_bn');
    }

    public function allChildren(): HasMany
    {
        return $this->hasMany(Location::class, 'parent_id')->orderBy('sort_order')->orderBy('name_bn');
    }

    // Scopes for different location types
    public function scopeDivisions($query)
    {
        return $query->where('type', 'division')->where('is_active', true)->orderBy('sort_order')->orderBy('name_bn');
    }

    public function scopeDistricts($query, $divisionId = null)
    {
        $query = $query->where('type', 'district')->where('is_active', true);
        if ($divisionId) {
            $query->where('parent_id', $divisionId);
        }
        return $query->orderBy('sort_order')->orderBy('name_bn');
    }

    public function scopeUpazilas($query, $districtId = null)
    {
        $query = $query->where('type', 'upazila')->where('is_active', true);
        if ($districtId) {
            $query->where('parent_id', $districtId);
        }
        return $query->orderBy('sort_order')->orderBy('name_bn');
    }

    public function scopeUnions($query, $upazilaId = null)
    {
        $query = $query->where('type', 'union')->where('is_active', true);
        if ($upazilaId) {
            $query->where('parent_id', $upazilaId);
        }
        return $query->orderBy('sort_order')->orderBy('name_bn');
    }

    public function scopeWards($query, $unionId = null)
    {
        $query = $query->where('type', 'ward')->where('is_active', true);
        if ($unionId) {
            $query->where('parent_id', $unionId);
        }
        return $query->orderBy('sort_order')->orderBy('name_bn');
    }

    public function scopeVillages($query, $parentId = null)
    {
        $query = $query->where('type', 'village')->where('is_active', true);
        if ($parentId) {
            $query->where('parent_id', $parentId);
        }
        return $query->orderBy('sort_order')->orderBy('name_bn');
    }

    // Helper methods
    public function getFullNameAttribute(): string
    {
        return $this->name_bn . ($this->name_en ? " ({$this->name_en})" : '');
    }

    public function getFullAddressAttribute(): string
    {
        $parts = [];
        
        if ($this->type !== 'division' && $this->division_name) {
            $parts[] = $this->division_name . ' বিভাগ';
        }
        
        if ($this->type !== 'district' && $this->district_name) {
            $parts[] = $this->district_name . ' জেলা';
        }
        
        if ($this->type !== 'upazila' && $this->upazila_name) {
            $parts[] = $this->upazila_name . ' উপজেলা';
        }
        
        $parts[] = $this->name_bn;
        
        return implode(', ', array_reverse($parts));
    }

    public function getAncestors()
    {
        $ancestors = collect();
        $current = $this->parent;
        
        while ($current) {
            $ancestors->prepend($current);
            $current = $current->parent;
        }
        
        return $ancestors;
    }

    public function getDescendants()
    {
        $descendants = collect();
        
        foreach ($this->children as $child) {
            $descendants->push($child);
            $descendants = $descendants->merge($child->getDescendants());
        }
        
        return $descendants;
    }

    // Update hierarchy path when saving
    protected static function boot()
    {
        parent::boot();
        
        static::saving(function ($location) {
            $location->updateHierarchyPath();
            $location->updateDivisionDistrictUpazila();
        });
    }

    private function updateHierarchyPath()
    {
        if ($this->parent_id) {
            $parent = static::find($this->parent_id);
            if ($parent) {
                $this->hierarchy_path = $parent->hierarchy_path ? $parent->hierarchy_path . '/' . $parent->id : (string)$parent->id;
                $this->level = $parent->level + 1;
            }
        } else {
            $this->hierarchy_path = null;
            $this->level = 0;
        }
    }

    private function updateDivisionDistrictUpazila()
    {
        $ancestors = [];
        $current = $this;
        
        // Get all ancestors including self
        while ($current) {
            $ancestors[] = $current;
            $current = $current->parent_id ? static::find($current->parent_id) : null;
        }
        
        // Set division, district, upazila names
        foreach (array_reverse($ancestors) as $ancestor) {
            switch ($ancestor->type) {
                case 'division':
                    $this->division_name = $ancestor->name_bn;
                    break;
                case 'district':
                    $this->district_name = $ancestor->name_bn;
                    break;
                case 'upazila':
                    $this->upazila_name = $ancestor->name_bn;
                    break;
            }
        }
    }
}