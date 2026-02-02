<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_code',
        'name',
        'category',
        'class',
        'price',
        'stock',
        'min_stock',
        'unit',
        'description',
        'status'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
        'min_stock' => 'integer',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($item) {
            if (empty($item->item_code)) {
                $item->item_code = 'INV' . str_pad(static::count() + 1, 3, '0', STR_PAD_LEFT);
            }
            $item->updateStatus();
        });

        static::updating(function ($item) {
            $item->updateStatus();
        });
    }

    /**
     * Update status based on stock levels
     */
    public function updateStatus()
    {
        if ($this->stock == 0) {
            $this->status = 'out_of_stock';
        } elseif ($this->stock <= $this->min_stock) {
            $this->status = 'low_stock';
        } else {
            $this->status = 'in_stock';
        }
    }

    /**
     * Get category name in Bengali
     */
    public function getCategoryNameAttribute()
    {
        $categories = [
            'uniform' => 'ইউনিফর্ম',
            'books' => 'বই',
            'accessories' => 'এক্সেসরিজ',
            'sports' => 'খেলাধুলা',
            'stationery' => 'স্টেশনারি',
            'furniture' => 'আসবাবপত্র',
            'electronics' => 'ইলেকট্রনিক্স',
            'medical' => 'চিকিৎসা সামগ্রী',
            'cleaning' => 'পরিষ্কার সামগ্রী',
            'safety' => 'নিরাপত্তা সামগ্রী'
        ];

        return $categories[$this->category] ?? $this->category;
    }

    /**
     * Get status name in Bengali
     */
    public function getStatusNameAttribute()
    {
        $statuses = [
            'in_stock' => 'স্টকে আছে',
            'low_stock' => 'কম স্টক',
            'out_of_stock' => 'স্টক নেই'
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    /**
     * Get unit name in Bengali
     */
    public function getUnitNameAttribute()
    {
        $units = [
            'piece' => 'পিস',
            'set' => 'সেট',
            'pair' => 'জোড়া',
            'kg' => 'কেজি',
            'liter' => 'লিটার'
        ];

        return $units[$this->unit] ?? $this->unit;
    }
}