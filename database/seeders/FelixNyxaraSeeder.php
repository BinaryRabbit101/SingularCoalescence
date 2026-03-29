<?php

namespace Database\Seeders;

use App\Models\Character;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class FelixNyxaraSeeder extends Seeder
{
    public function run(): void
    {
        Character::updateOrCreate(
            ['slug' => 'felix'],
            [
                'name'        => 'Felix',
                'tagline'     => 'A farmhand who fixes hyperdrives with gut feelings and good luck.',
                'description' => '',
                'traits'      => [
                    'Deeply curious and endlessly optimistic',
                    'Fiercely loyal to those he trusts',
                    'Operates on intuition rather than calculation',
                    'Natural affinity for the "rhythm" of machines',
                    'Recklessly brave',
                    'Dreams far beyond his origins',
                ],
                'abilities'   => [
                    'The Knack — understands and repairs machines through emotional connection, not engineering knowledge',
                    'Probability Defiance — solves problems in ways that shouldn\'t mathematically work',
                    'Unpredictability — the only entity whose next action Charlotte cannot calculate',
                    'Resourceful improvisation — his Junk Bag of scraps becomes surprisingly effective tools',
                ],
                'cons'        => [
                    'Reckless curiosity — "pull the wire and see what happens" puts the team in immediate danger',
                    'Lack of formal knowledge — doesn\'t understand the physics behind what he does',
                    'Mortal fragility — a regular human easily injured in galactic-scale conflicts',
                    'Overconfidence in gut feelings — sometimes the gut is just wrong',
                ],
                'sort_order'  => 1,
                'published'   => true,
            ]
        );

        Character::updateOrCreate(
            ['slug' => 'nyxara'],
            [
                'name'        => 'Nyxara',
                'tagline'     => 'The last of the Sol-Vari. Hunter. Protector. Survivor.',
                'description' => '',
                'traits'      => [
                    'Peaceful at heart beneath a terrifying exterior',
                    'Incredibly stoic and quiet',
                    'Fiercely protective of her found family',
                    'Communicates more through body language than words',
                    'Deeply grounded instinctual wisdom',
                    'Adapting from captivity to belonging',
                ],
                'abilities'   => [
                    'Master Archer — unparalleled skill with a Kinetic Bow requiring immense strength to draw',
                    'Physical Prowess — overwhelming strength, speed, and retractable claws',
                    'Sensory Advantage — bioluminescent orange eyes for perfect night vision, sharp hearing, and tracking instincts',
                    'Instinctual Wisdom — understands the raw nature of the universe in ways calculation cannot replicate',
                ],
                'cons'        => [
                    'Deep psychological trauma from years of captivity and being treated as an exhibit',
                    'Reckless loyalty — will sacrifice herself for her pack without hesitation',
                    'Technological disadvantage — relies entirely on physical skill and instinct',
                    'Intense grief — the last of her entire species, a wound that never fully heals',
                    'Enclosed spaces trigger severe distress',
                ],
                'sort_order'  => 2,
                'published'   => true,
            ]
        );
    }
}
