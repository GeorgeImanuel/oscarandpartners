<?php

namespace Database\Seeders;

use App\Models\ObjectOccupation;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ObjectOccupationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ObjectOccupation::insert([
            ['name' => 'Bangunan'],
            ['name' => 'Unit'],
            ['name' => 'Rumah'],
            ['name' => 'Apartemen'],
            ['name' => 'Kondo'],
            ['name' => 'Tanah'],
            ['name' => 'Kendaraan'],
            ['name' => 'Lainnya'],
        ]);
    }
}
