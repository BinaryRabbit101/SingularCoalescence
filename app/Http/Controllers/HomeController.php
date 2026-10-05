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

        $disk = Storage::disk('public');

        $banners = collect(config('story.banners'))
            ->filter(fn (array $banner) => $disk->exists("banner/{$banner['file']}"))
            ->map(fn (array $banner) => [
                // The modified time busts Cloudflare's week-long cache when the art is replaced.
                'src' => "banner/{$banner['file']}?v=".$disk->lastModified("banner/{$banner['file']}"),
                'alt' => $banner['alt'],
            ])
            ->values();

        return Inertia::render('Home/Index', [
            'characters' => $characters,
            'banners' => $banners,
        ]);
    }
}
