<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class ListGeminiModels extends Command
{
    protected $signature = 'gemini:list-models';
    protected $description = 'List all available Gemini models';

    public function handle(): int
    {
        $this->info('=== Fetching Available Gemini Models ===');
        $this->newLine();

        $apiKey = config('services.gemini.api_key');
        
        if (empty($apiKey)) {
            $this->error('API Key not configured!');
            return Command::FAILURE;
        }

        try {
            $url = "https://generativelanguage.googleapis.com/v1beta/models";
            
            $this->info("Calling: {$url}");
            $this->newLine();
            
            $response = Http::timeout(30)
                ->withoutVerifying()
                ->get("{$url}?key={$apiKey}");

            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['models'])) {
                    $this->info('Available Models:');
                    $this->newLine();
                    
                    foreach ($data['models'] as $model) {
                        $name = $model['name'] ?? 'Unknown';
                        $displayName = $model['displayName'] ?? 'N/A';
                        $supportedMethods = $model['supportedGenerationMethods'] ?? [];
                        
                        // Extract model name from full path
                        $modelName = str_replace('models/', '', $name);
                        
                        $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
                        $this->info("Model: {$modelName}");
                        $this->line("Display Name: {$displayName}");
                        
                        if (!empty($supportedMethods)) {
                            $methodsList = implode(', ', $supportedMethods);
                            $this->line("Supported: {$methodsList}");
                            
                            // Highlight if it supports generateContent
                            if (in_array('generateContent', $supportedMethods)) {
                                $this->comment("  ✓ Supports generateContent (can be used!)");
                            }
                        }
                        $this->newLine();
                    }
                    
                    $this->newLine();
                    $this->info('=== Recommended Models for Meal Planner ===');
                    $this->line('Look for models that support "generateContent"');
                    $this->newLine();
                    
                    // Find models that support generateContent
                    $compatibleModels = [];
                    foreach ($data['models'] as $model) {
                        $methods = $model['supportedGenerationMethods'] ?? [];
                        if (in_array('generateContent', $methods)) {
                            $modelName = str_replace('models/', '', $model['name']);
                            $compatibleModels[] = $modelName;
                        }
                    }
                    
                    if (!empty($compatibleModels)) {
                        $this->info('Compatible models you can use:');
                        foreach ($compatibleModels as $modelName) {
                            $this->line("  • {$modelName}");
                        }
                        $this->newLine();
                        $this->comment('Update GeminiMealPlannerService.php with one of these model names');
                    }
                    
                } else {
                    $this->warn('No models found in response');
                    $this->line(json_encode($data, JSON_PRETTY_PRINT));
                }
                
                return Command::SUCCESS;
            } else {
                $this->error("API Error: {$response->status()}");
                $this->line($response->body());
                return Command::FAILURE;
            }
        } catch (\Exception $e) {
            $this->error('Exception: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
