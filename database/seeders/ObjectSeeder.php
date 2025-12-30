<?php

namespace Database\Seeders;

use App\Models\ObjectModel;
use App\Models\ObjectPart;
use App\Models\Part;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ObjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ObjectModel::factory()->count(10)->create()->each(function ($object) {
            // Assign random parts to each object (1-3 parts)
            $parts = Part::inRandomOrder()->limit(rand(1, 3))->pluck('id');
            
            foreach ($parts as $partId) {
                ObjectPart::create([
                    'object_id' => $object->id,
                    'part_id' => $partId,
                    'value'     => rand(100, 10000)*10000000, // Random value for the part
                ]);
            }
        });
    }
}
