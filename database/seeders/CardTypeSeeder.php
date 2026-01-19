<?php

namespace Database\Seeders;

use App\Models\CardType;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CardTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CardType::insert([
            ['id' => 1, 'name' => 'New', 'color' => '#0084ff'],
            ['id' => 2, 'name' => 'Renewal', 'color' => '#008000'],
            ['id' => 3, 'name' => 'Endors', 'color' => '#FFA500'],
        ]);
    }
}
