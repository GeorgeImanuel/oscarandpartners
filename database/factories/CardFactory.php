<?php

namespace Database\Factories;

use App\Models\Card;
use App\Models\Client;
use App\Models\Policy;
use App\Models\CardStatus;
use App\Models\PaymentStatus;
use App\Models\PaymentType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Card>
 */
class CardFactory extends Factory
{
    protected $model = Card::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Parameter Input
        $premiPercentages = fake()->randomFloat(2, 0.5, 5.0);
        $taxPercentages = fake()->randomFloat(2, 2, 5);
        $commisionPercentages = fake()->randomFloat(2, 10, 30);
        $objectValue = fake()->randomFloat(2, 10000000, 500000000);
        $discount = fake()->randomFloat(2, 0, 1000000);

        // Calculated Values
        $productionValue = $objectValue * ($premiPercentages / 100);
        $premi           = $productionValue;
        $commisionBruto  = $productionValue * ($commisionPercentages / 100);
        $tax             = $commisionBruto * ($taxPercentages / 100);
        $commisionNetto  = $commisionBruto - $tax;
        $insurancePay    = $premi - $commisionNetto;

        return [
            'client_id' => Client::inRandomOrder()->first()?->id ?? Client::factory(),
            'policy_id' => Policy::inRandomOrder()->first()?->id ?? Policy::factory(),
            'card_status_id' => CardStatus::inRandomOrder()->first()?->id,
            'parent_card_id' => null,
            'payment_type_id' => PaymentType::inRandomOrder()->first()?->id,
            'created_by' => User::inRandomOrder()->first()?->id ?? User::factory(),

            // Parameter Input
            'premi_percentages' => $premiPercentages,
            'tax_percentages' => $taxPercentages,
            'commision_percentages' => $commisionPercentages,
            'object_value' => $objectValue,
            'discount' => $discount,

            // Calculated Values
            'production_value' => $productionValue,
            'premi' => $premi,
            'commision_bruto' => $commisionBruto,
            'commision_netto' => $commisionNetto,
            'tax' => $tax,
            'insurance_pay' => $insurancePay,

            // Insurance Payment
            'insurance_payment_status_id' => PaymentStatus::inRandomOrder()->first()?->id,
            'insurance_paid_at' => fake()->boolean(60) ? fake()->dateTimeBetween('-6 months', 'now') : null,
            'insurance_evident_file' => fake()->boolean(60) ? 'evidence_' . fake()->uuid() . '.pdf' : null,
            'insurance_evident_number' => fake()->boolean(60) ? 'TRF-' . fake()->numerify('##########') : null,
        ];
    }

    /**
     * Indicate that the card has been paid by insurance.
     */
    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'insurance_paid_at' => fake()->dateTimeBetween('-6 months', 'now'),
            'insurance_evident_file' => 'evidence_' . fake()->uuid() . '.pdf',
            'insurance_evident_number' => 'TRF-' . fake()->numerify('##########'),
        ]);
    }

    /**
     * Indicate that the card is unpaid.
     */
    public function unpaid(): static
    {
        return $this->state(fn (array $attributes) => [
            'insurance_paid_at' => null,
            'insurance_evident_file' => null,
            'insurance_evident_number' => null,
        ]);
    }
}
