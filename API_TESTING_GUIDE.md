# API Testing Guide

## ✅ Fixed Issue

**Problem:** API requests were timing out with "Maximum execution time of 30 seconds exceeded"

**Solution:** Added `set_time_limit(120)` in `MealPlanController.php` to allow 2 minutes for Gemini API calls.

## 🚀 How to Test the API

### 1. Start the Laravel Server

```bash
php artisan serve
```

Server will run at: `http://127.0.0.1:8000`

---

### 2. Test Using PowerShell Script (Easiest)

```powershell
.\test-api.ps1
```

This script will:
- Send a test request
- Show formatted meal plan
- Save full JSON response to `meal_plan_response.json`

---

### 3. Test Using PowerShell Commands

```powershell
# Create request body
$body = @{prompt="300 pesos for 2 days"} | ConvertTo-Json

# Send request (wait 30-60 seconds)
$response = Invoke-RestMethod `
    -Uri "http://127.0.0.1:8000/api/generateMealPlan" `
    -Method POST `
    -ContentType "application/json" `
    -Body $body

# View response
$response | ConvertTo-Json -Depth 10
```

---

### 4. Test Using curl (if installed)

```bash
curl.exe -X POST http://127.0.0.1:8000/api/generateMealPlan \
  -H "Content-Type: application/json" \
  -d "{\"prompt\": \"300 pesos for 2 days\"}"
```

---

### 5. Test Using Postman

1. **Method:** POST
2. **URL:** `http://127.0.0.1:8000/api/generateMealPlan`
3. **Headers:**
   - Key: `Content-Type`
   - Value: `application/json`
4. **Body** (raw JSON):
```json
{
  "prompt": "Give me a meal plan for 3 days with 500 pesos, healthy food"
}
```
5. **Click Send** (wait 30-60 seconds)

---

## 📊 Expected Response

```json
{
  "success": true,
  "data": {
    "total_budget": 300,
    "days": 2,
    "health_level": 3,
    "daily_plans": [
      {
        "day": 1,
        "meals": [
          {
            "meal_type": "breakfast",
            "place_id": 10,
            "place_name": "Tim Hortons Tec",
            "latitude": 25.6503535,
            "longitude": -100.28980558,
            "products": [
              {
                "product_id": 24,
                "name": "Café Americano",
                "price": 35,
                "quantity": 1
              }
            ],
            "total_cost": 35,
            "discount_applied": ""
          }
        ],
        "daily_total": 150
      }
    ],
    "total_cost": 295,
    "remaining_budget": 5
  },
  "meta": {
    "iterations": 3,
    "generated_at": "2025-10-26T04:32:51.000000Z"
  }
}
```

---

## 🎯 Example Prompts

```
"300 pesos for 2 days"
"500 pesos for 3 days, healthy meals"
"Give me a meal plan for 5 days with 1000 pesos"
"I need healthy food for a week, budget 1500 pesos"
"200 pesos for 1 day, I want Mexican food"
```

---

## ⚠️ Common Issues

### Issue: "500 Internal Server Error"
**Solution:** Check `storage/logs/laravel.log` for details

### Issue: Request takes too long
**Normal:** Gemini API can take 30-60 seconds
**If >2 minutes:** Check logs for errors

### Issue: "400 Bad Request"
**Solution:** Verify JSON format in request body
```json
{
  "prompt": "your prompt here"
}
```

---

## 🔧 Files Modified

1. **`app/Http/Controllers/MealPlanController.php`**
   - Added: `set_time_limit(120)` to handle long Gemini responses

2. **`app/Services/GeminiMealPlannerService.php`**
   - Fixed empty array handling
   - Increased max iterations to 15
   - Improved error handling

3. **`app/Http/Controllers/ProductsController.php`**
   - Added latitude/longitude to API responses

---

## 📝 Quick Reference

| What | Command |
|------|---------|
| Start server | `php artisan serve` |
| Run test script | `.\test-api.ps1` |
| View logs | `type storage\logs\laravel.log` |
| Clear logs | `Clear-Content storage\logs\laravel.log` |
| API endpoint | `POST http://127.0.0.1:8000/api/generateMealPlan` |

---

## ✅ Testing Checklist

- [ ] Laravel server is running (`php artisan serve`)
- [ ] GEMINI_API_KEY is set in `.env`
- [ ] Config cache cleared (`php artisan config:clear`)
- [ ] Request includes `prompt` field
- [ ] Wait 30-60 seconds for response
- [ ] Check logs if errors occur

The API is now working! The `set_time_limit(120)` fix allows enough time for Gemini to process requests.
