<?php

namespace Database\Seeders;

use App\Models\Character;
use Illuminate\Database\Seeder;

class ClydeSeeder extends Seeder
{
    public function run(): void
    {
        Character::updateOrCreate(
            ['slug' => 'clyde'],
            [
                'name' => 'Clyde',
                'tagline' => 'Seven feet of flesh and machine. He does not stop.',
                'description' => "Clyde is something between a creature and a machine: armour plating fused into scarred skin, tubes pumping a dark red fluid, a single red visor where a face should be, and a heart you can see beating in his chest.\n\nYou hear him before you see him. Heavy footsteps, and a wet, mechanical rasp of breath that never changes rhythm. He does not use doors. He rarely speaks, and when he does it sounds like a status report: \"Record… archived.\" \"Anomaly… detected.\"\n\nHe is hunting Charlotte. She knows him by name. Neither of them has said why.",
                'traits' => [
                    'Silent',
                    'Relentless',
                    'Precise',
                    'Certain, not cruel',
                    'Patient',
                ],
                'abilities' => [
                    'Goes through walls, floors and hulls rather than around them',
                    'A red scanning grid that sweeps everything in front of him',
                    'Claws that tear through steel',
                    'Shutting him down only slows him',
                ],
                'cons' => [
                    'You can always hear him coming',
                    'Sees only his target; everyone else is an obstacle',
                    'Prefers close quarters',
                ],
                'sort_order' => 3,
                'published' => true,
            ]
        );
    }
}
