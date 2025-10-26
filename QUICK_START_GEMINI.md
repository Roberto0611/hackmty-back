# Quick Start - Gemini Meal Planner

## ⚡ Fast Setup (5 minutes)

### Step 1: Add API Key
1. Open `.env` file
2. Add these lines at the bottom:
```env
GEMINI_API_KEY=your_key_here
GEMINI_APP_BASE_URL=http://127.0.0.1:8000/api
```
3. Get your key from: https://aistudio.google.com/app/apikey

### Step 2: Clear Cache
```cmd
php artisan config:clear
```

### Step 3: Start Your Server
```cmd
php artisan serve
```

### Step 4: Test It!

**Option A - Using Artisan Command (Easiest)**
```cmd
php artisan test:meal-planner
```

**Option B - Using cURL**
```cmd
curl -X POST http://127.0.0.1:8000/api/generateMealPlan -H "Content-Type: application/json" -d "{\"prompt\": \"I have 300 pesos for 3 days\"}"
```

**Option C - Using Postman**
- POST to: `http://127.0.0.1:8000/api/generateMealPlan`
- Body (JSON):
```json
{
  "prompt": "I have 300 pesos for 3 days and want healthy meals"
}
```

## 📝 Example Prompts to Try

```
"I have 200 pesos for 2 days"

"I need a 5-day meal plan with 600 pesos budget, very healthy food"

"Budget: 400 pesos, 4 days, health level 3, no meat"

"I have $500 for a week and want balanced meals with Mexican food"
```

## ✅ Expected Response Time
- First request: 40-60 seconds (Gemini is thinking + calling functions)
- The AI will call your endpoints multiple times to gather data
- You'll see function calls in the logs

## 🐛 Quick Troubleshooting

**"No response from Gemini"**
→ Check API key in `.env` and run `php artisan config:clear`

**"API call failed"**
→ Make sure Laravel server is running (`php artisan serve`)

**Timeout**
→ Normal for first request, wait up to 60 seconds

## 📊 What Happens Behind the Scenes

1. Gemini reads your prompt
2. Extracts: budget, days, health level (1-5)
3. Calls your endpoints to get:
   - Available products
   - Places and prices (with coordinates)
   - Current discounts
4. Creates optimized meal plan
5. Returns **structured JSON** with:
   - Daily meal breakdown
   - Location coordinates for each meal
   - Product details and pricing
   - Discounts applied

## 🎯 Next Steps

Once it works:
- Check `MEAL_PLAN_API.md` for complete JSON response format
- Use coordinates to draw routes on maps (Google Maps, Mapbox, etc.)
- Display meal plan in cards/tables on frontend
- Try different prompts and health levels
- Integrate with your navigation/mapping system

## 💡 Tips

- Be specific: "300 pesos for 3 days, health level 4"
- Include preferences: "vegetarian", "Mexican food", "no dairy"
- The AI understands natural language - just describe what you want!
- **NEW**: Response includes lat/lng for each location - perfect for route mapping!

---

For detailed docs, see: `GEMINI_MEAL_PLANNER.md`
