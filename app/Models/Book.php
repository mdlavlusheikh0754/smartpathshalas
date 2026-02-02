<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'isbn',
        'category',
        'description',
        'total_quantity',
        'available_quantity',
        'shelf_location',
        'price',
    ];

    public function issues()
    {
        return $this->hasMany(BookIssue::class);
    }
}
