<?php

namespace Database\Factories;

use App\Models\ProductionCard;
use App\Models\Client;
use App\Models\Policy;
use App\Models\CardStatus;
use App\Models\PaymentStatus;
use App\Models\PaymentType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductionCard>
 */
class ProductionCardFactory extends Factory
{
    protected $model = ProductionCard::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('-1 year', 'now');
        $endDate = (clone $startDate)->modify('+1 year');

        // Base calculations
        $premiumGross = fake()->randomFloat(2, 10000000, 500000000);
        $commissionPct = fake()->randomFloat(3, 0.5, 5.0);
        $commissionPercentage = fake()->randomFloat(2, 10, 30);
        $taxPercentage = fake()->randomFloat(2, 2, 5);
        $discount = fake()->randomFloat(2, 0, 10);
        $adminFee = fake()->randomFloat(2, 50000, 500000);

        // Calculate premi_amount (premi produksi)
        $premiAmount = $premiumGross * ($commissionPct / 100);

        // Calculate polis_amount
        $polisAmount = $adminFee;

        // Calculate client_total
        $clientTotal = $premiAmount + $polisAmount;

        // Calculate commission_amount_initial
        $commissionAmountInitial = $clientTotal * ($commissionPercentage / 100);

        // Calculate tax_amount
        $taxAmount = $commissionAmountInitial * ($taxPercentage / 100);

        // Calculate discount_amount
        $discountAmount = $commissionAmountInitial * ($discount / 100);

        // Calculate commission_total
        $commissionTotal = $commissionAmountInitial - $taxAmount - $discountAmount;

        // Calculate net_to_insurance
        $netToInsurance = $clientTotal - $commissionTotal;

        // Calculate commission_amount (same as initial for simplicity)
        $commissionAmount = $commissionAmountInitial;

        return [
            'client_id'      => Client::inRandomOrder()->first()?->id,
            'policy_id'      => Policy::inRandomOrder()->first()?->id,
            'card_status_id' => CardStatus::inRandomOrder()->first()?->id,
            'created_by'     => User::inRandomOrder()->first()?->id,
            'start_date'     => $startDate,
            'end_date'       => $endDate,

            'commission_percentage' => $commissionPercentage,
            'tax_percentage'        => $taxPercentage,

            'premium_gross'     => $premiumGross,
            'discount'          => $discount,
            'admin_fee'         => $adminFee,
            'commission_pct'    => $commissionPct,
            'commission_amount' => $commissionAmount,
            'net_to_insurance'  => $netToInsurance,

            'premi_amount' => $premiAmount,
            'polis_amount' => $polisAmount,
            'client_total' => $clientTotal,

            'commission_amount_initial' => $commissionAmountInitial,
            'tax_amount'                => $taxAmount,
            'discount_amount'           => $discountAmount,
            'commission_total'          => $commissionTotal,

            'insurance_payment_status_id' => PaymentStatus::inRandomOrder()->first()?->id,
            'insurance_paid_at'           => fake()->boolean(60) ? fake()->dateTimeBetween($startDate, 'now') : null,
            'insurance_evident_file'      => fake()->boolean(60) ? 'evidence_' . fake()->uuid() . '.pdf' : null,
            'insurance_evident_number'    => fake()->boolean(60) ? 'TRF-' . fake()->numerify('##########') : null,

            'payment_type_id' => PaymentType::inRandomOrder()->first()?->id,
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
