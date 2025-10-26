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
                $this->line('=== STRUCTURED MEAL PLAN ===');
                $this->newLine();
                
                $mealPlan = $result['meal_plan'];
                
                // Check if we have structured data
                if (isset($mealPlan['formatted_plan'])) {
                    // Text format fallback
                    $this->line($mealPlan['formatted_plan']);
                    if (isset($mealPlan['note'])) {
                        $this->newLine();
                        $this->warn($mealPlan['note']);
                    }
                    return Command::SUCCESS;
                }
                
                // Validate required fields
                if (!isset($mealPlan['total_budget']) || !isset($mealPlan['daily_plans'])) {
                    $this->error('Invalid meal plan structure');
                    $this->newLine();
                    $this->line('=== RAW RESPONSE ===');
                    $this->line(json_encode($mealPlan, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                    return Command::FAILURE;
                }
                
                // Display summary
                $this->line("Budget: {$mealPlan['total_budget']} pesos");
                $this->line("Days: {$mealPlan['days']}");
                $this->line("Health Level: {$mealPlan['health_level']}/5");
                $this->line("Total Cost: {$mealPlan['total_cost']} pesos");
                $this->line("Remaining: {$mealPlan['remaining_budget']} pesos");
                $this->newLine();
                
                // Display daily plans
                foreach ($mealPlan['daily_plans'] as $day) {
                    $this->info("DAY {$day['day']} - {$day['daily_total']} pesos");
                    
                    foreach ($day['meals'] as $meal) {
                        $this->line("  {$meal['meal_type']}: {$meal['place_name']}");
                        $this->line("    📍 Location: {$meal['latitude']}, {$meal['longitude']}");
                        
                        foreach ($meal['products'] as $product) {
                            $this->line("    - {$product['name']} x{$product['quantity']} = {$product['price']} pesos");
                        }
                        
                        if (!empty($meal['discount_applied'])) {
                            $this->line("    💰 Discount: {$meal['discount_applied']}");
                        }
                        
                        $this->line("    Total: {$meal['total_cost']} pesos");
                        $this->newLine();
                    }
                }
                
                $this->newLine();
                $this->line('=== RAW JSON (for frontend) ===');
                $this->newLine();
                $this->line(json_encode($result['meal_plan'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                
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
