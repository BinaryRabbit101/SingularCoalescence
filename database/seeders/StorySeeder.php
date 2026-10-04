<?php

namespace Database\Seeders;

use App\Models\Character;
use Illuminate\Database\Seeder;

/**
 * The public story content: characters, universe entries and the novel page.
 * Safe to re-run on a live site (`php artisan db:seed --class=StorySeeder --force`);
 * it never touches users.
 */
class StorySeeder extends Seeder
{
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
    }
}
