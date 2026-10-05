<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Character;
use App\Services\OpenRouterService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class CharacterController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Characters/Index', [
            'characters' => Character::orderBy('sort_order')->get(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Characters/Form', [
            'character' => null,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'traits' => 'nullable|array',
            'abilities' => 'nullable|array',
            'cons' => 'nullable|array',
            'sort_order' => 'nullable|integer',
            'published' => 'boolean',
            'profile_image' => 'nullable|image|max:5120',
            'action_image' => 'nullable|image|max:5120',
            'action_image_prompt' => 'nullable|string',
        ]);

        $data['slug'] = Str::slug($data['name']);

        if ($request->hasFile('profile_image')) {
            $data['profile_image'] = $request->file('profile_image')->store('characters', 'public');
        }

        if ($request->hasFile('action_image')) {
            $data['action_image'] = $request->file('action_image')->store('characters', 'public');
        }

        Character::create($data);

        return redirect()->route('admin.characters.index')->with('success', 'Character created.');
    }

    public function edit(Character $character): Response
    {
        return Inertia::render('Admin/Characters/Form', [
            'character' => $character,
        ]);
    }

    public function update(Request $request, Character $character): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'traits' => 'nullable|array',
            'abilities' => 'nullable|array',
            'cons' => 'nullable|array',
            'sort_order' => 'nullable|integer',
            'published' => 'boolean',
            'profile_image' => 'nullable|image|max:5120',
            'action_image' => 'nullable|image|max:5120',
            'action_image_prompt' => 'nullable|string',
        ]);

        if ($request->hasFile('profile_image')) {
            $data['profile_image'] = $request->file('profile_image')->store('characters', 'public');
        } else {
            unset($data['profile_image']);
        }

        if ($request->hasFile('action_image')) {
            $data['action_image'] = $request->file('action_image')->store('characters', 'public');
        } else {
            unset($data['action_image']);
        }

        $character->update($data);

        return redirect()->route('admin.characters.index')->with('success', 'Character updated.');
    }

    public function destroy(Character $character): RedirectResponse
    {
        $character->delete();

        return redirect()->route('admin.characters.index')->with('success', 'Character deleted.');
    }

    public function generateProfile(Character $character, OpenRouterService $openRouter): JsonResponse
    {
        $traits = implode(', ', $character->traits ?? []);
        $abilities = implode(', ', $character->abilities ?? []);
        $cons = implode(', ', $character->cons ?? []);

        $prompt = <<<PROMPT
Write a compelling 2-3 paragraph character profile for "{$character->name}" for the public website of a sci-fi story called "Singular Coalescence".

The profile should be atmospheric, intriguing, written in third-person prose. No headers, no bullet points, no markdown — just flowing text.

Character details:
- Tagline: {$character->tagline}
- Traits / Strengths: {$traits}
- Abilities: {$abilities}
- Weaknesses / Cons: {$cons}
PROMPT;

        $system = 'You are a creative writer for a sci-fi universe. Write vivid, immersive character profiles in third-person prose. No markdown formatting.';

        $result = $openRouter->chat($prompt, $system);

        if (! $result) {
            return response()->json(['error' => 'OpenRouter did not return a response.'], 502);
        }

        return response()->json(['description' => trim($result)]);
    }

    public function generateActionImage(Character $character, OpenRouterService $openRouter): JsonResponse
    {
        $prompt = $character->action_image_prompt;

        if (! $prompt) {
            $traits = implode(', ', $character->traits ?? []);
            $abilities = implode(', ', $character->abilities ?? []);
            $prompt = "Cinematic sci-fi action shot of a character named \"{$character->name}\" from the story \"Singular Coalescence\". {$character->tagline}. Abilities: {$abilities}. Traits: {$traits}. Dynamic full-body or upper-body pose, dramatic lighting, dark sci-fi aesthetic, high detail, square format. No text, no watermarks.";
        }

        $path = $openRouter->generateAndSaveImage($prompt, 'characters', 'action_'.$character->slug, '1:1');

        if (! $path) {
            return response()->json(['error' => 'Image generation failed.'], 502);
        }

        $character->update(['action_image' => $path]);

        return response()->json(['path' => $path]);
    }
}
