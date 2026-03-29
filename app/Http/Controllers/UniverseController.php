<?php

namespace App\Http\Controllers;

use App\Models\UniverseEntry;
use Inertia\Inertia;
use Inertia\Response;

class UniverseController extends Controller
{
    public function index(): Response
    {
        $entries = UniverseEntry::where('published', true)
            ->orderBy('category')
            ->orderBy('sort_order')
            ->get(['id', 'slug', 'name', 'category', 'description', 'image']);

        return Inertia::render('Universe/Index', [
            'entries' => $entries,
        ]);
    }

    public function show(string $slug): Response
    {
        $entry = UniverseEntry::where('slug', $slug)
            ->where('published', true)
            ->firstOrFail();

        return Inertia::render('Universe/Show', [
            'entry' => $entry,
        ]);
    }
}
