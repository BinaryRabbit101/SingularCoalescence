<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Character;
use App\Models\MusicTrack;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MusicTrackController extends Controller
{
    public function index(): Response
    {
        $tracks = MusicTrack::with('character:id,name,slug')
            ->orderBy('character_id')
            ->orderBy('sort_order')
            ->get();

        return Inertia::render('Admin/MusicTracks/Index', [
            'tracks' => $tracks,
            'characters' => Character::orderBy('sort_order')->get(['id', 'name']),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/MusicTracks/Form', [
            'track' => null,
            'characters' => Character::orderBy('sort_order')->get(['id', 'name']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'character_id' => 'required|exists:characters,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'audio_file' => 'required|file|mimes:mp3,wav,ogg,m4a|max:51200',
            'cover_image' => 'nullable|image|max:5120',
            'duration_seconds' => 'nullable|integer|min:1',
            'sort_order' => 'nullable|integer',
        ]);

        $data['file_path'] = $request->file('audio_file')->store('music', 'public');

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('music/covers', 'public');
        }

        unset($data['audio_file']);

        MusicTrack::create($data);

        return redirect()->route('admin.music-tracks.index')->with('success', 'Track uploaded.');
    }

    public function edit(MusicTrack $musicTrack): Response
    {
        return Inertia::render('Admin/MusicTracks/Form', [
            'track' => $musicTrack->load('character:id,name'),
            'characters' => Character::orderBy('sort_order')->get(['id', 'name']),
        ]);
    }

    public function update(Request $request, MusicTrack $musicTrack): RedirectResponse
    {
        $data = $request->validate([
            'character_id' => 'required|exists:characters,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'audio_file' => 'nullable|file|mimes:mp3,wav,ogg,m4a|max:51200',
            'cover_image' => 'nullable|image|max:5120',
            'duration_seconds' => 'nullable|integer|min:1',
            'sort_order' => 'nullable|integer',
        ]);

        if ($request->hasFile('audio_file')) {
            $data['file_path'] = $request->file('audio_file')->store('music', 'public');
        }
        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('music/covers', 'public');
        }

        unset($data['audio_file']);

        $musicTrack->update($data);

        return redirect()->route('admin.music-tracks.index')->with('success', 'Track updated.');
    }

    public function destroy(MusicTrack $musicTrack): RedirectResponse
    {
        $musicTrack->delete();

        return redirect()->route('admin.music-tracks.index')->with('success', 'Track deleted.');
    }

    public function reorder(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'integer|exists:music_tracks,id',
        ]);

        foreach ($validated['ids'] as $index => $id) {
            MusicTrack::where('id', $id)->update(['sort_order' => $index + 1]);
        }

        return redirect()->route('admin.music-tracks.index');
    }
}
