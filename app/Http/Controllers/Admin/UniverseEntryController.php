<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UniverseEntry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class UniverseEntryController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Universe/Index', [
            'entries' => UniverseEntry::orderBy('category')->orderBy('sort_order')->get(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Universe/Form', [
            'entry' => null,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'category' => 'required|in:faction,location,tech',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer',
            'published' => 'boolean',
            'image' => 'nullable|image|max:5120',
        ]);

        $data['slug'] = Str::slug($data['name']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('universe', 'public');
        }

        UniverseEntry::create($data);

        return redirect()->route('admin.universe.index')->with('success', 'Universe entry created.');
    }

    public function edit(UniverseEntry $universe): Response
    {
        return Inertia::render('Admin/Universe/Form', [
            'entry' => $universe,
        ]);
    }

    public function update(Request $request, UniverseEntry $universe): RedirectResponse
    {
        $data = $request->validate([
            'category' => 'required|in:faction,location,tech',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer',
            'published' => 'boolean',
            'image' => 'nullable|image|max:5120',
        ]);

        $data['slug'] = Str::slug($data['name']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('universe', 'public');
        } else {
            unset($data['image']);
        }

        $universe->update($data);

        return redirect()->route('admin.universe.index')->with('success', 'Universe entry updated.');
    }

    public function destroy(UniverseEntry $universe): RedirectResponse
    {
        $universe->delete();

        return redirect()->route('admin.universe.index')->with('success', 'Universe entry deleted.');
    }
}
