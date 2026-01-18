<?php

namespace Database\Factories;

use App\Models\Card;
use App\Models\User;
use App\Models\Client;
use App\Models\Policy;
use App\Models\CardType;
use App\Models\CardStatus;
use App\Models\CollectorType;
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
        // Parameter Input (percentages with 7 decimals, money with 2 decimals)
        $premiPercentages = round(fake()->randomFloat(7, 0.0000001, 0.3), 7);
        $taxPercentages = round(fake()->randomFloat(7, 0.0000001, 0.3), 7);
        $commisionPercentages = round(fake()->randomFloat(7, 0.0000001, 0.5), 7);
        $discountPercentages = round(fake()->randomFloat(7, 0, 0.1), 7);
        $objectValue = fake()->numberBetween(10000000, 500000000); // bigInteger
        $policyFee = round(fake()->randomFloat(2, 50000, 500000), 2);

        // Calculated Values (all with 2 decimal places for money)
        $productionValue = round($objectValue * ($premiPercentages / 100), 2);
        $premiValue      = $productionValue;
        $commisionBruto  = round($productionValue * ($commisionPercentages / 100), 2);
        $taxValue        = round($commisionBruto * ($taxPercentages / 100), 2);
        $commisionNetto  = round($commisionBruto - $taxValue, 2);
        $discountValue   = round($premiValue * ($discountPercentages / 100), 2);
        $clientPay       = round($premiValue + $policyFee - $discountValue, 2);
        $insurancePay    = round($clientPay - $commisionNetto, 2);

        return [
            'client_id'         => Client::inRandomOrder()->first()?->id ?? Client::factory(),
            'policy_id'         => Policy::inRandomOrder()->first()?->id ?? Policy::factory(),
            'card_type_id'      => CardType::inRandomOrder()->first()?->id,
            'card_status_id'    => CardStatus::inRandomOrder()->first()?->id,
            'parent_card_id'    => null,
            'payment_type_id'   => PaymentType::inRandomOrder()->first()?->id,
            'created_by'        => User::inRandomOrder()->first()?->id ?? User::factory(),

            // Parameter Input
            'object_value'          => $objectValue,
            'premi_percentages'     => $premiPercentages,
            'commision_percentages' => $commisionPercentages,
            'policy_fee'            => $policyFee,
            'discount_percentages'  => $discountPercentages,
            'discount_value'        => $discountValue,

            // Calculated Values
            'production_value' => $productionValue,
            'premi_value'      => $premiValue,
            'commision_bruto'  => $commisionBruto,
            'commision_netto'  => $commisionNetto,
            'tax'              => $taxValue,
            'discount_agent'   => 0.00,
            'insurance_pay'    => $insurancePay,
            'client_pay'       => $clientPay,
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
