<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MusicTrack extends Model
{
    protected $fillable = [
        'character_id',
        'title',
        'description',
        'file_path',
        'cover_image',
        'duration_seconds',
        'sort_order',
    ];

    protected $casts = [
        'duration_seconds' => 'integer',
        'sort_order' => 'integer',
    ];

    public function character(): BelongsTo
    {
        return $this->belongsTo(Character::class);
    }

    public function getFileUrlAttribute(): string
    {
        return asset('storage/'.$this->file_path);
    }

    public function getCoverUrlAttribute(): ?string
    {
        return $this->cover_image ? asset('storage/'.$this->cover_image) : null;
    }
}
