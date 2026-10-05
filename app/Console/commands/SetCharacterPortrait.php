<?php

namespace App\Console\Commands;

use App\Models\Character;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class SetCharacterPortrait extends Command
{
    protected $signature = 'characters:set-portrait {character : The character slug} {filename : Filename relative to the character\'s portrait directory (e.g. portrait_2.png)}';

    protected $description = 'Set the active portrait for a character by pointing the DB to a previously generated portrait file.';

    public function handle(): int
    {
        $slug = $this->argument('character');
        $filename = $this->argument('filename');

        $character = Character::where('slug', $slug)->first();

        if (! $character) {
            $this->error("No character found with slug: {$slug}");

            return self::FAILURE;
        }

        $path = "characters/{$slug}/{$filename}";

        if (! Storage::disk('public')->exists($path)) {
            $this->error("File not found in public storage: {$path}");
            $this->line('Tip: run <info>characters:generate-portraits '.$slug.'</info> to generate a new portrait first.');

            return self::FAILURE;
        }

        $character->update(['profile_image' => $path]);

        $this->info("  ✓ {$character->name} — active portrait set to: {$path}");

        return self::SUCCESS;
    }
}
