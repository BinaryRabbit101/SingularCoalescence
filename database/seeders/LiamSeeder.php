<?php

namespace Database\Seeders;

use App\Models\Character;
use Illuminate\Database\Seeder;

class LiamSeeder extends Seeder
{
    public function run(): void
    {
        Character::updateOrCreate(
            ['slug' => 'liam'],
            [
                'name'        => 'Liam',
                'tagline'     => '',
                'description' => "Liam is a human native of the planet Cairn. He serves as the *variable* in Charlotte's otherwise predictable universe. Though ostensibly a simple farmhand, he possesses an innate, intuitive connection to machinery that defies conventional logic or formal education.\n\nRaised in the deeply traditional and superstitious village of Oakhaven, he spent his free time building overly complex contraptions to automate his chores — which usually failed spectacularly. When Charlotte arrived, he was the only one who saw the mechanical principles behind her seemingly magical technology. He left everything behind to join her crew and finally reach the stars he had spent his whole life staring at.",
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
                'sort_order'  => 3,
                'published'   => true,
            ]
        );
    }
}
