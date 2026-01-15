<?php

namespace Database\Seeders;

use App\Models\ObjectModel;
use App\Models\Interest;
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
            // Assign random interests to each object (1-3 interests)
            $interests = Interest::inRandomOrder()->limit(rand(1, 3))->pluck('id');
            
            foreach ($interests as $interestId) {
                $object->interests()->attach($interestId, [
                    'value' => rand(100, 10000) * 10000000, // Random value for the interest
                ]);
            }
        });
    }
}
