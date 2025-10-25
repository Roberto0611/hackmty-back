<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $email = strtolower('fermaurolf@gmail.com');

        \App\Models\User::updateOrCreate(
            ['email' => $email],
            [
                'name' => 'Fermaurolf',
                'email' => $email,
                'password' => \Illuminate\Support\Facades\Hash::make('12345'),
            ]
        );
    }
}
