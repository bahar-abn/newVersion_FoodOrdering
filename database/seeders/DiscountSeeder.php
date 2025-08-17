<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Discount;

class DiscountSeeder extends Seeder
{
    public function run(): void
    {
        Discount::create([
            'code' => 'SUMMER25',
            'amount' => 25,
            'type' => 'percentage',
            'valid_from' => now(),
            'valid_to' => now()->addDays(30),
        ]);

        Discount::create([
            'code' => 'FIXED10',
            'amount' => 10,
            'type' => 'fixed',
            'valid_from' => now(),
            'valid_to' => now()->addDays(15),
        ]);
    }
}
