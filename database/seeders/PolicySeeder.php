<?php

namespace Database\Seeders;

use App\Models\Policy;
use App\Models\Insurance;
use App\Models\ObjectType;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PolicySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Policy::factory()->count(10)->create();
    }
}
