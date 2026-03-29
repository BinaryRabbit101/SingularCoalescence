<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Novel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class NovelController extends Controller
{
    public function edit(): Response
    {
        return Inertia::render('Admin/Novel/Edit', [
            'novel' => Novel::getSingleton(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:draft,published',
            'cover_image' => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('novel', 'public');
        } else {
            unset($data['cover_image']);
        }

        Novel::getSingleton()->update($data);

        return redirect()->route('admin.novel.edit')->with('success', 'Novel updated.');
    }
}
