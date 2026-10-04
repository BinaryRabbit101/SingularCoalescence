<?php

namespace App\Http\Controllers;

use App\Models\Character;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(): Response
    {
        $characters = Character::where('published', true)
            ->orderBy('sort_order')
            ->get(['id', 'slug', 'name', 'tagline', 'profile_image', 'action_image']);

        $banner = 'banner/storytime.webp';

        return Inertia::render('Home/Index', [
            'characters' => $characters,
            'banner' => Storage::disk('public')->exists($banner) ? $banner : null,
        ]);
    }
}
