<?php

namespace Database\Factories;

use App\Models\ObjectOccupation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ObjectModel>
 */
class ObjectModelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'object_occupation_id' => ObjectOccupation::inRandomOrder()->first()?->id ?? ObjectOccupation::factory(),
            'name' => 'Object '. fake()->numberBetween(1, 1000),
            'keterangan' => fake()->sentence(),
        ];
    }
}
