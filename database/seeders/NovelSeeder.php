<?php

namespace Database\Seeders;

use App\Models\Novel;
use Illuminate\Database\Seeder;

class NovelSeeder extends Seeder
{
    public function run(): void
    {
        Novel::getSingleton()->update([
            'title' => 'Singular Coalescence',
            'tagline' => 'One girl in two places at once, and the farm boy she never saw coming.',
            'description' => "Liam is a farm boy from a snowbound village that fears the ancient machines sleeping on the plains. He has his missing father's wrench, a head full of stories, and a sky he can't stop staring at.\n\nThen a battered little ship falls out of that sky, and out step two identical strangers who insist they are one person. Her name is Charlotte. She is very old, very bored, and being hunted.\n\nSingular Coalescence is a science-fantasy adventure about a girl in two bodies, the boy she calls useful, and everything that is coming for them both.\n\nThe novel is being written now.",
            'status' => 'draft',
        ]);
    }
}
