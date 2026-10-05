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

test('home shows only the banners whose art exists', function () {
    config(['story.banners' => [
        ['file' => 'one.webp', 'alt' => 'First'],
        ['file' => 'two.webp', 'alt' => 'Second'],
    ]]);

    $this->get('/')->assertInertia(fn ($page) => $page->has('banners', 0));

    Storage::disk('public')->put('banner/two.webp', 'x');

    $this->get('/')->assertInertia(fn ($page) => $page
        ->has('banners', 1)
        ->where('banners.0.src', fn ($src) => str_starts_with($src, 'banner/two.webp?v='))
        ->where('banners.0.alt', 'Second'));
});

test('home banners follow the configured slide order', function () {
    config(['story.banners' => [
        ['file' => 'one.webp', 'alt' => 'First'],
        ['file' => 'two.webp', 'alt' => 'Second'],
    ]]);
    Storage::disk('public')->put('banner/two.webp', 'x');
    Storage::disk('public')->put('banner/one.webp', 'x');

    $this->get('/')->assertInertia(fn ($page) => $page
        ->has('banners', 2)
        ->where('banners.0.src', fn ($src) => str_starts_with($src, 'banner/one.webp?v='))
        ->where('banners.1.src', fn ($src) => str_starts_with($src, 'banner/two.webp?v=')));
});

test('home shows only the storytime banner for now', function () {
    // Movie Night is painted but left out of the carousel (owner, 2026-10-05).
    Storage::disk('public')->put('banner/storytime.webp', 'x');
    Storage::disk('public')->put('banner/movienight.webp', 'x');

    $this->get('/')->assertInertia(fn ($page) => $page
        ->has('banners', 1)
        ->where('banners.0.src', fn ($src) => str_starts_with($src, 'banner/storytime.webp?v=')));
});

test('the novel cover url busts the cache once the art exists', function () {
    Novel::getSingleton()->update(['cover_image' => 'novel/cover.webp']);

    $this->get('/novel')->assertInertia(fn ($page) => $page->where('novel.cover_image', 'novel/cover.webp'));

    Storage::disk('public')->put('novel/cover.webp', 'x');

    $this->get('/novel')->assertInertia(fn ($page) => $page->where('novel.cover_image', fn ($cover) => str_starts_with($cover, 'novel/cover.webp?v=')));
    expect(Novel::getSingleton()->cover_image)->toBe('novel/cover.webp');
});

test('the seeder publishes art and is idempotent', function () {
    $dir = sys_get_temp_dir().'/sc-art-'.uniqid();
    mkdir("{$dir}/characters", 0777, true);
    mkdir("{$dir}/banner", 0777, true);
    mkdir("{$dir}/novel", 0777, true);
    file_put_contents("{$dir}/characters/liam.webp", 'liam-art');
    file_put_contents("{$dir}/banner/storytime.webp", 'banner-art');
    file_put_contents("{$dir}/banner/movienight.webp", 'movie-art');
    file_put_contents("{$dir}/novel/cover.webp", 'cover-art');
    config(['story.art_path' => $dir]);

    Character::where('slug', 'charlotte')->update(['profile_image' => 'old/charlotte.png']);

    $this->seed(StorySeeder::class);
    $this->seed(StorySeeder::class);

    $liam = Character::where('slug', 'liam')->first();
    expect($liam->profile_image)->toBe('characters/liam.webp')
        ->and($liam->action_image)->toBeNull();
    Storage::disk('public')->assertExists('characters/liam.webp');
    expect(Storage::disk('public')->get('banner/storytime.webp'))->toBe('banner-art');
    // Only the banners listed in config('story.banners') are published.
    Storage::disk('public')->assertMissing('banner/movienight.webp');
    expect(Storage::disk('public')->get('characters/liam.webp'))->toBe('liam-art');
    Storage::disk('public')->assertMissing('characters/charlotte.webp');
    expect(Storage::disk('public')->get('novel/cover.webp'))->toBe('cover-art')
        ->and(Novel::getSingleton()->cover_image)->toBe('novel/cover.webp');
});
