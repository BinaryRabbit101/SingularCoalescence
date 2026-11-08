<?php

use App\Models\Character;
use App\Models\DiaryEntry;
use App\Models\MusicTrack;
use Database\Seeders\StorySeeder;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('public');

    $dir = sys_get_temp_dir().'/sc-art-'.uniqid();
    mkdir("{$dir}/characters", 0777, true);
    file_put_contents("{$dir}/characters/barnabas.webp", 'barnabas-art');
    config(['story.art_path' => $dir]);
});

function seedLiveBaasil(): Character
{
    $baasil = Character::create([
        'slug' => 'baasil',
        'name' => 'Baasil',
        'profile_image' => 'characters/baasil.webp',
        'sort_order' => 4,
        'published' => true,
    ]);

    $baasil->diaryEntries()->create([
        'entry_number' => 1,
        'title' => 'My Friends',
        'slug' => 'my-friends',
        'content' => 'Another exhibit for the Menagerie.',
    ]);

    $baasil->musicTracks()->create([
        'title' => 'The Curator',
        'file_path' => 'music/curator.mp3',
    ]);

    Storage::disk('public')->put('characters/baasil.webp', 'old-art');

    return $baasil;
}

test('the seeder renames the live baasil row to barnabas in place', function () {
    $baasil = seedLiveBaasil();

    $this->seed(StorySeeder::class);
    $this->seed(StorySeeder::class);

    expect(Character::where('slug', 'baasil')->exists())->toBeFalse()
        ->and(Character::where('slug', 'barnabas')->count())->toBe(1);

    $barnabas = Character::where('slug', 'barnabas')->first();
    expect($barnabas->id)->toBe($baasil->id)
        ->and($barnabas->name)->toBe('Barnabas')
        ->and($barnabas->description)->toStartWith('Barnabas, known as the Curator')
        ->and($barnabas->profile_image)->toBe('characters/barnabas.webp')
        ->and($barnabas->diaryEntries()->pluck('title')->all())->toBe(['My Friends'])
        ->and($barnabas->musicTracks()->pluck('title')->all())->toBe(['The Curator'])
        ->and(DiaryEntry::where('slug', 'my-friends')->value('character_id'))->toBe($barnabas->id)
        ->and(MusicTrack::where('character_id', '!=', $barnabas->id)->exists())->toBeFalse();

    Storage::disk('public')->assertMissing('characters/baasil.webp');
    expect(Storage::disk('public')->get('characters/barnabas.webp'))->toBe('barnabas-art');

    $this->get('/characters/baasil')->assertNotFound();
    $this->get('/characters/barnabas')->assertOk();
});

test('a leftover baasil row next to barnabas is merged, not duplicated', function () {
    $baasil = seedLiveBaasil();
    $barnabas = Character::create(['slug' => 'barnabas', 'name' => 'Barnabas']);

    $this->seed(StorySeeder::class);

    expect(Character::where('slug', 'baasil')->exists())->toBeFalse()
        ->and(Character::where('slug', 'barnabas')->count())->toBe(1)
        ->and(Character::find($baasil->id))->toBeNull();

    $barnabas->refresh();
    expect($barnabas->diaryEntries()->count())->toBe(1)
        ->and($barnabas->musicTracks()->count())->toBe(1);
});
