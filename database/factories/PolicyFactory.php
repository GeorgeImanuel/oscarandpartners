<?php

namespace Database\Factories;

use App\Models\Insurance;
use App\Models\ObjectType;
use App\Models\Policy;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Policy>
 */
class PolicyFactory extends Factory
{
    protected $model = Policy::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Randomly assign parent policy from existing policies (30% chance)
        $parentPolicyId = null;
        if (Policy::count() > 0 && fake()->boolean(30)) {
            $parentPolicyId = Policy::inRandomOrder()->first()->id;
        }

        return [
            'insurance_id'     => Insurance::factory(),
            'parent_policy_id' => $parentPolicyId,
            'object_type_id'   => ObjectType::factory(),
            'policy_number'    => 'POL-' . fake()->unique()->numerify('######'),
            'object'           => fake()->words(3, true) . ' ' . fake()->randomElement(['Building', 'Vehicle', 'Property', 'Equipment']),
            'object_address'   => fake()->address(),
            'object_value'     => fake()->randomFloat(2, 50000000, 5000000000),
        ];
    }

}
