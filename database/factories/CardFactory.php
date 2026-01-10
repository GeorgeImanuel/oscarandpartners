<?php

namespace Database\Factories;

use App\Models\Card;
use App\Models\User;
use App\Models\Client;
use App\Models\Policy;
use App\Models\CardType;
use App\Models\CardStatus;
use App\Models\PaymentType;
use App\Models\PaymentStatus;
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
        $premiPercentages = fake()->randomFloat(4, 0, 0.3);
        $taxPercentages = fake()->randomFloat(4, 0, 0.3);
        $commisionPercentages = fake()->randomFloat(4, 0, 0.5);
        $objectValue = fake()->randomFloat(2, 10000000, 500000000);
        $policyFee = fake()->randomFloat(2, 50000, 500000);
        $discount = fake()->randomFloat(2, 0, 1000000);

        // Calculated Values
        $productionValue = $objectValue * ($premiPercentages / 100);
        $premiValue      = $productionValue;
        $commisionBruto  = $productionValue * ($commisionPercentages / 100);
        $taxValue        = $commisionBruto * ($taxPercentages / 100);
        $commisionNetto  = $commisionBruto - $taxValue;
        $clientPay       = $premiValue + $policyFee - $discount;
        $insurancePay    = $clientPay - $commisionNetto;

        return [
            'client_id'       => Client::inRandomOrder()->first()?->id ?? Client::factory(),
            'policy_id'       => Policy::inRandomOrder()->first()?->id ?? Policy::factory(),
            'card_type_id'    => CardType::inRandomOrder()->first()?->id,
            'card_status_id'  => CardStatus::inRandomOrder()->first()?->id,
            'parent_card_id'  => null,
            'payment_type_id' => PaymentType::inRandomOrder()->first()?->id,
            'created_by'      => User::inRandomOrder()->first()?->id ?? User::factory(),

            // Parameter Input
            'object_value'          => $objectValue,
            'policy_fee'            => $policyFee,
            'premi_percentages'     => $premiPercentages,
            'commision_percentages' => $commisionPercentages,
            'tax_percentages'       => $taxPercentages,
            'discount'              => $discount,

            // Calculated Values
            'production_value' => $productionValue,
            'premi_value'      => $premiValue,
            'commision_bruto'  => $commisionBruto,
            'commision_netto'  => $commisionNetto,
            'tax_value'        => $taxValue,
            'client_pay'       => $clientPay,
            'insurance_pay'    => $insurancePay,

            // Insurance Payment
            'insurance_payment_status_id' => PaymentStatus::inRandomOrder()->first()?->id,
            'insurance_paid_at'           => fake()->boolean(60) ? fake()->dateTimeBetween('-6 months', 'now') : null,
            'insurance_evident_file'      => fake()->boolean(60) ? 'evidence_' . fake()->uuid() . '.pdf' : null,
            'insurance_evident_number'    => fake()->boolean(60) ? 'TRF-' . fake()->numerify('##########') : null,
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
