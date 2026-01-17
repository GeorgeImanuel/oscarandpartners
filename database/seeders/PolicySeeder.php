<?php

namespace Database\Seeders;

use App\Models\Policy;
use App\Models\Insurance;
use App\Models\ObjectOccupation;
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
            
            // Calculate percentage splits in decimal format
            $insuranceCount = $randomInsurances->count();
            $percentages = [];
            
            if ($insuranceCount == 1) {
                $percentages = [1.00]; // 100%
            } elseif ($insuranceCount == 2) {
                $percentages = [0.60, 0.40]; // 60%, 40%
            } else { // 3
                $percentages = [0.60, 0.30, 0.10]; // 60%, 30%, 10%
            }
            
            foreach ($randomInsurances as $index => $insurance) {
                PolicyInsurance::create([
                    'policy_id' => $policy->id,
                    'insurance_id' => $insurance->id,
                    'is_main' => $index === 0,
                    'percentage' => $percentages[$index],
                ]);
            }
        }
    }
}
