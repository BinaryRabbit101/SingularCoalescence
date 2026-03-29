<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Character;
use App\Models\DiaryEntry;
use App\Models\MusicTrack;
use App\Models\Novel;
use App\Models\Product;
use App\Models\UniverseEntry;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'characters' => Character::count(),
                'diary_entries' => DiaryEntry::count(),
                'music_tracks' => MusicTrack::count(),
                'universe_entries' => UniverseEntry::count(),
                'products' => Product::count(),
                'novel' => Novel::exists(),
            ],
        ]);
    }
}
