<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Relation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    protected $model = Client::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'relation_id' => fake()->boolean(70) ? (Relation::inRandomOrder()->first()?->id ?? null) : null,
            'id_name' => fake()->name(),
            'id_number' => fake()->optional()->numerify('################'),
            'id_address' => fake()->optional()->address(),
        ];
    }
}
