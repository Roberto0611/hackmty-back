# Test Meal Plan API
# Usage: .\test-api.ps1

Write-Host "Testing Meal Plan API..." -ForegroundColor Cyan
Write-Host "Server: http://127.0.0.1:8000" -ForegroundColor Gray
Write-Host ""

# Create request body
$body = @{
    prompt = "300 pesos for 2 days, healthy meals"
} | ConvertTo-Json

Write-Host "Sending request..." -ForegroundColor Yellow
Write-Host "Prompt: 300 pesos for 2 days, healthy meals" -ForegroundColor Gray
Write-Host "This may take 30-60 seconds..." -ForegroundColor Gray
Write-Host ""

try {
    # Make API request
    $response = Invoke-RestMethod `
        -Uri "http://127.0.0.1:8000/api/generateMealPlan" `
        -Method POST `
        -ContentType "application/json" `
        -Body $body
    
    Write-Host "✓ Success!" -ForegroundColor Green
    Write-Host ""
    
    # Display meal plan summary
    $mealPlan = $response.data
    Write-Host "Budget: $($mealPlan.total_budget) pesos" -ForegroundColor White
    Write-Host "Days: $($mealPlan.days)" -ForegroundColor White
    Write-Host "Total Cost: $($mealPlan.total_cost) pesos" -ForegroundColor White
    Write-Host "Remaining: $($mealPlan.remaining_budget) pesos" -ForegroundColor White
    Write-Host ""
    
    # Display each day
    foreach ($day in $mealPlan.daily_plans) {
        Write-Host "DAY $($day.day) - $($day.daily_total) pesos" -ForegroundColor Cyan
        
        foreach ($meal in $day.meals) {
            Write-Host "  $($meal.meal_type): $($meal.place_name)" -ForegroundColor Yellow
            Write-Host "    📍 $($meal.latitude), $($meal.longitude)" -ForegroundColor Gray
            Write-Host "    Cost: $($meal.total_cost) pesos" -ForegroundColor Gray
        }
        Write-Host ""
    }
    
    # Save full JSON response
    $response | ConvertTo-Json -Depth 10 | Out-File "meal_plan_response.json"
    Write-Host "Full response saved to: meal_plan_response.json" -ForegroundColor Green
    
} catch {
    Write-Host "✗ Error!" -ForegroundColor Red
    Write-Host $_.Exception.Message -ForegroundColor Red
    
    if ($_.Exception.Response) {
        $reader = New-Object System.IO.StreamReader($_.Exception.Response.GetResponseStream())
        $responseBody = $reader.ReadToEnd()
        Write-Host "Response:" -ForegroundColor Yellow
        Write-Host $responseBody -ForegroundColor Gray
    }
}
