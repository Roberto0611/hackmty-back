# Meal Plan API Documentation

## Endpoint: Generate Meal Plan

**POST** `/api/generateMealPlan`

Generate a structured meal plan with location coordinates based on budget and preferences.

---

## Request

### Headers
```
Content-Type: application/json
```

### Body
```json
{
  "prompt": "I have 300 pesos for 3 days and want healthy meals"
}
```

### Parameters

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| prompt | string | Yes | Natural language description of meal plan requirements (budget, days, preferences) |

---

## Response

### Success Response (200 OK)

```json
{
  "success": true,
  "data": {
    "total_budget": 300,
    "days": 3,
    "health_level": 4,
    "daily_plans": [
      {
        "day": 1,
        "meals": [
          {
            "meal_type": "breakfast",
            "place_name": "OXXO Aulas 4",
            "place_id": 2,
            "latitude": 25.649347,
            "longitude": -100.290504,
            "products": [
              {
                "product_id": 15,
                "name": "Sandwich Club",
                "price": 35,
                "quantity": 1
              },
              {
                "product_id": 18,
                "name": "Agua Natural",
                "price": 12,
                "quantity": 1
              }
            ],
            "total_cost": 47,
            "discount_applied": null
          },
          {
            "meal_type": "lunch",
            "place_name": "Tacos Leal Tec",
            "place_id": 10,
            "latitude": 25.645980,
            "longitude": -100.289313,
            "products": [
              {
                "product_id": 3,
                "name": "Orden de Tacos (5)",
                "price": 70,
                "quantity": 1
              },
              {
                "product_id": 20,
                "name": "Agua de Sabor",
                "price": 15,
                "quantity": 1
              }
            ],
            "total_cost": 42.5,
            "discount_applied": "2x1 Martes de Tacos - 50% OFF"
          },
          {
            "meal_type": "dinner",
            "place_name": "Subway Tec",
            "place_id": 8,
            "latitude": 25.651351,
            "longitude": -100.288779,
            "products": [
              {
                "product_id": 15,
                "name": "Sandwich Club",
                "price": 65,
                "quantity": 1
              }
            ],
            "total_cost": 65,
            "discount_applied": null
          }
        ],
        "daily_total": 154.5
      },
      {
        "day": 2,
        "meals": [
          {
            "meal_type": "breakfast",
            "place_name": "Tim Hortons Tec",
            "place_id": 5,
            "latitude": 25.650353,
            "longitude": -100.289805,
            "products": [
              {
                "product_id": 25,
                "name": "Café Americano",
                "price": 35,
                "quantity": 1
              },
              {
                "product_id": 30,
                "name": "Brownie",
                "price": 30,
                "quantity": 1
              }
            ],
            "total_cost": 65,
            "discount_applied": null
          },
          {
            "meal_type": "lunch",
            "place_name": "Little Caesars Tec",
            "place_id": 12,
            "latitude": 25.651603,
            "longitude": -100.288879,
            "products": [
              {
                "product_id": 11,
                "name": "Pizza Personal",
                "price": 69,
                "quantity": 1
              }
            ],
            "total_cost": 69,
            "discount_applied": null
          },
          {
            "meal_type": "dinner",
            "place_name": "McDonald's Garza Sada",
            "place_id": 14,
            "latitude": 25.643147,
            "longitude": -100.287581,
            "products": [
              {
                "product_id": 7,
                "name": "Hamburguesa Sencilla",
                "price": 42,
                "quantity": 1
              }
            ],
            "total_cost": 42,
            "discount_applied": null
          }
        ],
        "daily_total": 176
      },
      {
        "day": 3,
        "meals": [
          {
            "meal_type": "breakfast",
            "place_name": "OXXO Eugenio Garza Sada",
            "place_id": 1,
            "latitude": 25.655436,
            "longitude": -100.294209,
            "products": [
              {
                "product_id": 15,
                "name": "Sandwich Club",
                "price": 35,
                "quantity": 1
              }
            ],
            "total_cost": 35,
            "discount_applied": null
          },
          {
            "meal_type": "lunch",
            "place_name": "Teo Tacos Tecnológico",
            "place_id": 11,
            "latitude": 25.649116,
            "longitude": -100.288378,
            "products": [
              {
                "product_id": 3,
                "name": "Orden de Tacos (5)",
                "price": 65,
                "quantity": 1
              }
            ],
            "total_cost": 65,
            "discount_applied": null
          }
        ],
        "daily_total": 100
      }
    ],
    "total_cost": 430.5,
    "remaining_budget": -130.5
  },
  "meta": {
    "iterations": 6,
    "generated_at": "2025-10-25T14:30:00Z"
  }
}
```

---

## Response Fields

### Root Level

| Field | Type | Description |
|-------|------|-------------|
| success | boolean | Whether the request was successful |
| data | object | The meal plan data (see below) |
| meta | object | Metadata about the generation process |

### Data Object

| Field | Type | Description |
|-------|------|-------------|
| total_budget | number | Total budget allocated for the meal plan |
| days | integer | Number of days in the meal plan |
| health_level | integer | Health level (1=unhealthy, 5=very healthy) |
| daily_plans | array | Array of daily meal plans (see below) |
| total_cost | number | Total cost of all meals |
| remaining_budget | number | Budget remaining (negative if over budget) |

### Daily Plan Object

| Field | Type | Description |
|-------|------|-------------|
| day | integer | Day number (1, 2, 3, etc.) |
| meals | array | Array of meals for this day |
| daily_total | number | Total cost for this day |

### Meal Object

| Field | Type | Description |
|-------|------|-------------|
| meal_type | string | Type of meal: "breakfast", "lunch", or "dinner" |
| place_name | string | Name of the restaurant/place |
| place_id | integer | Database ID of the place |
| latitude | number | Latitude coordinate for navigation |
| longitude | number | Longitude coordinate for navigation |
| products | array | Array of products ordered (see below) |
| total_cost | number | Total cost for this meal |
| discount_applied | string\|null | Description of discount if applied |

### Product Object

| Field | Type | Description |
|-------|------|-------------|
| product_id | integer | Database ID of the product |
| name | string | Product name |
| price | number | Price per unit |
| quantity | integer | Quantity ordered |

---

## Error Responses

### Validation Error (400)
```json
{
  "success": false,
  "errors": {
    "prompt": ["The prompt field is required."]
  }
}
```

### Generation Error (500)
```json
{
  "success": false,
  "error": "An error occurred while generating the meal plan",
  "message": "Detailed error message here"
}
```

---

## Usage Examples

### Example 1: Basic Request
```bash
curl -X POST http://localhost:8000/api/generateMealPlan \
  -H "Content-Type: application/json" \
  -d '{"prompt": "I have 300 pesos for 3 days"}'
```

### Example 2: With Health Preferences
```bash
curl -X POST http://localhost:8000/api/generateMealPlan \
  -H "Content-Type: application/json" \
  -d '{"prompt": "I need a 5-day meal plan with 600 pesos budget, very healthy food"}'
```

### Example 3: With Dietary Restrictions
```bash
curl -X POST http://localhost:8000/api/generateMealPlan \
  -H "Content-Type: application/json" \
  -d '{"prompt": "Budget: 400 pesos, 4 days, health level 3, vegetarian meals only"}'
```

---

## Frontend Integration Guide

### Displaying the Meal Plan

```javascript
const response = await fetch('/api/generateMealPlan', {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({ 
    prompt: 'I have 300 pesos for 3 days and want healthy meals' 
  })
});

const { success, data } = await response.json();

if (success) {
  // Display meal plan
  data.daily_plans.forEach(day => {
    console.log(`Day ${day.day}: ${day.daily_total} pesos`);
    day.meals.forEach(meal => {
      console.log(`  ${meal.meal_type} at ${meal.place_name}`);
      console.log(`  Location: ${meal.latitude}, ${meal.longitude}`);
    });
  });
}
```

### Creating Routes on Map

```javascript
// Extract all unique locations for the day
function getLocationsForDay(dayPlan) {
  return dayPlan.meals.map((meal, index) => ({
    position: { lat: meal.latitude, lng: meal.longitude },
    title: `${meal.meal_type} - ${meal.place_name}`,
    order: index + 1,
    placeId: meal.place_id
  }));
}

// Use with Google Maps, Mapbox, etc.
const day1Locations = getLocationsForDay(data.daily_plans[0]);

// Draw route connecting breakfast → lunch → dinner
day1Locations.forEach(location => {
  // Add marker to map
  // Draw polyline between consecutive locations
});
```

### Calculating Total Distance

```javascript
// Get all locations in visit order for a day
function getDailyRoute(dayPlan) {
  return dayPlan.meals.map(meal => ({
    lat: meal.latitude,
    lng: meal.longitude,
    name: meal.place_name,
    type: meal.meal_type
  }));
}

const route = getDailyRoute(data.daily_plans[0]);
// Use route with distance calculation API
```

---

## Notes

- All coordinates use WGS84 decimal format (standard for GPS)
- Coordinates are accurate to ~0.11 meters (6 decimal places)
- Meal types are always lowercase: "breakfast", "lunch", "dinner"
- Prices are in local currency (pesos)
- Discounts are automatically applied when available
- Response time: 40-60 seconds for first request (AI is planning)

---

## Related Endpoints

- `GET /api/getPlaces` - Get all available places
- `GET /api/getPlacesProducts` - Get products with prices
- `GET /api/getDiscountsByDayFlat/{day}` - Get discounts for specific day
- `GET /api/getPlacesOpenNow` - Get currently open places
