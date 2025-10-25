# Gemini AI Meal Planner

This feature uses Google's Gemini API with function calling to generate personalized meal plans based on user budget, timeframe, and health preferences.

## Features

- 🤖 **AI-Powered**: Uses Gemini 1.5 Pro with function calling
- 💰 **Budget-Aware**: Plans meals within specified budget
- 📅 **Flexible Duration**: Supports 1-30 day meal plans
- 🥗 **Health Levels**: 1 (junk food) to 5 (very healthy)
- 🏪 **Real Data**: Queries actual places, products, and prices from your database
- 🎯 **Discount-Aware**: Considers available discounts by day
- 📍 **Location-Based**: Can filter by places open now

## Setup Instructions

### 1. Get Gemini API Key

1. Go to [Google AI Studio](https://aistudio.google.com/app/apikey)
2. Create a new API key
3. Copy the key

### 2. Configure Environment

Add to your `.env` file:

```env
# Gemini AI Configuration
GEMINI_API_KEY=your_gemini_api_key_here
GEMINI_APP_BASE_URL=http://127.0.0.1:8000/api
```

**Important**: 
- Replace `your_gemini_api_key_here` with your actual API key
- For production, change `GEMINI_APP_BASE_URL` to your deployed API URL

### 3. Test the Service is Working

Clear config cache and test:

```cmd
php artisan config:clear
php artisan test:meal-planner
```

This will run a test with a default prompt. You should see output after 30-60 seconds.

### 4. Test with Custom Prompt

```cmd
php artisan test:meal-planner "I have 500 pesos for 7 days and want very healthy vegetarian meals"
```

## API Usage

### Endpoint

```
POST /api/generateMealPlan
```

**No authentication required** (for testing purposes)

### Request Body

```json
{
  "prompt": "I have $300 for 5 days and want balanced meals with some healthy options"
}
```

### Example Prompts

```json
// Basic prompt
{
  "prompt": "I have 200 pesos for 3 days"
}

// Detailed prompt
{
  "prompt": "I have a budget of 500 pesos for 7 days. I want very healthy meals, mostly vegetarian, and I prefer Mexican food."
}

// Specific requirements
{
  "prompt": "I need a 2-day meal plan with 300 pesos budget. Health level 4 out of 5. No dairy products."
}
```

### Response Format

```json
{
  "success": true,
  "meal_plan": {
    "days": [
      {
        "day": 1,
        "meals": {
          "breakfast": {
            "item": "Combo Familiar",
            "place": "Taquería El Buen Taco",
            "price": 150
          },
          "lunch": { ... },
          "dinner": { ... }
        },
        "daily_total": 450
      }
    ],
    "total_cost": 1350,
    "budget": 1500,
    "remaining": 150
  },
  "raw_response": "Full text response from Gemini...",
  "iterations": 5
}
```

## How It Works

### Function Calling Flow

1. **User sends prompt** → API receives natural language request
2. **Gemini extracts parameters** → Calls `extract_meal_plan_parameters` function
3. **Gemini queries data** → Calls multiple endpoints in parallel:
   - `get_all_products` - Get available food items
   - `get_places_products` - Get prices at each location
   - `get_discounts_by_day` - Check for daily discounts
   - `get_places_open_now` - Filter by current availability
4. **Gemini creates plan** → Generates structured meal plan
5. **API returns result** → JSON response with meal plan

### Available Functions for Gemini

The AI can call these endpoints to gather information:

- `extract_meal_plan_parameters` - Extract budget, days, health level (1-5)
- `get_all_products` - All food products
- `get_products_by_category` - Filter by category
- `get_all_places` - All restaurants/places
- `get_places_open_now` - Currently open places
- `get_places_products` - Products with prices at all places
- `get_places_products_by_place` - Products at specific place
- `get_discounts_by_day` - Discounts for specific day (0=Sun...6=Sat)

## Testing with cURL

### Windows (cmd)

```cmd
curl -X POST http://127.0.0.1:8000/api/generateMealPlan ^
  -H "Content-Type: application/json" ^
  -d "{\"prompt\": \"I have 400 pesos for 4 days and want healthy meals\"}"
```

### Linux/Mac

```bash
curl -X POST http://127.0.0.1:8000/api/generateMealPlan \
  -H "Content-Type: application/json" \
  -d '{"prompt": "I have 400 pesos for 4 days and want healthy meals"}'
```

### Using Postman

1. Method: `POST`
2. URL: `http://127.0.0.1:8000/api/generateMealPlan`
3. Headers: `Content-Type: application/json`
4. Body (raw JSON):
   ```json
   {
     "prompt": "I have 400 pesos for 4 days and want healthy meals"
   }
   ```

## Troubleshooting

### "No response from Gemini API"

- Check your API key is correct in `.env`
- Run `php artisan config:clear`
- Verify you have internet connection
- Check Gemini API quota/billing at [Google AI Studio](https://aistudio.google.com/)

### "API call failed" or empty responses

- Make sure your Laravel app is running: `php artisan serve`
- Verify the database has seeded data
- Check `GEMINI_APP_BASE_URL` matches your server URL
- Look at logs: `storage/logs/laravel.log`

### Timeout errors

- Gemini can take 30-60 seconds for complex requests
- Increase timeout in `GeminiMealPlannerService.php` if needed
- Reduce the number of days in your prompt

### Function calling not working

- Ensure you're using `gemini-1.5-pro` (supports function calling)
- Check the function definitions match the endpoint structure
- Review logs for function call attempts

## Debug Mode

Enable detailed logging by checking `storage/logs/laravel.log`:

```php
// Already enabled in GeminiMealPlannerService
Log::info("Executing function: {$functionName}", $functionArgs);
```

## Production Deployment

Before deploying:

1. Update `.env`:
   ```env
   GEMINI_APP_BASE_URL=https://your-production-domain.com/api
   ```

2. Add authentication if needed (currently disabled for testing)

3. Consider rate limiting on the endpoint

4. Monitor API costs at Google AI Studio

5. Cache common queries if needed

## Files Created

- `app/Services/GeminiMealPlannerService.php` - Main AI service
- `app/Http/Controllers/MealPlanController.php` - API controller
- `app/Console/Commands/TestMealPlanner.php` - Test command
- `config/services.php` - Updated with Gemini config
- `routes/api.php` - Added route

## Next Steps

1. Add your Gemini API key to `.env`
2. Run `php artisan test:meal-planner`
3. Test with various prompts
4. Adjust function definitions if needed
5. Fine-tune the AI prompt in `GeminiMealPlannerService.php`

## Support

For issues:
- Check Laravel logs: `storage/logs/laravel.log`
- Enable debug mode: `APP_DEBUG=true` in `.env`
- Review Gemini API docs: https://ai.google.dev/docs
