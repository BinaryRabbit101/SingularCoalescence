<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Character;
use App\Models\DiaryEntry;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class DiaryEntryController extends Controller
{
    public function index(): Response
    {
        $entries = DiaryEntry::with('character:id,name,slug')
            ->orderBy('character_id')
            ->orderBy('entry_number')
            ->get();

        return Inertia::render('Admin/DiaryEntries/Index', [
            'entries' => $entries,
            'characters' => Character::orderBy('sort_order')->get(['id', 'name']),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/DiaryEntries/Form', [
            'entry' => null,
            'characters' => Character::orderBy('sort_order')->get(['id', 'name']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'character_id' => 'required|exists:characters,id',
            'entry_number' => 'required|integer|min:1',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'entry_date' => 'nullable|date',
            'published' => 'boolean',
        ]);

        $data['slug'] = Str::slug($data['title']);

        DiaryEntry::create($data);

        return redirect()->route('admin.diary-entries.index')->with('success', 'Diary entry created.');
    }

    public function edit(DiaryEntry $diaryEntry): Response
    {
        return Inertia::render('Admin/DiaryEntries/Form', [
            'entry' => $diaryEntry->load('character:id,name'),
            'characters' => Character::orderBy('sort_order')->get(['id', 'name']),
        ]);
    }

    public function update(Request $request, DiaryEntry $diaryEntry): RedirectResponse
    {
        $data = $request->validate([
            'character_id' => 'required|exists:characters,id',
            'entry_number' => 'required|integer|min:1',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'entry_date' => 'nullable|date',
            'published' => 'boolean',
        ]);

        $data['slug'] = Str::slug($data['title']);

        $diaryEntry->update($data);

        return redirect()->route('admin.diary-entries.index')->with('success', 'Diary entry updated.');
    }

    public function destroy(DiaryEntry $diaryEntry): RedirectResponse
    {
        $diaryEntry->delete();

        return redirect()->route('admin.diary-entries.index')->with('success', 'Diary entry deleted.');
    }

    public function reorder(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'integer|exists:diary_entries,id',
        ]);

        foreach ($validated['ids'] as $index => $id) {
            DiaryEntry::where('id', $id)->update(['entry_number' => $index + 1]);
        }

        return redirect()->route('admin.diary-entries.index');
    }
}
