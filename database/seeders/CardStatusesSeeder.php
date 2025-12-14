<?php

namespace Database\Seeders;

use App\Models\CardStatus;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CardStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CardStatus::insert([
            ['name' => 'New', 'color' => '#0084ff'],
            ['name' => 'Renewal', 'color' => '#008000'],
            ['name' => 'Endors', 'color' => '#FFA500'],
        ]);
    }
}
