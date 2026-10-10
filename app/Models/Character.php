<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Character extends Model
{
    protected $fillable = [
        'slug',
        'name',
        'tagline',
        'description',
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

    /**
     * Append each image's modified time so a replaced portrait busts Cloudflare's
     * week-long /storage cache. For display only; never save the result.
     */
    public function withImageVersions(): static
    {
        $disk = Storage::disk('public');

        foreach (['profile_image', 'action_image'] as $field) {
            if ($this->{$field} && $disk->exists($this->{$field})) {
                $this->{$field} .= '?v='.$disk->lastModified($this->{$field});
            }
        }

        return $this;
    }

    public function diaryEntries(): HasMany
    {
        return $this->hasMany(DiaryEntry::class)->orderBy('entry_number');
    }

    public function musicTracks(): HasMany
    {
        return $this->hasMany(MusicTrack::class)->orderBy('sort_order');
    }
}
