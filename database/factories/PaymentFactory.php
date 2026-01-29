<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\Card;
use App\Models\User;
use App\Models\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'card_id' => Card::inRandomOrder()->first()?->id ?? Card::factory(),
            'sequence' => 1,
            'created_by' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'amount' => fake()->randomFloat(2, 1000000, 50000000),
            
            // CLIENT PAYMENT
            'payment_status_id' => PaymentStatus::inRandomOrder()->first()?->id,
            'deadline_at' => fake()->dateTimeBetween('now', '+30 days'),
            'paid_at' => fake()->boolean(50) ? fake()->dateTimeBetween('-30 days', 'now') : null,
            'client_evident_file' => fake()->boolean(50) ? 'payment_' . fake()->uuid() . '.pdf' : null,
            'client_evident_number' => fake()->boolean(50) ? 'PAY-' . fake()->numerify('##########') : null,
            
            // INSURANCE PAYMENT
            'insurance_payment_status_id' => fake()->boolean(60) ? PaymentStatus::inRandomOrder()->first()?->id : null,
            'insurance_paid_at' => fake()->boolean(50) ? fake()->dateTimeBetween('-30 days', 'now') : null,
            'insurance_evident_file' => fake()->boolean(50) ? 'insurance_' . fake()->uuid() . '.pdf' : null,
            'insurance_evident_number' => fake()->boolean(50) ? 'INS-' . fake()->numerify('##########') : null,
        ];
    }

    /**
     * Indicate that the payment has been paid.
     */
    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'paid_at' => fake()->dateTimeBetween('-30 days', 'now'),
            'client_evident_file' => 'payment_' . fake()->uuid() . '.pdf',
            'client_evident_number' => 'PAY-' . fake()->numerify('##########'),
        ]);
    }

    /**
     * Indicate that the payment is unpaid.
     */
    public function unpaid(): static
    {
        return $this->state(fn (array $attributes) => [
            'paid_at' => null,
            'client_evident_file' => null,
            'client_evident_number' => null,
        ]);
    }
}
