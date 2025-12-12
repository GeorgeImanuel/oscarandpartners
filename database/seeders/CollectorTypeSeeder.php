<?php

namespace Database\Seeders;

use App\Models\CollectorType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CollectorTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CollectorType::insert([
            ['name' => 'Owner'],
            ['name' => 'Staff'],
        ]);
    }
}
