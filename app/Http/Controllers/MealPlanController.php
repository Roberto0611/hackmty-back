<?php

namespace App\Http\Controllers;

use App\Services\GeminiMealPlannerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MealPlanController extends Controller
{
    private GeminiMealPlannerService $geminiService;

    public function __construct(GeminiMealPlannerService $geminiService)
    {
        $this->geminiService = $geminiService;
    }

    /**
     * Generate a meal plan using Gemini AI
     * 
     * POST /api/generateMealPlan
     * Body: { "prompt": "I have $200 for 5 days and want healthy meals" }
     */
    public function generate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'prompt' => 'required|string|min:10|max:1000'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 400);
        }

        $prompt = $request->input('prompt');

        try {
            $result = $this->geminiService->generateMealPlan($prompt);
            
            if ($result['success']) {
                return response()->json($result, 200);
            } else {
                return response()->json($result, 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'An error occurred while generating the meal plan',
                'message' => $e->getMessage(),
                'trace' => config('app.debug') ? $e->getTraceAsString() : null
            ], 500);
        }
    }
}
