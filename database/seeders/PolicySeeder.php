<?php

namespace Database\Seeders;

use App\Models\Policy;
use App\Models\Insurance;
use App\Models\ObjectType;
use App\Models\ObjectModel;
use App\Models\PolicyInsurance;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PolicySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $objects = ObjectModel::all();

        foreach ($objects as $object) {
            // Create a main policy for each object
            $policy = Policy::factory()->create([
                'object_id' => $object->id,
            ]);

            $randomInsurances = Insurance::all()->random(rand(1, 3));
            foreach ($randomInsurances as $index => $insurance) {

                PolicyInsurance::create([
                    'policy_id' => $policy->id,
                    'insurance_id' => $insurance->id,
                    'is_main' => $index === 0,
                    'percentage' => $index === 0 ? 100 : 0,
                ]);
            }
        }
    }
}
