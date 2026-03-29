<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'tagline',
        'description',
        'type',
        'images',
        'url',
        'sort_order',
        'published',
    ];

    protected $casts = [
        'images' => 'array',
        'published' => 'boolean',
    ];
}
