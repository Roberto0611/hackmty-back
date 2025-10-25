<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TecAreaSeeder extends Seeder
{
    /**
     * Seed the database with real places near Tec de Monterrey
     * Focus on student-friendly, budget options
     */
    public function run(): void
    {
        // Clear existing data

        // ==================== CATEGORIES ====================
        $categories = [
            'Tacos & Antojitos' => DB::table('categories')->insertGetId([
                'name' => 'Tacos & Antojitos',
                'created_at' => now(),
                'updated_at' => now(),
            ]),
            'Tortas & Sandwiches' => DB::table('categories')->insertGetId([
                'name' => 'Tortas & Sandwiches',
                'created_at' => now(),
                'updated_at' => now(),
            ]),
            'Pizza & Italiana' => DB::table('categories')->insertGetId([
                'name' => 'Pizza & Italiana',
                'created_at' => now(),
                'updated_at' => now(),
            ]),
            'Hamburguesas' => DB::table('categories')->insertGetId([
                'name' => 'Hamburguesas',
                'created_at' => now(),
                'updated_at' => now(),
            ]),
            'Comida China/Oriental' => DB::table('categories')->insertGetId([
                'name' => 'Comida China/Oriental',
                'created_at' => now(),
                'updated_at' => now(),
            ]),
            'Desayunos' => DB::table('categories')->insertGetId([
                'name' => 'Desayunos',
                'created_at' => now(),
                'updated_at' => now(),
            ]),
            'Café & Bebidas' => DB::table('categories')->insertGetId([
                'name' => 'Café & Bebidas',
                'created_at' => now(),
                'updated_at' => now(),
            ]),
            'Refrescos & Jugos' => DB::table('categories')->insertGetId([
                'name' => 'Refrescos & Jugos',
                'created_at' => now(),
                'updated_at' => now(),
            ]),
            'Snacks & Botanas' => DB::table('categories')->insertGetId([
                'name' => 'Snacks & Botanas',
                'created_at' => now(),
                'updated_at' => now(),
            ]),
            'Postres' => DB::table('categories')->insertGetId([
                'name' => 'Postres',
                'created_at' => now(),
                'updated_at' => now(),
            ]),
        ];

        // ==================== PRODUCTS ====================
        $products = [
            // Tacos & Antojitos
            'Taco de Pastor' => DB::table('products')->insertGetId([
                'name' => 'Taco de Pastor',
                'category_id' => $categories['Tacos & Antojitos'],
                'image_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]),
            'Taco de Asada' => DB::table('products')->insertGetId([
                'name' => 'Taco de Asada',
                'category_id' => $categories['Tacos & Antojitos'],
                'image_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]),
            'Taco de Trompo' => DB::table('products')->insertGetId([
                'name' => 'Taco de Trompo',
                'category_id' => $categories['Tacos & Antojitos'],
                'image_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]),
            'Orden de Tacos (5)' => DB::table('products')->insertGetId([
                'name' => 'Orden de Tacos (5)',
                'category_id' => $categories['Tacos & Antojitos'],
                'image_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]),
            'Quesadilla' => DB::table('products')->insertGetId([
                'name' => 'Quesadilla',
                'category_id' => $categories['Tacos & Antojitos'],
                'image_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]),
            'Gringa' => DB::table('products')->insertGetId([
                'name' => 'Gringa',
                'category_id' => $categories['Tacos & Antojitos'],
                'image_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]),

            // Tortas & Sandwiches
            'Torta de Milanesa' => DB::table('products')->insertGetId([
                'name' => 'Torta de Milanesa',
                'category_id' => $categories['Tortas & Sandwiches'],
                'image_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]),
            'Torta de Jamón' => DB::table('products')->insertGetId([
                'name' => 'Torta de Jamón',
                'category_id' => $categories['Tortas & Sandwiches'],
                'image_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]),
            'Sandwich Club' => DB::table('products')->insertGetId([
                'name' => 'Sandwich Club',
                'category_id' => $categories['Tortas & Sandwiches'],
                'image_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]),

            // Pizza
            'Pizza Personal' => DB::table('products')->insertGetId([
                'name' => 'Pizza Personal',
                'category_id' => $categories['Pizza & Italiana'],
                'image_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]),
            'Pizza Mediana' => DB::table('products')->insertGetId([
                'name' => 'Pizza Mediana',
                'category_id' => $categories['Pizza & Italiana'],
                'image_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]),
            'Pasta Alfredo' => DB::table('products')->insertGetId([
                'name' => 'Pasta Alfredo',
                'category_id' => $categories['Pizza & Italiana'],
                'image_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]),

            // Hamburguesas
            'Hamburguesa Sencilla' => DB::table('products')->insertGetId([
                'name' => 'Hamburguesa Sencilla',
                'category_id' => $categories['Hamburguesas'],
                'image_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]),
            'Hamburguesa con Queso' => DB::table('products')->insertGetId([
                'name' => 'Hamburguesa con Queso',
                'category_id' => $categories['Hamburguesas'],
                'image_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]),

            // Comida Oriental
            'Arroz Frito' => DB::table('products')->insertGetId([
                'name' => 'Arroz Frito',
                'category_id' => $categories['Comida China/Oriental'],
                'image_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]),
            'Chop Suey' => DB::table('products')->insertGetId([
                'name' => 'Chop Suey',
                'category_id' => $categories['Comida China/Oriental'],
                'image_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]),
            'Sushi Roll (8 pzas)' => DB::table('products')->insertGetId([
                'name' => 'Sushi Roll (8 pzas)',
                'category_id' => $categories['Comida China/Oriental'],
                'image_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]),

            // Desayunos
            'Huevos Rancheros' => DB::table('products')->insertGetId([
                'name' => 'Huevos Rancheros',
                'category_id' => $categories['Desayunos'],
                'image_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]),
            'Chilaquiles' => DB::table('products')->insertGetId([
                'name' => 'Chilaquiles',
                'category_id' => $categories['Desayunos'],
                'image_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]),
            'Hot Cakes' => DB::table('products')->insertGetId([
                'name' => 'Hot Cakes',
                'category_id' => $categories['Desayunos'],
                'image_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]),
            'Molletes' => DB::table('products')->insertGetId([
                'name' => 'Molletes',
                'category_id' => $categories['Desayunos'],
                'image_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]),

            // Café & Bebidas
            'Café Americano' => DB::table('products')->insertGetId([
                'name' => 'Café Americano',
                'category_id' => $categories['Café & Bebidas'],
                'image_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]),
            'Café Latte' => DB::table('products')->insertGetId([
                'name' => 'Café Latte',
                'category_id' => $categories['Café & Bebidas'],
                'image_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]),
            'Cappuccino' => DB::table('products')->insertGetId([
                'name' => 'Cappuccino',
                'category_id' => $categories['Café & Bebidas'],
                'image_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]),
            'Frappé' => DB::table('products')->insertGetId([
                'name' => 'Frappé',
                'category_id' => $categories['Café & Bebidas'],
                'image_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]),

            // Refrescos
            'Coca-Cola 600ml' => DB::table('products')->insertGetId([
                'name' => 'Coca-Cola 600ml',
                'category_id' => $categories['Refrescos & Jugos'],
                'image_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]),
            'Agua Natural' => DB::table('products')->insertGetId([
                'name' => 'Agua Natural',
                'category_id' => $categories['Refrescos & Jugos'],
                'image_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]),
            'Agua de Sabor' => DB::table('products')->insertGetId([
                'name' => 'Agua de Sabor',
                'category_id' => $categories['Refrescos & Jugos'],
                'image_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]),
            'Jugo Natural' => DB::table('products')->insertGetId([
                'name' => 'Jugo Natural',
                'category_id' => $categories['Refrescos & Jugos'],
                'image_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]),

            // Snacks
            'Papas Fritas' => DB::table('products')->insertGetId([
                'name' => 'Papas Fritas',
                'category_id' => $categories['Snacks & Botanas'],
                'image_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]),
            'Nachos con Queso' => DB::table('products')->insertGetId([
                'name' => 'Nachos con Queso',
                'category_id' => $categories['Snacks & Botanas'],
                'image_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]),

            // Postres
            'Pay de Manzana' => DB::table('products')->insertGetId([
                'name' => 'Pay de Manzana',
                'category_id' => $categories['Postres'],
                'image_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]),
            'Brownie' => DB::table('products')->insertGetId([
                'name' => 'Brownie',
                'category_id' => $categories['Postres'],
                'image_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]),
        ];

        // ==================== REAL PLACES NEAR TEC DE MONTERREY ====================
        // Using EXACT coordinates from Google Maps for accurate location data
        
        $places = [
            // OXXO Stores (Convenience stores - verified locations near Tec)
            'OXXO Eugenio Garza Sada' => DB::table('places')->insertGetId([
                'name' => 'OXXO Eugenio Garza Sada',
                'latitude' => 25.655436139112144, 
                'longitude' => -100.29420939183368,
                'image_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]),
            'OXXO Aulas 4' => DB::table('places')->insertGetId([
                'name' => 'OXXO Aulas 4',
                'latitude' => 25.649476165342023, 
                'longitude' => -100.28943269767414,
                'image_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]),
            'OXXO Lirios' => DB::table('places')->insertGetId([
                'name' => 'OXXO Lirios',
                'latitude' => 25.653837980169822, 
                'longitude' => -100.28960972337393,
                'image_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]),

            // 7-Eleven (Verified locations)
            '7-Eleven Plaza Tecnológico' => DB::table('places')->insertGetId([
                'name' => '7-Eleven Plaza Tecnológico',
                'latitude' => 25.6510982914529, 
                'longitude' => -100.29255516811033,
                'image_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]),

            // Coffee Shops (Exact coordinates from Google Maps)
            'Starbucks Tec de Monterrey' => DB::table('places')->insertGetId([
                'name' => 'Starbucks Tec de Monterrey',
                'latitude' => 25.65245712594979,
                'longitude' => -100.29002048069506,
                'image_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]),
            'Starbucks BiblioTec' => DB::table('places')->insertGetId([
                'name' => 'Starbucks BiblioTec',
                'latitude' => 25.65024720364488,
                'longitude' => -100.28965574093868,
                'image_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]),
            'Tim Hortons Tec' => DB::table('places')->insertGetId([
                'name' => 'Tim Hortons Tec',
                'latitude' => 25.650353497747602, 
                'longitude' => -100.2898055826362,
                'image_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]),
            'Tim Hortons CIAP' => DB::table('places')->insertGetId([
                'name' => 'Tim Hortons CIAP',
                'latitude' => 25.652804425616587,
                'longitude' => -100.28984495597815,
                'image_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]),

            // Pizza Chains
            'Dominos Pizza Garza Sada' => DB::table('places')->insertGetId([
                'name' => 'Dominos Pizza Garza Sada',
                'latitude' => 25.645575320295308, 
                'longitude' => -100.28835967434095,
                'image_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]),
            'Little Caesars Tec' => DB::table('places')->insertGetId([
                'name' => 'Little Caesars Tec',
                'latitude' => 25.651603193898787, 
                'longitude' => -100.288879352388,
                'image_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]),

            // Burger Chains
            'Burger King Tec' => DB::table('places')->insertGetId([
                'name' => 'Burger King Tec',
                'latitude' => 25.645918916006416, 
                'longitude' => -100.28852133564587,
                'image_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]),
            'Carl\'s Jr. Tec' => DB::table('places')->insertGetId([
                'name' => 'Carl\'s Jr. Tec',
                'latitude' => 25.651577444856432, 
                'longitude' => -100.28904570749397,
                'image_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]),
            'McDonald\'s Garza Sada' => DB::table('places')->insertGetId([
                'name' => 'McDonald\'s Garza Sada',
                'latitude' => 25.643147962387616, 
                'longitude' => -100.28758156831432,
                'image_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]),
            'KFC Paseo Tec' => DB::table('places')->insertGetId([
                'name' => 'KFC Paseo Tec',
                'latitude' => 25.65465094777046, 
                'longitude' => -100.29316079437311,
                'image_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]),

            // Subway
            'Subway Tec' => DB::table('places')->insertGetId([
                'name' => 'Subway Tec',
                'latitude' => 25.65135116605649, 
                'longitude' => -100.28877964169872,
                'image_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]),

            // Asian Food
            'The Sushi Boys Paseo Tec' => DB::table('places')->insertGetId([
                'name' => 'The Sushi Boys Paseo Tec',
                'latitude' => 25.653296298573718, 
                'longitude' => -100.29458998049452,
                'image_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]),

            // Local Taquerías (Approximate but realistic coordinates near Tec)
            'Tacos Leal Tec' => DB::table('places')->insertGetId([
                'name' => 'Tacos Leal Tec',
                'latitude' => 25.645980188467348, 
                'longitude' => -100.28931344752387,
                'image_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]),
            'Tacos Villa de Santiago Tec' => DB::table('places')->insertGetId([
                'name' => 'Tacos Villa de Santiago Tec',
                'latitude' => 25.645433725416503, 
                'longitude' => -100.28822983503201,
                'image_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]),
            'Teo Tacos Tecnológico' => DB::table('places')->insertGetId([
                'name' => 'Teo Tacos Tecnológico',
                'latitude' => 25.64911674239945, 
                'longitude' => -100.28837862996912,
                'image_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]),

            // Tortas
            'Tortas Alex Garza Sada' => DB::table('places')->insertGetId([
                'name' => 'Tortas Alex Garza Sada',
                'latitude' => 25.629365244374195, 
                'longitude' => -100.27784296765782,
                'image_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]),

            // Other
            'Toshi Tiger' => DB::table('places')->insertGetId([
                'name' => 'Toshi Tiger',
                'latitude' => 25.64903826318998, 
                'longitude' => -100.28851816192126,
                'image_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]),
            'Wings Army Paseo Tec' => DB::table('places')->insertGetId([
                'name' => 'Wings Army Paseo Tec',
                'latitude' => 25.654583353440373, 
                'longitude' => -100.2936601846173,
                'image_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]),
        ];

        // ==================== PLACE SCHEDULES ====================
        // Most places open 7am-11pm, some 24hrs
        foreach ($places as $placeName => $placeId) {
            // Most stores/restaurants
            if (strpos($placeName, 'OXXO') !== false || strpos($placeName, '7-Eleven') !== false) {
                // 24 hours for convenience stores
                for ($day = 0; $day <= 6; $day++) {
                    DB::table('place_schedules')->insert([
                        'place_id' => $placeId,
                        'day_of_week' => $day,
                        'open_time' => '00:00:00',
                        'close_time' => '23:59:59',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            } else {
                // Regular hours for restaurants (7am-11pm)
                for ($day = 0; $day <= 6; $day++) {
                    DB::table('place_schedules')->insert([
                        'place_id' => $placeId,
                        'day_of_week' => $day,
                        'open_time' => '07:00:00',
                        'close_time' => '23:00:00',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        // ==================== PLACES-PRODUCTS (Prices) ====================
        // Student-friendly pricing: 12-80 pesos
        
        // OXXO - Snacks and drinks
        $oxxoProducts = [
            ['product' => 'Coca-Cola 600ml', 'price' => 18],
            ['product' => 'Agua Natural', 'price' => 12],
            ['product' => 'Papas Fritas', 'price' => 15],
            ['product' => 'Nachos con Queso', 'price' => 25],
            ['product' => 'Sandwich Club', 'price' => 35],
        ];
        foreach (['OXXO Eugenio Garza Sada', 'OXXO Aulas 4', 'OXXO Lirios'] as $oxxo) {
            foreach ($oxxoProducts as $item) {
                DB::table('places_products')->insert([
                    'place_id' => $places[$oxxo],
                    'product_id' => $products[$item['product']],
                    'price' => $item['price'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // 7-Eleven - Similar to OXXO
        foreach ($oxxoProducts as $item) {
            DB::table('places_products')->insert([
                'place_id' => $places['7-Eleven Plaza Tecnológico'],
                'product_id' => $products[$item['product']],
                'price' => $item['price'] + 2, // Slightly more expensive
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Tacos Leal Tec
        $tacosProducts = [
            ['product' => 'Taco de Pastor', 'price' => 15],
            ['product' => 'Taco de Asada', 'price' => 17],
            ['product' => 'Orden de Tacos (5)', 'price' => 70],
            ['product' => 'Quesadilla', 'price' => 25],
            ['product' => 'Gringa', 'price' => 35],
            ['product' => 'Agua de Sabor', 'price' => 15],
            ['product' => 'Coca-Cola 600ml', 'price' => 20],
        ];
        foreach ($tacosProducts as $item) {
            DB::table('places_products')->insert([
                'place_id' => $places['Tacos Leal Tec'],
                'product_id' => $products[$item['product']],
                'price' => $item['price'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Tacos Villa de Santiago Tec - Slightly different prices
        foreach ($tacosProducts as $item) {
            DB::table('places_products')->insert([
                'place_id' => $places['Tacos Villa de Santiago Tec'],
                'product_id' => $products[$item['product']],
                'price' => $item['price'] + 2,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Teo Tacos Tecnológico
        $trompoProducts = [
            ['product' => 'Taco de Trompo', 'price' => 14],
            ['product' => 'Orden de Tacos (5)', 'price' => 65],
            ['product' => 'Gringa', 'price' => 32],
            ['product' => 'Agua de Sabor', 'price' => 12],
        ];
        foreach ($trompoProducts as $item) {
            DB::table('places_products')->insert([
                'place_id' => $places['Teo Tacos Tecnológico'],
                'product_id' => $products[$item['product']],
                'price' => $item['price'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Tortas Alex Garza Sada
        $tortasProducts = [
            ['product' => 'Torta de Milanesa', 'price' => 45],
            ['product' => 'Torta de Jamón', 'price' => 35],
            ['product' => 'Papas Fritas', 'price' => 20],
            ['product' => 'Coca-Cola 600ml', 'price' => 18],
        ];
        foreach ($tortasProducts as $item) {
            DB::table('places_products')->insert([
                'place_id' => $places['Tortas Alex Garza Sada'],
                'product_id' => $products[$item['product']],
                'price' => $item['price'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Starbucks
        $starbucksProducts = [
            ['product' => 'Café Americano', 'price' => 45],
            ['product' => 'Café Latte', 'price' => 55],
            ['product' => 'Cappuccino', 'price' => 52],
            ['product' => 'Frappé', 'price' => 65],
            ['product' => 'Brownie', 'price' => 40],
        ];
        foreach (['Starbucks Tec de Monterrey', 'Starbucks BiblioTec'] as $starbucks) {
            foreach ($starbucksProducts as $item) {
                DB::table('places_products')->insert([
                    'place_id' => $places[$starbucks],
                    'product_id' => $products[$item['product']],
                    'price' => $item['price'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Tim Hortons - Similar to Starbucks but cheaper
        foreach (['Tim Hortons Tec', 'Tim Hortons CIAP'] as $timHortons) {
            foreach ($starbucksProducts as $item) {
                DB::table('places_products')->insert([
                    'place_id' => $places[$timHortons],
                    'product_id' => $products[$item['product']],
                    'price' => $item['price'] - 10,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Dominos Pizza
        $dominosProducts = [
            ['product' => 'Pizza Personal', 'price' => 75],
            ['product' => 'Pizza Mediana', 'price' => 159],
            ['product' => 'Coca-Cola 600ml', 'price' => 20],
        ];
        foreach ($dominosProducts as $item) {
            DB::table('places_products')->insert([
                'place_id' => $places['Dominos Pizza Garza Sada'],
                'product_id' => $products[$item['product']],
                'price' => $item['price'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Little Caesars
        $caesarsProducts = [
            ['product' => 'Pizza Personal', 'price' => 69],
            ['product' => 'Pizza Mediana', 'price' => 119],
            ['product' => 'Coca-Cola 600ml', 'price' => 18],
        ];
        foreach ($caesarsProducts as $item) {
            DB::table('places_products')->insert([
                'place_id' => $places['Little Caesars Tec'],
                'product_id' => $products[$item['product']],
                'price' => $item['price'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Burger King
        $burgerKingProducts = [
            ['product' => 'Hamburguesa Sencilla', 'price' => 45],
            ['product' => 'Hamburguesa con Queso', 'price' => 55],
            ['product' => 'Papas Fritas', 'price' => 25],
            ['product' => 'Coca-Cola 600ml', 'price' => 22],
        ];
        foreach ($burgerKingProducts as $item) {
            DB::table('places_products')->insert([
                'place_id' => $places['Burger King Tec'],
                'product_id' => $products[$item['product']],
                'price' => $item['price'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Carl's Jr
        foreach ($burgerKingProducts as $item) {
            DB::table('places_products')->insert([
                'place_id' => $places['Carl\'s Jr. Tec'],
                'product_id' => $products[$item['product']],
                'price' => $item['price'] + 5,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // McDonald's
        $mcdonaldsProducts = [
            ['product' => 'Hamburguesa Sencilla', 'price' => 42],
            ['product' => 'Hamburguesa con Queso', 'price' => 52],
            ['product' => 'Papas Fritas', 'price' => 28],
            ['product' => 'Coca-Cola 600ml', 'price' => 20],
            ['product' => 'Hot Cakes', 'price' => 35],
        ];
        foreach ($mcdonaldsProducts as $item) {
            DB::table('places_products')->insert([
                'place_id' => $places['McDonald\'s Garza Sada'],
                'product_id' => $products[$item['product']],
                'price' => $item['price'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Subway
        $subwayProducts = [
            ['product' => 'Sandwich Club', 'price' => 65],
            ['product' => 'Papas Fritas', 'price' => 22],
            ['product' => 'Coca-Cola 600ml', 'price' => 20],
        ];
        foreach ($subwayProducts as $item) {
            DB::table('places_products')->insert([
                'place_id' => $places['Subway Tec'],
                'product_id' => $products[$item['product']],
                'price' => $item['price'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // The Sushi Boys Paseo Tec
        $sushiittoProducts = [
            ['product' => 'Sushi Roll (8 pzas)', 'price' => 89],
            ['product' => 'Agua Natural', 'price' => 15],
        ];
        foreach ($sushiittoProducts as $item) {
            DB::table('places_products')->insert([
                'place_id' => $places['The Sushi Boys Paseo Tec'],
                'product_id' => $products[$item['product']],
                'price' => $item['price'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Toshi Tiger
        $chinaProducts = [
            ['product' => 'Arroz Frito', 'price' => 55],
            ['product' => 'Chop Suey', 'price' => 65],
            ['product' => 'Coca-Cola 600ml', 'price' => 18],
        ];
        foreach ($chinaProducts as $item) {
            DB::table('places_products')->insert([
                'place_id' => $places['Toshi Tiger'],
                'product_id' => $products[$item['product']],
                'price' => $item['price'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // KFC
        $kfcProducts = [
            ['product' => 'Papas Fritas', 'price' => 30],
            ['product' => 'Coca-Cola 600ml', 'price' => 22],
        ];
        foreach ($kfcProducts as $item) {
            DB::table('places_products')->insert([
                'place_id' => $places['KFC Paseo Tec'],
                'product_id' => $products[$item['product']],
                'price' => $item['price'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Wings Army
        $wingsProducts = [
            ['product' => 'Papas Fritas', 'price' => 35],
            ['product' => 'Nachos con Queso', 'price' => 45],
            ['product' => 'Coca-Cola 600ml', 'price' => 20],
        ];
        foreach ($wingsProducts as $item) {
            DB::table('places_products')->insert([
                'place_id' => $places['Wings Army Paseo Tec'],
                'product_id' => $products[$item['product']],
                'price' => $item['price'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ==================== DISCOUNTS ====================
        // Student-friendly discounts

        // Student discount at Dominos (Monday-Thursday)
        $dominosDiscount = DB::table('discounts')->insertGetId([
            'place_id' => $places['Dominos Pizza Garza Sada'],
            'product_id' => $products['Pizza Mediana'],
            'discount_percentage' => 30.0,
            'description' => 'Descuento estudiante 30% - Lunes a Jueves',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        for ($day = 1; $day <= 4; $day++) { // Mon-Thu
            DB::table('discount_schedules')->insert([
                'discount_id' => $dominosDiscount,
                'day_of_week' => $day,
                'start_time' => '12:00:00',
                'end_time' => '22:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 2x1 Tacos on Tuesdays
        $tacosDiscount = DB::table('discounts')->insertGetId([
            'place_id' => $places['Tacos Leal Tec'],
            'product_id' => $products['Orden de Tacos (5)'],
            'discount_percentage' => 50.0,
            'description' => '2x1 en órdenes - Martes de Tacos',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('discount_schedules')->insert([
            'discount_id' => $tacosDiscount,
            'day_of_week' => 2, // Tuesday
            'start_time' => '12:00:00',
            'end_time' => '23:00:00',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Happy Hour at Starbucks (3pm-6pm)
        $starbucksDiscount = DB::table('discounts')->insertGetId([
            'place_id' => $places['Starbucks Tec de Monterrey'],
            'product_id' => $products['Frappé'],
            'discount_percentage' => 20.0,
            'description' => 'Happy Hour 20% - 3pm a 6pm',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        for ($day = 1; $day <= 5; $day++) { // Mon-Fri
            DB::table('discount_schedules')->insert([
                'discount_id' => $starbucksDiscount,
                'day_of_week' => $day,
                'start_time' => '15:00:00',
                'end_time' => '18:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Weekend special at Little Caesars
        $caesarsDiscount = DB::table('discounts')->insertGetId([
            'place_id' => $places['Little Caesars Tec'],
            'product_id' => $products['Pizza Mediana'],
            'discount_percentage' => 25.0,
            'description' => 'Fin de semana 25% OFF',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('discount_schedules')->insert([
            'discount_id' => $caesarsDiscount,
            'day_of_week' => 5, // Friday
            'start_time' => '17:00:00',
            'end_time' => '23:00:00',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('discount_schedules')->insert([
            'discount_id' => $caesarsDiscount,
            'day_of_week' => 6, // Saturday
            'start_time' => '12:00:00',
            'end_time' => '23:00:00',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('discount_schedules')->insert([
            'discount_id' => $caesarsDiscount,
            'day_of_week' => 0, // Sunday
            'start_time' => '12:00:00',
            'end_time' => '23:00:00',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        echo "\n✓ Seeded " . count($categories) . " categories\n";
        echo "✓ Seeded " . count($products) . " products\n";
        echo "✓ Seeded " . count($places) . " real places near Tec de Monterrey\n";
        echo "✓ Seeded place schedules\n";
        echo "✓ Seeded product prices for all locations\n";
        echo "✓ Seeded student-friendly discounts\n";
        echo "\n✅ Database populated successfully with real Tec area data!\n\n";
    }
}
