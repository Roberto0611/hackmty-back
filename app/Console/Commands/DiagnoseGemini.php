<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class DiagnoseGemini extends Command
{
    protected $signature = 'diagnose:gemini';
    protected $description = 'Diagnose Gemini API connection issues';

    public function handle(): int
    {
        $this->info('=== Gemini API Diagnostics ===');
        $this->newLine();

        // Check 1: API Key
        $this->info('1. Checking API Key...');
        $apiKey = config('services.gemini.api_key');
        
        if (empty($apiKey)) {
            $this->error('   ✗ API Key is NOT configured');
            $this->warn('   → Add GEMINI_API_KEY to your .env file');
            $this->warn('   → Run: php artisan config:clear');
            return Command::FAILURE;
        }
        
        $maskedKey = substr($apiKey, 0, 10) . '...' . substr($apiKey, -4);
        $this->info("   ✓ API Key found: {$maskedKey}");
        $this->newLine();

        // Check 2: App Base URL
        $this->info('2. Checking App Base URL...');
        $appBaseUrl = config('services.gemini.app_base_url');
        $this->info("   ✓ App Base URL: {$appBaseUrl}");
        $this->newLine();

        // Check 3: Test Gemini API Connection
        $this->info('3. Testing Gemini API connection...');
        $this->info('   Making test request to Gemini...');
        
        try {
            $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent";
            
            $response = Http::timeout(30)
                ->withoutVerifying() // Disable SSL verification for development
                ->withHeaders([
                    'Content-Type' => 'application/json',
                ])
                ->post("{$url}?key={$apiKey}", [
                    'contents' => [
                        [
                            'parts' => [
                                [
                                    'text' => 'Say "Hello" in one word'
                                ]
                            ]
                        ]
                    ]
                ]);

            if ($response->successful()) {
                $this->info('   ✓ Successfully connected to Gemini API!');
                $data = $response->json();
                
                if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                    $reply = $data['candidates'][0]['content']['parts'][0]['text'];
                    $this->info("   ✓ Gemini replied: {$reply}");
                }
                
                $this->newLine();
            } else {
                $this->error('   ✗ Gemini API returned error');
                $this->error("   Status: {$response->status()}");
                $this->line("   Response: {$response->body()}");
                $this->newLine();
                
                if ($response->status() == 400) {
                    $body = $response->json();
                    if (isset($body['error']['message'])) {
                        $this->warn("   Error message: {$body['error']['message']}");
                    }
                    if (strpos($response->body(), 'API_KEY_INVALID') !== false) {
                        $this->warn('   → Your API key appears to be invalid');
                        $this->warn('   → Get a new key from: https://aistudio.google.com/app/apikey');
                    }
                }
                
                return Command::FAILURE;
            }
        } catch (\Exception $e) {
            $this->error('   ✗ Exception occurred: ' . $e->getMessage());
            $this->newLine();
            
            if (strpos($e->getMessage(), 'cURL error 6') !== false || strpos($e->getMessage(), 'Could not resolve host') !== false) {
                $this->warn('   → Network/DNS issue detected');
                $this->warn('   → Check your internet connection');
                $this->warn('   → Try: ping generativelanguage.googleapis.com');
            }
            
            return Command::FAILURE;
        }

        // Check 4: Test local endpoints
        $this->info('4. Testing local API endpoints...');
        
        try {
            $testUrl = $appBaseUrl . '/getProducts';
            $this->info("   Testing: {$testUrl}");
            
            $response = Http::timeout(10)
                ->withoutVerifying() // Disable SSL verification for development
                ->get($testUrl);
            
            if ($response->successful()) {
                $products = $response->json();
                $count = is_array($products) ? count($products) : 0;
                $this->info("   ✓ Local API working! Found {$count} products");
            } else {
                $this->warn("   ⚠ Local API returned status: {$response->status()}");
                $this->warn('   → Make sure your Laravel server is running: php artisan serve');
            }
        } catch (\Exception $e) {
            $this->warn('   ⚠ Could not connect to local API: ' . $e->getMessage());
            $this->warn('   → Make sure your Laravel server is running: php artisan serve');
        }
        
        $this->newLine();
        $this->info('=== Diagnostics Complete ===');
        $this->newLine();
        $this->info('If all checks passed, try running:');
        $this->line('  php artisan test:meal-planner');
        
        return Command::SUCCESS;
    }
}
