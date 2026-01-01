<?php

namespace Database\Seeders;

use App\Models\Payment;
use App\Models\Card;
use App\Models\User;
use App\Models\CollectorType;
use App\Models\PaymentStatus;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productionCards = Card::all();

        foreach ($productionCards as $card) {
            // If payment_type_id = 1, create 1 payment, otherwise create multiple (2-4)
            $paymentCount = $card->payment_type_id == 1 ? 1 : rand(2, 4);
            
            // Calculate amount per payment
            $totalAmount = $card->premi; // or whatever field represents total payment
            $amountPerPayment = $totalAmount / $paymentCount;

            for ($i = 1; $i <= $paymentCount; $i++) {
                Payment::create([
                    'production_card_id'       => $card->id,
                    'sequence'                 => $i,
                    'created_by'               => User::inRandomOrder()->first()->id,
                    'collected_by'             => User::inRandomOrder()->first()->id,
                    'collector_type_id'        => CollectorType::inRandomOrder()->first()->id,
                    'amount'                   => round($amountPerPayment, 2),
                    'note'                     => fake()->optional()->sentence(),
                    'client_payment_status_id' => PaymentStatus::inRandomOrder()->first()->id,
                    'deadline_at'              => now()->addDays($i * 30),
                    'paid_at'                  => fake()->boolean(60) ? now()->subDays(rand(1, 30)) : null,
                    'client_evident_file'      => fake()->boolean(60) ? 'payment_' . fake()->uuid() . '.pdf' : null,
                    'client_evident_number'    => fake()->boolean(60) ? 'PAY-' . fake()->numerify('##########') : null,
                ]);
            }
        }
    }
}
