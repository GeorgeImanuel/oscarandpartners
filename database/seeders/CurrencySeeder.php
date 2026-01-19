<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currencies = [
            ['code' => 'IDR', 'name' => 'Indonesian Rupiah'],
            ['code' => 'USD', 'name' => 'US Dollar'],
            ['code' => 'EUR', 'name' => 'Euro'],
            ['code' => 'GBP', 'name' => 'British Pound Sterling'],
            ['code' => 'JPY', 'name' => 'Japanese Yen'],
            ['code' => 'SGD', 'name' => 'Singapore Dollar'],
            ['code' => 'AUD', 'name' => 'Australian Dollar'],
            ['code' => 'CNY', 'name' => 'Chinese Yuan'],
        ];

        foreach ($currencies as $currency) {
            Currency::firstOrCreate(
                ['code' => $currency['code']],
                ['name' => $currency['name']]
            );
        }
    }
}
