<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UniverseEntry extends Model
{
    protected $fillable = [
        'category',
        'name',
        'slug',
        'description',
        'image',
        'sort_order',
        'published',
    ];

    protected $casts = [
        'published' => 'boolean',
    ];
}
