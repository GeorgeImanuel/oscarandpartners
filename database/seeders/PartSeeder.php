<?php

namespace Database\Seeders;

use App\Models\Part;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Part::insert([
            ['name' => 'Build'],
            ['name' => 'Mesin'],
            ['name' => 'Gudang'],
            ['name' => 'Stock'],
            ['name' => 'Machine'],
            ['name' => 'Klin Dry'],
        ]);
    }
}
