<?php

namespace Database\Factories;

use App\Models\Policy;
use App\Models\Coverage;
use App\Models\Currency;
use App\Models\ObjectModel;
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
        $startDate = fake()->dateTimeBetween('-1 year', 'now');

        return [
            'policy_number' => 'POL-' . fake()->unique()->numerify('######'),
            'currency_code' => Currency::inRandomOrder()->first()?->code ?? Currency::factory(),
            'coverage_percentages' => 0,
            'started_date'  => $startDate,
            'ended_date'    => fake()->dateTimeBetween($startDate, '+2 years'),
        ];
    }
}
