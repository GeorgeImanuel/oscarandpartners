<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Policy;
use Illuminate\Database\Seeder;
use Database\Seeders\CardSeeder;
use Database\Seeders\PolicySeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

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
        $this->call(ObjectTypeSeeder::class);
        $this->call(PaymentTypeSeeder::class);
        $this->call(PaymentStatusSeeder::class);
        $this->call(CardStatusesSeeder::class);
        $this->call(CollectorTypeSeeder::class);
        $this->call(BankSeeder::class);

        // Application Data Seeders
        $this->call(InsuranceSeeder::class);
        $this->call(PolicySeeder::class);
        $this->call(RelationSeeder::class);
        $this->call(ClientSeeder::class);
        $this->call(PolicySeeder::class);
        $this->call(CardSeeder::class);        
    }
}
