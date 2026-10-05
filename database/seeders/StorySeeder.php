<?php

namespace Database\Seeders;

use App\Models\Character;
use App\Models\Novel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

/**
 * The public story content: characters, universe entries and the novel page.
 * Safe to re-run on a live site (`php artisan db:seed --class=StorySeeder --force`);
 * it never touches users.
 */
class StorySeeder extends Seeder
{
    private const CHARACTER_SLUGS = ['charlotte', 'liam', 'nyxara', 'clyde', 'baasil'];

    public function run(): void
    {
        // Felix was replaced by Liam when the story was rewritten.
        Character::where('slug', 'felix')->delete();

        $this->call([
            CharlotteSeeder::class,
            LiamSeeder::class,
            NyxaraSeeder::class,
            ClydeSeeder::class,
            BaasilSeeder::class,
            UniverseSeeder::class,
            NovelSeeder::class,
        ]);

        $this->publishArt();
    }

    /**
     * Copy painted art from resources/art to the public disk. Anything that is
     * not in the repo yet is skipped, so existing images stay as they are.
     */
    private function publishArt(): void
    {
        $root = rtrim((string) config('story.art_path'), DIRECTORY_SEPARATOR.'/');
        $disk = Storage::disk('public');

        foreach (self::CHARACTER_SLUGS as $slug) {
            $source = "{$root}/characters/{$slug}.webp";

            if (! is_file($source)) {
                continue;
            }

            $path = "characters/{$slug}.webp";
            $disk->put($path, file_get_contents($source));

            Character::where('slug', $slug)->update([
                'profile_image' => $path,
                'action_image' => null,
            ]);
        }

        foreach (config('story.banners') as $banner) {
            $source = "{$root}/banner/{$banner['file']}";

            if (is_file($source)) {
                $disk->put("banner/{$banner['file']}", file_get_contents($source));
            }
        }

        $cover = "{$root}/novel/cover.webp";

        if (is_file($cover)) {
            $disk->put('novel/cover.webp', file_get_contents($cover));
            Novel::getSingleton()->update(['cover_image' => 'novel/cover.webp']);
        }
    }
}
