<?php

namespace App\Console\Commands;

use App\Models\Character;
use App\Services\OpenRouterService;
use Illuminate\Console\Command;

class GeneratePortraits extends Command
{
    protected $signature = 'characters:generate-portraits {ids?* : Character IDs to process (omit for all)}';

    protected $description = 'Generate AI portrait images for characters';

    public function handle(OpenRouterService $openRouter): int
    {
        $ids = $this->argument('ids');

        $query = Character::orderBy('sort_order');
        if (! empty($ids)) {
            $query->whereIn('id', $ids);
        }

        $characters = $query->get();

        if ($characters->isEmpty()) {
            $this->error('No characters found.');

            return 1;
        }

        foreach ($characters as $character) {
            $this->info("Generating portrait for: {$character->name}");

            $traits = implode(', ', $character->traits ?? []);
            $abilities = implode(', ', $character->abilities ?? []);

            $prompt = $this->buildPrompt($character->name, $character->tagline ?? '', $traits, $abilities);

            $this->line('  Prompt: '.substr($prompt, 0, 120).'…');

            $path = $openRouter->generateAndSaveImage($prompt, 'characters', $character->slug, '3:4');

            if ($path) {
                $character->update(['profile_image' => $path]);
                $this->info("  ✓ Saved: {$path}");
            } else {
                $this->error("  ✗ Generation failed for {$character->name}");
            }

            sleep(2);
        }

        $this->info('Done.');

        return 0;
    }

    private function buildPrompt(string $name, string $tagline, string $traits, string $abilities): string
    {
        $prompts = [
            'Charlotte' => 'Cinematic portrait, head and shoulders. Two petite youthful women in their mid-twenties, pale skin, ordinary non-glowing natural green eyes with normal irises — one with long brilliant vibrant purple flowing hair, one with long brilliant vibrant orange flowing hair. Both wearing sleek fitted bodice dresses with a voluminous fluffy skirt that flares from the waist, modest round neckline just below the collarbone — one deep purple, one deep orange — with white elbow-length satin gloves. Natural neck clearly visible. Standing back to back, glancing over their shoulders toward camera with a slight smirk. Dramatic cinematic lighting, dark background. 3:4 format. Photorealistic. No text, no borders.',

            'Felix' => "Hyper-realistic portrait photo. A lean wiry young man in his late teens, messy light-brown hair, wide curious bright eyes, permanent grease and oil smudges on his face and hands. Wearing denim overalls over a plain shirt, pockets stuffed with scraps of metal, smith's goggles pushed up on his forehead. Warm amber side-lighting, dark background, upper body visible. 3:4 portrait format. Photographic realism. No text, no borders, no white edges.",

            'Nyxara' => "Hyper-realistic CGI render. A seven-foot-tall lean anthropomorphic black cat — not muscular, sleek lean body covered in dense realistic photorealistic jet-black fur with fine individual hair strands. Round broad domestic cat face, large prominent whiskers, small rounded ears, long thick black tail visible curling to one side. Large vivid orange bioluminescent eyes that glow and cast warm orange light into the dark. A wide unsettling smile showing rows of sharp white fangs. Wearing rugged green and brown leather archer's armor fitted over her frame. Dark environment lit by the warm orange glow of her eyes. Upper body portrait. 3:4 format. Photorealistic. No text, no borders, no white edges.",

            'Baasil' => 'Hyper-realistic portrait photo. A physically imposing large-framed older man, wearing expensive high-quality practical clothing adorned with layers of stolen priceless jewelry and gemstones. Cold calculating eyes, an imperious arrogant expression, holding a heavy gnarled fossilized wooden walking staff. Dimly lit opulent background. Upper body visible. 3:4 portrait format. Photographic realism. No text, no borders, no white edges.',

            'Clyde' => 'Hyper-realistic portrait photo. A deeply disturbing humanoid figure — a seamless fusion of exposed organic tissue and chrome metal plating like a flayed anatomy model encased in glass and steel. Sleek but horrifying, flesh and metal that should not coexist. Cold industrial lighting, faint ozone haze around him. Predatory silent presence, upper body visible. 3:4 portrait format. Photographic realism. No text, no borders, no white edges.',
        ];

        if (isset($prompts[$name])) {
            return $prompts[$name];
        }

        return "Hyper-realistic portrait photo of {$name} from the sci-fi story 'Singular Coalescence'. {$tagline}. Traits: {$traits}. Dark background, dramatic studio lighting, upper body visible. 3:4 portrait format. Photographic realism. No text, no borders, no white edges.";
    }
}
