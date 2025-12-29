<?php

namespace Database\Seeders;

use App\Models\ProductionCard;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductionCardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProductionCard::factory()->count(20)->create();
    }
}
