<?php

namespace Database\Seeders;

use App\Models\Character;
use Illuminate\Database\Seeder;

class BaasilSeeder extends Seeder
{
    public function run(): void
    {
        Character::updateOrCreate(
            ['slug' => 'baasil'],
            [
                'name' => 'Baasil',
                'tagline' => 'The Curator. He calls everything he keeps a friend.',
                'description' => "Baasil, known as the Curator, is a collector. For centuries he has gathered the rarest and most fleeting things in the galaxy aboard his flagship, the Menagerie: a museum of white marble and gold, drifting in the dark with cheerful music playing in its empty halls.\n\nHe is tall, horned and pale as porcelain, draped in white and gold with a fleece cape and a ring on every finger, and he rarely walks when he can float in on his throne. He is warm, polite and theatrical. He calls his exhibits \"my friends.\"\n\nHe believes he is preserving them. He has never come across anything quite like Charlotte.",
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
}
