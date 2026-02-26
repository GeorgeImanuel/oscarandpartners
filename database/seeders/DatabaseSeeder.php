<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\Policy;
use App\Models\User;
use Database\Seeders\CardSeeder;
use Database\Seeders\CardStatusSeeder;
use Database\Seeders\CardTypeSeeder;
use Database\Seeders\CoverageSeeder;
use Database\Seeders\CurrencySeeder;
use Database\Seeders\InterestSeeder;
use Database\Seeders\ObjectSeeder;
use Database\Seeders\PolicySeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Validation\Rules\In;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Master Data Seeders
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(ObjectOccupationSeeder::class);
        $this->call(PaymentTypeSeeder::class);
        $this->call(PaymentStatusSeeder::class);
        $this->call(CardStatusSeeder::class);
        $this->call(CardTypeSeeder::class);
        $this->call(BankSeeder::class);
        $this->call(CurrencySeeder::class);

        // Application Data Seeders
        $this->call(InterestSeeder::class);
        $this->call(CoverageSeeder::class);
        
        $this->call(InsuranceSeeder::class);
        $this->call(RelationSeeder::class);
        $this->call(ClientSeeder::class);
        if(env('APP_ENV') === 'local') {
            $this->call(ObjectSeeder::class);
            $this->call(PolicySeeder::class);
            $this->call(CardSeeder::class);        
            $this->call(PaymentSeeder::class);
        }
    }
}
