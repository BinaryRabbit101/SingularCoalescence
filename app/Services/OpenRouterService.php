<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class OpenRouterService
{
    protected string $baseUrl;

    protected string $token;

    protected string $model;

    protected string $imageModel;

    /** @var array<string, mixed> */
    protected array $defaultOptions;

    public function __construct()
    {
        $this->baseUrl = config('services.openrouter.base_url');
        $this->token = config('services.openrouter.token');
        $this->model = config('services.openrouter.model');
        $this->imageModel = config('services.openrouter.image_model');
        $this->defaultOptions = config('services.openrouter.default_options', []);
    }

    /**
     * Send a chat completion request to OpenRouter.
     */
    public function chat(string $userMessage, ?string $systemMessage = null, ?string $model = null, array $options = []): ?string
    {
        $messages = [];

        if ($systemMessage) {
            $messages[] = [
                'role' => 'system',
                'content' => $systemMessage,
            ];
        }

        $messages[] = [
            'role' => 'user',
            'content' => $userMessage,
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.$this->token,
            'Content-Type' => 'application/json',
        ])->post($this->baseUrl.'/chat/completions', array_merge(
            $this->defaultOptions,
            [
                'model' => $model ?? $this->model,
                'messages' => $messages,
            ],
            $options
        ));

        if ($response->successful()) {
            Log::info('OpenRouter API response', [
                'status' => $response->status(),
                'successful' => $response->successful(),
                'body' => $response->body(),
            ]);

            $content = $response->json();

            return $content['choices'][0]['message']['content'] ?? null;
        }

        Log::error('OpenRouter API failed', [
            'status' => $response->status(),
            'body' => $response->body(),
        ]);

        return null;
    }

    /**
     * Generate an image using OpenRouter's chat/completions endpoint with image modalities.
     *
     * @param  string  $prompt  The text prompt describing the image
     * @param  string|null  $aspectRatio  Aspect ratio hint appended to the prompt
     * @return string|null Base64 data URL or null on failure
     */
    public function generateImage(string $prompt, ?string $aspectRatio = '1:1'): ?string
    {
        $aspectHint = $aspectRatio ? " Aspect ratio: {$aspectRatio}." : '';
        $noBorder = ' Full bleed to all edges. No white border, no padding, no letterbox, no watermark, no text.';

        $response = Http::timeout(120)->withHeaders([
            'Authorization' => 'Bearer '.$this->token,
            'Content-Type' => 'application/json',
        ])->post($this->baseUrl.'/chat/completions', [
            'model' => $this->imageModel,
            'messages' => [
                [
                    'role' => 'user',
                    'content' => [
                        ['type' => 'text', 'text' => $prompt.$aspectHint.$noBorder],
                    ],
                ],
            ],
            'modalities' => ['image', 'text'],
        ]);

        if ($response->successful()) {
            $json = $response->json();
            $message = $json['choices'][0]['message'] ?? [];

            // OpenRouter places generated images in message.images[]
            $images = $message['images'] ?? [];
            if (! empty($images)) {
                $dataUrl = $images[0]['image_url']['url'] ?? null;
                if ($dataUrl) {
                    Log::info('OpenRouter image generated successfully (message.images)');

                    return $dataUrl;
                }
            }

            // Fallback: content array of parts
            $content = $message['content'] ?? null;
            if (is_array($content)) {
                foreach ($content as $part) {
                    $type = $part['type'] ?? '';

                    if ($type === 'image_url') {
                        $dataUrl = $part['image_url']['url'] ?? null;
                        if ($dataUrl) {
                            Log::info('OpenRouter image generated successfully (content image_url part)');

                            return $dataUrl;
                        }
                    }

                    if ($type === 'image' || isset($part['inline_data'])) {
                        $inlineData = $part['inline_data'] ?? $part;
                        $b64 = $inlineData['data'] ?? null;
                        $mime = $inlineData['mime_type'] ?? 'image/png';
                        if ($b64) {
                            Log::info('OpenRouter image generated successfully (inline_data part)');

                            return "data:{$mime};base64,{$b64}";
                        }
                    }
                }
            }

            // Fallback: string content that is itself a data URL
            if (is_string($content) && str_starts_with($content, 'data:image/')) {
                Log::info('OpenRouter image generated successfully (string data url)');

                return $content;
            }

            Log::warning('OpenRouter image response had no recognisable image part', ['response' => $json]);

            return null;
        }

        Log::error('OpenRouter image generation failed', [
            'status' => $response->status(),
            'body' => substr($response->body(), 0, 500),
        ]);

        return null;
    }

    /**
     * Generate an image and save it to storage.
     *
     * @param  string  $prompt  The text prompt describing the image
     * @param  string  $directory  Storage directory (e.g., 'avatars')
     * @param  string|null  $filename  Optional filename (without extension)
     * @param  string|null  $aspectRatio  Aspect ratio
     * @return string|null The storage path or null on failure
     */
    public function generateAndSaveImage(string $prompt, string $directory, ?string $filename = null, ?string $aspectRatio = '1:1'): ?string
    {
        $imageDataUrl = $this->generateImage($prompt, $aspectRatio);

        if (! $imageDataUrl) {
            return null;
        }

        return $this->saveBase64Image($imageDataUrl, $directory, $filename);
    }

    /**
     * Save a base64 encoded image to storage.
     *
     * @param  string  $base64DataUrl  The base64 data URL (data:image/png;base64,...)
     * @param  string  $directory  Storage directory
     * @param  string|null  $filename  Optional filename (without extension)
     * @return string|null The storage path or null on failure
     */
    public function saveBase64Image(string $base64DataUrl, string $directory, ?string $filename = null): ?string
    {
        // Parse the data URL
        if (! preg_match('/^data:image\/(\w+);base64,(.+)$/', $base64DataUrl, $matches)) {
            Log::error('Invalid base64 image data URL format');

            return null;
        }

        $extension = $matches[1];
        $base64Data = $matches[2];

        // Decode the image
        $imageData = base64_decode($base64Data);

        if ($imageData === false) {
            Log::error('Failed to decode base64 image data');

            return null;
        }

        // Trim white/near-white borders the model sometimes adds
        $trimmed = $this->trimBorders($imageData);
        if ($trimmed !== null) {
            $imageData = $trimmed;
            $extension = 'png';
        }

        // Generate filename if not provided
        $filename = $filename ?? uniqid('img_', true);
        $path = "{$directory}/{$filename}.{$extension}";

        // Save to storage
        if (Storage::disk('public')->put($path, $imageData)) {
            return $path;
        }

        Log::error('Failed to save image to storage', ['path' => $path]);

        return null;
    }

    /**
     * Trim white/near-white borders from an image using GD.
     * Returns the trimmed PNG bytes, or null if trimming wasn't possible.
     */
    private function trimBorders(string $imageData): ?string
    {
        if (! function_exists('imagecreatefromstring') || ! function_exists('imagecropauto')) {
            return null;
        }

        $img = @imagecreatefromstring($imageData);
        if (! $img) {
            return null;
        }

        // Crop with 15% threshold against white — handles off-white padding too
        $trimmed = @imagecropauto($img, IMG_CROP_THRESHOLD, 0.15, 0xFFFFFF);
        imagedestroy($img);

        if (! $trimmed) {
            return null;
        }

        ob_start();
        imagepng($trimmed);
        $result = ob_get_clean();
        imagedestroy($trimmed);

        return ($result !== false && strlen($result) > 0) ? $result : null;
    }
}
