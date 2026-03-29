<?php

namespace App\Http\Controllers;

use App\Models\Novel;
use Inertia\Inertia;
use Inertia\Response;

class NovelController extends Controller
{
    public function index(): Response
    {
        $novel = Novel::first();

        return Inertia::render('Novel/Index', [
            'novel' => $novel,
        ]);
    }
}
