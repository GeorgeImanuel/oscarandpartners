<?php

namespace Database\Seeders;

use App\Models\Coverage;
use App\Models\Policy;
use App\Models\Insurance;
use App\Models\ObjectOccupation;
use App\Models\ObjectModel;
use App\Models\PolicyCoverage;
use App\Models\PolicyInsurance;
use App\Models\PolicyObject;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PolicySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($j = 1; $j <= 20; $j++) {

            $policy = Policy::factory()->create();
            $coverages = Coverage::all();  
            // Coverage
            for ($i = 1; $i <= 3; $i++) {
                PolicyCoverage::create([
                    'policy_id'   => $policy->id,
                    'coverage_code' => $coverages[$i - 1]->code,
                    'percentage'  => random_int(1, 10) / 100,
                ]);
            }

            // Insurance
            $randomInsurances = Insurance::all()->random(rand(1, 3));
            $insuranceCount = $randomInsurances->count();

            // Calculate percentage splits in decimal format
            if ($insuranceCount == 1) {
                $percentages = [1.00];
            } elseif ($insuranceCount == 2) {
                $percentages = [0.60, 0.40];
            } else { // 3
                $percentages = [0.60, 0.30, 0.10];
            }

            foreach ($randomInsurances as $index => $insurance) {
                PolicyInsurance::create([
                    'policy_id'    => $policy->id,
                    'insurance_id' => $insurance->id,
                    'is_main'      => $index === 0,
                    'percentage'   => $percentages[$index],
                ]);
            }

            // Object
            $objects = ObjectModel::all()->random(rand(1, 3));
            foreach ($objects as $object) {
                PolicyObject::create([
                    'policy_id' => $policy->id,
                    'object_id' => $object->id,
                ]);
            }
        }
    }
}
