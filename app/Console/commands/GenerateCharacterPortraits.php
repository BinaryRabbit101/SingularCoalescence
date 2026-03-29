<?php

namespace App\Console\Commands;

use App\Models\Character;
use App\Services\OpenRouterService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class GenerateCharacterPortraits extends Command
{
    protected $signature = 'characters:generate-portraits {character? : The character slug (omit for all characters)}';

    protected $description = 'Generate AI portrait(s) for characters using their physical descriptions. New portraits are stored with an incrementing index alongside any previously generated portraits.';

    /**
     * Pre-crafted realistic concept art prompts, keyed by character slug.
     */
    private array $prompts = [
        'bazil' => 'Cinematic movie poster portrait. An imposing, powerfully built man, mid-40s, standing in a dominant commanding pose — arms crossed, chin raised, radiating ruthless authority. Dressed in expensive practical clothing layered with an extraordinary quantity of stolen priceless jewelry — rings on every finger, multiple thick necklaces, antique brooches and pendants pinned across his chest. Corrupt corporate magnate energy. Dramatic low-angle cinematic lighting. Neutral dark background. FRAMING: shot from the chest up, the top of the head must be in the CENTER of the image with at least 25% empty space above it. Detailed painterly realism.',

        'charlotte' => 'Cinematic movie poster portrait. Two identical petite women — average height, 5\'4" — in their late twenties with adult figures, standing back-to-back in a powerful dramatic pose, chins raised, radiating cool confidence. One has long flowing vivid purple hair (Eclipse), the other has long flowing vivid orange hair (Solaris). Both have vivid striking green eyes. Both wear elegant fluffy Victorian-style ball gowns with high necklines covering the shoulders and a modest amount of decorative ruffle — one deep violet, one warm amber. Both wear pristine white satin opera gloves to the elbow. Epic cinematic lighting, deep shadows, atmospheric dark background. FRAMING: shot from the waist up, tops of both heads in the CENTER of the image with at least 25% empty space above. Ultra-detailed painterly realism.',

        'clyde' => 'Cinematic movie poster portrait. A deeply unsettling humanoid figure that is part organic, part machine — dark organic plating fused over a chrome chassis, resembling a living flayed anatomy model preserved inside translucent glass and polished steel casing. Standing in a powerful slightly menacing pose — head tilted, limbs slightly spread. Internal components faintly visible through chassis. Faint blue bioluminescent glow from within. Cold antiseptic clinical lighting, stark dark background. FRAMING: shot from the chest up, the top of the head must be in the CENTER of the image with at least 25% empty space above it. Detailed painterly realism.',

        'felix' => 'Cinematic movie poster portrait. A clean-cut young man in his mid-twenties, lean and wiry, standing in a confident hero pose — slight smirk, eyes full of sharp clever energy. No facial hair whatsoever, completely clean-shaven. Neatly kept short hair. Wearing a worn linen farmer\'s shirt tucked into classic denim jeans with wide dungaree shoulder straps crossing over the chest. Clean sturdy farm boots. Warm golden field sunlight from behind creates a dramatic rim glow. FRAMING: shot from the waist up, the top of the head must be in the CENTER of the image with at least 25% empty space above it. Detailed painterly realism.',

        'nyxara' => 'Cinematic movie poster portrait. A towering anthropomorphic cat warrior — 7 feet tall, powerfully built, clearly feline not human. Entire body covered in long thick fluffy jet-black fur. Face unmistakably a cat\'s face: large exposed pointed ears (no hood), a broad feline nose, prominent whiskers, wide muzzle with visible fangs. Eyes glow with intense vivid bioluminescent orange-amber light. Standing in a powerful archer\'s ready pose — bow drawn back, one eye narrowed, radiating apex predator energy. Long thick fluffy black tail curling behind her. Wearing rugged forest-ranger leather armor in deep forest greens and earthy browns — straps and bracers, NO hood. Dramatic moonlit ancient forest atmosphere with god-rays and rim lighting. FRAMING: shot from the waist up, the top of the head and pointed ears must be in the CENTER of the image with at least 25% empty space above. Ultra-detailed painterly realism.',
    ];

    public function handle(OpenRouterService $openRouter): int
    {
        $slug = $this->argument('character');

        if ($slug) {
            $characters = Character::where('slug', $slug)->get();

            if ($characters->isEmpty()) {
                $this->error("No character found with slug: {$slug}");

                return self::FAILURE;
            }
        } else {
            $characters = Character::orderBy('sort_order')->get();
        }

        foreach ($characters as $character) {
            $this->generateForCharacter($character, $openRouter);
        }

        return self::SUCCESS;
    }

    private function generateForCharacter(Character $character, OpenRouterService $openRouter): void
    {
        $slug = $character->slug;

        if (! isset($this->prompts[$slug])) {
            $this->warn("No portrait prompt defined for character: {$slug} — skipping.");

            return;
        }

        $this->info("Generating portrait for {$character->name}...");

        $directory = "characters/{$slug}";
        $nextIndex = $this->resolveNextIndex($directory);
        $filename = "portrait_{$nextIndex}";

        $path = $openRouter->generateAndSaveImage(
            $this->prompts[$slug],
            $directory,
            $filename,
            '2:3'
        );

        if (! $path) {
            $this->error("  ✗ Failed to generate portrait for {$character->name}.");

            return;
        }

        $character->update(['profile_image' => $path]);

        $this->line("  ✓ Saved to: {$path}");
        $this->line("  ✓ DB updated: profile_image = {$path}");
    }

    private function resolveNextIndex(string $directory): int
    {
        $files = Storage::disk('public')->files($directory);

        $max = 0;
        foreach ($files as $file) {
            $basename = basename($file);
            if (preg_match('/^portrait_(\d+)\.\w+$/', $basename, $m)) {
                $max = max($max, (int) $m[1]);
            }
        }

        return $max + 1;
    }
}
