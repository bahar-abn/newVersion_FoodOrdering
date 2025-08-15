<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\{User, Menu, Discount};

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Admin', 'password' => Hash::make('password'), 'role' => 'admin']
        );

        User::updateOrCreate(
            ['email' => 'user1@example.com'],
            ['name' => 'User One', 'password' => Hash::make('password'), 'role' => 'user']
        );

        Menu::updateOrCreate(['name' => 'Pizza'], [
            'description' => 'Delicious cheese pizza',
            'price' => 12.99,
            'average_rating' => 4.5,
        ]);

        Menu::updateOrCreate(['name' => 'Burger'], [
            'description' => 'Juicy beef burger with cheese',
            'price' => 8.99,
            'average_rating' => 4.2,
        ]);

        Discount::updateOrCreate(['code' => 'SAVE10'], [
            'type' => 'percentage',
            'percent' => 10,
            'amount' => 0,
            'valid_until' => now()->addYear(),
            'active' => true,
        ]);
    }
}