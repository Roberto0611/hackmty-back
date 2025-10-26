# 🍔 HackMTY - Smart Food Discovery Platform

> Connecting students with affordable food options and discounts near Tec de Monterrey

![Laravel](https://img.shields.io/badge/Laravel-12.0-FF2D20?style=flat&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?style=flat&logo=php)
![JWT](https://img.shields.io/badge/JWT-Auth-000000?style=flat&logo=jsonwebtokens)
![AI](https://img.shields.io/badge/Gemini-AI-4285F4?style=flat&logo=google)

## 📋 Table of Contents

- [About the Project](#about-the-project)
- [Social Impact](#social-impact)
- [Tech Stack](#tech-stack)
- [Features](#features)
- [API Documentation](#api-documentation)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
- [Database Schema](#database-schema)
- [Contributing](#contributing)

---

## 🎯 About the Project

**HackMTY Backend** is a RESTful API that helps students discover affordable food options, compare prices, find discounts, and get AI-powered meal plans based on their budget. Built during HackMTY hackathon, this platform addresses food insecurity and budget constraints faced by university students.

### The Problem

- Students struggle to find affordable food options near campus
- Limited visibility of available discounts and promotions
- Difficulty planning meals within tight budgets
- Time wasted comparing prices across multiple locations

### Our Solution

A smart platform that:
- **Aggregates** food options from multiple places near campus
- **Displays** real-time discounts with schedules
- **Compares** prices across different locations
- **Generates** AI-powered meal plans within budget constraints
- **Provides** location-based filtering with operating hours

---

## 🌍 Social Impact

### Addressing Food Insecurity
- **30-40%** of university students experience food insecurity
- Our platform helps students maximize their food budget
- Promotes access to nutritious, affordable meals

### Economic Benefits
- Students save money by finding the best prices and discounts
- Local businesses gain visibility and increased foot traffic
- Supports the local economy around campus

### Health & Wellness
- AI meal planner considers health levels (1-5 scale)
- Encourages balanced meal planning
- Reduces stress around meal decisions and budgeting

### Sustainability
- Reduces food waste by promoting planned purchases
- Encourages local consumption
- Optimizes resource allocation for students

---

## 🛠️ Tech Stack

### Backend Framework
- **Laravel 12** - Modern PHP framework with elegant syntax
- **PHP 8.2** - Latest PHP version with performance improvements

### Authentication & Security
- **JWT (JSON Web Tokens)** - Stateless authentication via `php-open-source-saver/jwt-auth`
- **Laravel Sanctum** - API token management
- **Bcrypt** - Password hashing

### Database
- **MySQL/PostgreSQL** - Relational database with spatial data support
- **Laravel Migrations** - Version-controlled database schema
- **Eloquent ORM** - Intuitive database interactions

### AI & Machine Learning
- **Google Gemini 1.5 Pro** - Function calling for intelligent meal planning
- **Natural Language Processing** - Understanding user preferences and constraints
- **Multi-agent AI** - Parallel data fetching and optimization

### Cloud Services
- **AWS S3** - Image storage with CDN distribution
- **Flysystem** - Abstracted cloud storage interface

### Development Tools
- **Composer** - Dependency management
- **Pest PHP** - Modern testing framework
- **Laravel Pint** - Code style enforcement
- **Git** - Version control

### API & Integration
- **RESTful API** - Standard HTTP methods
- **CORS** - Cross-origin resource sharing configured
- **JSON** - Data exchange format

---

## ✨ Features

### 🔐 Authentication System
- User registration with validation
- Secure JWT-based login
- Token refresh and invalidation
- Protected routes with middleware

### 🏪 Place Management
- Restaurant/food location catalog
- Geographic coordinates (latitude/longitude)
- Operating hours by day of week
- Image storage with AWS S3

### 🍕 Product Catalog
- Categorized food items
- Price comparison across locations
- Product availability tracking
- Image galleries

### 💰 Discount System
- Time-based promotions
- Day-specific discounts
- Category filtering
- Active discount detection

### 🤖 AI Meal Planner
- Budget-aware meal planning
- Flexible duration (1-30 days)
- Health level preferences (1-5 scale)
- Discount optimization
- Multi-location price comparison
- Natural language input

### 👍 Voting System
- Like/dislike products and discounts
- User preference tracking
- Polymorphic vote relationships

---

## 📡 API Documentation

### Base URL
```
http://localhost:8000/api
```

### Authentication Endpoints

#### Register
```http
POST /api/register
Content-Type: application/json

{
  "name": "Juan Pérez",
  "email": "juan@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}

Response 200:
{
  "user": {
    "id": 1,
    "name": "Juan Pérez",
    "email": "juan@example.com"
  },
  "token": "eyJ0eXAiOiJKV1QiLCJhbGc..."
}
```

#### Login
```http
POST /api/login
Content-Type: application/json

{
  "email": "juan@example.com",
  "password": "password123"
}

Response 200:
{
  "user": { ... },
  "token": "eyJ0eXAiOiJKV1QiLCJhbGc..."
}
```

#### Get Current User (Protected)
```http
GET /api/user
Authorization: Bearer {token}

Response 200:
{
  "id": 1,
  "name": "Juan Pérez",
  "email": "juan@example.com",
  "created_at": "2025-10-26T12:00:00Z"
}
```

#### Logout (Protected)
```http
POST /api/logout
Authorization: Bearer {token}

Response 200:
{
  "message": "Successfully logged out"
}
```

#### Verify Token (Protected)
```http
POST /api/verify-token
Authorization: Bearer {token}
Content-Type: application/json

{
  "token": "eyJ0eXAiOiJKV1QiLCJhbGc..."
}

Response 200:
{
  "valid": true,
  "payload": { ... }
}
```

### Data Endpoints (Protected)

#### Get Discounts
```http
GET /api/getDiscounts
Authorization: Bearer {token}

Response 200:
[
  {
    "id": 1,
    "title": "2x1 Tacos",
    "description": "Todos los martes",
    "category": "Comida",
    "place": "Taquería El Buen Taco",
    "schedules": [...]
  }
]
```

#### Get Places
```http
GET /api/getPlaces
Authorization: Bearer {token}

Response 200:
[
  {
    "id": 1,
    "name": "Taquería El Buen Taco",
    "latitude": 25.6515,
    "longitude": -100.2895,
    "image_url": "https://s3.amazonaws.com/..."
  }
]
```

#### Get Products
```http
GET /api/getProducts
Authorization: Bearer {token}

Response 200:
[
  {
    "id": 1,
    "name": "Tacos al Pastor",
    "category": "Comida Mexicana",
    "image_url": "https://s3.amazonaws.com/...",
    "prices": [
      {
        "place": "Taquería El Buen Taco",
        "price": 45
      }
    ]
  }
]
```

### AI Meal Planner

#### Generate Meal Plan
```http
POST /api/generateMealPlan
Content-Type: application/json

{
  "prompt": "I have 500 pesos for 7 days and want healthy meals"
}

Response 200:
{
  "success": true,
  "meal_plan": {
    "days": [
      {
        "day": 1,
        "meals": {
          "breakfast": {
            "item": "Chilaquiles",
            "place": "Café Matutino",
            "price": 65
          },
          "lunch": { ... },
          "dinner": { ... }
        },
        "daily_total": 180
      }
    ],
    "total_cost": 1260,
    "budget": 1500,
    "remaining": 240
  }
}
```

---

## 🚀 Installation

### Prerequisites
- PHP >= 8.2
- Composer
- MySQL/PostgreSQL
- Node.js & npm (optional, for frontend assets)

### Steps

1. **Clone the repository**
```bash
git clone https://github.com/Roberto0611/hackmty-back.git
cd hackmty-back
```

2. **Install dependencies**
```bash
composer install
```

3. **Environment configuration**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configure database** in `.env`
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hackmty
DB_USERNAME=root
DB_PASSWORD=your_password
```

5. **Configure JWT**
```bash
php artisan jwt:secret
```

6. **Run migrations**
```bash
php artisan migrate
```

7. **Seed database** (optional)
```bash
php artisan db:seed
```

8. **Start development server**
```bash
php artisan serve
```

The API will be available at `http://localhost:8000`

---

## ⚙️ Configuration

### JWT Authentication

Edit `config/jwt.php`:
```php
'ttl' => env('JWT_TTL', 60), // Token lifetime in minutes
'refresh_ttl' => env('JWT_REFRESH_TTL', 20160), // Refresh token lifetime
```

### CORS Settings

Edit `config/cors.php`:
```php
'allowed_origins' => ['*'], // Change in production
'supports_credentials' => false,
```

### AWS S3 (Image Storage)

Add to `.env`:
```env
AWS_ACCESS_KEY_ID=your_access_key
AWS_SECRET_ACCESS_KEY=your_secret_key
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=your_bucket_name
```

### Gemini AI

Add to `.env`:
```env
GEMINI_API_KEY=your_gemini_api_key
GEMINI_APP_BASE_URL=http://127.0.0.1:8000/api
```

Get your API key at [Google AI Studio](https://aistudio.google.com/app/apikey)

---

## 📖 Usage

### Testing Authentication

```bash
# Register a new user
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test User",
    "email": "test@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'

# Login
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "test@example.com",
    "password": "password123"
  }'

# Use the token from login response
TOKEN="eyJ0eXAiOiJKV1QiLCJhbGc..."

# Get user data
curl -X GET http://localhost:8000/api/user \
  -H "Authorization: Bearer $TOKEN"
```

### Testing Meal Planner

```bash
# Test with artisan command
php artisan test:meal-planner "I have 400 pesos for 5 days and want healthy meals"

# Or via API
curl -X POST http://localhost:8000/api/generateMealPlan \
  -H "Content-Type: application/json" \
  -d '{
    "prompt": "I have 400 pesos for 5 days and want healthy meals"
  }'
```

### Running Tests

```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --filter=AuthenticationTest
```

---

## 🗄️ Database Schema

### Key Tables

#### users
- User authentication and profile data
- JWT token management

#### places
- Restaurants and food locations
- Geographic coordinates
- Operating schedules

#### products
- Food items catalog
- Category classification
- Pricing by location

#### discounts
- Time-based promotions
- Schedule management
- Category filtering

#### votes
- Polymorphic voting system
- User preferences tracking

### Relationships

```
users 1:N votes
places 1:N discounts
places 1:N place_schedules
places N:M products (via places_products)
discounts 1:N discount_schedules
categories 1:N products
categories 1:N discounts
votes N:1 products (polymorphic)
votes N:1 discounts (polymorphic)
```

For detailed schema, see [database.md](database.md)

---

## 🧪 Testing

### Run Tests
```bash
php artisan test
```

### Test Coverage
- Authentication flows
- JWT token validation
- API endpoints
- Meal planner service

---

## 📦 Project Structure

```
hackmty-back/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   │   └── JWTAuthController.php
│   │   │   ├── DiscountsController.php
│   │   │   ├── placesController.php
│   │   │   ├── ProductsController.php
│   │   │   └── MealPlanController.php
│   │   └── Middleware/
│   ├── Models/
│   │   ├── User.php
│   │   ├── Place.php
│   │   ├── Product.php
│   │   ├── Discount.php
│   │   └── Vote.php
│   └── Services/
│       └── GeminiMealPlannerService.php
├── config/
│   ├── auth.php
│   ├── jwt.php
│   ├── cors.php
│   └── services.php
├── database/
│   ├── migrations/
│   └── seeders/
├── routes/
│   ├── api.php
│   └── web.php
└── tests/
    ├── Feature/
    └── Unit/
```

---

## 🤝 Contributing

We welcome contributions! Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

### Code Style
- Follow PSR-12 coding standards
- Use Laravel best practices
- Write tests for new features

---

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

## 👥 Team

Built with ❤️ during HackMTY 2025

- **Roberto** - [@Roberto0611](https://github.com/Roberto0611)
- **Fernando** - Backend Development

---

## 📞 Support

For questions or issues:
- Open an issue on GitHub
- Review the [JWT_API_GUIDE.md](JWT_API_GUIDE.md) for authentication help
- Check [GEMINI_MEAL_PLANNER.md](GEMINI_MEAL_PLANNER.md) for AI features

---

## 🎓 Acknowledgments

- HackMTY organizers and mentors
- Tec de Monterrey community
- Google Gemini AI platform
- Laravel community

---

## 🔮 Future Enhancements

- [ ] Mobile app integration
- [ ] Real-time discount notifications
- [ ] User reviews and ratings
- [ ] Social sharing features
- [ ] Nutrition information
- [ ] Allergen filtering
- [ ] Payment integration
- [ ] Order ahead functionality
- [ ] Loyalty program
- [ ] Admin dashboard

---

**Made with ❤️ for students, by students**
