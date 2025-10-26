<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiMealPlannerService
{
    private string $apiKey;
    private string $baseUrl;
    private string $appBaseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key');
        $this->baseUrl = 'https://generativelanguage.googleapis.com/v1beta';
        $this->appBaseUrl = config('services.gemini.app_base_url', 'http://127.0.0.1:8000/api');
    }

    /**
     * Generate a meal plan based on user prompt
     */
    public function generateMealPlan(string $userPrompt): array
    {
        $messages = [
            [
                'role' => 'user',
                'parts' => [
                    [
                        'text' => "You are a helpful meal planning assistant for students at Tec de Monterrey. Based on the user's request, create a detailed meal plan.
                        
User request: {$userPrompt}

Instructions:
1. First, call extract_meal_plan_parameters to extract budget, days, and health level from the prompt
2. Call get_places_products to get all available products with prices and coordinates
3. Optionally call get_discounts_by_day to check for discounts
4. Create a meal plan (breakfast, lunch, dinner for each day) that fits the budget
5. Consider health level: 1=junk food, 3=balanced, 5=very healthy
6. Once you have all the data needed, STOP calling functions and return your final answer

CRITICAL: Your FINAL response must be a JSON code block with this EXACT structure:

```json
{
  \"total_budget\": <number>,
  \"days\": <number of days>,
  \"health_level\": <1-5>,
  \"daily_plans\": [
    {
      \"day\": 1,
      \"meals\": [
        {
          \"meal_type\": \"breakfast\" or \"lunch\" or \"dinner\",
          \"place_id\": <place_id number>,
          \"place_name\": \"<exact place name>\",
          \"latitude\": <latitude from get_places_products>,
          \"longitude\": <longitude from get_places_products>,
          \"products\": [
            {
              \"product_id\": <id>,
              \"name\": \"<product name>\",
              \"price\": <price>,
              \"quantity\": <quantity>
            }
          ],
          \"total_cost\": <sum of products>,
          \"discount_applied\": \"<discount description or empty string>\"
        }
      ],
      \"daily_total\": <sum of all meals>
    }
  ],
  \"total_cost\": <total for all days>,
  \"remaining_budget\": <budget minus total_cost>
}
```

IMPORTANT: 
- Use latitude/longitude from the get_places_products response
- Do NOT make up coordinates
- Return ONLY the JSON, wrapped in ```json ... ```
- Do NOT add any text before or after the JSON block

Start now by calling extract_meal_plan_parameters."
                    ]
                ]
            ]
        ];

        $tools = $this->getFunctionDefinitions();
        $maxIterations = 15; // Increased for complex requests (5+ days)
        $iteration = 0;

        while ($iteration < $maxIterations) {
            $iteration++;
            
            $response = $this->callGemini($messages, $tools);
            
            if (!$response || !isset($response['candidates'][0])) {
                return [
                    'success' => false,
                    'error' => 'No response from Gemini API',
                    'debug' => $response
                ];
            }

            $candidate = $response['candidates'][0];
            $content = $candidate['content'] ?? null;
            
            if (!$content) {
                return [
                    'success' => false,
                    'error' => 'Invalid response structure',
                    'debug' => $candidate
                ];
            }


            // Check if we have function calls to execute
            $parts = $content['parts'] ?? [];
            
            // Check for empty parts or blocked content
            if (empty($parts)) {
                $finishReason = $candidate['finishReason'] ?? 'UNKNOWN';
                
                // Check if we hit max iterations or other stop conditions
                if ($iteration >= $maxIterations - 1) {
                    return [
                        'success' => false,
                        'error' => 'Max iterations reached without response',
                        'finish_reason' => $finishReason,
                        'iterations' => $iteration
                    ];
                }
                
                // For other cases, log and continue to next iteration
                Log::warning('Empty parts in response, continuing', [
                    'finish_reason' => $finishReason,
                    'iteration' => $iteration
                ]);
                continue;
            }
            
            $functionCalls = array_filter($parts, fn($part) => isset($part['functionCall']));

            if (empty($functionCalls)) {
                // No more function calls, extract final answer
                $textParts = array_filter($parts, fn($part) => isset($part['text']));
                if (!empty($textParts)) {
                    $finalText = $textParts[0]['text'];
                    
                    return [
                        'success' => true,
                        'meal_plan' => $this->parseMealPlan($finalText),
                        'raw_response' => $finalText,
                        'iterations' => $iteration
                    ];
                }
                
                // No text and no function calls - might be blocked or finished
                $finishReason = $candidate['finishReason'] ?? 'UNKNOWN';
                Log::warning('No text in final response', [
                    'finish_reason' => $finishReason,
                    'iteration' => $iteration,
                    'parts' => $parts
                ]);
                
                return [
                    'success' => false,
                    'error' => 'No text response in final answer',
                    'finish_reason' => $finishReason,
                    'debug' => $content
                ];
            }

            // Execute function calls and prepare responses
            $functionResponseParts = [];
            foreach ($functionCalls as $part) {
                $functionCall = $part['functionCall'];
                $functionName = $functionCall['name'];
                $functionArgs = $functionCall['args'] ?? (object)[];

                Log::info("Executing function: {$functionName}", (array)$functionArgs);

                $result = $this->executeFunction($functionName, (array)$functionArgs);
                
                // Gemini API requires response to be an object/struct, not an array
                // If the result is a numeric array (including empty arrays), wrap it in an object
                if (is_array($result) && (empty($result) || array_keys($result) === range(0, count($result) - 1))) {
                    $result = ['data' => $result];
                }
                
                $functionResponseParts[] = [
                    'functionResponse' => [
                        'name' => $functionName,
                        'response' => $result
                    ]
                ];
            }

            // Add user message with function responses (skip model's function call)
            $messages[] = [
                'role' => 'user',
                'parts' => $functionResponseParts
            ];
        }

        return [
            'success' => false,
            'error' => 'Max iterations reached',
            'iterations' => $iteration
        ];
    }

    /**
     * Call Gemini API
     */
    private function callGemini(array $messages, array $tools): ?array
    {
        $url = "{$this->baseUrl}/models/gemini-2.5-flash:generateContent";
        
        $payload = [
            'contents' => $messages,
            'tools' => [
                [
                    'functionDeclarations' => $tools
                ]
            ],
            'generationConfig' => [
                'temperature' => 0.7,
                'topP' => 0.95,
                'topK' => 40,
                'maxOutputTokens' => 8192,
            ]
        ];

        try {
            Log::info('Calling Gemini API', [
                'url' => $url,
                'message_count' => count($messages),
                'function_count' => count($tools)
            ]);

            $response = Http::timeout(60)
                ->withoutVerifying() // Disable SSL verification for development
                ->withHeaders([
                    'Content-Type' => 'application/json',
                ])
                ->post("{$url}?key={$this->apiKey}", $payload);

            Log::info('Gemini API response received', [
                'status' => $response->status(),
                'successful' => $response->successful()
            ]);

            if (!$response->successful()) {
                Log::error('Gemini API error', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return null;
            }

            $responseData = $response->json();
            Log::info('Gemini response parsed', [
                'has_candidates' => isset($responseData['candidates']),
                'candidate_count' => isset($responseData['candidates']) ? count($responseData['candidates']) : 0
            ]);

            return $responseData;
        } catch (\Exception $e) {
            Log::error('Gemini API exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    /**
     * Define functions that Gemini can call
     */
    private function getFunctionDefinitions(): array
    {
        return [
            [
                'name' => 'extract_meal_plan_parameters',
                'description' => 'Extract and categorize the meal plan parameters from user input',
                'parameters' => [
                    'type' => 'object',
                    'properties' => [
                        'budget' => [
                            'type' => 'number',
                            'description' => 'Total budget in the local currency'
                        ],
                        'num_days' => [
                            'type' => 'integer',
                            'description' => 'Number of days for the meal plan (1-30)'
                        ],
                        'health_level' => [
                            'type' => 'integer',
                            'description' => 'Health level from 1 (unhealthy/junk food) to 5 (very healthy/nutritious)'
                        ],
                        'dietary_restrictions' => [
                            'type' => 'string',
                            'description' => 'Any dietary restrictions mentioned (vegetarian, vegan, gluten-free, etc.)'
                        ]
                    ],
                    'required' => ['budget', 'num_days', 'health_level']
                ]
            ],
            [
                'name' => 'get_all_products',
                'description' => 'Get all available products/food items',
                'parameters' => [
                    'type' => 'object',
                    'properties' => (object)[]
                ]
            ],
            [
                'name' => 'get_products_by_category',
                'description' => 'Get products filtered by category',
                'parameters' => [
                    'type' => 'object',
                    'properties' => [
                        'category_id' => [
                            'type' => 'integer',
                            'description' => 'The category ID to filter by'
                        ]
                    ],
                    'required' => ['category_id']
                ]
            ],
            [
                'name' => 'get_all_places',
                'description' => 'Get all places/restaurants',
                'parameters' => [
                    'type' => 'object',
                    'properties' => (object)[]
                ]
            ],
            [
                'name' => 'get_places_open_now',
                'description' => 'Get places that are currently open',
                'parameters' => [
                    'type' => 'object',
                    'properties' => (object)[]
                ]
            ],
            [
                'name' => 'get_places_products',
                'description' => 'Get all products available at places with their prices. Includes place coordinates (latitude, longitude).',
                'parameters' => [
                    'type' => 'object',
                    'properties' => (object)[]
                ]
            ],
            [
                'name' => 'get_places_products_by_place',
                'description' => 'Get products and prices for a specific place. Includes place coordinates (latitude, longitude).',
                'parameters' => [
                    'type' => 'object',
                    'properties' => [
                        'place_id' => [
                            'type' => 'integer',
                            'description' => 'The place ID to get products for'
                        ]
                    ],
                    'required' => ['place_id']
                ]
            ],
            [
                'name' => 'get_discounts_by_day',
                'description' => 'Get discounts available on a specific day of week',
                'parameters' => [
                    'type' => 'object',
                    'properties' => [
                        'day' => [
                            'type' => 'integer',
                            'description' => 'Day of week: 0=Sunday, 1=Monday, 2=Tuesday, 3=Wednesday, 4=Thursday, 5=Friday, 6=Saturday'
                        ]
                    ],
                    'required' => ['day']
                ]
            ]
        ];
    }

    /**
     * Execute a function call
     */
    private function executeFunction(string $functionName, array $args): mixed
    {
        return match ($functionName) {
            'extract_meal_plan_parameters' => $args, // Just return the extracted parameters
            'get_all_products' => $this->callEndpoint('GET', '/getProducts'),
            'get_products_by_category' => $this->callEndpoint('GET', "/getProductsByCategory/{$args['category_id']}"),
            'get_all_places' => $this->callEndpoint('GET', '/getPlaces'),
            'get_places_open_now' => $this->callEndpoint('GET', '/getPlacesOpenNow'),
            'get_places_products' => $this->callEndpoint('GET', '/getPlacesProducts'),
            'get_places_products_by_place' => $this->callEndpoint('GET', "/getPlacesProductsByPlace/{$args['place_id']}"),
            'get_discounts_by_day' => $this->callEndpoint('GET', "/getDiscountsByDayFlat/{$args['day']}"),
            default => ['error' => "Unknown function: {$functionName}"]
        };
    }

    /**
     * Call internal API endpoint
     */
    private function callEndpoint(string $method, string $path): mixed
    {
        try {
            $url = $this->appBaseUrl . $path;
            
            $response = Http::timeout(30)
                ->withoutVerifying() // Disable SSL verification for development
                ->withHeaders([
                    'Accept' => 'application/json',
                ])
                ->{strtolower($method)}($url);

            if ($response->successful()) {
                return $response->json();
            }

            // If 404, return empty array instead of error
            if ($response->status() === 404) {
                return [];
            }

            return [
                'error' => 'API call failed',
                'status' => $response->status(),
                'message' => $response->body()
            ];
        } catch (\Exception $e) {
            return [
                'error' => 'Exception during API call',
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Parse the final meal plan from Gemini's text response
     */
    private function parseMealPlan(string $text): array
    {
        // Try to extract JSON if present
        if (preg_match('/```json\s*(.*?)\s*```/s', $text, $matches)) {
            $json = json_decode($matches[1], true);
            if ($json) {
                return $json;
            }
        }

        // Otherwise return structured text
        return [
            'formatted_plan' => $text,
            'note' => 'Plan is in text format. Consider parsing or asking Gemini to return JSON.'
        ];
    }
}
