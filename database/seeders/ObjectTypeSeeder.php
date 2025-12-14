<?php

namespace Database\Seeders;

use App\Models\ObjectType;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ObjectTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ObjectType::insert([
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
