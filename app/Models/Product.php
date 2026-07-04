<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'brand',
        'slug',
        'color',
        'price',
        'old_price',
        'plan',
        'rating',
        'reviews',
        'features',
        'stock',
        'sold',
        'status',
        'image_path',
        'servers',
        'countries',
        'devices',
        'speed',
        'protocol',
        'headquarter',
        'founded',
        'refund',
        'description',
    ];

    protected $casts = [
        'features' => 'array',
        'price' => 'float',
        'old_price' => 'float',
        'rating' => 'float',
        'reviews' => 'integer',
        'stock' => 'integer',
        'sold' => 'integer',
    ];
}
