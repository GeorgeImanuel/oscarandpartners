<?php

namespace Database\Seeders;

use App\Models\CardStatus;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CardStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CardStatus::insert([
            ['id' => 1, 'name' => 'Active', 'color' => '#008000'],
            ['id' => 2, 'name' => 'Gress Period', 'color' => '#FFA500'],
            ['id' => 3, 'name' => 'Lewat Deadline', 'color' => '#BE0000'],
        ]);
    }
}
