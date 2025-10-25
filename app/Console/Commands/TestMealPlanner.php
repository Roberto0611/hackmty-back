<?php

namespace App\Console\Commands;

use App\Services\GeminiMealPlannerService;
use Illuminate\Console\Command;

class TestMealPlanner extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'test:meal-planner {prompt?}';

    /**
     * The console command description.
     */
    protected $description = 'Test the Gemini meal planner with a sample prompt';

    /**
     * Execute the console command.
     */
    public function handle(GeminiMealPlannerService $geminiService): int
    {
        $prompt = $this->argument('prompt') ?? 
            'I have $300 pesos for 3 days and want balanced healthy meals. I prefer Mexican food.';

        $this->info('Testing Gemini Meal Planner...');
        $this->info('Prompt: ' . $prompt);
        $this->newLine();

        $this->info('Calling Gemini API (this may take 30-60 seconds)...');
        $this->line('Check storage/logs/laravel.log for detailed debug info');
        $this->newLine();
        
        try {
            $result = $geminiService->generateMealPlan($prompt);
            
            $this->newLine();
            
            if ($result['success']) {
                $this->info('✓ Success! Meal plan generated.');
                $this->newLine();
                
                if (isset($result['iterations'])) {
                    $this->line("Iterations: {$result['iterations']}");
                }
                
                $this->newLine();
                $this->line('=== MEAL PLAN ===');
                $this->newLine();
                
                if (isset($result['meal_plan']['formatted_plan'])) {
                    $this->line($result['meal_plan']['formatted_plan']);
                } else {
                    $this->line(json_encode($result['meal_plan'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                }
                
                if (isset($result['raw_response'])) {
                    $this->newLine();
                    $this->line('=== RAW RESPONSE ===');
                    $this->newLine();
                    $this->line($result['raw_response']);
                }
                
                return Command::SUCCESS;
            } else {
                $this->error('✗ Failed to generate meal plan');
                $this->newLine();
                $this->line('Error: ' . ($result['error'] ?? 'Unknown error'));
                
                if (isset($result['debug'])) {
                    $this->newLine();
                    $this->line('=== DEBUG INFO ===');
                    $this->line(json_encode($result['debug'], JSON_PRETTY_PRINT));
                }
                
                return Command::FAILURE;
            }
        } catch (\Exception $e) {
            $this->error('Exception: ' . $e->getMessage());
            $this->error($e->getTraceAsString());
            return Command::FAILURE;
        }
    }
}
