<?php

namespace App\Http\Controllers;

use App\Models\Novel;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class NovelController extends Controller
{
    public function index(): Response
    {
        $novel = Novel::first();
        $disk = Storage::disk('public');

        if ($novel?->cover_image && $disk->exists($novel->cover_image)) {
            // The modified time busts Cloudflare's week-long cache when the cover is replaced (never saved).
            $novel->cover_image .= '?v='.$disk->lastModified($novel->cover_image);
        }

        return Inertia::render('Novel/Index', [
            'novel' => $novel,
        ]);
    }
}
