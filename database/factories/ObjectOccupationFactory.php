<?php

namespace Database\Factories;

use App\Models\ObjectOccupation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ObjectOccupation>
 */
class ObjectOccupationFactory extends Factory
{
    protected $model = ObjectOccupation::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement([
                'Properti',
                'Kendaraan',
                'Bangunan',
                'Pabrik',
                'Gudang',
                'Mesin',
                'Elektronik',
                'Inventaris'
            ]),
        ];
    }
}
