<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DiscountsSeeder extends Seeder
{
    /**
     * Seed student-friendly discounts for Tec area
     */
    public function run(): void
    {
        // Get place IDs (assuming places already exist)
        $dominosPizza = DB::table('places')->where('name', 'Dominos Pizza Garza Sada')->first();
        $tacosLeal = DB::table('places')->where('name', 'Tacos Leal Tec')->first();
        $starbucks = DB::table('places')->where('name', 'Starbucks Tec de Monterrey')->first();
        $littleCaesars = DB::table('places')->where('name', 'Little Caesars Tec')->first();

        // Get category IDs
        $pizzaCategory = DB::table('categories')->where('name', 'Pizza & Italiana')->first();
        $tacosCategory = DB::table('categories')->where('name', 'Tacos & Antojitos')->first();
        $cafeCategory = DB::table('categories')->where('name', 'Café & Bebidas')->first();

        if (!$dominosPizza || !$tacosLeal || !$starbucks || !$littleCaesars) {
            echo "❌ Error: Some places not found. Please run TecAreaSeeder first.\n";
            return;
        }

        // Student discount at Dominos (Monday-Thursday) - 30% OFF Pizza Mediana
        $dominosDiscount = DB::table('discounts')->insertGetId([
            'title' => 'Descuento Estudiante 30% OFF',
            'description' => 'Obtén 30% de descuento en Pizza Mediana de Lunes a Jueves. Válido de 12pm a 10pm. ¡Perfecto para estudiantes del Tec!',
            'image_url' => null,
            'category_id' => $pizzaCategory?->id,
            'place_id' => $dominosPizza->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Schedule: Monday to Thursday, 12pm-10pm
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
            'title' => '2x1 Martes de Tacos',
            'description' => '¡Martes de Tacos! Compra una orden de 5 tacos y lleva otra GRATIS. Válido todo el martes de 12pm a 11pm.',
            'image_url' => null,
            'category_id' => $tacosCategory?->id,
            'place_id' => $tacosLeal->id,
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

        // Happy Hour at Starbucks (3pm-6pm weekdays)
        $starbucksDiscount = DB::table('discounts')->insertGetId([
            'title' => 'Happy Hour 20% OFF',
            'description' => 'Happy Hour de Lunes a Viernes: 20% de descuento en Frappés de 3pm a 6pm. ¡La mejor hora para tu café!',
            'image_url' => null,
            'category_id' => $cafeCategory?->id,
            'place_id' => $starbucks->id,
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

        // Weekend special at Little Caesars (Friday-Sunday)
        $caesarsDiscount = DB::table('discounts')->insertGetId([
            'title' => 'Fin de Semana 25% OFF',
            'description' => '¡Especial de fin de semana! 25% de descuento en Pizza Mediana. Viernes desde las 5pm, Sábado y Domingo todo el día.',
            'image_url' => null,
            'category_id' => $pizzaCategory?->id,
            'place_id' => $littleCaesars->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Friday 5pm-11pm
        DB::table('discount_schedules')->insert([
            'discount_id' => $caesarsDiscount,
            'day_of_week' => 5,
            'start_time' => '17:00:00',
            'end_time' => '23:00:00',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Saturday all day
        DB::table('discount_schedules')->insert([
            'discount_id' => $caesarsDiscount,
            'day_of_week' => 6,
            'start_time' => '12:00:00',
            'end_time' => '23:00:00',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Sunday all day
        DB::table('discount_schedules')->insert([
            'discount_id' => $caesarsDiscount,
            'day_of_week' => 0,
            'start_time' => '12:00:00',
            'end_time' => '23:00:00',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        echo "\n✅ Successfully seeded 4 student-friendly discounts:\n";
        echo "   - Dominos: 30% OFF (Mon-Thu)\n";
        echo "   - Tacos Leal: 2x1 (Tuesdays)\n";
        echo "   - Starbucks: 20% Happy Hour (Mon-Fri 3-6pm)\n";
        echo "   - Little Caesars: 25% OFF (Weekends)\n\n";
    }
}
