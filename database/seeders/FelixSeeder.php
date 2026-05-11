<?php

namespace Database\Seeders;

use App\Models\Character;
use Illuminate\Database\Seeder;

class FelixSeeder extends Seeder
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
                'sort_order'  => 2,
                'published'   => true,
            ]
        );
    }
}
