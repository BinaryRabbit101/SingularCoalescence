<?php

return [

    /*
    | Where StorySeeder looks for painted character portraits
    | (characters/<slug>.webp) and the home page banner (banner/storytime.webp).
    */
    'art_path' => env('STORY_ART_PATH', resource_path('art')),

];
