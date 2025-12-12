<?php

namespace Database\Seeders;

use App\Models\PaymentStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PaymentStatus::insert([
            ['name' => 'Pending', 'color' => '#FFA500'],
            ['name' => 'Completed', 'color' => '#008000'],
            ['name' => 'Failed', 'color' => '#be0000'],
            ['name' => 'Refunded', 'color' => '#7b7b7b'],
        ]);
    }
}
