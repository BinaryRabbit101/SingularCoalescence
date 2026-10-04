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
                'title' => 'Hundreds of Light Years',
                'entry_date' => null,
                'content' => "There's a smudge on my front window exactly the shape of a face. It belongs to my passenger. He presses himself to the glass like the stars will leave if he blinks.\n\nThey won't leave. That's the whole trouble with stars.\n\nI've looked at them from both seats at once. From the floor. From the edge of the counter. Kneeling backwards on the co-pilot seat with my arms hung over the top. They don't improve with the angle. They're very far away, they're very bright, and they do absolutely nothing. A big void of boring, salted with lights.\n\nHe tells me they're beautiful. I tell him they're hundreds of light years off. He hears that and somehow likes them more. There's no arguing with a person like that, so I've stopped.\n\nOne admission, here and nowhere else. I know the look on his face. I just don't have it anymore. It went the way of the label on my welding torch. Torn off somewhere along the line. Not missed.\n\nThat's fine. Not everyone needs to be impressed by everything. Somebody has to fly the ship.\n\nNose print is back at the glass as I write this, breathing on my window. I've decided not to clean it. Let it be the most interesting thing out there.",
            ],
            [
                'entry_number' => 2,
                'title' => 'My Poor Baby',
                'entry_date' => null,
                'content' => "My ship is small. I'm aware. Every shiny frigate parked beside it makes sure I'm aware.\n\nThe captain's chair and the co-pilot's chair don't match the floor. They were slapped down and welded in place, crudely, and they will never move again, which is exactly how I like a chair. The floor is grooved and cold. Sprout looked at it like it had insulted him personally. The floor has not changed.\n\nOne whole wall is a kitchen. Counter, little sink bowl, cabinets underneath. It looks like it wandered out of somebody's house and decided to stay. I've never once wanted to change it.\n\nThere's a steel door that opens by spiraling into its own middle. It's the most dramatic thing a door has ever done. I approve.\n\nThe deck hums, all the time. You stop hearing it after a while. I stopped a long time ago.\n\nAnd then there's the voice. The ship speaks in the flattest, calmest tone in the galaxy, about absolutely everything. It would announce the end of the universe the way other people mention the weather. I've never heard it panic. I respect that more than I respect most people.\n\nFour canisters on the back. Two stiff wings. A blue-white flame when it really goes. Ugly, cramped, loud, mine.\n\nMy poor baby.",
            ],
            [
                'entry_number' => 3,
                'title' => 'Peaches on a Shard',
                'entry_date' => null,
                'content' => "Canned peaches taste best off a twisted metal shard. This is not up for debate.\n\nI open the can with a little crowbar, sit on the edge of the counter, and let my boots swing. One slice at a time, speared on the point. A fork would be faster. A fork would also be boring.\n\nMeanwhile, on the floor, cross-legged, I'm working through a bag of chips. The residue ends up on my gloves. Then it ends up in my mouth, one finger at a time. The gloves stay white. I won't be explaining how.\n\nSweet in one mouth. Salty in the other. Two snacks at once is the single best thing about being me, and I won't take notes on it from anyone who only has the one mouth.\n\nI offered my passenger a slice once, held out on the point like a little kabob. He looked queasy and said no thanks. Turnip turned down a perfectly good peach. I've decided to take that personally.\n\nThere are more cans in the back of the cabinet, stacked and dusty, waiting their turn. I don't rush them. Nothing on this ship gets rushed except the ship.\n\nOpen up, I say, to nobody. Then I eat it myself.",
            ],
            [
                'entry_number' => 4,
                'title' => 'Catch',
                'entry_date' => null,
                'content' => "Best game on the ship. I sit on the floor facing myself, legs crossed, dress pooled around me, and toss a little metal disc back and forth. Solaris throws. Eclipse catches. Eclipse throws. Solaris catches.\n\nI know where every throw will land before it leaves my hand. Both hands. It's still fun. That isn't up for discussion either.\n\nNow and then one sails wide, smacks into the wall with a heavy thud and sticks there, like a magnet finding steel. I leave it. Anything that sticks to my walls has earned the right to stay.\n\nPocket lint interrupted a game once. I gave him one word. Speak. It was plenty.\n\nOther things I do two at a time. With one body I comb my hair with my gloved fingers. With the other I fuss with my bracelet. A little later I swap, without a word. The purple gets combed, the other bracelet gets fussed. Nobody needs to say anything. It's my hair.\n\nAnd when something goes well, I high-five myself. Up top. Down low. Finish with a bump. It is the correct way to celebrate, and I'm not accepting feedback.\n\nIf anyone ever catches me at it, I was dusting off my dress.",
            ],
            [
                'entry_number' => 5,
                'title' => 'Names',
                'entry_date' => null,
                'content' => "I hate being called by nicknames. Put that on the record.\n\nWhen he wanted two names for me, I gave him two. Solaris for the orange. Eclipse for the purple. I held up strands of hair so he could keep up. He took the names and called me Charlottes anyway. Plural. Like a man counting sheep. There is one of me. I'll say it slowly as many times as it takes.\n\nHe, on the other hand, gets a new name every time I open my mouth. That isn't the same thing. That's a courtesy. A name used twice has stopped trying.\n\nTonight he's cupboard tenant. He sleeps folded up in the cabinet under the sink, beside the dusty cans, arm for a pillow, wrench within reach. I told him he was weird. He is weird.\n\nThere's a boy aboard my ship. I find him useful. That's all anyone gets.\n\nSomething relentless follows me, too. It never gets tired. I never get caught. Between the two of them, the stars finally have some competition.\n\nThe deck hums. The stars do nothing. The boy is asleep in my cabinet. Lights down.",
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
