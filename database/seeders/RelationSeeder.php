<?php

namespace Database\Seeders;

use App\Models\Relation;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RelationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Relation::insert([
            ['code' => 'REL-001', 'name' => 'Family'],
            ['code' => 'REL-002', 'name' => 'Friends'],
            ['code' => 'REL-003', 'name' => 'Colleagues'],
            ['code' => 'REL-004', 'name' => 'Business Partners'],
            ['code' => 'REL-005', 'name' => 'Others'],
        ]);
    }
}
