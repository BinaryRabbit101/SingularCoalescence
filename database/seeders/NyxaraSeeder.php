<?php

namespace Database\Seeders;

use App\Models\Character;
use App\Models\DiaryEntry;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class NyxaraSeeder extends Seeder
{
    public function run(): void
    {
        $nyxara = Character::updateOrCreate(
            ['slug' => 'nyxara'],
            [
                'name'        => 'Nyxara',
                'tagline'     => 'The last of the Sol-Vari. Hunter. Protector. The thing the darkness was built for.',
                'description' => "Nyxara is the final surviving member of the Sol-Vari — a race of powerful, feline humanoids. Standing seven feet tall and covered in jet-black fur, she is an apex predator built for the dark. Her bioluminescent orange eyes give her perfect vision in total blackness. Her claws retract. Her hearing can isolate a single pulse at thirty paces.\n\nFor years she was kept behind one-way glass in a private collection, treated as an exhibit rather than a person. When Charlotte and Liam broke her out, she nearly killed Liam before his quiet, stubborn kindness convinced her otherwise. Now she guards the crew with a ferocity that has no off switch — not because she was told to, but because they are the first pack she has had since her people were taken from her.\n\nShe speaks rarely. When she does, the words are chosen like arrows — one at a time, aimed precisely, never wasted.",
                'traits'      => [
                    'Last surviving Sol-Vari',
                    'Deeply stoic and observational',
                    'Communicates primarily through body language',
                    'Fiercely loyal to her found pack',
                    'Carries the grief of an extinct people without showing it',
                    'Peaceful at rest — terrifying when threatened',
                ],
                'abilities'   => [
                    'Bioluminescent orange eyes — perfect vision in total darkness',
                    'Master archer — unmatched skill with her Kinetic Bow',
                    'Retractable claws and fangs for close-quarters combat',
                    'Exceptional tracking — identifies targets by scent, sound, and displaced air',
                    'Physical strength and speed far beyond human limits',
                    'Instinctual battlefield awareness that no technology can replicate',
                ],
                'cons'        => [
                    'Captivity trauma — enclosed spaces trigger deep psychological distress',
                    'Reckless loyalty — will enter lethal danger to protect her pack without hesitation',
                    'Technological disadvantage — entirely unfamiliar with complex machinery',
                    'Grief — the weight of being the last of her kind surfaces at unexpected moments',
                    'Verbal communication barrier — her minimal speech is frequently misread as hostility',
                ],
                'sort_order'  => 1,
                'published'   => true,
            ]
        );

        $entries = [
            [
                'entry_number' => 1,
                'title'        => 'The Canopy',
                'entry_date'   => null,
                'content'      => "The mist comes up from the valley floor before the light does. I have watched it a thousand mornings — the way it crosses the lower canopy first, settles on the bark, makes the world below invisible for an hour. I never go below during the mist. There is nothing down there that needs me, and nothing up here that doesn't.\n\nThe upper branches hold my weight without complaint. I have slept in this fork of the great cedar since before I could name it a cedar. My claws have worn grooves into the bark. The wood knows my shape the way a riverbed knows the water that carved it — patiently, permanently. I am not a visitor here. I am part of the grain.\n\nI eat. I move. I track by scent when I want to and by sound when the wind shifts. I know which roots go deep and which are shallow. I know where the stream widens and goes cold, and where it narrows and runs fast over iron-colored stone. I have never needed anything beyond what sits inside the boundaries I set for myself.\n\nThe valley smoke rises around midday. Cook fires, mostly. Sometimes forge smoke — darker, heavier, carrying iron and old skin. I watch it from the high branch with both nostrils wide and feel the familiar pull that I always press back down. Not curiosity. Something simpler. Pattern recognition. The smoke means they are still there, below the mist line, moving through their small, enclosed lives. It means nothing has changed.\n\nThe wind comes from the west and the cedar tilts its crown just slightly, like an animal leaning into a familiar hand. I lean with it.\n\nI do not think about what is down there. Not yet.",
            ],
            [
                'entry_number' => 2,
                'title'        => 'Cobblestone and Shadow',
                'entry_date'   => null,
                'content'      => "The smell reaches me long before the lights do. Smoke from a dozen different sources, none of them clean — tallow fat, burning damp wood, something animal rendering in a pot somewhere to the east. Beneath it, bread. Beneath that, iron rust, sweat, rotten vegetable matter, and the particular sour smell of too many bodies enclosed behind stone for too long. My ears fold back flat the instant I cross the tree line. My body knows before my mind decides: this is a place built for small creatures with no useful senses.\n\nI kept to the rooftops where I could. Where the thatch was not thick enough to hold my weight I moved through the narrow lanes, pressing my back into walls that still held the day's residual warmth against my fur. They had torches burning in iron brackets every twenty paces or so — not enough to truly illuminate anything, enough to completely ruin whatever low-light capability they thought they had. I watched three separate pairs of guards walk past me within arm's reach and notice nothing. They were looking at the torchlight rather than the dark between it. I felt something close to pity, but not quite.\n\nAt the center of the settlement there was a wide square, mostly empty at that hour. A window across the square glowed orange through warped glass, and inside it a woman was doing something at a table — mending, or sorting grain, I could not tell from the angle. A child was pressed against her side, half asleep. She stroked its head without interrupting what her hands were doing. A completely automatic gesture. I crouched on the cold cobblestone for longer than was necessary, watching.\n\nI do not know what I expected to find down here. Something that explained why the smoke kept rising every day, why the noise never fully stopped, why they crowded themselves into stone boxes when the world above the treeline was so vast and clean. I did not find an answer. I found only the smell of tallow and old bread and the sight of a woman smoothing down a child's hair in the orange dark.\n\nI went back up before dawn. The mist was already rising.",
            ],
            [
                'entry_number' => 3,
                'title'        => 'The Outcast\'s Coin',
                'entry_date'   => null,
                'content'      => "The hall smells like melted wax, old stone, and something beneath both of those things that I have no name for. A kind of rot that is slow and expensive, the smell of a place that has been sealed against fresh air for so long that the air inside has simply given up and turned. There are candles in every corner, more wax every night, and still the smell underneath does not change. I have decided the smell is them. Generations of them, sealed in by their own walls.\n\nThey call for me with a long brass horn. Two short notes. I heard it carry through the forest on the morning I first came to them, and I thought it was a distress call. It was. Just not the kind I expected. I am the response to the horn. When the gate has been breached, when something comes through the tree line that their guards cannot handle, the horn sounds and I arrive. Then the horn stops and I am returned to the arrangement, which is: I hunt, I protect, I receive the coin, and I remain far from the hall until the next occasion.\n\nThey look at me from the edges of their eyes. Never directly. I have noticed that looking directly requires a kind of acknowledgment that they are not capable of making. The steward hands me the coin by setting it flat on a stone ledge and stepping back. The coin sits on the ledge. I carry it away. His hand has never touched mine.\n\nI do not pretend this wounds me. I understand the terms. They need the teeth and they cannot bring themselves to respect what the teeth belongs to. I provide what they cannot and they provide what I have decided I require for the season. It is a clean arrangement, stripped of the pretense that they make among themselves when they think they are being watched favorably.\n\nThe weight of the coin is the same every time. I have come to find that predictable, in the same way I find the horn predictable, and the ledge, and the sideways eyes. Predictable is not comfort. But it is something I can navigate without error, and for now that is sufficient.",
            ],
            [
                'entry_number' => 4,
                'title'        => 'Night\'s Edge',
                'entry_date'   => null,
                'content'      => "The last torch went out an hour before I needed it to.\n\nI had been kneeling at the edge of the tree line, taking inventory by scent: three separate pulses at twelve, seven, and two — close enough that I could hear their breathing if I slowed mine. Two of them were afraid. The third was not afraid enough, which is worse. There was iron on all of them, fresh sharpening oil on at least one blade, and beneath it the sweat that comes from cold, not effort. They had been waiting in the dark for a while. They knew this was the territory. They did not know I was already inside the perimeter.\n\nWhen the torchlight ends, everything they believe about safety inverts. The dark is not the absence of vision. The dark is the condition under which I become precise. My eyes open wide and the world resolves — not lit, exactly, but legible. Heat, shape, the particular texture of shadow that means a human body is behind it. The fern I pressed my palm to gave me the moisture-reading and the displacement angle. The mud at the stream crossing gave me direction of travel and time — less than an hour since the heaviest one had passed through.\n\nI moved in from behind the third. The one who was not afraid enough. He heard nothing until I was already there, and by then the moment had resolved without violence. I do not prefer violence. I prefer the encounter to end at the exact point when the prey understands that it has already lost, which is a different and more permanent education.\n\nI crouched in the empty dark afterward and pulled a slow breath through both nostrils. Pine resin. Cold stone. The faint iron smell of the stream running over ore deposits a quarter mile north. No threat remaining. My pulse had not changed throughout.\n\nThere is a version of myself that exists only here, in the total absence of torchlight, where every variable belongs to me. I do not know what to call it. It does not need a name. It is simply the most accurate version of what I am.",
            ],
            [
                'entry_number' => 5,
                'title'        => 'Unbound',
                'entry_date'   => null,
                'content'      => "I set the coin on the chair before I left. Not on the ledge — on the chair, where a hand would have to pick it up. I do not know why. It felt like the correct punctuation.\n\nThe collar I left in the dirt outside the east gate. Unbuckled, not broken. I could have broken it, but that would have implied I needed to. I simply removed it and set it down in the mud and walked through the gate before the sun had fully risen. No one was watching. The guards do not watch the outbound passage. There is nothing they have ever valued enough to worry about its leaving.\n\nThe first breath through the tree line was pine resin and cold ground-water and the particular mineral smell of old moss on north-facing stone. I stopped walking and simply breathed it. My ears came forward for the first time in months. There was a woodpecker somewhere in the middle canopy and the far sound of the stream running high with snowmelt, and beneath all of it the sustained, foundational silence of the deep forest that is not actually silence at all but the sound of everything alive and in its correct place.\n\nI did not look back. Not from anger — there was no anger by that morning, only a decision that had finished being made. Anger would have meant the place still had a hold. It did not. The castle and its wax smell and its sideways eyes and its two-note horn had simply become a season, the way a dry summer is a season. It ends. You do not grieve the end of a dry summer.\n\nThe cedar I came back to had not changed. The grooves my claws had worn into the bark were still there. I climbed to the high fork and sat with my back against the trunk and felt the wood take my weight the way it always had — without adjustment, without accommodation, as if I had never left at all.\n\nThe valley smoke was rising. I watched it reach the canopy and thin and disappear into the open sky.\n\nThere is no one I answer to. There is no horn. The distance between myself and everything I do not want is exactly as wide as I am willing to walk, and I have learned that I am willing to walk very far.",
            ],
        ];

        foreach ($entries as $entry) {
            DiaryEntry::updateOrCreate(
                [
                    'character_id' => $nyxara->id,
                    'entry_number' => $entry['entry_number'],
                ],
                [
                    'title'      => $entry['title'],
                    'slug'       => Str::slug($entry['title']),
                    'content'    => $entry['content'],
                    'entry_date' => $entry['entry_date'],
                    'published'  => true,
                ]
            );
        }
    }
}
