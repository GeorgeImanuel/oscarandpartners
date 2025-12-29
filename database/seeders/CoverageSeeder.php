<?php

namespace Database\Seeders;

use App\Models\Coverage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CoverageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Coverage::insert([
            ['code' => 'PAR', 'name' => 'Portion Of The Risk'],
            ['code' => 'FIR', 'name' => 'Fire and Extended Coverage'],
            ['code' => 'EQ', 'name' => 'Earthquake'],
            ['code' => 'CIS',  'name' => 'Civil Insurrection'],
            ['code' => 'THEFT',  'name' => 'Theft'],
            ['code' => 'FLD',  'name' => 'Flood'],
            ['code' => 'WIND',  'name' => 'Windstorm'],
            ['code' => 'LIAB',  'name' => 'Liability'],
        ]);
    }
}
