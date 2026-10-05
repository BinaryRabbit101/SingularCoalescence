<?php

return [

    /*
    | Where StorySeeder looks for painted character portraits
    | (characters/<slug>.webp) and the home page banners (banner/<file>).
    */
    'art_path' => env('STORY_ART_PATH', resource_path('art')),

    /*
    | The home page banner carousel, in slide order. StorySeeder publishes each
    | file from art_path/banner/ to the public disk; the home page shows the ones
    | that exist. Every banner is painted or cropped to 21:9. With one banner
    | there are no dots and no auto-advance.
    |
    | Also painted and ready in resources/art/banner/, left out for now (owner,
    | 2026-10-05): movienight.webp, alt "Charlotte, Liam and Nyxara watching a
    | film late at night in their pajamas".
    */
    'banners' => [
        [
            'file' => 'storytime.webp',
            'alt' => 'The cast gathered around Liam as he reads them a story',
        ],
    ],

];
