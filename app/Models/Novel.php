<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Novel extends Model
{
    protected $table = 'novel';

    protected $fillable = [
        'title',
        'tagline',
        'description',
        'cover_image',
        'status',
    ];

    public static function getSingleton(): self
    {
        return self::firstOrCreate(['id' => 1], [
            'title' => 'Untitled Novel',
            'status' => 'draft',
        ]);
    }
}
