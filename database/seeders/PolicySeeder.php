<?php

namespace Database\Seeders;

use App\Models\Policy;
use App\Models\Insurance;
use App\Models\ObjectType;
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
        Policy::factory()->count(10)->create();

        $policies = Policy::all();
        $insurances = Insurance::all();
        foreach ($policies as $policy) {
            // Attach 1 to 3 random insurances to each policy
            $randomInsurances = $insurances->random(rand(1, 3));
            foreach ($randomInsurances as $index => $insurance) {
                PolicyInsurance::create([
                    'policy_id' => $policy->id,
                    'insurance_id' => $insurance->id,
                    'is_main' => $index === 0, // First insurance is main
                    'percentage' => $index === 0 ? 100 : 0, // Main insurance gets 100%
                ]);
            }
        }
    }
}
