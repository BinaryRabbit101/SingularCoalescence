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
                'name' => 'Liam',
                'tagline' => 'A farm boy with his father\'s wrench and a sky he can\'t stop staring at.',
                'description' => "Liam grew up in a snowbound village that fears the machines sleeping out on the forbidden plains. His father refused to fear them, walked out among them one night, and never came back. What he left behind was a wrench built for giants.\n\nLiam carries it strapped to his back, talks to objects when nobody is listening, and is the only person in this story who still thinks the stars are beautiful. He is not a fighter. He is a farmer, a good pair of hands, and a long way out of his depth.\n\nHe is also the one thing Charlotte did not see coming.",
                'traits' => [
                    'Wonder he refuses to be embarrassed by',
                    'Talks to machines, buckets and well levers',
                    'Apologises to everyone, and to everything',
                    'Notices more than he lets on',
                    'Afraid, and keeps moving anyway',
                    'Quietly stubborn',
                ],
                'abilities' => [
                    'Fixes things by gut, not calculation, and is as surprised as anyone when it works',
                    'His father\'s wrench: two-handed, polished to a mirror, never out of reach',
                    'A satchel of odds and ends',
                    'A steady pair of hands at the controls',
                    'Reads people the way he reads machines',
                ],
                'cons' => [
                    'Not a fighter',
                    'Comes from a world of wells and spinning wheels',
                    'Panics the moment the wrench leaves his back',
                    'Trusts too easily',
                ],
                'sort_order' => 1,
                'published' => true,
            ]
        );
    }
}
