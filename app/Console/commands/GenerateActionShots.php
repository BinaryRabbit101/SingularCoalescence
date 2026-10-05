<?php

namespace App\Console\Commands;

use App\Models\Character;
use App\Services\OpenRouterService;
use Illuminate\Console\Command;

class GenerateActionShots extends Command
{
    protected $signature = 'characters:generate-action-shots {ids?* : Character IDs to process (omit for all)}';

    protected $description = 'Generate AI action shot images for characters';

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
            $this->info("Generating action shot for: {$character->name}");

            $traits = implode(', ', $character->traits ?? []);
            $abilities = implode(', ', $character->abilities ?? []);

            $prompt = $character->action_image_prompt
                ?? $this->buildPrompt($character->name, $character->tagline ?? '', $traits, $abilities);

            $this->line('  Prompt: '.substr($prompt, 0, 120).'…');

            $path = $openRouter->generateAndSaveImage($prompt, 'characters', 'action_'.$character->slug, '1:1');

            if ($path) {
                $character->update(['action_image' => $path]);
                $this->info("  ✓ Saved: {$path}");
            } else {
                $this->error("  ✗ Generation failed for {$character->name}");
            }

            // Small pause between requests
            sleep(2);
        }

        $this->info('Done.');

        return 0;
    }

    private function buildPrompt(string $name, string $tagline, string $traits, string $abilities): string
    {
        $prompts = [
            'Charlotte' => 'Hyper-realistic cinematic photo. Two petite youthful women in their mid-twenties, 4 feet 5 inches tall, pale skin, natural non-glowing green eyes — one with vivid long purple hair wearing a sleek fitted dark purple dress with a voluminous fluffy skirt and a modest round neckline just below the collarbone, white satin gloves to the elbow, one with vivid long orange hair wearing a sleek fitted dark orange dress with a voluminous fluffy skirt and a modest round neckline just below the collarbone, white satin gloves to the elbow. Both wearing camouflage military combat boots clearly visible below their skirts. Mid-action together: one hurling a glowing blue teleportation disc, the other in a combat stance fist raised. Dark environment, dramatic violet and amber lighting. Square format. Photographic realism. No text, no borders, no white edges.',

            'Felix' => "Hyper-realistic cinematic photo. A lean wiry young man late teens, messy light-brown hair, grease-smeared face and hands, wearing denim overalls over a plain shirt with smith's goggles on forehead. Mid-action inside a dimly-lit starship cockpit: holding a wrench amid flying sparks, star field visible through the viewport. Warm amber lighting, dramatic shadows. Square format. Photographic realism. No text, no borders, no white edges.",

            'Nyxara' => "Hyper-realistic CGI render. A seven-foot-tall lean anthropomorphic black cat — not muscular, sleek lean frame covered in dense realistic photorealistic jet-black fur with fine individual strands. A long thick black tail trailing behind. Round broad domestic cat face, large prominent whiskers, small rounded ears. Large vivid orange bioluminescent eyes that glow and cast warm orange light into the surroundings. A wide unsettling smile showing rows of sharp white fangs. Wearing fitted green and brown leather archer's armor with straps and pouches. No cape. Crouched in a powerful hunting stance, bow fully drawn, arrow nocked. Towering over ancient dark forest trees barely reaching her waist. Pitch-black environment. Edge-to-edge composition. Square format. No human figures. No text, no borders.",

            'Baasil' => 'Hyper-realistic cinematic photo. A physically imposing large-framed older man wearing expensive practical clothing covered in stolen priceless jewelry and gemstones. Cold calculating eyes, imperious arrogant expression. Gripping a heavy gnarled fossilized wooden walking staff with both hands. Standing in an opulent dimly lit corridor lined with glass display cases. Gold and shadow lighting, menacing presence. Square format. Photographic realism. No text, no borders, no white edges.',

            'Clyde' => 'Hyper-realistic cinematic photo. A deeply disturbing humanoid figure — a seamless fusion of exposed organic tissue and chrome metal plating like a flayed anatomy model encased in glass and steel. Moving in a predatory mid-lunge, retractable claws extended, emerging from deep shadow with a faint ozone haze around him. Industrial horror, deep blacks and cold chrome highlights. Square format. Photographic realism. No text, no borders, no white edges.',
        ];

        if (isset($prompts[$name])) {
            return $prompts[$name];
        }

        // Generic fallback using character data
        return "Cinematic sci-fi action shot of {$name} from 'Singular Coalescence'. {$tagline}. Abilities: {$abilities}. Traits: {$traits}. Dynamic full-body or upper-body pose, dramatic lighting, dark sci-fi aesthetic, square format, high detail. No text, no watermarks.";
    }
}
