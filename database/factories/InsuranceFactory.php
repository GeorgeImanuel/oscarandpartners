<?php

namespace Database\Factories;

use App\Models\Insurance;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Insurance>
 */
class InsuranceFactory extends Factory
{
    protected $model = Insurance::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company() . ' Insurance',
            'bank_account' => fake()->bankAccountNumber(),
            'va_number' => fake()->numerify('################'),
            'default_commission_pct' => fake()->randomFloat(2, 10, 30),
        ];
    }
}
