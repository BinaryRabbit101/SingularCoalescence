<?php

namespace App\Http\Controllers;

use App\Models\Character;
use Inertia\Inertia;
use Inertia\Response;

class CharacterController extends Controller
{
    public function index(): Response
    {
        $characters = Character::where('published', true)
            ->orderBy('sort_order')
            ->get(['id', 'slug', 'name', 'tagline', 'profile_image', 'action_image', 'body_name', 'body_hair_color']);

        return Inertia::render('Characters/Index', [
            'characters' => $characters,
        ]);
    }

    public function show(string $slug): Response
    {
        $character = Character::where('slug', $slug)
            ->where('published', true)
            ->with([
                'diaryEntries' => fn ($q) => $q->where('published', true),
                'musicTracks',
            ])
            ->firstOrFail();

        return Inertia::render('Characters/Show', [
            'character' => $character,
        ]);
    }
}
