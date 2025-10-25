<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class mainSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cat1 = DB::table('categories')->insertGetId([
            'name' => 'Full Meals',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $cat2 = DB::table('categories')->insertGetId([
            'name' => 'Soft Drinks',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $prod1 = DB::table('products')->insertGetId([
            'name' => 'Leche Entera',
            'category_id' => $cat1,
            'image_url' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $prod2 = DB::table('products')->insertGetId([
            'name' => 'Coca-Cola',
            'category_id' => $cat2,
            'image_url' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $place1 = DB::table('places')->insertGetId([
            'name' => 'JPizza',
            'latitude' => 25.6789,
            'longitude' => -100.1234,
            'image_url' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $place2 = DB::table('places')->insertGetId([
            'name' => 'Fer Maid Cafe',
            'latitude' => 25.6799,
            'longitude' => -100.1244,
            'image_url' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('place_schedules')->insert([
            [
                'place_id' => $place1,
                'day_of_week' => 5,
                'open_time' => '08:00:00',
                'close_time' => '22:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'place_id' => $place2,
                'day_of_week' => 1,
                'open_time' => '07:30:00',
                'close_time' => '20:30:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $disc1 = DB::table('discounts')->insertGetId([
            'title' => 'Family Pizza Combo',
            'description' => 'Family Pizza and Soft Drink Combo por $150 on MondaYs',
            'image_url' => null,
            'category_id' => $cat1,
            'place_id' => $place1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $disc2 = DB::table('discounts')->insertGetId([
            'title' => 'Students 15% off',
            'description' => 'Student from ITESM get 15% off on all menu items from 12 to 6pm on Tuesdays',
            'image_url' => null,
            'category_id' => $cat1,
            'place_id' => $place2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('discount_schedules')->insert([
            [
                'discount_id' => $disc1,
                'day_of_week' => 1,
                'start_time' => '08:00:00',
                'end_time' => '22:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'discount_id' => $disc2,
                'day_of_week' => 2,
                'start_time' => '12:00:00',
                'end_time' => '18:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::table('places_products')->insert([
            [
                'place_id' => $place1,
                'product_id' => $prod1,
                'price' => 40,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'place_id' => $place2,
                'product_id' => $prod2,
                'price' => 15,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $user1 = DB::table('users')->insertGetId([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $user2 = DB::table('users')->insertGetId([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('secret456'),
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('votes_tables')->insert([
            [
                'user_id' => $user1,
                'votable_type' => 'discount',
                'votable_id' => $disc1,
                'vote_value' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $user2,
                'votable_type' => 'product',
                'votable_id' => $prod2,
                'vote_value' => -300,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
