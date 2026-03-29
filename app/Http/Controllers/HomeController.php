<?php

namespace App\Http\Controllers;

use App\Models\Character;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(): Response
    {
        $characters = Character::where('published', true)
            ->orderBy('sort_order')
            ->get(['id', 'slug', 'name', 'tagline', 'profile_image', 'action_image']);

        return Inertia::render('Home/Index', [
            'characters' => $characters,
        ]);
    }
}
