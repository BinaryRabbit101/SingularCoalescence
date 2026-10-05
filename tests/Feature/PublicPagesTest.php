<?php

use App\Models\Character;
use App\Models\Novel;
use Database\Seeders\StorySeeder;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('public');
    // Keep the repo's real art out of the tests; each test points at its own fixture.
    config(['story.art_path' => sys_get_temp_dir().'/sc-art-none']);
    $this->seed(StorySeeder::class);
});

test('public pages render', function (string $url) {
    $this->get($url)->assertOk();
})->with([
    '/',
    '/characters',
    '/characters/charlotte',
    '/characters/baasil',
    '/novel',
    '/universe',
    '/universe/portal-discs',
    '/products',
]);

test('charlotte has five published undated diary entries', function () {
    $entries = Character::where('slug', 'charlotte')->firstOrFail()
        ->diaryEntries()->orderBy('entry_number')->get();

    expect($entries)->toHaveCount(5)
        ->and($entries->first()->title)->toBe('Hundreds of Light Years')
        ->and($entries->every(fn ($e) => $e->published === true && $e->entry_date === null))->toBeTrue();
});

test('the retired character is gone', function () {
    $this->get('/characters/felix')->assertNotFound();
});

test('home has no banner until the art exists', function () {
    $this->get('/')->assertInertia(fn ($page) => $page->where('banner', null));

    Storage::disk('public')->put('banner/storytime.webp', 'x');

    $this->get('/')->assertInertia(fn ($page) => $page->where('banner', fn ($banner) => str_starts_with($banner, 'banner/storytime.webp?v=')));
});

test('the seeder publishes art and is idempotent', function () {
    $dir = sys_get_temp_dir().'/sc-art-'.uniqid();
    mkdir("{$dir}/characters", 0777, true);
    mkdir("{$dir}/banner", 0777, true);
    mkdir("{$dir}/novel", 0777, true);
    file_put_contents("{$dir}/characters/liam.webp", 'liam-art');
    file_put_contents("{$dir}/banner/storytime.webp", 'banner-art');
    file_put_contents("{$dir}/novel/cover.webp", 'cover-art');
    config(['story.art_path' => $dir]);

    Character::where('slug', 'charlotte')->update(['profile_image' => 'old/charlotte.png']);

    $this->seed(StorySeeder::class);
    $this->seed(StorySeeder::class);

    $liam = Character::where('slug', 'liam')->first();
    expect($liam->profile_image)->toBe('characters/liam.webp')
        ->and($liam->action_image)->toBeNull();
    Storage::disk('public')->assertExists('characters/liam.webp');
    Storage::disk('public')->assertExists('banner/storytime.webp');
    expect(Storage::disk('public')->get('characters/liam.webp'))->toBe('liam-art');
    Storage::disk('public')->assertMissing('characters/charlotte.webp');
    expect(Storage::disk('public')->get('novel/cover.webp'))->toBe('cover-art')
        ->and(Novel::getSingleton()->cover_image)->toBe('novel/cover.webp');
});
