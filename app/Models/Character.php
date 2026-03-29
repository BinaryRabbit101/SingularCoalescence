<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Character extends Model
{
    protected $fillable = [
        'slug',
        'name',
        'tagline',
        'description',
        'body_name',
        'body_hair_color',
        'profile_image',
        'action_image',
        'action_image_prompt',
        'traits',
        'abilities',
        'cons',
        'sort_order',
        'published',
    ];

    protected $casts = [
        'traits' => 'array',
        'abilities' => 'array',
        'cons' => 'array',
        'published' => 'boolean',
    ];

    public function diaryEntries(): HasMany
    {
        return $this->hasMany(DiaryEntry::class)->orderBy('entry_number');
    }

    public function musicTracks(): HasMany
    {
        return $this->hasMany(MusicTrack::class)->orderBy('sort_order');
    }
}
