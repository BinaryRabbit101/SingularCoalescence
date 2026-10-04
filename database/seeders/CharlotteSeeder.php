<?php

namespace Database\Seeders;

use App\Models\Character;
use App\Models\DiaryEntry;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CharlotteSeeder extends Seeder
{
    public function run(): void
    {
        $charlotte = Character::updateOrCreate(
            ['slug' => 'charlotte'],
            [
                'name' => 'Charlotte',
                'tagline' => 'One person. Two bodies. Very old, very bored, and very good at everything.',
                'description' => "Charlotte is one person living in two bodies at once. Not twins, not clones, not robots. Ask her which and she will tell you she is whatever she wants to be. The orange-haired body answers to Solaris and the purple-haired one to Eclipse, and both share every thought and every bruise the moment it happens.\n\nShe is small, scrawny-looking and dressed like a goth, moves like a dancer, and fights like a demolition crew. She has been out among the stars so long that they bore her, and she treats everything from a hull breach to a mob of bounty hunters as mildly entertaining.\n\nShe keeps a farm boy aboard her ship. She says it is because he is useful. She does not say for what.",
                'traits' => [
                    'One mind, two bodies',
                    'Bored by almost everything',
                    'Theatrical',
                    'Dry and sardonic',
                    'Never explains herself',
                    'Coins a new nickname every time',
                ],
                'abilities' => [
                    'Two bodies fighting as one, each the other\'s launch pad',
                    'Portal discs: throw one, step through its twin',
                    'Dress pockets that hold far more than they should',
                    'Strength and speed far beyond her frame',
                    'Pilot, welder, and whatever else she decides to be',
                ],
                'cons' => [
                    'Numbers: enough opponents at once can wear her down',
                    'What one body feels, the other feels',
                    'Curiosity that forgets other people can be hurt',
                    'Personal questions',
                ],
                'sort_order' => 0,
                'published' => true,
            ]
        );

        $entries = [
            [
                'entry_number' => 1,
                'title' => 'The Infinite Yawn',
                'entry_date' => '2157-03-01',
                'content' => "I was plummeting off the side of a forty-story corporate spire in Sector 4. The wind was whipping through my purple hair, and there were about three dozen pulse-laser burns singeing the edges of my dress. The Syndicate goon who pushed me over the edge was probably still standing up there, monologuing into the smog. I wouldn't know. I had stopped paying attention three minutes ago.\n\nSimultaneously, I was sitting in a fluorescent-lit diner on the absolute opposite side of the planet, stirring a spectacularly awful cup of synth-coffee. My orange hair kept dipping into the foam. The waitress there was named Martha. She had a tell when she lied about the coffee being fresh. She tapped her left thumb. It was a very predictable algorithm.\n\nIn fact, almost everything is predictable. I've lived a long time. Decades. I knew the exact terminal velocity of that fall. I knew exactly when I needed to deploy my deceleration thrusters to comfortably land in the alleyway below without spilling the synth-coffee in my other hand half a world away. I knew the Syndicate goon would file a report claiming I cried on the way down. I knew Martha would ask if I wanted a refill in precisely forty-two seconds.\n\nThe universe is just a very large, very boring math equation. Once you learn the variables—physics, corporate greed, human desperation—you can solve it in your sleep. And being awake in two places at once means you get bored twice as fast.\n\nI deployed my thrusters. I hit the alley floor in Sector 4 with a soft thud. I took a sip of Martha's terrible coffee.\n\nI was just about to calculate how many days until the sun burned out just to pass the time, when I saw *him*.\n\nA guy in a battered jacket, cornered at the end of the alley by three scavengers. A completely standard, mathematically solved scenario. He was outgunned. The logical survival path was to surrender his credits, take the beating, and walk away. I've seen it play out ten thousand times.\n\nInstead, he did something utterly absurd. He didn't run. He didn't fight. He pulled a half-eaten sandwich out of his pocket, maintained aggressive eye contact with the lead scavenger, and took a bite.\n\nIt was a completely emotionally driven, universally suicidal, hopelessly illogical choice. The scavengers were so confused they actually lowered their weapons.\n\nI paused with the synth-coffee halfway to my mouth. A chaotic variable. Organic irrationality.\n\nI smiled, in the alley, and in the diner.\n\nMartha asked if I wanted a refill. *No,* I thought, dusting the ash off my purple dress. *I think I've finally found something interesting.*",
            ],
            [
                'entry_number' => 2,
                'title' => 'The Anomaly',
                'entry_date' => '2157-03-02',
                'content' => "I spent the better part of the afternoon trailing him through the lower levels of the Sprawl. It wasn't difficult. I had Eclipse walking two blocks parallel on the upper gangways, keeping a bird's-eye view, while Solaris followed at ground level, blending into the market crowds.\n\nI wanted to see if the alleyway was a fluke. It wasn't.\n\nHe got himself cornered again—this time by a rusted-out security drone. I watched the scene unfold from a rooftop and from behind a fruit stand simultaneously. My mind ran through fourteen million combat simulations. He had twenty logical ways to evade the patrol. Fifteen involved stealth. Five involved violence. All of them were predictable.\n\nSo, what did he choose? Option twenty-one.\n\nHe took off his shoe and threw it directly into the drone's primary sensor array. It was absolute, pure stupidity. But it worked. The drone short-circuited trying to classify the scent profile of a dirty sneaker, and he just jogged away with one shoe.\n\nI laughed out loud in two places at once. He is a walking anomaly, an error in the code of the universe. I hadn't been this entertained in fifty years.",
            ],
            [
                'entry_number' => 3,
                'title' => 'The Garage Sleight of Hand',
                'entry_date' => '2157-03-05',
                'content' => "I decided to interfere today. Not out of any moral obligation, but purely out of curiosity.\n\nLiam had managed to attract the attention of a Dreadnaught-Class Syndicate Cruiser in Sector 7. A bit ambitious for a guy with one shoe, but he continues to surprise me. He was pinned down at the loading docks, and the cruiser was powering entirely too many weapons directly at him.\n\nEclipse was sitting on a railing overlooking the dock, eating a bag of synthetic popcorn. The show was getting good. But I wanted to see his reaction to something truly impossible.\n\nSo, I had Solaris step out from the shadows right in front of him. He yelled something at me about taking cover. I ignored him, reached into my left pocket, and pulled out The Garage. It looks just like a matte-black child's block.\n\nI held it up toward the cruiser. Liam watched in sheer terror as space folded in on itself. The entire massive hover-cruiser twisted like black ink being sucked down a drain, instantly vanishing into the tiny block in my hand.\n\nHe just stared at me, jaw unhinged. I winked, pocketed the cruiser next to my mints, and walked away into the fog. Eclipse finished her popcorn. It was a good day.",
            ],
            [
                'entry_number' => 4,
                'title' => 'First Contact',
                'entry_date' => '2157-03-08',
                'content' => "We finally had our first proper conversation. If you could call it that.\n\nHe was backed into another alley—predictable, really, given his talent for making enemies. This time, he was on foot and out of breath. I figured it was time to formally introduce myself and give him an exit strategy.\n\nI cornered him before the thugs did. As Eclipse, I tossed one of my teleportation discs right under his feet in the grime. At the exact same moment, Solaris placed the linked disc on the floor of a well-lit, entirely safe coffee shop three miles away across the district.\n\nHe fell through the alleyway disc right in the middle of a scream, and seamlessly popped out of the coffee shop floor on his hands and knees.\n\nIt took him a moment to realize what had happened. When he looked up, he saw me sitting at the table, sipping an awful matcha synth-brew.\n\n\"Who—what are you?!\" he panicked, scrambling backward. \"There were two of you in the alley!\"\n\n\"There's only one of me,\" Solaris said calmly.\n\n\"You just happen to be looking at both,\" Eclipse muttered out loud back in the alley, though he couldn't hear that part. I just smiled at him in the coffee shop and offered him a napkin. Human brains are so delightfully fragile when confronted with biological anomalies.",
            ],
            [
                'entry_number' => 5,
                'title' => 'The Pet Project',
                'entry_date' => '2157-03-12',
                'content' => "I've made up my mind. I'm keeping him.\n\nI've survived decades knowing exactly how everyone is going to act. The universe is a beautifully complex but ultimately solved equation. But him? I literally have no idea what he is going to do in the next five seconds.\n\nToday, he tried to barter his way out of an interrogation by offering the interrogator relationship advice. The audacity was staggering.\n\nFrankly, considering eternity is a very long time to be alive—let alone alive in two places at once—I needed the distraction.\n\nSo, Eclipse loaded heavy artillery into my hoverbike in Sector 4, while Solaris climbed onto another bike in Sector 9, armed with nothing but empty snack wrappers and a bored sigh. We revved our engines simultaneously.\n\nI officially adopted the role of 'chaotic protector.' Strictly as a pet project, of course. I wanted to see exactly how much trouble we could get into.",
            ],
        ];

        foreach ($entries as $entry) {
            DiaryEntry::updateOrCreate(
                [
                    'character_id' => $charlotte->id,
                    'entry_number' => $entry['entry_number'],
                ],
                [
                    'title' => $entry['title'],
                    'slug' => Str::slug($entry['title']),
                    'content' => $entry['content'],
                    'entry_date' => $entry['entry_date'],
                    'published' => true,
                ]
            );
        }
    }
}
