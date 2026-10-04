<?php

namespace Database\Seeders;

use App\Models\UniverseEntry;
use Illuminate\Database\Seeder;

class UniverseSeeder extends Seeder
{
    public function run(): void
    {
        $entries = [
            [
                'category' => 'location',
                'slug' => 'the-forbidden-plains',
                'name' => 'The Forbidden Plains',
                'description' => "Beyond Liam's village lies a stretch of bare ground where the snow will not settle. Ancient machines stand there, silent and half buried, older than anyone's stories about them.\n\nThe villagers call them metallic demons and keep well away. Liam's father did not.",
            ],
            [
                'category' => 'location',
                'slug' => 'the-scrap-yard',
                'name' => 'The Scrap-Yard',
                'description' => "A desolate rock turned into a station, with a hangar full of frigates above and a maze of grimy alleys below. It is where you go for parts, for information, and for trouble.\n\nNobody asks where your ship has been. Plenty of people ask what it is worth.",
            ],
            [
                'category' => 'location',
                'slug' => 'charlottes-ship',
                'name' => "Charlotte's Ship",
                'description' => "Small, crude and cramped, and she calls it \"my poor baby.\" The pilot seats look as if they were slapped into place and welded to the floor. The kitchen wall looks as if it was ripped out of somebody's house.\n\nIt has two rooms, stiff metal wings, a flat and very calm automated voice, and a blue-white flame when it flies. When it is hurt, it bleeds bright blue.",
            ],
            [
                'category' => 'location',
                'slug' => 'the-menagerie',
                'name' => 'The Menagerie',
                'description' => "Baasil's flagship: a museum the size of a city, built of white marble veined with gold, with cathedral ceilings and cheerful music playing to empty halls.\n\nIts galleries hold relics, moments frozen mid-happening, and living habitats behind one-way glass, where the exhibits do not know they are being watched.",
            ],
            [
                'category' => 'tech',
                'slug' => 'portal-discs',
                'name' => 'Portal Discs',
                'description' => "Small metal discs, somewhere between a frisbee and a dinner plate. Thrown or slapped onto a surface, one sticks with a heavy thud and opens into a swirl of light and dark blue, like food dye being stirred through a mixing bowl.\n\nWhatever goes into one comes out of its twin. Charlotte uses them as doors, escape hatches and, when she is bored, a game of catch.",
            ],
            [
                'category' => 'tech',
                'slug' => 'hyperdrive-core',
                'name' => 'Hyperdrive Core',
                'description' => "Charlotte's explanation: \"Take one of the suns from your world and cram it into a can about the size of a hay bale.\"\n\nIt is, near enough, infinite power. She wants one very badly, and it is the only thing she talks about without sounding bored.",
            ],
            [
                'category' => 'tech',
                'slug' => 'stasis',
                'name' => 'Stasis',
                'description' => "A lime-green glow that closes around a target and holds it mid-motion, like a statue set in gel. Nothing inside ages or moves, and nothing outside can touch it.\n\nIt is how the Curator keeps a collection that never changes.",
            ],
            [
                'category' => 'faction',
                'slug' => 'the-snare',
                'name' => 'The Snare',
                'description' => "The Curator's recovery force: identical figures armoured head to foot in white plate with gold seams, their faces hidden behind mirrored visors. They stand like statues until they are told to move.\n\nThey carry nets and sleep darts, never anything that would damage a prize. Their whole job is to close around you.",
            ],
            [
                'category' => 'faction',
                'slug' => 'the-sol-vari',
                'name' => 'The Sol-Vari',
                'description' => "A people of tall, black-furred feline warriors from the twilight forests of Noctara. Words carried weight among them, so they used few.\n\nNyxara is the last of them.",
            ],
        ];

        foreach ($entries as $order => $entry) {
            UniverseEntry::updateOrCreate(
                ['slug' => $entry['slug']],
                [...$entry, 'sort_order' => $order, 'published' => true]
            );
        }
    }
}
