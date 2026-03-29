<?php

namespace App\Console\Commands;

use App\Services\OpenRouterService;
use Illuminate\Console\Command;

class TestOpenRouter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:open-router';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the OpenRouter API service';

    /**
     * Execute the console command.
     */
    public function handle(OpenRouterService $openRouterService): int
    {
        $this->info('Sending request to OpenRouter...');

        $systemMessage = 'You are a creative assistant that generates engaging, professional, and friendly company-facing messages for an internal help website. The website provides various tools and resources for employees. Your messages should be welcoming, concise, and highlight the available tools in an encouraging way. The message will be structured as a short paragraph. Exclude all markdown language syntax.';
        $userMessage = 'Generate a unique, professional welcome message for our company\'s help website. The site provides access to various tools. Keep the message friendly and encouraging.';

        $message = $openRouterService->chat($userMessage, $systemMessage);

        if ($message != null) {
            $this->info('Response received:');
            $this->line($message);

            return Command::SUCCESS;
        }

        $this->error('Request failed: '.$response->status());
        $this->error($response->body());

        return Command::FAILURE;
    }
}
