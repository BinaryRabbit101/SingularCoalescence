<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DiaryEntry extends Model
{
    protected $fillable = [
        'character_id',
        'entry_number',
        'title',
        'slug',
        'content',
        'entry_date',
        'published',
    ];

    protected $casts = [
        'entry_date' => 'date',
        'published' => 'boolean',
    ];

    public function character(): BelongsTo
    {
        return $this->belongsTo(Character::class);
    }
}
