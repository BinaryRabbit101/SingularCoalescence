<?php

namespace Database\Seeders;

use App\Models\Character;
use App\Models\DiaryEntry;
use App\Models\MusicTrack;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class BarnabasSeeder extends Seeder
{
    public function run(): void
    {
        $this->renameFromBaasil();

        Character::updateOrCreate(
            ['slug' => 'barnabas'],
            [
                'name' => 'Barnabas',
                'tagline' => 'The Curator. He calls everything he keeps a friend.',
                'description' => "Barnabas, known as the Curator, is a collector. For centuries he has gathered the rarest and most fleeting things in the galaxy aboard his flagship, the Menagerie: a museum of white marble and gold, drifting in the dark with cheerful music playing in its empty halls.\n\nHe is big, old and pompous: grey-skinned and goat-horned, with a woolly head of hair and a tuft of beard on his chin, draped in white and gold with a fleece cape and a ring on every finger. He is warm, polite and theatrical. He calls his exhibits \"my friends.\"\n\nHe believes he is preserving them. He has never come across anything quite like Charlotte.",
                'traits' => [
                    'Theatrical',
                    'Mockingly polite',
                    'Possessive',
                    'Patient',
                    'Certain he is the hero',
                    'Lonely',
                ],
                'abilities' => [
                    'Vast wealth and a flagship the size of a city',
                    'The Snare: his white-and-gold recovery force, armed with nets and sleep darts',
                    'An emerald scepter that stops whatever it strikes',
                    'All the time in the world',
                ],
                'cons' => [
                    'Wants his prizes undamaged, which gives them chances',
                    'Underestimates anything he thinks of as a specimen',
                    'Arrogance',
                ],
                'sort_order' => 4,
                'published' => true,
            ]
        );
    }

    /**
     * The author renamed Baasil to Barnabas (2026-11-07). Rename the live row in
     * place so its id, diary entries and music tracks survive. StorySeeder's
     * publishArt() then points profile_image at characters/barnabas.webp.
     */
    private function renameFromBaasil(): void
    {
        $old = Character::where('slug', 'baasil')->first();

        if ($old) {
            $new = Character::where('slug', 'barnabas')->first();

            if ($new) {
                // Both rows exist (a half-finished run): keep Barnabas, move anything
                // Baasil still owns onto it, then drop the old row.
                DiaryEntry::where('character_id', $old->id)->update(['character_id' => $new->id]);
                MusicTrack::where('character_id', $old->id)->update(['character_id' => $new->id]);
                $old->delete();
            } else {
                $old->update([
                    'slug' => 'barnabas',
                    'profile_image' => $old->profile_image === 'characters/baasil.webp' ? null : $old->profile_image,
                ]);
            }
        }

        Storage::disk('public')->delete('characters/baasil.webp');
    }
}
